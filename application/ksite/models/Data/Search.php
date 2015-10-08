<?php
class Data_SearchModel {

    private $search = NULL;


    public function __construct(){

        $this->search = new Sphinx('document');
    }

    public function query($keyword = '', $start = 0, $limit = 10) {

       if(!$this->search || empty($keyword)) {
           return array();
       }

       $this->search->SetLimits($start, $limit, 1000);
       $list = $this->search->query($keyword, 'document_index'); 

       $log = Log::getInstance('sphinx');
       if($list === false) {
        
           $log->warning("Sphinx Search Faild:" . $this->search->GetLastError());
           return array();
       }

       if(!isset($list['matches']) || empty($list['matches'])) {
           return array();
       }

       $cms = new Data_CmsModel();
       $articles = $cms->getArticle(array_keys($list['matches']));

       $titles = array();
       $contents = array();
       $catids = array();
       foreach($articles as $k => $v) {
           $titles[$k] = strip_tags($v['title']);
           $contents[$k] = preg_replace("/[\s\t\r\n(&nbsp;)]+/", "", strip_tags($v['introtext']));
           $catids[$v['catid']] = true;
       }

       $catids = array_keys($catids);
       $categorys = $cms->getCategory($catids);

       $catMap = array();
       foreach($categorys as $k => $v) {
           $catMap[$v['id']] = $v['title'];
       }

       $redclass = array("before_match" => "<span style='color:#FF0000'>", "after_match" => "</span>");
       $titles = $this->search->buildExcerpts($titles, "document_index", $keyword, $redclass);
       $contents = $this->search->buildExcerpts($contents, "document_index", $keyword, $redclass);

       foreach($articles as $k => $v) {
           $articles[$k]['title'] = $titles[$k];
           $articles[$k]['introtext'] = $contents[$k];
           $articles[$k]['catTitle'] = $catMap[$v['catid']];
       }

       $result = array();
       $result['articles'] = $articles;
       $result['count'] = $list['total'];

       return $result;
    }
}
