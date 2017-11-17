<?php

use yii\db\Migration;

/**
 * Handles the creation of table `member`.
 */
class m171112_073328_create_member_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('member', [
            'id' => $this->primaryKey(),
            'username'=>$this->string(50)->notNull()->comment('用户名'),
            'auth_key'=>$this->string(32)->notNull(),
            'password_hash'=>$this->string(100)->notNull(),
            'email'=>$this->string(100)->notNull(),
            'tel'=>$this->char(11)->notNull(),
            'last_login_time'=>$this->integer()->notNull(),
            'last_login_ip'=>$this->integer()->notNull(),
            'status'=>$this->integer(1)->notNull(),
            'created_at'=>$this->integer()->notNull(),
            'update_at'=>$this->integer()->notNull(),

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('member');
    }
}
