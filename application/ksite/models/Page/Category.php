<?php
class Page_CategoryModel {

    private $data = NULL;

    public function __construct() {

       $this->data = new Data_CmsModel();
    }

    public function build($catId, $page) {

        $page = max(1, intval($page));
        $pageNum = 2;
        $start = ($page - 1) * $pageNum;

        $count = $this->data->getArticleCount($catId);

        if($start > $count) {
           $start = 0;
        }

        $result['page'] = $page;
        $result['count'] = $count;
        $result['pageNum'] = $pageNum;
        $result['articles'] = $this->data->getArticle(0, $catId, $start, $pageNum);

        return $result;
    }

    public function index() {


        $index[] = array('title' => '关于我们', 'url' => 'http://www.w3school.com.cn/');

        $news = $this->data->getCategory(37);
        $news['children'] = $this->data->getCategory(0, 37);
        $index[] = $news;

        $index[] = array('title' => 'FAQ', 'url' => 'http://www.ksyun.com/ks3/index.html');
        $index[] = array('title' => '加入我们', 'url' => 'http://www.ksyun.com/ks3/index.html');
        $index[] = array('title' => '联系我们', 'url' => 'http://www.ksyun.com/ks3/index.html');

        return $index;
    }
}
