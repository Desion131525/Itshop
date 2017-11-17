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
        <th>商品名称</th>
        <th>品牌名称</th>
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
            <td><?=$v->brand->name?></td>
            <td><?=$v->sn?></td>
            <td><?=$v->market_price?></td>
            <td><?=$v->shop_price?></td>
            <td><?=$v->stock?></td>
            <td><?=$v->is_on_sale==1?'是':'否'?></td>
            <td><?=$v->view_times==''?'暂无':$v->view_times?></td>
            <td><img src="<?=Yii::getAlias('@web').$v->logo?>" class="img-rounded"/></td>
            <td><?=$v->create_time?></td>
            <td>

                <?php
                $can = \Yii::$app->user;
                if($can->can('goods/img'))
                {
                    echo " <a href='img?id=$v->id' class='btn btn-primary'>相册</a>";

                }
                if($can->can('goods/edit'))
                {
                    echo " <a href='edit?id=$v->id' class='btn btn-primary'>修改</a>";

                }
                if($can->can('goods/intro'))
                {
                    echo " <a href='intro?id=$v->id' class='btn btn-primary'>商品详情</a>";

                }
                if($can->can('goods/del'))
                {
                    echo " <a href='javascript: ;' class='btn btn-danger del'>删除</a>";

                }
                ?>



            </td>
        </tr>
    <?php endforeach;?>
<!-------------------------------------------------------------------------------------->
    <form class="form-inline" role="form" method="get" action="search">
        <div class="form-group col-lg-1">
            <a href="add" class="btn btn-primary">添加商品</a>
        </div>
        <div class="form-group col-lg-2">
            <input type="text" name="name" class="form-control" id="exampleInputEmail2" placeholder="商品名称">
        </div>
       <div class="form-group col-lg-2">
              <input type="text" name="sn" class="form-control" id="exampleInputEmail2" placeholder="货号">
          </div>
            <div class="form-group col-lg-2">
              <input type="text" name="shop_price" class="form-control" id="exampleInputEmail2" placeholder="￥">
          </div>
           <div class="form-group col-lg-2">
              <input type="text" name="shop_price_max" class="form-control" id="exampleInputPassword2" placeholder="￥">
          </div>

          <button type="submit" class="btn btn-default">搜索</button>
    </form>
<!-------------------------------------------------------------------------------------->
    <!-- 搜索  -->
    <!--<form class="form-inline" role="form" method="get" action="search">

        <div class="container">
            <div class="col-lg-1">

            </div>
            <div class="row">
                <div class="col-lg-1">
                    <button type="submit" class="btn btn-default">搜索</button>
                </div>
                <div class="col-lg-4">
                    <input type="text" name="search" class="form-control" id="exampleInputEmail2" placeholder="输入关键字搜索">
                </div>

            </div>-->

        </div>

    </form>


</table>
<?php
$can = Yii::$app->user;
if($can->can('goods/recycle'))
{
    echo "<a href='recycle' class='btn btn-warning' >回收站</a><br/>";
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
