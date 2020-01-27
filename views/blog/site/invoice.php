<?php

use app\components\BlogHelper;
use app\components\Helper;
use app\models\Invoice;
use yii\data\ArrayDataProvider;

$this->title = Yii::t('app', 'Invoice');

$dataProvider = new ArrayDataProvider([
    'allModels' => Yii::$app->view->params['invoices'],
    'modelClass' => 'app\models\Model',
]);
?>

<div class="row">
    <div class="col-sm-12 pb20">
        <h1 class="mt0"><?= $this->title ?></h1>
    </div>
</div>

<?php if (empty(Yii::$app->view->params['invoices'])): ?>
    <div class="row">
        <div class="col-sm-12 pb20">
            <?= Yii::t('yii', 'No results found.'); ?>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-sm-12 pb20">

            <div class="table-responsive">
                <?=
                TableView::widget([
                    'models' => $models,
                    'sort' => false,
                    'filterModel' => null,
                    'footerModel' => null,
                    'rowOptions' => function ($model, $key, $index, $grid) {
                        return [
                            'action' => null,
                            'method' => 'get',
                        ];
                    },
                    'columns' => [
                        'id',
                        'updated_at:datetimefa',
                        'name',
                        [
                            'attribute' => 'price',
                            'value' => function ($model, $key, $index, $grid) {
                                return Helper::formatPrice(intval($model->price));
                            },
                        ],
                        [
                            'attribute' => 'status',
                            'value' => function ($model, $key, $index, $grid) {
                                return Invoice::statuseLabel($model->status);
                            },
                        ],
                        [
                            'label' => '',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $grid, $form) {
                                return '<a class="btn btn-primary btn-block btn-social" style="height: 34px;" href="' . BlogHelper::url('site/invoice-view', ['id' => $model->id]) . '" >' . Yii::t('app', 'View') . '</a>';
                            },
                        ],
                        [
                            'label' => '',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $grid, $form) {
                                if (in_array($model->status, [Invoice::STATUS_VERIFIED, Invoice::STATUS_UNVERIFIED])) {
                                    return '<a class="btn btn-danger btn-block btn-social" style="height: 34px;" href="' . BlogHelper::url('site/invoice-remove', ['id' => $model->id]) . '" data-confirm="' . Yii::t('yii', 'Are you sure you want to delete this item?') . '">' . Yii::t('app', 'Remove') . '</a>';
                                }
                                return '';
                            },
                        ],
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
<?php endif; ?>
