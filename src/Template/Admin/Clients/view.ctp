<?= $this->element('header') ?>
<div id="base">
    <div class="offcanvas"></div>
    <div id="content">
        <section>
            <div class="section-body contain-lg">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="text-primary"><?= h($client->name) ?></h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-offset-1 col-md-10">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Id -:</div>
                                    <div class="col-md-8"><?= $client->id ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Name -:</div>
                                    <div class="col-md-8"><?= $client->name ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Email -:</div>
                                    <div class="col-md-8"><?= $client->email ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Contact No -:</div>
                                    <div class="col-md-8"><?= $client->contact_no ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Left Sms Quantity -:</div>
                                    <div class="col-md-8"><?= $client->sms_quantity ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Sms Sent -:</div>
                                    <div class="col-md-8"><?= $client->sms_sent ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Left Email Quantity -:</div>
                                    <div class="col-md-8"><?= $client->email_left ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Email Sent -:</div>
                                    <div class="col-md-8"><?= $client->email_sent ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Address -:</div>
                                    <div class="col-md-8"><?= $client->address ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Status -:</div>
                                    <div class="col-md-8">
                                        <?php
                                        if ($client->status) {
                                            echo 'Active';
                                        } else {
                                            echo "Inactive";
                                        }
                                        ?>
                                    </div>
                                    <hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Created At -:</div>
                                    <div class="col-md-8"><?= date("Y-M-d H:i", $client->created) ?></div><hr/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="section-body contain-lg">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="text-primary">Related Brands</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-offset-1 col-md-10">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table no-margin">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Name</th>
                                                <th>Status</th>
                                                <th>Created</th>
                                                <th>Action</th>
                                            </tr>
                                            <?php foreach ($client->brands as $brands): ?>
                                                <tr>
                                                    <td><?= $brands->id ?></td>
                                                    <td><?= $brands->brand_name ?></td>
                                                    <td>
                                                        <?php
                                                        if ($brands->status) {
                                                            echo 'Active';
                                                        } else {
                                                            echo "Inactive";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?= date("Y-M-d H:i", $brands->created) ?></td>
                                                    <td><?= $this->Html->link(__('View'), ['controller' => 'brands', 'action' => 'view', $brands->id]) ?></td>
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
        <?php if (!empty($client->smsplans)): ?>
            <section>
                <div class="section-body contain-lg">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="text-primary">Related Smsplans</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-offset-1 col-md-10">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table no-margin">
                                            <thead>
                                                <tr>
                                                    <th><?= __('Id') ?></th>
                                                    <th><?= __('Title') ?></th>
                                                    <th><?= __('Sms') ?></th>
                                                    <th><?= __('Price') ?></th>
                                                    <th><?= __('Status') ?></th>
                                                    <th><?= __('Created') ?></th>
                                                </tr>
                                                <?php foreach ($client->smsplans as $smsplans): ?>
                                                    <tr>
                                                        <td><?= h($smsplans->id) ?></td>
                                                        <td><?= h($smsplans->title) ?></td>
                                                        <td><?= h($smsplans->sms) ?></td>
                                                        <td><?= h($smsplans->price) ?></td>
                                                        <td><?php
                                                            if ($smsplans->status) {
                                                                echo "Active";
                                                            } else {
                                                                echo "Inactive";
                                                            }
                                                            ?></td>
                                                        <td><?= date("Y-m-d H:i", $smsplans->created) ?></td>
                                                        <td class="actions">
                                                            <?= $this->Html->link(__('View'), ['controller' => 'Smsplans', 'action' => 'view', $smsplans->id]) ?>
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