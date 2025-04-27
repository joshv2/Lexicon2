<?php
namespace App\Controller;

use App\Controller\AppController;

class UsersController extends AppController
{
    public function logout()
    {
        // Destroy the session
        $this->request->getSession()->destroy();

        // Redirect to the home page or login page
        return $this->redirect('/');
    }
}