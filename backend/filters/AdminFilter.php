<?php
namespace backend\filters;
use yii\base\ActionFilter;
use yii\web\HttpException;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/11 0011
 * Time: 16:41
 */
class AdminFilter extends ActionFilter
{

    //操作执行前
    public function beforeAction($action)
    {
        //判断该用户是否有操作权限
        if(!\Yii::$app->user->can($action->uniqueId)){


            //如果没有 再判断他是否有登陆
            if(\Yii::$app->user->isGuest){
                //如果没有登陆则跳转到登陆页面

               return $action->controller->redirect(['user/login']);

               // return false;

            }
            throw new HttpException(403,'对不起,您没有该操作权限');
            return false;


        }

        return true;

    }



   /* //操作执行后
    public function afterAction($action)
    {
        return true;
    }*/
}