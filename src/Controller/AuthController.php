<?php
// filepath: c:\xampp81\htdocs\Lexicon2\src\Controller\AuthController.php
namespace App\Controller;

use League\OAuth2\Client\Provider\Google;
use Cake\Core\Configure;

class AuthController extends AppController
{
    public function login()
    {
        $provider = new Google([
            'clientId'     => Configure::consume('Google.clientId'),
            'clientSecret' => Configure::consume('Google.clientSecret'),
            'redirectUri'  => Configure::consume('Google.redirectUri'),
        ]);

        if (!$this->request->getQuery('code')) {
            // Store the intended redirect URL in the session
            $redirectUrl = $this->request->getQuery('redirect', '/');
            $this->request->getSession()->write('Auth.redirect', $redirectUrl);

            // Redirect to Google's OAuth 2.0 server
            $authUrl = $provider->getAuthorizationUrl();
            $this->redirect($authUrl);
        } else {
            // Handle the callback
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $this->request->getQuery('code'),
            ]);

            $googleUser = $provider->getResourceOwner($token);
            $googleEmail = $googleUser->getEmail();

            // Check if the user exists in the database
            $user = $this->fetchTable('Users')->find()
                ->where(['email' => $googleEmail])
                ->first();

            if ($user) {
                // Log the user in and store additional details in the session
                $this->request->getSession()->write('Auth.id', $user->id);
                $this->request->getSession()->write('Auth.email', $user->email);
                $this->request->getSession()->write('Auth.username', $user->username); // Assuming 'username' exists in the Users table
                $this->request->getSession()->write('Auth.role', $user->role);         // Assuming 'role' exists in the Users table
            } else {
                // Create a new user if not found
                $user = $this->Users->newEntity([
                    'email' => $googleEmail,
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'password' => null, // No password needed for Google accounts
                ]);
                if ($this->Users->save($user)) {
                    // Log the new user in and store additional details in the session
                    $this->request->getSession()->write('Auth.id', $user->id);
                    $this->request->getSession()->write('Auth.email', $user->email);
                    $this->request->getSession()->write('Auth.username', $user->username); // Assuming 'username' exists in the Users table
                    $this->request->getSession()->write('Auth.role', $user->role);         // Assuming 'role' exists in the Users table
                } else {
                    // Handle save failure
                    $this->Flash->error('Unable to create a new account. Please try again.');
                    $this->redirect(['controller' => 'Users', 'action' => 'register']);
                }
            }

            // Redirect to the stored URL or default to the homepage
            $redirectUrl = $this->request->getSession()->read('Auth.redirect', '/');
            $this->request->getSession()->delete('Auth.redirect'); // Clear the redirect URL
            $this->redirect($redirectUrl);
        }
    }

    public function logout()
    {
        $this->request->getSession()->destroy();
        $redirectUrl = $this->request->getQuery('redirect', '/');
        $this->redirect($redirectUrl); // Redirect to the specified URL or homepage
    }
}