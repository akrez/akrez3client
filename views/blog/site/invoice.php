<?php

use app\components\BlogHelper;
use yii\data\ArrayDataProvider;
use yii\data\Pagination;
use yii\grid\GridView;
use yii\widgets\LinkPager;

$this->title = Yii::t('app', 'Invoice');

$dataProvider = new ArrayDataProvider([
    'allModels' => Yii::$app->view->params['invoices'],
    'modelClass' => 'app\models\Model',
    'sort' => false,
    'pagination' => false,
        ]);

$pagination = new Pagination([
    'pageSizeParam' => 'page_size',
    'pageSize' => Yii::$app->view->params['pagination']['page_size'],
    'page' => Yii::$app->view->params['pagination']['page'],
    'totalCount' => Yii::$app->view->params['pagination']['total_count'],
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
        <div class="col-sm-12">

            <div class="table-responsive">
                <?=
                GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        'id',
                        'updated_at:datetimefa',
                        'name',
                        'price:price',
                        [
                            'attribute' => 'status',
                            'value' => function ($model, $key, $index, $grid) {
                                return BlogHelper::getConstant('invoiceStatuses', $model['status']);
                            },
                        ],
                        [
                            'label' => '',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $grid) {
                                return '<a class="btn btn-primary btn-block btn-social" href="' . BlogHelper::url('site/invoice-view', ['id' => $model['id']]) . '" >' . Yii::t('app', 'View') . '</a>';
                            },
                        ],
                        [
                            'label' => '',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $grid ) {
                                //Invoice::STATUS_VERIFIED, Invoice::STATUS_UNVERIFIED
                                if (in_array($model['status'], [0, 1])) {
                                    return '<a class="btn btn-danger btn-block btn-social" href="' . BlogHelper::url('site/invoice-remove', ['id' => $model['id']]) . '" data-confirm="' . Yii::t('yii', 'Are you sure you want to delete this item?') . '">' . Yii::t('app', 'Remove') . '</a>';
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
    <div class="row">
        <div class="col-sm-12">
            <?=
            LinkPager::widget([
                'hideOnSinglePage' => false,
                'pagination' => new Pagination([
                    'page' => Yii::$app->view->params['pagination']['page'],
                    'pageSize' => Yii::$app->view->params['pagination']['page_size'],
                    'totalCount' => Yii::$app->view->params['pagination']['total_count'],
                        ]),
            ]);
            ?>
        </div>
    </div>
<?php endif; ?>
