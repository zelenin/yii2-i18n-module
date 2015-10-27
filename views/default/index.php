<?php
/**
 * @var View $this
 * @var SourceMessageSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;
use Zelenin\yii\modules\I18n\models\search\SourceMessageSearch;
use Zelenin\yii\modules\I18n\models\SourceMessage;
use Zelenin\yii\modules\I18n\Module;

$this->title = Module::t('Translations');
echo Breadcrumbs::widget(['links' => [
    $this->title
]]);
?>
<div class="message-index">
    <h3><?= Html::encode($this->title) ?></h3>
    <?php
    Pjax::begin();
    echo GridView::widget([
        'filterModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'id',
                'value' => function ($model, $index, $dataColumn) {
                    return $model->id;
                },
                'filter' => false
            ],
            [
                'attribute' => 'message',
                'format' => 'raw',
                'value' => function ($model, $index, $widget) {
                    return Html::a($model->message, ['update', 'id' => $model->id], ['data' => ['pjax' => 0]]);
                }
            ],
            [
                'attribute' => 'category',
                'value' => function ($model, $index, $dataColumn) {
                    return $model->category;
                },
                'filter' => ArrayHelper::map($searchModel::getCategories(), 'category', 'category')
            ],
            [
                'attribute' => 'status',
                'value' => function ($model, $index, $widget) {
                    /** @var SourceMessage $model */
                    return $model->isTranslated() ? 'Translated' : 'Not translated';
                },
                'filter' => $searchModel->getStatus()
            ]
        ]
    ]);
    Pjax::end();
    ?>
</div>
