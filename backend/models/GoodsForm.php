<?php

namespace backend\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property string $name
 * @property string $sn
 * @property string $logo
 * @property integer $goods_category_id
 * @property integer $brand_id
 * @property string $market_price
 * @property string $shop_price
 * @property integer $stock
 * @property integer $status
 * @property integer $is_on_sale
 * @property integer $sort
 * @property integer $create_time
 * @property integer $view_times
 */
class GoodsForm extends Model
{
    public $name;
    public $sn;
    public $logo;
    public $goods_category_id;
    public $brand_id;
    public $market_price;
    public $shop_price;
    public $stock;
    public $status;
    public $is_on_sale;
    public $sort;
    public $create_time;
    public $view_times;
    public $imgFile;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_category_id', 'brand_id', 'stock', 'status',
                'market_price', 'is_on_sale','shop_price','name','sn'], 'required'],
            [['market_price', 'shop_price',], 'number'],
            [['name', 'sn'], 'string', 'max' => 20],
            ['imgFile','file','extensions'=>['jpg','png','gif'],'skipOnEmpty'=>true]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '商品名称',
            'sn' => '货号',
            'logo' => '图片',
            'goods_category_id' => '商品分类',
            'brand_id' => '品牌分类',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'status' => '状态',
            'is_on_sale' => '是否在售',
        ];
    }
}
