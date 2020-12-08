<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Repositories\Interfaces\OfferRepositoryInterface;
use App\Transformers\Search\Listing as SearchListing;

class OfferController extends ApiController
{
   private $offerRepository;
  
   public function __construct(OfferRepositoryInterface $offerRepository)
   {
       $this->offerRepository = $offerRepository;
   }

   /**
     * Search application by product
     *
     * @param SearchListing $transformer
     * @param string          $product
     *
     * @throws ApiException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchOffers(SearchListing $transformer, string $source)
    {
    	$sortBy = request()->sort ?? 'name';
    	$orderBy = request()->order ?? 'asc';

        $offers = $this->offerRepository->findActiveOffersBySource($source, $sortBy, $orderBy);

        // try {
        //     $body = $this->getBodyParams();
        // } catch (ValidatorException $e) {
        //     throw new ApiException(ApiException::CODE_BAD_REQUEST, $e->getMessage(), Response::HTTP_BAD_REQUEST);
        // }
        // $criteria = ApplicationHelper::createSearchCriteriaArray($product, (array) $body);

        $data = [
            'total'    	=> $offers->count(),
            'offers' 	=> $offers->toArray(),
            'sort' 		=> $sortBy,
            'order' 	=> $orderBy,
        ];

        return view('offers', ['data' => $data]);
    }

   /**
     * Edit offer
     *
     * @param string          $source
     *
     * @throws ApiException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function editOffers(string $source)
    {
    	$sortBy = request()->sort ?? 'active';
    	$orderBy = request()->order ?? 'asc';

        $offers = $this->offerRepository->findBySource($source, $sortBy, $orderBy);

        $data = [
            'total'      => $offers->count(),
            'offers' => $offers->toArray(),
        ];

        return view('admin.offers', ['data' => $data]);
    }

    public function update($offerId)
    {
    	$this->offerRepository->update($offerId);
    }
}