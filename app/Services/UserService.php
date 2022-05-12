<?php

namespace App\Services;

use App\Contracts\UserContract;
use App\DTO\UserDTO;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\UserAdminRequest;
use App\Http\Requests\UserRequest;
use App\Validator\ValidatorDataU;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\isEmpty;

class UserService extends BaseService implements UserContract
{
   /* private $validator;

    public function __construct(ValidatorDataU $validator)
    {
        $this->validator = $validator;
    }*/

 private $tokenEmail;

    public function getUsers(PaginateRequest $request): array
    {
        $page = $request->get('page');
        $perPage = $request->get('perPage');
        $users =  User::with('role');
        $usersPag  = $this->generatePagedResponse($users, $perPage , $page);
       // $usrCount = DB::table('users')->count();

        $usersArr = [];

        foreach ($usersPag['data'] as $user)
        {
            $userDTO = new UserDTO();

            $userDTO->id = $user->id;
            $userDTO->username = $user->username;
            $userDTO->email = $user->email;
            $userDTO->role = array(
                "id" => $user->role->id,
                "name" => $user->role->name
            );
            $userDTO->created = $user->created_at;
            $userDTO->updated = $user->updated_at;
            $usersArr[] = $userDTO;

        }

     //   return array( 'data' => $usersArr , 'count' => $usrCount);
        return array( 'data' => $usersArr );
    }

    public function findUser(int $id): ?UserDTO
    {
        $user = User::with('role')->findOrFail($id);
        if($user != null) {
            $userDTO = new UserDTO();
            $userDTO->id = $user->id;
            $userDTO->username = $user->username;
            $userDTO->email = $user->email;
            $userDTO->role = array(
                "id" => $user->role->id,
                "name" => $user->role->name
            );
            $userDTO->created = $user->created_at;
            $userDTO->updated = $user->updated_at;
            return $userDTO;
        }
        return null;
    }


    public function loginUser(string $email, string $password): UserDTO
    {
        $user = User::with('role')->where([['email' , '=', $email] , [ 'password' , '=' , Hash::make($password) ]])->first();
        $userDTO = new UserDTO();
        $userDTO->id = $user->id;
        $userDTO->username = $user->username;
        $userDTO->email = $user->email;
        $userDTO->role = $user->role->name;
        $userDTO->created = $user->created_at;
        $userDTO->updated = $user->updated_at;
        return $userDTO;
    }



    public function addUser(UserRequest $request)
    {
        $username = $request->get('username');
        $email = $request->get('email');
        $password = $request->get('password');
        $role = $request->get('role');

        $this->tokenEmail = $this->emailToken($email);


        $addData = [
              'username' => $username,
                'email' => $email,
                'password' => Hash::make($password),
                'verify_token' => $this->tokenEmail,
                'created_at' => Carbon::now()->toDateTime(),
                'updated_at' =>null
        ];


        if (isset($role)) {
            $addData['role_id'] = $role;
        }

            $user = User::create($addData);
            $user->save();

    }


    public function updateUser(UserAdminRequest $request, int $id )
    {
      $username = $request->get('username');
        $email = $request->get('email');
        $password = $request->get('password');
        $role = $request->get('role');

        $user = User::findOrFail($id);


       $updateData = [
            'username' => $username,
            'email' => $email,
            'updated_at' => Carbon::now()->toDateTime()
        ];

        if (isset($password)) {
            $updateData['password'] = Hash::make($password);
        }
        if (isset($role)) {
            $updateData['role_id'] = $role;
        }
            $user->update($updateData);

    }

    public function deleteUser(int $id)
    {
        $user = User::findOrFail($id);

        if ($user != null ) {
            $user->delete();
        }
    }
}
