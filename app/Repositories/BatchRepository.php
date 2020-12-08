<?php
namespace App\Repositories;

use App\Models\Batch;
use App\Repositories\Interfaces\BatchRepositoryInterface;
use Illuminate\Support\Collection;

class BatchRepository extends BaseRepository implements BatchRepositoryInterface
{

   /**
    * UserRepository constructor.
    *
    * @param User $model
    */
   public function __construct(Batch $model)
   {
       parent::__construct($model);
   }

   public function findByBatchId(int $id): ?Batch 
   {
        return Batch::where('batch_id', $id)->first();
   }
}