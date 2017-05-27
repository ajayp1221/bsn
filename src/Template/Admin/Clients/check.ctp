<?php echo $this->element('side'); ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">
<div class="clients index large-9 medium-8 columns content">
    <h3><?= __('Report') ?></h3>
    <?= $this->Form->create(NULL, ['type' => 'get']) ?>
    <input type="text" name="st" value="<?= @$this->request->query['st'];?>" placeholder="Start Date" id="datepicker"/>
    <input type="text" name="et" value="<?= @$this->request->query['et'];?>" placeholder="End Date"  id="datepicker1"/>
    <?= $this->Form->button(__('Search')) ?>
    <?= $this->Form->end() ?>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th>Answer</th>
                <th>Customers</th>
                <th>Social-Shares</th>
                <th>Redeemed CPN</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= $answers ?></td>
                <td><?= ($customers) ?></td>
                <td><?= $socialshares ?></td>
                <td><?= $messages ?></td>
            </tr>
        </tbody>
    </table>
</div>
<script>
    $(function () {
        $("#datepicker").datepicker();
        $("#datepicker1").datepicker();
    });
</script>
