<?php

class trig_spider {
	private $urls = array();

	function spider($urls = array()) {
		$this->urls = $urls;
	}

	function getContent($url) {
		$content = trig_curl_request::send($url);
		return $content;
	}

	function getImages() {
		$imageUrls = array();
		$imgPattern = '/<img\s*src=\"([^\"]*)\"[^>]*\/?>/is';
		if (!empty($this->urls)) {
			foreach ($this->urls as $k => $url) {
				$content = $this->getContent($url);
				preg_match_all($imgPattern, $content, $matches);
				$imageUrls[$k] = $matches[1];
			}
		}
		return $imageUrls;
	}
}