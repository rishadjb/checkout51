<?php

namespace App\Models;

use App\Helpers\Source;
use Novus\Platform\Models\UuidModel;

class Batch extends UuidModel
{
    /**
     * @var string
     */
    protected $table = 'batch';

    /**
     * @var array
     */
    protected $fillable = [
        'batch_id',
        'source_id',
    ];

    /**
     * @var array
     */
    protected $guarded = [
    ];

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var array
     */
    protected $casts = [];
}
