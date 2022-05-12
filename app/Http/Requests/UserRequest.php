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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "username" => "required|unique:users,username|regex:/^[\w\-\@\+\?\!\.]{3,19}$/",
            "email" => "required|email|unique:users,email",
            "password"=> "required|regex:/^(?=.*[a-zšđčćž])(?=.*[A-ZŠĐČĆŽ])(?=.*[\d]).{7,}$/",
            "role" => "nullable|regex:/^[\d]{1,3}$/"
        ];
    }

    public function messages()
    {
        return [
            "username.required" => "Username is required",
            'username.unique'    => 'Username is exist',
            "username.regex" => "Username is not valid",
            "email.unique" => "Email is exist",
            "email.required" => "Email is required",
            "email.email" => "Email is not valid",
            "password.regex" => "Password must have at least one uppercase letter, lowercase letter and digit, 7 characters long ",
            "password.required" => "Password is required"
        ];
    }

}
