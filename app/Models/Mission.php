<?php

namespace Koodilab\Models;

use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
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
