<?php

use yii\db\Migration;

/**
 * Class m191021_172706_normalize_kodkat_for_inventori_asset
 */
class m191021_172706_normalize_kodkat_for_inventori_asset extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%aset}}', 'kodkat', 'VARCHAR(4) NOT NULL');
        $this->alterColumn('{{%aset_inventori}}', 'kodkat', 'VARCHAR(4) DEFAULT NULL');
        $this->alterColumn('{{%aset_pergerakan}}', 'kodkat', 'VARCHAR(4) NOT NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%aset}}', 'kodkat', 'VARCHAR(2) NOT NULL');
        $this->alterColumn('{{%aset_inventori}}', 'kodkat', 'VARCHAR(2) DEFAULT NULL');
        $this->alterColumn('{{%aset_pergerakan}}', 'kodkat', 'VARCHAR(2) NOT NULL');
    }
}
