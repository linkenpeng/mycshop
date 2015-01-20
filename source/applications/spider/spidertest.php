<?php
defined('SYS_IN') or exit('Access Denied.');
class spidertest {
    function __construct() {
        
    }	
    
    /*
	     采集大量的发型图片
	    网友可以给图片选喜欢，不喜欢
	    网友可以给图片分类，选择一个标签
	    最后，每个图片都会有若干个标签，有喜欢的人的个数，不喜欢的人的个数
	    网站分两栏，左边热门图片(按喜欢的人数，标签排序)，右边随机排序的图片
	    
	    发展壮大以后，可以与高级一点的美发机构合作，每个理发机构提供一台平板电脑，
	    每位顾客到店里之后利用平板选择自己喜欢的发型，让美发师给自己造型。
	    盈利，靠美发机构的团购，靠与头发有关的广告。
	*/
    function hair() {
		Base::load_sys_class("spider",'',0);
		$urls = array(
			0 => 'http://www.56ml.com/faxing/',
		);
		$spider = new spider($urls);
		$imageUrls = $spider->getImages();
		foreach($imageUrls as $k=>$imageUrl) {
			$hosts = parse_url($urls[$k]);
			$website = $hosts['scheme'].'://'.$hosts['host'];
			foreach ($imageUrl as $imgsrc) {
				// 不需要的图片
				if(strpos($imgsrc,'uploadfile') === false)	continue; 
				// 补全网址
				$imgsrc = strpos($imgsrc,'http://') !== false ? $imgsrc : 
				(strpos($imgsrc, '\/') !== false ? $website.$imgsrc : $website.'/'.$imgsrc);
				
				echo '<img src="'.$imgsrc.'" />';
			}
		}
    }
    
    /**
     * 分析股票资金流量
     */
    function stock() {
    	$stockno = empty($_GET['stockno']) ? 'dpzjlx' : trim($_GET['stockno']);
    	require_once(ROOT_PATH.'/source/librarys/phpQuery/phpQuery.php'); 	
    	
    	$total = array(
    			2 => array('data' => 0,'name' => '主力'), 
    			4 => array('data' => 0,'name' => '超大单'),
    			6 => array('data' => 0,'name' => '大单'),
    			8 => array('data' => 0,'name' => '中单'),
    			10 => array('data' => 0,'name' => '小单'),
		);
    	$rows = array();
    	
    	phpQuery::newDocumentFile('http://data.eastmoney.com/zjlx/'.$stockno.'.html');
		$stockName = mb_convert_encoding(pq(".tit a")->html(), 'utf-8', 'gb2312');
    	$trList = pq("#content_zjlxtable table tbody tr");
    	foreach($trList as $tr) {
    		$row = array();
    		$td = pq($tr)->find('td');    		
    		$row[0] = trim(pq($td)->eq(0)->html());
    		$spanList = pq($td)->find('span');
    		$i = 0;
    		foreach($spanList as $span) {
    			$data = pq($span)->html();
    			$data = mb_convert_encoding($data, 'utf-8', 'gb2312');
    			$data = $this->_formatRate($data);    			
    			if(array_key_exists($i, $total)) {
    				$total[$i]['data'] += $data;
    			}    			
    			$row[] = $data;
    			$i++;
    		}
    		$rows[] = $row;
    	}
    	
    	//output
		echo '<b>'.$stockName.'</b><br/><br/>'; 
    	$all = 0;
    	foreach($total as $val) {
    		$color = $val['data'] > 0 ? '#F00' : '#090';
    		$fdata = $val['data']/100000000;
    		echo '<b style="color:'.$color.'">'.$val['name'].': '.$fdata.'亿'.'</b><br>';
    		$all += $fdata;
    	}
    	echo '<b>全部：'.$all.'亿'.'</b><br/><br/>';    	
    	echo 'Time: '.(mtime() - START_TIME);
    }
    
    private function _formatRate($data) {
    	$units = array('亿'=>100000000, '万'=>10000);
    	foreach($units as $u=>$val) {
    		if(strpos($data, $u) !== false) {
    			$data = str_replace($u, '', $data);
    			$data = floatval($data);
    			$data = $data * $val;
    		}
    	}
    	
    	return $data;
    }
    
}