<?php

Route::group(['prefix' => 'admin'], function () {

    /* *
     * Partners
     * */

    Route::get('filemanager', [
        'as'=>'admin.filemanager.index',
        'uses'=>'\datastat\FileManager\Http\Controllers\FileManagerAdminController@index'
    ]);

    Route::get('filemanager/attached_files', [
        'as'=>'admin.filemanager.attached_files',
        'uses'=>'\datastat\FileManager\Http\Controllers\FileManagerAdminController@attachedFiles'
    ]);

    Route::patch('filemanager/attach_file', [
        'as'=>'admin.filemanager.attach_file',
        'uses'=>'\datastat\FileManager\Http\Controllers\FileManagerAdminController@attachFile'
    ]);

    Route::delete('filemanager/detach_file', [
        'as'=>'admin.filemanager.detach_file',
        'uses'=>'\datastat\FileManager\Http\Controllers\FileManagerAdminController@detachFile'
    ]);

    Route::post('filemanager', [
        'as' => 'admin.filemanager.store',
        'uses' => '\datastat\FileManager\Http\Controllers\FileManagerAdminController@store'
    ]);

    Route::delete('filemanager/{id}', [
        'as' => 'admin.filemanager.delete',
        'uses' => '\datastat\FileManager\Http\Controllers\FileManagerAdminController@delete'
    ]);

    Route::get('filemanager/folder', [
        'as' => 'admin.filemanager.folder.index',
        'uses' => '\datastat\FileManager\Http\Controllers\FolderAdminController@index'
    ]);
    
    Route::get('filemanager/folder/{id}', [
        'as' => 'admin.filemanager.folder.show',
        'uses' => '\datastat\FileManager\Http\Controllers\FolderAdminController@show'
    ]);

    Route::post('filemanager/folder', [
        'as' => 'admin.filemanager.folder.store',
        'uses' => '\datastat\FileManager\Http\Controllers\FolderAdminController@store'
    ]);

    Route::patch('filemanager/folder/{id}', [
        'as' => 'admin.filemanager.folder.update',
        'uses' => '\datastat\FileManager\Http\Controllers\FolderAdminController@update'
    ]);

    Route::get('filemanager/folder/{id}/files', [
        'as' => 'admin.filemanager.folder.files',
        'uses' => '\datastat\FileManager\Http\Controllers\FolderAdminController@files'
    ]);

});
