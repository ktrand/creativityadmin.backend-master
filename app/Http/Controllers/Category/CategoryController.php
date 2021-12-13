<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Modules\Category\Models\Category;
use App\Modules\Category\Requests\CategoryStoreRequest;
use App\Modules\Category\Requests\CategoryUpdateRequest;
use App\Modules\Category\Requests\DestroySelectedRequest;
use App\Modules\Category\UseCases\CategoryUseCase;

class CategoryController extends Controller
{
    private $categoryUseCase;

    public function __construct(CategoryUseCase $categoryUseCase)
    {
        $this->categoryUseCase = $categoryUseCase;
    }

    public function getAll()
    {
        $categories = Category::all();

        return compact('categories');
    }

    public function store(CategoryStoreRequest $request)
    {
        $category = $this->categoryUseCase->create($request);

        return compact('category');
    }

    public function update(CategoryUpdateRequest $request, $categoryId)
    {
        $category = $this->categoryUseCase->update($request, $categoryId);

        return compact('category');
    }

    public function destroy($categoryId)
    {
        $this->categoryUseCase->destroy($categoryId);

        return response()->json('success', 200);
    }

    public function destroySelected(DestroySelectedRequest $selectedRequest)
    {
        $this->categoryUseCase->destroySelected($selectedRequest);

        return response()->json('success', 200);
    }
}
