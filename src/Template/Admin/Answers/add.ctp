<?php echo $this->element('side');?>
<div class="answers form large-9 medium-8 columns content">
    <?= $this->Form->create($answer) ?>
    <fieldset>
        <legend><?= __('Add Answer') ?></legend>
        <?php
            echo $this->Form->input('customers_id');
            echo $this->Form->input('question_id', ['options' => $questions]);
            echo $this->Form->input('option_id', ['options' => $options]);
            echo $this->Form->input('answer_type');
            echo $this->Form->input('answer');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
