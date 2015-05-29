<?php namespace datastat\FileManager\Http\Controllers;

use Input;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use datastat\FileManager\Repositories\FileEloquentRepository;
use datastat\FileManager\Repositories\FolderEloquentRepository;
use datastat\FileManager\Models\FileTypes;

use OpenCloud\Rackspace;

class FileManagerAdminController extends Controller {

	public function __construct(){
		\Assets::add('admin-seed');
		\Assets::add('admin-filemanager');
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function index(FileEloquentRepository $file_repo, FolderEloquentRepository $folder_repo)
	{
		\Assets::add('vendor/filemanager/js/index.js');		
		return view('filemanager::index');
	}

	public function template(){
		// return view('admin.filemanager.template');
	}

	private $container = null;
	private function get_container_connection(){

		if(!$this->container){
			$client = new Rackspace(Rackspace::UK_IDENTITY_ENDPOINT, array(
		        'username' => env('RACKSPACE_USERNAME'),
		        'apiKey' => env('RACKSPACE_KEY')
	        ));
	        $region = env('RACKSPACE_REGION');
	        $objectStoreService = $client->objectStoreService(null, $region);
	        $this->container = $objectStoreService->getContainer(env('RACKSPACE_CONTAINER'));			
		}

		return $this->container;

	}

	public function attachedFiles(Request $request, FileEloquentRepository $file_repo){
		$module = $request->input('module', false);
		$fileable_id = $request->input('fileable_id', false);
		
		if(!$module || !$fileable_id){
			throw new \Exception("Module and fileable id must be defined", 1);			
		}

		$files = $file_repo->getAttachedFiles($module, $fileable_id);

		return \Response::json([
				'status' => 1,
				'feed' => $files
			]);
	}

	public function attachFile(Request $request, FileEloquentRepository $file_repo){

		$module = $request->input('module', false);
		$fileable_id = $request->input('fileable_id', false);
		$file_id = $request->input('file_id', false);
		
		if(!$module || !$fileable_id || !$file_id){
			throw new \Exception("Module and fileable id must be defined", 1);			
		}

		$files = $file_repo->attachFile($module, $fileable_id, $file_id);

		return \Response::json([
				'status' => 1,
				'feed' => $files
			]);

	}

	public function detachFile(Request $request, FileEloquentRepository $file_repo){

		$module = $request->input('module', false);
		$fileable_id = $request->input('fileable_id', false);
		$file_id = $request->input('file_id', false);
		
		if(!$module || !$fileable_id || !$file_id){
			throw new \Exception("Module and fileable id must be defined", 1);			
		}

		$files = $file_repo->detachFile($module, $fileable_id, $file_id);

		return \Response::json([
				'status' => 1,
				'feed' => $files
			]);

	}

	public function delete($id, FileEloquentRepository $file_repo){

		$file = $file_repo->get([
				'id' => $id
			]);

		// try to delete from storage
		try {

			// rackspace specific
			$object = $this->get_container_connection()
				->getObject($file->location_path);

			$object->delete();

			// todo delete all file links
			$file->delete();
			
		} catch (\Exception $e) {
			return \Response::json([
				'status' => -1,
				'file' => $file,
				'msg' => $e->getMessage()
			]);
		}

		return \Response::json([
				'status' => 1,
				'file' => $file
			]);
	}

	public function store(FileEloquentRepository $file_repo, FolderEloquentRepository $folder_repo, Request $request){

		// needs refactoring

		$folder = $folder_repo->get([
			'id' => $request->input('folder_id')
			]);

		$ds = DIRECTORY_SEPARATOR;
		$cleanup_files = [];

		$ret = [
			'files' => [],
			'msg' => '',
			'log' => []
		];

		$cleanup_files = [];

		foreach(array_get($_FILES, 'files.error', []) as $_file_idx => $_file){

			if($_FILES['files']['error'][$_file_idx] != UPLOAD_ERR_OK){
				$ret['log'][] = $_file_idx . ' not good upload';
				continue;
			}

			// get document type
			$shorttype = array_get(FileTypes::$mime_to_short_type,
				$_FILES['files']['type'][$_file_idx],
				false);

			// check if mime is correct type
			if(!$shorttype){
				$ret['msg'] .= 'File not recognized as image, video or document.';
				$ret['log'][] = $_file_idx . ' not good filetype (' . $_FILES['files']['type'][$_file_idx] . ')';
				continue;
			}

			$file_info = [
				'original_filename' => $_FILES['files']['name'][$_file_idx]
			];

			try {
				// create new entry in media
				$file = $file_repo->create([
					'name' => [
						'sl' => 'partial'
					],
					'description' => [],
					'folder_id' => $folder->id,
					'type' => $shorttype,
					'mime_type' => $_FILES['files']['type'][$_file_idx],
					'file_info' => $file_info,
					'disk' => 'rackspace',
					'location_path' => '',
					'public_url' => '',
					'created_by' => 0,
					'updated_by' => 0,
					]);
			} catch (\Exception $e) {				
				$ret['msg'] .= 'File can not be created.';
				$ret['log'][] = $_file_idx . ' ' . $e->getMessage();
				continue;
			}

			$ret['log'][] = 'new file id: ' . $file->id;

			// generate cloud filename
			$pi = pathinfo($_FILES['files']['name'][$_file_idx]);
			$filename = preg_replace('/[^a-z0-9\._-]+/', '', $pi['filename'])
			. '.' . 
			array_get($pi, 'extension', 'unkn');
			$filename = $file->id . '_' . time() . '_' . $filename;

			$file->name = [
				'sl' => preg_replace('/[^a-z0-9\._-]+/', '', $pi['filename'])
			];

			// move file to new temporary location
			$temporary_file_path = storage_path() . $ds . 'app' . $ds . 'tmp_' . $filename;

			move_uploaded_file($_FILES['files']['tmp_name'][$_file_idx], $temporary_file_path);

			$file_info['filesize'] = filesize($temporary_file_path);

			// add this file to be cleaned at the end of this script
			$cleanup_files[] = $temporary_file_path;
			$ret['log'][] = 'new filename: ' . $filename;

			// define storage folder (rackspace, other cloud providers)
			$storage_folder = 'u/' . date('Y') . '/' . date('m') . '/';

			try {
				
				// $file_upload = \Storage::put(
				// 	$storage_folder . $filename,
				// 	file_get_contents($temporary_file_path)
				// 	);
				// $ret['storage_response'] = $file_upload;

				// upload file
				$fileData = fopen($temporary_file_path, 'r');
				$ret['log'][] = 'uploaded to cloud:';
				$object = $this->get_container_connection()
					->uploadObject($storage_folder . $filename, $fileData);

				$ret['log'][] = 'OK';
				$ret['log'][] = $object->getPublicUrl();	

				// update and persist public url
				$file->location_path = $storage_folder . $filename;
				$file->public_url = $object->getPublicUrl();
				$file->save();

				// if once needed file can put in session
				// \Session::push('uploaded_files', $file->id);

			} catch (\Exception $e) {
				fclose($fileData);
				$ret['msg'] .= 'File can not be stored on storage.';
				$ret['log'][] = $_file_idx . ' ' . $e->getMessage();
				continue;
			}
			
			fclose($fileData);

			$ret['files'][] = $file;

		}

		// clear tmp files
		foreach($cleanup_files as $_cf){
			$_cf = str_replace('\\', $ds, $_cf);
			$_cf = str_replace('/', $ds, $_cf);
			$ret['log'][] = 'Cleaning up ' . $_cf;
			unlink($_cf);
		}

		return \Response::json($ret);
	}

}
