<?php namespace datastat\FileManager\Models;

class FileTypes{

	static $mime_to_short_type = [
		'image/jpeg' => 'image',
		'image/pjpeg' => 'image',
		'image/png' => 'image',
		
		'video/avi' => 'video',
		'video/mpeg' => 'video',
		'video/mp4' => 'video',
		'video/ogg' => 'video',
		'video/quicktime' => 'video',
		'video/x-matroska' => 'video',
		'video/webm' => 'video',

		'text/plain' => 'document',
		'application/pdf' => 'document',
		'application/x-pdf' => 'document',
		'application/msword' => 'document',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'document',
		'application/vnd.ms-excel' => 'document',
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'document',
	];

}

