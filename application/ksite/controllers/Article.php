<?php
class ArticleController extends Yaf_Controller_Abstract {
   public function showAction() {

       $id = $this->_request->getParam('id');

       $page = new Page_ArticleModel();
       $article = $page->build($id);

       $adv = new Page_AdvModel();
       $advBottom = $adv->bottom();
       $this->getView()->assign("advBottom", $advBottom);

       $this->getView()->assign("article", $article);
       $this->getView()->display('article/show.html');
   }
}
