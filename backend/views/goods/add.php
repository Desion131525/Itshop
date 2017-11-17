<?php
//普通分类
//$form->field($model, 'goods_category_id')->dropDownList($gc)
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \kucha\ueditor\UEditor;
/* @var $this yii\web\View */
/* @var $model backend\models\GoodsForm */
/* @var $form ActiveForm */
?>
<div class="add">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput() ?>


        <?= $form->field($model, 'brand_id')->dropDownList($brand) ?>
        <?= $form->field($goods_categoryForm, 'parent_id')->hiddenInput() ?>
    <?php
    //=======================分类分类分类分类分类分类分类分类================================================
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
           //展开所有节点
              zTreeObj.expandAll(true);
         
        //获取节点  ,根据节点的id搜索节点
        var node = zTreeObj.getNodeByParam("id", {$goods_categoryForm->parent_id}, null);   
        zTreeObj.selectNode(node);
    
JS

    );
    echo ' <div>
        <ul id="treeDemo" class="ztree"></ul>
      </div>';





    //========================分类分类分类分类分类分类分类分类===============================================
    ?>
        <?= $form->field($model, 'stock')->textInput() ?>
        <?= $form->field($model, 'status')->radioList(['1'=>'正常','0'=>'隐藏']) ?>
        <?= $form->field($model, 'is_on_sale')->radioList(['1'=>'是','0'=>'否']) ?>
        <?= $form->field($model, 'market_price')->textInput() ?>
        <?= $form->field($model, 'shop_price')->textInput() ?>
        <?= $form->field($model, 'imgFile')->fileInput() ?>
       <?= $form->field($goods_introForm,'content')->widget('kucha\ueditor\UEditor',[]);?>
        <div class="form-group">
            <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- add -->
