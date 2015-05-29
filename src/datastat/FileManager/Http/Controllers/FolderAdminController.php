<?php namespace datastat\FileManager\Http\Controllers;

use Input;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use datastat\FileManager\Repositories\FileEloquentRepository;
use datastat\FileManager\Repositories\FolderEloquentRepository;

class FolderAdminController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function index(FileEloquentRepository $file_repo, FolderEloquentRepository $folder_repo)
	{

		return \Response::json([
				'status' => 1,
				'feed' => $folder_repo->getList()
			]);
	}

	public function show($id = 0, FileEloquentRepository $file_repo, FolderEloquentRepository $folder_repo)
	{

		$entry = $folder_repo->get([
						'id' => $id
					]);

		return \Response::json([
				'status' => 1,
				'entry' => $entry
			]);
	}



	public function files($id = 0, FileEloquentRepository $file_repo, FolderEloquentRepository $folder_repo){

		return \Response::json([
				'status' => 1,
				'feed' => $folder_repo->files(['id' => $id])
			]);

	}

	public function store(FolderEloquentRepository $folder_repo, Request $request){

		$attrs = $request->except('_token', '_method');
		$folder_repo->create($attrs);

		return \Response::json([
				'status' => 1,
				'msg' => 'ok folder createad'
			]);

	}

	public function update($id, FolderEloquentRepository $folder_repo, Request $request){

		$attrs = $request->except('_method');

		// dd($attrs);

		$folder = $folder_repo->update([
					'id' => $id,
					'attributes' => $attrs
				]);

		return \Response::json([
				'status' => 1,
				'msg' => 'folder updated'
			]);

	}

	public function edit(){
		// create new folder
		return \Response::json([
				'status' => 1,
				'msg' => 'ok folder updated'
			]);
	}

}
