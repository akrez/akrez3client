<?php

use blog\components\Api;
use common\components\Helper;
use common\models\FieldList;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use yii\widgets\LinkPager;

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

$dp = Api::getDp();

$pagination = new Pagination([
    'pageSizeParam' => 'page_size',
    'pageSize' => $dp['pagination']['page_size'],
    'page' => $dp['pagination']['page'],
    'totalCount' => $dp['pagination']['total_count'],
        ]);
?>

<?php if (count($dp['products']) > 0): ?>

    <div class="row">
        <div class="col-sm-12">
            <ul class="pagination">
                <?php foreach ($dp['sort']['attributes'] as $sortAttributeId => $sortAttributeValue) : ?>
                    <?php if ($dp['sort']['attribute'] == $sortAttributeId): ?>
                        <li class="active"><a href="#"><?= HtmlPurifier::process($sortAttributeValue) ?></a></li>
                    <?php else: ?>
                        <li class=""><a href="<?= Url::current(['sort' => $sortAttributeId]) ?>"><?= HtmlPurifier::process($sortAttributeValue) ?></a></li>
                    <?php endif ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row equal">
            <?php foreach ($dp['products'] as $product) : ?>
                <a class="thumbnail col-xs-12 col-sm-6 col-md-4 col-lg-3" href="<?= Api::url('site', 'product', ['id' => $product['id']]) ?>"> 

                    <?php
                    if ($product['image']):
                        echo Html::img(Api::galleryUrl($product['image'], 'product', '400'), ['class' => 'img img-responsive', 'style' => 'margin-left: auto; margin-right: auto; padding: 9px 9px 0;']);
                    endif;
                    ?>

                    <div class="caption">

                        <?php
                        echo Html::tag('h5', $product['title']);

                        if (isset($dp['productsFields'][$product['id']])) :
                            foreach ($dp['productsFields'][$product['id']] as $field) :
                                if ($field['in_summary']) :
                                    echo ' <strong> ' . $field['title'] . ' </strong> ';
                                    if ($field['type'] == FieldList::TYPE_BOOLEAN):
                                        foreach ($field['values'] as $value) :
                                            if ($value):
                                                echo($field['label_yes'] ? $field['label_yes'] : '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>');
                                            else:
                                                echo($field['label_no'] ? $field['label_no'] : '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>');
                                            endif;
                                        endforeach;
                                    else:
                                        echo HtmlPurifier::process(implode(' ,', $field['values']));
                                    endif;
                                    echo '<br>';
                                endif;
                            endforeach;
                        endif;

                        if (empty($product['price_min']) && empty($product['price_max'])):
                        else:
                            echo '<p style="text-align: left;">';
                            if (!empty($product['price_min']) && !empty($product['price_max'])):
                                if ($product['price_min'] == $product['price_max']):
                                    echo Helper::formatPrice($product['price_min']) . '</p>';
                                else:
                                    echo ' از ' . Helper::formatPrice($product['price_min']) . '<br>' . ' تا ' . Helper::formatPrice($product['price_max']);
                                endif;
                            else:
                                if (!empty($product['price_min'])) :
                                    echo ' از ' . Helper::formatPrice($product['price_min']);
                                endif;
                                if (!empty($product['price_max'])) :
                                    echo ' تا ' . Helper::formatPrice($product['price_max']);
                                endif;
                            endif;
                            echo '</p>';
                        endif;
                        ?>
                    </div>
                </a>
            <?php endforeach ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <?= LinkPager::widget(['pagination' => $pagination, 'hideOnSinglePage' => false]); ?>
        </div>
    </div>

<?php else: ?>

    <div class="row">
        <div class="col-xs-12">
            <div class="alert alert-warning" role="alert">
                <?= Yii::t('yii', 'No results found.'); ?>
            </div>
        </div>
    </div>

<?php endif; ?>