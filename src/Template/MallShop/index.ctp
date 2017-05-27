<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Mall Shop'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="mallShop index large-9 medium-8 columns content">
    <h3><?= __('Mall Shop') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('name') ?></th>
                <th><?= $this->Paginator->sort('status') ?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th><?= $this->Paginator->sort('modified') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mallShop as $mallShop): ?>
            <tr>
                <td><?= $this->Number->format($mallShop->id) ?></td>
                <td><?= h($mallShop->name) ?></td>
                <td><?= $this->Number->format($mallShop->status) ?></td>
                <td><?= $this->Number->format($mallShop->created) ?></td>
                <td><?= $this->Number->format($mallShop->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $mallShop->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $mallShop->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $mallShop->id], ['confirm' => __('Are you sure you want to delete # {0}?', $mallShop->id)]) ?>
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
