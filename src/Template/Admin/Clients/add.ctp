<?= $this->element('header') ?>
<div id="base">
    <div class="offcanvas"></div>
    <div id="content">
        <section>
            <div class="section-body contain-lg">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="text-primary">Add New Clients</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-offset-1 col-md-10">
                        <div class="card">
                            <div class="card-body">
                                <?= $this->Form->create($client, ['type' => 'file', 'class' => 'form']) ?>
                                <div class="form-group">
                                    <input type="text" id="name" name="name" class="form-control" required="required" />
                                    <label>Name</label>
                                </div>
                                <div class="form-group">
                                    <input type="email" id="email" name="email" class="form-control" required="required" />
                                    <label>Email</label>
                                </div>
                                <div class="form-group">
                                    <input type="text" id="contact_no" name="contact_no" class="form-control" required="required" />
                                    <label>Contact No</label>
                                </div>
                                <div class="form-group">
                                    <input type="text" id="senderid" name="senderid" class="form-control" />
                                    <label>Sms Sender Id</label>
                                </div>
                                <div class="form-group">
                                    <textarea rows="3" class="form-control" name="address" id="address"></textarea>
                                    <label>Address</label>
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