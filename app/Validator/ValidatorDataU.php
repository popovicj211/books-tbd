<?php

namespace App\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ValidatorDataU
{
     private $data;

    public function validateUpdateUser($model, $request, $updateData,$messages){
        $username = $request->get('username');
        $email = $request->get('email');
        $password = $request->get('password');
        $role = $request->get('role');



        if (isset($password)) {
            $messages = [

                'username.required'    => 'Username is required',
                'username.regex'    => 'Username is not valid',
                'email.required'    => 'Email is required',
                'email.email'    => 'Email is not valid',
                'role.required'    => 'Role is required',
                'password.regex'    => 'Password must have at least one uppercase letter, lowercase letter and digit, 7 characters long',
            ];

            $validate = Validator::make($request->all(), [
                'username' => 'required|unique:users,username|regex:/^[\w\-\@\+\?\!\.]{3,19}$/',
                'email' => 'required|email|unique:users,email',
                'password'=> 'required|regex:/^(?=.*[a-zšđčćž])(?=.*[A-ZŠĐČĆŽ])(?=.*[\d]).{7,}$/',
                'role' => 'required'
            ] , $messages);
            if(!$validate->fails()) {
                $model->update([
                    'username' => $username,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'role_id' => $role,
                    'updated_at' => Carbon::now()->toDateTime()
                ]);
            }else{
                return response()->json($validate->errors());
            }
        } else {
            $messages = [
                'username.required'    => 'Username is required',
                'username.regex'    => 'Username is not valid',
                'email.required'    => 'Email is required',
                'email.email'    => 'Email is not valid',
                'role.required'    => 'Role is required',
            ];
            $validate = Validator::make($request->all(), [
                'username' => 'required|unique:users,username|regex:/^[\w\-\@\+\?\!\.]{3,19}$/',
                'email' => 'required|email|unique:users,email',
                'role' => 'required'
            ], $messages);
            if(!$validate->fails()) {
                $model->update([
                    'username' => $username,
                    'email' => $email,
                    'role_id' => $role,
                    'updated_at' => Carbon::now()->toDateTime()
                ]);
            }else{
                return response()->json($validate->errors());
            }
        }
    }
}
