<?php

namespace App\Interface;

interface CategoryRepositoryInterface
{
    public function getAllCategories();
    public function getSubCategories($category);
    
}
