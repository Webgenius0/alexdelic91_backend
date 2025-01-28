<?php

namespace App\Repositories\Contracts;

use App\Models\Category;

interface CategoryRepositoryInterface
{
    public function getAllCategories();
    public function getSubCategories($category);
    
}
