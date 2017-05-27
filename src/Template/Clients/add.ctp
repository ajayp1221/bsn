<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Clients'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Brands'), ['controller' => 'Brands', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Brand'), ['controller' => 'Brands', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Devices'), ['controller' => 'Devices', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Device'), ['controller' => 'Devices', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sharedcodes'), ['controller' => 'Sharedcodes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sharedcode'), ['controller' => 'Sharedcodes', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Social Connections'), ['controller' => 'SocialConnections', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Social Connection'), ['controller' => 'SocialConnections', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Socialshares'), ['controller' => 'Socialshares', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Socialshare'), ['controller' => 'Socialshares', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Smsplans'), ['controller' => 'Smsplans', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Smsplan'), ['controller' => 'Smsplans', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="clients form large-9 medium-8 columns content">
    <?= $this->Form->create($client) ?>
    <fieldset>
        <legend><?= __('Add Client') ?></legend>
        <?php
            echo $this->Form->input('senderid');
            echo $this->Form->input('plivo_authid');
            echo $this->Form->input('plivo_auth_token');
            echo $this->Form->input('slug');
            echo $this->Form->input('name');
            echo $this->Form->input('email');
            echo $this->Form->input('password');
            echo $this->Form->input('pwd');
            echo $this->Form->input('contact_no');
            echo $this->Form->input('image');
            echo $this->Form->input('address');
            echo $this->Form->input('sms_quantity');
            echo $this->Form->input('sms_sent');
            echo $this->Form->input('email_left');
            echo $this->Form->input('email_sent');
            echo $this->Form->input('pass_hash');
            echo $this->Form->input('status');
            echo $this->Form->input('soft_deleted');
            echo $this->Form->input('max_device_limit');
            echo $this->Form->input('smsplans._ids', ['options' => $smsplans]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
