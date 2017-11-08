<?php
namespace backend\controllers;
use backend\models\User;
use backend\models\UserForm;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/7 0007
 * Time: 21:54
 */
class UserController extends Controller
{
    //显示管理员列表
    public function actionIndex()
    {
        //实例化分页组件
        $pager = new Pagination();
        //查询数据
        $user = User::find();
        //每页显示条数据
        $pager->pageSize = 3;
        //总记录数据
        $pager->totalCount = $user->where(['status'=>['1','0']])->count();
        //设置偏移量
        $rows = $user->where(['status'=>['1','0']])->limit($pager->limit)->offset($pager->offset)->all();
        //分配数据
        return $this->render('index',['rows'=>$rows,'pager'=>$pager]);
    }

    //添加管理员
    public function actionAdd()
    {
        //显示管理员表单
        $userForm = new UserForm();
        //接收表单数据
        $request = new Request();
        $userForm->load($request->post());
        $user = new User();
        //验证表单数据
        if($userForm->validate()){
            //保存数据
            $user->username = $userForm->username;
            $user->password_hash = $userForm->password_hash;
            $user->auth_key = $userForm->auth_key;
            $user->email = $userForm->email;
            $user->status = $userForm->status;
            $user->created_at = $userForm->created_at;
            $result = $user->save();
            if($result)
            {
                //跳转
                \Yii::$app->session->setFlash('success','添加成功');
                $this->redirect("index");
            }

        }



        return $this->render('add',['userForm'=>$userForm]);
    }

    //修改管理员
    public function actionEdit()
    {
        //接收id
        $request = new Request();
        $id = $request->get('id');
        //回显示表单
        $rows = User::findOne($id);
        $userForm = new UserForm();
        $userForm->username = $rows->username;
        $userForm->password_hash = $rows->password_hash;
        $userForm->auth_key = $rows->auth_key;
        $userForm->email = $rows->email;
        $userForm->status = $rows->status;
        $userForm->created_at = $rows->created_at;
        if($request->post())
        {
            //接收修改数据
            $userForm->load($request->post());
            //验证数据
            if($userForm->validate())
            {
                //保存
                $rows->username = $userForm->username;
                $rows->password_hash = $userForm->password_hash;
                $rows->auth_key = $userForm->auth_key;
                $rows->email = $userForm->email;
                $rows->status = $userForm->status;
                $rows->created_at = $userForm->created_at;
                if(  $rows->save())
                {
                    //跳转
                    \Yii::$app->session->setFlash('success','添加成功');
                    $this->redirect("index");
                }

            }


        }

        return $this->render('edit',['userForm'=>$userForm]);
    }

    //>>4.删除文章
    public function actionDel()
    {
        //接收id
        $request = new Request();
        $id = $request->post('id');

        //根据id查询数据
        $rows = User::findOne($id);

        //删除数据
        $rows->status = -1;
        $result = $rows->save(false);

        //响应ajax
        if($result)
        {
            echo '1';
        }

    }
}