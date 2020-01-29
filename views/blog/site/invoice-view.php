<?php

use app\components\Helper;
use app\models\Invoice;
use yii\helpers\HtmlPurifier;

$this->title = Yii::t('app', 'Invoice View') . ' شماره ' . Yii::$app->view->params['invoice']['id'];

$invoice = new Invoice();
$invoice->setAttributes(Yii::$app->view->params['invoice'], false);
$this->registerCss('
    .white-background { background-color: #f9f9f9; }
    .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {
        border: 1px solid #dddddd;
    }
');
?>

<div class="row">
    <div class="col-sm-12 pb20">
        <h1 class="mt0"><?= $this->title ?></h1>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 pb20">
        <?= $this->render('_basket_table') ?>
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
                    <td class="white-background"><?= $invoice->getAttributeLabel('province') ?></td><td><?= \app\components\BlogHelper::getConstant('province', $invoice->province) ?></td>
                    <td class="white-background"><?= $invoice->getAttributeLabel('address') ?></td><td colspan="5"><?= HtmlPurifier::process($invoice->address) ?></td>
                </tr>
                <tr>
                    <td class="white-background"><?= $invoice->getAttributeLabel('des') ?></td><td colspan="7"><?= HtmlPurifier::process($invoice->des) ?></td>
                </tr>
                <tr>
                    <td class="white-background"><?= $invoice->getAttributeLabel('price') ?></td><td colspan="7"><?= Yii::$app->formatter->asPrice($invoice->price) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
