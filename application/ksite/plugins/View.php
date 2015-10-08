<?php
class ViewPlugin {

    static public function Arturl($articleInfo) {
    
        if(isset($articleInfo['redict']) && !empty($articleInfo['redict'])) {
            
            return $articleInfo['redict'];
        }

        return '/article/show/id/' . $articleInfo['id'];
    }

    static public function formatDate($format, $date) {

        return date($format, strtotime($date));
    }

    static public function makeIndex($index) {

        if(!is_array($index)) {
            return array();
        }

        $result = array();
        $result[] = array('title' => '首页', 'url' => '/');

        $endKey = count($index) - 1;
        foreach($index as $k => $v) {
            
            if($endKey == $k) {
                $result[] = array('title' => $v['title'], 'url' => '/category/list/id/' . $v['id']);
            } else {
                $result[] = array('title' => $v['title']);
            }
        }

        return $result;
    }
}
