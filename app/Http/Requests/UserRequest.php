<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required|max:100|unique:clients,client_name',
            'address1' => 'required',
            'address2' => 'required',
            'city' => 'required|max:100',
            'state' => 'required|max:100',
            'country' => 'required|max:100',
            'zipCode' => 'required|max:20',
            'phoneNo1' => 'nullable|numeric',
            'phoneNo2' => 'nullable|numeric',
            'user.firstName' => 'required|max:50',
            'user.lastName' => 'max:50',
            'user.email' => request()->route('user') 
                ? 'required|email|max:150|unique:users,email,' . request()->route('user')
                : 'required|email|max:150|unique:users,email',
            'user.password' => request()->route('user') ? 'nullable' : 'required|max:255|confirmed'
        ];
    }
}
