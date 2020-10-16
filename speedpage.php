<?php


function getValueInQuotes(string $str) {
    $start = strpos($str, "'") + 1;
    $length = strrpos($str, "'") - $start;
    return substr($str, $start, $length);
}

function getChargedCurlResourceFromString(string $curlString)
{
    $strings = explode(PHP_EOL, $curlString);
    $curl = curl_init();

    $url = '';
    $headers = [];
    foreach ($strings as $str) {
        if (strpos($str, 'curl') !== false) {
            // get URL from string
            $url = getValueInQuotes($str);
        } elseif (strpos($str, '-H')) {
            $headers[] = getValueInQuotes($str);
        }
    }

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    return $curl;
}


// Google.com to get stats
$curlString = "curl 'https://www.google.com/' \
  -H 'authority: www.google.com' \
  -H 'pragma: no-cache' \
  -H 'cache-control: no-cache' \
  -H 'upgrade-insecure-requests: 1' \
  -H 'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36' \
  -H 'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9' \
  -H 'x-client-data: CJa2yQEIpbbJAQipncoBCJasygEI9cfKAQjXzcoBCMLXygEIntjKARjmv8oBGPfAygE=' \
  -H 'sec-fetch-site: same-origin' \
  -H 'sec-fetch-mode: navigate' \
  -H 'sec-fetch-user: ?1' \
  -H 'sec-fetch-dest: document' \
  -H 'accept-language: en-US,en;q=0.9,ru;q=0.8' \
  -H 'cookie: S=billing-ui-v3=sple9At75bXip2_Kk6Uz2hu-31sCl4R0:billing-ui-v3-efe=sple9At75bXip2_Kk6Uz2hu-31sCl4R0; HSID=ALN6pbiKZ9ebqiwGI; SSID=A5HV8-k-lR4GB0IWp; APISID=ctBp4ORRW4G0DAKg/AtUEr7vrChEZ9ivW1; SAPISID=_yt3nn6_FxvmfmjO/AcpBbL1W8Lmi1Oefj; __Secure-3PAPISID=_yt3nn6_FxvmfmjO/AcpBbL1W8Lmi1Oefj; ANID=AHWqTUklY0dv4x1ui7FRpfrFf8IvS8zo4Yn5S6iIiXl8jxiI4AXVwmss0UqWR-Nk; SID=2Aeh68sIa3xa9f740gfxEcdkqv05s4NieKlEH_1DyYeLDBjgNHbmlf5ZFbZtiZWW4lTGRg.; __Secure-3PSID=2Aeh68sIa3xa9f740gfxEcdkqv05s4NieKlEH_1DyYeLDBjg7PEnmDtOc_ZTrU4XBCBJRA.; SEARCH_SAMESITE=CgQI7ZAB; OTZ=5675644_44_44_123780_40_436260; 1P_JAR=2020-10-16-15; NID=204=YsULiGihQrbwQyZUUE5td_pPMiNS77F1dDyjwhjEFkL6G97D554aLrb6qcw21r4fRIXhivdKkeexBcCA8Fc-WUSkS3_QjczFnlKb3VKBVg0fN5v3AtsauQHWaNPiZwfqQ88C07ZZiwJ5mwaVwg3XUAXvRRdv8LrTT96gdyDmLF0wrgNG0A9d11yEgXXim0HTjwyeLnDMwOz0IjoVxgHwKVwSfLSADD8Pqk4fotnDpBAANsy7aJ4; SIDCC=AJi4QfHE8Ba20E0SfIhSPHetz4pGRe86wPjtX9GP_AUDxaQ-25yRsS7xdevgvbnvo3xjwjQ2Q_8D; __Secure-3PSIDCC=AJi4QfEqaVkQbl4vBSB5JnJYBF1V17VFLfwHC1rNtob1-m8Lzjv2EWugVPlAmuOsnJNpYxXPqZ6r' \
  --compressed";


$curl = getChargedCurlResourceFromString($curlString);

$total = [];
$nameLookup = [];
$connect = [];
$pretransfer = [];
$speedDn = [];

$steps = 5;
echo "URL: " . curl_getinfo($curl, CURLINFO_EFFECTIVE_URL) . PHP_EOL;
echo "Lets go {$steps} times" . PHP_EOL;

for ($i = 0; $i < $steps; $i++) {
    $output = curl_exec($curl);
    $curlInfo = curl_getinfo($curl);

    $total[]       = $curlInfo['total_time'];
    $nameLookup[]  = $curlInfo['namelookup_time'];
    $connect[]     = $curlInfo['connect_time'];
    $pretransfer[] = $curlInfo['pretransfer_time'];
    $speedDn[]     = $curlInfo['speed_download'];
    echo ".";
}
echo PHP_EOL;
curl_close($curl);

echo "Average total time: " . (array_sum($total) / $steps) . PHP_EOL;
echo "Max total time: " . max($total) . PHP_EOL;
echo "Min total time: " . min($total) . PHP_EOL;
