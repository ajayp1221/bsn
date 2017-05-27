<?= $this->element('header') ?>
<div id="base">
    <div class="offcanvas"></div>
    <div id="content">
        <section>
            <div class="section-body">
                <div class="add_new"><?= $this->Html->link(__('Add New SmsPlans'), ['action' => 'add']) ?> </div>
                <h2 class="text-primary">SmsPlans Table</h2><br/>
                <div class="col-lg-12">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                    <tr>
                                        <th><?= $this->Paginator->sort('id') ?></th>
                                        <th><?= $this->Paginator->sort('title') ?></th>
                                        <th><?= $this->Paginator->sort('sms') ?></th>
                                        <th><?= $this->Paginator->sort('email') ?></th>
                                        <th><?= $this->Paginator->sort('price') ?></th>
                                        <th><?= $this->Paginator->sort('status') ?></th>
                                        <th><?= $this->Paginator->sort('created') ?></th>
                                        <th class="actions"><?= __('Actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($smsplans as $smsplan): ?>
                                        <tr>
                                            <td><?= $this->Number->format($smsplan->id) ?></td>
                                            <td><?= h($smsplan->title) ?></td>
                                            <td><?= $this->Number->format($smsplan->sms) ?></td>
                                            <td><?= $this->Number->format($smsplan->email) ?></td>
                                            <td><?= $this->Number->format($smsplan->price) ?></td>
                                            <td>
                                                <?php
                                                if ($smsplan->status) {
                                                    echo "Active";
                                                } else {
                                                    echo "Inactive";
                                                }
                                                ?></td>
                                            <td><?= date("Y-M-d H:i", $smsplan->created) ?></td>
                                            <td class="actions">
                                                <?php echo $this->Html->link(__('View'), ['action' => 'view', $smsplan->id]); ?>
                                                <?php echo $this->Html->link(__('Edit'), ['action' => 'edit', $smsplan->id]) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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
    </div>
    <?= $this->element('side') ?>
</div>
<?= $this->element('tablejs') ?>
