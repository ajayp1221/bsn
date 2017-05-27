<?php echo $this->element('side');?>
<div class="customers form large-9 medium-8 columns content">
    <?= $this->Form->create($customer) ?>
    <fieldset>
        <legend><?= __('Add Customer') ?></legend>
        <?php
            echo $this->Form->input('store_id', ['options' => $stores]);
            echo $this->Form->input('contact_no');
            echo $this->Form->input('email');
            echo $this->Form->input('name');
            echo $this->Form->input('gender');
            echo $this->Form->input('dob');
            echo $this->Form->input('doa');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>