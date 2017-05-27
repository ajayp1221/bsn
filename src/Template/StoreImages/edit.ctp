<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $storeImage->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $storeImage->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Store Images'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="storeImages form large-9 medium-8 columns content">
    <?= $this->Form->create($storeImage) ?>
    <fieldset>
        <legend><?= __('Edit Store Image') ?></legend>
        <?php
            echo $this->Form->input('store_id', ['options' => $stores]);
            echo $this->Form->input('name');
            echo $this->Form->input('desc');
            echo $this->Form->input('price');
            echo $this->Form->input('medium_img_w');
            echo $this->Form->input('medium_img_h');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
