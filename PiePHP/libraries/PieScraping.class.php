<?php

class PieScraping {
	
	static function crawl($URL) {
		$file = '';
		@$file = join('', file($URL));
		return $file;
	}
	
	static function post($URL, $postData, &$headers = '') {
		$parse = parse_url($URL);
		$host = $parse['host'];
	    $socket = fsockopen($host, 80, $errorNumber, $errorString);
	    if (!$socket) {
	        echo "$errorString ($errorNumber)<br/>\n".$socket;
	    }  else {
	        $command =
				"POST $URL  HTTP/1.1\r\n".
				"Host: $host\r\n".
				"User-Agent: PHP Script\r\n".
				"Content-Type: application/x-www-form-urlencoded\r\n".
				"Content-Length: ".strlen($postData)."\r\n".
				"Connection: close\r\n\r\n".
				$postData;
	        fwrite($socket, $command);
			$response = '';
			while (!feof($socket)) $response .= fgets($socket, 128);
			list($headers, $responseContent) = split("\r\n\r\n", $response);
			if (strpos($headers, "Transfer-Encoding: chunked") !== false) {
				$auxiliary = split("\r\n", $responseContent);
				for ($i = 0; $i < count($auxiliary); $i++) if ($i % 2 == 0) $auxiliary[$i] = '';
				$responseContent = implode('', $auxiliary);
			}
			return chop($responseContent);
		}
	}
	
	
	static function match($pattern, &$text) {
		preg_match($pattern, $text, $match);
		return trim($match[1]);
	}
	
	static function matches($pattern, &$text, $matchCount = 0) {
		if (!$text) return array();
		preg_match_all($pattern, $text, $matches);
		if ($matchCount == 2 || count($matches) == 2) return $matches[1];
		if ($matchCount == 1 || count($matches) == 1) return $matches[0];
		return $matches;
	}
	
	static function tagContent($tagName, &$text) {
		/*if ($tagStart = strpos('<'.$tagName, $text)) {
			echo 'TagStart: '.$tagStart;
			$contentStart = strpos('>', $text, $tagStart) + 1;
			echo 'ContentStart: '.$tagStart;
			$contentEnd = strpos('</'.$tagName, $contentStart);
			echo 'ContentEnd: '.$tagStart;
			return substr($text, $contentStart, $contentEnd - $contentStart);
		}
		return '';*/
		return Match('/<'.$tagName.'[^>]*>(.*?)<\/'.$tagName.'>/msi', $text);
	}
	
	
	static function noBold($value) {
		return trim(preg_replace('/<[\/]?b>/msi', '', $value));
	}
	
	static function noStrong($value) {
		return trim(preg_replace('/<[\/]?strong>/msi', '', $value));
	}
	
	static function noTags($value) {
		return trim(preg_replace('/<[^>]*>/msi', '', $value));
	}
	
	static function p($value) {
		echo '<pre>';
		print_r($value);
		echo '</pre>';
	}
	
	static function googleResults($query, $max = 1000) {
		$file = '';
		$start = 0;
		$results = array();
		do {
			$file = Crawl('http://www.google.com/search?q='.urlencode($query).'&num=100&hl=en&start='.$start.'&sa=N');
			$resultMatches = Matches('/<a class=l href="(.*?)<\/span><nobr>/msi', $file);
			while (list($i, $result) = each($resultMatches)) {
				if (count($results) < $max) $results[] = array(
					'URL' => Match('/^(.*?)"/msi', $result),
					'title' => NoBold(Match('/^[^>]+>(.*?)<\/a>/msi', $result)),
					'description' => NoTags(Match('/<font size=\-1>(.*?)(<span class=a>|$)/msi', $result))
				);
			}
			$start += 100;
		} while (count($results) < $max && preg_match('/&num=100&hl=en&ie=UTF-8&start='.$start.'&sa=N/msi', $file));
		return $results;
	}
	
