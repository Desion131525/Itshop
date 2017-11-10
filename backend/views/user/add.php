<?php
header("Content-Type: text/html;charset=utf-8");
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password_hash')->passwordInput();
//$form->field($userForm,'auth_key')->textInput();
echo $form->field($model,'email')->textInput();
echo $form->field($model,'status',['inline'=>1])->radioList(['1'=>'正常','0'=>'异常']);
echo $form->field($role_model,'role',['inline'=>1])->checkboxList($roles);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();