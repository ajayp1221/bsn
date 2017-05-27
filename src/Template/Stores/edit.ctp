<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $store->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $store->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Stores'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Brands'), ['controller' => 'Brands', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Brand'), ['controller' => 'Brands', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Albums'), ['controller' => 'Albums', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Album'), ['controller' => 'Albums', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Campaigns'), ['controller' => 'Campaigns', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Campaign'), ['controller' => 'Campaigns', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Customer Visits'), ['controller' => 'CustomerVisits', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Customer Visit'), ['controller' => 'CustomerVisits', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Customers'), ['controller' => 'Customers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Customer'), ['controller' => 'Customers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Messages'), ['controller' => 'Messages', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Message'), ['controller' => 'Messages', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Productcats'), ['controller' => 'Productcats', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Productcat'), ['controller' => 'Productcats', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Purchases'), ['controller' => 'Purchases', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Purchase'), ['controller' => 'Purchases', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Pushmessages'), ['controller' => 'Pushmessages', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Pushmessage'), ['controller' => 'Pushmessages', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Questions'), ['controller' => 'Questions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Question'), ['controller' => 'Questions', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Recommend Screen'), ['controller' => 'RecommendScreen', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Recommend Screen'), ['controller' => 'RecommendScreen', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Share Screen'), ['controller' => 'ShareScreen', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Share Screen'), ['controller' => 'ShareScreen', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sharedcodes'), ['controller' => 'Sharedcodes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sharedcode'), ['controller' => 'Sharedcodes', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Templatemessages'), ['controller' => 'Templatemessages', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Templatemessage'), ['controller' => 'Templatemessages', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Welcomemsgs'), ['controller' => 'Welcomemsgs', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Welcomemsg'), ['controller' => 'Welcomemsgs', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="stores form large-9 medium-8 columns content">
    <?= $this->Form->create($store) ?>
    <fieldset>
        <legend><?= __('Edit Store') ?></legend>
        <?php
            echo $this->Form->input('zkpstoreid');
            echo $this->Form->input('brand_id', ['options' => $brands]);
            echo $this->Form->input('name');
            echo $this->Form->input('slug');
            echo $this->Form->input('address');
            echo $this->Form->input('status');
            echo $this->Form->input('soft_deleted');
            echo $this->Form->input('contact_numbers');
            echo $this->Form->input('emails');
            echo $this->Form->input('links');
            echo $this->Form->input('timings');
            echo $this->Form->input('closed');
            echo $this->Form->input('location_cordinates');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
