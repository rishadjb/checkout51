<?php
namespace App\Repositories\Interfaces;

use App\Models\Offer;
use Illuminate\Support\Collection;

interface OfferRepositoryInterface
{
    /**
    * @param atring $source
    * @return Collection
    */
    public function findBySource(string $source, string $sort = 'id', string $order = 'asc'): Collection;
 
    /**
    * @param atring $source
    * @return Collection
    */
    public function findActiveOffersBySource(string $source, string $sort, string $order): Collection;

    public function update($offerId): Offer;
}