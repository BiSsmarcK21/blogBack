<?php

use yii\db\Migration;

class m250322_220317_add_fk_to_post_images_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx_post_images_unique', 'post_images', ['post_id', 'image_id']);

        $this->addForeignKey(
            'fk_post_images_post_id',
            '{{%post_images}}',
            'post_id',
            'post',
            'id',
            'CASCADE',
            'CASCADE',
        );

        $this->addForeignKey(
            'fk_post_images_image_id',
            'post_images',
            'image_id',
            'image',
            'id',
            'CASCADE',
            'CASCADE',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_post_images_image_id', 'post_images');
        $this->dropForeignKey('fk_post_images_post_id', 'post_images');
        $this->dropIndex('idx_post_images_unique', 'post_images');
    }
}
