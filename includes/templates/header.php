<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title><?php getTitle(); ?></title>
        <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css" />
        <link rel="stylesheet" href="<?php echo $css; ?>font-awesome.min.css" />
        <link rel="stylesheet" href="<?php echo $css; ?>jquery-ui.css" />
        <link rel="stylesheet" href="<?php echo $css; ?>jquery.selectBoxIt.css" />
        <link rel="stylesheet" href="<?php echo $css; ?>frontend.css" />
    </head>
    <body>
    <div class="upper-bar">
        <div class="container">
            <?php
                if(isset($_SESSION['user'])) {?>
                    <div class="bor">
                        
                        <div class="btn-group my-info">
                            <span class="btn btn-default dorpdown-taggle" data-toggle="dropdown">
                                <img class="img-thumbnail img-circle" src="Icon.png" alt="" />
                                <?php echo $sessionuser; ?>
                                <span class="caret"></span>
                                </span>
                                <ul class="dropdown-menu">
                                    <li><a href="profile.php">My Profile</a></li>
                                    <li><a href="newad.php">New Item</a></li>
                                    <li><a href="profile.php#my-ads">My Items</a></li>
                                    <li><a href="admin/index.php" target="_blank">Login As Admin</a></li>
                                    <li><a href="logout.php">Logout</a></li>
                                </ul>
                        </div>
                    </div>
                    <?php
                    
                    //checkUserStatus($_SESSION['user']);

                    
                }else {
            ?>
            <a href="login.php">
                <span class="pull-right">Login/signup</span>
            </a>
                <?php } ?>
        </div>
    </div>
    <nav class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">Homepage</a>
        </div>
        <div class="collapse navbar-collapse" id="app-nav">
            <ul class="nav navbar-nav navbar-right">
                <?php
                foreach(getCat() as $category){
                    echo 
                    '<li>
                        <a href="categories.php?pageid=' . $category['ID'].'">
                        '. $category["Name"] . '
                        </a>
                    </li>';
                }
                ?>
            </ul>
        </div>
    </div>
</nav>