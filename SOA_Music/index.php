<?php
session_start();
include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );
$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
$code_url = $o->getAuthorizeURL( WB_CALLBACK_URL );
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
		<link rel="shortcut icon" href="assets/img/favicon.png">
    <title>SOA Music</title>
		
		 <!-- Bootstrap -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
		<link href="assets/css/bootstrap-theme.css" rel="stylesheet">

    <!-- siimple style -->
    <link href="assets/css/style.css" rel="stylesheet">
		
    <link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/speech-input.css">
		<link rel="stylesheet" href="css/gh-buttons.css">
</head>
<body>

		<div class="navbar navbar-inverse navbar-fixed-top" style="background: #333;">
      <div class="container" >
        <div class="navbar-header" >
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">SOA</a>
        </div>
        <div class="navbar-collapse collapse" style="background: #333;">
					
          <ul class="nav navbar-nav navbar-right">
						<li>
							<div class="si-wrapper">
								<input type="text" id="t_input" class="si-input" placeholder="">
								<button class="si-btn">
									speech input
									<span class="si-mic"></span>
									<span class="si-holder"></span>
								</button>
							</div>
						</li>
            <li><a id="s_button" href="javascript:search()">Search</a></li>
						<?php
						if (isset($_SESSION['uid'])){
							echo '<li><a href="logout.php">Logout</a></li>';
							echo '<li><img style="padding-left:10px;" href="#" title="' . $_SESSION['uname'] . '" src="' . $_SESSION['uphoto'] .'"  alt="头像" /></li>';
						}
						else{
							echo '<li class="dropdown"><a href="' . $code_url . '" class="dropdown-toggle" data-toggle="dropdown">
									Sign in
									<b class="caret"></b>
								</a>
								<ul class="dropdown-menu">
									 <li><a href="' . $code_url . '">新浪微博登录</a></li>
									 <li><a href="#">其他</a></li>
								</ul>
							</li>';
						}
						?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

	<div id="header">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<div class="container1">
							<div class="player1">
									<div id="cd" class="cd">
											<div class="out">
											</div>
											<div id="album" class="album">
											</div>
											<div id="in" class="in">
											</div>
									</div>
									<div class="action1">
											<a href="javascript:m_play()"><img id="m_play" src="images/play.png"></a>
											<input id="range" type="range" min="0" max="10" value="5" onchange="volume(this.value)">
											<a href="javascript:next_music()"><img id="next_music" src="images/forward.png"></a>
									</div>
									<div class="info">
											<span id="music_name"></span><span id="artist"></span>
									</div>
									<div id="lrc" class="lrc">
									</div>
							</div>
					</div>					
				</div>
				<div class="col-lg-4 col-lg-offset-2">
					<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
					  <ol class="carousel-indicators">
						<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
						<li data-target="#carousel-example-generic" data-slide-to="1"></li>
						<li data-target="#carousel-example-generic" data-slide-to="2"></li>
					  </ol>					
					  <!-- slides -->
					  <div class="carousel-inner" style="margin-top:-30px;">
						<div class="item active">
						  <img id="img1" src="assets/img/slide1.png" alt="" title="微博动态推荐音乐">
						</div>
						<div class="item">
						  <img src="assets/img/slide2.png" alt="">
						</div>
						<div class="item">
						  <img src="assets/img/slide3.png" alt="">
						</div>
					  </div>
					</div>		
				</div>
				
			</div>
		</div>
	</div>
	<audio id="player">
</audio>
	<div id="footer">
	<div class="container">
		<div class="row">
			<div class="col-lg-6 col-lg-offset-3">
					<p class="copyright">Copyright &copy; 2016 - THUCST</p>
			</div>
		</div>		
	</div>	
	</div>
		

<script src="js/speech-input.js"></script>
<script src="js/jquery-1.11.1.min.js" type="text/javascript"></script>
<script src="js/player.js" type="text/javascript"></script>
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>