<?php echo $this->element('side'); ?>
<div class="brands view large-9 medium-8 columns content">
    <h3><?= h($brand->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Client') ?></th>
            <td><?= $brand->has('client') ? $this->Html->link($brand->client->name, ['controller' => 'Clients', 'action' => 'view', $brand->client->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Brand Name') ?></th>
            <td><?= h($brand->brand_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($brand->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?php if($brand->status){echo "Active";}else{echo "Inactive";} ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= date("Y-M-d H:i",$brand->created) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Stores') ?></h4>
        <?php if (!empty($brand->stores)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Brand Id') ?></th>
                <th><?= __('Name') ?></th>
                <th><?= __('Address') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Created') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($brand->stores as $stores): ?>
            <tr>
                <td><?= h($stores->id) ?></td>
                <td><?= h($stores->brand_id) ?></td>
                <td><?= h($stores->name) ?></td>
                <td><?= h($stores->address) ?></td>
                <td><?php if($stores->status){echo "Active";}else{echo "Inactive";} ?></td>
                <td><?= date("Y-M-d H:i",$stores->created) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Stores', 'action' => 'view', $stores->id]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
