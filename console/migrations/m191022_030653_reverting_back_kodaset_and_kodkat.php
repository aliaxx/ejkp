<?php

use yii\db\Migration;

/**
 * Class m191022_030653_reverting_back_kodaset_and_kodkat
 */
class m191022_030653_reverting_back_kodaset_and_kodkat extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand('SET foreign_key_checks=0')->execute();
        $this->alterColumn('{{%aset}}', 'kodkat', 'VARCHAR(2) NOT NULL');
        $this->alterColumn('{{%aset_kategori}}', 'kodkat', 'VARCHAR(2) NOT NULL');
        $this->alterColumn('{{%aset_inventori}}', 'kodkat', 'VARCHAR(2) DEFAULT NULL');
        $this->alterColumn('{{%aset_pergerakan}}', 'kodkat', 'VARCHAR(2) NOT NULL');

        $this->alterColumn('{{%aset}}', 'kodaset', 'VARCHAR(6) NOT NULL');
        $this->alterColumn('{{%aset_inventori}}', 'kodaset', 'VARCHAR(6) NOT NULL');
        $this->alterColumn('{{%aset_pergerakan}}', 'kodaset', 'VARCHAR(6) NOT NULL');
        $this->alterColumn('{{%handheld}}', 'kodaset_peranti', 'VARCHAR(6) NOT NULL');
        $this->alterColumn('{{%handheld}}', 'kodaset_pencetak', 'VARCHAR(6) NOT NULL');
        Yii::$app->db->createCommand('SET foreign_key_checks=1')->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191022_030653_reverting_back_kodaset_and_kodkat cannot be reverted.\n";

        return false;
    }
}
