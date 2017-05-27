<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Store Cover Image'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="storeCoverImages index large-9 medium-8 columns content">
    <h3><?= __('Store Cover Images') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('store_id') ?></th>
                <th><?= $this->Paginator->sort('name') ?></th>
                <th><?= $this->Paginator->sort('status') ?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($storeCoverImages as $storeCoverImage): ?>
            <tr>
                <td><?= $this->Number->format($storeCoverImage->id) ?></td>
                <td><?= $storeCoverImage->has('store') ? $this->Html->link($storeCoverImage->store->name, ['controller' => 'Stores', 'action' => 'view', $storeCoverImage->store->id]) : '' ?></td>
                <td><?= h($storeCoverImage->name) ?></td>
                <td><?= $this->Number->format($storeCoverImage->status) ?></td>
                <td><?= $this->Number->format($storeCoverImage->created) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $storeCoverImage->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $storeCoverImage->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $storeCoverImage->id], ['confirm' => __('Are you sure you want to delete # {0}?', $storeCoverImage->id)]) ?>
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
