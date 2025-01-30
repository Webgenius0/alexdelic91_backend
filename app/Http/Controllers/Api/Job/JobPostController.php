<?php

namespace App\Http\Controllers\Api\Job;

use App\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\JobPostRequest;
use App\Interface\JobPostRepositoryInterface;

class JobPostController extends Controller
{
    use ApiResponse;

    private $JobPostRepositoryInterface;

    public function __construct(JobPostRepositoryInterface $JobPostRepositoryInterface)
    {
        $this->JobPostRepositoryInterface = $JobPostRepositoryInterface;
    }

    public function jobPost(JobPostRequest $request)
    {
        $jobpost = $this->JobPostRepositoryInterface->jobPost($request->validated());

        if (!$jobpost) {
            return $this->error([], "An error occurred while creating the job post", 500);
        }

        return $this->success($jobpost, 'Job post created successfully', 201);
    }

    public function pastJobPost()
    {
        $jobpost = $this->JobPostRepositoryInterface->pastJobPost();

        if (!$jobpost) {
            return $this->error([], "An error occurred while fetching past job post", 500);
        }

        return $this->success($jobpost, 'Past job post fetched successfully', 200);
    }

    public function upcomingJobPost()
    {
        $jobpost = $this->JobPostRepositoryInterface->upcomingJobPost();    

        if (!$jobpost) {
            return $this->error([], "An error occurred while fetching upcoming job post", 500);
        }

        return $this->success($jobpost, 'Upcoming job post fetched successfully', 200);
    }

    public function jobPostDetails($id)
    {
        $jobpost = $this->JobPostRepositoryInterface->jobPostDetails($id);

        if (!$jobpost) {
            return $this->error([], "An error occurred while fetching job post details", 500);
        }

        return $this->success($jobpost, 'Job post details fetched successfully', 200);
    }

    public function reJobPost(JobPostRequest $request, $id)
    {
        $jobpost = $this->JobPostRepositoryInterface->reJobPost($request->validated(), $id);

        if (!$jobpost) {
            return $this->error([], "An error occurred while fetching job post details", 500);
        }

        return $this->success($jobpost, 'Job post details fetched successfully', 200);
    }
}
