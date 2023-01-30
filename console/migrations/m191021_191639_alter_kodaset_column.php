<?php

use yii\db\Migration;

/**
 * Class m191021_191639_alter_kodaset_column
 */
class m191021_191639_alter_kodaset_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%aset}}', 'kodaset', 'VARCHAR(10) NOT NULL');
        $this->alterColumn('{{%aset_inventori}}', 'kodaset', 'VARCHAR(10) NOT NULL');
        $this->alterColumn('{{%aset_pergerakan}}', 'kodaset', 'VARCHAR(10) NOT NULL');
        $this->alterColumn('{{%handheld}}', 'kodaset_peranti', 'VARCHAR(10) NOT NULL');
        $this->alterColumn('{{%handheld}}', 'kodaset_pencetak', 'VARCHAR(10) NOT NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%aset}}', 'kodaset', 'VARCHAR(6) NOT NULL');
        $this->alterColumn('{{%aset_inventori}}', 'kodaset', 'VARCHAR(6) NOT NULL');
        $this->alterColumn('{{%aset_pergerakan}}', 'kodaset', 'VARCHAR(6) NOT NULL');
        $this->alterColumn('{{%handheld}}', 'kodaset_peranti', 'VARCHAR(6) NOT NULL');
        $this->alterColumn('{{%handheld}}', 'kodaset_pencetak', 'VARCHAR(6) NOT NULL');
    }
}
