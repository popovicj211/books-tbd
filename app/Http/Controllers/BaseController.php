<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{

    protected $data;
    protected $service;
    protected $result;

    protected const OK = 200;
    protected const CREATED = 201;
    protected const NO_CONTENT = 204;
    protected const BAD_REQUEST = 400;
    protected const UNAUTHORIZED = 401;
    protected const FORBIDDEN = 403;
    protected const NOT_FOUND = 404;
    protected const CONFLICT = 409;
   protected const UNPROCCESSABLE_ENTITY = 422;
    protected const INTERNAL_SERVER_ERROR = 500;
    protected const BAD_GATEWAY = 502;

    public function __construct($service)
    {
        $this->service = $service;
    }


    protected function Ok($message)
    {
        return response()->json($message, self::OK);
    }

    protected function Created($message)
    {
        return response()->json(['message' => $message], self::CREATED);
    }

    protected function NoContent()
    {
        return response()->json(null, self::NO_CONTENT);
    }

    protected function BadRequest($error)
    {
        return response()->json(['error' => $error], self::BAD_REQUEST);
    }

    public function ServerError($error){
        return response()->json(['error' => $error], self::INTERNAL_SERVER_ERROR);
    }

    protected function Unauthorized($error)
    {
        return response()->json(['error' => $error], self::UNAUTHORIZED);
    }

    protected function Conflitct($error)
    {
        return response()->json(['error' => $error], self::CONFLICT);
    }

    protected function NotFound($error)
    {
        return response()->json(['error' => $error], self::NOT_FOUND);
    }
}
