    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

         
            'book',

            /* this method below is used in concatinating two fields in the database using a relationship when and id of the specific
            model is used in this case user0 is the relationship function that links this model and the user model */
               [
            'attribute' => 'Buyer',
            'value' => function($model) { return $model->user0->firstname." ".$model->user0->lastname;}
        ],
          
          /* this is used to directly display the name instead of the id where supplier0 is the relationship functions that binds the supllier model and this particular model */

            'supplier0.sup_name',
            //'price',
             'quantity',

             /* instead of giving a direct interger it formats the value to currency */
            'total:currency',
            // 'status',

            /* instead of giving a direct interger it formats the value to datetime format other option is date or time */
             'created_at:datetime',

  /* this is used to specify what action are to be displayed in gridview in the action column mainly view in this case other actions are {update} and {delete}*/
  
            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view}',
        ],
        ],
     
    ]); ?>
</div>
