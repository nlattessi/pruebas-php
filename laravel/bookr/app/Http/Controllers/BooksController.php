<?php

namespace App\Http\Controllers;

use App\Book;
use App\Transformers\BookTransformer;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BooksController extends Controller
{
    public function index()
    {
        return $this->collection(Book::all(), new BookTransformer);
    }

    public function show($id)
    {
        return $this->item(Book::findOrFail($id), new BookTransformer);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'author' => 'required'
        ],[
            'description.required' => 'Please fill out the :attribute.'
        ]);
        
        $book = Book::create($request->all());
        $data = $this->item($book, new BookTransformer);

        return response()->json($data, 201, [
            'Location' => route('books.show', ['id' => $book->id])
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $book = Book::findOrFail($id);    
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => [
                    'message' => 'Book not found'
                ]
            ], 404);
        }

        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'author' => 'required'
        ],[
            'description.required' => 'Please fill out the :attribute.'
        ]);
        
        $book->fill($request->all());
        $book->save();

        return $this->item($book, new BookTransformer);
    }

    public function destroy($id)
    {
        try {
            $book = Book::findOrFail($id);    
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => [
                    'message' => 'Book not found'
                ]
            ], 404);
        }
        
        $book->delete();

        return response(null, 204);
    }
}