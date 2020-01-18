<?php

use blog\components\Api;
use common\components\Helper;
use common\models\BasketItem;
use common\models\Invoice;
use common\models\Province;
use yii\helpers\HtmlPurifier;

$dp = Api::getDp();

$this->title = Yii::t('app', 'Invoice View') . ' شماره ' . $dp['invoice']['id'];

$this->registerCss('
    .white-background { background-color: #f9f9f9; }
    .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {
        border: 1px solid white;
    }
');

$models = [];
foreach ($dp['baskets'] as $basket) {
    $package = $dp['packages'][$basket['package_id']];
    $product = $dp['products'][$package['product_id']];
    $model = new BasketItem();
    $model->load($package, '');
    $model->image = $product['image'];
    $model->title = $product['title'];
    $model->cnt = $basket['cnt'];
    $models[] = $model;
}

$invoice = new Invoice();
$invoice->setAttributes($dp['invoice'], false);
?>

<div class="row">
    <div class="col-sm-12 pb20">
        <h1 class="mt0"><?= $this->title ?></h1>
    </div>
</div>

<?php if (empty($models)): ?>
    <div class="row">
        <div class="col-sm-12 pb20">
            <?= Yii::t('yii', 'No results found.'); ?>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-sm-12 pb20">
            <?= $this->render('_basket_table', ['models' => $models, 'editable' => false]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 pb20">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td class="white-background"><?= $invoice->getAttributeLabel('id') ?></td><td><?= $invoice->id ?></td>
                        <td class="white-background"><?= $invoice->getAttributeLabel('created_at') ?></td><td><?= Yii::$app->formatter->asDatetimefa($invoice->created_at) ?></td>
                        <td class="white-background"><?= $invoice->getAttributeLabel('updated_at') ?></td><td><?= Yii::$app->formatter->asDatetimefa($invoice->updated_at) ?></td>
                        <td class="white-background"><?= $invoice->getAttributeLabel('status') ?></td><td><?= Invoice::statuseLabel($invoice->status) ?></td>
                    </tr>
                    <tr>
                        <td class="white-background"><?= $invoice->getAttributeLabel('name') ?></td><td><?= HtmlPurifier::process($invoice->name) ?></td>
                        <td class="white-background"><?= $invoice->getAttributeLabel('phone') ?></td><td><?= HtmlPurifier::process($invoice->phone) ?></td>
                        <td class="white-background"><?= $invoice->getAttributeLabel('mobile') ?></td><td><?= HtmlPurifier::process($invoice->mobile) ?></td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td class="white-background"><?= $invoice->getAttributeLabel('province') ?></td><td><?= Province::getLabel($invoice->province) ?></td>
                        <td class="white-background"><?= $invoice->getAttributeLabel('address') ?></td><td colspan="5"><?= HtmlPurifier::process($invoice->address) ?></td>
                    </tr>
                    <tr>
                        <td class="white-background"><?= $invoice->getAttributeLabel('des') ?></td><td colspan="7"><?= HtmlPurifier::process($invoice->des) ?></td>
                    </tr>
                    <tr>
                        <td class="white-background"><?= $invoice->getAttributeLabel('price') ?></td><td colspan="7"><?= Helper::formatPrice($invoice->price) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
