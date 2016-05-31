/**
 * Created by Zhengyk11 on 2016/4/4.
 */
oAudio = document.getElementById('player');
btn = $("#m_play");
album = $("#album");
inn = $("#in");
music_name = $("#music_name");
artist = $("#artist");
cd = $("#cd");
lrc_row = $("#lrc");
s_button = $("#s_button")
input = $("#t_input")
star = $("#star")

function actionFormatter(value, row, index) {
    return [
        '<a class="play" href="javascript:void(0)" title="Play">',
        '<i class="glyphicon glyphicon-play-circle"></i>',
        '</a>',
        '<a class="remove ml10" href="javascript:void(0)" title="Remove">',
        '<i class="glyphicon glyphicon-remove"></i>',
        '</a>'
    ].join('');
}

window.actionEvents = {
    'click .play': function (e, value, row, index) {
				$('#myModal').modal('hide');
				oAudio.pause();
				album.removeClass("roll");
				inn.removeClass("roll");
				if (!oAudio.paused && lrc != "no") {
						clearInterval(lrc_interval);
				}
				load_music("player.php?mid=" + row["id"]);
    },
    'click .remove': function (e, value, row, index) {
        $.get("remove_star_song.php?mid="+row["id"], function (data) {
					$('#m_list').bootstrapTable('refresh', {silent: true});
					if (data == "1"){
						star.attr("src", "images/heart_36.512820512821px_1194482_easyicon.net.png");
					}
				});
		}
};

var TableInit = function () {
  var oTableInit = new Object();
  //初始化Table
  oTableInit.Init = function () {
    $('#m_list').bootstrapTable({
      url: 'star_songs.php',		 //请求后台的URL（*）
      method: 'get',					  //请求方式（*）
      striped: true,					  //是否显示行间隔色
      cache: false,					   //是否使用缓存，默认为true，所以一般情况下需要设置一下这个属性（*）
      pagination: true,				   //是否显示分页（*）
      sortable: true,					 //是否启用排序
      sortOrder: "asc",				   //排序方式
      queryParams: oTableInit.queryParams,//传递参数（*）
      sidePagination: "server",		   //分页方式：client客户端分页，server服务端分页（*）
      pageNumber:1,					   //初始化加载第一页，默认第一页
      pageSize: 10,					   //每页的记录行数（*）
      pageList: [10, 25, 50, 100],		//可供选择的每页的行数（*）
      search: true,					   //是否显示表格搜索，此搜索是客户端搜索，不会进服务端，所以，个人感觉意义不大
      strictSearch: true,
      showColumns: true,				  //是否显示所有的列
      showRefresh: true,				  //是否显示刷新按钮
      minimumCountColumns: 2,			 //最少允许的列数
      clickToSelect: true,				//是否启用点击选中行
      height: 500,						//行高，如果没有设置height属性，表格自动根据记录条数觉得表格高度
      uniqueId: "id",					 //每一行的唯一标识，一般为主键列
      showToggle:true,					//是否显示详细视图和列表视图的切换按钮
      cardView: false,					//是否显示详细视图
      detailView: false,				   //是否显示父子表
      columns: [{
        field: 'id',
				align: "center",
        title: 'id',
				sortable: true,//是否可排序
        order: "desc"//默认排序方式
      }, {
        field: 'name',
				align: "center",
        title: '歌曲',
				sortable: true
      }, {
        field: 'artist',
				align: "center",
        title: '艺术家'
      }, {
        field: 'times',
				align: "center",
				sortable: true,
        title: '播放次数'
      },
			{
        field: 'action',
				align: "center",
        title: 'Action',
				formatter: 'actionFormatter',
				events: 'actionEvents'
      },]
    });
  };
  //得到查询的参数
  oTableInit.queryParams = function (params) {
    var temp = {   //这里的键的名字和控制器的变量名必须一直，这边改动，控制器也需要改成一样的
      limit: params.limit,   //页面大小
      offset: params.offset,  //页码
			order: params.order,
			sort: params.sort,
    };
    return temp;
  };
  return oTableInit;
};
var ButtonInit = function () {
  var oInit = new Object();
  var postdata = {};
  oInit.Init = function () {
    //初始化页面上面的按钮事件
  };
  return oInit;
};

$(document).ready(function () {
    cd_size();
    $.get("player.php?_=" + (new Date()).getTime(), function (data) {
        mp3_info = JSON.parse(data);
        $("#player").attr("src", mp3_info.mp3);
        album.css("background-image", "url('" + mp3_info.cover + "')");
        music_name.html(mp3_info.music_name);
        artist.html(mp3_info.artists);
        if (mp3_info.lrc != "no") {
            lrc = mp3_info.lrc;
        } else {
            lrc = "no";
        }
				if (mp3_info.star == 1) {
					star.attr("src", "images/Heart_love_16px_1096414_easyicon.net.png");
				}
				else{
					star.attr("src", "images/heart_36.512820512821px_1194482_easyicon.net.png");
				}
    });
    oAudio.volume = 0.5;
});

