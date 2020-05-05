<?php

if (! function_exists('checkImage')) {

    function checkImage($path)
    {
        if (\File::exists(public_path('upload/'.$path))){
            return asset('upload/'.$path);
        }else{
            $path = explode('/',$path);
            $place_holder = 'no_image.png';
            if(count($path) > 0){
                $path = $path[0];
                if($path == 'users'){
                    $place_holder = 'no_image.png';
                }
            }
            return asset('upload/'.$place_holder);
        }
    }
}
if (! function_exists('checkAddressImage')) {

    function checkAddressImage($path)
    {
        if (\File::exists(public_path('upload/'.$path))){
            return asset('upload/'.$path);
        }else{
            $path = explode('/',$path);
            $place_holder = 'no_image.png';
            if(count($path) > 0){
                $path = $path[0];
                if($path != 'addresses'){
                    $place_holder = 'no_image.png';
                }
            }

            return asset('upload/'.$place_holder);
        }
    }
}
if (! function_exists('checkPostImage')) {
    function checkPostImage($path)
    {
        if (\File::exists(public_path('upload/'.$path))){
            return asset('upload/'.$path);
        }else{
            $path = explode('/',$path);
            $place_holder = 'no_image.png';
            if(count($path) > 0){
                $path = $path[0];
                if($path != 'posts'){
                    $place_holder = 'no_image.png';
                }
            }

            return asset('upload/'.$place_holder);
        }
    }
}


if (! function_exists('checkChatImage')) {
    function checkChatImage($path)
    {
        if (\File::exists(public_path('upload/'.$path))){
            return asset('upload/'.$path);
        }else{
            $path = explode('/',$path);
            $place_holder = 'no_image.png';
            if(count($path) > 0){
                $path = $path[0];
                if($path != 'posts'){
                    $place_holder = 'no_image.png';
                }
            }

            return asset('upload/'.$place_holder);
        }
    }
}



if (! function_exists('checkEventImage')) {
    function checkEventImage($path)
    {
        if (\File::exists(public_path('upload/'.$path))){
            return asset('upload/'.$path);
        }else{
            $path = explode('/',$path);
            $place_holder = 'no_image.png';
            if(count($path) > 0){
                $path = $path[0];
                if($path != 'posts'){
                    $place_holder = 'no_image.png';
                }
            }

            return asset('upload/'.$place_holder);
        }
    }
}

if (! function_exists('checkPostVideo')) {
    function checkPostVideo($path)
    {
        if (\File::exists(public_path('upload/'.$path))){
            return asset('upload/'.$path);
        }else{
            $path = explode('/',$path);
            $place_holder = 'no_image.png';
            if(count($path) > 0){
                $path = $path[0];
                if($path != 'posts'){
                    $place_holder = 'no_image.png';
                }
            }

            return asset('upload/'.$place_holder);
        }
    }
}
if (! function_exists('checkBusinessVideo')) {
    function checkBusinessVideo($path)
    {
        if (\File::exists(public_path('upload/'.$path))){
            return asset('upload/'.$path);
        }else{
            $path = explode('/',$path);
            $place_holder = 'no_image.png';
            if(count($path) > 0){
                $path = $path[0];
                if($path != 'posts'){
                    $place_holder = 'no_image.png';
                }
            }

            return asset('upload/'.$place_holder);
        }
    }
}

if (! function_exists('checkEventVideo')) {
    function checkEventVideo($path)
    {
        if (\File::exists(public_path('upload/'.$path))){
            return asset('upload/'.$path);
        }else{
            $path = explode('/',$path);
            $place_holder = 'no_image.png';
            if(count($path) > 0){
                $path = $path[0];
                if($path != 'posts'){
                    $place_holder = 'no_image.png';
                }
            }

            return asset('upload/'.$place_holder);
        }
    }
}

if (! function_exists('checkClassifiedImage')) {
    function checkClassifiedImage($path)
    {
        if (\File::exists(public_path('upload/'.$path))){
            return asset('upload/'.$path);
        }else{
            $path = explode('/',$path);
            $place_holder = 'no_image.png';
            if(count($path) > 0){
                $path = $path[0];
                if($path != 'classifieds'){
                    $place_holder = 'no_image.png';
                }
            }
            return asset('upload/'.$place_holder);
        }
    }
}

if (! function_exists('checkBusinessImage')) {
    function checkBusinessImage($path)
    {
        if (\File::exists(public_path('upload/'.$path))){
            return asset('upload/'.$path);
        }else{
            $path = explode('/',$path);
            $place_holder = 'no_image.png';
            if(count($path) > 0){
                $path = $path[0];
                if($path != 'businesses'){
                    $place_holder = 'no_image.png';
                }
            }
            return asset('upload/'.$place_holder);
        }
    }
}

if (! function_exists('checkProfile')) {

    function checkProfile($path)
    {
        if (\File::exists(public_path('upload/'.$path))){
            return asset('upload/'.$path);
        }else{
            $path = explode('/',$path);
            $place_holder = 'no_image.png';
            if(count($path) > 0){
                $path = $path[0];
                if($path == 'profile'){
                    $place_holder = 'no_image.png';
                }
            }
            return asset('upload/'.$place_holder);
        }
    }
}

if (! function_exists('isActiveRoute')) {
    function isActiveRoute($route, $output = "open") {
        $adminRoute = 'admin/'.$route;
        $adminRoutes = 'admin/'.$route.'*';
        return (Request::is($adminRoute) || Request::is($adminRoutes) ? $output : '');
    }
}

if (! function_exists('generatePIN')) {
    function generatePIN($digits) {
       return mt_rand(pow(10, $digits-1), pow(10, $digits)-1);
    }
}


if (! function_exists('encodeId')) {

    function encodeId($string, $key=5) {
        $result = '';
        for($i=0, $k= strlen($string); $i<$k; $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char)+ord($keychar));
            $result .= $char;
        }
        return base64_encode($result);
    }
}

if (! function_exists('decodeId')) {

    function decodeId($string, $key=5) {
        $result = '';
        $string = base64_decode($string);
        for($i=0,$k=strlen($string); $i< $k ; $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char)-ord($keychar));
            $result.=$char;
        }
        return $result;
    }
}

if (! function_exists('number_format_short')) {

    function number_format_short( $n, $precision = 3 ) {
        if ($n < 900) {
            // 0 - 900
            $n_format = number_format($n, $precision);
            $suffix = '';
        } else if ($n < 900000) {
            // 0.9k-850k
            $n_format = number_format($n / 1000, $precision);
            $suffix = 'K';
        } else if ($n < 900000000) {
            // 0.9m-850m
            $n_format = number_format($n / 1000000, $precision);
            $suffix = 'M';
        } else if ($n < 900000000000) {
            // 0.9b-850b
            $n_format = number_format($n / 1000000000, $precision);
            $suffix = 'B';
        } else {
            // 0.9t+
            $n_format = number_format($n / 1000000000000, $precision);
            $suffix = 'T';
        }
        // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
        // Intentionally does not affect partials, eg "1.50" -> "1.50"
        if ( $precision > 0 ) {
            $dotzero = '.' . str_repeat( '0', $precision );
            $n_format = str_replace( $dotzero, '', $n_format );
        }
        return $n_format . $suffix;
    }
}
