<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/4 0004
 * Time: 9:56
 */

namespace backend\models;


use yii\base\Model;

class ArticleForm extends Model
{
    public $name;
    public $intro;
    public $status;
    public $article_category_id;
    public function rules()
    {
        return [
            [['name', 'intro', 'status','article_category_id'], 'required'],
            [['intro'], 'string'],
            [ 'status', 'integer'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '文章标题',
            'intro' => '文章简介',
            'status' => '状态',
            'article_category_id' => '文章分类',
        ];
    }
}