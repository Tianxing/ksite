<?php
class ProductController extends Yaf_Controller_Abstract {

    public $page = null;

    public function init(){
        
        $this->page = new Page_ProductModel();
    }

    public function KecAction() {

        $page = $this->page->kec();
        $left = $this->page->left();

        $this->getView()->assign("page", $page);
        $this->getView()->assign("left", $left);

        $adv = new Page_AdvModel();
        $advBottom = $adv->bottom();
        $this->getView()->assign("advBottom", $advBottom);

        $this->getView()->display('product/kec.html');
    }
}
