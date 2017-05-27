<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Zakoopi</title>
        <!-- Bootstrap -->
        <meta name="description" content="" />
        <meta name="viewport" content="initial-scale=1, minimum-scale=1, maximum-scale=1">
        <meta name="robots" content="index,follow" />
        <!-- Bootstrap -->
        <link href="/css/albumshare/bootstrap.css" rel="stylesheet">
        <!-- Custom CSS -->
        <!--[if IE]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <link href="css/ie8.css" rel="stylesheet">
        <![endif]-->
        <link href="/css/albumshare/style.css" rel="stylesheet">
        <link href="/css/albumshare/font-awesome.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,200,200italic,300,300italic,400italic,600,600italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
    </head>
    <body class="loginpage">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="fullpage">
                        <div class="panel-group" id="accordion">
                            <?php $c1 = 1;
                            foreach ($data as $albumName => $d) { ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"> 
                                            <i class="indicator fa fa-lg  fa-angle-down"></i> 
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-<?= $c1 ?>"> <?= $albumName ?> </a> 
                                        </h4>
                                    </div>
                                    <div id="collapse-<?= $c1 ?>" class="panel-collapse collapse <?php if ($c1 == 1) { ?>in<?php } ?>">
                                        <div class="panel-body">
                                            <div class="row">
                                                <ul class="productlisting">
    <?php foreach ($d as $image) { ?>
                                                    <li class="col-md-3 col-sm-3 col-xs-6"> 
                                                        <div class="product-img">
                                                            <a class="viewErBX-btn" data-toggle="modal" class="viewErBX-btn" data-src="<?= $image->android_api_img_large ?>" data-srcbig="<?= $image->android_api_img ?>">
                                                                <img src="<?= $image->android_api_img_medium ?>" alt="" class="img-thumbnail" style="display: block; margin: 0 auto;">
                                                            </a> 
                                                        </div>
                                                        <span class="desc-limit">
                                                            <?= $image->description ?>  
                                                        </span>
                                                        <span class="pricebox"><?= $image->price ?></span>
                                                    </li>
    <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
    <?php $c1++;
} ?>
                            <!--                            <div class="panel panel-default">
                                                            <div class="panel-heading">
                                                                <h4 class="panel-title"> <i class="indicator fa  fa-lg fa-angle-down"></i> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"> Men's Kurta </a> </h4>
                                                            </div>
                                                            <div id="collapseTwo" class="panel-collapse collapse">
                                                                <div class="panel-body"> Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS. </div>
                                                            </div>
                                                        </div>
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">
                                                                <h4 class="panel-title"> <i class="indicator fa  fa-lg fa-angle-down"></i> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseThree"> Men's formalwear </a> </h4>
                                                            </div>
                                                            <div id="collapseThree" class="panel-collapse collapse">
                                                                <div class="panel-body"> Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS. </div>
                                                            </div>
                                                        </div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal  fade" id="viewErBX" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div>
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8">
                        <div class="popupslider-del"> 
                            <div class="panZoomer-parent">
                                <img class="panZoomer" alt="" src="/images/popup-img3.jpg" style="display: block; margin: 2% auto;" > 
                                <img src="/img/pinch.png" class="pinchOverlay" style="position: absolute; top: 30%; left: 35%; width:110px;"/>                                
                            </div>

                            <a href="#" data-dismiss="modal" style="position: fixed;
                               top: 14px;
                               right: 20px; z-index: 999999;"><img title="close" alt="close" class="close-img" src="/images/slider-close1.svg" width="48px" ></a>
                            <!--          <div class="sliderarrow"> 
                                          <a href="#"><img title="Previous" alt="Previous" class="left" src="/images/slider-left-arrow1.png"></a>
                                          <a href="#"> <img title="Next" alt="Next" src="/images/slider-right-arrow1.png" class="right"></a> 
                                      </div>-->
                        </div>
                    </div>
                    <div class=" col-lg-4 col-md-4 col-sm-4">
                        <div class="popuptxt"> 
                            <p id="md-TXT"></p>

                            <b>Click on image to zoom...</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/js/jquery.min.js"></script>
        <script src="/js/jquery.panzoom.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        <script src="/js/jasny-bootstrap.js"></script>
        <style type="text/css">
            .modal-dialog {
                width: 100%;
                height: 100%;
                padding: 0;
            }

            .modal-content {
                height: 100%;
                border-radius: 0;
            }
            .desc-limit {
                overflow: hidden;
                text-overflow: ellipsis;
                display: -webkit-box;
                -webkit-box-orient: vertical;
                -webkit-line-clamp: 1;
            }
        </style>
        <script>
            function toggleChevron(e) {
                $(e.target)
                        .prev('.panel-heading')
                        .find("i.indicator")
                        .toggleClass('fa-angle-down fa-angle-up ');
            }
            $('#accordion').on('hidden.bs.collapse', toggleChevron);
            $('#accordion').on('shown.bs.collapse', toggleChevron);
            $(document).ready(function () {
                
                $('.viewErBX-btn').on("click",function(){
                    var data = $(this).data();
                    $(".panZoomer").attr({src:data.srcbig});
                    $('#md-TXT').html($(this).parent().next().html());
                    $('#viewErBX').modal('show');
                });
                
                $(".panZoomer").css({'max-height':$(window).height() + 'px','width':'100%'});
                
                if($(window).width() > 767){
                    $('.pinchOverlay').remove();
   
                }
                $(".panZoomer, .pinchOverlay").on("click", function () {
                    $('.popuptxt').parent().prev().removeAttr('class');
                    $('.popuptxt').parent().hide(); $('.pinchOverlay').hide();
                    $(".panZoomer").css({'max-height':'auto','width':'auto','height':$(window).height() + 'px'});
//                    $('.panZoomer').height($(window).height());
                    $panzoom = $(".panZoomer").panzoom();
                    $panzoom.parent().on('mousewheel.focal', function( e ) {
                        e.preventDefault();
                        var delta = e.delta || e.originalEvent.wheelDelta;
                        var zoomOut = delta ? delta < 0 : e.originalEvent.deltaY > 0;
                        $panzoom.panzoom('zoom', zoomOut, {
                          increment: 0.1,
                          animate: true,
                          focal: e
                        });
                    });
                    $panzoom.on('panzoomstart', function(e, panzoom, matrix, changed) {
                        
                    });
                    
                });
                $('#viewErBX').on('hidden.bs.modal', function () {
                    $('.popuptxt').parent().prev().attr({'class':'col-lg-8 col-md-8 col-sm-8'});
                    $(".panZoomer").panzoom("destroy");
                    $(".panZoomer").removeAttr('style');
                    $(".panZoomer").css({'display': 'block', 'margin': '2% auto'});
                    $(".panZoomer").css({'max-height':$(window).height() + 'px','width':'100%'});
                    $('.popuptxt, .pinchOverlay').show();
                    $('.popuptxt').parent().show();
                });


            });
        </script>
    </body>
</html>
