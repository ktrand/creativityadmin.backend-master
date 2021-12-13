<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 4/23/2020
 * Time: 1:07 PM
 */

namespace App\Modules\Category\UseCases;


use App\Modules\Category\CategoryService;
use App\Modules\Category\Models\Category;

class CategoryUseCase
{
    public function create($request)
    {
        $imgPath = $request->file('img')->store('images');

        $category = Category::create([
            'title' => $request->title,
            'img' => $imgPath,
            'slug' => CategoryService::convertToLatin($request->title),
        ]);

        return $category;
    }

    public function update($request, $categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $category->title = $request->title;
        if($request->file('img')){
            $imgPath = $request->file('img')->store('images');
            $category->img = $imgPath;
        }

        $category->update();

        return $category;
    }

    public function destroy($categoryId):void
    {
        $category = Category::findOrFail($categoryId);

        $category->delete();
    }

    public function destroySelected($selectedRequest):void
    {
        foreach ($selectedRequest->selected as $id) {
            $category = Category::find($id);
            $category->delete();
        }
    }
}