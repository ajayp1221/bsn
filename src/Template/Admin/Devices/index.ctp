<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Device'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Clients'), ['controller' => 'Clients', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Client'), ['controller' => 'Clients', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="devices index large-9 medium-8 columns content">
    <h3><?= __('Devices') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('client_id') ?></th>
                <th><?= $this->Paginator->sort('device_uuid') ?></th>
                <th><?= $this->Paginator->sort('status') ?></th>
                <th><?= $this->Paginator->sort('added_on') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($devices as $device): ?>
            <tr>
                <td><?= $this->Number->format($device->id) ?></td>
                <td><?= $device->has('client') ? $this->Html->link($device->client->name, ['controller' => 'Clients', 'action' => 'view', $device->client->id]) : '' ?></td>
                <td><?= h($device->device_uuid) ?></td>
                <td><?= $this->Number->format($device->status) ?></td>
                <td><?= h($device->added_on) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $device->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $device->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $device->id], ['confirm' => __('Are you sure you want to delete # {0}?', $device->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
