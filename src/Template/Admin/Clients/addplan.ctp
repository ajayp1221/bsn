<?= $this->element('header') ?>
<div id="base">
    <div class="offcanvas"></div>
    <div id="content">
        <section>
            <div class="section-body contain-lg">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="text-primary">Add Plan Client</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-offset-1 col-md-10">
                        <div class="card">
                            <div class="card-body">
                                <?= $this->Form->create($client, ['url' => '', 'class' => 'form']) ?>
                                <div class="form-group">
                                    <?php
                                    echo $this->Form->input('smsplan_id', ['options' => $smsplans, 'class' => "form-control select2-list", 'data-placeholder' => "Select an item", 'label' => false]);
                                    echo "<br/><br/>OR";
                                    echo $this->Form->input('client_id', ['value' => $clientid, 'type' => 'hidden']);
                                    ?>
                                    <label>Sms-Plan</label>
                                </div>
                                <div class="form-group">
                                    <input type="text" id="credit_sms" name="credit_sms" class="form-control" />
                                    <label>Credit Sms</label>
                                </div>
                                <div class="card-actionbar">
                                    <div class="card-actionbar-row">
                                        <button type="submit" class="btn btn-success ink-reaction">Save</button>
                                    </div>
                                </div>
                                <?= $this->Form->end() ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?= $this->element('side') ?>
</div>
<?= $this->element('formjs') ?>