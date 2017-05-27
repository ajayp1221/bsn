<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Client'), ['action' => 'add']) ?></li>
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
<div class="clients index large-9 medium-8 columns content">
    <h3><?= __('Clients') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('senderid') ?></th>
                <th><?= $this->Paginator->sort('plivo_authid') ?></th>
                <th><?= $this->Paginator->sort('plivo_auth_token') ?></th>
                <th><?= $this->Paginator->sort('slug') ?></th>
                <th><?= $this->Paginator->sort('name') ?></th>
                <th><?= $this->Paginator->sort('email') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clients as $client): ?>
            <tr>
                <td><?= $this->Number->format($client->id) ?></td>
                <td><?= h($client->senderid) ?></td>
                <td><?= h($client->plivo_authid) ?></td>
                <td><?= h($client->plivo_auth_token) ?></td>
                <td><?= h($client->slug) ?></td>
                <td><?= h($client->name) ?></td>
                <td><?= h($client->email) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $client->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $client->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $client->id], ['confirm' => __('Are you sure you want to delete # {0}?', $client->id)]) ?>
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
