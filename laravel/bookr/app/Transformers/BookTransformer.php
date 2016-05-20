<?php

namespace App\Transformers;

use App\Book;
use League\Fractal\TransformerAbstract;

class BookTransformer extends TransformerAbstract
{
    public function transform(Book $book)
    {
        return [
            'id' => $book->id,
            'title' => $book->title,
            'description' => $book->description,
            'author' => $book->author,
            'created' => $book->created_at->toIso8601String(),
            'updated' => $book->updated_at->toIso8601String(),
        ];
    }
}