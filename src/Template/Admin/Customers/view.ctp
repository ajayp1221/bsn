<?php echo $this->element('side');?>
<div class="customers view large-9 medium-8 columns content">
    <h3><?= h($customer->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($customer->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Store') ?></th>
            <td><?= $customer->has('store') ? $this->Html->link($customer->store->name, ['controller' => 'Stores', 'action' => 'view', $customer->store->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Email') ?></th>
            <td><?= h($customer->email) ?></td>
        </tr>
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($customer->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Contact No') ?></th>
            <td><?= $this->Number->format($customer->contact_no) ?></td>
        </tr>
        <tr>
            <th><?= __('Gender') ?></th>
            <td><?php if($customer->gender==1){echo "MALE";}else{echo "FEAMLE";} ?></td>
        </tr>
        <tr>
            <th><?= __('Dob') ?></th>
            <td><?= date("Y-M-d",$customer->dob) ?></td>
        </tr>
        <tr>
            <th><?= __('Doa') ?></th>
            <td><?= date("Y-M-d",$customer->doa) ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= $this->Number->format($customer->status) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= date("Y-M-d H:i",$customer->created) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Messages') ?></h4>
        <?php if (!empty($customer->messages)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Promo Code') ?></th>
                <th><?= __('Used') ?></th>
                <th><?= __('Used Date') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Received') ?></th>
                <th><?= __('Created') ?></th>
            </tr>
            <?php foreach ($customer->messages as $messages): ?>
            <tr>
                <td><?= h($messages->id) ?></td>
                <td><?= h($messages->promo_code) ?></td>
                <td><?php if($messages->used==1){echo "used";}else{echo "unused";} ?></td>
                <td><?= h($messages->used_date) ?></td>
                <td><?= h($messages->status) ?></td>
                <td><?= h($messages->received) ?></td>
                <td><?= date("Y-M-d H:i",$messages->created) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>