<?php

namespace App\Interface;

interface JobPostRepositoryInterface
{
    public function jobPost($data);
    public function pastJobPost();
    public function upcomingJobPost();
    public function jobPostDetails($id);
    public function reJobPost($data, $id);
    public function pastHistoryDelete();

    public function getJobPost();
    public function singelJobPost($id);
}
