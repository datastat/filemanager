<?php namespace datastat\FileManager\Models;

use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;

// use App\Traits\I18nModelTrait;
// use App\Exceptions\InternalModelErrorException;

use Baum\Node;

class FolderEloquent extends Node {

    use ValidatingTrait;
    use I18nModelTrait;
    protected $casts = [
        'name' => 'json'
    ];

    protected $fillable = [
        'name',
        'parent_id',
        'lft',
        'rgt',
        'depth',
    ];
    protected $table = 'folders';

    protected $rules = [
        // 'name.sl' => 'required|min:3',
        // 'parent_id' => 'sometimes'
    ];

    public function do_error(){

        if(true){
            throw new InternalModelErrorException("Can not do what you want", 1);
        }

    }

    public function files(){
        return $this->hasMany('datastat\FileManager\Models\FileEloquent', 'folder_id', 'id');
    }

}