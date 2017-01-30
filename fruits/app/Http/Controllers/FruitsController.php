<?php

namespace App\Http\Controllers;

use App\Fruit;
use App\Http\Requests\StoreFruitRequest;
use App\Transformers\FruitsTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;

class FruitsController extends Controller
{
    use Helpers;

    public function index()
    {
        $fruits = Fruit::all();

        return $this->collection($fruits, new FruitsTransformer);
    }

    public function show($id)
    {
        $fruit = Fruit::where('id', $id)->first();

        if ($fruit) {
            return $this->item($fruit, new FruitsTransformer);
        }

        return $this->response->errorNotFound();
    }

    public function store(StoreFruitRequest $request)
    {    
        if (Fruit::Create($request->all())) {
            return $this->response->created();
        }

        return $this->response->errorBadRequest();
    }

    public function destroy($id)
    {
        $fruit = Fruit::find($id);

        if ($fruit) {
            $fruit->delete();
            return $this->response->noContent();
        }

        return $this->response->errorBadRequest();
    }
}
