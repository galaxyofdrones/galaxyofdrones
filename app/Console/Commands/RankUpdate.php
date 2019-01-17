<?php

namespace Koodilab\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Koodilab\Console\Behaviors\PrependTimestamp;
use Koodilab\Models\Rank;
use Koodilab\Models\User;

class RankUpdate extends Command
{
    use PrependTimestamp;

    /**
     * {@inheritdoc}
     */
    protected $signature = 'rank:update';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Update the ranks';

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
        $this->info(
            $this->prependTimestamp('Updating ranks...')
        );

        $this->database->transaction(function () {
            $users = User::whereNotNull('started_at')->get();

            foreach ($users as $user) {
                tap(Rank::firstOrNew([
                    'user_id' => $user->id,
                ], [
                    'mission_count' => $user->missionLogs()->count(),
                    'expedition_count' => $user->expeditionLogs()->count(),
                    'planet_count' => $user->planets()->count(),
                    'winning_battle_count' => $user->winningBattleLogCount(),
                    'losing_battle_count' => $user->losingBattleLogCount(),
                ]))->save();
            }
        });

        $this->info(
            $this->prependTimestamp('Update complete!')
        );
    }
}
