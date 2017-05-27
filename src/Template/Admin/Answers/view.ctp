<?php echo $this->element('side');?>
<div class="answers view large-9 medium-8 columns content">
    <h3><?= h($answer->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Question') ?></th>
            <td><?= $answer->has('question') ? $this->Html->link($answer->question->question, ['controller' => 'Questions', 'action' => 'view', $answer->question->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Option') ?></th>
            <td><?= $answer->has('option') ? $this->Html->link($answer->option->option, ['controller' => 'Options', 'action' => 'view', $answer->option->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Answer Type') ?></th>
            <td><?= h($answer->answer_type) ?></td>
        </tr>
        <tr>
            <th><?= __('Answer') ?></th>
            <td><?= h($answer->answer) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($answer->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Customers Id') ?></th>
            <td><?= $this->Number->format($answer->customers_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= $this->Number->format($answer->status) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= date("Y-M-d H:i:s",$answer->created) ?></td>
        </tr>
    </table>
</div>
