<?php
defined('SYS_IN') or exit('Access Denied.');
class spidertest {
    function __construct() {
        /*
		�ɼ������ķ���ͼƬ
		���ѿ��Ը�ͼƬѡϲ������ϲ��
		���ѿ��Ը�ͼƬ���࣬ѡ��һ����ǩ
		���ÿ��ͼƬ���������ɸ���ǩ����ϲ�����˵ĸ�������ϲ�����˵ĸ���
		��վ���������������ͼƬ(��ϲ������������ǩ����)���ұ���������ͼƬ
		
		��չ׳���Ժ󣬿�����߼�һ�����������������ÿ���������ṩһ̨ƽ����ԣ�
		ÿλ�˿͵�����֮������ƽ��ѡ���Լ�ϲ���ķ��ͣ�������ʦ���Լ����͡�
		ӯ�����������������Ź�������ͷ���йصĹ�档
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
				// ����Ҫ��ͼƬ
				if(strpos($imgsrc,'uploadfile') === false)	continue; 
				// ��ȫ��ַ
				$imgsrc = strpos($imgsrc,'http://') !== false ? $imgsrc : 
				(strpos($imgsrc, '\/') !== false ? $website.$imgsrc : $website.'/'.$imgsrc);
				
				echo '<img src="'.$imgsrc.'" />';
			}
		}
    }
}
?>