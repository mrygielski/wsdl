<?php


function DateTimeToString($dt)
{
    $_dt = new DateTime($dt);
    return $_dt->format(DateTime::ATOM);
}

function StartTimer()
{
    $time = microtime();$time = explode(' ', $time);$time = $time[1] + $time[0];$start = $time;
    return $start;
}

function StopTimer($start)
{
    $time = microtime();$time = explode(' ', $time);$time = $time[1] + $time[0];$finish = $time; //$total_time = round(($finish - $start), 4); 
    return round(($finish - $start), 4);
}

function startsWith($haystack, $needle)
{
    return !strncmp($haystack, $needle, strlen($needle));
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}

function strrtrim($message, $strip) { 
    // break message apart by strip string 
    $lines = explode($strip, $message); 
    $last  = ''; 
    // pop off empty strings at the end 
    do { 
        $last = array_pop($lines); 
    } while (empty($last) && (count($lines))); 
    // re-assemble what remains 
    return implode($strip, array_merge($lines, array($last))); 
} 


function full_url()
{
    $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
    $sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
    $protocol = substr($sp, 0, strpos($sp, "/")) . $s;
    $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
    return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
}

function md5_hash_encode($source) 
{
    $base_string = md5($source); // kodowanie stringa standardowym MD5()

    // dzielenie stringa na 3 kawałki
    $begin = substr($base_string, 0, 4);
    $in = substr($base_string, 4,20);
    $end = substr($base_string, 20, strlen($base_string));

    $base_string = $begin.rand(0,8).$in.rand(0,8).$end; // dodawanie dodatkowego hasha
    $base_string = strrev($base_string); // odwracanie kolejności znaków w stringu

    return $base_string;
}

function md5_hash_decode($source) 
{
    $base_string = strrev($source); // odwracanie kolejności znaków w stringu

    // pobieranie prawidłowych elementów stringa
    $begin = substr($base_string, 0, 4);
    $in = substr($base_string, 5, 20);
    $end = substr($base_string, 30, strlen($base_string));

    $base_string = $begin.$in.$end;

    return $base_string;
}

function base64_hash_encode($source, $base = false) 
{
    if ($base) $base_string = base64_encode($source); else $base_string = $source;

    if (strlen($source) > 24) {
     // dzielenie stringa na 3 kawałki
     $begin = substr($base_string, 0, 4);
     $in = substr($base_string, 4,20);
     $end = substr($base_string, 24, strlen($base_string));

     $base_string = $begin.rand(0, 8).$in.rand(0, 8).$end; // dodawanie dodatkowego hasha
    }

    $base_string = strrev($base_string); // odwracanie kolejności znaków w stringu

    for ($i = 0; $i < strlen($base_string); $i++) $base_string[$i] = chr(ord($base_string[$i]) + ($i * 3));

    // zamiana treści na HEX
    $encode_result = "";
    foreach(str_split($base_string) as $c) $encode_result .= sprintf("%02X", ord($c));

    return strtolower($encode_result);

}

function base64_hash_decode($source, $base = false) 
{
    // odwracanie treści z HEX
    $source_decode = "";
    foreach(explode("\n", trim(chunk_split(strtoupper($source), 2))) as $h) $source_decode .= chr(hexdec($h));

    for ($i = 0; $i < strlen($source_decode); $i++) $source_decode[$i] = chr(ord($source_decode[$i]) - ($i * 3));

    $base_string = strrev($source_decode); // odwracanie kolejności znaków w stringu

    if (strlen($base_string) > 24) {
     // pobieranie prawidłowych elementów stringa
     $begin = substr($base_string, 0, 4);
     $in = substr($base_string, 5, 20);
     $end = substr($base_string, 26, strlen($base_string));

     $base_string = $begin.$in.$end;
    }

    if ($base) $base_string = base64_decode($base_string);

    return $base_string;

}


//  echo md5('passowrd!!!!787').'<BR>';
//  echo md5_hash_decode('912e198bd3876d387bd8846228375303036951').'<BR>';
?>