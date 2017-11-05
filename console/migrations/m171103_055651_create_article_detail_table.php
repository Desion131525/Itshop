<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_detail`.
 */
class m171103_055651_create_article_detail_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_detail', [
            'id' => $this->primaryKey(),
            'content'=>$this->text()->notNull()->comment('文章详情')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_detail');
    }
}
