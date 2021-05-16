<?php
//
//use Monolog\Utils;
//use Psr\Log\LogLevel;

require("load_func.php");
//require("../libs.php");


//index.php
$screen_shot_image = '';
// http://localhost:8080/index.php

/*
if (isset($_POST["screen_shot"])) {
//    $domain = $_POST["url"];
    $domain = $_POST["domain"];
//    $screen_shot_json_data = file_get_contents("https://www.googleapis.com/pagespeedonline/v2/runPagespeed?url=$url&screenshot=true");
//    $screen_shot_json_data = file_get_contents('https://s.wordpress.com/mshots/v1/' . urlencode($url) . '?w=730&h=300');
    $url_screen = "http://webscreen.pl:3000/png/{$domain}";

//    urlencode($url)

//    $screen_shot_result = json_decode($screen_shot_json_data, true);
//    $screen_shot = $screen_shot_result['screenshot']['data'];
//    $screen_shot = str_replace(array('_', '-'), array('/', '+'), $screen_shot);

    $type = pathinfo($url_screen, PATHINFO_EXTENSION);
    $data = file_get_contents($url_screen);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

    $screen_shot_image = "<img src=\"" . $base64 . "\" class='img-responsive img-thumbnail'/>";
}
*/

if (empty($_POST["domains"])) {
    $_POST["domains"] = "softreck.com";
}

if (isset($_POST["multi"])) {
    load_func([
        'https://php.letjson.com/let_json.php',
        'https://php.defjson.com/def_json.php',
        'https://php.eachfunc.com/each_func.php',

    ], function () {

        $domains = $_POST["domains"];
        // Clean URL
        $domains = str_replace('"', "", $domains);
        $domains = str_replace("'", "", $domains);
        $domains = str_replace(" ", "", $domains);
        $domains = str_replace(",", "", $domains);
//        $domains = str_replace(";", "", $domains);
//        $domain_list = explode("\n", str_replace("\r", "", $domains));
//        $domain_list = explode(PHP_EOL, $domains);
        $domains = trim($domains);

        if (empty($domains)) {
            throw new Exception("domains is empty");
        }

        $domain_list = array_values(array_filter(explode(PHP_EOL, $domains)));

//        var_dump($domain_list);
//        die;
        if (empty($domain_list)) {
            throw new Exception("domain list is empty");
        }

        $domain_nameserver_list = each_func($domain_list, function ($url) {

            if (empty($url)) return null;

            //$url = str_replace(PHP_EOL, '', $url);
                
            $url = trim($url);
            //$url = rtrim($url, ' ');
            $url = rtrim($url, '"');
            $url = rtrim($url, ';');
            $url = rtrim($url, ',');
            $url = rtrim($url, '/');

            
            if (empty($url)) return null;
            
            
            if (!(strpos($url, "http://") === 0) && !(strpos($url, "https://") === 0)) {
                $url = "http://" . $url;
            }

//            if(!(strpos( $url, "https://" ) === 0)){
//                $url = "https://" . $url
//            }

            $urle = urlencode($url);
            $url_screen = "http://webscreen.pl:3000/url/{$urle}";
//            var_dump($url_screen);
//die;
//            $type = pathinfo($url_screen, PATHINFO_EXTENSION);
//            $data = file_get_contents($url_screen);
//            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

//    <img src=\"" . $base64 . "\" class='img-responsive img-thumbnail'/>
//            $dns = getDNS($url);
            $domain = getDomain($url);

            return "
 <br><div>
    SCREEN: <a href='$url_screen' target='_blank'> $url</a>
    WEB: <a href='$url' target='_blank'> $url</a>
    <br>
    DNS: <a class='domain' href='https://domain-dns.parkingomat.pl/get.php?domain=$domain' target='_blank'> $domain </a>
    <br>
    REG: 
    <a class='registrar' href='https://ap.www.namecheap.com/domains/domaincontrolpanel/$domain/domain' target='_blank'> NAMECHEAP </a>
    <a class='registrar' href='https://premium.pl/ustawienia/$domain' target='_blank'> PREMIUM </a>
    
    REG-DNS:
    <a class='registrar' href='https://premium.pl/domain/changens.html?name=$domain&type=domain' target='_blank'> PREMIUM </a>
    <a class='registrar' href='https://www.aftermarket.pl/Domain/NS/?domain=$domain' target='_blank'> AFTERMARKET </a>
    
    <br>
    <img src='$url_screen' class='img-responsive img-thumbnail' />
    <br>
    <iframe src='$url' title='$domain'></iframe> 
</div>
            ";
        });

        global $screen_shot_image;

        $screen_shot_image = implode("<br>", $domain_nameserver_list);
//        var_dump($domain_nameserver_list);
//        var_dump($screen_shot_image);

    });
}

