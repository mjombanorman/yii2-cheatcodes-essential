<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;

?>
<div class="user-view">

    <p>
        <!-- In the case of having a custom button that is specifically made to use a modal and pjax to update a field in the database the button is as follows:
            >> (activate) in lower case is the action and parameters which row to update is specified by 'id'=> $model->id.
            >>

        -->
                    <?= Html::a(Yii::t('app', 'Activate'), ['activate', 'id' => $model->id], [
            'class' => 'btn btn-success',
            'data' => [
                  'toggle' => 'modal',
                  'target'=> '#activate'
            ],
        ]) ?>

    </p>
<?php Pjax::begin(); ?>  
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'firstname',
            'lastname',
            'user_id',
            'username',
            'email:email',
            // this custom function allows displaying a string field of either Active or Inactive
            // instead of dispalying intergers to users
            [
                'attribute'=>'Account Status',
                 'value' => $model->status === 10 ? 'Active' : 'Inactive',

            ],
             'created_at:date',
            'updated_at:datetime',
        ],
    ]) ?>
    <?php Pjax::end(); ?> 

</div>
<!-- this is the modal structure that allows the form t be update in the samepage it was requested by the user.-->

<?php
Modal::begin(['id' => 'activate',
'header'=>'<h4>Are You Sure You Want to Perform this Action?</h4>',
'footer' => '<a href="#" class="btn btn-warning" data-dismiss="modal">Close</a>',

]);
    Pjax::begin(['id'=>'pjax-modal', 'timeout'=>false,
        'enablePushState'=>false,
        'enableReplaceState'=>false,]);

    Pjax::end();
Modal::end();  
?>

<?php
$this->registerJs('
    $("#activate").on("shown.bs.modal", function (event) {
        var button = $(event.relatedTarget)
        var href = button.attr("href")
        $.pjax.reload("#pjax-modal", {
            "timeout":false,
            "url":href,
            "replace":false,  
        });  
    })
');    
?>



<?php
// the action governing this script that will be used in its governing controller.

public function actionActivate($id)
    {
        $model = User::findOne($id);

        if ($model->load(Yii::$app->request->post())){

        $model->status = (strtolower($model->status) === "activate" ) ? 10 : 0 ;

         if($model->save()) {
             //this is the methods that are used to render the page when the function has been successfully
            //excecuted by the user.
                Yii::$app->session->setFlash('success', 'Successfully Activated');
                return $this->redirect(['index']);
                return $this->refresh();
            }}else {
                //the action that happens in case the post request fails or is not performed at all
                if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('activate', ['model' => $model]);
                }
                else{
                    //fallback mechanism that allows the page to be render still even throught its not an ajax request.
                    $this->layout ='main-index';
                    return $this->render('activate', ['model' => $model]);
                }
            }
    }?>