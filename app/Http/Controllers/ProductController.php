<?php

use App\Http\Controllers\ApiController;
use App\Repositories\Interfaces\ProductRepositoryInterface

class ProductController extends ApiController
{
   private $productRepository;
  
   public function __construct(ProductRepositoryInterface $productRepository)
   {
       $this->productRepository = $productRepository;
   }
}