<?php namespace datastat\FileManager\Models;

use Illuminate\Database\Eloquent\Model;
// use Watson\Validating\ValidatingTrait;

// use App\Traits\I18nModelTrait;
// use App\Exceptions\InternalModelErrorException;

class FileEloquent extends Model {

    use ValidatingTrait;
    
    // use I18nModelTrait;
    protected $casts = [
        'name' => 'json',
        'description' => 'json',
        'file_info' => 'json'
    ];

    protected $fillable = [
        'name',
        'description',
        'folder_id',
        'type',
        'mime_type',
        'file_info',
        'disk',
        'location_path',
        'public_url',
        'created_by',
        'updated_by',

    ];
    protected $table = 'files';

    protected $rules = [
        'type' => 'required'
    ];

    public function do_error(){

        if(true){
            throw new InternalModelErrorException("Can not do what you want", 1);
        }

    }

}