# yii2-pagination-widget
widget untuk pagination

# USAGE

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

```html
 <div class="row">
    <?= PaginationWidget::widget([
        'query' => $data,
        'listData' => function ($model) {
            return "
            <div class='col-lg-12'>
            <h2>$model->id</h2>
            <p>$model->title</p>
        </div>";
        },
        //optional
        'pageSize' => 5, //default 20 
        'linkSize' => 10, //default 5
    ]) ?>
</div>
```

# image
