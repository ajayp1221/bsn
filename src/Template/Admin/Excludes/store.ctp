<?= $this->element('header') ?>
<div id="base">
    <div class="offcanvas"></div>
    <div id="content">
        <section>
            <div class="section-body contain-lg">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="text-primary">Exclude Store</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-offset-1 col-md-10">
                        <div class="card">
                            <div class="card-body">
                                <?= $this->Form->create($exclude, ['class' => 'form']) ?>
                                <div class="form-group">
                                    <label>Model Name</label>
                                    <?php
                                    $model_name = array('Stores' => 'Stores');
                                    echo $this->Form->input('model_name', ['options' => $model_name, 'class' => "form-control select2-list", 'data-placeholder' => "Select an item", 'label' => false]);
                                    ?>
                                </div>
                                <div class="form-group">
                                    <?php echo $this->Form->input('value', ['options' => $stores, 'class' => "form-control select2-list", 'data-placeholder' => "Select an item", 'label' => false]);?>
                                    <label>Store</label>
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

