<?php

/**
 * creates a rfc-confirming http redirect
 * and sets a location header.
 * by default, this function exits 
 * after setting the header.
 * if you like your script to be
 * continued, use the second parameter.
 *
 * Usage:
 *
 * // relative to http://example.com/path/index.html
 * http_redirect('?foo=bar'); // => http://example.com/path/index.html?foo=bar
 * http_redirect('another/file.html'); // => http://example.com/path/another/file.html
 *
 * // absolute to http://example.com/path/to/index.html
 * http_redirect('/another/file.html'); // => http://example.com/another/file.html
 *
 * // relative to the schema while on https://example.com
 * http_redirect('//another.domain.com/foo/bar.html'); // => https://another.domain.com/foo/bar.html
 * 
 * @param string $uri an absolute, relative of complete url
 * @param boolean $exit (optional) whether to redirect immediatly.
 * 		defaults to true.
 * @param boolean $debug (optional) if true, will produce debug output
 * 		and exit immediatly.
 * @return void
 * @author glaszig at gmail dot com
 */
function http_redirect($uri, $exit = true, $debug = false) {

	$template = "{:schema}://{:host}{:port}/{:path}{:query}";

	$uri = trim($uri);
	$path = $_SERVER['SCRIPT_NAME'];
	
	// simple get parameter substitution
	foreach($_GET as $key => $val) {
		if(!is_array($val)) {
			$uri = str_replace(":$key", $val, $uri);
		}
	}
	
	// handle pure query string
	if($uri{0} == '?') {
		$query = substr($uri, 1); // the query string without its first character
	}
	// handle http(s) redirects
	// that is external redirects
	elseif(preg_match('~^https?://~i', $uri)) {
		$template = $uri;
	}
	// handle schema-relative redirects
	elseif(preg_match('~^//~', $uri)) {
	    $template = "{:schema}:{$uri}";
	}
	// handle file redirects with options query string
	else {
		list($path, $query) = explode('?', $uri);
		
		// relative path redirect
		if($path{0} != '/') {
			$path = dirname($_SERVER['SCRIPT_NAME'])."/$path";
		}
	}
	
	$data = array(
		'schema' => 'http'.($_SERVER['HTTPS']=='on'?'s':''),
		'host' => $_SERVER['HTTP_HOST'],
		'port' => $_SERVER['SERVER_PORT'] != 80 ? ":{$_SERVER['SERVER_PORT']}" : '',
		'path' => ltrim($path, '/'),
		'query' => !is_null($query) ? "?$query" : ''
	);

	// compile the template
	foreach($data as $key => $val) {
		$template = str_replace("{:$key}", $val, $template);
	}

	if($debug) {
		$message = "<pre>=== REDIRECT DEBUG ===\r\n\r\n$template\r\n\r\n";
		$message.= htmlentities(print_r($_SERVER, true)).'</pre>';
		$message = "<p>$message</p>";
		echo $message;
		return;
	}

	// set the header
	header("Location: $template", true, 302);
	if($exit === true) exit;
	
}
?>