$(window).resize(function () {
    cd_size();
});

$("#player").bind("ended", function () {
    if (lrc != "no") {
        clearInterval(lrc_interval);
    }
    next_music();
});

$('#t_input').bind('keypress',function(event){
		if(event.keyCode == "13")    
		{
				search();
		}
});

function show_list() {
	$('#myModal').modal('show');
	//1.初始化Table
  var oTable = new TableInit();
  oTable.Init();
  //2.初始化Button的点击事件
  var oButtonInit = new ButtonInit();
  oButtonInit.Init();
}

function m_play() {
    if (oAudio.paused) {
        oAudio.play();
        btn.attr("src", "images/pause.png");
        album.addClass("roll");
        inn.addClass("roll");
        if (lrc != "no") {
            lrc_interval = setInterval("display_lrc()", 1000);
        }
    }
    else {
        oAudio.pause();
        btn.attr("src", "images/play.png");
        album.removeClass("roll");
        inn.removeClass("roll");
        if (lrc != "no") {
            clearInterval(lrc_interval);
        }
    }
}

function next_music() {
    oAudio.pause();
    album.removeClass("roll");
    inn.removeClass("roll");
    if (!oAudio.paused && lrc != "no") {
        clearInterval(lrc_interval);
    }
    load_music("player.php?_=" + (new Date()).getTime());
    btn.attr("src", "images/pause.png");
}

function weibo_listen() {
	if (!oAudio.paused){
		oAudio.pause();
		btn.attr("src", "images/play.png");
	}
	album.removeClass("roll");
	inn.removeClass("roll");
	if (!oAudio.paused && lrc != "no") {
			clearInterval(lrc_interval);
	}
	load_music("player.php?weibo=1");
}

function load_music(str) {
		$("#over").toggle();
		$("#layout").toggle();
    $.get(str, function (data) {
				$("#over").toggle();
				$("#layout").toggle();
        mp3_info = JSON.parse(data);
        $("#player").attr("src", mp3_info.mp3);
        album.css("background-image", "url('" + mp3_info.cover + "')");
        music_name.html(mp3_info.music_name);
        artist.html(mp3_info.artists);
        oAudio.play();
        album.addClass("roll");
        inn.addClass("roll");
        lrc_row.html("");
        if (mp3_info.lrc != "no") {
            lrc = mp3_info.lrc;
            lrc_interval = setInterval("display_lrc()", 1000);
        } else {
            lrc = "no";
        }
				if (mp3_info.star == 1) {
					star.attr("src", "images/Heart_love_16px_1096414_easyicon.net.png");
				}
				else{
					star.attr("src", "images/heart_36.512820512821px_1194482_easyicon.net.png");
				}
				btn.attr("src", "images/pause.png");
				$('#m_list').bootstrapTable('refresh', {silent: true});
				
    });
}

function volume(vol) {
    oAudio.volume = vol / 10;
}

function cd_size() {
    cd_height = cd.height();
    cd.css("width", cd_height);
}

function display_lrc() {
    play_time = Math.floor(oAudio.currentTime).toString();
    lrc_row.html(lrc[play_time]);
}

function search() {
		if (input.val() != ""){
			$("#over").toggle();
			$("#layout").toggle();
			$.get("player.php?search=" + input.val(), function (data) {
					$("#over").toggle();
					$("#layout").toggle();
					input.val("")
					mp3_info = JSON.parse(data);
					$("#player").attr("src", mp3_info.mp3);
					album.css("background-image", "url('" + mp3_info.cover + "')");
					btn.attr("src", "images/pause.png");
					music_name.html(mp3_info.music_name);
					artist.html(mp3_info.artists);
					oAudio.play();
					album.addClass("roll");
					inn.addClass("roll");
					lrc_row.html("");
					if (mp3_info.lrc != "no") {
							lrc = mp3_info.lrc;
							lrc_interval = setInterval("display_lrc()", 1000);
					} else {
							lrc = "no";
					}
					if (mp3_info.star == 1) {
						star.attr("src", "images/Heart_love_16px_1096414_easyicon.net.png");
					}
					else{
						star.attr("src", "images/heart_36.512820512821px_1194482_easyicon.net.png");
					}
					$('#m_list').bootstrapTable('refresh', {silent: true});
			});
		}
}

function m_star() {
	if (star.attr("src") != "images/Heart_love_16px_1096414_easyicon.net.png"){
		star.attr("src", "images/Heart_love_16px_1096414_easyicon.net.png");
		$.get("mark.php?key=1", function (data) {
			$('#m_list').bootstrapTable('refresh', {silent: true});
		});
	}
	else{
		star.attr("src", "images/heart_36.512820512821px_1194482_easyicon.net.png");
		$.get("mark.php?key=0", function (data) {
			$('#m_list').bootstrapTable('refresh', {silent: true});
		});
	}
}

