<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<div class="site-index">
    <div class="row">
        <div class="col-sm-3 col-xs-12">
            <h3 style="margin-bottom: 20px;"><?= Yii::t('app', 'Reset Password Request') ?></h3>
            <?php
            $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'fieldConfig' => [
                            'template' => '<div class="input-group">{label}{input}</div>{error}',
                            'labelOptions' => [
                                'class' => 'input-group-addon',
                            ],
                        ]
            ]);
            ?>
            <?= $form->field($model, 'email')->textInput() ?>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block" name="login-button" style="float: right;"> <?= Yii::t('app', 'Reset Password') ?> </button>
            </div>
            <?php ActiveForm::end(); ?>

        </div>
        <div class="col-sm-8 col-xs-12">
        </div>
    </div>
</div>
