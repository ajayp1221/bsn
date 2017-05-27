<?= $this->element('header') ?>
<div id="base">
    <div class="offcanvas"></div>
    <div id="content">
        <section>
            <div class="section-body contain-lg">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="text-primary">Send Sms To Client</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-offset-1 col-md-10">
                        <div class="card">
                            <div class="card-body">
                                <?= $this->Form->create(NULL, ['class' => 'form']) ?>
                                <div class="form-group">
                                    <select name="c[]" id="e1" class="zk-f-city1"  multiple="multiple">
                                        <?php foreach($clients as $client){?>
                                            <option value="<?= $client->contact_no?>"><?= $client->contact_no." - ".$client->name?></option>
                                       <?php }?>
                                    </select>
                                    <input type="checkbox" id="checkbox" >Select All
                                </div>
                                <div class="form-group">
                                    <textarea name="numbers" class="form-control" placeholder="Type mobile number  separated by commas..."></textarea>
                                    <label>Numbers</label>
                                </div>
                                <div class="form-group">
                                    <textarea name="message" class="form-control" placeholder="Type text sms" required="required"></textarea>
                                    <label>Message</label>
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
<?php echo $this->Html->css('select2.min') ?>
<?= $this->Html->script('jquery.min'); ?>
<?= $this->Html->script('select2.full.min'); ?>
<script>
    $(document).ready(function () {
        $("#e1").select2({
            placeholder: "Select Client Contact Number",
            allowClear: true
        });
        $("#checkbox").click(function(){
            if($("#checkbox").is(':checked') ){
                $("#e1 > option").prop("selected","selected");// Select All Options
                $("#e1").trigger("change");// Trigger change to select 2
            }else{
                $("#e1 > option").removeAttr("selected");
                $("#e1").trigger("change");// Trigger change to select 2
             }
        });

    });
</script>
