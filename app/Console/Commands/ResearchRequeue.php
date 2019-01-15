<?php

namespace Koodilab\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\Dispatcher as Bus;
use Koodilab\Console\Behaviors\PrependTimestamp;
use Koodilab\Jobs\Research as ResearchJob;
use Koodilab\Models\Research;
use Symfony\Component\Console\Input\InputArgument;

class ResearchRequeue extends Command
{
    use PrependTimestamp;

    /**
     * {@inheritdoc}
     */
    protected $name = 'requeue:research';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Requeue the research';

    /**
     * The bus instance.
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
            $ids = Research::pluck('id');
        }

        foreach ($ids as $id) {
            $this->requeueResearch($id);
        }
    }

    /**
     * Requeue the research.
     *
     * @param int $id
     */
    protected function requeueResearch($id)
    {
        /** @var Research $research */
        $research = Research::find($id);

        if ($research) {
            $this->bus->dispatch(
                (new ResearchJob($research->id))->delay($research->remaining)
            );

            $this->info(
                $this->prependTimestamp("The research [{$id}] has been requeued!")
            );
        } else {
            $this->error(
                $this->prependTimestamp("The research [{$id}] not found.")
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getArguments()
    {
        return [
            ['id', InputArgument::IS_ARRAY, 'The ID of the research'],
        ];
    }
}
