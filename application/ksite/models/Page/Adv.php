<?php
class Page_AdvModel {

    private $data = NULL;

    public function __construct() {

       $this->data = new Data_CmsModel();
    }

    public function bottom() {

        return $this->data->getArticle(0, 91, 0, 1);
    }
}
