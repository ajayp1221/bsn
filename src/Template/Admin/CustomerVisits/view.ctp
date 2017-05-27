<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Customer Visit'), ['action' => 'edit', $customerVisit->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Customer Visit'), ['action' => 'delete', $customerVisit->id], ['confirm' => __('Are you sure you want to delete # {0}?', $customerVisit->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Customer Visits'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Customer Visit'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Customers'), ['controller' => 'Customers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Customer'), ['controller' => 'Customers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="customerVisits view large-9 medium-8 columns content">
    <h3><?= h($customerVisit->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Customer') ?></th>
            <td><?= $customerVisit->has('customer') ? $this->Html->link($customerVisit->customer->name, ['controller' => 'Customers', 'action' => 'view', $customerVisit->customer->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Came At') ?></th>
            <td><?= h($customerVisit->came_at) ?></td>
        </tr>
        <tr>
            <th><?= __('Store') ?></th>
            <td><?= $customerVisit->has('store') ? $this->Html->link($customerVisit->store->name, ['controller' => 'Stores', 'action' => 'view', $customerVisit->store->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($customerVisit->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Visits Till Now') ?></th>
            <td><?= $this->Number->format($customerVisit->visits_till_now) ?></td>
        </tr>
    </table>
</div>
