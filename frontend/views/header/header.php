<?php
header("Content-Type: text/html;charset=utf-8");
?>
<div class="topnav">
    <div class="topnav_bd w1210 bc">
        <div class="topnav_left">

        </div>
        <div class="topnav_right fr">
            <ul>
                <li>
                    <?php

                    if(Yii::$app->user->isGuest)
                    {
                        echo "您好，欢迎来到京西！";
                        echo "[<a href='".\yii\helpers\Url::to(['members/login'])."'>登录</a>]" ;


                    }else{
                        echo "您好，欢迎".\Yii::$app->user->identity->username."来到京西！";
                        echo " [<a href='".\yii\helpers\Url::to(['members/logout'])."'>退出登陆</a>]" ;
                    }

                    ?>

[<a href="<?=\yii\helpers\Url::to(['members/register'])?>">
    免费注册
</a>]
</li>
<li class="line">|</li>
<li>我的订单</li>
<li class="line">|</li>
<li>客户服务</li>

</ul>
</div>
</div>
</div>

