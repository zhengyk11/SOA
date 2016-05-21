<?php
function split_word($input, $p1 = 0.8, $p2 = 0) {

    $ch = curl_init();
    $url = 'http://apis.baidu.com/apistore/pullword/words?source=';
    $mode = '&param1='.$p1.'&param2='.$p2;
    $header = array(
        'apikey:e9efbd5ac9db0c055b973011482a4418',
    );

    // 添加apikey到header
    curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // 执行HTTP请求
    curl_setopt($ch , CURLOPT_URL , $url . $input . $mode );
    $res = curl_exec($ch);
    return $res;
    //return json_encode($res);
}

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

function get_music_list($input, $limit = 100){
    header("Content-type:text/html;charset=utf-8");
    $url= "http://music.163.com/api/search/get/web?csrf_token=";
    $s = $input;
    //$limit = 100;
    $music_id = array();
    
    if(!$s||!$limit){
        $tempArr = array("code"=>-1,"msg"=>"输入参数有误！");
        echo  json_encode($tempArr);
    }else{
        $result = curl($url,$s,$limit);
        $de_json = json_decode($result,TRUE);
        $dt_record = $de_json['result']['songs'];
        $count_json = count($dt_record);
        for ($i = 0; $i < $count_json; $i++){
            $message =  $dt_record[$i]['id'];
            array_push($music_id, $message);
        }
        $jt_record = json_encode($music_id);
    //    echo $count_json;
        //echo $jt_record;
    }
    return $music_id;
}
?>
