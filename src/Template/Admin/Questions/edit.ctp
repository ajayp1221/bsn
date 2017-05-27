<?php echo $this->element('side');?>
<div class="questions form large-9 medium-8 columns content">
    <?= $this->Form->create($question) ?>
    <fieldset>
        <legend><?= __('Edit Question') ?></legend>
        <?php
            echo $this->Form->input('store_id', ['options' => $stores]);
            echo $this->Form->input('question');
            echo $this->Form->input('question_type');
            $status = array('1' => 'Active', '0' => 'Inactive');
            echo $this->Form->input('status', ['options' => $status]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
