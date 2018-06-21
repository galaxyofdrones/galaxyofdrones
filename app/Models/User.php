<?php

namespace Koodilab\Models;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Koodilab\Events\UserUpdated;
use Koodilab\Support\Util;
use Laravel\Passport\HasApiTokens;

/**
 * User.
 *
 * @property int                                                                                                       $id
 * @property int|null                                                                                                  $capital_id
 * @property int|null                                                                                                  $current_id
 * @property string                                                                                                    $username
 * @property string                                                                                                    $email
 * @property string                                                                                                    $password
 * @property string|null                                                                                               $remember_token
 * @property bool                                                                                                      $is_enabled
 * @property int                                                                                                       $energy
 * @property int                                                                                                       $solarion
 * @property int                                                                                                       $experience
 * @property int                                                                                                       $production_rate
 * @property \Carbon\Carbon|null                                                                                       $last_login
 * @property \Carbon\Carbon|null                                                                                       $last_capital_changed
 * @property \Carbon\Carbon|null                                                                                       $last_energy_changed
 * @property \Carbon\Carbon|null                                                                                       $started_at
 * @property \Carbon\Carbon|null                                                                                       $created_at
 * @property \Carbon\Carbon|null                                                                                       $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|BattleLog[]                                                      $attackBattleLogs
 * @property \Illuminate\Database\Eloquent\Collection|Block[]                                                          $blocks
 * @property \Illuminate\Database\Eloquent\Collection|Bookmark[]                                                       $bookmarks
 * @property Planet|null                                                                                               $capital
 * @property \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[]                                       $clients
 * @property Planet|null                                                                                               $current
 * @property \Illuminate\Database\Eloquent\Collection|BattleLog[]                                                      $defenseBattleLogs
 * @property \Illuminate\Database\Eloquent\Collection|ExpeditionLog[]                                                  $expeditionLogs
 * @property \Illuminate\Database\Eloquent\Collection|Expedition[]                                                     $expeditions
 * @property int                                                                                                       $capital_change_remaining
 * @property int                                                                                                       $level
 * @property int                                                                                                       $level_experience
 * @property int                                                                                                       $next_level
 * @property int                                                                                                       $next_level_experience
 * @property \Illuminate\Database\Eloquent\Collection|Message[]                                                        $messages
 * @property \Illuminate\Database\Eloquent\Collection|MissionLog[]                                                     $missionLogs
 * @property \Illuminate\Database\Eloquent\Collection|Mission[]                                                        $missions
 * @property \Illuminate\Database\Eloquent\Collection|Movement[]                                                       $movements
 * @property \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property \Illuminate\Database\Eloquent\Collection|Planet[]                                                         $planets
 * @property \Illuminate\Database\Eloquent\Collection|Research[]                                                       $researches
 * @property \Illuminate\Database\Eloquent\Collection|resource[]                                                       $resources
 * @property \Illuminate\Database\Eloquent\Collection|Shield[]                                                         $shields
 * @property \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[]                                        $tokens
 * @property \Illuminate\Database\Eloquent\Collection|Unit[]                                                           $units
 *
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCapitalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEnergy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastCapitalChanged($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastEnergyChanged($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProductionRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSolarion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasApiTokens,
        Notifiable,
        Concerns\CanChangeCapital,
        Concerns\CanOccupy,
        Concerns\HasEnergy,
        Concerns\HasExperience,
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
        Queries\FindNotExpiredExpeditions,
        Queries\FindNotExpiredMissions,
        Queries\FindNotExpiredShields,
        Queries\FindPlanetsOrderByName,
        Queries\FindResearchedResources,
        Queries\FindUnitsOrderBySortOrder,
        Queries\IncomingUserAttackMovementCount,
        Queries\LosingBattleLogCount,
        Queries\WinningBattleLogCount,
        Queries\PaginateAllStartedOrderByPve,
        Queries\PaginateAllStartedOrderByPvp,
        Queries\PaginateBattleLogs,
        Queries\PaginateExpeditionLogs,
        Queries\PaginateMessages,
        Queries\PaginateMissionLogs,
        Queries\PaginatePlanets,
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
        'last_login', 'last_capital_changed', 'last_energy_changed', 'started_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'is_enabled' => 'bool',
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
     * Set the password attribute.
     *
     * @param string $value
     */
    public function setPasswordAttribute($value)
    {
        if (! empty($value)) {
            $this->attributes['password'] = bcrypt($value);
        }
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
     * @param array $parameters
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

    /**
     * {@inheritdoc}
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function (self $user) {
            if ($user->isDirty('capital_id')) {
                $user->last_capital_changed = Carbon::now();
            }
        });

        static::deleting(function (self $user) {
            if (auth()->id() != $user->getKey()) {
                $user->planets->each->update([
                    'user_id' => null,
                ]);

                return true;
            }

            return false;
        });

        static::updated(function (self $user) {
            event(
                new UserUpdated($user->id)
            );
        });
    }
}
