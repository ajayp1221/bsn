<?php echo $this->element('side');?>
<div class="campaigns index large-9 medium-8 columns content">
    <h3><?= __('Campaigns') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('store_id') ?></th>
                <th><?= $this->Paginator->sort('campaign_type') ?></th>
                <th><?= $this->Paginator->sort('send_before_day') ?></th>
                <th><?= $this->Paginator->sort('send_date') ?></th>
                <th><?= $this->Paginator->sort('repeat') ?></th>
                <th><?= $this->Paginator->sort('whos_send') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($campaigns as $campaign): ?>
            <tr>
                <td><?= $this->Number->format($campaign->id) ?></td>
                <td><?= $campaign->has('store') ? $this->Html->link($campaign->store->name, ['controller' => 'Stores', 'action' => 'view', $campaign->store->id]) : '' ?></td>
                <td><?= h($campaign->campaign_type) ?></td>
                <td><?= $this->Number->format($campaign->send_before_day) ?></td>
                <td><?= $this->Number->format($campaign->send_date) ?></td>
                <td><?= $this->Number->format($campaign->repeat) ?></td>
                <td><?= $this->Number->format($campaign->whos_send) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $campaign->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $campaign->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $campaign->id], ['confirm' => __('Are you sure you want to delete # {0}?', $campaign->id)]) ?>
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
