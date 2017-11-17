<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/7 0007
 * Time: 21:57
 */

namespace backend\models;


use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{

    public function rules()
    {
        return [
            [['username','email',],'unique'],
            ['email','email'],
            [['email','username','password_hash','status'],'required'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'auth_key'=>'角色',
            'password_hash'=>'密码',
            'status'=>'状态',
            'created_at'=>'创建时间',
            'old_pwd'=>'原使密码',
            'new_pad'=>'新密码',
            're_pad'=>'再次输入新密码',
        ];
    }

    //获取用户对应的菜单
    public function getMenus()
    {
       /* $menuItems = [
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

        ];*/

        $menuItems = [];
        $menus = Menu::find()->where(['parent_id'=>0])->all();

       //获取所有一级菜单
        foreach ($menus as $menu)
        {
            $items = [];
            //遍历该一级菜单的子菜单
            foreach ($menu->children as $child)
            {
                //根据权限来确定是否显示该菜单
                if(\Yii::$app->user->can($child->route))
                {
                    $items[] = ['label'=>$child->name,'url'=>[$child->route]];
                }

            }
            $menuItem = ['label'=>$menu->name,'items'=>$items];
            //将该组菜单放入菜单组里面.
            //如果没有二级菜单就不显示一级菜单
            if($items)
            {
                $menuItems[] = $menuItem;
            }

        }









        return $menuItems;
    }


    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
       return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;

}


}