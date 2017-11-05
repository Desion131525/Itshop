<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\GoodsForm */
/* @var $form ActiveForm */
?>
<div class="add">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput() ?>
        <?= $form->field($model, 'goods_category_id')->dropDownList(['1'=>1,2=>2]) ?>
        <?= $form->field($model, 'brand_id')->dropDownList(['1'=>1,2=>2]) ?>
        <?= $form->field($model, 'stock')->textInput() ?>
        <?= $form->field($model, 'status')->radioList(['1'=>'正常','0'=>'隐藏']) ?>
        <?= $form->field($model, 'is_on_sale')->radioList(['1'=>'是','0'=>'否']) ?>
        <?= $form->field($model, 'market_price')->textInput() ?>
        <?= $form->field($model, 'shop_price')->textInput() ?>
        <?= $form->field($model, 'sn')->textInput() ?>
        <?= $form->field($model, 'imgFile')->fileInput() ?>

        <div class="form-group">
            <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- add -->
