<?php

namespace App\Game;

use App\Events\PlanetUpdated;
use App\Jobs\Train as TrainJob;
use App\Models\Grid;
use App\Models\Training;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Contracts\Bus\Dispatcher as Bus;
use Illuminate\Contracts\Events\Dispatcher as Event;

class TrainingManager
{
    /**
     * The bus instance.
     *
     * @var Bus
     */
    protected $bus;

    /**
     * The event instance.
     *
     * @var Event
     */
    protected $event;

    /**
     * Constructor.
     */
    public function __construct(Bus $bus, Event $event)
    {
        $this->bus = $bus;
        $this->event = $event;
    }

    /**
     * Create.
     *
     * @param int $quantity
     *
     * @return Training
     */
    public function create(Grid $grid, Unit $unit, $quantity)
    {
        $grid->planet->user->decrementEnergy($quantity * $unit->train_cost);

        $training = Training::create([
            'grid_id' => $grid->id,
            'unit_id' => $unit->id,
            'quantity' => $quantity,
            'ended_at' => Carbon::now()->addSeconds($quantity * $unit->train_time),
        ]);

        $this->bus->dispatch(
            (new TrainJob($training->id))->delay($training->remaining)
        );

        return $training;
    }

    /**
     * Finish.
     *
     * @throws \Exception|\Throwable
     */
    public function finish(Training $training)
    {
        /** @var \App\Models\Population $population */
        $population = $training->grid->planet->populations()->firstOrNew([
            'unit_id' => $training->unit->id,
        ]);

        $population->setRelations([
            'planet' => $training->grid->planet,
            'unit' => $training->unit,
        ])->incrementQuantity($training->quantity);

        $training->delete();

        $this->event->dispatch(
            new PlanetUpdated($training->grid->planet_id)
        );
    }

    /**
     * Cancel.
     *
     * @throws \Exception|\Throwable
     */
    public function cancel(Training $training)
    {
        $totalTime = $training->quantity * $training->unit->train_time;
        $totalCost = $training->quantity * $training->unit->train_cost;

        $energy = round(
            $training->remaining / $totalTime * $totalCost
        );

        $training->grid->planet->user->incrementEnergy($energy);

        $training->delete();
    }
}
