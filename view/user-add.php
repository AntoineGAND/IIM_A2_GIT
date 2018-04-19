
<?php include 'includes/header.php'; ?>
<body style="background-color: #333;">

	<section id="login-container">
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 midway-horizontal midway-vertical fadeInDown animated">
			<div id="logbox" class="register">
				<h1><i class="fa fa-soundcloud"></i> Inscrivez-vous !</h1>
				<?php include 'includes/alert.php'; ?>
				<form method="POST" action="request/users/add.php">
					<div class="form-input">
						<span class="username">
							<input type="text" name="username" placeholder="Username">
						</span>
					</div>
					<div class="form-input">
						<span class="email">
							<input type="text" name="email" placeholder="Email">
						</span>
					</div>
					<div class="form-input">
						<span class="email">
							<input type="text" name="email1" placeholder="Confirmer email">
						</span>
					</div>
					<div class="form-input">
						<span class="password">
							<input type="password" name="password" placeholder="Password">
						</span>
					</div>
					<div class="form-input">
						<span class="password">
							<input type="password" name="password1" placeholder="Confirmer password">
						</span>
					</div>



					<div class="form-submit">
						<input type="submit" value="Je m'inscris !">	
					</div>
					<p class="account">Vous avez un compte ? <a href="login.php">Connectez-vous !</a></p>
				</form>
			</div>
		</div>
	</section>
	<?php include 'includes/footer.php'; ?>