<?php

use yii\db\Migration;

class m250322_211134_update_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%post}}', 'created_at', $this->timestamp()->null()->defaultExpression('CURRENT_TIMESTAMP')->after('content'));

        $this->addColumn('{{%post}}', 'updated_at', $this->timestamp()->defaultValue(null)->append('ON UPDATE CURRENT_TIMESTAMP')->after('created_at'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%post}}', 'updated_at');

        $this->dropColumn('{{%post}}', 'created_at');
    }
}
