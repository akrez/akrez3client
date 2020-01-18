<?php

use blog\components\Api;
use common\components\Helper;
use common\components\TableView;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="table-responsive">
    <?=
    TableView::widget([
        'models' => $models,
        'sort' => false,
        'filterModel' => null,
        'footerModel' => null,
        'rowOptions' => function ($model, $key, $index, $grid) {
            return [
                'action' => Api::url('site', 'basket-add', ['id' => $model->id]),
                'method' => 'get',
            ];
        },
        'filterRowOptions' => [
            'action' => Url::current(),
            'method' => 'get',
        ],
        'footerRowOptions' => [
            'action' => Url::current(['id' => null]),
        ],
        'columns' => [
            [
                'attribute' => 'image',
                'value' => function ($model, $key, $index, $grid, $form) {
                    return ($model->image ? Html::img(Api::galleryUrl($model->image, 'product', '_33_66')) : '');
                },
                'format' => 'raw',
                'enableSorting' => false,
            ],
            'title',
            [
                'attribute' => Yii::t('app', 'color'),
                'value' => function ($model, $key, $index, $grid, $form) {
                    if ($model->color) {
                        return '<span class="label label-default" style="background-color: #' . $model->color . '">⠀⠀</span>';
                    }
                    return '';
                },
                'format' => 'raw',
            ],
            'guaranty',
            'des',
            [
                'attribute' => Yii::t('app', 'price'),
                'value' => function ($model, $key, $index, $grid, $form) {
                    return Helper::formatPrice($model->price);
                },
            ],
            [
                'attribute' => Yii::t('app', 'cnt'),
                'visible' => !$editable,
            ],
            [
                'attribute' => Yii::t('app', 'cnt'),
                'value' => function ($model, $key, $index, $grid, $form) {
                    $btnUpdate = '<button type="submit" class="btn btn-primary btn-social" style="height: 34px;">' . Yii::t('app', 'Update') . '</button>';
                    return $form->field($model, 'cnt', ['template' => '<div class="input-group"> {input} <span class="input-group-btn">' . $btnUpdate . ' </span> </div> {error}'])->textInput(['name' => 'cnt', 'maxlength' => true]);
                },
                'format' => 'raw',
                'visible' => $editable,
            ],
            [
                'label' => '',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $grid, $form) {
                    return '<a class="btn btn-danger btn-block btn-social" style="height: 34px;" href="' . Api::url('site', 'basket-remove', ['id' => $model->id]) . '" data-confirm="' . Yii::t('yii', 'Are you sure you want to delete this item?') . '">' . Yii::t('app', 'Remove') . '</a> <div class="help-block"></div>';
                },
                'visible' => $editable,
            ],
        ],
    ]);
    ?>
</div>
