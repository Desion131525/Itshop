<?php
header("Content-Type: text/html;charset=utf-8");
/*$this->registerCssFile('@web/Data_tables/media/css/jquery.dataTables.css');
$this->registerJsFile('@web/Data_tables/media/js/jquery.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);
$this->registerJsFile('@web/Data_tables/media/js/jquery.dataTables.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);*/

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
<a href="add" class="btn btn-primary">添加菜单</a><br/><br/>
<table id="table_id_example" class="table table-bordered">
    <thead>
    <tr>
        <th>名称</th>
        <th>路由</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($rows as $v):?>
    <tr>
        <td><?=$v->name?></td>
        <td><?=$v->route?></td>
        <td><?=$v->sort?></td>
        <td>
            <a href="<?=\yii\helpers\Url::to(['edit','id'=>$v->id])?>" class="btn btn-primary">修改</a>
            <a menu_id = "<?=$v->id?>"href="javascript: ;" class="btn btn-primary del">删除</a>
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
          var id = $(this).attr('menu_id');
         
           
       var that = this;
        $.post('del',{id:id},function(data) {
             if(data =='1')
             {
                 $(that).closest('tr').remove();
             }else {
                 alert(data);
             }
        })
       }
       
   });


   /* $('#table_id_example').DataTable();*/

JS

)


?>


