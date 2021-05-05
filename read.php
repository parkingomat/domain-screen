<?php

require("load_func.php");

//header("Content-Type: image/jpeg");

# Webs service with JSON to show/write list of nameservice many domains in: domain_list.json
try {

    load_func([
        'https://php.letjson.com/let_json.php',
        'https://php.defjson.com/def_json.php',
        'https://php.eachfunc.com/each_func.php',

    ], function () {

        $domain = "softreck.com";
//        $url = $_GET["url"];

        if (empty($domain)) {
            throw new Exception("url value is empty");
        }

        $url_screen = "http://webscreen.pl:3000/png/{$domain}";


        /*
                $curl_init = curl_init($url_screen);
                curl_setopt($curl_init, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($curl_init);
                curl_close($curl_init);
        */
        //call Google PageSpeed Insights API
        //decode json data
//        $googlepsdata = json_decode($response, true);
        //screenshot data
//        $snap = $googlepsdata['screenshot']['data'];
//        $snap = str_replace(['_', ' - '], [' / ', ' + '], $snap);
//        echo "<img src=\"data:image/jpeg;base64," . $snap . "\" />";
//        echo "<img src=\"data:image/jpeg;base64," . $snap . "\" />";
//        echo $response;
        echo $url_screen;


    });

} catch (Exception $e) {
    echo def_json('', ['error' => $e->getMessage()]);
}
