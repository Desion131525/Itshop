<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/10 0010
 * Time: 14:49
 */

namespace backend\models;

use creocoder\nestedsets\NestedSetsBehavior;
use yii\db\ActiveRecord;

class Menu extends ActiveRecord
{

    public $old_name;//用于保存原来的权限名称
    //定义类常量
    const SCENARIO_Add = 'add';
    const SCENARIO_Edit = 'edit';
    //约束
    public function rules()
    {


        return [
            [['name','parent_id'],'required'],
            [["route",'sort'],"safe"],
            ['name','validateName','on'=>self::SCENARIO_Add],//添加时生效,修改不生效
            ['name','validateUpdateName','on'=>self::SCENARIO_Edit]//修改时生效,添加不生效
        ];
    }
    //设置属性标签名
    public function attributeLabels()
    {
        return [
            'name'=>'名称',
            'parent_id'=>'上级菜单',
            'route'=>'路由/地址',
            'sort'=>'排序',
        ];
    }

   //验证添加数据
    public function validateName()
    {
        $name = Menu::find()->where(['name'=>$this->name])->one();

        if($name)
        {
            return   $this->addError('name','菜单名已存在');

        }
        return true;
    }

    //修改时验证
    public function validateUpdateName()
    {
        $rbac = \Yii::$app->authManager;
        //当修改的名称和原来的不一样时,就说明名称有修改
       // var_dump($this->name);
        //var_dump($this->old_name);die;
        if($this->old_name!=$this->name)
        {
            //根据修改的名称查询数据

            $name = Menu::findOne(['name'=>$this->name]);
            //var_dump($name);die;
            if($name)
            {
                //如果有数据说明名已经存在,无法更新
                $this->addError('name','菜单名称已经存在');
            }
        }


    }

    //建立一级菜单和二级菜单的关系 一对多
    // 儿子的prent_id === 父亲的 id
    public function getChildren()
    {
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }

}