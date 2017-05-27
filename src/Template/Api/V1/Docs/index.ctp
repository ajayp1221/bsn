<script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>
<div class="container">
    <?php $cnt = 1;
    foreach ($results as $className => $class) { ?>
        <div class="row">
            <div class="apiClass col-lg-16">
                <h2 id="<?= str_replace("App\\Controller\\Api\\", "", $className) ?>" class="class-name"><b>Class <?= $cnt ?>): </b> <?= $className ?></h2>
    <?php foreach ($class as $methodName => $prop) { ?>
                    <div class="apiMethods">
                        <h3><b>Method: </b><?= $methodName ?></h3>
                        <div class="apiProp">
                                <?php foreach ($prop as $propName => $propValue) { ?>
                                <div>
                                    <?php if (!in_array($propName, ['apiUrl', 'apiDescription'])) { ?>
                                        <h4><b><?= ltrim($propName, 'api') ?></b></h4>
                                        <?php } ?>
                                    <div>
                                        <?php
                                        if (is_array($propValue)) {
                                            echo '<pre class="prettyprint lang-js">';
                                            echo json_encode($propValue,JSON_PRETTY_PRINT);
                                            echo '</pre>';
                                        } else {
                                            echo htmlspecialchars($propValue);
                                        }
                                        ?>
                                    </div>
                                </div>
        <?php } ?>
                        </div>
                    </div>
    <?php } ?>
            </div>
        </div>    
    <?php $cnt++;
} ?>
</div>
<script>
    $('.apiMethods>h3').on('click', function () {
        $(this).next().toggle();
    });
</script>    
<style type="text/css">
    .prettyprint{
        overflow-x: scroll
    }
    h2{
        background: #CCC;
        font-size: 15px;
        padding: 2px;
    }
    h2.class-name {
        background: #0077b3;
        color: #FFFFFF;
        font-size: 17px;
        padding: 3px;
    }
    h3{
        background: #CCC;
        font-size: 14px;
        padding: 2px;
        cursor: pointer;
    }
    h4{
        background: #c4def1;
        font-size: 11px;
        padding: 2px;
    }
    .apiMethods{
        margin-left: 15px;
        border: 1px solid #eee;
        padding: 5px;
    }
    .apiProp {
        margin-left: 30px;
        border: 1px solid #eee;
        padding: 5px;
        display: none;
    }
</style>
