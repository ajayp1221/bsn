<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $campaign->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $campaign->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Campaigns'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Messages'), ['controller' => 'Messages', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Message'), ['controller' => 'Messages', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="campaigns form large-9 medium-8 columns content">
    <?= $this->Form->create($campaign) ?>
    <fieldset>
        <legend><?= __('Edit Campaign') ?></legend>
        <?php
            echo $this->Form->input('store_id', ['options' => $stores]);
            echo $this->Form->input('campaign_type');
            echo $this->Form->input('send_before_day');
            echo $this->Form->input('send_date');
            echo $this->Form->input('repeat');
            echo $this->Form->input('compaign_name');
            echo $this->Form->input('message');
            echo $this->Form->input('whos_send');
            echo $this->Form->input('status');
            echo $this->Form->input('soft_deleted');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
