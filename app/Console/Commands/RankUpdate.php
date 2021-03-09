<?php

namespace App\Console\Commands;

use App\Console\Behaviors\PrependTimestamp;
use App\Models\Rank;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;

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
     */
    public function __construct(DatabaseManager $database)
    {
        parent::__construct();

        $this->database = $database;
    }

    /**
     * Execute the console command.
     *
     * @throws \Exception|\Throwable
     */
    public function handle()
    {
        $this->info(
            $this->prependTimestamp('Updating ranks...')
        );

        $users = User::whereNotNull('started_at')->get();

        foreach ($users as $user) {
            $this->database->transaction(function () use ($user) {
                Rank::firstOrNew([
                    'user_id' => $user->id,
                ])->fill([
                    'mission_count' => $user->missionLogs()->count(),
                    'expedition_count' => $user->expeditionLogs()->count(),
                    'planet_count' => $user->planets()->count(),
                    'winning_battle_count' => $user->winningBattleLogCount(),
                    'losing_battle_count' => $user->losingBattleLogCount(),
                ])->save();
            });
        }

        $this->info(
            $this->prependTimestamp('Update complete!')
        );
    }
}
