<?php

/**
 * get Facebook page number of likes
 * @param string pageID
 * @return int
 */
function getFbCount($pageID)
{
	$fans = get_transient('cfFbLikes');
	if ($false !== $fans) {
		$xml = @simplexml_load_file("http://api.facebook.com/restserver.php?method=facebook.fql.query&query=SELECT%20fan_count%20FROM%20page%20WHERE%20page_id=".$pageID."") or die ("a lot");
		$fans = $xml->page->fan_count;
		set_transient('cfFbLikes', $fans, 600);
	}
	echo $fans;
}

/**
 * get count of Twitter followers
 * @param string Twitter username
 * @return int
 */
function getTwitCount($user='codeforest'){
    $apiurl = "http://api.twitter.com/1/users/show.json?screen_name={$user}";

    $transientKey = "cf_twitter_followers";

    $cached = get_transient($transientKey);

    if (false !== $cached) {
        return $cached;
    }

    // Request the API data, using the constructed URL
    $remote = wp_remote_get(esc_url($apiurl));

    // If the API data request results in an error, return
    // some number :)
    if (is_wp_error($remote)) {
        return '256';
    }
    $data = json_decode( $remote['body'] );
    $output = $data->followers_count;
    set_transient($transientKey, $output, 600);
    
    return $output;
}

/**
 * get RSS readers count
 * @param string Feedburner id
 * @return int
 */
function getRssCount($feedburnerID)
{
	$transientKey = "cf_rss_followers";

    $rssCount = get_transient($transientKey);

    if (false !== $cached) {
    	$fbUrl="http://feedburner.google.com/api/awareness/1.0/GetFeedData?uri=" . $feedburnerID;
		$data = wp_remote_get($fbUrl);
	
		$grid = new SimpleXMLElement($data['body']);
		$rssCount = $grid->feed->entry['circulation'];
	
		set_transient('cf_rss_followers', $rssCount, 600);
    }

	return $rssCount;
}