<?php
class Page_ArticleModel {

    private $data = NULL;

    public function __construct() {

       $this->data = new Data_CmsModel();
    }

    public function build($artId) {

        $result['content'] = $this->data->getArticle($artId);

        $result['index'] = $this->data->getCategoryList($result['content']['catid']);
        
        return $result;
    }
}
