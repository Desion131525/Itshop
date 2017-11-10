<?php
header("Content-Type: text/html;charset=utf-8");
$this->registerCssFile('@web/Data_tables/media/css/jquery.dataTables.css');
$this->registerJsFile('@web/Data_tables/media/js/jquery.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);
$this->registerJsFile('@web/Data_tables/media/js/jquery.dataTables.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>

</head>

<body>


</body>
</html>
<!--第二步：添加如下 HTML 代码-->
<a href="add" class="btn btn-primary">添加角色</a><br/><br/>
<table id="table_id_example" class="display">
    <thead>
    <tr>
        <th>角色名称</th>
        <th>角色描述</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($roles as $v):?>
    <tr>
        <td><?=$v->name?></td>
        <td><?=$v->description?></td>
        <td>
            <a href="edit?name=<?=$v->name?>" class="btn btn-primary">修改</a>
            <a href="javascript: ;" class="btn btn-primary del">删除</a>
        </td>

    </tr>
    <?php endforeach;?>
    </tbody>
</table>



<?php
$this->registerJs(
        <<<JS

   $('.del').click(function() {
       if(confirm('确定删除?'))
       {
           var name = $(this).closest('tr').find('td:first-child').text()
       var that = this;
        $.post('del',{name:name},function(data) {
             if(data =='1')
             {
                 $(that).closest('tr').remove();
             }
        })
       }
       
   })






    $('#table_id_example').DataTable();
 ;





JS

)


?>


