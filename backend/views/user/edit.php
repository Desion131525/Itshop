<?php
header("Content-Type: text/html;charset=utf-8");
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($userForm,'username')->textInput();
echo $form->field($userForm,'password_hash')->textInput();
echo $form->field($userForm,'auth_key')->textInput();
echo $form->field($userForm,'email')->textInput();
echo $form->field($userForm,'status')->textInput();
echo $form->field($userForm,'created_at')->textInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();