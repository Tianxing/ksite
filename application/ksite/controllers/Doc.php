<?php
class DocController extends Yaf_Controller_Abstract {

    public function indexAction() {
        
        $adv = new Page_AdvModel();
        $advBottom = $adv->bottom();
        $this->getView()->assign("advBottom", $advBottom);

        $this->getView()->display("doc/index.html");
    }

    public function searchAction() {

        $page = new Page_DocModel();
        $word = $_GET["word"];
        $pageNo = $this->_request->getParam('page');

        $result = $page->search($word, $pageNo);

        $this->getView()->assign('word', $word);
        $this->getView()->assign('articles', $result['articles']);

        //wrap page
        $this->getView()->assign("page", $result['page']);
        $this->getView()->assign("pageNum", $result['pageNum']);
        $this->getView()->assign("count", $result['count']);


        $adv = new Page_AdvModel();
        $advBottom = $adv->bottom();
        $this->getView()->assign("advBottom", $advBottom);

        $this->getView()->display("doc/search.html");
    }

    public function artAction() {

       $id = $this->_request->getParam('id');

       $page = new Page_DocModel();
       $article = $page->article($id);

       $adv = new Page_AdvModel();
       $advBottom = $adv->bottom();
       $this->getView()->assign("advBottom", $advBottom);

       $this->getView()->assign("article", $article);
       $this->getView()->display('doc/art.html');
    }
}
