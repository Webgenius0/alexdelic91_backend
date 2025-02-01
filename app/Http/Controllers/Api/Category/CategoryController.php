<?php

namespace App\Http\Controllers\Api\Category;

use App\Http\Controllers\Controller;
use App\Interface\CategoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    use ApiResponse;
    

    private $categoryRepository;

    public function __construct(CategoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get all categories
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function categories() : JsonResponse
    {
        $categories = $this->categoryRepository ->getAllCategories();

        return $this->success($categories, 'Categories fetched successfully', 200);
    }
    

    /**
     * Get all sub categories
     *
     * @param  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function subCategories($category) : JsonResponse
    {
        $subCategories =  $this->categoryRepository ->getSubCategories($category);

        return $this->success($subCategories, 'Sub Categories fetched successfully', 200);
    }

    
}
