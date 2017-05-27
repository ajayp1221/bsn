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
                                    <input type="hidden" name='slug' data-bind="jqAuto: { value: myValue, source: getOptions, inputProp: 'slug',  template: 'rowTmpl' }" />
                                    <input type="hidden" name='zkpstoreid' data-bind="jqAuto: { value: myValue, source: getOptions, inputProp: 'id',  template: 'rowTmpl' }" />
                                </div>
                                <div class="form-group">
                                    <input class="form-control" type="text" name='address' data-bind="jqAuto: { value: myValue, source: getOptions, inputProp: 'store_address', template: 'rowTmpl' }" />
                                    <label for="name">Address</label>
                                </div>
                                <span data-bind="visible:isLoading ">Loading...</span>

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
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<?php echo $this->Html->script(['knockout-min', 'knockout-jqAutocomplete']); ?>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script id="rowTmpl" type="text/html">
    <a>
        <span data-bind="text: id"></span> - 
        <span data-bind="text: store_name"></span>:
        <span data-bind="text: store_address"></span>
        <span data-bind="text: slug"></span>
    </a>
</script>
<script>
            var viewModel = function(){
            var me = this;
                    me.rq = false;
                    me.myValue = ko.observable();
                    me.isLoading = ko.observable(false);
                    me.getOptions = function(searchTerm, callback) {
                    var m = me;
                            m.isLoading(true);
                            if (me.rq){
                    me.rq.abort()
                    }
                    me.rq = $.ajax({
                    dataType: "json",
                            url: "/admin/stores/search.json",
                            data: {
                            query: searchTerm
                            },
                    }).done(function(a, e){callback(a, e); m.isLoading(false); });
                    }
            };
            ko.applyBindings(new viewModel());
</script>
