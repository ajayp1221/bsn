<?= $this->element('header') ?>
<div id="base">
    <div class="offcanvas"></div>
    <div id="content">
        <section>
            <div class="section-body contain-lg">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="text-primary">Update Store</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-offset-1 col-md-10">
                        <div class="card">
                            <div class="card-body">
                                <?= $this->Form->create($store,['class' => 'form']) ?>
                                <div class="form-group">
                                    <?php echo $this->Form->input('discount_on_next_visit'); ?>
                                    <?php echo $this->Form->input('is_customer_feedback',['type' => 'checkbox']); ?>
                                    <?php echo $this->Form->input('is_appdata',['type' => 'checkbox']); ?>
                                    <?php echo $this->Form->input('is_analytics',['type' => 'checkbox']); ?>
                                    <?php echo $this->Form->input('is_mall',['type' => 'checkbox']); ?>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control" value="<?= $store->name?>" />
                                    <label>Store Name</label>
                                </div>
                                <div class="form-group">
                                    <textarea name="address" class="form-control"><?= $store->address?></textarea>
                                    <label>Address</label>
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