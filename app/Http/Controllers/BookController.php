<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaginateRequest;
use App\Services\BookService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookController extends BaseController
{
    public function __construct(BookService $service)
    {
        parent::__construct($service);
        $this->service = $service;
    }

    public function getAllBooks(PaginateRequest $request)
    {


        try {
            $this->data['books'] = $this->service->getBooks($request);
            $this->result['books'] = $this->Ok($this->data['books']);
        } catch (QueryException $e) {
            Log::error("Error, get books:" . $e->getMessage());
            $this->result['books'] = $this->ServerError("Error, books are not get from server");
        }catch (ModelNotFoundException $e){
            $this->result['books'] = $this->NotFound("Books not found");
        }

        return $this->result['books'];
    }




}
