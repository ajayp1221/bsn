<?php echo $this->element('side'); ?>
<div class="campaigns form large-9 medium-8 columns content">
    <?= $this->Form->create($campaign) ?>
    <fieldset>
        <legend><?= __('Add Campaign') ?></legend>
        <?php
            echo $this->Form->input('store_id', ['options' => $stores]);
            echo $this->Form->input('campaign_type');
            echo $this->Form->input('send_before_day');
            echo $this->Form->input('send_date');
            echo $this->Form->input('repeat');
            echo $this->Form->input('compaign_name');
            echo $this->Form->input('message');
            echo $this->Form->input('whos_send');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
