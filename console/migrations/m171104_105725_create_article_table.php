<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m171104_105725_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_day_count', [
            'id' => $this->primaryKey(),
            'day'=>$this->date()->comment('日期'),
            'count'=>$this->integer()->comment('商品数据'),

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article');
    }
}
