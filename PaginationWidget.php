<?php

namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;

use Yii;
use yii\web\Request;

class PaginationWidget extends Widget
{
    public $query;
    public $pageSize = 20;
    public $pageCount;
    public $error;
    public $data;

    public $listData;
    public $links;
    public $linkSize = 5;

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
            if ($this->listData != null) {
                $renderList  = $this->renderList();
            }
            if ($this->links === null) {
                $renderLinks  = $this->renderLink();
            }
            return Html::decode($renderList . $renderLinks);
        }
        return Html::encode("silahkan masukkan query");
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
        $link = "<div class='pagination'>";
        $mulai = 1;
        if ($currentPage > (int)($this->linkSize / 2)) {
            $link .= "<a href='?page=1'>&laquo;</a>";
            $mulai =   $currentPage - ((int)($this->linkSize / 2) - 1);
        }
        $middle = $this->linkSize % 2 == 0 ? $this->linkSize + 1 : $this->linkSize;
        $linkSize =  $mulai + $middle;
        if ($linkSize >= $this->pageCount) {
            $linkSize = $this->pageCount + 1;
        }
        for ($i = $mulai; $i <  $linkSize; $i++) {
            $link .= "<a ";
            $link .= ($currentPage + 1) == $i ? "class='active'" : "";
            $link .= "href='?page=$i'>$i</a>";
        }
        if ($currentPage + 1 < $this->pageCount - 2) {
            $link .= "<a href='?page=$this->pageCount'>&raquo;</a>";
        }
        $link .= "</div>";
        return $link;
    }

    //pagination data
    public function paginate()
    {
        $count = $this->query->count();
        $offset = $this->getOffset();
        $limit = $this->getLimit();
        $this->pageCount = $this->getPageCount($count);
        $this->data = $this->query->offset($offset)
            ->limit($limit)
            ->all();
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