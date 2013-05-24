<?php defined('SYSPATH') OR die('No direct access allowed.');

class FeedTag_Core {

    public static function rss2() {
        $posts = self::recent_list();
        $home_url = Kohana::config('njiandan.site_url');
        $base = url::base();
        $last_updated = '';
        if (isset($posts[0])) {
            $last_updated = date('r', $posts[0]['date']);
        }

        $output = '<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	>
';
    $output .= '
<channel>
	<title>' . Kohana::config('njiandan.site_title') . '</title>
	<atom:link href="' . $home_url . url::site('feed') . '" rel="self" type="application/rss+xml" />
	<link>' . $home_url . $base . '</link>
	<description>' . Kohana::config('njiandan.site_description') . '</description>
	<pubDate>' . $last_updated . '</pubDate>
	<generator>http://www.njiandan.com</generator>
	<language>en</language>
	<sy:updatePeriod>hourly</sy:updatePeriod>
	<sy:updateFrequency>1</sy:updateFrequency>';

    foreach($posts as $post) {
        $output .= '
        <item>
		<title>' . $post['title'] . '</title>
		<link>' . $home_url . url::site($post['link']) . '</link>
		<comments>' . $home_url . html::anchor($post['link']) . '#comments</comments>
		<pubDate>' . date('r', $post['date']) . '</pubDate>
		<dc:creator>' . $post['author'] . '</dc:creator>';
		if (!empty($post['category'])) {
		    $output .= '
		<category><![CDATA[' . $post['category'] . ']]></category>';
		}
		$output .= '
		<guid isPermaLink="false">' . $home_url . url::base() . 'index.php/' . Kohana::config('njiandan.post_uri') . '/' . $post['id'] . '</guid>
        
		<description><![CDATA[' . $post['content'] . '
]]></description>
			<content:encoded><![CDATA[' . $post['content'] . '
]]></content:encoded>
		</item>';
	    }
	    $output .= '
	</channel>
</rss>
    ';

        return $output;
    }


    public static function atom() {
        $home_url = Kohana::config('njiandan.site_url');
        $base = url::base();
        $last_updated = '';
        if (isset($posts[0])) {
            $last_updated = date('c', $last_updated);
        }

        $output = '<?xml version="1.0" encoding="UTF-8"?>
<feed
  xmlns="http://www.w3.org/2005/Atom"
  xmlns:thr="http://purl.org/syndication/thread/1.0"
  xml:lang="en"
  xml:base="' . $home_url . url::site('feed/atom') . '"
   >
	<title type="text">' . Kohana::config('njiandan.site_title') . '</title>
	<subtitle type="text">' . Kohana::config('njiandan.site_description') . '</subtitle>

	<updated>' . $last_updated . '</updated>
	<generator uri="http://njiandan.com" version="' . Kohana::config('njiandan.version') . '">Njiandan</generator>
	<link rel="alternate" type="text/html" href="' . $home_url . '" />
	<id>' . $home_url . url::site('feed/atom') . '</id>
	<link rel="self" type="application/atom+xml" href="' . $home_url . url::site('feed/atom') . '" />';
        $posts = self::recent_list();
        foreach($posts as $post) {
            $post_link = $home_url . url::site($post['link']);
            $output .= '
		<entry>
		<author>
			<name>' . $post['author'] . '</name>
		</author>
		<title type="html"><![CDATA[' . $post['title'] . ']]></title>
		<link rel="alternate" type="text/html" href="' . $post_link . '" />
		<id>' . $home_url . url::base() . 'index.php/' . Kohana::config('njiandan.post_uri') . '/' . $post['id'] . '</id>
		<updated>' . $last_updated . '</updated>
		<published>' . $last_updated . '</published>';
		    if (!empty($post['category'])) {
		        $output .= '
		<category scheme="' . $home_url . '" term="' . $post['category'] . '" />';
		    }
		    $output .= '
		<summary type="html"><![CDATA[' . $post['content'] . '
]]></summary>
		<content type="html" xml:base="' . $post_link . '"><![CDATA[' . $post['content'] . '
]]></content>
		<link rel="replies" type="text/html" href="' . $post_link . '#comments" thr:count="' . $post['comment_count'] . '"/>
		<thr:total>' . $post['comment_count'] . '</thr:total>
        </entry>';
        }
        $output .= '
</feed>';

	    return $output;
    }


    /*
        get the recent post and page
    */
    public static function recent_list() {
        $results = array();
        $posts = ORM::factory('post')->where('status', 1)->limit(15)->find_all();
        foreach($posts as $post) {
            $results[$post->date] = array
            (
                'id' => $post->id,
                'author' => $post->user->username,
                'date' => $post->date,
                'title' => $post->title,
                'content' => $post->content,
                'link' => $post->link,
                'category' => $post->diagram->title,
                'comment_count' => count($post->comments),
            );
        }

        $pages = ORM::factory('diagram')->where('type', 'page')->orderby(array('date'=>'DESC'))->limit(15)->find_all();
        foreach($pages as $page) {
            $page_key = self::_get_unique_key($results, $page->date);
            $results[$page_key] = array
            (
                'id' => $page->id,
                'author' => $page->user->username,
                'date' => $page->date,
                'title' => $page->title,
                'content' => $page->content,
                'link' => $page->uri,
                'category' => '',
                'comment_count' => count($page->comments),
            );
        }
        krsort($results);
        return array_slice($results, 0, 15);
    }

    protected static function _get_unique_key($array, $key) {
        if (isset($array[$key])) {
            self::_get_unique_key($array, $key - 1);
        } else {
            return $key;
        }
    }
}
