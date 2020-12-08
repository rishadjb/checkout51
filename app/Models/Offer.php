<?php

namespace App\Models;

use App\Models\Batch;
use App\Models\Product;
use App\Helpers\Source;
use Novus\Platform\Models\UuidModel;

class Offer extends UuidModel
{
    /**
     * @var string
     */
    protected $table = 'offer';

    /**
     * @var array
     */
    protected $fillable = [
        'source_id',
        'batch_id',
        'product_id',
        'offer_id',
        'cash_back',
        'image_url',
        'active',
        'additional_data',
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
    protected $casts = [
        'additional_data' => 'array',
    ];
}