	static function yahooResults($query, $max = 1000) {
		$file = '';
		$start = 0;
		$results = array();
		do {
			$file = Crawl('http://search.yahoo.com/search?p='.urlencode($query).'&ei=UTF-8&n=100&pstart=1&fr=yfp-t-501&b='.($start + 1));
			$resultMatches = Matches('/<a class=yschttl(.*?)(<li>|<\/ol>)/msi', $file, 1);
			while (list($i, $result) = each($resultMatches)) {
				if (count($results) < $max) $results[] = array(
					'URL' => Match('/href="(.*?)"/msi', $result),
					'title' => NoBold(Match('/^[^>]+>(.*?)<\/a>/msi', $result)),
					'description' => NoTags(Match('/<div class=yschabstr>(.*?)<\/div>/msi', $result))
				);
			}
			$start += 100;
		} while (count($results) < $max && preg_match('/&fr=yfp-t-501&b='.($start + 1).'/msi', $file));
		return $results;
	}
	
	static function mSNResults($query, $max = 1000) {
		$file = '';
		$start = 0;
		$results = array();
		do {
			$file = Crawl('http://search.msn.com/results.aspx?q='.urlencode($query).'&first='.($start + 1).'&FORM=PERE'.($start ? $start / 10 : ''));
			$free = preg_replace('/SPONSORED SITES.*$/msi', '', $file);
			$resultMatches = Matches('/<li[^>]*><h3>(.*?)<\/ul><\/li>/msi', $free, 1);
			while (list($i, $result) = each($resultMatches)) {
				if (count($results) < $max) $results[] = array(
					'URL' => Match('/href="(.*?)"/msi', $result),
					'title' => NoStrong(Match('/<a[^>]+>(.*?)<\/a>/msi', $result)),
					'description' => NoTags(Match('/<p>(.*?)<\/p>/msi', $result))
				);
			}
			$start += 10;
		} while (count($results) < $max && preg_match('/>'.($start / 10).'<\/a><\/li>/msi', $file));
		return $results;
	}
	
	static function askResults($query, $max = 1000) {
		$file = '';
		$start = 0;
		$page = 1;
		$results = array();
		$URL = 'http://www.ask.com/web?q=M-X+Video+Network&qsrc=0&o=0&l=dir'.urlencode($query).'&qsrc=0&o=0&l=dir';
		do {
			$file = Crawl($URL);
			$resultMatches = Matches('/(<div id="r_t[0-9]".*?)>Save<\/a>/msi', $file, 1);
			for ($i = 0; list(, $result) = each($resultMatches); $i++) {
				if (count($results) < $max) $results[] = array(
					'URL' => Match('/id="r'.$i.'_t"[^>]+ href="(.*?)"/msi', $result),
					'title' => NoBold(Match('/<a id="r'.$i.'_t"[^>]+>(.*?)<\/a>/msi', $result)),
					'description' => NoTags(Match('/<div[^>]+ id="r'.$i.'_a">(.*?)<\/div>/msi', $result))
				);
			}
			$start += 10;
			$page++;
		} while (count($results) < $max && ($URL = str_replace(' ', '%20', Match('/>Results Page<.*<a[^>]+href="([^"]+)"[^>]+>Next/msi', $file))));
		return $results;
	}
	
	static function intersectFraction($a, $b) {
		$a = preg_split('/[^a-z^0-9]+/', preg_replace('/^[^a-z^0-9]*(.*?)[^a-z^0-9]*$/', '$1', strtolower($a)));
		$b = preg_split('/[^a-z^0-9]+/', preg_replace('/^[^a-z^0-9]*(.*?)[^a-z^0-9]*$/', '$1', strtolower($b)));
		return count(array_intersect($a, $b)) / max(count($a), count($b));
	}
	
	static function substringFraction($haystack, $needles) {
		$needles = preg_split('/[^a-z^0-9]+/', preg_replace('/^[^a-z^0-9]*(.*?)[^a-z^0-9]*$/', '$1', strtolower($needles)));
		$haystack = strtolower($haystack);
		$substrings = 0;
		while (list($i, $needle) = each($needles)) {
			if (strpos($haystack, $needle) !== false) $substrings++;
		}
		return $substrings / count($needles);
	}
	
}
?>