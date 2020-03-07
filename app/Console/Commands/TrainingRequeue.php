<?php

namespace Koodilab\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\Dispatcher as Bus;
use Koodilab\Console\Behaviors\PrependTimestamp;
use Koodilab\Jobs\Train as TrainJob;
use Koodilab\Models\Training;
use Symfony\Component\Console\Input\InputArgument;

class TrainingRequeue extends Command
{
    use PrependTimestamp;

    /**
     * {@inheritdoc}
     */
    protected $name = 'requeue:training';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Requeue the training';

    /**
     * The bus instance.
     *
     * @var Bus
     */
    protected $bus;

    /**
     * Constructor.
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
            $ids = Training::pluck('id');
        }

        foreach ($ids as $id) {
            $this->requeueTraining($id);
        }
    }

    /**
     * Requeue the training.
     *
     * @param int $id
     */
    protected function requeueTraining($id)
    {
        /** @var Training $training */
        $training = Training::find($id);

        if ($training) {
            $this->bus->dispatch(
                (new TrainJob($training->id))->delay($training->remaining)
            );

            $this->info(
                $this->prependTimestamp("The training [{$id}] has been requeued!")
            );
        } else {
            $this->error(
                $this->prependTimestamp("The training [{$id}] not found.")
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getArguments()
    {
        return [
            ['id', InputArgument::IS_ARRAY, 'The ID of the training'],
        ];
    }
}
