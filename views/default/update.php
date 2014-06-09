<?php
/**
 * @var View $this
 * @var SourceMessage $model
 */

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;
use Zelenin\yii\modules\I18n\models\SourceMessage;

$this->title = Yii::t('app', 'Update') . ': ' . $model->message;
echo Breadcrumbs::widget(['links' => [
    ['label' => Yii::t('app', 'I18n'), 'url' => ['index']],
    ['label' => $this->title]
]]);
?>
<div class="message-update">
    <div class="message-form">
        <div class="panel panel-default">
            <div class="panel-heading"><?= Yii::t('app', 'Source message') ?></div>
            <div class="panel-body"><?= Html::encode($model->message) ?></div>
        </div>
        <?php $form = ActiveForm::begin(['validateOnType' => true]); ?>
        <div class="row">
            <?php foreach ($model->messages as $language => $message) : ?>
                <?= $form->field($model->messages[$language], '[' . $language . ']translation', ['options' => ['class' => 'form-group col-sm-6']])->textInput()->label($language) ?>
            <?php endforeach; ?>
        </div>
        <div class="form-group">
            <?=
            Html::submitButton(
                $model->getIsNewRecord() ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
                [
                    'class' => $model->getIsNewRecord() ? 'btn btn-success' : 'btn btn-primary'
                ]
            ) ?>
        </div>
        <?php $form::end(); ?>
    </div>
</div>
