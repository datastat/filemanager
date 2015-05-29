<?php namespace datastat\FileManager\Repositories;

// use datastat\FileManager\Events\PartnerWasPlacedEvent;
use datastat\FileManager\Models\FileEloquent;
use App\Traits\EventCollector;
use App\User;

class FileEloquentRepository {

    use EventCollector;

    protected $partner;


    /*
    for phone verification add
        "propaganistas/laravel-phone": "~2.1"
        to composer
    */

    function __construct(FileEloquent $partner)
    {
        $this->partner = $partner;
    }
    
    public function create($opts = []){

        $p = new FileEloquent($opts);
        $p->saveOrFail();
        
        return $p;
    }
    
    /**
    * @param $opts[id]
    */
    public function destroy($opts = []){

        $p = FileEloquent::findOrFail(array_get($opts, 'id', 0));
        $p->delete();
        
        return true;

    }

    public function update($opts = []){

        $partner = $this->get(['id' => array_get($opts, 'id')]);
        $partner->fill(array_get($opts, 'attributes'));
        // $partner->do_error(); for testing error response
        $partner->saveOrFail();
        
        return true;
    }

    /**
    * @param $opts[id]
    */
    public function get($opts = []){
        $p = FileEloquent::findOrFail(array_get($opts, 'id', 0));
        return $p;
    }

    public function getAttachedFiles($module, $fileable_id){

        $model = new $module;
        $model = $model->findOrFail($fileable_id);

        return $model->files;

    }

    public function attachFile($module, $fileable_id, $file_id){

        $model = new $module;
        $model = $model->findOrFail($fileable_id);

        return $model->files()->attach($file_id);

    }

    public function detachFile($module, $fileable_id, $file_id){

        $model = new $module;
        $model = $model->findOrFail($fileable_id);

        return $model->files()->detach($file_id);

    }

    public function getList()
    {
        return $this->partner->get();
    }

    public function getListByUserId()
    {

    }

    public function setStatus($status)
    {

    }

    public function bindWith()
    {

    }

}