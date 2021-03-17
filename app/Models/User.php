<?php

namespace App\Models;

use App\Events\UserUpdated;
use App\Support\Util;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * User.
 *
 * @property int                                                                                                       $id
 * @property int|null                                                                                                  $capital_id
 * @property int|null                                                                                                  $current_id
 * @property string                                                                                                    $username
 * @property string                                                                                                    $email
 * @property \Illuminate\Support\Carbon|null                                                                           $email_verified_at
 * @property string                                                                                                    $password
 * @property string|null                                                                                               $remember_token
 * @property bool                                                                                                      $is_enabled
 * @property int                                                                                                       $energy
 * @property int                                                                                                       $solarion
 * @property int                                                                                                       $experience
 * @property int                                                                                                       $production_rate
 * @property float                                                                                                     $penalty_rate
 * @property bool                                                                                                      $is_notification_enabled
 * @property \Illuminate\Support\Carbon|null                                                                           $last_login
 * @property \Illuminate\Support\Carbon|null                                                                           $last_capital_changed
 * @property \Illuminate\Support\Carbon|null                                                                           $last_energy_changed
 * @property \Illuminate\Support\Carbon|null                                                                           $started_at
 * @property \Illuminate\Support\Carbon|null                                                                           $donated_at
 * @property \Illuminate\Support\Carbon|null                                                                           $created_at
 * @property \Illuminate\Support\Carbon|null                                                                           $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\BattleLog[]                                          $attackBattleLogs
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Block[]                                              $blocks
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Bookmark[]                                           $bookmarks
 * @property \App\Models\Planet|null                                                                                   $capital
 * @property \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[]                                       $clients
 * @property \App\Models\Planet|null                                                                                   $current
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\BattleLog[]                                          $defenseBattleLogs
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\ExpeditionLog[]                                      $expeditionLogs
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Expedition[]                                         $expeditions
 * @property int                                                                                                       $capital_change_remaining
 * @property int                                                                                                       $level
 * @property int                                                                                                       $level_experience
 * @property int                                                                                                       $next_level
 * @property int                                                                                                       $next_level_experience
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Message[]                                            $messages
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\MissionLog[]                                         $missionLogs
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Mission[]                                            $missions
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Movement[]                                           $movements
 * @property \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Planet[]                                             $planets
 * @property \App\Models\Rank                                                                                          $rank
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Research[]                                           $researches
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Resource[]                                           $resources
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Shield[]                                             $shields
 * @property \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[]                                        $tokens
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Unit[]                                               $units
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCapitalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCurrentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereDonatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEnergy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereIsEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereIsNotificationEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereLastCapitalChanged($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereLastEnergyChanged($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePenaltyRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereProductionRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereSolarion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUsername($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        Concerns\CanChangeCapital,
        Concerns\CanOccupy,
        Concerns\HasEnergy,
        Concerns\HasExperience,
        Concerns\HasPenaltyRate,
        Concerns\HasResearchable,
        Concerns\HasSolarion,
        Queries\FindAvailableResource,
        Queries\FindAvailableUnits,
        Queries\FindByBlocked,
        Queries\FindByIdOrUsername,
        Queries\FindByUsername,
        Queries\FindExpeditionStar,
        Queries\FindIncomingUserAttackMovements,
        Queries\FindMissionResources,
        Queries\FindNotDonated,
        Queries\FindNotExpiredExpeditions,
        Queries\FindNotExpiredMissions,
        Queries\FindNotExpiredShields,
        Queries\FindPlanetsOrderByName,
        Queries\FindResearchedResources,
        Queries\FindUnitsOrderBySortOrder,
        Queries\IncomingUserAttackMovementCount,
        Queries\LosingBattleLogCount,
        Queries\WinningBattleLogCount,
        Queries\PaginateBattleLogs,
        Queries\PaginateExpeditionLogs,
        Queries\PaginateMessages,
        Queries\PaginateMissionLogs,
        Queries\PaginatePlanets,
        Relations\HasOneRank,
        Relations\HasManyBlock,
        Relations\HasManyBookmark,
        Relations\HasManyPlanet,
        Relations\HasManyMovement,
        Relations\HasManyResearch,
        Relations\HasManyMission,
        Relations\HasManyMissionLog,
        Relations\HasManyExpedition,
        Relations\HasManyExpeditionLog;

    /**
     * {@inheritdoc}
     */
    protected $attributes = [
        'is_enabled' => true,
        'energy' => 1000,
        'solarion' => 0,
        'experience' => 0,
        'production_rate' => 0,
        'penalty_rate' => 0,
        'is_notification_enabled' => true,
    ];

    /**
     * {@inheritdoc}
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'remember_token', 'created_at', 'updated_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'last_login', 'last_capital_changed', 'last_energy_changed', 'started_at', 'donated_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'is_enabled' => 'bool',
        'email_verified_at' => 'datetime',
        'is_notification_enabled' => 'bool',
    ];

    /**
     * Get the capital.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function capital()
    {
        return $this->belongsTo(Planet::class, 'capital_id');
    }

    /**
     * Get the current.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function current()
    {
        return $this->belongsTo(Planet::class, 'current_id');
    }

    /**
     * Get the attack battle logs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attackBattleLogs()
    {
        return $this->hasMany(BattleLog::class, 'attacker_id');
    }

    /**
     * Get the defense battle logs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function defenseBattleLogs()
    {
        return $this->hasMany(BattleLog::class, 'defender_id');
    }

    /**
     * Get the shields.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function shields()
    {
        return $this->hasManyThrough(Shield::class, Planet::class);
    }

    /**
     * Get the messages.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    /**
     * Get the resources.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function resources()
    {
        return $this->belongsToMany(Resource::class)
            ->withPivot('is_researched', 'quantity')
            ->withTimestamps();
    }

    /**
     * Get the units.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function units()
    {
        return $this->belongsToMany(Unit::class)
            ->withPivot('is_researched', 'quantity')
            ->withTimestamps();
    }

    /**
     * Is started?
     *
     * @return bool
     */
    public function isStarted()
    {
        return ! empty($this->started_at);
    }

    /**
     * Get the gravatar.
     *
     * @return string
     */
    public function gravatar(array $parameters = [])
    {
        return Util::gravatar($this->email, $parameters);
    }

    /**
     * The channels the user receives notification broadcasts on.
     *
     * @return string
     */
    public function receivesBroadcastNotificationsOn()
    {
        return "user.{$this->id}";
    }

    /**
     * Delete the notifications by type.
     *
     * @param string $type
     */
    public function deleteNotificationsByType($type)
    {
        $count = $this->notifications()
            ->where('type', $type)
            ->count();

        if ($count) {
            $this->notifications()
                ->where('type', $type)
                ->delete();

            event(
                new UserUpdated($this->id)
            );
        }
    }
}
