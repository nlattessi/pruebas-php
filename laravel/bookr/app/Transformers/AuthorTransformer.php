<?php

namespace App\Transformers;

use App\Author;
use League\Fractal\TransformerAbstract;

class AuthorTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'books'
    ];

    public function includeBooks(Author $author)
    {
        return $this->collection($author->books, new BookTransformer());
    }

    public function transform(Author $author)
    {
        return [
            'id' => $author->id,
            'name' => $author->name,
            'biography' => $author->biography,
            'gender' => $author->gender,
            'created' => $author->created_at->toIso8601String(),
            'updated' => $author->updated_at->toIso8601String(),
        ];
    }
}