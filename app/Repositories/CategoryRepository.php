<?php
namespace App\Repositories;

use App\Models\Category;
use App\Models\Subcategory;
use App\Interface\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * Get all categories
     *
     * @return $categories
     */

    public function getAllCategories()
    {
        return Category::all();
    }


    /**
     * Get all sub categories
     *
     * @param $category
     * @return mixed
     */

    public function getSubCategories ($category)
    {
        return Subcategory::where('category_id', $category)->get();
    }
}
