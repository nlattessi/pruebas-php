<?php

namespace App\Transformers;

use App\Author;
use League\Fractal\TransformerAbstract;

class AuthorTransformer extends TransformerAbstract
{
    public function transform(Author $author)
    {
        return [
        ];
    }
}