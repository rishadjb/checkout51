<?php   

namespace App\Repositories;

use App\Models\Source;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class BaseRepository implements BaseRepositoryInterface 
{     
    /**      
     * @var Model      
     */     
     protected $model;       

    /**      
     * BaseRepository constructor.      
     *      
     * @param Model $model      
     */     
    public function __construct(Model $model)     
    {         
        $this->model = $model;
    }

    /**
    * @return Collection
    */
    public function all(): Collection
    {
        return $this->model->all();    
    }
 
    /**
    * @param array $attributes
    *
    * @return Model
    */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }
 
    /**
    * @param $id
    * @return Model
    */
    public function find($id): ?Model
    {
        return $this->model->find($id);
    }

    protected function getSourceIdByName(string $source) {
        $source = Source::where('name', $source)->first();

        if(!$source) {
            //throw exception
        }

        return $source->id;
    }
 
    /**
    * @param atring $source
    * @return Collection
    */
    public function findBySource(string $source, string $sortBy = 'id', string $orderBy = 'asc'): Collection
    {
        $sourceId = $this->getSourceIdByName($source);

        return $this->model->where('source_id', $sourceId)->get();
    }
}