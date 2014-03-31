<?php
class spider {
	private $urls = array();
	function spider($urls = array()) {
		$this->urls = $urls;
	}
	
	function getContent($url) {
		$content = $this->sendHttpRequest($url);
		return $content;
	}
	
	function getImages() {
		$imageUrls = array();
		$imgPattern = '/<img\s*src=\"([^\"]*)\"[^>]*\/?>/is';
		if(!empty($this->urls)) {
			foreach ($this->urls as $k=>$url) {
				$content = $this->getContent($url);
				preg_match_all($imgPattern, $content, $matches);
				$imageUrls[$k] = $matches[1];
			}
		}
		return $imageUrls;
	}
	
	public function sendHttpRequest($url, $javascript_loop = 0, $timeout = 5) {
	    $url = str_replace("&amp;","&",urldecode(trim($url)));
	    $ch = curl_init();
	    curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
	    curl_setopt($ch,CURLOPT_URL,$url);
	    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
	    curl_setopt($ch,CURLOPT_ENCODING,"");
	    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	    curl_setopt($ch,CURLOPT_AUTOREFERER,true);
	    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
	    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	    curl_setopt($ch,CURLOPT_TIMEOUT,$timeout);
	    curl_setopt($ch,CURLOPT_MAXREDIRS,10);
	    $content = curl_exec($ch);
	    $response = curl_getinfo($ch);
	    curl_close($ch);
	    
	    if ($response['http_code']==301||$response['http_code']==302) {
	        ini_set("user_agent","Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
	        if ($headers = get_headers($response['url'])) {
	            foreach($headers as $value) {
	                if (substr(strtolower($value),0,9)=="location:")
	                    return $this->sendHttpRequest(trim(substr($value,9,strlen($value))));
	            }
	        }
	    }
	    if ((preg_match("/>[[:space:]]+window\.location\.replace\('(.*)'\)/i",$content,$value)||preg_match("/>[[:space:]]+window\.location\=\"(.*)\"/i",$content,$value))&&$javascript_loop<5) {
	        return $this->sendHttpRequest($value[1],$javascript_loop+1);
	    } else {
			return $content;
	    }
	}
	
}
?>