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
                // Log the user in
                $this->request->getSession()->write('Auth.User', $user);
                $this->redirect(['controller' => 'Pages', 'action' => 'index']);
            } else {
                // Create a new user if not found
                $user = $this->Users->newEntity([
                    'email' => $googleEmail,
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'password' => null, // No password needed for Google accounts
                ]);
                if ($this->Users->save($user)) {
                    // Log the new user in
                    $this->request->getSession()->write('Auth.User', $user);
                    $this->redirect(['controller' => 'Pages', 'action' => 'index']);
                } else {
                    // Handle save failure
                    $this->Flash->error('Unable to create a new account. Please try again.');
                    $this->redirect(['controller' => 'Users', 'action' => 'register']);
                }
            }
        }
    }

    public function logout()
    {
        $this->request->getSession()->destroy();
        $this->redirect(['controller' => 'Pages', 'action' => 'index']); // Redirect to index
    }
}