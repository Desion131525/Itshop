<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Article_categoryForm */
/* @var $form ActiveForm */
?>
<div class="add">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput() ?>
    <?= $form->field($model, 'intro')->textarea() ?>
    <?= $form->field($model, 'status')->radioList(['1'=>'显示','0'=>'隐藏']) ?>
    <?= $form->field($model, 'article_category_id')->dropDownList(['1'=>'军事','2'=>'娱乐']) ?>
    <?= $form->field($article_detailForm, 'content')->textarea() ?>
    <div class="form-group">
            <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- add -->
