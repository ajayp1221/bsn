<?php echo $this->element('side'); ?>
<div class="users view large-9 medium-8 columns content">
    <h3><?= h($user->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($user->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Email') ?></th>
            <td><?= h($user->email) ?></td>
        </tr>
        <tr>
            <th><?= __('First Name') ?></th>
            <td><?= h($user->first_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Last Name') ?></th>
            <td><?= h($user->last_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Roles') ?></th>
            <td><?= h($user->roles) ?></td>
        </tr>
        <tr>
            <th><?= __('Gender') ?></th>
            <td><?php if($user->gender=='1'){echo "Male";}else{echo "Female";} ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?php if($user->status=='Active'){echo "Male";}else{echo "Inactive";} ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= date("Y-M-d H:i",($user->created)); ?></td>
        </tr>
    </table>
</div>