<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Socialshares'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="socialshares form large-9 medium-8 columns content">
    <?= $this->Form->create($socialshare) ?>
    <fieldset>
        <legend><?= __('Add Socialshare') ?></legend>
        <?php
            echo $this->Form->input('image');
            echo $this->Form->input('description');
            echo $this->Form->input('facebook');
            echo $this->Form->input('twitter');
            echo $this->Form->input('instagram');
            echo $this->Form->input('status');
            echo $this->Form->input('soft_deleted');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
