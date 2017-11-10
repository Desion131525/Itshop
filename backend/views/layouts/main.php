<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => '商城后台管理系统',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        [
            'label' => '商品管理',
            'items' =>
                    [
                        ['label'=>'商品列表','url' => ['/goods/index']],
                        ['label'=>'商品分类','url' => ['/goods_category/index']]
                    ],
        ],
        [
            'label' => '文章管理',
            'items' =>
                    [
                        ['label'=>'文章列表','url' => ['/article/index']],
                        ['label'=>'文章分类','url' => ['/article_category/index']]
                    ],
        ],
        [
            'label' => '帐户管理',
            'items' =>
                    [
                        ['label'=>'管理员列表','url' => ['/user/index']],
                        ['label'=>'角色列表','url' => ['/role/index']],
                        ['label'=>'权限列表','url' => ['/permission/index']],
                        ['label'=>'密码修改','url' => ['/user/edit_password']],
                    ],
        ],

    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => '品牌列表', 'url' => ['/brand/index']];
        //$menuItems[] = ['label' => '管理员', 'url' => ['/user/index']];
        $menuItems[] = ['label' => '登陆', 'url' => ['/user/login']];
 //       $menuItems[] = ['label' => '商品列表', 'url' => ['goods/index']];
 //       $menuItems[] = ['label' => '商品分类', 'url' => ['goods_category/index']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                '注销 (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' =>$menuItems,


    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
