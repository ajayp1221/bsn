<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Client'), ['action' => 'edit', $client->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Client'), ['action' => 'delete', $client->id], ['confirm' => __('Are you sure you want to delete # {0}?', $client->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Clients'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Client'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Brands'), ['controller' => 'Brands', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Brand'), ['controller' => 'Brands', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Devices'), ['controller' => 'Devices', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Device'), ['controller' => 'Devices', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Sharedcodes'), ['controller' => 'Sharedcodes', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sharedcode'), ['controller' => 'Sharedcodes', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Social Connections'), ['controller' => 'SocialConnections', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Social Connection'), ['controller' => 'SocialConnections', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Socialshares'), ['controller' => 'Socialshares', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Socialshare'), ['controller' => 'Socialshares', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Smsplans'), ['controller' => 'Smsplans', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Smsplan'), ['controller' => 'Smsplans', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="clients view large-9 medium-8 columns content">
    <h3><?= h($client->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Senderid') ?></th>
            <td><?= h($client->senderid) ?></td>
        </tr>
        <tr>
            <th><?= __('Plivo Authid') ?></th>
            <td><?= h($client->plivo_authid) ?></td>
        </tr>
        <tr>
            <th><?= __('Plivo Auth Token') ?></th>
            <td><?= h($client->plivo_auth_token) ?></td>
        </tr>
        <tr>
            <th><?= __('Slug') ?></th>
            <td><?= h($client->slug) ?></td>
        </tr>
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($client->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Email') ?></th>
            <td><?= h($client->email) ?></td>
        </tr>
        <tr>
            <th><?= __('Password') ?></th>
            <td><?= h($client->password) ?></td>
        </tr>
        <tr>
            <th><?= __('Pwd') ?></th>
            <td><?= h($client->pwd) ?></td>
        </tr>
        <tr>
            <th><?= __('Contact No') ?></th>
            <td><?= h($client->contact_no) ?></td>
        </tr>
        <tr>
            <th><?= __('Image') ?></th>
            <td><?= h($client->image) ?></td>
        </tr>
        <tr>
            <th><?= __('Pass Hash') ?></th>
            <td><?= h($client->pass_hash) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($client->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Sms Quantity') ?></th>
            <td><?= $this->Number->format($client->sms_quantity) ?></td>
        </tr>
        <tr>
            <th><?= __('Sms Sent') ?></th>
            <td><?= $this->Number->format($client->sms_sent) ?></td>
        </tr>
        <tr>
            <th><?= __('Email Left') ?></th>
            <td><?= $this->Number->format($client->email_left) ?></td>
        </tr>
        <tr>
            <th><?= __('Email Sent') ?></th>
            <td><?= $this->Number->format($client->email_sent) ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= $this->Number->format($client->status) ?></td>
        </tr>
        <tr>
            <th><?= __('Soft Deleted') ?></th>
            <td><?= $this->Number->format($client->soft_deleted) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= $this->Number->format($client->created) ?></td>
        </tr>
        <tr>
            <th><?= __('Modified') ?></th>
            <td><?= $this->Number->format($client->modified) ?></td>
        </tr>
        <tr>
            <th><?= __('Max Device Limit') ?></th>
            <td><?= $this->Number->format($client->max_device_limit) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Address') ?></h4>
        <?= $this->Text->autoParagraph(h($client->address)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Brands') ?></h4>
        <?php if (!empty($client->brands)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Client Id') ?></th>
                <th><?= __('Logo') ?></th>
                <th><?= __('Brand Name') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Soft Deleted') ?></th>
                <th><?= __('Created') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($client->brands as $brands): ?>
            <tr>
                <td><?= h($brands->id) ?></td>
                <td><?= h($brands->client_id) ?></td>
                <td><?= h($brands->logo) ?></td>
                <td><?= h($brands->brand_name) ?></td>
                <td><?= h($brands->status) ?></td>
                <td><?= h($brands->soft_deleted) ?></td>
                <td><?= h($brands->created) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Brands', 'action' => 'view', $brands->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Brands', 'action' => 'edit', $brands->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Brands', 'action' => 'delete', $brands->id], ['confirm' => __('Are you sure you want to delete # {0}?', $brands->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Devices') ?></h4>
        <?php if (!empty($client->devices)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Client Id') ?></th>
                <th><?= __('Device Uuid') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Added On') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($client->devices as $devices): ?>
            <tr>
                <td><?= h($devices->id) ?></td>
                <td><?= h($devices->client_id) ?></td>
                <td><?= h($devices->device_uuid) ?></td>
                <td><?= h($devices->status) ?></td>
                <td><?= h($devices->added_on) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Devices', 'action' => 'view', $devices->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Devices', 'action' => 'edit', $devices->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Devices', 'action' => 'delete', $devices->id], ['confirm' => __('Are you sure you want to delete # {0}?', $devices->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Sharedcodes') ?></h4>
        <?php if (!empty($client->sharedcodes)): ?>
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
            <?php foreach ($client->sharedcodes as $sharedcodes): ?>
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
        <h4><?= __('Related Social Connections') ?></h4>
        <?php if (!empty($client->social_connections)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Client Id') ?></th>
                <th><?= __('Model') ?></th>
                <th><?= __('Key') ?></th>
                <th><?= __('Secret') ?></th>
                <th><?= __('Access Token') ?></th>
                <th><?= __('Raw Data') ?></th>
                <th><?= __('Email') ?></th>
                <th><?= __('Last Accessed') ?></th>
                <th><?= __('Status') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($client->social_connections as $socialConnections): ?>
            <tr>
                <td><?= h($socialConnections->id) ?></td>
                <td><?= h($socialConnections->client_id) ?></td>
                <td><?= h($socialConnections->model) ?></td>
                <td><?= h($socialConnections->key) ?></td>
                <td><?= h($socialConnections->secret) ?></td>
                <td><?= h($socialConnections->access_token) ?></td>
                <td><?= h($socialConnections->raw_data) ?></td>
                <td><?= h($socialConnections->email) ?></td>
                <td><?= h($socialConnections->last_accessed) ?></td>
                <td><?= h($socialConnections->status) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'SocialConnections', 'action' => 'view', $socialConnections->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'SocialConnections', 'action' => 'edit', $socialConnections->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'SocialConnections', 'action' => 'delete', $socialConnections->id], ['confirm' => __('Are you sure you want to delete # {0}?', $socialConnections->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Socialshares') ?></h4>
        <?php if (!empty($client->socialshares)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Client Id') ?></th>
                <th><?= __('Image') ?></th>
                <th><?= __('Description') ?></th>
                <th><?= __('Facebook') ?></th>
                <th><?= __('Twitter') ?></th>
                <th><?= __('Instagram') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Soft Deleted') ?></th>
                <th><?= __('Created') ?></th>
                <th><?= __('Schedule Date') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($client->socialshares as $socialshares): ?>
            <tr>
                <td><?= h($socialshares->id) ?></td>
                <td><?= h($socialshares->client_id) ?></td>
                <td><?= h($socialshares->image) ?></td>
                <td><?= h($socialshares->description) ?></td>
                <td><?= h($socialshares->facebook) ?></td>
                <td><?= h($socialshares->twitter) ?></td>
                <td><?= h($socialshares->instagram) ?></td>
                <td><?= h($socialshares->status) ?></td>
                <td><?= h($socialshares->soft_deleted) ?></td>
                <td><?= h($socialshares->created) ?></td>
                <td><?= h($socialshares->schedule_date) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Socialshares', 'action' => 'view', $socialshares->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Socialshares', 'action' => 'edit', $socialshares->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Socialshares', 'action' => 'delete', $socialshares->id], ['confirm' => __('Are you sure you want to delete # {0}?', $socialshares->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Smsplans') ?></h4>
        <?php if (!empty($client->smsplans)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Title') ?></th>
                <th><?= __('Description') ?></th>
                <th><?= __('Sms') ?></th>
                <th><?= __('Email') ?></th>
                <th><?= __('Price') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Soft Deleted') ?></th>
                <th><?= __('Created') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($client->smsplans as $smsplans): ?>
            <tr>
                <td><?= h($smsplans->id) ?></td>
                <td><?= h($smsplans->title) ?></td>
                <td><?= h($smsplans->description) ?></td>
                <td><?= h($smsplans->sms) ?></td>
                <td><?= h($smsplans->email) ?></td>
                <td><?= h($smsplans->price) ?></td>
                <td><?= h($smsplans->status) ?></td>
                <td><?= h($smsplans->soft_deleted) ?></td>
                <td><?= h($smsplans->created) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Smsplans', 'action' => 'view', $smsplans->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Smsplans', 'action' => 'edit', $smsplans->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Smsplans', 'action' => 'delete', $smsplans->id], ['confirm' => __('Are you sure you want to delete # {0}?', $smsplans->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
