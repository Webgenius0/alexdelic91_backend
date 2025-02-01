<?php
namespace App\Repositories;

use App\Exceptions\CustomException;
use App\Interface\JobPostRepositoryInterface;
use App\Models\JobPost;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;

class JobPostRepository implements JobPostRepositoryInterface
{
    use ApiResponse;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function jobPost($data)
    {
        $user = auth()->user();

        if (! $user) {
            throw new CustomException("User Unauthorized", 404);
        }

        try {

            DB::beginTransaction();

            $job = JobPost::create([
                'user_id'        => $user->id,
                'title'          => $data['title'],
                'location'       => $data['location'],
                'latitude'       => $data['latitude'],
                'longitude'      => $data['longitude'],
                'category_id'    => $data['category_id'],
                'subcategory_id' => $data['subcategory_id'],
                'notes'          => $data['notes'],
                'start_time'     => $data['start_time'],
                'end_time'       => $data['end_time'],
            ]);

            if (isset($data['date'])) {
                foreach ($data['date'] as $date) {
                    $job->jobPostDates()->create([
                        'date' => $date,
                    ]);
                }
            }

            if (! $job) {
                throw new CustomException("Job post not created", 404);
            }

            DB::commit();
            return $job;

        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function pastJobPost()
    {
        $user = auth()->user();

        if (! $user) {
            throw new CustomException("User Unauthorized", 404);
        }

        $job = JobPost::with([
            'jobPostDates',
            'category:id,category_name',
            'subcategory:id,subcategory_name',
            'user'
        ])
            ->where('user_id', $user->id)
            ->whereNot('status', 'deleted')
            ->whereDate('created_at', '<', now())
            ->get();

        if (! $job) {
            throw new CustomException("Job post not found", 404);
        }

        return $job;
    }

    public function upcomingJobPost()
    {
        $user = auth()->user();

        if (! $user) {
            throw new CustomException("User Unauthorized", 404);
        }

        $job = JobPost::with([
            'jobPostDates',
            'category:id,category_name',
            'subcategory:id,subcategory_name',
            'user'
        ])
            ->where('user_id', $user->id)
            ->whereNot('status', 'deleted')
            ->whereDate('created_at', '>=', now())
            ->get();

        if (! $job) {
            throw new CustomException("Job post not found", 404);
        }

        return $job;
    }

    public function jobPostDetails($id)
    {
        $user = auth()->user();

        if (! $user) {
            throw new CustomException("User Unauthorized", 404);
        }

        $job = JobPost::with([
            'jobPostDates',
            'category:id,category_name',
            'subcategory:id,subcategory_name',
            'user'
        ])
            ->where('id', $id)
            ->whereNot('status', 'deleted')
            ->where('user_id', $user->id)
            ->first();

        if (! $job) {
            throw new CustomException("Job post not found", 404);
        }

        return $job;
    }

    public function reJobPost($data, $id)
    {
        $user = auth()->user();

        if (! $user) {
            throw new CustomException("User Unauthorized", 404);
        }

        $job = JobPost::where('id', $id)
            ->where('user_id', $user->id)
            ->create([
                'user_id'        => $user->id,
                'title'          => $data['title'],
                'location'       => $data['location'],
                'latitude'       => $data['latitude'],
                'longitude'      => $data['longitude'],
                'category_id'    => $data['category_id'],
                'subcategory_id' => $data['subcategory_id'],
                'notes'          => $data['notes'],
                'start_time'     => $data['start_time'],
                'end_time'       => $data['end_time'],
            ]);
        if (isset($data['date'])) {
            foreach ($data['date'] as $date) {
                $job->jobPostDates()->create([
                    'date' => $date,
                ]);
            }
        }
        if (! $job) {
            throw new CustomException("An error occurred while creating the job post", 404);
        }

        return $job;
    }

    public function pastHistoryDelete()
    {
        $user = auth()->user();

        if (! $user) {
            throw new CustomException("User Unauthorized", 404);
        }

        $job = JobPost::where('user_id', $user->id)
            ->whereDate('created_at', '<', now())
            ->update(['status' => 'deleted']);

        if (! $job) {
            throw new CustomException("Data not found", 404);
        }

        return $job;
    }

    public function getJobPost()
    {
        $user = auth()->user();

        $data = User::with('serviceProviderProfile:id,user_id,category_id')
            ->where('id', $user->id)
            ->first();

        if (!$data || !$data->serviceProviderProfile) {
            throw new CustomException("User Unauthorized", 404);
        }

        $categoryId = $data->serviceProviderProfile->category_id;

        $subCategoryId = $data->serviceProviderProfile->subcategories->pluck('id')->toArray();

        // dd($subCategoryId);

        $job = JobPost::with([
           'jobPostDates',
            'category:id,category_name',
            'subcategory:id,subcategory_name',
            'user'
        ])
            ->whereNot('status', 'canceled')
            ->where('category_id', $categoryId) // Check category_id match
            ->whereIn('subcategory_id', $subCategoryId) // Check subcategory_id match
            ->get();                            // Retrieve data

        if ($job->isEmpty()) {
            throw new CustomException("Data not found", 404);
        }

        return $job;
    }

    public function singelJobPost($id)
    {
        $auth = auth()->user();

        if(!$auth){
            throw new CustomException("User Unauthorized", 404);
        }

        $job = JobPost::with([
            'jobPostDates',
            'category:id,category_name',
            'subcategory:id,subcategory_name',
            'user'
        ])
            ->where('id', $id)
            ->first();

        if (!$job) {
            throw new CustomException("Job post not found", 404);
        }

        return $job;
    }

}
