<?php
session_start();
if (isset($_SESSION["uid"])){
	if (isset($_SESSION['emulate_data'])) {
    //已生成
	} else {
			$list = array();
			//模拟生成数据，从action数据库中将所有uid为$_SESSION["uid"]，star为1的数据条目取出，取出mid，name，artis，times这几项，写入到list这个array中。
			for($i = 1; $i < 50; $i ++) {
					$list[] = array(
									"id" => $i,
									"name" => substr(str_shuffle(implode('', range('a', 'z'))), 0, 5),
									"artist" => substr(str_shuffle(implode('', range('a', 'z'))), 5, 10),
									"times" => mt_rand(10, 30),
					);
			}
			$list[0]["id"] = 40147552;
			$_SESSION['emulate_data'] = $list;
	}

	$list_temp = $_SESSION['emulate_data'];

	//排序
	if (isset($_GET['sort'])) {
			$temp = array();
			foreach ($list_temp as $row) {
					$temp[] = $row[$_GET['sort']];
			}
			//php的多维排序
			array_multisort($temp,
					$_GET['sort'] == 'name' ? SORT_STRING : SORT_NUMERIC,
					$_GET['order'] == 'asc' ? SORT_ASC : SORT_DESC,
					$list_temp
			);
	}

	//分页时需要获取记录总数，键值为 total
	$result["total"] = count($list_temp);
	//根据传递过来的分页偏移量和分页量截取模拟分页 rows 可以根据前端的 dataField 来设置
	$result["rows"] = array_slice($list_temp, $_GET['offset'], $_GET['limit']);

	echo json_encode($result);
}
?>