function getDomain($url)
{
    $parse = parse_url($url);
    $domain = $parse['host'];
    $domain = rtrim($domain, ' ');
    $domain = rtrim($domain, '"');
    $domain = rtrim($domain, ';');
    $domain = rtrim($domain, ',');
    $domain = rtrim($domain, '_');

    return $domain;
}


function getDNS($url)
{
    // DNS-y
    $parse = parse_url($url);
    $domain = $parse['host'];
    $dns_url = "https://domain-dns.parkingomat.pl/get.php?domain=" . $domain;
//            $data = file_get_contents($dns_url);
    $obj = file_get_contents($dns_url);
    $json = json_decode($obj, false);
    $dnsy = implode(" | ", $json->nameserver);
    return $dnsy;
}

?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>How to capture website screen shot from url in php</title>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"/>
    <style>
        .box {
            width: 100%;
            max-width: 720px;
            margin: 0 auto;
        }
    </style>

</head>
<body>
<div class="container box">
    <a href="http://webscreen.pl:3000/remove/png" target='_blank'> remove png </a>
     <a href="http://webscreen.pl:3000/remove/png" target='_blank'> remove txt </a>
    <br/>
    <h2 align="center">capture website screen shot</h2><br/>
    <form method="post">
        <div class="form-group">
             <label for="registrar">Choose a registrar:</label>

            <select name="registrar" id="registrar">
            <option value="namecheap">namecheap</option>
                
            <option value="premium">premium</option>
            <option value="aftermarket">aftermarket</option>
            
            </select> 
         </div>
         <div class="form-group">
             <label for="nameserver">Choose a nameserver:</label>

            <select name="nameserver" id="nameserver">
            <option value="cloudflare">cloudflare</option>
                
            <option value="digitalocean">digitalocean</option>
            
            </select> 
             
         </div>
         <div class="form-group">
             <label for="server">Choose a server:</label>

            <select name="server" id="server">
            <option value="one">one</option>
                
            <option value="two">two</option>
            
            </select> 
             
         </div>
        <div class="form-group">
            <!--            <label>Enter URL</label>-->
            <label>Enter Domain name</label>
            <br>
            <!--            <input type="url" name="url" class="form-control input-lg" required autocomplete="off"-->
            <!--                   value="http://softreck.com"/>-->
            <!--            <input type="domain" name="domain" class="input-lg" required autocomplete="on"-->
            <!--                   value="--><?php //echo $_POST["domain"] ?><!--"/>-->

            <textarea name="domains" cols="55" rows="20"><?php echo $_POST["domains"] ?></textarea>
        </div>
        <br/>
        <!--        <input type="submit" name="screen_shot" value="Take a Screenshot" class="btn btn-info btn-lg"/>-->
        <input type="submit" name="multi" value="Take a Screenshot" class="btn btn-info btn-lg"/>
    </form>
    <br/>
    <?php

    echo $screen_shot_image;

    ?>
</div>
<div style="clear:both"></div>
<br/>

<br/>
<br/>
<br/>

<script>
    $('a.domain').each(function () {
        // var value = $(this).attr('href');
        // $(this).attr('href', value.replace('#/',''));
        var atext = $(this);
        var url = atext.attr('href');
        // console.log(domain);
        // var url = "https://domain-dns.parkingomat.pl/get.php?domain=" + domain;
        var jqxhr = $.ajax(url)
            .done(function (result) {
                console.log(result);
                console.log(atext);
                // var nameservers = JSON.stringify(result.nameserver);
                var nameservers = result.nameserver.join(", ");
                console.log(nameservers);
                // alert( "success" );
                atext.html( nameservers );
                atext.addClass("active");
            });
    });
</script>

<style>
    a.domain {
        color: gray;
        text-decoration: none;
    }

    a.domain.active {
        color: #222333;
        text-decoration: none;
    }
    img.img-thumbnail {
        height:300px;
    }
    iframe {
        height:300px;
        width:600px;
    }
</style>
</body>
</html>
