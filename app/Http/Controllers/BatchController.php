<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\BatchRepositoryInterface;
use App\Repositories\OfferRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Response;
use App\Models\Offer;
use App\Models\Product;
use App\Helpers\Source as SourceHelper;
use App\Transformers\Batch\Batch;
use App\Validators\ValidatorException;
use App\Exceptions\ApiException;
use App\Http\Controllers\ApiController;

class BatchController extends ApiController
{
   private $batchRepository;
  
   public function __construct(BatchRepositoryInterface $batchRepository)
   {
       $this->batchRepository = $batchRepository;
   }

   /**
     * Search offer by product
     *
     * @param Batch $transformer
     * @param string $product
     *
     * @throws ApiException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function batchUpload(Batch $transformer, string $source)
    {
        $batchObj = json_decode(file_get_contents(base_path().'/resources/assets/batch.json'), true);

        if ($this->batchIdExists($batchObj['batch_id'])) {
            throw new ApiException(ApiException::CODE_BAD_REQUEST, 'Batch ID already exists', Response::HTTP_BAD_REQUEST);
        }

        $this->batchRepository->create([
            'source_id'      => SourceHelper::getSourceIdForApi($source),
            'batch_id'       => $batchObj['batch_id'],
        ]);

        $result = $this->getBatchUploadResult($source, $batchObj);

        return $this->response($transformer->transform($result), Response::HTTP_OK);
    }

    private function getBatchUploadResult (string $source, array $batch): array 
    {
        $result = $this->newBatchResultsArray();

        foreach ($batch['offers'] as $offer) {
            $errors = $this->validateOffer($source, $offer);

            if (empty($errors)) {
                $result['completed']++;

                $offerRepository = new OfferRepository(new Offer);
                $productRepository = new ProductRepository(new Product);

                $offerRepository->create([
		            'source_id'      => SourceHelper::getSourceIdForApi($source),
		            'batch_id'      => $this->batchRepository->findByBatchId($batch['batch_id'])->id,
		            'product_id'      => $productRepository->getNewOrExistingProductByName($offer['name'])->id,
		            'offer_id'      => $offer['offer_id'],
		            'cash_back' => $offer['cash_back'],
		            'image_url'      => $offer['image_url'],
		            'active'      => true,
		            'additional_data'      => isset($offer['additional_data']) ? $offer['additional_data'] : [],
                ]); 
            } else {
                $result['failed']++;

                $result['errors'][] = [
                    'offer'     => $offer,
                    'errors'    => $errors,
                ];
            }
        }

        return $result;
    }

    private function newBatchResultsArray (): array 
    {
        return [
            'completed' => 0,
            'failed'    => 0,
            'errors'    => [],
        ];
    }

    private function batchIdExists (int $id) {
        return $this->batchRepository->findByBatchId($id);
    }

    private function validateOffer (string $source, array $offer)
    {
        $sourceId = SourceHelper::getSourceIdForApi($source);
        $offerValidator = SourceHelper::getClientValidatorClass($sourceId, 'offer');

        // try {
        //     $cleanedOfferRequestBody = $offerValidator->cleanData($offer);
        // } catch (ValidatorException $e) {
        //     throw new ApiException(ApiException::CODE_BAD_REQUEST, $e->getMessage(), Response::HTTP_BAD_REQUEST);
        // }

        // if (empty($cleanedOfferRequestBody)) {
        //     throw new ApiException(ApiException::CODE_BAD_REQUEST, translate('Invalid request body'), Response::HTTP_BAD_REQUEST);
        // }


        $cleanedOfferRequestBody = $offer;
        $cleanedOfferRequestBody['source_id'] = $sourceId;

        $errors = $offerValidator->validateOffer($cleanedOfferRequestBody);

        return $errors;
    }
}