<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Socialshare'), ['action' => 'edit', $socialshare->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Socialshare'), ['action' => 'delete', $socialshare->id], ['confirm' => __('Are you sure you want to delete # {0}?', $socialshare->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Socialshares'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Socialshare'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="socialshares view large-9 medium-8 columns content">
    <h3><?= h($socialshare->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Image') ?></th>
            <td><?= h($socialshare->image) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($socialshare->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Facebook') ?></th>
            <td><?= $this->Number->format($socialshare->facebook) ?></td>
        </tr>
        <tr>
            <th><?= __('Twitter') ?></th>
            <td><?= $this->Number->format($socialshare->twitter) ?></td>
        </tr>
        <tr>
            <th><?= __('Instagram') ?></th>
            <td><?= $this->Number->format($socialshare->instagram) ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= $this->Number->format($socialshare->status) ?></td>
        </tr>
        <tr>
            <th><?= __('Soft Deleted') ?></th>
            <td><?= $this->Number->format($socialshare->soft_deleted) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= $this->Number->format($socialshare->created) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Description') ?></h4>
        <?= $this->Text->autoParagraph(h($socialshare->description)); ?>
    </div>
</div>
