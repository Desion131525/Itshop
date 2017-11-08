<?php
header("Content-Type: text/html;charset=utf-8");
use \kucha\ueditor\UEditor;
//echo $form->field($model,'colum')->widget('kucha\ueditor\UEditor',[]);
\yii\bootstrap\ActiveForm::begin();
echo \kucha\ueditor\UEditor::widget(['name' => 'content']);
echo \yii\helpers\Html::submitButton('提交', ['class' => 'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();