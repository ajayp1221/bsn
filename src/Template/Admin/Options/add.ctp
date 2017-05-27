<?php echo $this->element('side');?>
<div class="options form large-9 medium-8 columns content">
    <?= $this->Form->create($option) ?>
    <fieldset>
        <legend><?= __('Add Option') ?></legend>
        <?php
            echo $this->Form->input('question_id', ['options' => $questions]);
            echo $this->Form->input('option');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
