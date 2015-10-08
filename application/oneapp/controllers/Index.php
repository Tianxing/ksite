<?php
class IndexController extends Yaf_Controller_Abstract {
   public function indexAction() {//ĬÈAction
       $this->getView()->assign("content", "Hello World - oneapp");
   }
}
