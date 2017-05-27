<?php echo $this->element('side'); ?>
<div class="stores index large-9 medium-8 columns content">
    <h3>Add iOS .pem file for push notifications</h3>
    <?= $this->Form->create(null, ['type'=>'file']) ?>
    <fieldset>
        <?php 
            echo $this->Form->input('pemfile', ['type' => 'file']);
            echo $this->Form->input('pemkey',['value'=>'12345']);
            echo $this->Form->input('Save', ['type' => 'submit']);
        ?>
    </fieldset>
    <?= $this->Form->end() ?>
</div>
