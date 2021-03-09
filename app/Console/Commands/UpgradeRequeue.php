<?php

namespace App\Console\Commands;

use App\Console\Behaviors\PrependTimestamp;
use App\Jobs\Upgrade as UpgradeJob;
use App\Models\Upgrade;
use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\Dispatcher as Bus;
use Symfony\Component\Console\Input\InputArgument;

class UpgradeRequeue extends Command
{
    use PrependTimestamp;

    /**
     * {@inheritdoc}
     */
    protected $name = 'requeue:upgrade';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Requeue the upgrade';

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
            $ids = Upgrade::pluck('id');
        }

        foreach ($ids as $id) {
            $this->requeueUpgrade($id);
        }
    }

    /**
     * Requeue the upgrade.
     *
     * @param int $id
     */
    protected function requeueUpgrade($id)
    {
        /** @var Upgrade $upgrade */
        $upgrade = Upgrade::find($id);

        if ($upgrade) {
            $this->bus->dispatch(
                (new UpgradeJob($upgrade->id))->delay($upgrade->remaining)
            );

            $this->info(
                $this->prependTimestamp("The upgrade [{$id}] has been requeued!")
            );
        } else {
            $this->error(
                $this->prependTimestamp("The upgrade [{$id}] not found.")
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getArguments()
    {
        return [
            ['id', InputArgument::IS_ARRAY, 'The ID of the upgrade'],
        ];
    }
}
