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
    load_music();
    btn.attr("src", "images/pause.png");
}

function load_music() {
    $.get("player.php?_=" + (new Date()).getTime(), function (data) {
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
			input.val("")
			$.get("player.php?search=" + input.val(), function (data) {
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
			});
		}
}