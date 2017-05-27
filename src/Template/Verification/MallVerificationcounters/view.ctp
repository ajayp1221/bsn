<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Mall Verificationcounter'), ['action' => 'edit', $mallVerificationcounter->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Mall Verificationcounter'), ['action' => 'delete', $mallVerificationcounter->id], ['confirm' => __('Are you sure you want to delete # {0}?', $mallVerificationcounter->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Mall Verificationcounters'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Mall Verificationcounter'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="mallVerificationcounters view large-9 medium-8 columns content">
    <h3><?= h($mallVerificationcounter->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Store') ?></th>
            <td><?= $mallVerificationcounter->has('store') ? $this->Html->link($mallVerificationcounter->store->name, ['controller' => 'Stores', 'action' => 'view', $mallVerificationcounter->store->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Email') ?></th>
            <td><?= h($mallVerificationcounter->email) ?></td>
        </tr>
        <tr>
            <th><?= __('Password') ?></th>
            <td><?= h($mallVerificationcounter->password) ?></td>
        </tr>
        <tr>
            <th><?= __('Pwd') ?></th>
            <td><?= h($mallVerificationcounter->pwd) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($mallVerificationcounter->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= $this->Number->format($mallVerificationcounter->created) ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= $this->Number->format($mallVerificationcounter->status) ?></td>
        </tr>
    </table>
</div>
