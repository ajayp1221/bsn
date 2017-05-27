<?= $this->element('tablecss') ?>
<div class="menubar-hoverable header-fixed ">
    <?= $this->element('header') ?>

    <!-- BEGIN BASE-->
    <div id="base">
        <div class="offcanvas"></div>
        <div id="content">
            <section class="style-default">
                <div class="section-body">
                    <div class="add_new"><?= $this->Html->link(__('Add New Client'), ['action' => 'add']) ?> </div>
                    <h2 class="text-primary">Client Table</h2><br/>
                    <div style="text-align: center;font-size: 15px;font-weight: bold;">
                        <p>Need Schedule Campaign Sms Credit : <?= $messages; ?></p>
                        <p>Total Sms Credit Required :  <?= $sum; ?></p>
                        Client Max Scheduled Messages : 
                        <?php if (@$maxclientcmsneed->customersCount) { ?>
                            <?= $maxclientcmsneed->customersCount; ?> (<?= $maxclientcmsneed->store->brand->client->name ?>)
                        <?php } ?>
                    </div>
                    <?= $this->Form->create(NULL, ['type' => 'get','class' => 'form-horizontal']) ?>
                    <div class="form-group">
                            <div class="col-sm-12">
                                    <div class="input-group">
                                            <div class="input-group-content">
                                                    <input type="text" name="q" placeholder="Search Here" id="groupbutton17" class="form-control">
                                                    <div class="form-control-line"></div>
                                            </div>
                                            <div class="input-group-btn">
                                                    <?= $this->Form->button(__('Search!'),['class' => 'btn btn-success']) ?>
                                            </div>
                                    </div>
                            </div>
                    </div>
                    <?= $this->Form->end() ?>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><?= $this->Paginator->sort('id') ?></th>
                                <th><?= $this->Paginator->sort('name') ?></th>
                                <th><?= $this->Paginator->sort('contact_no') ?></th>
                                <th><?= $this->Paginator->sort('Left Message', 'sms_quantity') ?></th>
                                <th><?= $this->Paginator->sort('max_device_limit') ?></th>
                                <th class="text-right"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clients as $client): ?>
                                <tr>
                                    <td><?= $this->Number->format($client->id) ?></td>
                                    <td><?= h($client->name) ?></td>
                                    <td><?= $this->Number->format($client->contact_no) ?></td>
                                    <td>
                                        SMS - <?= $this->Number->format($client->sms_quantity) ?><br/>
                                        Email - <?= $this->Number->format($client->email_left) ?><br/>
                                        <?php
                                        $date = date('Y/m/d H:i');
                                        $cmpTbl = \Cake\ORM\TableRegistry::get('Campaigns');
                                        $brndTbl = \Cake\ORM\TableRegistry::get('Brands');
                                        $msgTbl = \Cake\ORM\TableRegistry::get('Messages');

                                        $clientId = $brndTbl->find()->where(['client_id' => $client->id])->contain(['Stores'])->first();
                                        if (@$clientId->stores) {
                                            $data = new \Cake\Collection\Collection($clientId->stores);
                                            $strid = $data->combine("id", "id")->toArray();
                                            $campaigns = "";
                                            $campaigns = $cmpTbl->find()->where(['store_id IN' => $strid, 'status' => 1, 'send_date >=' => $date])->toArray();
                                            if ($campaigns) {
                                                $cmpId = new \Cake\Collection\Collection($campaigns);
                                                $cmpIds = $cmpId->combine("id", "id")->toArray();
                                                if ($cmpIds) {
                                                    $creditRequired = $msgTbl->find()->where(['campaign_id IN' => $cmpIds])->count();
                                                    echo "<b>Required Sms - " . $creditRequired . "</b>";
                                                }
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <div class="numbers-row">
                                            <input type="hidden" name="id" class="id" value="<?= $client->id; ?>">
                                            <input type="text" name="french-hens" class="max_device_limit" value="<?= $client->max_device_limit; ?>">
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <?= $this->Html->link(__('AddPlan'), ['action' => 'addplan', $client->id]) ?><br/>
                                        <?= $this->Html->link(__('View'), ['action' => 'view', $client->id]) ?><br/>
                                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $client->id]) ?><br/>
                                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $client->id], ['confirm' => __('Are you sure you want to delete # {0}?', $client->id)]) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="paginator">
                        <ul class="pagination">
                            <?= $this->Paginator->prev('< ' . __('previous')) ?>
                            <?= $this->Paginator->numbers() ?>
                            <?= $this->Paginator->next(__('next') . ' >') ?>
                        </ul>
                        <p><?= $this->Paginator->counter() ?></p>
                    </div>
                </div>
            </section>
            <?= $this->element('side') ?>
        </div>
        
    </div>
</div>
<?= $this->element('tablejs') ?>

<script type="text/javascript">
    $(function () {
        $(".numbers-row").append('<div class="inc button1">+</div><div class="dec button1">-</div>');
        $(".button1").on("click", function () {
            var $button = $(this);
            var oldValue = $button.parent().find(".max_device_limit").val();
            var idValue = $button.parent().find(".id").val();
            if ($button.text() == "+") {
                var newVal = parseFloat(oldValue) + 1;
            } else {
                // Don't allow decrementing below zero
                if (oldValue > 0) {
                    var newVal = parseFloat(oldValue) - 1;
                } else {
                    newVal = 0;
                }
            }
            $button.parent().find(".max_device_limit").val(newVal);
            $.post('/admin/clients/update.json', {id: idValue, max_device_limit: newVal}, function (d) {

            });
        });
    });
</script>
<style>
    .max_device_limit{
        float: left;
        border: 1px solid #0aa89e;
        width: 40px !important;
        font: bold 20px Helvetica, sans-serif;
        padding: 3px 0 0 0;
        text-align: center;
    }
    .button1{
        background-color: #0AA89E;
        color: #ffffff;
        cursor: pointer;
        float: left;
        height: 30px;
        padding: 3px 0 0;
        text-align: center;
        width: 20px;
        font-weight: bolder;
    }
    .add_new{
        color: #0AA89E;
        float: right;
        margin-bottom: 20px;
        padding-bottom: 0.5rem;
        size: 20px;
        font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;
        font-style: normal;
        font-weight: bold;
        margin-top: 0.2rem;
        text-rendering: optimizelegibility;
        font-size: 1.2875rem;
    }
</style>