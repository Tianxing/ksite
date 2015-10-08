<?php
class Page_DocModel {

    public function __construct() {

    }

    public function search($keyword, $page) {

        $page = max(1, intval($page));
        $pageNum = 1;
        $start = ($page - 1) * $pageNum;

        $search = new Data_SearchModel();
        $result = $search->query($keyword, $start, $pageNum);

        $result['page'] = $page;
        $result['pageNum'] = $pageNum;

        return $result;
    }

    public function article($artId) {

        $cms = new Data_CmsModel();
        return $cms->getArticle($artId);
    }
}
