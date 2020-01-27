<?php

use app\components\BlogHelper;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

$models = [];
foreach (Yii::$app->view->params['baskets'] as $basket) {
    $package = Yii::$app->view->params['packages'][$basket['package_id']];
    $product = Yii::$app->view->params['products'][$package['product_id']];
    //
    $model['id'] = $package['id'];
    $model['image'] = $product['image'];
    $model['title'] = $product['title'];
    $model['cnt'] = $basket['cnt'];
    $model['price'] = $package['price'];
    $model['color'] = $package['color'];
    $model['guaranty'] = $package['guaranty'];
    $model['des'] = $package['des'];
    $model['package_id'] = $package['id'];
    $models[] = $model;
}

$dataProvider = new ArrayDataProvider([
    'allModels' => $models,
    'modelClass' => 'app\models\Model',
    'sort' => false,
    'pagination' => false,
        ]);
?>

<div class="table-responsive">
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'image',
                'value' => function ($model, $key, $index, $grid) {
                    return ($model['image'] ? Html::img(BlogHelper::getImage('product', '_33_66', $model['image'])) : '');
                },
                'format' => 'raw',
            ],
            'title',
            [
                'attribute' => 'color',
                'value' => function ($model, $key, $index, $grid) {
                    if ($model['color']) {
                        return ' <span style="background-color: #' . $model['color'] . ';">⠀⠀</span> ' . BlogHelper::getConstant('color', $model['color']);
                    }
                    return '';
                },
                'format' => 'raw',
            ],
            'guaranty',
            'des',
            'price:price',
            [
                'attribute' => Yii::t('app', 'cnt'),
                'value' => function ($model, $key, $index, $grid) {
                    $r = [];
                    $r[] = Html::beginForm(
                                    BlogHelper::url('site/basket-add', ['id' => $model['package_id']]),
                                    'GET'
                    );
                    $btnUpdate = '<button type="submit" class="btn btn-primary btn-social" style="height: 34px;">' . Yii::t('app', 'Update') . '</button>';
                    $r[] = '<div class="input-group"> ' . Html::input('number', 'cnt', $model['cnt'], ['class' => 'form-control', 'min' => '1',]) . ' <span class="input-group-btn">' . $btnUpdate . ' </span> </div> ';
                    $r[] = Html::endForm();
                    return implode(' ', $r);
                },
                'format' => 'raw',
            ],
            [
                'label' => '',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $grid) {
                    return '<a class="btn btn-danger btn-block btn-social" href="' . BlogHelper::url('site/basket-remove', ['id' => $model['id']]) . '" data-confirm="' . Yii::t('yii', 'Are you sure you want to delete this item?') . '">' . Yii::t('app', 'Remove') . '</a> <div class="help-block"></div>';
                },
            ],
        ],
    ]);
    ?>
</div>
