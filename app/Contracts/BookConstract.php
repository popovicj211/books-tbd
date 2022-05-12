<?php

namespace App\Contracts;

use App\DTO\BookDTO;
use App\Http\Requests\BookRequest;
use App\Http\Requests\PaginateRequest;
use Illuminate\Http\Request;

interface BookConstract
{
    public function getBooks(PaginateRequest $request): array;
    public  function findBook(int $id): ?BookDTO;
    public function addBook(Request $request);
    public function updateBook(Request $request, int $id );
    public function deleteBook( int $id);
}
