<?php 
	$error = SESSION::getError();
	if(!is_null($error)){ 
?>
	<div class="alert alert-danger alert-dismissable">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<?php echo $error; ?>
	</div>
<?php 
	}else{
		$succes = SESSION::getSucces();
		if(!is_null($succes)){
?>
		<div class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<?php echo $succes; ?>
		</div>
<?php
	}
} 
?>