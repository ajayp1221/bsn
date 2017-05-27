<?php echo $this->element('side');?>
<div class="options view large-9 medium-8 columns content">
    <h3><?= h($option->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Question') ?></th>
            <td><?= $option->has('question') ? $this->Html->link($option->question->question, ['controller' => 'Questions', 'action' => 'view', $option->question->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Option') ?></th>
            <td><?= h($option->option) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($option->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= $this->Number->format($option->status) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= date("Y-M-d H:i:s",$option->created) ?></td>
        </tr>
    </table>
</div>
