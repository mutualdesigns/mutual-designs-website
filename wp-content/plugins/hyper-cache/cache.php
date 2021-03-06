<?php



global $hyper_cache_stop;



$hyper_cache_stop = false;



// Do not cache post request (comments, plugins and so on)

if ($_SERVER["REQUEST_METHOD"] == 'POST') return false;



// Try to avoid enabling the cache if sessions are managed with request parameters and a session is active

if (defined(SID) && SID != '') return false;



$hyper_uri = $_SERVER['REQUEST_URI'];

$hyper_qs = strpos($hyper_uri, '?');



if ($hyper_qs !== false) {

    if ($hyper_cache_strip_qs) $hyper_uri = substr($hyper_uri, 0, $hyper_qs);

    else if (!$hyper_cache_cache_qs) return false;

}



if (strpos($hyper_uri, 'robots.txt') !== false) return false;



// Checks for rejected url

if ($hyper_cache_reject !== false) {

    foreach($hyper_cache_reject as $uri) {

        if (substr($uri, 0, 1) == '"') {

            if ($uri == '"' . $hyper_uri . '"') return false;

        }

        if (substr($hyper_uri, 0, strlen($uri)) == $uri) return false;

    }

}



if ($hyper_cache_reject_agents !== false) {

    $hyper_agent = strtolower($_SERVER['HTTP_USER_AGENT']);

    foreach ($hyper_cache_reject_agents as $hyper_a) {

        if (strpos($hyper_agent, $hyper_a) !== false) return false;

    }

}



// Do nested cycles in this order, usually no cookies are specified

if ($hyper_cache_reject_cookies !== false) {

    foreach ($hyper_cache_reject_cookies as $hyper_c) {

        foreach ($_COOKIE as $n=>$v) {

            if (substr($n, 0, strlen($hyper_c)) == $hyper_c) return false;

        }

    }

}



// Do not use or cache pages when a wordpress user is logged on



foreach ($_COOKIE as $n=>$v) {

// If it's required to bypass the cache when the visitor is a commenter, stop.

    if ($hyper_cache_comment && substr($n, 0, 15) == 'comment_author_') return false;



    // SHIT!!! This test cookie makes to cache not work!!!

    if ($n == 'wordpress_test_cookie') continue;

    // wp 2.5 and wp 2.3 have different cookie prefix, skip cache if a post password cookie is present, also

    if (substr($n, 0, 14) == 'wordpressuser_' || substr($n, 0, 10) == 'wordpress_' || substr($n, 0, 12) == 'wp-postpass_') {

        return false;

    }

}



// Do not cache WP pages, even if those calls typically don't go throught this script

if (strpos($hyper_uri, '/wp-') !== false) return false;



// Multisite

if (function_exists('is_multisite') && is_multisite() && strpos($hyper_uri, '/files/') !== false) return false;



$hyper_uri = $_SERVER['HTTP_HOST'] . $hyper_uri;



// The name of the file with html and other data

$hyper_cache_name = md5($hyper_uri);

$hc_file = $hyper_cache_path . $hyper_cache_name . hyper_mobile_type() . '.dat';



if (!file_exists($hc_file)) {

    hyper_cache_start(false);

    return;

}



$hc_file_time = @filemtime($hc_file);

$hc_file_age = time() - $hc_file_time;



if ($hc_file_age > $hyper_cache_timeout) {

    hyper_cache_start();

    return;

}



$hc_invalidation_time = @filemtime($hyper_cache_path . '_global.dat');

if ($hc_invalidation_time && $hc_file_time < $hc_invalidation_time) {

    hyper_cache_start();

    return;

}



// Load it and check is it's still valid

$hyper_data = @unserialize(file_get_contents($hc_file));



if (!$hyper_data) {

    hyper_cache_start();

    return;

}



if ($hyper_data['type'] == 'home' || $hyper_data['type'] == 'archive') {



    $hc_invalidation_archive_file =  @filemtime($hyper_cache_path . '_archives.dat');

    if ($hc_invalidation_archive_file && $hc_file_time < $hc_invalidation_archive_file) {

        hyper_cache_start();

        return;

    }

}



// Valid cache file check ends here



if ($hyper_data['location']) {

    header('Location: ' . $hyper_data['location']);

    flush();

    die();

}



// It's time to serve the cached page

if (array_key_exists("HTTP_IF_MODIFIED_SINCE", $_SERVER)) {

    $if_modified_since = strtotime(preg_replace('/;.*$/', '', $_SERVER["HTTP_IF_MODIFIED_SINCE"]));

    if ($if_modified_since >= $hc_file_time) {

        header("HTTP/1.0 304 Not Modified");

        flush();

        die();

    }

}



// Now serve the real content



// True if user ask to NOT send Last-Modified

header('Cache-Control: no-cache, must-revalidate, max-age=0');

header('Pragma: no-cache');

header('Expires: Wed, 11 Jan 1984 05:00:00 GMT');

if (!$hyper_cache_lastmodified) {

    header('Last-Modified: ' . date("r", $hc_file_time));

}



header('Content-Type: ' . $hyper_data['mime']);

if ($hyper_data['status'] == 404) header("HTTP/1.1 404 Not Found");



// Send the cached html

if ($hyper_cache_gzip && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false && strlen($hyper_data['gz']) > 0) {

    header('Content-Encoding: gzip');

    echo $hyper_data['gz'];

}

