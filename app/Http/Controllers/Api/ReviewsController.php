<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interface\ReviewsInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Js;

class ReviewsController extends Controller
{
       use ApiResponse;

       private $reviewsRepository,$query,$reviews;


       public function __construct(ReviewsInterface $reviewsRepository) {
                $this->reviewsRepository = $reviewsRepository;
       }

       /**
        * Get all reviews
        *
        * @param  $request
        * @return \Illuminate\Http\JsonResponse
        */
       public function reviews(Request $request) :JsonResponse {
          
              $query = $request->query('q','');
     
              $reviews = $this->reviewsRepository->getReviews($query);
       
              return $this->success($reviews,'Data Fetch Successfully',200);
       }
}
