<?php
header("Content-Type: text/html;charset=utf-8");
$form = \yii\bootstrap\ActiveForm::begin();
//echo $form->field($model,'goods_id')->textInput();
//------------------------------------------------------------------
//>>1.引入cs 和 js  文件
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);
//>>2.注册Js代码
//注册js代码
$url = \yii\helpers\Url::to("add_img?goods_id={$model->goods_id}");
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
    
    $("#img").attr('src',response.url);
       //将图片地址写入logo
    $("#goodsform-logo").val();
    if(response.url!=null)
    {
     location.reload();
    
    }
       //console.debug(file) ;
       //console.debug(response) ;

});
JS

)

?>
<div id="uploader-demo">
    <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <div id="filePicker">选择图片</div>
</div>
<!--<div><img id="img"  /></div>-->

<?php
//------------------------------------------------------------------
\yii\bootstrap\ActiveForm::end();
echo '<br/>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>

</head>
<body>
<table>
    <?php foreach ($rows as $v):?>
       <tr>
           <td>
           <img src="<?=Yii::getAlias('@web').$v->path?>" class="del"/>
           </td>
           <td>
               <input type="hidden" class="goods_id"  value="<?=$v->id?>"/>
           </td>
     </tr>
    <?php endforeach;?>
</table>
<br/>
<a href="index" class="btn btn-success">返回</a>
<?php
    //视图注册jd代码
    $url = \yii\helpers\Url::to(['del_img']);
    $this->registerJs(
    <<<JS
    
            $('.del').dblclick(function () {
    if(confirm('确定删除?'))
    {   
        var url = '{$url}';
     var id = $(this).closest('tr').find('td input').val();     
        var that = this;
    $.post(url,{id:id},function(data) {
        if(data=='1')
        {
          $(that).remove();
     
        }
    })
    }
    
    })

JS

)
?>
</body>
</html>


