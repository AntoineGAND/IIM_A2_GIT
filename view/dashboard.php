<body>
	<?php include '_topbar.php'; ?>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div id="musicfeed">
					<h1><i class="fa fa-clock-o"></i> Sound Feed</h1>
					<?php foreach($musics as $music){ ?>
						<div class="music animated fadeInDown" data-src="<?php echo $music['file']; ?>">
							<div class="row">
								<div class="col-xs-2 col-sm-2 col-md-1 col-lg-1">
									<div class="author">
										<?php 
											echo '<img class="" src="'.Dir::getParent().$music['user']['avatar'].'" alt="">';
										?>
									</div>
								</div>
								<div class="col-xs-10 col-sm-10 col-md-11 col-lg-11">
									<div class="pull-right">
										<ul class="list-inline actionicon">
										<?php
                                        echo '<li><span class="badge badge-primary">Like</span></li>';
                                        if(MUSIC::isOwn($music['id'])){
											echo '<li><a href="edit.php?id='.$music['id'].'&&user_id='.$music['user_id'].'"><i class="fa fa-pencil"></i></a></li>';
											echo '<li><a href="delete.php?id='.$music['id'].'"><i class="fa fa-times"></i></a></li>';
										} ?>
										</ul>
									</div>
									<b class="username">Posté par <?php echo $music['user']['username']; ?></b>
                                    <a href="<?php echo "music.php?id=".$music['id'];?>">
                                    <h3 class="title">

										<?php echo $music['title']; ?>
									</h3>
                                    </a>
									<p class="clearfix">
										<small class="date pull-right"><i class="fa fa-clock-o"></i> <?php echo $music['created_at']; ?></small>
									</p>
								</div>
							</div>
						</div>
					<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>


