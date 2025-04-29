<?php

namespace App\Http\Controllers\Api\BookingProvider;

use App\Traits\ApiResponse;
use App\Services\FCMService;
use Illuminate\Http\Request;
use App\Enum\NotificationType;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Notifications\NewNotification;
use App\Interface\BookingProviderInterface;

class BookingProviderController extends Controller
{
    use ApiResponse;
    private $bookingProviderInterface;

    /**
     * Create a new class instance.
     */

    public function __construct(BookingProviderInterface $bookingProviderInterface)
    {
        $this->bookingProviderInterface = $bookingProviderInterface;
    }

    public function book(BookingRequest $request)
    {
        try {
            $booking = $this->bookingProviderInterface->book($request->validated());
            $booking->serviceProvider->notify(new NewNotification(
                subject: 'Booking Request',
                message: 'You have a new booking',
                channels: ['database'],
                type: NotificationType::SUCCESS,
            ));

            $fcmService = new FCMService();
            $fcmService->sendNotification(
                $booking->serviceProvider->firebaseTokens->token,  
                'Booking Request',
                'You have a new booking',
            );
            return $this->success($booking, 'Booking created successfully', 201);
        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), "An unexpected error occurred", 500);
        }
    }

    public function pastBookings()
    {
        try {
            $bookings = $this->bookingProviderInterface->pastBookings();

            return $this->success($bookings, 'Past bookings fetched successfully', 200);
        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), "An unexpected error occurred", 500);
        }
    }

    public function upcomingBookings()
    {
        try {
            $bookings = $this->bookingProviderInterface->upcomingBookings();

            return $this->success($bookings, 'Upcoming bookings fetched successfully', 200);
        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), "An unexpected error occurred", 500);
        }
    }

    public function single($id)
    {
        try {
            $booking = $this->bookingProviderInterface->single($id);

            return $this->success($booking, 'Booking fetched successfully', 200);
        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), "An unexpected error occurred", 500);
        }
    }

    public function providerSingle($id)
    {
        try {
            $booking = $this->bookingProviderInterface->providerSingle($id);

            return $this->success($booking, 'Booking fetched successfully', 200);
        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), "An unexpected error occurred", 500);
        }
    }

    public function edit(BookingRequest $request, $id)
    {
        try {
            $booking = $this->bookingProviderInterface->edit($request->validated(), $id);

            return $this->success($booking, 'Booking updated successfully', 200);
        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), "An unexpected error occurred", 500);
        }
    }

    public function cancel($id)
    {
        try {
            $booking = $this->bookingProviderInterface->cancel($id);

            return $this->success($booking, 'Booking cancelled successfully', 200);

            $booking->user->notify(new NewNotification(
                subject: 'Booking Cancelled',
                message: 'Your booking has been cancelled',
                channels: ['database'],
                type: NotificationType::SUCCESS,
            ));

            $fcmService = new FCMService();
            $fcmService->sendNotification(
                $booking->user->firebaseTokens->token,  
                'Booking Cancelled',
                'Your booking has been cancelled',
                ['booking_id' => $booking->id]
            );

        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), "An unexpected error occurred", 500);
        }
    }

    public function booked($id)
    {
        try {
            $booking = $this->bookingProviderInterface->booked($id);

            return $this->success($booking, 'Booking fetched successfully', 200);

            $booking->user->notify(new NewNotification(
                message: 'Your booking has been accepted',
                channels: ['database'],
                type: NotificationType::SUCCESS,
            ));

            $fcmService = new FCMService();
            $fcmService->sendNotification(
                $booking->user->firebaseTokens->token,  
                'Booking Accepted',
                'Your booking has been accepted',
                ['booking_id' => $booking->id]
            );

        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), "An unexpected error occurred", 500);
        }
    }

    public function getProviderBookings(Request $request)
    {
        try {
            $bookings = $this->bookingProviderInterface->getProviderBookings($request->date);

            return $this->success($bookings, 'Provider bookings fetched successfully', 200);
        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), "An unexpected error occurred", 500);
        }
    }

    public function getProviderBookingsHistory()
    {
        try {
            $bookings = $this->bookingProviderInterface->getProviderBookingsHistory();

            return $this->success($bookings, 'Provider bookings history fetched successfully', 200);
        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), "An unexpected error occurred", 500);
        }
    }

    public function deleteProviderBookingsHistory()
    {
        try {
            $bookings = $this->bookingProviderInterface->deleteProviderBookingsHistory();

            return $this->success($bookings, 'Provider bookings history deleted successfully', 200);
        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), "An unexpected error occurred", 500);
        }
    }

    public function providerWithRatingSingle($id)
    {
        try {
            $booking = $this->bookingProviderInterface->providerWithRatingSingle($id);

            return $this->success($booking, 'Booking fetched successfully', 200);
        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), "An unexpected error occurred", 500);
        }
    }
}
