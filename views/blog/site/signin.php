<?php

use yii\widgets\ActiveForm;
use app\components\BlogHelper;



?>

<div class="row">
    <div class="col-sm-5">
        <h1 style="margin-bottom: 20px;"><?= Yii::t('app', 'Signin') ?></h1>
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
        <?= $form->field($model, 'password')->passwordInput() ?>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block" style="float: right;"> <?= Yii::t('app', 'Signin') ?> </button>
        </div>
        <div class="form-group">
            <a type="button" class="btn btn-default" style="margin-top: 20px;float: right;" href="<?= BlogHelper::url('site/reset-password-request') ?>"><?= Yii::t('app', 'Reset Password Request') ?></a>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
