<?php

/* @var $this yii\web\View */
/* @var $searchModel app\models\searches\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use app\models\Orders;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

?>

<div class="body-content">

    <div class="row">
        <div class="col-lg-12">
            <h2><?=Yii::t("app","my_orders")?></h2>

            <?php Pjax::begin(); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                /*'pjax' => true,
                'striped' => true,
                'hover' => true,
                'showPageSummary' => true,*/
                'columns' => [
                    'id',
                    'ext_id',
                    [
                        'content' => function (Orders $model) {
                            return Html::img(
                                'data:image/jpg;base64, ' . $model->avatar_base64,
                                ['width' => '100px']
                            );
                        }
                    ],
                    [
                        'content' => function (Orders $model) {
                            return Html::a(
                                $model->link,
                                $model->link,
                                ['target' => '_blank', 'data-pjax' => 0]
                            );
                        }
                    ],
                    'service.name',
                    [
                        'content' => function (Orders $model) {
                            return $model->quantity - $model->remains . ' ' . Yii::t("app","from"). ' ' . $model->quantity;
                        }
                    ],
                    'created_at',
                    'updated_at',
                ],
            ]); ?>

            <?php Pjax::end(); ?>

        </div>
    </div>

</div>
