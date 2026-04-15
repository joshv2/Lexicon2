<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\ResultSetInterface|\Cake\ORM\ResultSet $users
 * @var array<string,string> $allowedRoles
 * @var string|null $userid
 */

$currentUserId = (string)($userid ?? '');
?>

<h2>User Roles</h2>
<p>Promote regular users to moderator or superuser.</p>

<table>
    <tr>
        <th>Email</th>
        <th>Username</th>
        <th>Role</th>
        <th>Superuser</th>
        <th>Action</th>
    </tr>

    <?php foreach ($users as $user): ?>
        <?php
            $userId = (string)$user->id;
            $isSelf = ($userId !== '' && $userId === $currentUserId);
            $isExistingSuperuser = ((bool)($user->is_superuser ?? false) === true);
            $isAdmin = ((string)($user->role ?? '') === 'admin');
            $locked = ($isSelf || $isExistingSuperuser || $isAdmin);
        ?>
        <tr>
            <td><?= h((string)($user->email ?? '')) ?></td>
            <td><?= h((string)($user->username ?? '')) ?></td>
            <td><?= h((string)($user->role ?? '')) ?></td>
            <td><?= $isExistingSuperuser ? 'Yes' : 'No' ?></td>
            <td>
                <?php if ($locked): ?>
                    <?php if ($isSelf): ?>
                        <em>Cannot edit yourself</em>
                    <?php elseif ($isExistingSuperuser): ?>
                        <em>Protected superuser</em>
                    <?php elseif ($isAdmin): ?>
                        <em>Protected admin</em>
                    <?php endif; ?>
                <?php else: ?>
                    <?= $this->Form->create(null, [
                        'url' => ['action' => 'setRole', $userId],
                    ]) ?>
                    <?= $this->Form->control('role', [
                        'type' => 'select',
                        'options' => $allowedRoles,
                        'label' => false,
                        'default' => (string)($user->role ?? 'user'),
                    ]) ?>
                    <?= $this->Form->button(__('Update'), ['style' => 'margin-top:6px;']) ?>
                    <?= $this->Form->end() ?>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<div style="margin-top: 12px;">
    <?= $this->Paginator->numbers() ?>
</div>
