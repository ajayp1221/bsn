<?= $this->element('header') ?>
<div id="base">
    <div class="offcanvas"></div>
    <div id="content">
        <section>
            <div class="section-body">
               
                <h2 class="text-primary">Management Panel</h2><br/>
                
                
                <div class="col-lg-6">
                    <div class="card-body">
                        <pre id="gearManStatus">
                            
                        </pre>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card-body">
                        <div class="table-responsive">
                            
                        </div>
                    </div>
                </div>
                
            </div>
        </section>
    </div>
    <?= $this->element('side') ?>
</div>
<?= $this->element('tablejs') ?>
<script type="text/javascript">
    var DashboardVM = function(){
      var me = this;
      me.updateGearmanStatus = function(){
          $.get('/admin/dashboard/gearstats',function(d){
              $('#gearManStatus').html(d);
          });
      };
      me._init = function(){
          
      };
      me._init();
    };
    var dashObj = new Dashboard();
    $(function () {
        setInterval(function(){
            dashObj.updateGearmanStatus();
        },3000);
    });
</script>
