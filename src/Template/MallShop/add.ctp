<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Mall Shop'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="mallShop form large-9 medium-8 columns content">
    <?= $this->Form->create($mallShop) ?>
    <fieldset>
        <legend><?= __('Add Mall Shop') ?></legend>
        <?php
            echo $this->Form->input('name');
            echo $this->Form->input('status');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
