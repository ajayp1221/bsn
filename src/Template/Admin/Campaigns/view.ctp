<?= $this->element('header') ?>
<div id="base">
    <div class="offcanvas"></div>
    <div id="content">
        <section>
            <div class="section-body contain-lg">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="text-primary">Campaign</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-offset-1 col-md-10">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Id -:</div>
                                    <div class="col-md-8"><?= h($campaign->id) ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Store -:</div>
                                    <div class="col-md-8"><?= $campaign->has('store') ? $this->Html->link($campaign->store->name, ['controller' => 'Stores', 'action' => 'view', $campaign->store->id]) : '' ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Campaign Name -:</div>
                                    <div class="col-md-8"><?= $this->Text->autoParagraph(h($campaign->compaign_name)); ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Message -:</div>
                                    <div class="col-md-8"><?= $campaign->message ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Campaign Type -:</div>
                                    <div class="col-md-8"><?= h($campaign->campaign_type) ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Send Before Day -:</div>
                                    <div class="col-md-8"><?= h($campaign->send_before_day) ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Send Date -:</div>
                                    <div class="col-md-8"><?= h($campaign->send_date) ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Repeat -:</div>
                                    <div class="col-md-8"><?= h($campaign->repeat) ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Who Send -:</div>
                                    <div class="col-md-8"><?= h($campaign->whos_send) ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Status -:</div>
                                    <div class="col-md-8"><?php
                                        if ($campaign->status) {
                                            echo "active";
                                        } else {
                                            echo "deactive";
                                        }
                                        ?></div><hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-md-4 text-bold">Created -:</div>
                                    <div class="col-md-8"><?= date('Y-M-d H:s', $campaign->created) ?></div><hr/>
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
