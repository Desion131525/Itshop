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
        <?php // $form->field($model, 'imgFile')->fileInput()
        // 引入cs 和 js  文件
        $this->registerCssFile('@web/webuploader/webuploader.css');
        $this->registerJsFile('@web/webuploader/webuploader.js',[
                'depends'=>\yii\web\JqueryAsset::className()
        ]);
        //注册js代码
        $url = \yii\helpers\Url::to('upload');
        $this->registerJs(
                <<<JS
               // 初始化Web Uploader
var uploader = WebUploader.create({

    // 选完文件后，是否自动上传。
    auto: true,

    // swf文件路径
    swf: '/js/Uploader.swf',

    // 文件接收服务端。
    server: '{$url}',

    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: '#filePicker',

    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,bmp,png',
        mimeTypes: 'image/jpg,image/jpeg,image/png',//弹出选择框慢的问题
        
    }
});
//文件上传成功  回显图片
uploader.on( 'uploadSuccess', function( file ,response) {
    //$( '#'+file.id ).addClass('upload-state-done');
    //console.log(response);
    //console.log(file);
    //response.url  //上传成功的文件路径
    //将图片地址赋值给img
    $("#img").attr('src',response.url);
    //将图片地址写入logo
    $("#brand-logo").val(response.url);
});
JS

        )

        ?>
        <div id="uploader-demo">
            <!--用来存放item-->
            <div id="fileList" class="uploader-list"></div>
            <div id="filePicker">选择图片</div>
        </div>
        <div><img id="img"  /></div>
        <?= $form->field($model, 'status')->radioList(['1'=>'显示','0'=>'隐藏']) ?>
    
        <div class="form-group">
            <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- add -->
