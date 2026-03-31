<?php
declare(strict_types=1);

namespace App\Controller\Moderators;

use App\Controller\AppController;
use Cake\Http\Response;
use Cake\Http\Exception\NotFoundException;

class UsersController extends AppController
{
    protected bool $loggedIn = false;
    protected string $currentRole = 'user';
    protected ?string $currentUsername = null;
    protected ?string $currentUserId = null;

    public function initialize(): void
    {
        parent::initialize();

        $this->loggedIn = (bool)$this->request->getSession()->read('Auth.username');
        $this->currentRole = (string)($this->request->getSession()->read('Auth.role') ?? 'user');
        $this->currentUsername = $this->request->getSession()->read('Auth.username');
        $this->currentUserId = (string)($this->request->getSession()->read('Auth.id') ?? '');

        $this->viewBuilder()->setLayout('moderators');
    }

    private function requireLogin(): ?Response
    {
        if ($this->loggedIn) {
            return null;
        }

        return $this->redirect('/login');
    }

    private function requireSuperuser(): ?Response
    {
        if ($resp = $this->requireLogin()) {
            return $resp;
        }

        if ($this->currentRole === 'superuser') {
            return null;
        }

        $this->Flash->error(__('You must be a superuser to do that.'));
        return $this->redirect(['prefix' => 'Moderators', 'controller' => 'Panel', 'action' => 'index']);
    }

    private function setModeratorLayoutVars(): void
    {
        $userid = $this->currentUserId;
        $userLevel = $this->currentRole;

        // The layout shows remaining credits only for superusers.
        $remainingcredits = null;
        if ($userLevel === 'superuser') {
            try {
                $remainingcredits = $this->getremainingcredits();
            } catch (\Throwable $e) {
                $remainingcredits = null;
            }
        }

        $this->set(compact('userid', 'userLevel', 'remainingcredits'));
    }

    public function index()
    {
        if ($resp = $this->requireSuperuser()) {
            return $resp;
        }

        $this->setModeratorLayoutVars();

        $usersTable = $this->fetchTable('CakeDC/Users.Users');

        $query = $usersTable->find()
            ->select(['id', 'username', 'email', 'role', 'is_superuser', 'active', 'created'])
            ->orderBy(['email' => 'ASC']);

        $users = $this->paginate($query, ['limit' => 50]);

        $allowedRoles = [
            'user' => 'User',
            'moderator' => 'Moderator',
            'superuser' => 'Superuser',
        ];

        $this->set(compact('users', 'allowedRoles'));
    }

    public function setRole($id = null)
    {
        if ($resp = $this->requireSuperuser()) {
            return $resp;
        }

        $this->request->allowMethod(['post']);
        $this->setModeratorLayoutVars();

        $targetId = (string)($id ?? '');
        if ($targetId === '') {
            throw new NotFoundException(__('User not found'));
        }

        if ($this->currentUserId !== '' && $targetId === $this->currentUserId) {
            $this->Flash->error(__('You cannot change your own role.'));
            return $this->redirect(['action' => 'index']);
        }

        $requestedRole = (string)($this->request->getData('role') ?? '');
        $requestedRole = strtolower(trim($requestedRole));

        $allowed = ['user', 'moderator', 'superuser'];
        if (!in_array($requestedRole, $allowed, true)) {
            $this->Flash->error(__('Invalid role.'));
            return $this->redirect(['action' => 'index']);
        }

        $usersTable = $this->fetchTable('CakeDC/Users.Users');
        $user = $usersTable->get($targetId, [
            'fields' => ['id', 'email', 'username', 'role', 'is_superuser'],
        ]);

        if ((bool)$user->is_superuser === true) {
            $this->Flash->error(__('You cannot change the role of an existing superuser.'));
            return $this->redirect(['action' => 'index']);
        }

        if ((string)$user->role === 'admin') {
            $this->Flash->error(__('You cannot change the role of an admin account.'));
            return $this->redirect(['action' => 'index']);
        }

        $isSuperuser = ($requestedRole === 'superuser');

        // CakeDC/Users entity protects role/is_superuser from mass assignment.
        // We set them explicitly with guard disabled.
        $user->set('role', $requestedRole, ['guard' => false]);
        $user->set('is_superuser', $isSuperuser, ['guard' => false]);

        if ($usersTable->save($user, ['validate' => false])) {
            $this->Flash->success(__('Updated role for {0}.', $user->email ?: $user->username ?: ('User #' . $user->id)));
        } else {
            $this->Flash->error(__('Could not update role. Please try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
