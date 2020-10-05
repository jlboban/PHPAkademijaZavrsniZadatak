<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Model\Event;

use App\Model\Musician;
use App\Model\Venue;
use App\Validation\EventValidator;
use App\Validation\ImageValidator;
use Core\Input;
use DateTime;

class EventController extends AbstractController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function listAction()
    {
        if ($this->auth->getCurrentUser() === null || !$this->auth->getCurrentUser()->isAdmin() || !$this->auth->isLoggedIn())
        {
            $this->redirect('');
        }

        $events = Event::getAll();

        $this->view->render("Admin/Event/List", [
            'events' => $events
        ]);
    }

    public function viewAction($id)
    {
        if ($this->auth->getCurrentUser() === null || !$this->auth->isLoggedIn())
        {
            $this->redirect('');
        }

        if ($id)
        {
            $event = Event::getOne('id', $id);
            $musicians = Event::getEventMusicians($id);
            $venues = Event::getEventVenues($id);

            $currentDate = new DateTime(date("Y/m/d"));
            $startDate = new DateTime($event->getStartDate());
            $interval = date_diff($currentDate, $startDate);
            $daysUntilEvent = $interval->format('%R%a');
            $daysUntilEvent > 30 ? $discount = true : $discount = false;

            $price = $this->calculatePrice($event->getPrice(), $discount ? $event->getDiscount() : '0');

            $this->view->render("Pages/Event", [
                'event' => $event,
                'musicians' => $musicians,
                'venues' => $venues,
                'daysUntilEvent' => $daysUntilEvent,
                'price' => $price,
                'discount' => $discount
            ]);

            $event = Event::getOne('id', $id);
        }
        else
        {
            $event = Event::getAll();

            $this->view->render("Pages/Event", [
                'event' => $event
            ]);
        }
    }

    public function createAction()
    {
        if ($this->auth->getCurrentUser() === null || !$this->auth->getCurrentUser()->isAdmin() || !$this->auth->isLoggedIn())
        {
            $this->redirect('');
        }

        $this->view->render("Admin/Event/Create", [
            'musicians' => Musician::getAll(),
            'venues' => Venue::getAll()
        ]);

        // Clear errors and data if they exist for next validation
        $this->session->resetFormInput();
    }

    public function createSubmitAction()
    {
        if ($this->auth->getCurrentUser() === null || !$this->auth->getCurrentUser()->isAdmin() || !$this->auth->isLoggedIn())
        {
            $this->redirect('');
        }

        $eventValidator = new EventValidator();
        $imageValidator = new ImageValidator();

        $post = Input::validatePost();
        $files = Input::validateFiles();

        // Clear errors and data if they exist for next validation
        $this->session->resetFormInput();

        if ($eventValidator->validate($post) && $imageValidator->validate($files))
        {
            $name = ucfirst(strtolower($post['name']));
            $description = $post['description'];
            $startDate = $post['start-date'];
            $startTime = $post['start-time'];
            $endDate = $post['end-date'];
            $endTime = $post['end-time'];
            $musicianId = $post['musician'];
            $venueId = $post['venue'];

            $type = $files['image']['type'];
            $tmpName = $files['image']['tmp_name'];
            $path = $this->getImagePath($name, $type);

            if (move_uploaded_file($tmpName, $path))
            {
                $eventId = Event::insert([
                    'name' => $name,
                    'description' => $description,
                    'start_date' => $startDate,
                    'start_time' => $startTime,
                    'end_date' => $endDate,
                    'end_time' => $endTime,
                    'image' => $path
                ]);

                $event = Event::getOne('id', $eventId);
                Event::addMusicianToEvent($event->getId(), $musicianId);
                Event::addVenueToEvent($event->getId(), $venueId);

                $this->redirect('event/list');
            }
        }
        else
        {
            // Pass all discovered errors and valid data to session and redirect back to form
            $this->session->setFormData(array_merge($eventValidator->getData(), $imageValidator->getData()));
            $this->session->setFormErrors(array_merge($eventValidator->getErrors(), $imageValidator->getErrors()));
            $this->redirect('event/create');
        }
    }

    public function editAction(string $id)
    {
        if ($this->auth->getCurrentUser() === null || !$this->auth->getCurrentUser()->isAdmin() || !$this->auth->isLoggedIn())
        {
            $this->redirect('');
        }

        $this->view->render("Admin/Event/Edit", [
            'event' => Event::getOne('id', $id)
        ]);

        // Clear errors and data if they exist for next validation
        $this->session->resetFormInput();
    }

    public function editSubmitAction(string $id)
    {
        if ($this->auth->getCurrentUser() === null || !$this->auth->getCurrentUser()->isAdmin() || !$this->auth->isLoggedIn())
        {
            $this->redirect('');
        }
    }

    public function deleteSubmitAction($id)
    {
        if ($this->auth->getCurrentUser() === null || !$this->auth->getCurrentUser()->isAdmin() || !$this->auth->isLoggedIn())
        {
            $this->redirect('');
        }

        Event::delete('id', $id);
        $this->redirect('event/list');
    }

    private function getImagePath(string $name, string $type) : string
    {
        $ext = explode('/', $type);
        $ext = strtolower(end($ext));

        $name = $name . uniqid('_', false) . '.' . $ext;
        $path = "assets" . DS . "events" . DS;
        return $path . $name;
    }

    private function calculatePrice(string $price, string $discount = null)
    {
        $discountNum = $price * $discount;
        return $newPrice = $price - $discountNum . ".00";
    }
}
