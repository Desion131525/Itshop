<?php
header("Content-Type: text/html;charset=utf-8");
?>
<div class="cart fl">
    <dl>
        <dt>
            <a href="<?=\yii\helpers\Url::to(['index/cart'])?>">去购物车结算</a>
            <b></b>
        </dt>
        <dd>
            <div class="prompt">
                购物车中还没有商品，赶紧选购吧！
            </div>
        </dd>
    </dl>
</div>
