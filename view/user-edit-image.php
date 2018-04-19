<?php
	$user = USER::get(['id' => SESSION::getUserId()]);
	
?>


<?php include 'includes/header.php'; ?>
<body>
	<?php include 'includes/topbar.php'; ?>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div id="musicfeed">
					<h1><i class="fa fa-pencil"></i> Editer votre image de profil</h1>
					<div class="block animated fadeInDown">
						<div class="row">
							<div class="col-xs-2 col-sm-2 col-md-1 col-lg-1">
								<div class="author">
									<img src="<?php echo USER::getAvatar($user['id']);?>" alt="">
								</div>
							</div>
							<div class="col-xs-10 col-sm-10 col-md-11 col-lg-11">

								<?php include 'includes/alert.php'; ?>

								<b class="username"><?php echo $user['username']; ?></b>
								<p>
									<br>
									Extensions autorisées : .jpg, .png et .gif
								</p>
								<form action="request/users/avatar/edit.php" method="POST" enctype="multipart/form-data">
									<input type="file" name="image">
									<p class="clearfix"><button type="submit" class="valid pull-right"><i class="fa fa-check"></i> Valider</button></p>
								</form>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php include 'includes/footer.php'; ?>