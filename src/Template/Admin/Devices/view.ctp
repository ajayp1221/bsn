<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Device'), ['action' => 'edit', $device->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Device'), ['action' => 'delete', $device->id], ['confirm' => __('Are you sure you want to delete # {0}?', $device->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Devices'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Device'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Clients'), ['controller' => 'Clients', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Client'), ['controller' => 'Clients', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="devices view large-9 medium-8 columns content">
    <h3><?= h($device->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Client') ?></th>
            <td><?= $device->has('client') ? $this->Html->link($device->client->name, ['controller' => 'Clients', 'action' => 'view', $device->client->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Device Uuid') ?></th>
            <td><?= h($device->device_uuid) ?></td>
        </tr>
        <tr>
            <th><?= __('Added On') ?></th>
            <td><?= h($device->added_on) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($device->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= $this->Number->format($device->status) ?></td>
        </tr>
    </table>
</div>
