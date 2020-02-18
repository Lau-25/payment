<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'User info';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <? 
        echo $params->nickname . '<br/>';
        echo $params->balance;
    
    ?>
    
    <?  $form = ActiveForm::begin([
            'id' => 'billing',
            'options' => ['class' => 'form-horizontal'],
        ]);
        $form->action = '/site/update';
    ?>
    <?= $form->field($model, 'nickname') ?>
    <?= $form->field($model, 'balance') ?>
    <?=Html::hiddenInput("id", $params->id)?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Перевести', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
    <? ActiveForm::end() ?>
</div>
