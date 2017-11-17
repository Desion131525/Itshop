<?php
header("Content-Type: text/html;charset=utf-8");
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($loginForm,'username')->textInput();
echo $form->field($loginForm,'password')->passwordInput();
echo $form->field($loginForm,'cookie')->checkbox(['cookie'=>'rr'])->label('记住我');
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();