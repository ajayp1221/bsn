<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Message'), ['action' => 'edit', $message->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Message'), ['action' => 'delete', $message->id], ['confirm' => __('Are you sure you want to delete # {0}?', $message->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Messages'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Message'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Customers'), ['controller' => 'Customers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Customer'), ['controller' => 'Customers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Campaigns'), ['controller' => 'Campaigns', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Campaign'), ['controller' => 'Campaigns', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="messages view large-9 medium-8 columns content">
    <h3><?= h($message->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Customer') ?></th>
            <td><?= $message->has('customer') ? $this->Html->link($message->customer->name, ['controller' => 'Customers', 'action' => 'view', $message->customer->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Store') ?></th>
            <td><?= $message->has('store') ? $this->Html->link($message->store->name, ['controller' => 'Stores', 'action' => 'view', $message->store->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Campaign') ?></th>
            <td><?= $message->has('campaign') ? $this->Html->link($message->campaign->id, ['controller' => 'Campaigns', 'action' => 'view', $message->campaign->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Promo Code') ?></th>
            <td><?= h($message->promo_code) ?></td>
        </tr>
        <tr>
            <th><?= __('Api Key') ?></th>
            <td><?= h($message->api_key) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($message->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Used') ?></th>
            <td><?= $this->Number->format($message->used) ?></td>
        </tr>
        <tr>
            <th><?= __('Used Date') ?></th>
            <td><?= $this->Number->format($message->used_date) ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= $this->Number->format($message->status) ?></td>
        </tr>
        <tr>
            <th><?= __('Received') ?></th>
            <td><?= $this->Number->format($message->received) ?></td>
        </tr>
        <tr>
            <th><?= __('Soft Deleted') ?></th>
            <td><?= $this->Number->format($message->soft_deleted) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= $this->Number->format($message->created) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Api Response') ?></h4>
        <?= $this->Text->autoParagraph(h($message->api_response)); ?>
    </div>
</div>
