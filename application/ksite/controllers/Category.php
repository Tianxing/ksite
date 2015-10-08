<?php
class CategoryController extends Yaf_Controller_Abstract {
   public function listAction() {

       $id = $this->_request->getParam('id');
       $pageNo = $this->_request->getParam('page');

       $page = new Page_CategoryModel();

       $result = $page->build($id, $pageNo);
       $result['index'] = $page->index();

       $adv = new Page_AdvModel();
       $advBottom = $adv->bottom();
       $this->getView()->assign("advBottom", $advBottom);

       //wrap page
       $this->getView()->assign("page", $result['page']);
       $this->getView()->assign("pageNum", $result['pageNum']);
       $this->getView()->assign("count", $result['count']);

        
       $this->getView()->assign("index", $result['index']);
       $this->getView()->assign("articles", $result['articles']);

       $this->getView()->display('category/list.html');
   }
}
