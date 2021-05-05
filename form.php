<?php

class Capture
{
    /**
     * Capture web screenshot using google api.
     *
     * @param (string) $url Valid url
     *
     * @return blob
     */
    public function snap($url)
    {
        //Url value should not empty and validate url
        if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL)) {
            $curl_init = curl_init("http://webscreen.pl:3000/png/{$url}");
            curl_setopt($curl_init, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl_init);
            curl_close($curl_init);
            //call Google PageSpeed Insights API
            //decode json data
            $googlepsdata = json_decode($response, true);
            //screenshot data
            $snap = $googlepsdata['screenshot']['data'];
            $snap = str_replace(['_', '-'], ['/', '+'], $snap);return $snap;
        } else {
            return false;
        }
    }
}if (isset($_POST['submit']) && !empty($_POST['url'])) {
    $snap = (new Capture())->snap($_POST['url']);
    if ($snap)
        echo "<img src=\"data:image/jpeg;base64,".$snap."\" />";
    else
        echo "Enter valid url";
}
?>
<form method="post" action="form.php" >
    <p>Website URL: <input type="text" name="url" value="" /></p>
    <input type="submit" name="submit" value="CAPTURE">
</form>
