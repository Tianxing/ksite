<?php
class Data_CmsModel {

    private $db = NULL;


    public function __construct(){

        $this->db = new Db_Mysql('jcms');
    }

    public function getArticle($id = 0, $catId = 0, $start = 0, $limit = 10, $state = 1) {


       $state = intval($state);

       $start = intval($start);
       $limit = intval($limit);

       $sql = "SELECT `id`, `catid`, `title`, `introtext`, `metadesc`, `images`, `urls`, `modified`, `created`, `state` FROM `jcms_content` WHERE `state` = '{$state}'";
#       $sql = "SELECT `id`, `title`, `images`, `modified`, `created`, `state` FROM `jcms_content` WHERE `state` = '{$state}'";

       if($id) {

           if(is_array($id)) {
               $sql .= " AND `id` IN(" . @mysql_real_escape_string(implode(',', $id)) . ")";
           } else {
               $sql .= " AND `id` = " . intval($id);
           }
       }

       //栏目分类
       if($catId) {

           if(is_array($catId)) {
               $sql .= " AND `catid` IN(" . @mysql_real_escape_string(implode(',', $catId)) . ")";
           } else {
               $sql .= " AND `catid` = " . intval($catId);
           }
       }

       $sql .= " ORDER BY `ordering` ASC";

       if($start && $limit) {
           
            $sql .= " LIMIT {$start}, {$limit}";
       }elseif($limit) {
            $sql .= " LIMIT {$limit}";
       }

       $articles = $this->db->getAll($sql);

       $result = array();
       foreach($articles as $k => $v) {

           $urls = json_decode($articles[$k]['urls'], true);

           if(isset($urls['urla']) && !empty($urls['urla'])) {
               $articles[$k]['redict'] = $urls['urla'];
           }

           unset($articles[$k]['urls']);

           $articles[$k]['introtext'] = str_replace('src="images/', 'src="http://182.92.131.108/images/', $articles[$k]['introtext']);
           $articles[$k]['images'] = str_replace('"image_intro":"images\/', '"image_intro":"http:\/\/182.92.131.108\/images\/', $articles[$k]['images']);
           $articles[$k]['images'] = str_replace('"image_fulltext":"images\/', '"image_fulltext":"http:\/\/182.92.131.108\/images\/', $articles[$k]['images']);

           $articles[$k]['images'] = json_decode($articles[$k]['images'], true);
       }

       if($id && !is_array($id)) {

           return isset($articles[0]) ? $articles[0] : array();
       }

       return $articles;
    }

    public function getArticleCount($catId = 0, $state = 1) {


       $catId = intval($catId);
       $state = intval($state);

       $sql = "SELECT count(`id`) as `count` FROM `jcms_content` WHERE `state` = '{$state}'";

       //栏目分类
       if($catId) {

           if(is_array($catId)) {
               $sql .= " AND `catid` IN(" . @mysql_real_escape_string(implode(',', $catId)) . ")";
           } else {
               $sql .= " AND `catid` = " . intval($catId);
           }
       }

       $counts = $this->db->getOne($sql);

       return isset($counts['count']) ? $counts['count'] : 0;
    }

    public function getCategory($id = 0, $parentId = 0, $published = 1, $extension = 'com_content') {

        $parentId = intval($parentId);
        $published = intval($published);
        $extension = @mysql_real_escape_string($extension);

        $sql = "SELECT `id`, `parent_id`, `lft`, `rgt`, `level`, `title`, `alias`, `description`, `metadesc` ,`published` FROM `jcms_categories`";
        $sql .= " WHERE `extension` = '{$extension}' AND `published` = '{$published}' AND `parent_id` != 0";


        if($id) {
 
            if(is_array($id)) {
                $sql .= " AND `id` IN(" . @mysql_real_escape_string(implode(',', $id)) . ")";
            } else {
                $sql .= " AND `id` = " . intval($id);
            }
        }

        if($parentId) {
            $sql .= " AND `parent_id` = '{$parentId}'";
        }

        $sql .= " ORDER BY `lft` ASC";

        $categories = $this->db->getAll($sql);

        foreach($categories as $k => $v) {
            
            $categories[$k]['description'] = str_replace('src="images/', 'src="http://182.92.131.108/images/', $categories[$k]['description']);
        }


        if($id && !is_array($id)) {

            return isset($categories[0]) ? $categories[0] : array();
        }

        return $categories;
    }

    public function getCategoryList($id) {

        $result = array();

        $maxtime = 10;
        do {
            $info = $this->getCategory($id);
            $result[] = $info;
            $id = $info['parent_id'];
            $maxtime--;

        } while($maxtime && $info['parent_id'] && $info['level'] > 1);

        return array_reverse($result);
    }

    public function getCategoryCount($parentId = 0, $published = 1, $extension = 'com_content') {

        $parentId = intval($parentId);
        $published = intval($published);
        $extension = @mysql_real_escape_string($extension);

        $sql = "SELECT COUNT(`id`) as `count` FROM `jcms_categories` WHERE `extension` = '{$extension}' AND `published` = '{$published}' AND `parent_id` != 0";
    
        if($parentId) {
            $sql .= " AND `parent_id` = '{$parentId}'";
        }
    
        $counts = $this->db->getOne($sql);
    
        return isset($counts['count']) ? $counts['count'] : 0;
    }
}
