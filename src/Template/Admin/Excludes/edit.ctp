<?php echo $this->element('side'); ?>
<div class="excludes form large-9 medium-8 columns content">
    <?= $this->Form->create($exclude) ?>
    <fieldset>
        <legend><?= __('Edit Exclude') ?></legend>
        <?php
            echo $this->Form->input('model_name');
            echo $this->Form->input('value');
            echo $this->Form->input('status');
            echo $this->Form->input('soft_deleted');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
