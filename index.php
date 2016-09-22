<?php 
	require_once 'header.php';
	require_once 'db_config.php';
	
	$mysqli = new MySQLi(DB_HOST,DB_USER,DB_PASS,DB_NAME);
	$x = 2;
	
	if(@$_GET['sort']=='type')
	{
		$x = 1;
	}
	if(@$_GET['sort']=='year')
	{
		$x = 2;
	}
	
	$Query = NULL;
	switch($x)
	{
		case 1 : 
			$Query = "SELECT ev.name, ev.thumbnail, et.type, ev.id FROM event ev INNER JOIN event_type et ON ev.type = et.id ORDER BY et.type";
			break;
		case 2 :
			$Query = "SELECT name, thumbnail, year, id FROM event ORDER BY year DESC";
			break;
	}
	
	$result = $mysqli->query($Query);
	$previous = NULL;
	$i = 0;
	while($x = $result->fetch_array())
	{
			
?>
			<?php
			 if ($previous!=$x[2])
			 {
				 if($i>=1)
				 {
					 echo '</div>';
				 }
			?>
        	<h1><?php echo $x[2] ?></h1>
            <hr/>
            <br/>
            <div class="clearfix">
            <?php
			 }
			?>  
                <div data="<?php echo $x[3]?>" class="tile bg-color-darken image album-container">
                    <div class="tile-content">
                        <img src="/images/<?php echo $x[1]?>">
                    </div>
                    <div class="brand bg-color-orangeDark text-center">
                        	<?php echo $x[0] ?>
                    </div>
                </div>
            <?php
			 $previous = $x[2];
			 $i++;
			 ?>
            
<?php
		
	}
?>
            
            <script>
				$(document).ready(function(e){
					$('.album-container').on('click',function(){
						var aid = $(this).attr('data');
						//alert(aid);
						$.post('/view-album.php','aid='+aid).done(function(data){
							$('#container').html(data);
						}).fail(function(){});
					});
				});
			</script>
            
            
            
            
            
            <!------------------------------------------------------------------------------------------------------>
<?php
	require_once('footer.php');
?>