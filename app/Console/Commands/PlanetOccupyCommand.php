<?php

namespace Koodilab\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Koodilab\Console\Behaviors\PrependTimestamp;
use Koodilab\Models\Planet;
use Koodilab\Models\User;

class PlanetOccupyCommand extends Command
{
    use PrependTimestamp;

    /**
     * {@inheritdoc}
     */
    protected $signature = 'planet:occupy {planet} {user}';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Occupy a planet';

    /**
     * The database manager instance.
     *
     * @var DatabaseManager
     */
    protected $database;

    /**
     * Constructor.
     *
     * @param DatabaseManager $database
     */
    public function __construct(DatabaseManager $database)
    {
        parent::__construct();

        $this->database = $database;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /** @var Planet $planet */
        $planet = Planet::find($this->argument('planet'));

        /* @var User $user */
        $user = User::find($this->argument('user'));

        if (! $planet) {
            $this->error(
                $this->prependTimestamp("The planet [{$planet->id}] not found.")
            );
        } elseif (! $user) {
            $this->error(
                $this->prependTimestamp("The user [{$user->id}] not found.")
            );
        } elseif (! $user->canOccupy($planet)) {
            $this->error(
                $this->prependTimestamp("The user [{$user->id}] can not occupy the planet [{$planet->id}].")
            );
        } else {
            $this->database->transaction(function () use ($planet, $user) {
                $user->occupy($planet);

                $this->info(
                    $this->prependTimestamp("The user [{$user->id}] has been occupied the planet [{$planet->id}]!")
                );
            });
        }
    }
}
