<?php
 require_once '../../header.php' ;
?>
	<h2 id="pageTitle">Upload Photos to Album : <?php echo $_POST['eventName'] ?></h2>
    <hr/>
    <div class="clearfix padding10"></div>
	<form action="/admin/logic/add_photos.php" method="post" enctype="multipart/form-data">
    	<input type="hidden" name="eventId" value="<?php echo $json['eventId']?>">
        <input type="hidden" name="eventTypeId" value="<?php echo $_POST['eventType']?>">
        <h3>Select Files to Upload</h3>
        
        <div class="input-control select">
        	<input type="file" name="file[]" multiple>
        </div>
        
        <input type="submit" class="button" value="Upload">
    </form>
<?php
	require_once '../../footer.php';
?>