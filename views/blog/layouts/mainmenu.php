<?php

use app\components\BlogHelper;
use app\models\FieldList;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\web\View;

$this->registerJs('
    $("body").on("click", ".btn-delete", function(){
        $(this).closest(".filter").find("input[type=text]").val("");
    });
', View::POS_END);
$constant = BlogHelper::getConstant();
?>

<?php if ($this->context->id == 'site' && in_array($this->context->action->id, ['index']) && Yii::$app->blog->attribute('logo')): ?>
    <div class="row pb20">
        <div class="col-sm-12">
            <?php
            $blogLogo = BlogHelper::getImage('logo', '400__67', Yii::$app->blog->attribute('logo'));
            $logo = Html::img($blogLogo, ['style' => 'margin: auto;', 'class' => 'img-responsive img-rounded', 'alt' => HtmlPurifier::process(Yii::$app->blog->attribute('title'))]);
            echo Html::a($logo, BlogHelper::blogFirstPageUrl(), ['style' => 'text-align: center;']);
            ?>
        </div>
    </div>
<?php endif; ?>

<?php if ($this->context->id == 'site' && $this->context->action->id == 'category'): ?>

    <div class="row pb20">
        <div class="col-sm-12">
            <h4>جستجو</h4>
        </div>
    </div>

    <?php
    echo Html::beginForm(BlogHelper::url('site/category', ['id' => Yii::$app->view->params['categoryId']]), 'GET');
    ?>

    <div class="row">

        <?php
        $i = 0;
        foreach (Yii::$app->view->params['search'] as $fieldId => $filters) :

            $field = Yii::$app->view->params['fields'][$fieldId];
            $type = $field['type'];
            $typeFilter = $field['filter'];
            $typeOperations = (isset($constant['opertaion'][$typeFilter]) ? $constant['opertaion'][$typeFilter] : []);

            if (in_array($typeFilter, [FieldList::TYPE_STRING, FieldList::TYPE_NUMBER])):
                $filters[] = ['operation' => null, 'value' => null];
            elseif (count($filters) == 0):
                $filters[] = ['operation' => null, 'value' => null];
            endif;

            foreach ($filters as $filter) :
                $namePrefix = 'Search[' . $fieldId . '][' . $i . ']';
                ?>

                <?php if (in_array($typeFilter, [FieldList::FILTER_STRING, FieldList::FILTER_NUMBER])) : $filter['value'] = (is_array($filter['value']) ? implode(' ', $filter['value']) : $filter['value']) ?>
                    <div class="col-sm-12 pb20 filter">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <?= Html::tag('label', $field['title'], ['class' => 'control-label', 'style' => 'margin-top: 3px;margin-bottom: 2px;']) ?>
                            </span>
                            <?= Html::dropDownList($namePrefix . '[operation]', $filter['operation'], $typeOperations, ['class' => 'form-control', 'style' => 'width: 40%; padding: 4px;']); ?>
                            <?= Html::textInput($namePrefix . '[value]', $filter['value'], ['class' => 'form-control', 'style' => 'width: 60%; padding: 4px;']); ?>
                            <?= (empty($field['unit']) ? '' : Html::tag('span', $field['unit'], ['class' => 'input-group-addon'])); ?>
                            <?php if ($filter['value'] !== null) : ?>
                                <span class="input-group-btn">
                                    <button class="btn btn-danger btn-delete" type="button" style="height: 34px;padding-top: 9px;">
                                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                    </button>
                                </span>
                            <?php endif ?>
                        </div>
                    </div>
                <?php elseif ($typeFilter == FieldList::FILTER_2STATE) : ?>

                    <div class="col-sm-12 pb20">
                        <?= Html::hiddenInput($namePrefix . '[operation]', '='); ?>
                        <?= Html::checkbox($namePrefix . '[value]', strval($filter['value']), [], ['separator' => ' ']); ?>
                        <?= Html::tag('label', $field['title'], ['class' => 'control-label', 'style' => 'margin-top: 3px;margin-bottom: 2px;']) ?>
                    </div>

                <?php elseif ($typeFilter == FieldList::FILTER_3STATE) : ?>

                    <?php
                    $values = [
                        0 => (empty($field['label_no']) ? Yii::$app->formatter->booleanFormat[0] : $field['label_no']),
                        1 => (empty($field['label_yes']) ? Yii::$app->formatter->booleanFormat[1] : $field['label_yes']),
                    ];
                    ?>

                    <div class="col-sm-12 pb20">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <?= Html::tag('label', $field['title'], ['class' => 'control-label', 'style' => 'margin-top: 3px;margin-bottom: 2px;']) ?>
                            </span>
                            <?= Html::hiddenInput($namePrefix . '[operation]', '='); ?>
                            <?= Html::dropDownList($namePrefix . '[value]', $filter['value'], $values, ['class' => 'form-control', 'style' => 'width: 100%; padding: 4px;', 'prompt' => '']); ?>
                            <?= (empty($field['unit']) ? '' : Html::tag('span', $field['unit'], ['class' => 'input-group-addon'])) ?>
                            <?php if ($filter['value'] !== null && false) : ?> 
                                <span class="input-group-btn">
                                    <button class="btn btn-danger" type="button" style="height: 34px;padding-top: 9px;">
                                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                    </button>
                                </span>
                            <?php endif ?> 
                        </div>
                    </div>

                <?php elseif ($typeFilter == FieldList::FILTER_MULTI) : $values = explode(',', $field['options']); ?>
                    <div class="col-sm-12 pb20">
                        <?= Html::tag('label', $field['title'], ['class' => 'control-label', 'style' => 'margin-top: 3px;margin-bottom: 2px;']) ?>
                        <?= Html::hiddenInput($namePrefix . '[operation]', 'IN'); ?>
                        <?= Html::checkboxList($namePrefix . '[value]', $filter['value'], array_combine($values, $values), ['separator' => ' ']); ?>
                    </div>
                <?php elseif ($typeFilter == FieldList::FILTER_SINGLE) : $values = explode(',', $field['options']); ?>
                    <div class="col-sm-12 pb20 radiolistbox">
                        <?= Html::tag('label', $field['title'], ['class' => 'control-label']) ?>
                        <a onclick="$(this).closest('.radiolistbox').find('input[type=radio]').prop('checked', false)" href="javascript:void(0);"><small class="control-label">(هیچکدام)</small></a>
                        <?= Html::hiddenInput($namePrefix . '[operation]', 'IN'); ?>
                        <?= Html::radioList($namePrefix . '[value]', $filter['value'], array_combine($values, $values), ['itemOptions' => ['labelOptions' => ['style' => 'margin: 0 0 0 10px;']]]); ?>
                    </div>
                <?php endif; ?>

                <?php
                $i++;
            endforeach;
        endforeach;
        ?>

    </div>

    <?php
    echo '<div class="row pb20"><div class="col-sm-6">' . Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-default btn-block']) . '</div></div>';
    echo Html::endForm();
    ?>

<?php endif; ?>

<?php if ($this->context->id == 'site' && in_array($this->context->action->id, ['category']) == false): ?>
    <div class="row pb20">
        <div class="col-xs-12">
            <div class="btn-group-vertical" role="group" style="width: 100%;">
                <?php
                foreach (Yii::$app->view->params['_categories'] as $id => $title) {
                    $url = BlogHelper::url('site/category', ['id' => $id]);
                    echo '<a class="btn btn-default" href="' . HtmlPurifier::process($url) . '"><h4 class="h4mainmenu">' . HtmlPurifier::process($title) . '</h4></a>';
                }
                ?>
            </div>
        </div>
    </div>
<?php endif ?>