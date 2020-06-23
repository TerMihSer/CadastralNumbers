<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $modelForm frontend\models\CadastralForm */

$this->title = 'My Yii Application';

?>
    <h2>Получение кадастровых данных</h2>
<?php $form = ActiveForm::begin();
?>
<?= $form->field($modelForm, 'cadastralNumber')->label('Кадастровые номера')->hint('Введите кадастровые номера через запятую. Например, «69:27:0000022:1306, 69:27:0000022:1307»') ?>
    <div class="form-group">
        <?= Html::submitButton('Получить данные', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>


<?php
if (!empty($dataProvider)) {
    echo GridView::widget([
        'dataProvider' => $dataProvider,
    ]);
}
