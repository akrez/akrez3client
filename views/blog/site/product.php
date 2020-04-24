<?php

use app\components\BlogHelper;
use app\components\Helper;
use app\models\FieldList;
use yii\helpers\HtmlPurifier;
use yii\widgets\Breadcrumbs;

$this->title = Yii::$app->view->params['product']['title'];

$blogSlug = (Yii::$app->blog->attribute('slug') ? Yii::$app->blog->attribute('slug') : '');
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => Helper::normalizeArrayUnorder([Yii::$app->blog->attribute('title'), $blogSlug, Yii::$app->blog->attribute('name')], false, ',') . (isset(Yii::$app->view->params['_categories']) && Yii::$app->view->params['_categories'] ? ',' . implode(',', Yii::$app->view->params['_categories']) : ''),
]);

$this->registerCss("
    .carousel-indicators li {
        border-color: gray;
    }
    .carousel-control.left, .carousel-control.right {
        background-image: none;
    }
");

$this->registerCss("
    .row.equal {
        display: flex;
        flex-wrap: wrap;
    }

    .thumbnail {
        margin: 0px;
        border-radius: 0;
    }

    a.thumbnail {
        text-decoration: none;
    }

    .thumbnail img {
        text-decoration: none;
    }

    .thumbnail .caption * {
        margin: 9px 0 0;
    }

");
?>

<?=
Breadcrumbs::widget([
    'homeLink' => [
        'label' => Yii::t('yii', 'Home'),
        'url' => BlogHelper::blogFirstPageUrl(),
    ],
    'links' => [
        ['label' => Yii::$app->view->params['_categories'][Yii::$app->view->params['categoryId']], 'url' => BlogHelper::url('site/category', ['id' => Yii::$app->view->params['categoryId']])],
        ['label' => Yii::$app->view->params['product']['title']],
    ],
]);
?>

<div class="row">
    <div class="col-sm-12 pb20">
        <h1 class="mt0"><?= HtmlPurifier::process(Yii::$app->view->params['product']['title']) ?></h1>
    </div>
</div>

<div class="row">

    <div class="col-sm-5 pb20">
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <?php if (count(Yii::$app->view->params['images']) > 0): ?>
                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                    <?php
                    $i = 0;
                    foreach (Yii::$app->view->params['images'] as $imageKey => $image):
                        echo '<div class="' . ($i == 0 ? 'item active' : 'item') . '"> <img src="' . BlogHelper::getImage('product', '400', $image['name']) . '" alt="' . HtmlPurifier::process(Yii::$app->view->params['product']['title']) . '"> </div>';
                        $i++;
                    endforeach;
                    ?>
                </div>
                <?php if (count(Yii::$app->view->params['images']) > 1): ?>
                    <!-- Left and right controls -->
                    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#myCarousel" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                        <span class="sr-only">Next</span>
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-sm-7 pb20">
        <div class="container-fluid">
            <div class="row equal">
                <?php foreach (Yii::$app->view->params['packages'] as $package): ?>
                    <a class="thumbnail col-xs-12 col-sm-12 col-md-6" href="<?= BlogHelper::url('site/basket-add', ['id' => $package['id']]) ?>">
                        <div class="caption">
                            <div> <?= HtmlPurifier::process($package['guaranty']) ?> </div>
                            <div> <small style="text-align: justify;"> <?= HtmlPurifier::process($package['des']) ?> </small> </div>
                            <div>
                                <?php if ($package['color']) : ?>
                                    <span style="float: right;">
                                        <span class="label label-default" style="background-color: #<?= $package['color'] ?>">⠀⠀</span> <?= BlogHelper::getConstant('color', $package['color']) ?>
                                    </span>
                                <?php endif; ?>
                                <span style="float: left;">
                                    <?= HtmlPurifier::process(Yii::$app->formatter->asPrice($package['price'])) ?>
                                </span>
                                <span class="clearfix"> </span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</div>

<div class="row pb20">
    <div class="col-sm-12">
        <table class="table table-striped table-bordered">
            <tbody>
                <?php foreach (Yii::$app->view->params['productFields'] as $productField): ?>
                    <tr>
                        <td>
                            <strong> <?= HtmlPurifier::process($productField['title']) ?> </strong>
                        </td>
                        <td> 
                            <?php
                            if ($productField['type'] == FieldList::TYPE_BOOLEAN):
                                foreach ($productField['values'] as $value) :
                                    if ($value):
                                        echo($productField['label_yes'] ? HtmlPurifier::process($productField['label_yes']) : '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>');
                                    else:
                                        echo($productField['label_no'] ? HtmlPurifier::process($productField['label_no']) : '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>');
                                    endif;
                                endforeach;
                            else:
                                echo HtmlPurifier::process(implode(' ,', $productField['values']));
                            endif;
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>