<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "brand".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property string $logo
 * @property integer $sort
 * @property integer $status
 */
class BrandForm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $name;
    public $intro;
    public $imgFile;
    public $status;
    public $logo;
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'intro','status','logo'], 'required'],
            [['intro'], 'string'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 50],
            //['imgFile','file','extensions'=>['jpg','png','gif'],'skipOnEmpty'=>true]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '品牌名称',
            'intro' => '品牌简介',
            'logo' => '图片',
            'status' => '状态',
        ];
    }
}
