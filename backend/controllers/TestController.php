<?php
namespace backend\controllers;
use backend\models\Goods_day_count;
use backend\models\Goods_intro;
use yii\web\Controller;
use yii\web\Request;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/7 0007
 * Time: 8:51
 */
class TestController extends Controller
{


    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }

    public function actionTest()
    {
        $request = new Request();
        $goods_content = new Goods_intro();
        $goods_content->load($request->post());
        //$goods_content = $request->post('content');

        return $this->render('test');
    }
}