<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Model\Venue;
use App\Validation\ImageValidator;
use App\Validation\VenueValidator;
use Core\Input;

class VenueController extends AbstractController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function listAction(): void
    {
        if ($this->auth->getCurrentUser() === null || !$this->auth->getCurrentUser()->isAdmin() || !$this->auth->isLoggedIn())
        {
            $this->redirect('');
        }

        $this->view->render("Admin/Venue/List", [
            'venues' => Venue::getAll()
        ]);
    }

    public function viewAction($id)
    {
        if ($id)
        {
            $venue = Venue::getOne('id', $id);

            $this->view->render("Pages/Venue", [
                'venue' => $venue
            ]);
        }
        else
        {
            $venues = Venue::getAll();

            $this->view->render("Pages/Venues", [
                'venues' => $venues
            ]);
        }
    }

    public function createAction(): void
    {
        if ($this->auth->getCurrentUser() === null || !$this->auth->getCurrentUser()->isAdmin() || !$this->auth->isLoggedIn())
        {
            $this->redirect('');
        }

        $this->view->render("Admin/Venue/Create");

        // Clear errors and data if they exist for next validation
        $this->session->resetFormInput();
    }

    public function createSubmitAction(): void
    {
        if ($this->auth->getCurrentUser() === null || !$this->auth->getCurrentUser()->isAdmin() || !$this->auth->isLoggedIn())
        {
            $this->redirect('');
        }

        $venueValidator = new VenueValidator();
        $imageValidator = new ImageValidator();

        $post = Input::validatePost();
        $files = Input::validateFiles();

//        array (size=1)
//          'image' =>
//            array (size=5)
//              'name' => string 'logo.png' (length=8)
//              'type' => string 'image/png' (length=9)
//              'tmp_name' => string '/tmp/phpS8FD3I' (length=14)
//              'error' => string '0' (length=1)
//              'size' => string '7594' (length=4)

        // Clear errors and data if they exist for next validation
        $this->session->resetFormInput();

        if ($venueValidator->validate($post) && $imageValidator->validate($files))
        {
            $name = ucfirst(strtolower($post['name']));
            $capacity = $post['capacity'];
            $address = ucfirst(strtolower($post['address']));
            $city = ucfirst(strtolower($post['city']));
            $postcode = $post['postcode'];
            $country = ucfirst(strtolower($post['country']));

            $type = $files['image']['type'];
            $tmpName = $files['image']['tmp_name'];
            $path = $this->getImagePath($name, $type);

            if (move_uploaded_file($tmpName, $path))
            {
                Venue::insert([
                    'name' => $name,
                    'capacity' => $capacity,
                    'image' => $path,
                    'address' => $address,
                    'city' => $city,
                    'postcode' => $postcode,
                    'country' => $country
                ]);

                $this->redirect('venue/list');
            }
        }
        else
        {
            // Pass all discovered errors and valid data to session and redirect back to form
            $this->session->setFormData(array_merge($venueValidator->getData(), $imageValidator->getData()));
            $this->session->setFormErrors(array_merge($venueValidator->getErrors(), $imageValidator->getErrors()));
            $this->redirect('venue/create');
        }
    }

    public function editAction(string $id): void
    {
        if ($this->auth->getCurrentUser() === null || !$this->auth->getCurrentUser()->isAdmin() || !$this->auth->isLoggedIn())
        {
            $this->redirect('');
        }

        $this->view->render("Admin/Venue/Edit", [
            'venue' => Venue::getOne('id', $id)
        ]);

        // Clear errors and data if they exist for next validation
        $this->session->resetFormInput();
    }

    public function editSubmitAction(string $id): void
    {
        if ($this->auth->getCurrentUser() === null || !$this->auth->getCurrentUser()->isAdmin() || !$this->auth->isLoggedIn())
        {
            $this->redirect('');
        }

        $venueValidator = new VenueValidator();
        $imageValidator = new ImageValidator();

        $post = Input::validatePost();
        $files = Input::validateFiles();

        // Clear errors and data if they exist for next validation
        $this->session->resetFormInput();

        if ($venueValidator->validate($post) && $imageValidator->validate($files))
        {
            $name = ucfirst(strtolower($post['name']));
            $capacity = $post['capacity'];
            $address = ucfirst(strtolower($post['address']));
            $city = ucfirst(strtolower($post['city']));
            $postcode = $post['postcode'];
            $country = ucfirst(strtolower($post['country']));

            $type = $files['image']['type'];
            $tmpName = $files['image']['tmp_name'];
            $path = $this->getImagePath($name, $type);

            if (move_uploaded_file($tmpName, $path))
            {
                $oldImage = Venue::getOne('id', $id)->getImage();
                if ($oldImage != null)
                {
                    unlink($oldImage);
                }

                Venue::update([
                    'name' => $name,
                    'capacity' => $capacity,
                    'image' => $path,
                    'address' => $address,
                    'city' => $city,
                    'postcode' => $postcode,
                    'country' => $country
                ], 'id', $id);

                $this->redirect('venue/list');
            }
        }
        else
        {
            // Pass all discovered errors and valid data to session and redirect back to form
            $this->session->setFormData(array_merge($venueValidator->getData(), $imageValidator->getData()));
            $this->session->setFormErrors(array_merge($venueValidator->getErrors(), $imageValidator->getErrors()));
            $this->redirect('venue/create');
        }
    }

    public function deleteSubmitAction($id): void
    {
        if ($this->auth->getCurrentUser() === null || !$this->auth->getCurrentUser()->isAdmin() || !$this->auth->isLoggedIn())
        {
            $this->redirect('');
        }

        Venue::delete('id', $id);
        $this->redirect('venue/list');
    }

    public function asyncVenueAction(): string
    {
        return json_encode(Venue::getAll());
    }

    private function getImagePath(string $name, string $type) : string
    {
        $ext = explode('/', $type);
        $ext = strtolower(end($ext));

        $name = $name . uniqid('_', false) . '.' . $ext;
        $path = "assets" . DS . "venues" . DS;
        return $path . $name;
    }
}
