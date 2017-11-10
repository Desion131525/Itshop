<?php
header("Content-Type: text/html;charset=utf-8");
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'old_pwd')->textInput();
echo $form->field($model,'new_pwd')->textInput();
echo $form->field($model,'re_pwd')->textInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();
