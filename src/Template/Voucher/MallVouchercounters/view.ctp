<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Mall Vouchercounter'), ['action' => 'edit', $mallVouchercounter->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Mall Vouchercounter'), ['action' => 'delete', $mallVouchercounter->id], ['confirm' => __('Are you sure you want to delete # {0}?', $mallVouchercounter->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Mall Vouchercounters'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Mall Vouchercounter'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="mallVouchercounters view large-9 medium-8 columns content">
    <h3><?= h($mallVouchercounter->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Store') ?></th>
            <td><?= $mallVouchercounter->has('store') ? $this->Html->link($mallVouchercounter->store->name, ['controller' => 'Stores', 'action' => 'view', $mallVouchercounter->store->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Email') ?></th>
            <td><?= h($mallVouchercounter->email) ?></td>
        </tr>
        <tr>
            <th><?= __('Password') ?></th>
            <td><?= h($mallVouchercounter->password) ?></td>
        </tr>
        <tr>
            <th><?= __('Pwd') ?></th>
            <td><?= h($mallVouchercounter->pwd) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($mallVouchercounter->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= $this->Number->format($mallVouchercounter->created) ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= $this->Number->format($mallVouchercounter->status) ?></td>
        </tr>
    </table>
</div>
