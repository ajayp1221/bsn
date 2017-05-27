<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Store'), ['action' => 'edit', $store->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Store'), ['action' => 'delete', $store->id], ['confirm' => __('Are you sure you want to delete # {0}?', $store->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Stores'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Store'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Brands'), ['controller' => 'Brands', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Brand'), ['controller' => 'Brands', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Albums'), ['controller' => 'Albums', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Album'), ['controller' => 'Albums', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Campaigns'), ['controller' => 'Campaigns', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Campaign'), ['controller' => 'Campaigns', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Customer Visits'), ['controller' => 'CustomerVisits', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Customer Visit'), ['controller' => 'CustomerVisits', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Customers'), ['controller' => 'Customers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Customer'), ['controller' => 'Customers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Messages'), ['controller' => 'Messages', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Message'), ['controller' => 'Messages', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Productcats'), ['controller' => 'Productcats', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Productcat'), ['controller' => 'Productcats', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Purchases'), ['controller' => 'Purchases', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Purchase'), ['controller' => 'Purchases', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Pushmessages'), ['controller' => 'Pushmessages', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Pushmessage'), ['controller' => 'Pushmessages', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Questions'), ['controller' => 'Questions', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Question'), ['controller' => 'Questions', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Recommend Screen'), ['controller' => 'RecommendScreen', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Recommend Screen'), ['controller' => 'RecommendScreen', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Share Screen'), ['controller' => 'ShareScreen', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Share Screen'), ['controller' => 'ShareScreen', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Sharedcodes'), ['controller' => 'Sharedcodes', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sharedcode'), ['controller' => 'Sharedcodes', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Templatemessages'), ['controller' => 'Templatemessages', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Templatemessage'), ['controller' => 'Templatemessages', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Welcomemsgs'), ['controller' => 'Welcomemsgs', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Welcomemsg'), ['controller' => 'Welcomemsgs', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="stores view large-9 medium-8 columns content">
    <h3><?= h($store->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Brand') ?></th>
            <td><?= $store->has('brand') ? $this->Html->link($store->brand->id, ['controller' => 'Brands', 'action' => 'view', $store->brand->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($store->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Slug') ?></th>
            <td><?= h($store->slug) ?></td>
        </tr>
        <tr>
            <th><?= __('Timings') ?></th>
            <td><?= h($store->timings) ?></td>
        </tr>
        <tr>
            <th><?= __('Closed') ?></th>
            <td><?= h($store->closed) ?></td>
        </tr>
        <tr>
            <th><?= __('Location Cordinates') ?></th>
            <td><?= h($store->location_cordinates) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($store->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Zkpstoreid') ?></th>
            <td><?= $this->Number->format($store->zkpstoreid) ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= $this->Number->format($store->status) ?></td>
        </tr>
        <tr>
            <th><?= __('Soft Deleted') ?></th>
            <td><?= $this->Number->format($store->soft_deleted) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= $this->Number->format($store->created) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Address') ?></h4>
        <?= $this->Text->autoParagraph(h($store->address)); ?>
    </div>
    <div class="row">
        <h4><?= __('Contact Numbers') ?></h4>
        <?= $this->Text->autoParagraph(h($store->contact_numbers)); ?>
    </div>
    <div class="row">
        <h4><?= __('Emails') ?></h4>
        <?= $this->Text->autoParagraph(h($store->emails)); ?>
    </div>
    <div class="row">
        <h4><?= __('Links') ?></h4>
        <?= $this->Text->autoParagraph(h($store->links)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Albums') ?></h4>
        <?php if (!empty($store->albums)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Store Id') ?></th>
                <th><?= __('Name') ?></th>
                <th><?= __('Description') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Soft Deleted') ?></th>
                <th><?= __('Created') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($store->albums as $albums): ?>
            <tr>
                <td><?= h($albums->id) ?></td>
                <td><?= h($albums->store_id) ?></td>
                <td><?= h($albums->name) ?></td>
                <td><?= h($albums->description) ?></td>
                <td><?= h($albums->status) ?></td>
                <td><?= h($albums->soft_deleted) ?></td>
                <td><?= h($albums->created) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Albums', 'action' => 'view', $albums->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Albums', 'action' => 'edit', $albums->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Albums', 'action' => 'delete', $albums->id], ['confirm' => __('Are you sure you want to delete # {0}?', $albums->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Campaigns') ?></h4>
        <?php if (!empty($store->campaigns)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Store Id') ?></th>
                <th><?= __('Campaign Type') ?></th>
                <th><?= __('Send Before Day') ?></th>
                <th><?= __('Send Date') ?></th>
                <th><?= __('Repeat') ?></th>
                <th><?= __('Repeat Type') ?></th>
                <th><?= __('Compaign Name') ?></th>
                <th><?= __('Subject') ?></th>
                <th><?= __('Message') ?></th>
                <th><?= __('Whos Send') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Soft Deleted') ?></th>
                <th><?= __('Created') ?></th>
                <th><?= __('Embedcode') ?></th>
                <th><?= __('Send At') ?></th>
                <th><?= __('Cost Multiplier') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($store->campaigns as $campaigns): ?>
            <tr>
                <td><?= h($campaigns->id) ?></td>
                <td><?= h($campaigns->store_id) ?></td>
                <td><?= h($campaigns->campaign_type) ?></td>
                <td><?= h($campaigns->send_before_day) ?></td>
                <td><?= h($campaigns->send_date) ?></td>
                <td><?= h($campaigns->repeat) ?></td>
                <td><?= h($campaigns->repeat_type) ?></td>
                <td><?= h($campaigns->compaign_name) ?></td>
                <td><?= h($campaigns->subject) ?></td>
                <td><?= h($campaigns->message) ?></td>
                <td><?= h($campaigns->whos_send) ?></td>
                <td><?= h($campaigns->status) ?></td>
                <td><?= h($campaigns->soft_deleted) ?></td>
                <td><?= h($campaigns->created) ?></td>
                <td><?= h($campaigns->embedcode) ?></td>
                <td><?= h($campaigns->send_at) ?></td>
                <td><?= h($campaigns->cost_multiplier) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Campaigns', 'action' => 'view', $campaigns->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Campaigns', 'action' => 'edit', $campaigns->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Campaigns', 'action' => 'delete', $campaigns->id], ['confirm' => __('Are you sure you want to delete # {0}?', $campaigns->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Customer Visits') ?></h4>
        <?php if (!empty($store->customer_visits)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Customer Id') ?></th>
                <th><?= __('Came At') ?></th>
                <th><?= __('Visits Till Now') ?></th>
                <th><?= __('Store Id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($store->customer_visits as $customerVisits): ?>
            <tr>
                <td><?= h($customerVisits->id) ?></td>
                <td><?= h($customerVisits->customer_id) ?></td>
                <td><?= h($customerVisits->came_at) ?></td>
                <td><?= h($customerVisits->visits_till_now) ?></td>
                <td><?= h($customerVisits->store_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'CustomerVisits', 'action' => 'view', $customerVisits->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'CustomerVisits', 'action' => 'edit', $customerVisits->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'CustomerVisits', 'action' => 'delete', $customerVisits->id], ['confirm' => __('Are you sure you want to delete # {0}?', $customerVisits->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Customers') ?></h4>
        <?php if (!empty($store->customers)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Store Id') ?></th>
                <th><?= __('Contact No') ?></th>
                <th><?= __('Email') ?></th>
                <th><?= __('Name') ?></th>
                <th><?= __('Gender') ?></th>
                <th><?= __('Dob') ?></th>
                <th><?= __('Doa') ?></th>
                <th><?= __('Added By') ?></th>
                <th><?= __('Opt In') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Soft Deleted') ?></th>
                <th><?= __('Created') ?></th>
                <th><?= __('Age') ?></th>
                <th><?= __('Refered By') ?></th>
                <th><?= __('Spouse Name') ?></th>
                <th><?= __('Spouse Date') ?></th>
                <th><?= __('Kids Info') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($store->customers as $customers): ?>
            <tr>
                <td><?= h($customers->id) ?></td>
                <td><?= h($customers->store_id) ?></td>
                <td><?= h($customers->contact_no) ?></td>
                <td><?= h($customers->email) ?></td>
                <td><?= h($customers->name) ?></td>
                <td><?= h($customers->gender) ?></td>
                <td><?= h($customers->dob) ?></td>
                <td><?= h($customers->doa) ?></td>
                <td><?= h($customers->added_by) ?></td>
                <td><?= h($customers->opt_in) ?></td>
                <td><?= h($customers->status) ?></td>
                <td><?= h($customers->soft_deleted) ?></td>
                <td><?= h($customers->created) ?></td>
                <td><?= h($customers->age) ?></td>
                <td><?= h($customers->refered_by) ?></td>
                <td><?= h($customers->spouse_name) ?></td>
                <td><?= h($customers->spouse_date) ?></td>
                <td><?= h($customers->kids_info) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Customers', 'action' => 'view', $customers->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Customers', 'action' => 'edit', $customers->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Customers', 'action' => 'delete', $customers->id], ['confirm' => __('Are you sure you want to delete # {0}?', $customers->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Messages') ?></h4>
        <?php if (!empty($store->messages)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Customer Id') ?></th>
                <th><?= __('Store Id') ?></th>
                <th><?= __('Campaign Id') ?></th>
                <th><?= __('Promo Code') ?></th>
                <th><?= __('Used') ?></th>
                <th><?= __('Used Date') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Cause') ?></th>
                <th><?= __('Received') ?></th>
                <th><?= __('Open') ?></th>
                <th><?= __('Api Key') ?></th>
                <th><?= __('Api Response') ?></th>
                <th><?= __('Soft Deleted') ?></th>
                <th><?= __('Created') ?></th>
                <th><?= __('Cost Multiplier') ?></th>
                <th><?= __('External Msgid') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($store->messages as $messages): ?>
            <tr>
                <td><?= h($messages->id) ?></td>
                <td><?= h($messages->customer_id) ?></td>
                <td><?= h($messages->store_id) ?></td>
                <td><?= h($messages->campaign_id) ?></td>
                <td><?= h($messages->promo_code) ?></td>
                <td><?= h($messages->used) ?></td>
                <td><?= h($messages->used_date) ?></td>
                <td><?= h($messages->status) ?></td>
                <td><?= h($messages->cause) ?></td>
                <td><?= h($messages->received) ?></td>
                <td><?= h($messages->open) ?></td>
                <td><?= h($messages->api_key) ?></td>
                <td><?= h($messages->api_response) ?></td>
                <td><?= h($messages->soft_deleted) ?></td>
                <td><?= h($messages->created) ?></td>
                <td><?= h($messages->cost_multiplier) ?></td>
                <td><?= h($messages->external_msgid) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Messages', 'action' => 'view', $messages->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Messages', 'action' => 'edit', $messages->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Messages', 'action' => 'delete', $messages->id], ['confirm' => __('Are you sure you want to delete # {0}?', $messages->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Productcats') ?></h4>
        <?php if (!empty($store->productcats)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Name') ?></th>
                <th><?= __('Store Id') ?></th>
                <th><?= __('Gender') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($store->productcats as $productcats): ?>
            <tr>
                <td><?= h($productcats->id) ?></td>
                <td><?= h($productcats->name) ?></td>
                <td><?= h($productcats->store_id) ?></td>
                <td><?= h($productcats->gender) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Productcats', 'action' => 'view', $productcats->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Productcats', 'action' => 'edit', $productcats->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Productcats', 'action' => 'delete', $productcats->id], ['confirm' => __('Are you sure you want to delete # {0}?', $productcats->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Products') ?></h4>
        <?php if (!empty($store->products)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Store Id') ?></th>
                <th><?= __('Category') ?></th>
                <th><?= __('Product Name') ?></th>
                <th><?= __('Purchase Count') ?></th>
                <th><?= __('Productcat Id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($store->products as $products): ?>
            <tr>
                <td><?= h($products->id) ?></td>
                <td><?= h($products->store_id) ?></td>
                <td><?= h($products->category) ?></td>
                <td><?= h($products->product_name) ?></td>
                <td><?= h($products->purchase_count) ?></td>
                <td><?= h($products->productcat_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Products', 'action' => 'view', $products->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Products', 'action' => 'edit', $products->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Products', 'action' => 'delete', $products->id], ['confirm' => __('Are you sure you want to delete # {0}?', $products->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Purchases') ?></h4>
        <?php if (!empty($store->purchases)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Store Id') ?></th>
                <th><?= __('Product Id') ?></th>
                <th><?= __('Customer Id') ?></th>
                <th><?= __('Product Name') ?></th>
                <th><?= __('Qty') ?></th>
                <th><?= __('Price') ?></th>
                <th><?= __('Sold On') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($store->purchases as $purchases): ?>
            <tr>
                <td><?= h($purchases->id) ?></td>
                <td><?= h($purchases->store_id) ?></td>
                <td><?= h($purchases->product_id) ?></td>
                <td><?= h($purchases->customer_id) ?></td>
                <td><?= h($purchases->product_name) ?></td>
                <td><?= h($purchases->qty) ?></td>
                <td><?= h($purchases->price) ?></td>
                <td><?= h($purchases->sold_on) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Purchases', 'action' => 'view', $purchases->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Purchases', 'action' => 'edit', $purchases->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Purchases', 'action' => 'delete', $purchases->id], ['confirm' => __('Are you sure you want to delete # {0}?', $purchases->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Pushmessages') ?></h4>
        <?php if (!empty($store->pushmessages)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Store Id') ?></th>
                <th><?= __('Store Slug') ?></th>
                <th><?= __('At Time') ?></th>
                <th><?= __('Image') ?></th>
                <th><?= __('Title') ?></th>
                <th><?= __('Message') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Soft Deleted') ?></th>
                <th><?= __('Created') ?></th>
                <th><?= __('Modified') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($store->pushmessages as $pushmessages): ?>
            <tr>
                <td><?= h($pushmessages->id) ?></td>
                <td><?= h($pushmessages->store_id) ?></td>
                <td><?= h($pushmessages->store_slug) ?></td>
                <td><?= h($pushmessages->at_time) ?></td>
                <td><?= h($pushmessages->image) ?></td>
                <td><?= h($pushmessages->title) ?></td>
                <td><?= h($pushmessages->message) ?></td>
                <td><?= h($pushmessages->status) ?></td>
                <td><?= h($pushmessages->soft_deleted) ?></td>
                <td><?= h($pushmessages->created) ?></td>
                <td><?= h($pushmessages->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Pushmessages', 'action' => 'view', $pushmessages->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Pushmessages', 'action' => 'edit', $pushmessages->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Pushmessages', 'action' => 'delete', $pushmessages->id], ['confirm' => __('Are you sure you want to delete # {0}?', $pushmessages->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Questions') ?></h4>
        <?php if (!empty($store->questions)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Store Id') ?></th>
                <th><?= __('Question') ?></th>
                <th><?= __('Question Type') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Soft Deleted') ?></th>
                <th><?= __('Created') ?></th>
                <th><?= __('View Type') ?></th>
                <th><?= __('No Delete') ?></th>
                <th><?= __('No Skip') ?></th>
                <th><?= __('Order Seq') ?></th>
                <th><?= __('Buyer') ?></th>
                <th><?= __('Group Mark') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($store->questions as $questions): ?>
            <tr>
                <td><?= h($questions->id) ?></td>
                <td><?= h($questions->store_id) ?></td>
                <td><?= h($questions->question) ?></td>
                <td><?= h($questions->question_type) ?></td>
                <td><?= h($questions->status) ?></td>
                <td><?= h($questions->soft_deleted) ?></td>
                <td><?= h($questions->created) ?></td>
                <td><?= h($questions->view_type) ?></td>
                <td><?= h($questions->no_delete) ?></td>
                <td><?= h($questions->no_skip) ?></td>
                <td><?= h($questions->order_seq) ?></td>
                <td><?= h($questions->buyer) ?></td>
                <td><?= h($questions->group_mark) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Questions', 'action' => 'view', $questions->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Questions', 'action' => 'edit', $questions->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Questions', 'action' => 'delete', $questions->id], ['confirm' => __('Are you sure you want to delete # {0}?', $questions->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Recommend Screen') ?></h4>
        <?php if (!empty($store->recommend_screen)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Store Id') ?></th>
                <th><?= __('Active') ?></th>
                <th><?= __('Header Text') ?></th>
                <th><?= __('Description') ?></th>
                <th><?= __('Created') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($store->recommend_screen as $recommendScreen): ?>
            <tr>
                <td><?= h($recommendScreen->id) ?></td>
                <td><?= h($recommendScreen->store_id) ?></td>
                <td><?= h($recommendScreen->active) ?></td>
                <td><?= h($recommendScreen->header_text) ?></td>
                <td><?= h($recommendScreen->description) ?></td>
                <td><?= h($recommendScreen->created) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'RecommendScreen', 'action' => 'view', $recommendScreen->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'RecommendScreen', 'action' => 'edit', $recommendScreen->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'RecommendScreen', 'action' => 'delete', $recommendScreen->id], ['confirm' => __('Are you sure you want to delete # {0}?', $recommendScreen->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Share Screen') ?></h4>
        <?php if (!empty($store->share_screen)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Store Id') ?></th>
                <th><?= __('Active') ?></th>
                <th><?= __('Header Text') ?></th>
                <th><?= __('Description') ?></th>
                <th><?= __('Created') ?></th>
                <th><?= __('Popup Title') ?></th>
                <th><?= __('Popup Message') ?></th>
                <th><?= __('Popup Url') ?></th>
                <th><?= __('Embedcode') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($store->share_screen as $shareScreen): ?>
            <tr>
                <td><?= h($shareScreen->id) ?></td>
                <td><?= h($shareScreen->store_id) ?></td>
                <td><?= h($shareScreen->active) ?></td>
                <td><?= h($shareScreen->header_text) ?></td>
                <td><?= h($shareScreen->description) ?></td>
                <td><?= h($shareScreen->created) ?></td>
                <td><?= h($shareScreen->popup_title) ?></td>
                <td><?= h($shareScreen->popup_message) ?></td>
                <td><?= h($shareScreen->popup_url) ?></td>
                <td><?= h($shareScreen->embedcode) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'ShareScreen', 'action' => 'view', $shareScreen->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'ShareScreen', 'action' => 'edit', $shareScreen->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'ShareScreen', 'action' => 'delete', $shareScreen->id], ['confirm' => __('Are you sure you want to delete # {0}?', $shareScreen->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Sharedcodes') ?></h4>
        <?php if (!empty($store->sharedcodes)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Client Id') ?></th>
                <th><?= __('Customer Id') ?></th>
                <th><?= __('Code') ?></th>
                <th><?= __('Type') ?></th>
                <th><?= __('Store Id') ?></th>
                <th><?= __('Redeemed Count') ?></th>
                <th><?= __('Created At') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($store->sharedcodes as $sharedcodes): ?>
            <tr>
                <td><?= h($sharedcodes->id) ?></td>
                <td><?= h($sharedcodes->client_id) ?></td>
                <td><?= h($sharedcodes->customer_id) ?></td>
                <td><?= h($sharedcodes->code) ?></td>
                <td><?= h($sharedcodes->type) ?></td>
                <td><?= h($sharedcodes->store_id) ?></td>
                <td><?= h($sharedcodes->redeemed_count) ?></td>
                <td><?= h($sharedcodes->created_at) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Sharedcodes', 'action' => 'view', $sharedcodes->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Sharedcodes', 'action' => 'edit', $sharedcodes->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Sharedcodes', 'action' => 'delete', $sharedcodes->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sharedcodes->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Templatemessages') ?></h4>
        <?php if (!empty($store->templatemessages)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Store Id') ?></th>
                <th><?= __('Type') ?></th>
                <th><?= __('Message') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Soft Deleted') ?></th>
                <th><?= __('Created') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($store->templatemessages as $templatemessages): ?>
            <tr>
                <td><?= h($templatemessages->id) ?></td>
                <td><?= h($templatemessages->store_id) ?></td>
                <td><?= h($templatemessages->type) ?></td>
                <td><?= h($templatemessages->message) ?></td>
                <td><?= h($templatemessages->status) ?></td>
                <td><?= h($templatemessages->soft_deleted) ?></td>
                <td><?= h($templatemessages->created) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Templatemessages', 'action' => 'view', $templatemessages->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Templatemessages', 'action' => 'edit', $templatemessages->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Templatemessages', 'action' => 'delete', $templatemessages->id], ['confirm' => __('Are you sure you want to delete # {0}?', $templatemessages->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Welcomemsgs') ?></h4>
        <?php if (!empty($store->welcomemsgs)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Customer Id') ?></th>
                <th><?= __('Type') ?></th>
                <th><?= __('Created') ?></th>
                <th><?= __('Store Id') ?></th>
                <th><?= __('Cost Multiplier') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($store->welcomemsgs as $welcomemsgs): ?>
            <tr>
                <td><?= h($welcomemsgs->id) ?></td>
                <td><?= h($welcomemsgs->customer_id) ?></td>
                <td><?= h($welcomemsgs->type) ?></td>
                <td><?= h($welcomemsgs->created) ?></td>
                <td><?= h($welcomemsgs->store_id) ?></td>
                <td><?= h($welcomemsgs->cost_multiplier) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Welcomemsgs', 'action' => 'view', $welcomemsgs->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Welcomemsgs', 'action' => 'edit', $welcomemsgs->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Welcomemsgs', 'action' => 'delete', $welcomemsgs->id], ['confirm' => __('Are you sure you want to delete # {0}?', $welcomemsgs->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
