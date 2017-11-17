<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_goods`.
 */
class m171117_024936_create_order_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order_goods', [
            'id' => $this->primaryKey(),
            'order_id'=>$this->integer()->notNull(),
            'goods_id'=>$this->integer()->notNull(),
            'goods_name'=>$this->string()->notNull(),
            'logo'=>$this->string()->notNull(),
            'price'=>$this->decimal()->notNull(),
            'amount'=>$this->integer()->notNull(),
            'total'=>$this->decimal()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order_goods');
    }
}
