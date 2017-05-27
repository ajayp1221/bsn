<?= $this->element('header') ?>
<div id="base">
    <div class="offcanvas"></div>
    <div id="content">
        <section>
            <div class="section-body">
                <h2 class="text-primary">Stores Report</h2><br/>
                <?php echo $this->Form->create(NULL, ['type' => 'get', 'class' => 'form-horizontal']) ?>
                <div class="form-group">
                    <div class="col-lg-12">
                        <div class="col-md-5 text-bold">
                            <input type="text" name="st" placeholder="startdate" class="dmask form-control" data-inputmask="'alias': 'yyyy-mm-dd'"  data-mask="">
                        </div>
                        <div class="col-md-5 text-bold">
                            <input type="text" name="ed" placeholder="enddate" class="dmask1 form-control" data-inputmask="'alias': 'yyyy-mm-dd'"  data-mask="">
                        </div>
                        <div class="col-md-2">
                            <div class="input-group-btn">
                                <?php echo $this->Form->button(__('Search!'), ['class' => 'btn btn-success']) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo $this->Form->end() ?>
                <div class="col-lg-12">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                    <tr>
                                        <th><?= 'Store Name' ?></th>
                                        <th><?= 'FeedBack' ?></th>
                                        <th><?= 'Mobile-Added-Customer' ?></th>
                                        <th><?= 'Recomemded' ?></th>
                                        <th><?= 'TotalSharedcodes' ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stores as $store): ?>
                                        <tr>
                                            <td><?php echo $store['name']; ?></td>
                                            <td><?php echo $store['answer']; ?></td>
                                            <td><?php echo $store['totalCustomer']; ?></td>
                                            <td><?php echo $store['recomemdedCustomer']; ?></td>
                                            <td><?php echo $store['totalSharedcodes']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
    <?= $this->element('side') ?>
</div>

<?= $this->element('tablejs') ?>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<?= $this->Html->css(['https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.css']) ?>

<script type="text/javascript">
    $(".dmask").datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $(".dmask1").datepicker({
        dateFormat: 'yy-mm-dd'
    });
</script>
<style>
    .add_new{
        color: #0AA89E;
        float: right;
        margin-bottom: 20px;
        padding-bottom: 0.5rem;
        size: 20px;
        font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;
        font-style: normal;
        font-weight: bold;
        margin-top: 0.2rem;
        text-rendering: optimizelegibility;
        font-size: 1.2875rem;
    }
</style>