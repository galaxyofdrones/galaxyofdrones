<?php

namespace Koodilab\Models;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Koodilab\Events\UserUpdated;
use Koodilab\Models\Relations\BelongsToManyResource;
use Koodilab\Models\Relations\BelongsToManyUnit;
use Koodilab\Models\Relations\HasManyBookmark;
use Koodilab\Models\Relations\HasManyMissionLog;
use Koodilab\Models\Relations\HasManyMovement;
use Koodilab\Models\Relations\HasManyPlanet;
use Koodilab\Models\Relations\HasManyResearch;
use Laravel\Passport\HasApiTokens;

/**
 * User.
 *
 * @property int $id
 * @property int|null $capital_id
 * @property int|null $current_id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property bool $is_enabled
 * @property int $role
 * @property int $energy
 * @property int $experience
 * @property int $production_rate
 * @property \Carbon\Carbon|null $last_login
 * @property \Carbon\Carbon|null $last_capital_changed
 * @property \Carbon\Carbon|null $last_production_changed
 * @property \Carbon\Carbon|null $started_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|BattleLog[] $attackBattleLogs
 * @property-read \Illuminate\Database\Eloquent\Collection|Bookmark[] $bookmarks
 * @property-read Planet|null $capital
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read Planet|null $current
 * @property-read \Illuminate\Database\Eloquent\Collection|BattleLog[] $defenseBattleLogs
 * @property-read int $capital_change_remaining
 * @property-read int $level
 * @property-read int $level_experience
 * @property-read int $next_level
 * @property-read int $next_level_experience
 * @property-read \Illuminate\Database\Eloquent\Collection|MissionLog[] $missionLogs
 * @property-read \Illuminate\Database\Eloquent\Collection|Movement[] $movements
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|Planet[] $planets
 * @property-read \Illuminate\Database\Eloquent\Collection|Research[] $researches
 * @property-read \Illuminate\Database\Eloquent\Collection|resource[] $resources
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read \Illuminate\Database\Eloquent\Collection|Unit[] $units
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
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastProductionChanged($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProductionRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable, BelongsToManyResource, BelongsToManyUnit,
        HasManyBookmark, HasManyPlanet, HasManyMovement, HasManyResearch, HasManyMissionLog;

    /**
     * The user role.
     *
     * @var int
     */
    const ROLE_USER = 0;

    /**
     * The administrator role.
     *
     * @var int
     */
    const ROLE_ADMIN = 1;

    /**
     * The super admin role.
     *
     * @var int
     */
    const ROLE_SUPER_ADMIN = 2;

    /**
     * The experience offset.
     *
     * @var float
     */
    const EXPERIENCE_OFFSET = 0.04;

    /**
     * The hyperdrive cooldown.
     *
     * @var int
     */
    const CAPITAL_CHANGE_COOLDOWN = 86400;

    /**
     * {@inheritdoc}
     */
    protected $attributes = [
        'is_enabled' => true,
        'role' => self::ROLE_USER,
        'energy' => 1000,
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
        'last_login', 'last_capital_changed', 'last_production_changed', 'started_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'is_enabled' => 'bool',
    ];

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

            if ($user->isDirty(['energy', 'production_rate'])) {
                $user->last_production_changed = Carbon::now();
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
            event(new UserUpdated($user->id));
        });
    }

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
     * Set the password attribute.
     *
     * @param string $value
     */
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    /**
     * Is admin?
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role == static::ROLE_ADMIN;
    }

    /**
     * Is super admin?
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->role == static::ROLE_SUPER_ADMIN;
    }

    /**
     * Can give this role?
     *
     * @param string $role
     *
     * @return bool
     */
    public function canGiveRole($role)
    {
        return $this->role >= $role;
    }

    /**
     * Has energy?
     *
     * @param int $energy
     *
     * @return bool
     */
    public function hasEnergy($energy)
    {
        return $this->energy >= $energy;
    }

    /**
     * Increment the energy.
     *
     * @param int $amount
     */
    public function incrementEnergy($amount)
    {
        $this->update([
            'energy' => $this->energy + $amount,
        ]);
    }

    /**
     * Decrement the energy.
     *
     * @param int $amount
     */
    public function decrementEnergy($amount)
    {
        $this->update([
            'energy' => max(0, $this->energy - $amount),
        ]);
    }

    /**
     * Synchronize the production.
     */
    public function syncProduction()
    {
        $this->update([
            'energy' => $this->energy,
            'production_rate' => $this->planets->sum('production_rate'),
        ]);
    }

    /**
     * Is capital changeable?
     *
     * @return bool
     */
    public function isCapitalChangeable()
    {
        $dt = $this->last_capital_changed;

        return !$dt || $dt->copy()->addSeconds(static::CAPITAL_CHANGE_COOLDOWN)->lte(Carbon::now());
    }

    /**
     * Get the energy attribute.
     *
     * @return int
     */
    public function getEnergyAttribute()
    {
        $energy = 0;

        if (!empty($this->attributes['energy'])) {
            $energy = $this->attributes['energy'];
        }

        $produced = round(
            $this->production_rate / 3600 * Carbon::now()->diffInSeconds($this->last_production_changed)
        );

        return $energy + $produced;
    }

    /**
     * Get the level attribute.
     *
     * @return int
     */
    public function getLevelAttribute()
    {
        return (int) (static::EXPERIENCE_OFFSET * sqrt($this->experience)) + 1;
    }

    /**
     * Get the level expereience attribute.
     *
     * @return int
     */
    public function getLevelExperienceAttribute()
    {
        return (int) pow(($this->level - 1) / static::EXPERIENCE_OFFSET, 2);
    }

    /**
     * Get the next level attribute.
     *
     * @return int
     */
    public function getNextLevelAttribute()
    {
        return $this->level + 1;
    }

    /**
     * Get the next level expereience attribute.
     *
     * @return int
     */
    public function getNextLevelExperienceAttribute()
    {
        return (int) pow($this->level / static::EXPERIENCE_OFFSET, 2);
    }

    /**
     * Get the capital change remaining attribute.
     *
     * @return int
     */
    public function getCapitalChangeRemainingAttribute()
    {
        if (!$this->isCapitalChangeable()) {
            $dt = $this->last_capital_changed;

            return Carbon::now()->diffInSeconds($dt->copy()->addSeconds(static::CAPITAL_CHANGE_COOLDOWN));
        }

        return 0;
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
}
