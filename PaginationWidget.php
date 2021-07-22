<?php

namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;

use Yii;
use yii\web\Request;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

class PaginationWidget extends Widget
{
    public $query;
    public $pageSize = 20;
    public $pageCount;
    public $error;
    public $data;

    public $listData;
    public $gridData;
    public $links;
    public $linkSize = 5;
    public $search_model;

    public function init()
    {
        parent::init();
        if ($this->query === null) {
            $this->error = true;
        } else {
        }
    }

    public function run()
    {
        if (!$this->error) {
            $this->paginate();
            $renderData  = '';
            if ($this->listData != null) {
                $this->data = $this->query->all();
                $renderData  = $this->renderList();
            }
            if ($this->gridData != null) {
                $renderData  = $this->renderGrid();
            }
            $renderLinks  = $this->renderLink();
            return Html::decode($renderData . $renderLinks);
        }
        return Html::encode("silahkan masukkan query");
    }

    public function renderGrid()
    {
        $view = '';
        $model =  new ActiveDataProvider([
            'query' => $this->query,
            'pagination' => false,
        ]);
        $column = $this->search_model->column($this->query);
        $view .= call_user_func($this->gridData, $model, $column);
        return $view;
    }

    public function renderList()
    {
        $view = '';
        foreach ($this->data as $value) {
            $model = json_decode(json_encode($value));
            $view .= call_user_func($this->listData, $model);
        }
        return $view;
    }

    public function renderLink()
    {
        $currentPage = $this->getPage();
        $link = "<ul class='pagination'>";
        $param = Yii::$app->request->queryParams;
        $mulai = 1;
        if ($currentPage >= round($this->linkSize / 2) && $this->pageCount > $this->linkSize) {
            $param['page'] = 1;
            $link .= "<li><a href='" . Url::to(array_merge([''], $param)) . "'>First</a></li>";
            $mulai =   $currentPage - (round($this->linkSize / 2) - 2);
        }
        $middle = $this->linkSize % 2 == 0 ? $this->linkSize - 1 : $this->linkSize;
        $linkSize =  $mulai + $middle;
        if ($linkSize > $this->pageCount) {
            $linkSize = $this->pageCount + 1;
        }
        if ($linkSize - $mulai < $middle && $this->pageCount >= $middle) {
            $mulai = $linkSize -  $middle;
        }
        if ($currentPage >= round($this->linkSize / 2) && $this->pageCount > $this->linkSize) {
            $param['page'] = $mulai - 1;
            $link .= "<li><a href='" . Url::to(array_merge([''], $param)) . "'>&laquo;</a></li>";
        }
        for ($i = $mulai; $i <  $linkSize; $i++) {
            $link .= "<li ";
            $link .= ($currentPage + 1) == $i ? "class='active'" : "";
            $param['page'] = $i;
            $link .= " ><a href='" . Url::to(array_merge([''], $param)) . "'>$i</a></li>";
        }

        if ($currentPage + 1 <= $this->pageCount - 2 && $this->pageCount >= $linkSize) {
            $param['page'] = $linkSize;
            $link .= "<li><a href=' " . Url::to(array_merge([''], $param)) . "'>&raquo;</a></li>";
            $param['page'] = $this->pageCount;
            $link .= "<li><a href=' " . Url::to(array_merge([''], $param)) . "'>Last</a></li>";
        }
        $link .= "</ul>";
        return $link;
    }

    //pagination data
    public function paginate()
    {
        $count = $this->query->count();
        $offset = $this->getOffset();
        $limit = $this->getLimit();
        $this->pageCount = $this->getPageCount($count);
        $this->query = $this->query->offset($offset)
            ->limit($limit);
    }

    public function getOffset()
    {
        $pageSize = $this->pageSize;
        return $pageSize < 1 ? 0 : $this->getPage() * $pageSize;
    }

    public function getLimit()
    {
        $pageSize = $this->pageSize;
        return $pageSize < 1 ? -1 : $pageSize;
    }

    public function getPage()
    {
        $page = (int) $this->getQueryParam('page', 1) - 1;
        return $page;
    }

    public function getPageCount($totalCount)
    {
        $pageSize = $this->pageSize;
        if ($pageSize < 1) {
            return $totalCount > 0 ? 1 : 0;
        }
        $totalCount = $totalCount < 0 ? 0 : (int) $totalCount;
        return (int) (($totalCount + $pageSize - 1) / $pageSize);
    }

    protected function getQueryParam($name, $defaultValue = null)
    {
        $request = Yii::$app->getRequest();
        $params = $request instanceof Request ? $request->getQueryParams() : [];
        return isset($params[$name]) && is_scalar($params[$name]) ? $params[$name] : $defaultValue;
    }
}