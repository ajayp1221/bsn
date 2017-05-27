<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Mall Shop'), ['action' => 'edit', $mallShop->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Mall Shop'), ['action' => 'delete', $mallShop->id], ['confirm' => __('Are you sure you want to delete # {0}?', $mallShop->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Mall Shop'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Mall Shop'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="mallShop view large-9 medium-8 columns content">
    <h3><?= h($mallShop->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($mallShop->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($mallShop->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= $this->Number->format($mallShop->status) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= $this->Number->format($mallShop->created) ?></td>
        </tr>
        <tr>
            <th><?= __('Modified') ?></th>
            <td><?= $this->Number->format($mallShop->modified) ?></td>
        </tr>
    </table>
</div>
