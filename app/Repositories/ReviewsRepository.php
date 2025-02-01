<?php

namespace App\Repositories;

use App\Interface\ReviewsInterface;
use App\Models\Feedback;

class ReviewsRepository implements ReviewsInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getReviews($query)  {
       
        switch ($query) {
            case 'only_bad_reviews':
                $data = Feedback::whereBetween('rating',[1, 3])->with(['user','serviceProvider','booking'])->get();
                break;
            case 'newest_reviews':
                $data = Feedback::orderBy('created_at','desc')->with(['user','serviceProvider','booking'])->get();
                break;
            case 'oldest_reviews':
                $data = Feedback::orderBy('created_at','asc')->with(['user','serviceProvider','booking'])->get();
                break;
            case 'only_good_reviews':
                $data = Feedback::whereBetween('rating',[4, 5])->with(['user','serviceProvider','booking'])->get();
                break;
            case 'unpopular_reviews':
                $data = Feedback::where('rating',1)->with(['user','serviceProvider','booking'])->get();
                break;
            default:
                $data = Feedback::with(['user','serviceProvider','booking'])->get();
                break;
        }
       return $data;
    }
}
