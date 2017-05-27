<?= $this->element('header') ?>
<div id="base">
    <div class="offcanvas"></div>
    <div id="content">
        <section>
            <div class="section-body contain-lg">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="text-primary">Add New Store</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-offset-1 col-md-10">
                        <div class="card">
                            <div class="card-body">
                                <?= $this->Form->create($store, ['class' => 'form']) ?>
                                <div class="form-group">
                                    <label>Client</label>
                                    <?php
                                    echo $this->Form->input('client_id', ['options' => $clients, 'class' => "form-control select2-list", 'data-placeholder' => "Select an item", 'label' => false]);
                                    ?>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" type="text" name='name' data-bind="jqAuto: { value: myValue, source: getOptions, inputProp: 'store_name',  template: 'rowTmpl' }" />
                                    <label for="name">Name</label>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" type="text" name='address' />
                                    <label for="name">Address</label>
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
