<?php 
	session_start();
	if(isset($_SESSION['uid']))
	{
		header('location:/admin/');
	}
	
	session_destroy();
	require_once ('../header.php');
?>
<div class="clearfix padding40"></div>
<form action="/admin/process" method="post" class="offset4 span4">
    <h3 class="fg-color-dark">Username</h3>
    <div class="input-control text">
    <input type="text" name="username" />
    <button class="btn-clear"></button>
    </div>
    <h3 class="fg-color-dark">Password</h3>
    <div class="input-control password">
    <input type="password" name="password" />
    <button class="btn-reveal"></button>
    </div>
    <input type="submit" class="bg-color-green text-center center" value="Authenticate"/>
    <input type="hidden" name="option" value="1">
</form>
<div class="clearfix padding80"></div>
<div class="clearfix padding40"></div>
<?php
	require_once('../footer.php');
?>