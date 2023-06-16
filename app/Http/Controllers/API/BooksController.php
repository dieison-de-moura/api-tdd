<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\BooksRequest;
use Illuminate\Http\Request;
use App\Models\Book;

class BooksController extends Controller
{
    private $book;

    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    public function index(Book $book)
    {
        return response()->json($book->all());
    }

    public function show($id)
    {
        $book = $this->book->findOrFail($id);
        return response()->json($book);
    }

    public function store(BooksRequest $request)
    {
        $book = $this->book->create($request->all());
        return response()->json($book, 201);
    }

    public function update($id, Request $request)
    {
        $book = $this->book->findOrFail($id);
        $book->update($request->all());
        return response()->json($book);
    }

    public function destroy($id)
    {
        $book = $this->book->findOrFail($id);
        $book->delete();
        return response()->json([], 204);
    }
}
