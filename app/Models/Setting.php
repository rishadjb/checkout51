<?php

namespace App\Models;

use Novus\Platform\Models\UuidModel;

class Setting extends UuidModel
{
    /**
     * @var string
     */
    protected $table = 'settings';

    /**
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'source_id',
        'key',
        'data',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
