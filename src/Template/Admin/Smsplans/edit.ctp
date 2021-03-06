<?= $this->element('header') ?>
<div id="base">
    <div class="offcanvas"></div>
    <div id="content">
        <section>
            <div class="section-body contain-lg">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="text-primary">Update Sms-Plan</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-offset-1 col-md-10">
                        <div class="card">
                            <div class="card-body">
                                <?= $this->Form->create($smsplan, ['class' => 'form']) ?>
                                <div class="form-group">
                                    <input type="text" name="title" class="form-control" value ="<?= $smsplan->title?>" />
                                    <label>Title</label>
                                </div>
                                <div class="form-group">
                                    <textarea name="description" class="form-control"><?= $smsplan->description?></textarea>
                                    <label>Description</label>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="sms" class="form-control" value="<?= $smsplan->sms?>"/>
                                    <label>Sms</label>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="email" class="form-control" value="<?= $smsplan->email?>"/>
                                    <label>Email</label>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="price" class="form-control" value="<?= $smsplan->price?>" />
                                    <label>Price</label>
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <?php
                                    $status = array('1' => 'Active', '0' => 'Inactive');
                                    echo $this->Form->input('status', ['options' => $status, 'class' => "form-control select2-list", 'data-placeholder' => "Select an item", 'label' => false]);
                                    ?>
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
