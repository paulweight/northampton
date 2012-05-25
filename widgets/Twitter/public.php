<?php
	require_once('JaduTimedCache.php');
	$TWITTER_USERNAME = '%TWITTER_USERNAME%';
	$TWITTER_MAX_POSTS = '%TWITTER_MAXPOSTS%' != '' ? intval('%TWITTER_MAXPOSTS%') : 5;
	$TWITTER_TIMEOUT = 60 * 15;

	if(! function_exists(plural)) {
	function plural($num) {
		if ($num != 1)
			return "s";
	}
}

	if(! function_exists(getRelativeTime)) {

	function getRelativeTime($date) {
		$diff = time() - strtotime($date);
		if ($diff<60)
			return $diff . " second" . plural($diff) . " ago";
		$diff = round($diff/60);
		if ($diff<60)
			return $diff . " minute" . plural($diff) . " ago";
		$diff = round($diff/60);
		if ($diff<24)
			return "About ". $diff . " hour" . plural($diff) . " ago";
		$diff = round($diff/24);
		if ($diff<7)
			return $diff . " day" . plural($diff) . " ago";
		$diff = round($diff/7);
		if ($diff<4)
			return $diff . " week" . plural($diff) . " ago";
		return "on " . date("F j, Y", strtotime($date));
	}
}

	if ($TWITTER_USERNAME != '' && !strpos($TWITTER_USERNAME, 'TWITTER_USERNAME' )) {
		require_once("JaduTimedCache.php");
		$cache = new TimedCache('twitteruser', $TWITTER_USERNAME);
		$userExists = true;
		if ($cache->isEmpty()) {
			// check user exists before attempting to get feed
			$url = 'http://twitter.com/' . $TWITTER_USERNAME;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $url);
			if (defined('HTTP_PROXY') && HTTP_PROXY != '') {
				curl_setopt($ch, CURLOPT_PROXY, HTTP_PROXY);
				curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
				if (defined('HTTP_PROXY_PORT') && HTTP_PROXY_PORT != '') {
					curl_setopt($ch, CURLOPT_PROXYPORT, HTTP_PROXY_PORT);
				}
				if (defined('HTTP_PROXY_USER') && HTTP_PROXY_USER != '' && defined('HTTP_PROXY_PASS') && HTTP_PROXY_PASS != '') {
					require_once('utilities/JaduCryptBlowfish.php');
					$blowfish = new CryptBlowfish(HASH_SALT);
					curl_setopt($ch, CURLOPT_PROXYUSERPWD, HTTP_PROXY_USER . ':' . $blowfish->decrypt(base64_decode(HTTP_PROXY_PASS)));
					unset($blowfish);
				}
			}
			if (mb_strpos($url, 'https') === 0) {
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			}
			$xml = curl_exec($ch);
			if (curl_error($ch) || curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200) {
				$userExists = false;
			}
			curl_close($ch);
			$cache->setData($userExists, time() + (86400 * 30));
		}
		else {
			if ($cache->data !== true) {
				$userExists = false;
			}
		}
		
		if ($userExists) {
?>
	<div class="twitterFeed">
		<h3><a href="http://twitter.com/<?php print $TWITTER_USERNAME; ?>"><?php print $TWITTER_USERNAME; ?><br /> on Twitter</a></h3>
			<?php
				$tc = new TimedCache('twitter', $TWITTER_USERNAME . $TWITTER_MAX_POSTS);
				if ($tc->isEmpty()) {
					$url = 'http://twitter.com/statuses/user_timeline/' . $TWITTER_USERNAME . '.xml';
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_TIMEOUT, 5);
					if (defined('HTTP_PROXY') && HTTP_PROXY != '') {
						curl_setopt($ch, CURLOPT_PROXY, HTTP_PROXY);
						curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
						if (defined('HTTP_PROXY_PORT') && HTTP_PROXY_PORT != '') {
							curl_setopt($ch, CURLOPT_PROXYPORT, HTTP_PROXY_PORT);
						}
						if (defined('HTTP_PROXY_USER') && HTTP_PROXY_USER != '' && defined('HTTP_PROXY_PASS') && HTTP_PROXY_PASS != '') {
							require_once('utilities/JaduCryptBlowfish.php');
							$blowfish = new CryptBlowfish(HASH_SALT);
							curl_setopt($ch, CURLOPT_PROXYUSERPWD, HTTP_PROXY_USER . ':' . $blowfish->decrypt(base64_decode(HTTP_PROXY_PASS)));
							unset($blowfish);
						}
					}
					if (mb_strpos($url, 'https') === 0) {
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					}
					$response = curl_exec($ch);
					curl_close($ch);
					
					$buffer = '';
					if (!empty($response)) {
						$xml = new SimpleXMLElement($response);
						$pos = 0;
						if (isset($xml->status)) {
							foreach ($xml->status as $status) {
								$text = $status->text;
								$url = 'http://twitter.com/' . $TWITTER_USERNAME . '/status/' . $status->id;
								$dateString = getRelativeTime($status->created_at);
								
								// make links clickable
								$text = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]", "<a href=\"\\0\">\\0</a>", $text);
								// make #... clickable		
								$text = preg_replace('/#([0-9a-z_]+)/i', "<a href=\"http://search.twitter.com/search?q=%23\\1\">\\0</a>", $text);					
								// make @... clickable		
								$text = preg_replace('/@([0-9a-z_]+)/i', "<a href=\"http://twitter.com/\\1\">\\0</a>", $text);
								
								$buffer .= "<p>\n";
								$buffer .= $text. "\n";
								$buffer .= "<br /><a href=\"$url\" class=\"tweetDate\">$dateString</a>\n";
								$buffer .= "</p>\n";
								$pos++;
								if ($pos >= $TWITTER_MAX_POSTS) {
									break;
								}
							}
						}
					}
					$tc->setData($buffer, time() + $TWITTER_TIMEOUT);
					print $buffer;
				}
				else {
					print $tc->data;
				}
?>
			</div>
<?php 
		}
	}
?>