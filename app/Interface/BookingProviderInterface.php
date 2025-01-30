<?php

namespace App\Interface;

interface BookingProviderInterface
{
    public function book($data);
    public function pastBookings();
    public function upcomingBookings();
    public function single($id);
    public function providerSingle($id);
    public function edit($data, $id);
    public function cancel($id);
    public function booked($id);
    public function getProviderBookings($date);
}
