<body>
<?php include '_topbar.php'; ?>
<div class="container">

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div id="musicfeed">
                <?php foreach($musics as $music){ ?>
                <h1><?php echo $music['title']; ?> </h1>
                    <div class="music animated fadeInDown" data-src="<?php echo $music['file']; ?>">
                        <div class="row">
                            <div class="col-xs-2 col-sm-2 col-md-1 col-lg-1">
                                <div class="author">
                                    <?php
                                    $sql = "SELECT picture FROM users WHERE id = :id LIMIT 1";
                                    $req = $db->prepare($sql);
                                    $req->execute(array(
                                        ':id' => $music['user_id']
                                    ));
                                    $result = $req->fetch(PDO::FETCH_ASSOC);
                                    if(!empty($result)){
                                        echo '<img class="" src="'.$result['picture'].'" alt="">';
                                    }
                                    else{
                                        echo '<img src="view/profil_pic/undefined.jpg" alt=""></a>';
                                    }
                                    ?>

                                </div>
                            </div>
                            <div class="col-xs-10 col-sm-10 col-md-11 col-lg-11">
                                <div class="pull-right">
                                    <ul class="list-inline actionicon">

                                        <?php
                                        echo '<li><span class="badge badge-primary">Like</span></li>';
                                        if($music['user_id'] == $_SESSION['id']){
                                            echo '<li><a href="edit.php?id='.$music['id'].'&&user_id='.$music['user_id'].'"><i class="fa fa-pencil"></i></a></li>';
                                            echo '<li><a href="delete.php?id='.$music['id'].'"><i class="fa fa-times"></i></a></li>';
                                        } ?>
                                    </ul>
                                </div>
                                <b class="username">Posté par <?php echo $music['username']; ?></b>
                                <h3 class="title">
                                    <?php echo $music['title']; ?>
                                </h3>
                                <p class="clearfix">
                                    <small class="date pull-right"><i class="fa fa-clock-o"></i> <?php echo $music['created_at']; ?></small>
                                </p>
                            </div>


                            <div class="card">
                                <div class="card-header">
                                    Commentaires
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">Cras justo odio</li>
                                    <li class="list-group-item">Dapibus ac facilisis in</li>
                                    <li class="list-group-item">Vestibulum at eros</li>
                                </ul>

                                <form method="POST" action="music.php">
                                    <div align="center">
                                    <div class="form-input">
                                        <span class="text-field">
                                            <input type="text" name="text_field" placeholder="Ecrivez votre commentaire">
                                        </span>
                                    </div>

                                    <div class="forme-input">
                                        <input type="submit" value="Envoyer">
                                        <input type="hidden" value="<?php echo $music['id'] ?>">
                                    </div>
                                    </div>

                                    <p class="account">Vous n'avez pas de compte ? <a href="register.php">Inscrivez vous !</a></p>
                                </form>
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

