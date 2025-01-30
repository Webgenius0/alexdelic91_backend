<?php

namespace App\Interface;

interface BookingProviderInterface
{
    public function book($data);
    public function pastBookings();
    public function upcomingBookings();
    public function single($id);
    public function edit($data, $id);
}
