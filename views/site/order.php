<?php
/* @var string $avatar_base64 */
/* @var string $name */
/* @var string $username */
/* @var array $services */
/* @var CreateOrderForm $createOrderForm */

use app\models\forms\CreateOrderForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<div class="container">
    <div class="row">
        <div class="flex-column">
            <img src="data:image/jpg;base64, <?=$avatar_base64?>" width="100px"/>
            <span><?=$name?></span>
            <a href="https://instagram.com/<?=$username?>" target="_blank"><?=$username?></a>
        </div>
    </div>
    <div class="row mt-lg-3">

        <?php $form = ActiveForm::begin([
            'id' => 'create-order-form',
            'validationUrl' => Url::to(['site/create-order-validate']),
            'enableAjaxValidation' => true,
            'action' => Url::to(['site/create-order']),
            'fieldConfig' => [
                'template' => "<b>{label}</b>\n{input}\n<div class=\"text-danger\">{error}</div>",
                'labelOptions' => ['class' => 'control-label'],
            ],
        ]); ?>

        <?=  $form
            ->field($createOrderForm, 'serviceId')
            ->radioList($services, [
                'style' => 'display: grid;',
                'uncheck' => null,
            ]);
        ?>

        <?= $form->field($createOrderForm, 'quantity')->textInput(['type' => 'number'])?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t("app","create_order"), ['class' => 'btn btn-primary', 'name' => 'create-button']) ?>
        </div>

        <?= $form->field($createOrderForm, 'profileURL')->hiddenInput()->label(false)?>
        <?= $form->field($createOrderForm, 'avatar_base64')->hiddenInput()->label(false)?>
        <?php ActiveForm::end(); ?>
    </div>
</div>