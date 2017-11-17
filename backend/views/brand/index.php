<?php
/* @var $this yii\web\View */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>
        .img-rounded{
            width: 50px;
            height: 50px;
        }
    </style>
</head>
<body>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>品牌名称</th>
        <th>品牌简介</th>
        <th>LOGO</th>
        <th>操作</th>
    </tr>
    <?php foreach ($rows as $v):?>
        <tr>
            <td><?=$v->id?></td>
            <td><?=$v->name?></td>
            <td><?=$v->intro?></td>
            <td><img src="<?=$v->logo?>" class="img-rounded"/></td>
            <td>
                <?php
                $can = \Yii::$app->user;
                if($can->can('brand/add'))
                {
                    echo " <a href='edit?id=$v->id' class='btn btn-primary' >修改</a>";
                }
                if($can->can('brand/del'))
                {
                    echo "<a href='javascript: ;' class='btn btn-danger del' >删除</a>";
                }
                ?>

            </td>
        </tr>
    <?php endforeach;?>
    <?php
    $can = \Yii::$app->user;
    if($can->can('brand/add'))
    {
       echo " <a href='add' class='btn btn-primary' >添加品牌</a>";
    }
    ?>

</table>
<?php
$can = \Yii::$app->user;
if($can->can('brand/recycle'))
{
    echo " <a href='recycle' class='btn btn-warning'>回收站</a><br/>";
}
?>


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
