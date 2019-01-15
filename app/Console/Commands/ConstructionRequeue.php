<?php

namespace Koodilab\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\Dispatcher as Bus;
use Koodilab\Console\Behaviors\PrependTimestamp;
use Koodilab\Jobs\Construction as ConstructionJob;
use Koodilab\Models\Construction;
use Symfony\Component\Console\Input\InputArgument;

class ConstructionRequeue extends Command
{
    use PrependTimestamp;

    /**
     * {@inheritdoc}
     */
    protected $name = 'requeue:construction';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Requeue the construction';

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
            $ids = Construction::pluck('id');
        }

        foreach ($ids as $id) {
            $this->requeueConstruction($id);
        }
    }

    /**
     * Requeue the construction.
     *
     * @param int $id
     */
    protected function requeueConstruction($id)
    {
        /** @var Construction $construction */
        $construction = Construction::find($id);

        if ($construction) {
            $this->bus->dispatch(
                (new ConstructionJob($construction->id))->delay($construction->remaining)
            );

            $this->info(
                $this->prependTimestamp("The construction [{$id}] has been requeued!")
            );
        } else {
            $this->error(
                $this->prependTimestamp("The construction [{$id}] not found.")
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getArguments()
    {
        return [
            ['id', InputArgument::IS_ARRAY, 'The ID of the construction'],
        ];
    }
}
