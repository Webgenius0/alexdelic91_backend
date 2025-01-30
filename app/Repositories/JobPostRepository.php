<?php
namespace App\Repositories;

use App\Models\JobPost;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interface\JobPostRepositoryInterface;

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
            return $this->error([], "User Unauthorized", 404);
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
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
            ]);

            if (isset($data['date'])) {
                foreach ($data['date'] as $date) {
                    $job->jobPostDates()->create([
                        'date' => $date,
                    ]);
                }
            }

            DB::commit();
            return $job;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return null;
        }
    }

    public function pastJobPost()
    {
        $user = auth()->user();

        if (! $user) {
            return $this->error([], "User Unauthorized", 404);
        }

        $job = JobPost::with([
            'user', 
            'category:id,category_name', 
            'subcategory:id,subcategory_name'
        ])
        ->where('user_id', $user->id)
        ->whereDate('created_at', '<', now())
        ->get();

        return $job;
    }

    public function upcomingJobPost()
    {
        $user = auth()->user();

        if (! $user) {
            return $this->error([], "User Unauthorized", 404);
        }

        $job = JobPost::with([
            'user',
            'category:id,category_name',
            'subcategory:id,subcategory_name'
        ])
        ->where('user_id', $user->id)
        ->whereDate('created_at', '>=', now())
        ->get();

        return $job;
    }

    public function jobPostDetails($id)
    {
        $user = auth()->user();

        if (!$user) {
            return $this->error([], "User Unauthorized", 404);
        }

        $job = JobPost::with([
            'user',
            'category:id,category_name',
            'subcategory:id,subcategory_name'
        ])
        ->where('id', $id)
        ->where('user_id', $user->id)
        ->first();

        return $job;
    }

    public function reJobPost($data, $id)
    {
        $user = auth()->user();

        if (!$user) {
            return $this->error([], "User Unauthorized", 404);
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
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
        ]);
        if (isset($data['date'])) {
            foreach ($data['date'] as $date) {
                $job->jobPostDates()->create([
                    'date' => $date,
                ]);
            }
        }

        return $job;
    }

}
