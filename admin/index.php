<?php
	require_once('../header.php');
	require_once('../db_config.php');
	$mysqli = new MySQLi(DB_HOST,DB_USER,DB_PASS,DB_NAME);
	
	$Query = "SELECT * FROM event_type";
		
?>

	<h2 id="pageTitle">Create New Album</h2>
    <hr/>
    <div class="clearfix padding10"></div>
    
    <div class="span6">
    	<form action="/admin/logic/create_album.php" method="post">
        <h4>Event Name</h4>
        <div class="input-control text span4">
            <input type="text" name="eventName" id="eventName" />
        </div>
        <br/>
        <h4>Event Type</h4>
        <div class="input-control select span4">
            <select name="eventType" id="eventType">
            	<option value="nothing">Select Value </option>
                <?php 
				if($result = $mysqli->query($Query))
				{
					while($x = $result->fetch_array())
					{
				?>
                <option value="<?php echo $x[0]?>"><?php echo $x[1]?></option>
                <?php
					}
				}
				?>
            </select>
        </div>
        <br/>
        <h4>Event Year</h4>
        <div class="input-control select span4">
            <select name="eventYear" id="eventYear">
            	<option value="nothing">Select Value </option>
                <?php
					$year = date('Y');
					echo $year;
					while($year>2004)
					{
				?>
                <option value="<?php echo $year?>"><?php echo $year?></option>
                <?php
					$year--;
					}
				?>
            </select>
        </div>
        <br/>
        <input id="resetBtn" type="reset" class="bg-color-purple fg-color-white" value="Reset"/>
        <input id="submitBtn" type="submit" class="bg-color-purple fg-color-white" value="Submit"/>
		</form>
        <div class="clearfix padding80"></div> 
    </div>
    <script type="text/javascript">
    	$(document).ready(function(e){
			/*$('#submitBtn').on('click',function(){
				var eventName = $('#eventName').val();
				var eventType = $('#eventType').val();
				var eventYear = $('#eventYear').val();
				//alert('eventName='+eventName+'&eventType='+eventType+'eventYear='+eventYear);
				$.post('/admin/process.php','option=2&eventName='+eventName+'&eventType='+eventType+'eventYear='+eventYear).done(function(data){
					var json = parse.JSON(data);
					alert(json['msg']);
				}).fail(function(){
					alert('error');
				});
			*/	/*$.ajax({
					url : '/admin/process.php',
					data : {
						'option':2,
						'eventName':eventName,
						'eventType': eventType,
						'eventYear':eventYear
					},
					dataType : json,
					type : post,
					beforeSend : function(){
						$('#container').prepend('<img id="loading" class="center" src="/metroui/images/preloader-w8-line-black.gif">');
					},
					success : function(data){
						alert(data['msg']);	
					},
					error : function(){
						//Error Callback
						alert('Error');
					}
				});*/
			});
    </script>
    
    
<?php
	require_once('../footer.php');
?>