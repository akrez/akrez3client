<?php

use app\components\BlogHelper;
use app\components\Helper;
use app\models\FieldList;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\jui\SliderInput;
use yii\web\JsExpression;
use yii\web\View;

$this->registerJs('
    $("body").on("click", ".btn-delete", function(){
        $(this).closest(".filter").find("input[type=text]").val("");
    });
    
    function sliderCheckboxOnChange(event) {
        var checkbox = $(event.target);
        var idPrefix = checkbox.attr("data-idprefix");
        if(checkbox.is(":checked")){
            $(checkbox).parents(".panel").find(".panel-body").slideDown();
            $(checkbox).removeClass("panel-collapsed");
            $("#"+idPrefix+"-input-min").prop("disabled", false);
            $("#"+idPrefix+"-input-max").prop("disabled", false);
        } else {
            $(checkbox).parents(".panel").find(".panel-body").slideUp();
            $(checkbox).addClass("panel-collapsed");
            $("#"+idPrefix+"-input-min").prop("disabled", true);
            $("#"+idPrefix+"-input-max").prop("disabled", true);
        }
    }
 
', View::POS_END);
$this->registerCss('
	width: calc(100% - 20px) !important;
');
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

    <?= Html::beginForm(BlogHelper::url('site/category', ['id' => Yii::$app->view->params['categoryId']]), 'GET'); ?>



    <div class="row">

        <?php
        $this->registerJs('
            $(document).on("click", ".panel .clickable", function (e) {
                var that = $(this);
                if (that.hasClass("panel-collapsed")) {
                    that.parents(".panel").find(".panel-body").slideDown();
                    that.removeClass("panel-collapsed");
                } else {
                    that.parents(".panel").find(".panel-body").slideUp();
                    that.addClass("panel-collapsed");
                }
            });
        ', View::POS_READY);
        $i = 0;
        foreach (Yii::$app->view->params['search'] as $fieldId => $fieldFilters) :
            $field = Yii::$app->view->params['fields'][$fieldId];
            $type = $field['type'];
            $widgets = (array) $field['widgets'];
            foreach ($widgets as $widget) :
                $filter = ['operation' => null, 'value' => null];
                foreach ((array) $fieldFilters as $fieldFilterKey => $fieldFilter) :
                    if ($fieldFilter['widget'] == $widget) {
                        $filter = Yii::$app->view->params['search'][$fieldId][$fieldFilterKey];
                        unset(Yii::$app->view->params['search'][$fieldId][$fieldFilterKey]);
                        continue;
                    }
                endforeach;
                $namePrefix = 'Search[' . $fieldId . '][' . $i . ']';
                echo Html::hiddenInput($namePrefix . '[widget]', $widget);
                if ($type == FieldList::TYPE_STRING || $type == FieldList::TYPE_NUMBER):
                    if ($type == FieldList::TYPE_NUMBER) {
                        $specialWidgets = ['>=' => BlogHelper::getConstant('widget', $type, '>='), '<=' => BlogHelper::getConstant('widget', $type, '<='), '=' => BlogHelper::getConstant('widget', $type, '='), '<>' => BlogHelper::getConstant('widget', $type, '<>')];
                    } else {
                        $specialWidgets = ['LIKE' => BlogHelper::getConstant('widget', $type, 'LIKE'), 'NOT LIKE' => BlogHelper::getConstant('widget', $type, 'NOT LIKE'), '=' => BlogHelper::getConstant('widget', $type, '='), '<>' => BlogHelper::getConstant('widget', $type, '<>')];
                    }
                    if (in_array($widget, array_keys($specialWidgets))):
                        ?>
                        <div class="col-sm-12 pb20 filter">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <?= Html::tag('label', $field['title'] . ' ' . BlogHelper::getConstant('widget', $type, $widget), ['class' => 'control-label', 'style' => 'margin-top: 3px;margin-bottom: 2px;']) ?>
                                </span>
                                <?= Html::hiddenInput($namePrefix . '[operation]', $widget); ?>
                                <?= Html::textInput($namePrefix . '[value]', $filter['value'], ['class' => 'form-control',]); ?>
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
                        <?php
                    elseif ($widget == 'COMBO') :
                        ?>
                        <div class="col-sm-12 pb20 filter">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <?= Html::tag('label', $field['title'], ['class' => 'control-label', 'style' => 'margin-top: 3px;margin-bottom: 2px;']) ?>
                                </span>
                                <?= Html::dropDownList($namePrefix . '[operation]', $filter['operation'], $specialWidgets, ['class' => 'form-control', 'style' => 'width: 40%; padding: 4px;']); ?>
                                <?= Html::textInput($namePrefix . '[value]', $filter['value'], ['class' => 'form-control', 'style' => 'width: 60%; padding: 4px;']); ?>
                                <?php if ($filter['value'] !== null) : ?>
                                    <span class="input-group-btn">
                                        <button class="btn btn-danger btn-delete" type="button" style="height: 34px;padding-top: 9px;">
                                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                        </button>
                                    </span>
                                <?php endif ?>
                            </div>
                        </div>
                        <?php
                    elseif ($widget == 'SINGLE') :
                        $items = Helper::normalizeArray($field['options'], true);
                        ?>
                        <div class="col-sm-12 pb20 filter">
                            <div class="panel panel-default">
                                <div class="panel-heading clickable" style="padding-top: 5px;padding-bottom: 0px;padding-right: 12px;padding-left: 12px;background: #eeeeee; cursor: pointer;">
                                    <?= Html::tag('label', $field['title'] . '<small>' . $field['unit'] . '</small>', ['class' => 'control-label', 'style' => 'color: #555555;']) ?>
                                </div>
                                <div class="panel-body" style="padding-right: 12px;padding-left: 12px;padding-top: 6px;padding-bottom: 6px;">
                                    <?= Html::hiddenInput($namePrefix . '[operation]', '='); ?>
                                    <?= Html::radioList($namePrefix . '[value]', $filter['value'], array_combine($items, $items), ['separator' => "<br />", 'encode' => false]) ?>
                                    <a onclick="$(this).closest('.filter').find('input[type=radio]').prop('checked', false)" href="javascript:void(0);" style="padding-right: 15px;"><small class="control-label">(هیچکدام)</small></a>
                                </div>
                            </div>
                        </div>
                        <?php
                    elseif ($widget == 'MULTI') :
                        $items = Helper::normalizeArray($field['options'], true);
                        ?>
                        <div class="col-sm-12 filter">
                            <div class="panel panel-default">
                                <div class="panel-heading clickable" style="padding-top: 5px;padding-bottom: 0px;padding-right: 12px;padding-left: 12px;background: #eeeeee; cursor: pointer;">
                                    <?= Html::tag('label', $field['title'] , ['class' => 'control-label', 'style' => 'color: #555555;']) . Html::tag('small', $field['unit']) ?>
                                </div>
                                <div class="panel-body" style="padding-right: 12px;padding-left: 12px;padding-top: 6px;padding-bottom: 6px;">
                                    <?= Html::hiddenInput($namePrefix . '[operation]', 'IN'); ?>
                                    <?= Html::checkboxList($namePrefix . '[value][]', $filter['value'], array_combine($items, $items), ['separator' => "<br />", 'encode' => false]) ?>
                                    <a onclick="$(this).closest('.filter').find('input[type=checkbox]').prop('checked', false)" href="javascript:void(0);" style="padding-right: 15px;"><small class="control-label">(هیچکدام)</small></a>
                                </div>
                            </div>
                        </div>
                        <?php
                    elseif ($widget == 'BETWEEN'):
                        $idPrefix = 'Search-' . $fieldId . '-' . $i;
                        //
                        $min = 0;
                        $max = 100;
                        if ($fieldId == 'price') {
                            if (Yii::$app->view->params['category']['price_min']) {
                                $min = floatval(Yii::$app->view->params['category']['price_min']);
                            }
                            if (Yii::$app->view->params['category']['price_max']) {
                                $max = floatval(Yii::$app->view->params['category']['price_max']);
                            }
                        } elseif (isset($field['options'])) {
                            $items = Helper::normalizeArray($field['options'], true);
                            if (count($items) > 0) {
                                $min = reset($items);
                                $max = end($items);
                            }
                        }
                        $min = floatval($min);
                        $max = floatval($max == $min ? $max + 1 : $max);
                        //
                        if (!is_array($filter['value'])) {
                            $filter['value'] = Helper::normalizeArray($filter['value'], true);
                        }
                        $disabled = true;
                        if (count($filter['value']) == 2) {
                            $disabled = false;
                        } else {
                            $filter['value'] = [
                                0 => $min,
                                1 => $max,
                            ];
                        }
                        //
                        echo Html::hiddenInput($namePrefix . '[operation]', $widget);
                        ?>
                        <div class="col-sm-12 filter">
                            <div class="panel panel-default">
                                <div class="panel-heading <?= $disabled ? 'panel-collapsed' : '' ?>" style="padding-top: 4px;padding-bottom: 0px;padding-right: 12px;padding-left: 12px;background: #eeeeee;">
                                    <?= Html::checkbox($namePrefix . '[value]', !$disabled, ['id' => $idPrefix . '-checkbox', 'style' => 'padding-top: 6px;padding-bottom: 6px;margin-left: 12px;border-left: black solid 1px;', 'data-idPrefix' => $idPrefix, 'onchange' => 'sliderCheckboxOnChange(event);',]); ?> 
                                    <?= Html::tag('label', $field['title'], ['for' => $idPrefix . '-checkbox', 'class' => 'control-label', 'style' => 'margin-top: 0px;margin-bottom: 4px; user-select: none; margin-right: 12px;color: #555;width: calc(100% - 40px);']) ?>
                                </div>
                                <div class="panel-body" style="<?= $disabled ? 'display: none;' : '' ?>padding-right: 12px;padding-left: 12px;padding-top: 6px;padding-bottom: 6px;">
                                    <?php
                                    echo "<div><span style='float: left;' id='$idPrefix-label-min'>" . number_format($filter['value'][0]) . "</span><span style='float: right;' id='$idPrefix-label-max'>" . number_format($filter['value'][1]) . "</span><div class='clearfix'></div></div>";
                                    echo SliderInput::widget([
                                        'options' => [
                                            'id' => $idPrefix . '-slider',
                                        ],
                                        'clientOptions' => [
                                            //'disabled' => $disabled,
                                            'min' => $min,
                                            'max' => $max,
                                            'range' => true,
                                            'values' => $filter['value'],
                                            'slide' => new JsExpression("function( event, ui ) { $('#{$idPrefix}-label-min').text(ui.values[0].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')); $('#{$idPrefix}-label-max').text(ui.values[1].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));   $('#{$idPrefix}-input-min').val(ui.values[0]); $('#{$idPrefix}-input-max').val(ui.values[1]); }"),
                                        ],
                                    ]);
                                    echo Html::hiddenInput($namePrefix . '[value][0]', $filter['value'][0], ['id' => $idPrefix . '-input-min'] + ($disabled ? ['disabled' => 'disabled'] : []));
                                    echo Html::hiddenInput($namePrefix . '[value][1]', $filter['value'][1], ['id' => $idPrefix . '-input-max'] + ($disabled ? ['disabled' => 'disabled'] : []));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    endif;
                elseif ($type == FieldList::TYPE_BOOLEAN) :
                    if ($widget == '2STATE') :
                        $idPrefix = 'Search-' . $fieldId . '-' . $i;
                        ?>
                        <div class="col-sm-12 pb20 filter">
                            <div class="input-group"> 
                                <span class="input-group-addon">
                                    <?= Html::checkbox($namePrefix . '[value]', $filter['value'], ['id' => $idPrefix]); ?> 
                                </span>
                                <?= Html::hiddenInput($namePrefix . '[operation]', '='); ?>
                                <span class="form-control" style="background-color: #eeeeee;padding-right: 12px;padding-left: 12px;">
                                    <?= Html::tag('label', $field['title'], ['for' => $idPrefix, 'class' => 'control-label', 'style' => 'margin-top: 3px;margin-bottom: 2px;width: 100%; user-select: none; ']) ?>
                                </span>
                            </div>
                        </div>
                    <?php elseif ($widget == '3STATE') : ?>
                        <?php
                        $items = [
                            0 => (empty($field['label_no']) ? Yii::$app->formatter->booleanFormat[0] : $field['label_no']),
                            1 => (empty($field['label_yes']) ? Yii::$app->formatter->booleanFormat[1] : $field['label_yes']),
                        ];
                        ?>
                        <div class="col-sm-12 pb20 filter">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <?= Html::tag('label', $field['title'], ['class' => 'control-label', 'style' => 'margin-top: 3px;margin-bottom: 2px;']) ?>
                                </span>
                                <?= Html::hiddenInput($namePrefix . '[operation]', '='); ?>
                                <?= Html::dropDownList($namePrefix . '[value]', $filter['value'], $items, ['class' => 'form-control', 'style' => 'width: 100%; padding: 4px;', 'prompt' => '']); ?>
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
                        <?php
                    endif;
                endif;

                $i++;
            endforeach;
        endforeach;
        ?>

    </div>

    <div class="row pb20">
        <div class="col-sm-6">
            <button type="submit" class="btn btn-default btn-block"><?= Yii::t('app', 'Search') ?></button>
        </div>
    </div>

    <?= Html::endForm(); ?>

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
    <?php
 endif ?>