<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use common\models\Member;

/* @var $this yii\web\View */
/* @var $model common\models\Member */

$this->title = $model->Code." - ".$model->displayName;
$this->params['breadcrumbs'][] = ['label' => 'Miembros', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="member-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('<i class="fas fa-edit"></i> Actualizar', ['update', 'id' => $model->Id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('<i class="fas fa-times"></i> Eliminar', ['delete', 'id' => $model->Id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Está seguro que desea Eliminar este Registro?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">
                    <table class="table table-sm table-bordered">
                        <tbody>
                            <tr>
                                <td class="text-center border border-bottom-0">
                                    <?= Html::img($model->path ? $model->path : '@web/img/avatar.png', ['id' => 'member-picture', 'alt' => 'Miembro', 'class' => 'user-image', 'style' => 'width: 175px']); ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-10">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'Id',
                            'Code',
                            'FirstName',
                            'SecondName',
                            'ThirdName',
                            'FirstLastName',
                            'SecondLastName',
                            [
                                'attribute' => 'Gender',
                                'value' => $model->Gender == Member::GENDER_FEMALE ? 'Femenino' : 'Masculino',
                            ],
                            [
                                'attribute' => 'IdServiceCentre',
                                'value' => $model->IdServiceCentre ? $model->serviceCentre->Name: '',
                            ],
                            [
                                'attribute' => 'IdState',
                                'value' => $model->IdState ? $model->state->Name: '',
                            ],
                            'BirthDate',
                            'ConversionDate',
                            'BaptismDate',
                            'DeceaseDate',
                        ],
                    ])
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?= Html::a('<i class="fas fa-arrow-circle-left"></i> Cancelar',['index'],['class' => 'btn btn-danger']);?>
</div>
