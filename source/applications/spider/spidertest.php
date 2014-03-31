<?php
defined('SYS_IN') or exit('Access Denied.');
class spidertest {
    function __construct() {
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
    }
	
    function init() {
		Base::load_sys_class("spider",'',0);
		$urls = array(
			0 => 'http://www.56ml.com/faxing/',
		);
		$spider = new spider($urls);
		$imageUrls = $spider->getImages();
		//print_r($imageUrls);
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
}
?>