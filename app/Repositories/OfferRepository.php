<?php
namespace App\Repositories;

use App\Models\Offer;
use App\Repositories\Interfaces\OfferRepositoryInterface;
use Illuminate\Support\Collection;

class OfferRepository extends BaseRepository implements OfferRepositoryInterface
{

   /**
    * UserRepository constructor.
    *
    * @param User $model
    */
   public function __construct(Offer $model)
   {
       parent::__construct($model);
   }
 
    /**
    * @param atring $source
    * @return Collection
    */
    public function findBySource(string $source, string $sort = 'id', string $order = 'asc'): Collection
    {
        $sourceId = $this->getSourceIdByName($source);

        return $this->model->where('source_id', $sourceId)
        	->with('product')
        	->orderBy($sort, $order)
        	->get()
        	->map->format();
    }
 
    /**
    * @param atring $source
    * @return Collection
    */
    public function findActiveOffersBySource(string $source, string $sort, string $order): Collection
    {
        $sourceId = $this->getSourceIdByName($source);

        return $this->model->where('source_id', $sourceId)
        	->where('active', true)
        	->join('product', 'product_id', '=', 'product.id')	//alternative to using with()
        	->orderBy($sort, $order)
        	->get()
        	->map->format();

        // equivalent sql query: select * from offer where active and source_id = (select id from source where name=$source)) offers LEFT JOIN products ON offer.product_id = product.id
    }

    public function update($offerId): Offer
    {
    	$offer = Offer::where('offer_id', $offerId)->firstOrFail();

    	$offer->update(request()->only('cash_back', 'active'));

    	return $offer;
    }
}