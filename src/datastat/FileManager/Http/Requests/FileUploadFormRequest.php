<?php namespace datastat\FileManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Response;

class PartnerUpdateFormRequest extends FormRequest
{
    public function rules()
    {
        return [
        'name' => 'required|min:5',
	        'registration_number' => 'min:5',
	        'address' => 'min:5',
	        'zip' => 'min:4',
	        'city' => 'min:5',
	        'country' => 'min:5',
	        'email' => 'min:5',
	        'phone' => 'min:5',
	        'url' => 'min:5',
	        'description.sl' => 'min:5',
	        'contact_first_name' => 'min:5',
	        'contact_last_name' => 'min:5',
	        'contact_email' => 'email',
	        'contact_phone' => 'min:10',
        ];
    }

    public function authorize()
    {
        // Only allow logged in users
        // return \Auth::check();
        // Allows all users in
        // return false;
        return true;
    }

    // OPTIONAL OVERRIDE
    public function xforbiddenResponse()
    {
        // Optionally, send a custom response on authorize failure 
        // (default is to just redirect to initial page with errors)
        // 
        // Can return a response, a view, a redirect, or whatever else
        return Response::make('Permission denied foo!', 403);
    }

    // OPTIONAL OVERRIDE
    public function xresponse()
    {
        // If you want to customize what happens on a failed validation,
        // override this method.
        // See what it does natively here: 
        // https://github.com/laravel/framework/blob/master/src/Illuminate/Foundation/Http/FormRequest.php
    }
}
