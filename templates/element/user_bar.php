<?php if ($this->request->getSession()->check('Auth.User')): ?>
    <nav id="crumbs" class="group">
        <ul class="right">
            <li>
                <?php if ($this->request->getSession()->read('Auth.User.role') != 'contributor') {
                    echo $this->Html->link('Admin Panel', '/moderators', ['class' => 'user button grey']);
                } ?>
            </li>
            <li>
                <?= $this->Html->link(
                    'Log out ' . $this->request->getSession()->read('Auth.User.email'),
                    '/logout',
                    ['class' => 'user button grey']
                ); ?>
            </li>
        </ul>
    </nav>
<?php endif; ?>

