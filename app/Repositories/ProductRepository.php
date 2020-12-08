<?php
namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Collection;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{

   /**
    * UserRepository constructor.
    *
    * @param User $model
    */
   public function __construct(Product $model)
   {
       parent::__construct($model);
   }

   public function getNewOrExistingProductByName(string $name): Product
   {
		return Product::firstOrCreate(['name' => $name, ['additional_data' => []]]);
   }
}