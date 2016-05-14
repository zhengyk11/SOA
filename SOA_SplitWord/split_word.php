<?php
/**
 * Created by PhpStorm.
 * User: ZhengYukun
 * Date: 16/5/14
 * Time: 22:56
 */

function split_word($input) {

    $ch = curl_init();
    $url = 'http://apis.baidu.com/apistore/pullword/words?source=';
    $mode = '&param1=0.8&param2=0';
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

$in = '科比公司申请注册“黑曼巴”将用于运动装备';
echo split_word($in);

?>