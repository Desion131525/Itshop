<?php

use yii\db\Migration;

class m171108_022248_alter_column_to_user extends Migration
{
    public function up()
    {
        $this->addColumn('user','last_login_time',
        $this->integer()->notNull()->comment('最后登陆时间')
        );
        $this->addColumn('user','last_login_ip',
        $this->integer()->notNull()->comment('最后登陆ip')
        );
    }

    public function down()
    {
        echo "m171108_022248_alter_column_to_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
