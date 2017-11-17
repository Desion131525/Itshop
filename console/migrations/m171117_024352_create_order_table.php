<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order`.
 */
class m171117_024352_create_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order', [
            'id' => $this->primaryKey(),
            'member_id'=>$this->integer()->notNull(),
            'name'=>$this->string(50)->notNull(),
            'province'=>$this->string(20)->notNull(),
            'city'=>$this->string(20)->notNull(),
            'area'=>$this->string(20)->notNull(),
            'address'=>$this->string()->notNull(),
            'tel'=>$this->integer()->notNull(),
            'delivery_id'=>$this->integer()->notNull(),
            'delivery_name'=>$this->string()->notNull(),
            'delivery_price'=>$this->decimal()->notNull(),
            'payment_id'=>$this->integer()->notNull(),
            'payment_name'=>$this->string()->notNull(),
            'total'=>$this->decimal()->notNull(),
            'status'=>$this->integer()->notNull(),
            'trade_no'=>$this->string()->notNull(),
            'create_time'=>$this->integer()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order');
    }
}
