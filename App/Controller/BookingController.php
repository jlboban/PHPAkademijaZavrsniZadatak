<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Model\Booking;
use App\Model\Event;
use Core\Input;
use App\Model\User;
use DateTime;

class BookingController extends AbstractController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function createAction($id)
    {
        if ($this->auth->getCurrentUser() === null || !$this->auth->isLoggedIn())
        {
            $this->redirect('');
        }

        $event = Event::getOne('id', $id);
        $musicians = Event::getEventMusicians($id);
        $venues = Event::getEventVenues($id);
        $user = User::getOne('id', $this->auth->getCurrentUser()->getId());

        $currentDate = new DateTime(date("Y/m/d"));
        $startDate = new DateTime($event->getStartDate());
        $interval = date_diff($currentDate, $startDate);

        $interval->format('%R%a') > 30 ? $discount = true : $discount = false;

        $price = $this->calculatePrice($event->getPrice(), $discount ? $event->getDiscount() : '0');

        $this->view->render("User/Booking/Create", [
            'event' => $event,
            'musicians' => $musicians,
            'venues' => $venues,
            'user' => $user,
            'price' => $price,
            'discount' => $discount
        ]);
    }

    public function createSubmitAction($id)
    {
        if ($this->auth->getCurrentUser() === null || !$this->auth->isLoggedIn())
        {
            $this->redirect('');
        }

        $post = Input::validatePost();
        $userId = $this->auth->getCurrentUser()->getId();
        $eventId = $id;
        $price = $post['price'];

        Booking::insert([
            'user_id' => $userId,
            'event_id' => $eventId,
            'final_price' => $price,
        ]);


        $this->redirect("user/booking/{$eventId}");
    }

    public function deleteSubmitAction(string $id)
    {
        if ($this->auth->getCurrentUser() === null || !$this->auth->isLoggedIn())
        {
            $this->redirect('');
        }

        $bookingId = Booking::getOne('event_id', $id)->getId();

        Booking::delete('id', $bookingId);

        $this->redirect('event/view');
    }

    private function calculatePrice(string $price, string $discount = null)
    {
        $discountNum = $price * $discount;
        return $newPrice = $price - $discountNum . ".00";
    }
}
