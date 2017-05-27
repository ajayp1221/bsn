<?php echo $this->element('side');?>
<div class="answers index large-9 medium-8 columns content">
    <h3><?= __('Answers') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('customers_id') ?></th>
                <th><?= $this->Paginator->sort('question_id') ?></th>
                <th><?= $this->Paginator->sort('option_id') ?></th>
                <th><?= $this->Paginator->sort('answer_type') ?></th>
                <th><?= $this->Paginator->sort('answer') ?></th>
                <th><?= $this->Paginator->sort('status') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($answers as $answer): ?>
            <tr>
                <td><?= $this->Number->format($answer->id) ?></td>
                <td><?= $this->Number->format($answer->customers_id) ?></td>
                <td><?= $answer->has('question') ? $this->Html->link($answer->question->id, ['controller' => 'Questions', 'action' => 'view', $answer->question->id]) : '' ?></td>
                <td><?= $answer->has('option') ? $this->Html->link($answer->option->option, ['controller' => 'Options', 'action' => 'view', $answer->option->id]) : '' ?></td>
                <td><?= h($answer->answer_type) ?></td>
                <td><?= h($answer->answer) ?></td>
                <td><?= $this->Number->format($answer->status) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $answer->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $answer->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $answer->id], ['confirm' => __('Are you sure you want to delete # {0}?', $answer->id)]) ?>
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
