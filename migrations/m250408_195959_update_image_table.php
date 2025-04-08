<?php

use yii\db\Migration;

class m250408_195959_update_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('image', 'path', $this->string(255)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->alterColumn('image', 'path', $this->integer(255)->notNull());
    }
}
