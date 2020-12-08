<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Response;
use App\Models\Source;
use App\Transformers\Source\Listing;
use App\Transformers\Source\Source as SourceItem;
use App\Exceptions\ApiException;
use Novus\Platform\Core\Http\Controllers\ApiController;
use Ramsey\Uuid\Uuid;
use Validator;

class SourceController extends ApiController
{
    /**
     * List all the sources.
     *
     * @param Listing $transformer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listSources(Listing $transformer)
    {
        $sources = DB::table('product')
        ->where(['product.active' => true, 'source.active' => true])
        ->join('source', 'product.id', '=', 'source.product_id')
        ->select('product.name AS product_name', 'product.full_name AS product_full_name', 'source.full_name AS source_full_name', 'source.name AS source_name', 'source.active AS source_active', 'product.active AS product_active')
        ->get()
        ->toArray();

        return $this->response($transformer->transform($sources), Response::HTTP_OK);
    }
}
