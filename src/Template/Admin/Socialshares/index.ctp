<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Socialshare'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="socialshares index large-9 medium-8 columns content">
    <h3><?= __('Socialshares') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('image') ?></th>
                <th><?= $this->Paginator->sort('facebook') ?></th>
                <th><?= $this->Paginator->sort('twitter') ?></th>
                <th><?= $this->Paginator->sort('instagram') ?></th>
                <th><?= $this->Paginator->sort('status') ?></th>
                <th><?= $this->Paginator->sort('soft_deleted') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($socialshares as $socialshare): ?>
            <tr>
                <td><?= $this->Number->format($socialshare->id) ?></td>
                <td><?= h($socialshare->image) ?></td>
                <td><?= $this->Number->format($socialshare->facebook) ?></td>
                <td><?= $this->Number->format($socialshare->twitter) ?></td>
                <td><?= $this->Number->format($socialshare->instagram) ?></td>
                <td><?= $this->Number->format($socialshare->status) ?></td>
                <td><?= $this->Number->format($socialshare->soft_deleted) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $socialshare->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $socialshare->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $socialshare->id], ['confirm' => __('Are you sure you want to delete # {0}?', $socialshare->id)]) ?>
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
