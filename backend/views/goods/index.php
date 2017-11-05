<?php
/* @var $this yii\web\View */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>商品名称</th>
        <th>货号</th>
        <th>市场价格</th>
        <th>商品价格</th>
        <th>库存</th>
        <th>是否在售</th>
        <th>浏览次数</th>
        <th>LOGO</th>
        <th>添加时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($rows as $v):?>
        <tr>
            <td><?=$v->id?></td>
            <td><?=$v->name?></td>
            <td><?=$v->sn?></td>
            <td><?=$v->market_price?></td>
            <td><?=$v->shop_price?></td>
            <td><?=$v->stock?></td>
            <td><?=$v->is_on_sale?></td>
            <td><?=$v->view_times?></td>
            <td><img src="<?=Yii::getAlias('@web').$v->logo?>"/></td>
            <td><?=$v->create_time?></td>
            <td>
                <a href="edit?id=<?=$v->id?>" class="btn btn-primary">修改</a>
                <a href="javascript: ;" class="btn btn-danger del">删除</a>
            </td>
        </tr>
    <?php endforeach;?>
    <a href="add" class="btn btn-primary">添加商品</a>
</table>
    <a href="recycle" class="btn btn-warning">回收站</a><br/>

<!-- Modal -->
<?php
//实例化工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页']);
//视图注册jd代码
$url = \yii\helpers\Url::to(['del']);
$this->registerJs(
        <<<JS

    $('.del').click(function () {
        if(confirm('确定删除?'))
        {
            var url = '{$url}'
        var id = $(this).closest('tr').find('td:first').text()
        var that = this;
        $.post(url,{id:id},function(data) {
          if(data=='1')
          {
              $(that).closest('tr').remove();
          }
        })
        }
        
    })

JS

)
?>
</body>
</html>
