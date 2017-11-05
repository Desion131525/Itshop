<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\BrandForm */
/* @var $form ActiveForm */
?>
<div class="add">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput() ?>
        <?= $form->field($model, 'intro')->textarea() ?>
        <?= $form->field($model, 'imgFile')->fileInput() ?>
        <?= $form->field($model, 'status')->radioList(['1'=>'显示','0'=>'隐藏']) ?>
    
        <div class="form-group">
            <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- add -->
