<?php

namespace App\Services;

use App\Contracts\BookConstract;
use App\DTO\BookDTO;
use App\Http\Requests\PaginateRequest;
use App\Imports\AuthorsImport;
use App\Imports\BooksAuthorsImport;
use App\Imports\BooksImport;
use App\Imports\BooksPublishersImport;
use App\Imports\PublishersImport;
use App\Models\Author;
use App\Models\Book;
use App\Models\BookAuthor;
use App\Models\Publisher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class BookService extends BaseService implements BookConstract
{

    public function getBooks(PaginateRequest $request): array
    {
        $page = $request->get('page');
        $perPage = $request->get('perPage');
        $search = $request->get('search');
        $filterPublishedYears = $request->get('filterYears');
        $publishedYearsIsBefore = (boolean)$request->get('filterYearsIsBefore');

        if($search){
            if($filterPublishedYears){
                $publishedYearsDateFormat = date("Y-m-d",strtotime("-".$filterPublishedYears." years"));
                if(!$publishedYearsIsBefore){
                    $books =  Book::with(['publishers','authors'])->where('published_year', '>=', $publishedYearsDateFormat)->where('published_year', 'LIKE', '%'.strtolower($search).'%');
                }else{
                    $books =  Book::with(['publishers','authors'])->where('published_year', '<', $publishedYearsDateFormat)->where('published_year', 'LIKE', '%'.strtolower($search).'%');
                }
            }else{
                $books =  Book::with(['publishers','authors'])->where('name', 'LIKE', '%'.strtolower($search).'%');
            }

        }else{
            $books =  Book::with(['publishers','authors']);
        }

        $booksPag  = $this->generatePagedResponse($books, $perPage , $page);

        $booksArr = [];

        foreach ($booksPag['data'] as $book)
        {
            $bookDTO = new BookDTO();

            $bookDTO->id = $book->id;
            $bookDTO->name = $book->name;
            foreach ($book->authors as $author){
                $bookDTO->authors = array($author->name);
            }
            foreach ($book->publishers as $publisher)
            {
                $bookDTO->publisher =  $publisher->name;
            }

            $bookDTO->publishedyear = $book->published_year;
            $bookDTO->created = $book->created_at;
            $bookDTO->updated = $book->updated_at;
            $booksArr[] = $bookDTO;
        }


        return array( 'data' => $booksArr ,  'count' => $booksPag['count']);

    }



    public function findBook(int $id): ?BookDTO
    {
        $book = Book::with(['publishers','authors'])->findOrFail($id);
        if($book != null) {
            $bookDTO = new BookDTO();
            $bookDTO->id = $book->id;
            $bookDTO->name = $book->name;
            foreach ($book->authors as $author){
                $bookDTO->authors = array($author->name);
            }
            foreach ($book->publishers as $publisher)
            {
                $bookDTO->publisher =  $publisher->name;
            }

            $bookDTO->publishedyear = $book->published_year;
            $bookDTO->created = $book->created_at;
            $bookDTO->updated = $book->updated_at;
            return $bookDTO;
        }
        return null;
    }

    public function addBook(Request $request)
    {
       Excel::import(new BooksImport(), $request->file);
        Excel::import(new AuthorsImport(), $request->file);
        Excel::import(new BooksAuthorsImport(new Book(), new Author()), $request->file);
        Excel::import(new PublishersImport(), $request->file);
        Excel::import(new BooksPublishersImport(new Book(),new Publisher()), $request->file);


    }


    public function updateBook(Request $request,int $id)
    {


         $name = $request->get('name');
         $publishedYear = $request->get('published_year');
         $authors_ids = (array)$request->get('author_id');
         $pub_id = $request->get('pub_id');


         $book = Book::findOrFail($id);
         $book->update([
             'name' => $name,
             'published_year' => $publishedYear,
             'updated_at' => Carbon::now()->toDateTime()
         ]);

         if (count($authors_ids) > 0) {

             foreach ($authors_ids as $author_id) {
                 $ifExistBookAuthor = BookAuthor::where([['book_id', '=', $id], ['author_id', '=', $author_id]])->first();
             if ($ifExistBookAuthor != null) {
                 $updateBookAuthor = BookAuthor::where(['book_id' => $id]);

                 $updateBookAuthor->book_id = $id;
                 $updateBookAuthor->author_id = $author_id;
                 $updateBookAuthor->updated_at = Carbon::now()->toDateTime();
              }
             }

         }
        if (isset($pub_id)) {
            $ifExistBookPub = BookAuthor::where([['book_id', '=', $id], ['pub_id', '=', $pub_id]])->first();
            if ($ifExistBookPub != null) {
                $updateBookPub = BookAuthor::where(['book_id' => $id]);

                $updateBookPub->book_id = $id;
                $updateBookPub->pub_id = $pub_id;
                $updateBookPub->updated_at = Carbon::now()->toDateTime();
            }

        }


    }


    public function deleteBook(int $id)
    {
        $book = Book::findOrFail($id);

        if ($book != null ) {
            $book->delete();
        }
    }




}
