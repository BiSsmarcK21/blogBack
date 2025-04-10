<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%post_images}}`.
 */
class m250322_215342_create_post_images_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%post_images}}', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer()->notNull(),
            'image_id' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%post_images}}');
    }
}
