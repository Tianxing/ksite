<?php
class Page_ProductModel {

    private $data = NULL;

    public function __construct() {

       $this->data = new Data_CmsModel();
    }

    public function kec() {

        $pageInfo = $this->data->getArticle(array(47, 48, 49, 50));

        $result = array();
        foreach($pageInfo as $k => $v) {
            
            //low price
            $v['introtext'] = str_replace(array('<p>', '</p>'), '', $v['introtext']);
            if($v['id'] == 47) {
                $result['price'] = $v['introtext'];
                continue;
            }
            
            //recommend server
            if($v['id'] == 48 || $v['id'] == 49 || $v['id'] == 50) {

                $v['introtext'] = htmlspecialchars_decode($v['introtext']);

                preg_match('/<price>(.*?)<\/price>/', $v['introtext'], $matches);
                $price = $matches[1];

                preg_match('/<cpu>(.*?)<\/cpu>/', $v['introtext'], $matches);
                $cpu = $matches[1];

                preg_match('/<mem>(.*?)<\/mem>/', $v['introtext'], $matches);
                $mem = $matches[1];

                preg_match('/<dist>(.*?)<\/dist>/', $v['introtext'], $matches);
                $dist = $matches[1];

                $url = "http://www.ksyun.com/?cpu=" . $cpu . '&mem=' . $mem . '&dist=' . $dist;
                $result['recommend'][] = array('title' => $v['title'], 'url' => $url, 'price' => $price , 'cpu' => $cpu, 'mem' => $mem, 'dist' => $dist);

                continue;
            }
        }

        $result['userCase'] = $this->data->getArticle(0, 87);
        $result['buy'] = $this->data->getArticle(0, 88);
        $result['guide'] = $this->data->getArticle(0, 89);
        $result['question'] = $this->data->getArticle(0, 90);

        return $result;
    }


    public function left(){

        $result = array(
            'computer' => array(
                'title' => '计算机与网络',
                'children' => array(
                    array(
                        'title' => '云服务器',
                        'url' => '/product/kec',
                    ),
                    array(
                        'title' => '云服务器',
                        'url' => '/product/kec',
                    ),
                    array(
                        'title' => '云服务器',
                        'url' => '/product/kec',
                    ),
                 ),
             ),
            'database' => array(
                'title' => '数据库',
                'children' => array(
                    array(
                        'title' => '云服务器',
                        'url' => '/product/kec',
                    ),
                    array(
                        'title' => '云服务器',
                        'url' => '/product/kec',
                    ),
                    array(
                        'title' => '云服务器',
                        'url' => '/product/kec',
                    ),
                ),
             ),
            'store' => array(
                'title' => '存储与内容传输',
                'children' => array(
                    array(
                        'title' => '云服务器',
                        'url' => '/product/kec',
                    ),
                    array(
                        'title' => '云服务器',
                        'url' => '/product/kec',
                    ),
                    array(
                        'title' => '云服务器',
                        'url' => '/product/kec',
                    ),
                ),
            ),
            'safe' => array(
                'title' => '安全与监控',
                'children' => array(
                    array(
                        'title' => '云服务器',
                        'url' => '/product/kec',
                    ),
                    array(
                        'title' => '云服务器',
                        'url' => '/product/kec',
                    ),
                    array(
                        'title' => '云服务器',
                        'url' => '/product/kec',
                    ),
                ),
            ),
        );
        
        return $result;
    }
}
