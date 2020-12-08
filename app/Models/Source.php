<?php

namespace App\Models;

use Novus\Platform\Models\UuidModel;

class Source extends UuidModel
{
    /**
     * @var string
     */
    protected $table = 'source';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'full_name',
        'active',
    ];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var array
     */
    protected $casts = [];
}
