<?= $this->element('header') ?>
<div id="cover"></div>
<div id="base">
    <div class="offcanvas"></div>
    <div id="content">
        <section>
            <div class="section-body">
                <h2 class="text-primary">Verify</h2><br/>
                <div class="msg"></div>
                <div class="col-lg-12">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                    <tr>
                                        <th><?= $this->Paginator->sort('id') ?></th>
                                        <th><?= 'Store Name' ?></th>
                                        <th><?= $this->Paginator->sort('amount') ?></th>
                                        <th><?= $this->Paginator->sort('status') ?></th>
                                        <th><?= $this->Paginator->sort('created') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bills as $bill): ?>
                                        <tr>
                                            <td><?= $this->Number->format($bill->id) ?></td>
                                            <td><?= $bill->store->name ?></td>
                                            <td><?= $bill->amount ?>
                                                <a href="#" class="pop">
                                                    <img src="/<?php echo  @$bill->bill_file ?>" />
                                                </a>
                                            </td>
                                            <td>
                                                <?php if($bill->status!="VERIFIED"){ if($bill->status!="INVALID"){?>
                                                    <input type="button" value="VERIFIED" id="verify_<?= $bill->id?>" class="btn btn-success" onclick="verified(<?= $bill->id?>)"/>
                                                    <input type="button" value="INVALID" id="invalid_<?= $bill->id?>" class="btn btn-danger" onclick="invalid(<?= $bill->id?>)"/>
                                                <?php }}
                                                if($bill->status=="VERIFIED" || $bill->status=="INVALID"){
                                                    echo $bill->status;
                                                }
                                                ?>
                                                <span class="status_<?= $bill->id?>"></span>
                                            </td>
                                            <td><?= date("Y-M-d H:i", $bill->created) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="paginator">
                    <ul class="pagination">
                        <?= $this->Paginator->prev('< ' . __('previous')) ?>
                        <?= $this->Paginator->numbers() ?>
                        <?= $this->Paginator->next(__('next') . ' >') ?>
                    </ul>
                    <p><?= $this->Paginator->counter() ?></p>
                </div>
            </div>
        </section>
    </div>
    <?= $this->element('side') ?>
</div>
<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">              
      <div class="modal-body">
      	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <img src="" class="imagepreview" style="width: 100%;" >
      </div>
    </div>
  </div>
</div>
<?= $this->element('tablejs') ?>
<script type="text/javascript">
    
    function verified(id){
        $('#verify_'+id).hide();
        $('#invalid_'+id).hide();
        $('#cover').fadeIn();
        $.post("/verification/mall-verificationcounters/verified.json", {id: id}, function (d) {
            if(d.result.error==0){
                $('.status_'+id).html('VERIFIED');
            }
            $('.msg').html(d.result.msg);
            $('#cover').fadeOut();
        });
    };
    function invalid(id){
        $('#verify_'+id).hide();
        $('#invalid_'+id).hide();
        $('#cover').fadeIn();
        $.post("/verification/mall-verificationcounters/invalid.json", {id: id}, function (d) {
            if(d.result.error==0){
                $('.status_'+id).html('INVALID');
            }
            $('.msg').html(d.result.msg);
            $('#cover').fadeOut();
        });
    };
    $(function() {
        $('.pop').on('click', function() {
                $('.imagepreview').attr('src', $(this).find('img').attr('src'));
                $('#imagemodal').modal('show');   
        });		
    });
    $(window).load(function(){
        $('#cover').fadeOut(1000);
    });
</script>
<style>
#cover {
    background: url("/images/loading.gif") no-repeat scroll center center #FFF;
    position: absolute;
    height: 100%;
    width: 100%;
    }
</style>