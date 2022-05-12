<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Mail\PHPMail;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AuthController extends BaseController
{

    private $mailer;
    private $verifyEmail;

    public function __construct(UserService $service)
    {
        parent::__construct($service);
        $this->service = $service;
        $this->mailer = new PHPMail();
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function login(LoginRequest $request){

        $credentials = $request->only(['email', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return  response()->json(['message' => 'Successfully logged in','user'=> $this->me() , 'token' => $this->respondWithToken($token) ]);

    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return response()->json($this->respondWithToken(auth()->refresh()));
    }

/*

    public function verify($tokenemail){

        try {
            $user = User::where('email_token', '=', $tokenemail);
            $user->update([
                'email_verified_at' => Carbon::now()
            ]);

        } catch (QueryException $e) {
            Log::error("Error, verification user:" . $e->getMessage());

            return redirect()->route('showverify')->with("message", "Verification is not successfully");
        } catch (ModelNotFoundException $e) {
            return redirect()->route('showverify');
        }
        return response()->json("Verification is successfully", 200);
    }

*/

    /**
     * Get the token array structure.
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function register(UserRequest $request){

        $email = $request->get('email');
        $this->tokenEmail = md5(time().$email.rand(1,10000));
        $this->mailer->setToken($this->tokenEmail);
        $this->mailer->setEmail($email);
   //     $this->mailer->verification();
        try {
            $this->service->addUser($request);
            $credentials = $request->only(['email', 'password']);
            $token = auth()->attempt($credentials);
            $this->result['signUp'] = response()->json(['message' => 'Successfully logged in','user'=> $this->me() , 'token' => $this->respondWithToken($token) ]);

        }catch (QueryException $e){
            Log::error("Error, register user:".$e->getMessage());
            $this->result['signUp'] =  $this->ServerError("Error, user is not registered");
        }
        return $this->result['signUp'];

    }

    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];

    }



}
