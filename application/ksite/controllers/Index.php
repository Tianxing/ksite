<?php
class IndexController extends Yaf_Controller_Abstract {
   public function indexAction() {

       $page = new Page_IndexModel();
       $articles = $page->build();

       $adv = new Page_AdvModel();
       $advBottom = $adv->bottom();
       $this->getView()->assign("advBottom", $advBottom);
       
       $this->getView()->assign("articles", $articles);
       $this->getView()->display('index/index.html');
   }
}
