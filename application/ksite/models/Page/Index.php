<?php
class Page_IndexModel {

    private $data = NULL;

    public function __construct() {

       $this->data = new Data_CmsModel();
    }

    public function build() {

         $articles = array();
         $articles['notice'] = $this->data->getArticle(0, 36, 0, 8);
         $articles['platform'] = $this->data->getArticle(0, 39, 0, 8);
         $articles['news'] = $this->data->getArticle(0, 38, 0, 8);

         $articles['focusImgs'] = $this->data->getArticle(0, 76);

         if(!empty($articles['focusImgs'])) {
            
             foreach($articles['focusImgs'] as $k => $v) {
                 
                 $v['introtext'] = htmlspecialchars_decode($v['introtext']);

                 preg_match('/<image_small>.*?<img.*src="(.*?)".*?<\/image_small>/', $v['introtext'], $matches);
                 $articles['focusImgs'][$k]['small'] = $matches[1];

                 preg_match('/<image_middle>.*?<img.*src="(.*?)".*?<\/image_middle>/', $v['introtext'], $matches);
                 $articles['focusImgs'][$k]['middle'] = $matches[1];

                 preg_match('/<image_big>.*?<img.*src="(.*?)".*?<\/image_big>/', $v['introtext'], $matches);
                 $articles['focusImgs'][$k]['big'] = $matches[1];
             }
         }
  
         $articles['userCase'] = $this->data->getArticle(0, 77);
         $articles['friends'] = $this->data->getArticle(0, 78);

         $articles['noticePlatform'] =  $this->data->getArticle(0, array(36, 39), 0, 8);
         
         $articles['actPlan'] = $this->data->getArticle(0, 82, 0, 2);
         
         return $articles;
    }
}
