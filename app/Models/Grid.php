<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;
use Koodilab\Models\Relations\BelongsToBuilding;
use Koodilab\Models\Relations\BelongsToPlanet;
use Koodilab\Models\Relations\HasOneConstruction;
use Koodilab\Models\Relations\HasOneTraining;
use Koodilab\Models\Relations\HasOneUpgrade;

class Grid extends Model
{
    use BelongsToBuilding, BelongsToPlanet, HasOneConstruction, HasOneUpgrade, HasOneTraining;

    /**
     * The plain type.
     *
     * @var int
     */
    const TYPE_PLAIN = 0;

    /**
     * The resource type.
     *
     * @var int
     */
    const TYPE_RESOURCE = 1;

    /**
     * The central type.
     *
     * @var int
     */
    const TYPE_CENTRAL = 2;

    /**
     * {@inheritdoc}
     */
    protected $perPage = 30;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];
}
