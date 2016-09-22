<?php
	require ('db_config.php');
	$mysqli = new MySQLi(DB_HOST,DB_USER,DB_PASS,DB_NAME) or die('Unable to connect. Critical Error');
	//$_POST['aid']=199;
	$Query = "SELECT im.name, im.event_id, im.small_size, im.large_size, ev.name FROM image im INNER JOIN event ev ON im.event_id = ev.id WHERE ev.id=".$_POST['aid'];	
	//echo $Query;
	if($result = $mysqli->query($Query))
	{
	?>
    	
	<?php
		$i=0;
		while($x=$result->fetch_array())
		{
			if($i==0)
			{
				
	?>
    	<div class="page-header">
            <div class="page-header content">
                <a href="/" class="back-button big page-back"></a>
                <h1><?php echo $x[4]?></h1>
            </div>
        </div>
    	<div class="image-collection p4x3">
     <?php
			}
	 ?>
		 	<div class="image" style="cursor:pointer" big="/images/<?php echo $x[3]?>" title="<?php echo $x[0]?>">
				<img src="/images/<?php echo $x[2]?>"/>
			</div>
			
			
	<?php
			$i++;
		}	
	}
	
?>

		</div>
       <script src="/metroui/javascript/dialog.js" type="text/javascript"></script>
       <script type="text/javascript">
	    $(document).ready(function(){
				$('.image').click(function(e) {
                            //alert('Hello');
							var imgbig = $(this).attr('big');
							var title = $(this).attr('title');
							$.Dialog({
                                'title'      : title,
                                'content'    : '<img src="'+imgbig+'">',
								'draggable'	: true,
								'closeButton' : true,
								'position'   : {
                                    'zone'   : 'center'
                                },
                                'buttons'    : {
                                    /*'Ok'    : {
                                        'action': function() {}
                                    },
                                    'Cancel'     : {
                                        'action': function() {}
                                    }*/
                                }
                            });
                        });
			});
	</script>