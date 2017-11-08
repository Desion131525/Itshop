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
<h4 align="center">商品详情</h4>



<table class="table table-bordered">


    <tr>
        <th>商品名称:</th>
        <td><?=$goods->name?></td>
    </tr>
    <tr>
        <th>商品货号:</th>
        <td><?=$goods->sn?></td>
    </tr>
    <tr>
        <th>价格:</th>
        <td><?=$goods->shop_price?></td>
    </tr>


</table>
<hr/>
<p><?=$content->content?></p>
</body>
</html>
