<?php echo $this->element('side');?>
<div class="options index large-9 medium-8 columns content">
    <h3><?= __('Options') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('question_id') ?></th>
                <th><?= $this->Paginator->sort('option') ?></th>
                <th><?= $this->Paginator->sort('status') ?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($options as $option): ?>
            <tr>
                <td><?= $this->Number->format($option->id) ?></td>
                <td><?= $option->has('question') ? $this->Html->link($option->question->id, ['controller' => 'Questions', 'action' => 'view', $option->question->id]) : '' ?></td>
                <td><?= h($option->option) ?></td>
                <td><?= $this->Number->format($option->status) ?></td>
                <td><?= date("Y-M-d H:i",$option->created) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $option->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $option->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $option->id], ['confirm' => __('Are you sure you want to delete # {0}?', $option->id)]) ?>
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
