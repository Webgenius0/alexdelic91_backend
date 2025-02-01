<?php

namespace App\Interface;

interface CategoryInterface
{
    public function getAllCategories();
    public function getSubCategories($category);
    
}
