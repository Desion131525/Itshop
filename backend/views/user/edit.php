<?php
header("Content-Type: text/html;charset=utf-8");
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($rows,'username')->textInput();
echo $form->field($rows,'email')->textInput();
//echo "<p><a href='add_pwd'>点击修改密码</a><p/>";
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();

//var_dump(\Yii::$app->user->identity);die;