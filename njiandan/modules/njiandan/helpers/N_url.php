<?php defined('SYSPATH') OR die('No direct access allowed.');

class url extends url_Core {
    public static function admin_redirect($uri = '', $method = '') {
        $uri = trim($uri, '/');
        return parent::redirect('/' . Kohana::config('njiandan.admin_uri') . '/' . $uri, $method);
    }

	/**
	 * Fetches an absolute site URL based on a URI segment.
	 *
	 * @param   string  site URI to convert
	 * @param   string  non-default protocol
	 * @return  string
	 */
    public static function admin_site($uri = '', $protocol = FALSE) {
        $uri = trim($uri, '/');
        $uri = '/' . Kohana::config('njiandan.admin_uri') . '/' . $uri;
        return parent::site($uri, $protocol);
    }


	/**
	 * Base URL, with or without the index page.
	 *
	 * If protocol (and core.site_protocol) and core.site_domain are both empty,
	 * then
	 *
	 * @param   boolean  include the index page
	 * @param   boolean  non-default protocol
	 * @return  string
	 */
	public static function base($index = FALSE, $protocol = FALSE) {
		if ($protocol == FALSE) {
			// Use the default configured protocol
			$protocol = Kohana::config('core.$protocolsite_protocol');
		}

		// Load the site domain
		$site_domain = (string) Kohana::config('core.site_domain', TRUE);
        $base_url = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
		if ($protocol == FALSE) {
			if ($site_domain === '') {
			} else if ( $site_domain[0] === '/') {
				// Use the configured site domain
				$base_url = $site_domain;
			} else {
				// Guess the protocol to provide full http://domain/path URL
				$base_url = ((empty($_SERVER['HTTPS']) OR $_SERVER['HTTPS'] === 'off') ? 'http' : 'https').'://'.$site_domain;
			}
		} else {
			if ($site_domain === '') {
			    $base_url = $protocol.'://'.$_SERVER['HTTP_HOST'] . $base_url;
			} else if ($site_domain[0] === '/') {
				// Guess the server name if the domain starts with slash
				$base_url = $protocol.'://'.$_SERVER['HTTP_HOST'].$site_domain;

			} else {
				// Use the configured site domain
				$base_url = $protocol.'://'.$site_domain;
			}
		}
		if ($index === TRUE AND $index = Kohana::config('core.index_page')) {
			// Append the index page
			$base_url = rtrim($base_url, '/') . '/' . $index;
		}

		// Force a slash on the end of the URL
		return rtrim($base_url, '/').'/';
	}

    public static function admin_file($file = '') {
    
    
    }

	public static function themesPath() {
		return self::base() . WEBROOT . '/themes/' . Kohana::config('core.theme');
	}
}
