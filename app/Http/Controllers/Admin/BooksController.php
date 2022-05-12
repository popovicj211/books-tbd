<?php

namespace App\Http\Controllers\Admin;

use App\Exports\BooksExport;
use App\Http\Controllers\BaseController;
use App\Http\Requests\BookRequest;
use App\Http\Requests\PaginateRequest;
use App\Services\BookService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class BooksController extends BaseController
{

    public function __construct(BookService $service)
    {
        parent::__construct($service);
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(PaginateRequest $request)
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $this->service->addBook($request);
            $this->result['addBook'] = $this->Created('Book is successfully added');
        }catch (QueryException $e){
            Log::error("Error, add book:" . $e->getMessage());
            $this->result['addBook']  = $this->ServerError("Error ,book is not added on server!");
        }
        return $this->result['addBook'];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $this->data['showBook'] = $this->service->findBook($id);
            $this->result['showBook'] = $this->Ok($this->data['showBook']);
        } catch (QueryException $e) {
            Log::error("Error, show book:" . $e->getMessage());
            $this->result['showBook'] = $this->ServerError("Error, book is not get from server");
        }catch (ModelNotFoundException $e){
            $this->result['showBook'] = $this->NotFound("Book not found");
        }

        return $this->result['showBook'];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        try {
            $this->data['editBook'] = $this->service->findBook($id);
            $this->result['editBook'] = $this->Ok($this->data['editBook']);
        } catch (QueryException $e) {
            Log::error("Error, edit book:" . $e->getMessage());
            $this->result['editBook'] = $this->ServerError("Error, book is not get from server");
        }catch (ModelNotFoundException $e){
            $this->result['editBook'] = $this->NotFound("Book not found");
        }

        return $this->result['editBook'];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $this->service->updateBook($request, $id);
            $this->result['updateBook'] = $this->NoContent();
            Excel::download(new BooksExport(), 'books-collection.xlsx');
        }catch (QueryException $e){
            Log::error("Error, book is not updated:" . $e->getMessage());
            $this->result['updateBook']  = $this->ServerError("Error ,book is not updated");
        }catch (ModelNotFoundException $e){
            $this->result['updateBook'] = $this->NotFound("Book not found");
        }
        return $this->result['updateBook'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $this->service->deleteBook($id);
            $this->result['deleteBook'] = $this->NoContent();
        } catch (QueryException $e) {
            Log::error("Error, book is not deleted:" . $e->getMessage());
            $this->result['deleteBook'] = $this->ServerError("Error, book is not deleted");
        }catch (ModelNotFoundException $e){
            $this->result['deleteBook'] = $this->NotFound("Book not found");
        }
        return $this->result['deleteBook'];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function fileExport()
    {
         Excel::download(new BooksExport(), 'books-collection.xlsx');
    }

}
