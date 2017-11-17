<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/9 0009
 * Time: 13:55
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Auth_item extends ActiveRecord
{
    public $permissions;
    public $role;
    public $old_name;//用于保存原来的权限名称

    //定义类常量
    const SCENARIO_Add = 'add';
    const SCENARIO_Edit = 'edit';


    public function rules()
    {
        return [
            [['name','description',],'required'],
            ['permissions','safe'],
            ['role','safe'],
            ['name','validateName','on'=>self::SCENARIO_Add],//添加时生效,修改不生效
            ['name','validateUpdateName','on'=>self::SCENARIO_Edit]//修改时生效,添加不生效
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>'名称',
            'description'=>'描述',
            'permissions'=>'权限',
            'role'=>'角色',
        ];
    }

    //验证添加数据
    public function validateName()
    {
        $name = \Yii::$app->authManager->getPermission($this->name);
        if($name)
        {
            return   $this->addError('name','权限名已存在');

        }
        return true;
    }

    //添加数据
    public function add()
    {
        //创建权限对象
        $permission = \Yii::$app->authManager;
        //保存数据
        $per1 = $permission->createPermission($this->name);
        $per1->description = $this->description;
        return $permission->add($per1);
    }

    //修改权限时验证
    public function validateUpdateName()
    {
        $rbac = \Yii::$app->authManager;
        //当修改的权限名称和原来的不一样时,就说明权限名称有修改
        if($this->old_name!=$this->name)
        {
            //根据修改的权限名称查询数据
            $permission = $rbac->getPermission($this->name);
            if($permission)
            {
                //如果有数据说明权限名已经存在,无法更新
                $this->addError('name','权限名称已经存在');
            }
        }


    }
}