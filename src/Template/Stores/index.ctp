<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Store'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Brands'), ['controller' => 'Brands', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Brand'), ['controller' => 'Brands', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Albums'), ['controller' => 'Albums', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Album'), ['controller' => 'Albums', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Campaigns'), ['controller' => 'Campaigns', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Campaign'), ['controller' => 'Campaigns', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Customer Visits'), ['controller' => 'CustomerVisits', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Customer Visit'), ['controller' => 'CustomerVisits', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Customers'), ['controller' => 'Customers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Customer'), ['controller' => 'Customers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Messages'), ['controller' => 'Messages', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Message'), ['controller' => 'Messages', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Productcats'), ['controller' => 'Productcats', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Productcat'), ['controller' => 'Productcats', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Purchases'), ['controller' => 'Purchases', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Purchase'), ['controller' => 'Purchases', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Pushmessages'), ['controller' => 'Pushmessages', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Pushmessage'), ['controller' => 'Pushmessages', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Questions'), ['controller' => 'Questions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Question'), ['controller' => 'Questions', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Recommend Screen'), ['controller' => 'RecommendScreen', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Recommend Screen'), ['controller' => 'RecommendScreen', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Share Screen'), ['controller' => 'ShareScreen', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Share Screen'), ['controller' => 'ShareScreen', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sharedcodes'), ['controller' => 'Sharedcodes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sharedcode'), ['controller' => 'Sharedcodes', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Templatemessages'), ['controller' => 'Templatemessages', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Templatemessage'), ['controller' => 'Templatemessages', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Welcomemsgs'), ['controller' => 'Welcomemsgs', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Welcomemsg'), ['controller' => 'Welcomemsgs', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="stores index large-9 medium-8 columns content">
    <h3><?= __('Stores') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('zkpstoreid') ?></th>
                <th><?= $this->Paginator->sort('brand_id') ?></th>
                <th><?= $this->Paginator->sort('name') ?></th>
                <th><?= $this->Paginator->sort('slug') ?></th>
                <th><?= $this->Paginator->sort('status') ?></th>
                <th><?= $this->Paginator->sort('soft_deleted') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($stores as $store): ?>
            <tr>
                <td><?= $this->Number->format($store->id) ?></td>
                <td><?= $this->Number->format($store->zkpstoreid) ?></td>
                <td><?= $store->has('brand') ? $this->Html->link($store->brand->id, ['controller' => 'Brands', 'action' => 'view', $store->brand->id]) : '' ?></td>
                <td><?= h($store->name) ?></td>
                <td><?= h($store->slug) ?></td>
                <td><?= $this->Number->format($store->status) ?></td>
                <td><?= $this->Number->format($store->soft_deleted) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $store->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $store->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $store->id], ['confirm' => __('Are you sure you want to delete # {0}?', $store->id)]) ?>
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
