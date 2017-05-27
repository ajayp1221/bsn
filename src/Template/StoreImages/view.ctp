<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Store Image'), ['action' => 'edit', $storeImage->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Store Image'), ['action' => 'delete', $storeImage->id], ['confirm' => __('Are you sure you want to delete # {0}?', $storeImage->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Store Images'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Store Image'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="storeImages view large-9 medium-8 columns content">
    <h3><?= h($storeImage->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Store') ?></th>
            <td><?= $storeImage->has('store') ? $this->Html->link($storeImage->store->name, ['controller' => 'Stores', 'action' => 'view', $storeImage->store->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($storeImage->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Price') ?></th>
            <td><?= h($storeImage->price) ?></td>
        </tr>
        <tr>
            <th><?= __('Medium Img W') ?></th>
            <td><?= h($storeImage->medium_img_w) ?></td>
        </tr>
        <tr>
            <th><?= __('Medium Img H') ?></th>
            <td><?= h($storeImage->medium_img_h) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($storeImage->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= $this->Number->format($storeImage->created) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Desc') ?></h4>
        <?= $this->Text->autoParagraph(h($storeImage->desc)); ?>
    </div>
</div>
