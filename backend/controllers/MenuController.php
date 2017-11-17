<?php
namespace backend\controllers;
use backend\models\Menu;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;
use backend\filters\AdminFilter;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/10 0010
 * Time: 14:37
 */
class MenuController extends Controller
{
    //添加菜单
    public function actionAdd()
    {
        $request = new Request();
        //创建表单模型
        $model = new Menu();
        //获取权限名称
        $rbac = \Yii::$app->authManager;
        $permissions = $rbac->getPermissions();
        //默认
        $model->route = 0;
        $permissions = ArrayHelper::map($permissions,'name','name');
        $ly = ['0'=>'===请选路由==='];
        //合并
        $permissions =  ArrayHelper::merge($ly,$permissions);
        //设置权限场景
        $model->scenario = Menu::SCENARIO_Add;
        //获取上级菜单
        $parent = Menu::find()->where(['parent_id'=>0])->all();
        $parent = ArrayHelper::map($parent,'id','name');
        $dj = ['0'=>'顶级菜单'];
        //合并
        $parent =  ArrayHelper::merge($parent,$dj);
        $model->parent_id = 0;
        if($request->isPost)
        {
            //接收表单数据
            $model->load($request->post());
            //验证数据

            if($model->validate())
            {

               $model->save();
               //跳转
               \Yii::$app->session->setFlash('success','添加成功');
               $this->redirect('index');
            }
        }
        //显示表单
        return $this->render('add',['model'=>$model,'parent'=>$parent,'permissions'=>$permissions]);
    }

    //菜单列表
    public function actionIndex()
    {
        //查询菜单数据
        $rows = Menu::find()->all();
        //将数据渲染到视图
       return $this->render('index',['rows'=>$rows]);
    }

    //菜单修改
    public function actionEdit()
    {
        $model = new Menu();

        //获取权限名称
        $rbac = \Yii::$app->authManager;
        $permissions = $rbac->getPermissions();
        //默认
        $permissions = ArrayHelper::map($permissions,'name','name');
        $ly = ['0'=>'===请选路由==='];
        //合并
        $permissions =  ArrayHelper::merge($permissions,$ly);

        //获取上级菜单
        $parent = Menu::find()->where(['parent_id'=>0])->all();
        $parent = ArrayHelper::map($parent,'id','name');
        $dj = ['0'=>'顶级菜单'];
        //合并
        $parent =  ArrayHelper::merge($parent,$dj);

        $request = new Request();
        //接收修改id
        $id = $request->get('id');
        //根据id查询修改数据
        $row = Menu::findOne(['id'=>$id]);

        //设置修改的时的权限场景
        $model->scenario = Menu::SCENARIO_Edit;
        $model->old_name = $row->name;

        //将数据回显到修改表单
        $model->name =$row->name;
        $model->route = $row->route;
        $model->parent_id = $row->parent_id;
        $model->sort = $row->sort;
        if($request->isPost)
        {
            //接收数据
            $model->load($request->post());
            //验证数据
            if($model->validate())
            {
                //保存数据
              $row->name =  $model->name;
              $row->route =  $model->route;
              $row->parent_id =  $model->parent_id;
              $row->sort =  $model->sort;
              $row->save();
                //跳转
                //跳转
                \Yii::$app->session->setFlash('success','修改成功');
                $this->redirect('index');
            }

        }
        return $this->render('edit',['model'=>$model,'parent'=>$parent,'permissions'=>$permissions]);

    }

    //菜单删除
    public function actionDel()
    {
        //接收id
        $request = new Request();
        $id = $request->post('id');

        //根据id查询数据
        $row = Menu::findOne($id);
        //删除数据


            if($row->parent_id !=0)
            {
                //如果叶子节点不是根节点,直接删除.
                $result = $row->delete();
            }else{

                //节点和他的儿子一起删除

                echo '有子类不能删除';
                return false;
            }

        //响应ajax
        if($result)
        {
            echo '1';
        }

    }

    //测试
    public function actionTest(){
        $menu = Menu::findOne(['id'=>70]);
        var_dump($menu->children);
    }

    Public function behaviors()
    {
        Return
            [
                [
                    'class'=>AdminFilter::className(),
                    //'only'=>['add'],//指定执行的过虑器的操作,
                    //'except'=>['login'],//除了这些操作,都有效
                ]

            ];
    }

}