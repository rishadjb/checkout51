<?php
namespace App\Repositories\Interfaces;

use App\Models\Batch;
use Illuminate\Support\Collection;

interface BatchRepositoryInterface
{
   public function findByBatchId(int $id): ?Batch;
}