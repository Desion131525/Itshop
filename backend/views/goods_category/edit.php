<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Article_categoryForm */
/* @var $form ActiveForm */
?>
<div class="add">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput() ?>
    <?= $form->field($model, 'parent_id')->textInput()?>

    <?php
    // 加载 css js
    $this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
    $this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',[
        'depends'=>\yii\web\JqueryAsset::className()
    ]);
    $dj = [['id'=>0,'parent_id'=>0,'name'=>'顶级分类']];
   $nodes = \yii\helpers\Json::encode(\yii\helpers\ArrayHelper::merge($dj,\backend\models\Goods_category::getZtreeNodes()));
    //注册js代码


    $this->registerJs(
            <<<JS
 var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
             callback:{
                onClick: function(event, treeId, treeNode){
                    //alert(treeNode.tId + ", " + treeNode.name);
                    //点击时获取节点id
                    var id= treeNode.id;
                    
                    //将id写入parent_id的值
                    $("#goods_categoryform-parent_id").val(id);
                }
            },
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = {$nodes};
      
           zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
           //展开
              zTreeObj.expandAll(true);
            
        //获取节点  ,根据节点的id搜索节点
        var node = zTreeObj.getNodeByParam("id", {$model->parent_id}, null);   
        zTreeObj.selectNode(node);
    
JS

    );
echo ' <div>
        <ul id="treeDemo" class="ztree"></ul>
      </div>';

    ?>

    <?= $form->field($model, 'intro')->textarea() ?>

    <div class="form-group">
            <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- add -->
