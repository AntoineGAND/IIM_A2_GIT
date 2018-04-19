<?php include 'includes/header.php'; ?>
<body>
<?php include 'includes/topbar.php'; ?>
<div class="container">
	<?php include 'includes/alert.php'; ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div id="musicfeed">
                <h1><i class="fa fa-clock-o"></i>MUSIC NAME</h1>
                    <div class="music animated fadeInDown" data-src="<?php echo $music['file']; ?>">
                        <div class="row">
                            <div class="col-xs-2 col-sm-2 col-md-1 col-lg-1">
                                <div class="author">
									<img src="<?php echo $music['user']['avatar']; ?>" alt="">
                                </div>
                            </div>
                            <div class="col-xs-10 col-sm-10 col-md-11 col-lg-11">
                                <div class="pull-right">
                                    <ul class="list-inline actionicon">
										<?php if(!is_null(SESSION::getUserID())){ ?>
											<?php if ($music['liked'] === false){ ?>
												<li><a href="request/musics/like.php?id=<?php echo $music['id']; ?>">Like</a></li>
											<?php }else{ ?>
												<li><a href="request/musics/like.php?id=<?php echo $music['id']; ?>">Unlike</a></li>
											<?php } ?>
										<?php } ?>
                                        <li><span class="badge badge-primary"><?php echo $music['nbr-likes']; ?> like<?php if($music['nbr-likes'] > 1){ ?>s<?php } ?></span></li>
                                        <li><span class="badge badge-primary"><?php echo $music['nbr-comments']; ?> commentaire<?php if($music['nbr-comments'] > 1){ ?>s<?php } ?></span></li>
                                        <?php if(MUSIC::isOwn($music['user']['id'])){ ?>
											<li><a href="music-edit.php?id=<?php echo $music['id']; ?>"><i class="fa fa-pencil"></i></a></li>
                                            <li><a href="request/musics/delete.php?id=<?php echo $music['id']; ?>"><i class="fa fa-times"></i></a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <b class="username">Posté par <?php echo $music['user']['username']; ?></b>
                                <h3 class="title"><?php echo $music['title']; ?></h3>
                                <p class="clearfix">
                                    <small class="date pull-right"><i class="fa fa-clock-o"></i> <?php echo $music['created_at']; ?></small>
                                </p>
                            </div>


                            <div class="card">
                                <div class="card-header">
                                    Commentaires
                                </div>
                                <ul class="list-group list-group-flush">
								
								
									<?php
										$i = 0;
										$max = count($music['comments']);
									?>
									<?php while($i < $max){ ?>
								
										<li class="list-group-item"><strong><?php echo $music['comments'][$i]['user']['username']; ?>: </strong><?php echo $music['comments'][$i]['comment']; ?></li>
                
									
									<?php $i++; } ?>
                                </ul>

                                <form method="POST" action="request/musics/comment/add.php">
                                    <div align="center">
										<div class="form-input">
											<span class="text-field">
												<input type="text" name="comment" placeholder="Ecrivez votre commentaire...">
											</span>
										</div>

										<div class="forme-input">
											<input type="submit" value="Envoyer">
											<input type="hidden" name="id" value="<?php echo $music['id'] ?>">
										</div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include 'includes/footer.php'; ?>


