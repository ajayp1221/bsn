<?= $this->element('header') ?>
<div id="base">
    <div class="offcanvas"></div>
    <div id="content">
        <section>
            <div class="section-body contain-lg">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="text-primary"><?= h($store->name) ?></h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-offset-1 col-md-10">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Id -:</div>
                                    <div class="col-md-8"><?= h($store->id) ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Brand -:</div>
                                    <div class="col-md-8"><?= $store->has('brand') ? $this->Html->link($store->brand->brand_name, ['controller' => 'brands', 'action' => 'view', $store->brand->id]) : '' ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Name -:</div>
                                    <div class="col-md-8"><?= h($store->name) ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Status -:</div>
                                    <div class="col-md-8"><?php
                                        if ($store->status) {
                                            echo "active";
                                        } else {
                                            echo "deactive";
                                        }
                                        ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Created -:</div>
                                    <div class="col-md-8"><?= date("Y-m-d H:i:s", $store->created) ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Address -:</div>
                                    <div class="col-md-8"><?= $this->Text->autoParagraph(h($store->address)); ?></div><hr/>
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
                        <h2 class="text-primary">Related Campaigns</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-offset-1 col-md-10">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table no-margin">
                                        <tr>
                                            <th><?= __('Id') ?></th>
                                            <th><?= __('Campaign Type') ?></th>
                                            <th><?= __('Send Before Day') ?></th>
                                            <th><?= __('Send Date') ?></th>
                                            <th><?= __('Repeat') ?></th>
                                            <th><?= __('Compaign Name') ?></th>
                                            <th><?= __('Status') ?></th>
                                            <th><?= __('Created') ?></th>
                                            <th class="actions">Action</th>
                                        </tr>
                                        <?php foreach ($store->campaigns as $campaigns): ?>
                                            <tr>
                                                <td><?= h($campaigns->id) ?></td>
                                                <td><?= h($campaigns->campaign_type) ?></td>
                                                <td><?= h($campaigns->send_before_day) ?></td>
                                                <td><?= h($campaigns->send_date) ?></td>
                                                <td><?= h($campaigns->repeat) ?></td>
                                                <td><?= h($campaigns->compaign_name) ?></td>
                                                <td><?php
                                                    if ($campaigns->status) {
                                                        echo "Active";
                                                    } else {
                                                        echo "Inactive";
                                                    }
                                                    ?></td>
                                                <td><?php echo date("Y-m-d H:i", $campaigns->created); ?></td>
                                                <td class="actions">
                                                    <?php echo $this->Html->link(__('View'), ['controller' => 'Campaigns', 'action' => 'view', $campaigns->id]); ?>
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
    </div>
    <?php echo $this->element('side'); ?>
</div>
<?= $this->element('tablejs') ?>