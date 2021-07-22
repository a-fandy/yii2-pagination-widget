# yii2-pagination-widget
widget untuk pagination

# USAGE LIST
PHP
```php
    use app\components\PaginationWidget;

    //optional style
    $this->registerCss("
    .pagination a {
        // color: black;
        float: left;
        padding: 8px 16px;
        text-decoration: none;
        transition: background-color .3s;
    }
    
    .pagination a.active {
        color: black;
    }"
  );

  $query = (new \yii\db\Query())
            ->select(['*'])
            ->from('table');
```

HTML
```html
 <div class="row">
    <?= PaginationWidget::widget([
        'query' => $query,
        'listData' => function ($model) {
            return "
            <div class='col-lg-12'>
            <h2>$model->id</h2>
            <p>$model->title</p>
        </div>";
        },
        //optional
        //'pageSize' => 5, //default 20 
        //'linkSize' => 10, //default 5
    ]) ?>
</div>
```

# USAGE GRID
PHP
```php
    use app\components\PaginationWidget;
```

HTML
```html
 <div class="row">
    <?= 
       <?= PaginationWidget::widget([
            'query' => $query,
            'search_model' =>  $search_model,
            'gridData' => function ($data_provider, $column) {
                return GridView::widget([
                    'dataProvider' => $data_provider,
                    'columns' => $column,
                ]);
            },
            //optional
            'pageSize' => 5, //default 20 
            //'linkSize' => 5, //default 20 
        ]) ?>
    ?>
</div>
```

# image
[Screenshot](Screenshot.png)