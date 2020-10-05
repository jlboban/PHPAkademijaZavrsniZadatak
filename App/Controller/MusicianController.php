<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Model\Musician;
use App\Validation\ImageValidator;
use App\Validation\MusicianValidator;
use Core\Input;

class MusicianController extends AbstractController
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

        $this->view->render("Admin/Musician/List", [
            'musicians' => Musician::getAll()
        ]);
    }

    public function viewAction($id)
    {
        if ($id)
        {
            $musician = Musician::getOne('id', $id);

            $this->view->render("Pages/Musician", [
                'musician' => $musician
            ]);
        }
        else
        {
            $musicians = Musician::getAll();

            $this->view->render("Pages/Musicians", [
                'musicians' => $musicians
            ]);
        }
    }

    public function createAction(): void
    {
        if ($this->auth->getCurrentUser() === null || !$this->auth->getCurrentUser()->isAdmin() || !$this->auth->isLoggedIn())
        {
            $this->redirect('');
        }

        $this->view->render("Admin/Musician/Create");

        // Clear errors and data if they exist for next validation
        $this->session->resetFormInput();
    }

    public function createSubmitAction(): void
    {
        if ($this->auth->getCurrentUser() === null || !$this->auth->getCurrentUser()->isAdmin() || !$this->auth->isLoggedIn())
        {
            $this->redirect('');
        }

        $musicianValidator = new MusicianValidator();
        $imageValidator = new ImageValidator();

        $post = Input::validatePost();
        $files = Input::validateFiles();

        // Clear errors and data if they exist for next validation
        $this->session->resetFormInput();

        if ($musicianValidator->validate($post) && $imageValidator->validate($files))
        {
            $name = $post['name'];
            $genre = $post['genre'];
            $description = $post['description'];
            $spotify = $post['spotify'];

            $type = $files['image']['type'];
            $tmpName = $files['image']['tmp_name'];
            $path = $this->getImagePath($name, $type);

            if (move_uploaded_file($tmpName, $path))
            {
                Musician::insert([
                    'name' => $name,
                    'genre' => $genre,
                    'description' => $description,
                    'image' => $path,
                    'spotify' => $spotify
                ]);

                $this->redirect('musician/list');
            }
        }
        else
        {
            // Pass all discovered errors and valid data to session and redirect back to form
            $this->session->setFormData(array_merge($musicianValidator->getData(), $imageValidator->getData()));
            $this->session->setFormErrors(array_merge($musicianValidator->getErrors(), $imageValidator->getErrors()));
            $this->redirect('musician/create');
        }
    }

    public function editAction(string $id): void
    {
        if ($this->auth->getCurrentUser() === null || !$this->auth->getCurrentUser()->isAdmin() || !$this->auth->isLoggedIn())
        {
            $this->redirect('');
        }

        $this->view->render("Admin/Musician/Edit", [
            'musician' => Musician::getOne('id', $id)
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

        $musicianValidator = new MusicianValidator();
        $imageValidator = new ImageValidator();

        $post = Input::validatePost();
        $files = Input::validateFiles();

        // Clear errors and data if they exist for next validation
        $this->session->resetFormInput();

        if ($musicianValidator->validate($post) && $imageValidator->validate($files))
        {
            $name = $post['name'];
            $genre = $post['genre'];
            $description = $post['description'];
            $spotify = $post['spotify'];

            $type = $files['image']['type'];
            $tmpName = $files['image']['tmp_name'];
            $path = $this->getImagePath($name, $type);

            if (move_uploaded_file($tmpName, $path))
            {
                $oldImage = Musician::getOne('id', $id)->getImage();

                if (file_exists($oldImage) && $oldImage != null)
                {
                    unlink($oldImage);
                }

                Musician::update([
                    'name' => $name,
                    'genre' => $genre,
                    'description' => $description,
                    'image' => $path,
                    'spotify' => $spotify
                ], 'id', $id);

                $this->redirect('musician/list');
            }
        }
        else
        {
            // Pass all discovered errors and valid data to session and redirect back to form
            $this->session->setFormData(array_merge($musicianValidator->getData(), $imageValidator->getData()));
            $this->session->setFormErrors(array_merge($musicianValidator->getErrors(), $imageValidator->getErrors()));
            $this->redirect('musician/create');
        }
    }

    public function deleteSubmitAction($id): void
    {
        if ($this->auth->getCurrentUser() === null || !$this->auth->getCurrentUser()->isAdmin() || !$this->auth->isLoggedIn())
        {
            $this->redirect('');
        }

        Musician::delete('id', $id);
        $this->redirect('musician/list');
    }

    private function getImagePath(string $name, string $type) : string
    {
        $ext = explode('/', $type);
        $ext = strtolower(end($ext));

        $name = $name . uniqid('_', false) . '.' . $ext;
        $path = "assets" . DS . "musicians" . DS;
        return $path . $name;
    }
}
