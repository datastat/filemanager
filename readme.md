# Filemanager for Laravel 5

## Installation

### Step 1
`composer require datastat/filemanager`

### Step 2
open config/app.php and add 
`'datastat\FileManager\FileManagerServiceProvider',`

### Step 3
`php artisan vendor:publish --provider="datastat\FileManager\FileManagerServiceProvider"`

### Step 4
Add this to your main template or wherever you would like to have filemanager available
@include('filemanager::templates')

### Step 5 (Javascript)
`bower init` if needed
`bower install --save bootstrap toastr lodash jquery-file-upload `

include all libraries above. Additional to this include


summernote editor plugin is provided

### Step 6
`php artisan migrate`

needs env
RACKSPACE_USERNAME
RACKSPACE_KEY
RACKSPACE_REGION
RACKSPACE_CONTAINER
