<?= $this->element('header') ?>
<div id="base">
    <div class="offcanvas"></div>
    <div id="content">
        <section>
            <div class="section-body contain-lg">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="text-primary">Update Clients</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-offset-1 col-md-10">
                        <div class="card">
                            <div class="card-body">
                                <?= $this->Form->create($client, ['type' => 'file']) ?>
                                <div class="form-group">
                                    <label>Name</label>
                                    <?php echo $this->Form->input('name', ['class' => "form-control", 'label' => false]); ?>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <?php echo $this->Form->input('email', ['class' => "form-control", 'label' => false]); ?>

                                </div>
                                <div class="form-group">
                                    <label>Contact No</label>
                                    <?php echo $this->Form->input('contact_no', ['class' => "form-control", 'label' => false]); ?>
                                </div>
                                <div class="form-group">
                                    <label>Sms Sender Id</label>
                                    <?php echo $this->Form->input('senderid', ['class' => "form-control", 'label' => false]); ?>
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    <?php echo $this->Form->input('address', ['class' => "form-control", 'label' => false]); ?>
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <?php
                                    $status = array('1' => 'Active', '0' => 'Inactive');
                                    echo $this->Form->input('status', ['options' => $status, 'class' => "form-control select2-list", 'data-placeholder' => "Select an item", 'label' => false]);
                                    ?>
                                </div>
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
        </section>
    </div>
    <?= $this->element('side') ?>
</div>
<?= $this->element('formjs') ?>