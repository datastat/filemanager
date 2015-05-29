<?php namespace datastat\FileManager\Repositories;

// use datastat\FileManager\Events\PartnerWasPlacedEvent;
use datastat\FileManager\Models\FolderEloquent;
use App\Traits\EventCollector;
use App\User;

class FolderEloquentRepository {

    use EventCollector;

    protected $model;


    /*
    for phone verification add
        "propaganistas/laravel-phone": "~2.1"
        to composer
    */

    function __construct(FolderEloquent $model)
    {
        $this->model = $model;
    }
    
    public function create($opts = []){

        if(!$opts['parent_id']){
            $opts['parent_id'] = null;
        }

        $folder = new FolderEloquent($opts);
        $folder->saveOrFail();
        if($opts['parent_id']){
            // $folder->
            // with the `makeChildOf` method
            try {
                $parent = FolderEloquent::findOrFail($opts['parent_id']);
                $folder->makeChildOf($parent);
            } catch (\Exception $e) {
                return false;
            }
        }
        
        return $folder;
    }
    
    /**
    * @param $opts[id]
    */
    public function destroy($opts = []){

        $p = FolderEloquent::findOrFail(array_get($opts, 'id', 0));
        $p->delete();
        
        return true;

    }

    public function update($opts = []){

        $model = $this->get(['id' => array_get($opts, 'id')]);
        $model->fill(array_get($opts, 'attributes'));
        $model->saveOrFail();
        
        return true;
    }

    public function files($opts = []){
        $model = $this->get(['id' => array_get($opts, 'id')]);
        return $model->files;
    }

    /**
    * @param $opts[id]
    */
    public function get($opts = []){
        return FolderEloquent::findOrFail($opts['id'])
            ->getDescendantsAndSelf()
            ->toHierarchy()
            ->first();
    }

    public function getList($opts = [])
    {
        $ret = [];
        foreach($this->model->where('parent_id', '=', array_get($opts, 'id', 0))->get() as $folder){ // all roots
            foreach($folder->getDescendantsAndSelf()->toHierarchy() as $node){
                $ret[] = $node;
            }
        }
        return $ret;
        
    }

}