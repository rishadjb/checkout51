<?php

namespace App\Models;

use Novus\Platform\Models\UuidModel;

class Product extends UuidModel
{
    /**
     * @var string
     */
    protected $table = 'product';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
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
