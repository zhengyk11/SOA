<?php
header("Content-type:text/html;charset=utf-8");
$url= "http://music.163.com/api/search/get/web?csrf_token=";
$s = '简单爱';
$limit = 200;

function curl($url,$s,$limit){
    $curl = curl_init();
    $post_data = 'hlpretag=<span class="s-fc7">&hlposttag=</span>&s='. $s . '&type=1&offset=0&total=true&limit=' . $limit;
    curl_setopt($curl, CURLOPT_URL,$url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);

    $header =array(
        'Host: music.163.com',
        'Origin: http://music.163.com',
        'User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36',
        'Content-Type: application/x-www-form-urlencoded',
        'Referer: http://music.163.com/search/',
    );

    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    $src = curl_exec($curl);
    curl_close($curl);
    return $src;
}

if(!$s||!$limit){
    $tempArr = array("code"=>-1,"msg"=>"输入参数有误！");
    echo  json_encode($tempArr);
}else{
    echo curl($url,$s,$limit);
}
