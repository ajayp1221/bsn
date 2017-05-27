<?php echo $this->element('side');?>
<div class="answers form large-9 medium-8 columns content">
    <?= $this->Form->create($answer) ?>
    <fieldset>
        <legend><?= __('Edit Answer') ?></legend>
        <?php
            echo $this->Form->input('customers_id');
            echo $this->Form->input('question_id', ['options' => $questions]);
            echo $this->Form->input('option_id', ['options' => $options]);
            echo $this->Form->input('answer_type');
            echo $this->Form->input('answer');
            $status = array('1' => 'Active', '0' => 'Inactive');
            echo $this->Form->input('status', ['options' => $status]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
