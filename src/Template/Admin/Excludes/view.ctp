<?php echo $this->element('side'); ?>
<div class="excludes view large-9 medium-8 columns content">
    <h3><?= h($exclude->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Model Name') ?></th>
            <td><?= h($exclude->model_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Value') ?></th>
            <td><?= h($exclude->value) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($exclude->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= $this->Number->format($exclude->status) ?></td>
        </tr>
        <tr>
            <th><?= __('Soft Deleted') ?></th>
            <td><?= $this->Number->format($exclude->soft_deleted) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= $this->Number->format($exclude->created) ?></td>
        </tr>
        <tr>
            <th><?= __('Modified') ?></th>
            <td><?= $this->Number->format($exclude->modified) ?></td>
        </tr>
    </table>
</div>
