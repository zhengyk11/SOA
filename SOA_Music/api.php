<?php
include 'list.php';
function curl_get($url)
{
    $refer = "http://music.163.com/";
    $header[] = "Cookie: " . "appver=1.5.0.75771;";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($ch, CURLOPT_REFERER, $refer);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function get_playlist_info($playlist_id)
{
    $url = "http://music.163.com/api/playlist/detail?id=" . $playlist_id;
    return curl_get($url);
}

function get_music_info($music_id)
{
    $url = "http://music.163.com/api/song/detail/?id=" . $music_id . "&ids=%5B" . $music_id . "%5D";
    return curl_get($url);
}

function get_music_lyric($music_id)
{
    $url = "http://music.163.com/api/song/lyric?os=pc&id=" . $music_id . "&lv=-1&kv=-1&tv=-1";
    return curl_get($url);
}

function rand_music()
{
    global $player_list;
	//if(isset($_COOKIE["playlist"])){
	//	$tmp = (array)json_decode(str_replace("\\", "", $_COOKIE["playlist"]));
	//	if(count($tmp) > 0)
	//		$player_list = array_values($tmp);
	//}
	//$player_list = array("27602841","28870240","28773824","19164058","4209157","28837261","26136782","18127541","4153632","4208437","26082104","22711515","1987888","857606","22717355","29418291","17194024","857619","17194024","29498036","28768456","17115765","859516","4164331",);
	//if(isset($_COOKIE["playlist"])){
	//$tmp = (array)json_decode(str_replace("\\", "", $_COOKIE["playlist"]));
	//if(count($tmp) > 0)
	//$player_list = array_values($tmp);
	//$f = fopen("log1.txt","w");
	//fwrite($f,var_export($player_list,true));
	//fclose($f); 
	//}
	//$f = fopen("log1.txt","w");
	//fwrite($f,var_export($player_list,true));
	//fclose($f);
    $sum = count($player_list);
    $id = (string)$player_list[rand(0, $sum - 1)];
    return $id;
}

function get_music_id()
{
    $played = isset($_COOKIE["played"]) ? json_decode(str_replace("\\", "", $_COOKIE["played"])) : null;
    $id = rand_music();
	global $player_list;
	$sum = count($player_list);
    if ($played != null && $sum >= 4) {
        if ($sum >= 2) {
            $sum = $sum * 0.5;
        } else {
            $sum -= 1;
        }
        while (in_array($id, $played)) {
            $id = rand_music();
        }
        if (count($played) >= $sum) {
            array_shift($played);
        }
    }
		if (!in_array($id, $played)){
				$played[] = $id;
		}
    setcookie("played", json_encode($played), time() + 3600);
    return $id;
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

function split_word($input, $p1 = 0.8, $p2 = 0) {

    $input = str_replace(" ", ".", $input);
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