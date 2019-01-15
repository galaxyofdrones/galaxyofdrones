<?php

namespace Koodilab\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\Dispatcher as Bus;
use Koodilab\Console\Behaviors\PrependTimestamp;
use Koodilab\Jobs\Move as MoveJob;
use Koodilab\Models\Movement;
use Symfony\Component\Console\Input\InputArgument;

class MovementRequeue extends Command
{
    use PrependTimestamp;

    /**
     * {@inheritdoc}
     */
    protected $name = 'requeue:movement';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Requeue the movement';

    /**
     * The bus instance
     *
     * @var Bus
     */
    protected $bus;

    /**
     * Constructor.
     *
     * @param Bus $bus
     */
    public function __construct(Bus $bus)
    {
        parent::__construct();

        $this->bus = $bus;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ids = $this->argument('id');

        if (count($ids) === 1 && $ids[0] === 'all') {
            $ids = Movement::pluck('id');
        }

        foreach ($ids as $id) {
            $this->requeueMovement($id);
        }
    }

    /**
     * Requeue the movement.
     *
     * @param int $id
     */
    protected function requeueMovement($id)
    {
        /** @var Movement $movement */
        $movement = Movement::find($id);

        if ($movement) {
            $this->bus->dispatch(
                (new MoveJob($movement->id))->delay($movement->remaining)
            );

            $this->info(
                $this->prependTimestamp("The movement [{$id}] has been requeued!")
            );
        } else {
            $this->error(
                $this->prependTimestamp("The movement [{$id}] not found.")
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getArguments()
    {
        return [
            ['id', InputArgument::IS_ARRAY, 'The ID of the movement'],
        ];
    }
}
