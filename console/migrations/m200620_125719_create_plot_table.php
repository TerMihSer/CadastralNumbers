<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%plot}}`.
 */
class m200620_125719_create_plot_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%plot}}', [
            'id' => $this->primaryKey(),
            'cadastralNumber' => $this->string(),
            'address' => $this->text(),
            'price' => $this->decimal(10, 4),
            'area' => $this->decimal(10, 4),
        ]);

        $this->createIndex(
            'idx-plot-cadastralNumber',
            'plot',
            'cadastralNumber',
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-plot-cadastralNumber',
            'plot'
        );
        $this->dropTable('{{%plot}}');
    }
}
