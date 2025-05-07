<?php

namespace App\Http\Controllers\Api\Job;

use App\Traits\ApiResponse;
use App\Services\FCMService;
use App\Enum\NotificationType;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\JobPostRequest;
use App\Notifications\NewNotification;
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
        try {
            $jobpost = $this->JobPostRepositoryInterface->jobPost($request->validated());

            // $fcmService = new FCMService();
            // $fcmService->sendNotification(
            //     $jobpost->user->firebaseTokens->token,
            //     'Job Post',
            //     'You have a new job post',
            //     ['job_post_id' => $jobpost->id]
            // );

            return $this->success($jobpost, 'Job post created successfully', 201);
        } catch (CustomException $e) {

            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {

            return $this->error([], "An unexpected error occurred", 500);
        }
    }

    public function pastJobPost()
    {
        try {

            $jobpost = $this->JobPostRepositoryInterface->pastJobPost();
            return $this->success($jobpost, 'Past job post fetched successfully', 200);
        } catch (CustomException $e) {

            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {

            return $this->error([], "An unexpected error occurred", 500);
        }
    }

    public function upcomingJobPost()
    {
        try {
            $jobpost = $this->JobPostRepositoryInterface->upcomingJobPost();
            return $this->success($jobpost, 'Upcoming job post fetched successfully', 200);
        } catch (CustomException $e) {

            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {

            return $this->error([], "An unexpected error occurred", 500);
        }
    }

    public function jobPostDetails($id)
    {
        try {
            $jobpost = $this->JobPostRepositoryInterface->jobPostDetails($id);
            return $this->success($jobpost, 'Job post details fetched successfully', 200);
        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {

            return $this->error([], "An unexpected error occurred", 500);
        }
    }

    public function reJobPost(JobPostRequest $request, $id)
    {
        try {
            $jobpost = $this->JobPostRepositoryInterface->reJobPost($request->validated(), $id);
            return $this->success($jobpost, 'Job post details fetched successfully', 200);
        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error([], "An unexpected error occurred", 500);
        }
    }

    public function pastHistoryDelete()
    {
        try {
            $jobpost = $this->JobPostRepositoryInterface->pastHistoryDelete();
            return $this->success($jobpost, 'Past job history deleted successfully', 200);
        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error([], "An unexpected error occurred", 500);
        }
    }

    public function getJobPost()
    {
        try {
            $jobpost = $this->JobPostRepositoryInterface->getJobPost();
            return $this->success($jobpost, 'Job post fetched successfully', 200);
        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error([], "An unexpected error occurred", 500);
        }
    }

    public function singelJobPost($id)
    {
        try {
            $jobpost = $this->JobPostRepositoryInterface->singelJobPost($id);
            return $this->success($jobpost, 'Job post details fetched successfully', 200);
        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error([], "An unexpected error occurred", 500);
        }
    }

    public function jobPostEdit(JobPostRequest $request, $id)
    {
        try {
            $jobpost = $this->JobPostRepositoryInterface->jobPostEdit($request->validated(), $id);
            return $this->success($jobpost, 'Job post updated successfully', 200);
        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error([], "An unexpected error occurred", 500);
        }
    }

    public function jobPostCancel($id)
    {
        try {
            $jobpost = $this->JobPostRepositoryInterface->jobPostCancel($id);

            // $jobpost->serviceProvider->notify(new NewNotification(
            //     subject: 'Cancelled',
            //     message: 'Your job post has been cancelled',
            //     channels: ['database'],
            //     type: NotificationType::SUCCESS,
            // ));

            // $fcmService = new FCMService();
            // $fcmService->sendMessage(
            //     $jobpost->serviceProvider->firebaseTokens->token, 
            //     'Cancelled',
            //     'Your job post has been cancelled',
            // );

            return $this->success($jobpost, 'Job post cancelled successfully', 200);
        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error([], "An unexpected error occurred", 500);
        }
    }
}
