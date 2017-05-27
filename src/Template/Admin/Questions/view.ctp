<?php echo $this->element('side');?>
<div class="questions view large-9 medium-8 columns content">
    <h3><?= h($question->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Store') ?></th>
            <td><?= $question->has('store') ? $this->Html->link($question->store->name, ['controller' => 'Stores', 'action' => 'view', $question->store->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($question->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Question Type') ?></th>
            <td><?= $this->Number->format($question->question_type) ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= $this->Number->format($question->status) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= date("Y-M-d H:i",$question->created) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Question') ?></h4>
        <?= $this->Text->autoParagraph(h($question->question)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Options') ?></h4>
        <?php if (!empty($question->options)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Question Id') ?></th>
                <th><?= __('Option') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Soft Deleted') ?></th>
                <th><?= __('Created') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($question->options as $options): ?>
            <tr>
                <td><?= h($options->id) ?></td>
                <td><?= h($options->question_id) ?></td>
                <td><?= h($options->option) ?></td>
                <td><?= h($options->status) ?></td>
                <td><?= h($options->soft_deleted) ?></td>
                <td><?= h($options->created) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Options', 'action' => 'view', $options->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'Options', 'action' => 'edit', $options->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Options', 'action' => 'delete', $options->id], ['confirm' => __('Are you sure you want to delete # {0}?', $options->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
