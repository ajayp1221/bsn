<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Brand'), ['action' => 'edit', $brand->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Brand'), ['action' => 'delete', $brand->id], ['confirm' => __('Are you sure you want to delete # {0}?', $brand->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Brands'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Brand'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Clients'), ['controller' => 'Clients', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Client'), ['controller' => 'Clients', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="brands view large-9 medium-8 columns content">
    <h3><?= h($brand->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Client') ?></th>
            <td><?= $brand->has('client') ? $this->Html->link($brand->client->name, ['controller' => 'Clients', 'action' => 'view', $brand->client->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Logo') ?></th>
            <td><?= h($brand->logo) ?></td>
        </tr>
        <tr>
            <th><?= __('Brand Name') ?></th>
            <td><?= h($brand->brand_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($brand->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= $this->Number->format($brand->status) ?></td>
        </tr>
        <tr>
            <th><?= __('Soft Deleted') ?></th>
            <td><?= $this->Number->format($brand->soft_deleted) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= $this->Number->format($brand->created) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Stores') ?></h4>
        <?php if (!empty($brand->stores)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Zkpstoreid') ?></th>
                <th><?= __('Brand Id') ?></th>
                <th><?= __('Name') ?></th>
                <th><?= __('Slug') ?></th>
                <th><?= __('Address') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Soft Deleted') ?></th>
                <th><?= __('Created') ?></th>
                <th><?= __('Contact Numbers') ?></th>
                <th><?= __('Emails') ?></th>
                <th><?= __('Links') ?></th>
                <th><?= __('Timings') ?></th>
                <th><?= __('Closed') ?></th>
                <th><?= __('Location Cordinates') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($brand->stores as $stores): ?>
            <tr>
                <td><?= h($stores->id) ?></td>
                <td><?= h($stores->zkpstoreid) ?></td>
                <td><?= h($stores->brand_id) ?></td>
                <td><?= h($stores->name) ?></td>
                <td><?= h($stores->slug) ?></td>
                <td><?= h($stores->address) ?></td>
                <td><?= h($stores->status) ?></td>
                <td><?= h($stores->soft_deleted) ?></td>
                <td><?= h($stores->created) ?></td>
                <td><?= h($stores->contact_numbers) ?></td>
                <td><?= h($stores->emails) ?></td>
                <td><?= h($stores->links) ?></td>
                <td><?= h($stores->timings) ?></td>
                <td><?= h($stores->closed) ?></td>
                <td><?= h($stores->location_cordinates) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Stores', 'action' => 'view', $stores->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Stores', 'action' => 'edit', $stores->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Stores', 'action' => 'delete', $stores->id], ['confirm' => __('Are you sure you want to delete # {0}?', $stores->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