else {

// No compression accepted, check if we have the plain html or

// decompress the compressed one.

    if ($hyper_data['html']) {

    //header('Content-Length: ' . strlen($hyper_data['html']));

        echo $hyper_data['html'];

    }

    else {

        $buffer = hyper_cache_gzdecode($hyper_data['gz']);

        if ($buffer === false) echo 'Error retriving the content';

        else echo $buffer;

    }

}

flush();

die();





function hyper_cache_start($delete=true) {

    global $hc_file;



    if ($delete) @unlink($hc_file);

    foreach ($_COOKIE as $n=>$v ) {

        if (substr($n, 0, 14) == 'comment_author') {

            unset($_COOKIE[$n]);

        }

    }

    ob_start('hyper_cache_callback');

}



// From here Wordpress starts to process the request



// Called whenever the page generation is ended

function hyper_cache_callback($buffer) {

    global $hyper_cache_notfound, $hyper_cache_stop, $hyper_cache_charset, $hyper_cache_home, $hyper_cache_redirects, $hyper_redirect, $hc_file, $hyper_cache_name, $hyper_cache_gzip;



    $buffer = apply_filters('hyper_cache_buffer', $buffer);



    if ($hyper_cache_stop) return $buffer;



    if (!$hyper_cache_notfound && is_404()) {

        return $buffer;

    }



    if (strpos($buffer, '</body>') === false) return $buffer;



    // WP is sending a redirect

    if ($hyper_redirect) {

        if ($hyper_cache_redirects) {

            $data['location'] = $hyper_redirect;

            hyper_cache_write($data);

        }

        return $buffer;

    }



    if (is_home() && $hyper_cache_home) {

        return $buffer;

    }



    if (is_feed() && !$hyper_cache_feed) {

        return $buffer;

    }



    if (is_home()) $data['type'] = 'home';

    else if (is_feed()) $data['type'] = 'feed';

        else if (is_archive()) $data['type'] = 'archive';

            else if (is_single()) $data['type'] = 'single';

                else if (is_page()) $data['type'] = 'page';

    $buffer = trim($buffer);



    // Can be a trackback or other things without a body. We do not cache them, WP needs to get those calls.

    if (strlen($buffer) == 0) return '';



    if (!$hyper_cache_charset) $hyper_cache_charset = 'UTF-8';



    if (is_feed()) {

        $data['mime'] = 'text/xml;charset=' . $hyper_cache_charset;

    }

    else {

        $data['mime'] = 'text/html;charset=' . $hyper_cache_charset;

    }



    $buffer .= '<!-- hyper cache: ' . $hyper_cache_name . ' ' . date('y-m-d h:i:s') .' -->';



    $data['html'] = $buffer;



    if (is_404()) $data['status'] = 404;



    hyper_cache_write($data);



    return $buffer;

}



function hyper_cache_write(&$data) {

    global $hc_file, $hyper_cache_store_compressed;



    $data['uri'] = $_SERVER['REQUEST_URI'];



    // Look if we need the compressed version

    if ($hyper_cache_store_compressed) {

        $data['gz'] = gzencode($data['html']);

        if ($data['gz']) unset($data['html']);

    }

    $file = fopen($hc_file, 'w');

    fwrite($file, serialize($data));

    fclose($file);



    header('Last-Modified: ' . date("r", @filemtime($hc_file)));

}



function hyper_mobile_type() {

    global $hyper_cache_mobile, $hyper_cache_mobile_agents, $hyper_cache_plugin_mobile_pack;



    if ($hyper_cache_plugin_mobile_pack) {

        @include_once ABSPATH . 'wp-content/plugins/wordpress-mobile-pack/plugins/wpmp_switcher/lite_detection.php';

        if (function_exists('lite_detection')) {

            $is_mobile = lite_detection();

            if (!$is_mobile) return '';

            include_once ABSPATH . 'wp-content/plugins/wordpress-mobile-pack/themes/mobile_pack_base/group_detection.php';

            if (function_exists('group_detection')) {

                return 'mobile' . group_detection();

            }

            else return 'mobile';

        }

    }



    if (!isset($hyper_cache_mobile) || $hyper_cache_mobile_agents === false) return '';



    $hyper_agent = strtolower($_SERVER['HTTP_USER_AGENT']);

    foreach ($hyper_cache_mobile_agents as $hyper_a) {

        if (strpos($hyper_agent, $hyper_a) !== false) {

            if (strpos($hyper_agent, 'iphone') || strpos($hyper_agent, 'ipod')) {

                return 'iphone';

            }

            else {

                return 'pda';

            }

        }

    }

    return '';

}



function hyper_cache_gzdecode ($data) {



    $flags = ord(substr($data, 3, 1));

    $headerlen = 10;

    $extralen = 0;



    $filenamelen = 0;

    if ($flags & 4) {

        $extralen = unpack('v' ,substr($data, 10, 2));



        $extralen = $extralen[1];

        $headerlen += 2 + $extralen;

    }

    if ($flags & 8) // Filename



        $headerlen = strpos($data, chr(0), $headerlen) + 1;

    if ($flags & 16) // Comment



        $headerlen = strpos($data, chr(0), $headerlen) + 1;

    if ($flags & 2) // CRC at end of file



        $headerlen += 2;

    $unpacked = gzinflate(substr($data, $headerlen));

    return $unpacked;

}

