<?php
/**
 * Copyright 2010 - 2019, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2018, Cake Development Corporation (https://www.cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

use Cake\Core\Configure;

?>
<div class="users form" id="centeredform">
    <?= $this->Flash->render('auth') ?>
    <?= $this->Form->create() ?>
    <fieldset id='loginfieldset'>
        <p><?= __d('cake_d_c/users', 'Already registered? Login here:') ?></p>
        <?= $this->Form->control('email', ['placeholder' => 'Email address', 'label' => false, 'required' => true, 'id'=> 'loginemail']) ?>
        <?= $this->Form->control('password', ['placeholder' => 'Password', 'label' => false, 'required' => true, 'id'=> 'loginpassword']) ?>
        <?php
        if (Configure::read('Users.reCaptcha.login')) {
            echo $this->User->addReCaptcha();
        }
        if (Configure::read('Users.RememberMe.active')) {
            echo $this->Form->control(Configure::read('Users.Key.Data.rememberMe'), [
                'type' => 'checkbox',
                'label' => __d('cake_d_c/users', 'Remember me'),
                'checked' => Configure::read('Users.RememberMe.checked')
            ]);
        }
        ?>
        <?php
        $registrationActive = Configure::read('Users.Registration.active');
        
        echo '</br><p>Not yet registered?</p>';
        
        if ($registrationActive) {
            echo $this->Html->link(__d('cake_d_c/users', 'Register'), ['action' => 'register']) . ' to record words or sentences';
        }
        if (Configure::read('Users.Email.required')) {
            if ($registrationActive) {
                echo ' </br></br> ';
            }
            echo $this->Html->link(__d('cake_d_c/users', 'Reset Password'), ['action' => 'requestResetPassword']);
        }
        ?>
    </fieldset>
    <?= implode(' ', $this->User->socialLoginList()); ?>
    <?= $this->Form->button(__d('cake_d_c/users', 'Login'), ['class' => 'button blue2']); ?>
    <?= $this->Form->end() ?>
</div>
