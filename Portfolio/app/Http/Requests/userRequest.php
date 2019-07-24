<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class userRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required|numeric',  
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }
    public function messages()
    {
        return [
            'first_name.required' => ' First Name is required',
            'last_name.required'  => 'last Name is required',
            'email.required' => ' Email Address required',
            'password.required'  => 'Please enter password',
            'address.required' => ' please enter valid Address',
            'city.required'  => 'City name is must',
            'state.required' => ' State name is required',
            'zip.required'  => 'ZIP is Required',
            'image.required'  => 'Please select Image for Profile Photo',


        ];
    }
}
