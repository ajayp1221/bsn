<div>
    <?= $this->Flash->render() ?>
</div>
<?= $this->fetch('content') ?>



<!-- Modal for homepop upload-->

<div class="modal fade" id="homepop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="home-popup">
        <?php if ($authUser) { ?>
            <div class="left-sec"> <a data-toggle="modal" data-target="#myModalnew" href="#"><img src="/images/lookbook-img2.png" alt="" /></a> <span>Upload a lookbook</span> </div>
            <div class="left-sec"> <a href="#" data-toggle="modal" data-target="#writepopup"><img src="/images/review-img.png"  alt="" /></a> <span>Write a review</span> </div>
        <?php } else { ?>
            <div class="left-sec"> <a data-toggle="modal" data-target="#myModal" href="#"><img src="/images/lookbook-img2.png" alt="" /></a> <span>Upload a lookbook</span> </div>
            <div class="left-sec"> <a data-toggle="modal" data-target="#myModal" href="#"><img src="/images/review-img.png"  alt="" /></a> <span>Write a review</span> </div>
        <?php } ?>
    </div>
</div>
<div class="modal fade" id="writepopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="user-popup5">
        <div class="user-popup-inner"> 
            <a href="#" class="close" data-dismiss="modal"> 
                <img src="/images/popup-close-img.jpg" alt="" class="close-icon" />
            </a>
            <div class="write-input">
                <div class="header-search  header-search1">                           
                    <input type="text" value="" id="stores_avl" maxlength="255" placeholder="eg. Meena Bazaar" name="store_id">
                    <input type="submit" name="">
                </div>

            </div>
        </div>

    </div>
    <!-- Modal -->     
</div>
<!-- Modal for homepop upload End-->
<script>
    $(function () {
        $("#stores_avl").autocomplete({
            source: function (request, response) {
                $.getJSON("/stores/ajax_store_search", {
                    term: request.term
                }, function (d) {
                    var rsp = [];
                    for (i in d) {
                        rsp.push({
                            label: d[i],
                            value: i
                        });
                    }
                    response(rsp);
                });
            },
            minLength: 2,
            select: function (event, ui) {
                event.preventDefault();
                $(this).val(stripHTML(ui.item.label));
//                    $(this).parent().find('#zk-keyword').val(ui.item.value);
//                    $("#change_url").attr("href","/<?= $ClientLocation->slug ?>/"+ui.item.value);
                window.location.href = "/<?= $ClientLocation->slug ?>/" + ui.item.value + "/#ReviewStore";
            },
            focus: function (event, ui) {
                event.preventDefault();
//                    $(this).parent().find('#zk-keyword').val(ui.item.value);
//                    $("#change_url").attr("href","/<?= $ClientLocation->slug ?>/"+ui.item.value);

            }
        }).data("ui-autocomplete")._renderItem = function (ul, item) {
            return $("<li></li>")
                    .data("item.autocomplete", item)
                    .append("<a style='font-size:15px;'>" + item.label + "</a>")
                    .appendTo(ul);
        };

//        $("#stores_avl").autocomplete({
//            source: "/stores/ajax_store_slug_search",
//            minLength: 2,
//            select: function (event, ui) {
//                $("#change_url").attr("href", "<?= \Cake\Routing\Router::url('/' . $ClientLocation->slug, true) ?>/" + ui.item.value + '#store_review');
//            }
//        });
    });
</script>


<!-- Loading Gif-->
<div style="position: fixed; z-index: 100000; background: rgba(40,40,40,0.7); top: 0; left: 0; display: none;" id="page-loader">
    <br />
    <br />
    <br />
    <br />
    <br />
    <center>
        <img src="<?php echo \Cake\Routing\Router::url('/bar_loader.gif'); ?>" />
    </center>
</div>
<!-- Loading Gif End-->



<!-- Login popup will triger with id= myModal-->
<div class="modal fade" id="myModal" tabindex='-1' role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop='static'>
    <div class="modal-dialog modal_dial">
        <div class="modal-content">
            <div class="modal-header modal_header_bg" id="_closebtn">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Login to Zakoopi</h4>
            </div>
            <div class="modal-body">
                <div id="dialog-busy" style="display: none;"><div id="busy-text"></div></div>
                <ul class="login_share">
                    <li class="login_facebook"><a href="javascript:;" onclick="fb_login();" id="signInButtons" class="mylink"><img class="fb_login" src="/images/fb.png"></a></li>

                    <li class="login_google"><a href="javascript:;" onclick="login();" id="signInButton" class="mylink"><img class="fb_login" src="/images/google.png"></a></li>
                    <li>
                        <p style="font-size: 16px;">We will never post/share anything without permission.</p>
                    </li>
                </ul>
                <div id="fb-root"></div>
            </div>
        </div>
    </div>

</div>
<!-- Login popup End-->
<?php echo $this->element('lookbook'); ?>
<script>
    $(document).ready(function () {
        $('.left-filter #dLabel, .filter_close').click(function () {
            $('.dropdown-menu.filters_dropdown').toggle();
        });
        $('.click_event_menu').click(function () {
            $('.navbar-collapse.right_menu').toggleClass('show_menu_item');
        });
    });
</script>
<script>

    $(".download-btn").click(function () {
        $("ul.app-box").slideToggle();
    });
    $.pjax.defaults.timeout = 10000;

    if ($.support.pjax) {
        $(document).on('click', 'a:not([no-pjax])', function (event) {
            var container = $('#pjax-container')
            $.pjax.click(event, {container: container})
        });
        $(document).on('pjax:beforeSend', function (xhr, options) {
            console.log(xhr);
            console.log(options);
        });
    }
//            $(document).pjax("a", '#pjax-container');

    $('#page-loader').height($(window).height());
    $('#page-loader').width($(window).width());
    $(document).on('pjax:send', function () {
        $('#page-loader').show();
    });
    $(document).on('pjax:success', function () {
        $('#page-loader').hide();
        try {
            ZKP.pjaxDone();
        } catch (e) {
        }
    });
    $(document).on('pjax:error', function (xhr, status, e) {
//                $("#notifxi").notifxi('q', 'Opps! it seems a slow connection...', 'warn');
    });

    $('.cl-odr-sr').on("change", function () {
        var el = $(this).parent().next().find('select');
        if ($(this).val() == "women-maternity-wear") {
            $('option:first', el).attr("selected", "selected");
            el.attr('disabled', 'disabled');
        } else {
            el.removeAttr('disabled');
        }
    });

</script>
<script>
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-51929805-1', 'zakoopi.com');
    ga('send', 'pageview');
</script>

<style>
    .share-icon {
        display: none;
    }


    #dialog-busy {
        background: #fff none repeat scroll 0 0;
        border-radius: 5px;
        bottom: 0;           
        left: 0;
        opacity: 0.9;
        position: absolute;
        right: 0;
        top: 0;
        z-index: 1000;
    }
    #busy-text {
        background: transparent url("/images/floading.gif") no-repeat scroll center center;
        height: 100%;
        position: absolute;
        width: 100%;
        z-index: 3000;
    }

    .circle > img {
        height: 100% !important;
        width: 100% !important;
    }
</style>