<nav class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="dashbord.php"><?php echo lang('Home_Admin') ?></a>
        </div>
        <div class="collapse navbar-collapse" id="app-nav">
            <ul class="nav navbar-nav">
                <li><a href="categories.php"><?php echo lang('Home_Categories') ?></a></li>
                <li><a href="items.php"><?php echo lang('Home_ITEMS') ?></a></li>
                <li><a href="members.php"><?php echo lang('Home_MEMBERS') ?></a></li>
                <li><a href="comments.php"><?php echo lang('Home_Comment') ?></a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo lang('Home_Loai') ?> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a target="_blanck" href="../index.php">Visit Shop</a></li>
                        <li><a href="members.php?do=Edit&userid=<?php echo $_SESSION['ID'] ?>"><?php echo lang('Edit_Profile') ?></a></li>
                        <li><a href="#"><?php echo lang('Home_Settings') ?></a></li>
                        <li><a href="logout.php"><?php echo lang('Home_Logout') ?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>