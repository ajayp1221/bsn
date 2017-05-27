<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $productcat->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $productcat->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Productcats'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="productcats form large-9 medium-8 columns content">
    <?= $this->Form->create($productcat) ?>
    <fieldset>
        <legend><?= __('Edit Productcat') ?></legend>
        <?php
            echo $this->Form->input('name');
            echo $this->Form->input('store_id', ['options' => $stores]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
