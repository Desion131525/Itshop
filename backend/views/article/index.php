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
        <th>文章标题</th>
        <th>内容简介</th>
        <th>添加时间</th>
        <th>操作</th>

    </tr>
    <?php foreach ($rows as $v):?>
        <tr>
            <td><?=$v->id?></td>
            <td><?=$v->name?></td>
            <td><?=$v->intro?></td>
            <td><?=date('Y-m-d H:i:s',$v->create_time)?></td>
            <td>
                <a href="edit?&id=<?=$v->id?>" class="btn btn-primary">修改</a>
                <a href="javascript: ;" class="btn btn-danger del">删除</a>
            </td>
        </tr>
    <?php endforeach;?>
    <a href="add" class="btn btn-primary">添加文章</a>
</table>

<!--
--><?php
//实例化工具条
    echo \yii\widgets\LinkPager::widget([
        'pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页']);
//视图注册jd代码
$url =\yii\helpers\Url::to(['del']);
$this->registerJs(
    <<<JS
   
    $('.del').click(function () {
        if(confirm('是否删除该用户?删除后无法恢复!'))
        {
            var url = "{$url}";
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