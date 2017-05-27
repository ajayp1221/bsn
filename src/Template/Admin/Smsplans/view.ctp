<?= $this->element('header') ?>
<div id="base">
    <div class="offcanvas"></div>
    <div id="content">
        <section>
            <div class="section-body contain-lg">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="text-primary"><?= h($smsplan->title) ?></h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-offset-1 col-md-10">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Id -:</div>
                                    <div class="col-md-8"><?= $smsplan->id ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Title -:</div>
                                    <div class="col-md-8"><?= $smsplan->title ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Sms -:</div>
                                    <div class="col-md-8"><?= $smsplan->sms ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Email -:</div>
                                    <div class="col-md-8"><?= $smsplan->email ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Price -:</div>
                                    <div class="col-md-8"><?= $smsplan->price ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Status -:</div>
                                    <div class="col-md-8"><?php
                                        if ($smsplan->status) {
                                            echo "Active";
                                        } else {
                                            echo "Inactive";
                                        }
                                        ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Created -:</div>
                                    <div class="col-md-8"><?= date("Y-M-d H:i", $smsplan->created) ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Sms -:</div>
                                    <div class="col-md-8"><?= $smsplan->description ?></div><hr/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php if (!empty($smsplan->clients)): ?>
            <section>
                <div class="section-body contain-lg">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="text-primary">Related Clients</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-offset-1 col-md-10">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table no-margin">
                                            <h4><?= __('Related Clients') ?></h4>
                                            <tr>
                                                <th><?= __('Id') ?></th>
                                                <th><?= __('Contact No') ?></th>
                                                <th><?= __('Email') ?></th>
                                                <th><?= __('Status') ?></th>
                                                <th class="actions"><?php echo 'Actions'; ?></th>
                                            </tr>
                                            <?php foreach ($smsplan->clients as $clients): ?>
                                                <tr>
                                                    <td><?= h($clients->id) ?></td>
                                                    <td><?= h($clients->contact_no) ?></td>
                                                    <td><?= h($clients->email) ?></td>
                                                    <td><?php
                                                        if ($clients->status) {
                                                            echo "Active";
                                                        } else {
                                                            echo "Inactive";
                                                        }
                                                        ?></td>
                                                    <td class="actions">
                                                        <?php echo $this->Html->link(__('View'), ['controller' => 'Clients', 'action' => 'view', $clients->id]); ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </div>
    <?php echo $this->element('side'); ?>
</div>
<?= $this->element('tablejs') ?>
