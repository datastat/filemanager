<?php namespace datastat\FileManager\Repositories;

// use datastat\FileManager\Events\PartnerWasPlacedEvent;
use datastat\FileManager\Models\FileEloquent;

class FileEloquentRepository {

    protected $filemodel;


    /*
    for phone verification add
        "propaganistas/laravel-phone": "~2.1"
        to composer
    */

    function __construct(FileEloquent $filemodel)
    {
        $this->filemodel = $filemodel;
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

        $filemodel = $this->get(['id' => array_get($opts, 'id')]);
        $filemodel->fill(array_get($opts, 'attributes'));
        // $filemodel->do_error(); for testing error response
        $filemodel->saveOrFail();
        
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
        return $this->filemodel->get();
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