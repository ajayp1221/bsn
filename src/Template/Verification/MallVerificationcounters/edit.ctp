<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $mallVerificationcounter->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $mallVerificationcounter->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Mall Verificationcounters'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="mallVerificationcounters form large-9 medium-8 columns content">
    <?= $this->Form->create($mallVerificationcounter) ?>
    <fieldset>
        <legend><?= __('Edit Mall Verificationcounter') ?></legend>
        <?php
            echo $this->Form->input('store_id', ['options' => $stores]);
            echo $this->Form->input('email');
            echo $this->Form->input('password');
            echo $this->Form->input('pwd');
            echo $this->Form->input('status');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
