<?php

namespace App\Providers;

use App\Repositories\Interfaces\BaseRepositoryInterface; 
use App\Repositories\Interfaces\BatchRepositoryInterface; 
use App\Repositories\Interfaces\OfferRepositoryInterface; 
use App\Repositories\Interfaces\ProductRepositoryInterface; 
use App\Repositories\OfferRepository; 
use App\Repositories\BaseRepository; 
use App\Repositories\BatchRepository; 
use App\Repositories\ProductRepository; 
use Illuminate\Support\ServiceProvider; 

class RepositoryServiceProvider extends ServiceProvider
{
    /** 
    * Register services. 
    * 
    * @return void  
    */ 
   public function register() 
   { 
       $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
       $this->app->bind(BatchRepositoryInterface::class, BatchRepository::class);
       $this->app->bind(OfferRepositoryInterface::class, OfferRepository::class);
       $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
   }
}
