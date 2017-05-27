<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Store Cover Image'), ['action' => 'edit', $storeCoverImage->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Store Cover Image'), ['action' => 'delete', $storeCoverImage->id], ['confirm' => __('Are you sure you want to delete # {0}?', $storeCoverImage->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Store Cover Images'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Store Cover Image'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="storeCoverImages view large-9 medium-8 columns content">
    <h3><?= h($storeCoverImage->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Store') ?></th>
            <td><?= $storeCoverImage->has('store') ? $this->Html->link($storeCoverImage->store->name, ['controller' => 'Stores', 'action' => 'view', $storeCoverImage->store->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($storeCoverImage->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($storeCoverImage->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= $this->Number->format($storeCoverImage->status) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= $this->Number->format($storeCoverImage->created) ?></td>
        </tr>
    </table>
</div>
