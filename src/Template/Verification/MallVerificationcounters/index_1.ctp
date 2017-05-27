<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Mall Verificationcounter'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="mallVerificationcounters index large-9 medium-8 columns content">
    <h3><?= __('Mall Verificationcounters') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('store_id') ?></th>
                <th><?= $this->Paginator->sort('email') ?></th>
                <th><?= $this->Paginator->sort('password') ?></th>
                <th><?= $this->Paginator->sort('pwd') ?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th><?= $this->Paginator->sort('status') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mallVerificationcounters as $mallVerificationcounter): ?>
            <tr>
                <td><?= $this->Number->format($mallVerificationcounter->id) ?></td>
                <td><?= $mallVerificationcounter->has('store') ? $this->Html->link($mallVerificationcounter->store->name, ['controller' => 'Stores', 'action' => 'view', $mallVerificationcounter->store->id]) : '' ?></td>
                <td><?= h($mallVerificationcounter->email) ?></td>
                <td><?= h($mallVerificationcounter->password) ?></td>
                <td><?= h($mallVerificationcounter->pwd) ?></td>
                <td><?= $this->Number->format($mallVerificationcounter->created) ?></td>
                <td><?= $this->Number->format($mallVerificationcounter->status) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $mallVerificationcounter->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $mallVerificationcounter->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $mallVerificationcounter->id], ['confirm' => __('Are you sure you want to delete # {0}?', $mallVerificationcounter->id)]) ?>
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
