<?php

use yii\widgets\ActiveForm;




?>

<div class="row">
    <div class="col-sm-5">
        <h1 style="margin-bottom: 20px;"><?= Yii::t('app', 'Reset Password Request') ?></h1>
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
            <button type="submit" class="btn btn-primary btn-block" style="float: right;"> <?= Yii::t('app', 'Reset Password Request') ?> </button>
        </div>
        
        
        
        <?php ActiveForm::end(); ?>
    </div>
</div>
