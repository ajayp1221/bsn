<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Store Image'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="storeImages index large-9 medium-8 columns content">
    <h3><?= __('Store Images') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('store_id') ?></th>
                <th><?= $this->Paginator->sort('name') ?></th>
                <th><?= $this->Paginator->sort('price') ?></th>
                <th><?= $this->Paginator->sort('medium_img_w') ?></th>
                <th><?= $this->Paginator->sort('medium_img_h') ?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($storeImages as $storeImage): ?>
            <tr>
                <td><?= $this->Number->format($storeImage->id) ?></td>
                <td><?= $storeImage->has('store') ? $this->Html->link($storeImage->store->name, ['controller' => 'Stores', 'action' => 'view', $storeImage->store->id]) : '' ?></td>
                <td><?= h($storeImage->name) ?></td>
                <td><?= h($storeImage->price) ?></td>
                <td><?= h($storeImage->medium_img_w) ?></td>
                <td><?= h($storeImage->medium_img_h) ?></td>
                <td><?= $this->Number->format($storeImage->created) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $storeImage->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $storeImage->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $storeImage->id], ['confirm' => __('Are you sure you want to delete # {0}?', $storeImage->id)]) ?>
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
