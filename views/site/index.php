<?php

/* @var $this yii\web\View */
/* @var $instaProfileForm InstaProfileForm */

use app\models\forms\InstaProfileForm;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = Yii::$app->params['app.name'];
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-12">
                <h2><?=Yii::t("app","order")?></h2>

                <?php $form = ActiveForm::begin([
                    'id' => 'get-instagram-profile',
                    'validationUrl' => Url::to(['site/order-validate']),
                    'enableAjaxValidation' => true,
                    'action' => Url::to(['site/order']),
                    'fieldConfig' => [
                        'template' => "<b>{label}</b>\n{input}\n<div class=\"text-danger\">{error}</div>",
                        'labelOptions' => ['class' => 'control-label'],
                    ],
                ]); ?>

                <?= $form->field($instaProfileForm, 'profileURL')->textInput(['value' => 'https://instagram.com/reebok'])?>

                <?php ActiveForm::end(); ?>
            </div>
            <div class="col-lg-12" id="order-container">
            </div>
        </div>

    </div>
</div>

<?php
$this->registerJs(<<<JS
    function init() {
        let formGetInstagramProfile = $('#get-instagram-profile');
        formGetInstagramProfile.on('afterValidate', function (event, messages, errorAttributes) {
            if (errorAttributes.length === 0) {
                getInstagramProfile();    
            }
        });
        formGetInstagramProfile.on('beforeSubmit', function() {
            getInstagramProfile();
            return false;
        });
    }
    
    function getInstagramProfile() {
        let formGetInstagramProfile = $('#get-instagram-profile');
        $.ajax({
            url: formGetInstagramProfile.attr('action'),
            type: 'POST',
            data: formGetInstagramProfile.serialize(),
            success: function (data) {
                $('#order-container').html(data);
            },
            error: function(jqXHR, errMsg) {
                //alert('Error');
            }
         });
    }
    
    init();
JS);
?>