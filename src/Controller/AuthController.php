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

            $user = $provider->getResourceOwner($token);
            // Save user information in the session or database
            $this->request->getSession()->write('Auth.User', $user->toArray());
            $this->redirect(['controller' => 'Pages', 'action' => 'display', 'home']);
        }
    }

    public function logout()
    {
        $this->request->getSession()->destroy();
        $this->redirect(['controller' => 'Pages', 'action' => 'display', 'home']);
    }
}