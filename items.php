<?php
    ob_start();
    session_start();
    $pageTitle = 'Show Items';
    include 'init.php';
    // check if Get Request userid Is Numeric & Get The Integer Value Of It
    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? $_GET['itemid'] : 0;
    

    // Select All Data Depend On This ID
    $stmt = $con->prepare("SELECT 
                                items.*, categories.Name AS category_name,
                                users.Username
                            FROM 
                                items
                            INNER JOIN
                                categories
                            ON
                                categories.ID = items.Cat_ID
                            INNER JOIN
                                users
                            ON
                                users.UserID = items.Member_ID
                            WHERE 
                                Item_ID = ?
                            And 
                                Approve = 1");
    
    // Execute Query
    $stmt->execute(array($itemid));
    
    // The Row Count
    $count = $stmt->rowCount();
    
    // If There's ID Show The Form
    if($count > 0){
        
        // Fetch The Data
        $item = $stmt->fetch();
        
?>
<h1 class="text-center"><?php echo $item['Name']; ?></h1>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <img class="img-responsive img-thumbnail center-block" src="p2.png" alt="" />
        </div>
        <div class="col-md-8 item-info">
            <h2><?php echo $item['Name']; ?></h2>
            <p><?php echo $item['Description']; ?></p>
            <ul class="list-unstyled">
                <li>
                    <i class="fa fa-calendar fa-fw"></i>
                    <span>Added Date</span> : <?php echo $item['Add_Date']; ?>
                </li>
                <li>
                    <i class="fa fa-money fa-fw"></i>
                    <span>Price</span> : <?php echo $item['Price']; ?>
                </li>
                <li>
                    <i class="fa fa-globe fa-fw"></i>
                    <span>Made In</span> : <?php echo $item['Country_Made']; ?>
                </li>
                <li>
                    <i class="fa fa-tags fa-fw"></i>
                    <span>Category</span> : <a href="categories.php?pageid=<?php echo $item['Cat_ID']?>"><?php echo $item['category_name']; ?></a>
                </li>
                <li>
                    <i class="fa fa-user-plus fa-fw"></i>
                    <span>Add By</span> : <a href="#"><?php echo $item['Username']; ?></a>
                </li>
                <li class="tags-items">
                    <i class="fa fa-user-plus fa-fw"></i>
                    <span>Tags</span> : 
                    <?php
                    $alltags = explode(",", $item['tags']);
                    foreach($alltags as $tag){
                        $tag = str_replace(' ','', $tag);
                        if(! empty($tag)){
                        echo "<a href='tags.php?name=" . strtolower($tag) ."'>" . $tag . '</a>';
                        }
                    }
                    ?>
                </li>
            </ul>
        </div>
    </div>
    <hr>
    <?php if(isset($_SESSION['user'])) { ?>
    <!-- Start Add Comment -->
    <div class="row">
            <div class="col-md-offset-3">
                <div class="add-comment">
                    <h3>Add Your Comment</h3>
                    <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' .$item['Item_ID'] ?>" method="POST">
                        <textarea resize="off" name="comment" required></textarea>
                        <input class="btn btn-primary" type="submit" value="Add Comment">
                    </form>
                    <?php
                        if($_SERVER['REQUEST_METHOD'] == 'POST'){

                            $comment    = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                            $userid     = $_SESSION['uid'];
                            $itemid     = $item['Item_ID'];

                            if(! empty($comment)){

                                $stmt = $con->prepare("INSERT INTO
                                    comments(comment, status, comment_date, item_id, `user_id`)
                                    VALUE(:zcomment, 1, NOW(), :zitemid, :zuser)");

                                    $stmt->execute(array(

                                        'zcomment'  => $comment,
                                        'zitemid'   => $itemid,
                                        'zuser'     => $userid

                                    ));

                                    if($stmt) {
                                        echo '<div class="alert alert-success">Comment Added</div>';
                                    }
                                    

                            }else{
                            
                            echo '<div class="alert alert-danger">Please Enter Your Comment</div>';
                        }
                        }
                    ?>
                </div>
            </div>
        </div>
    <!-- End Add Comment -->
    <?php }else{
        echo '<a href="login.php">Login</a> or <a Register href="login.php">Register</a> To Add Comment';
    } ?>
    <hr>
    <h1 class = "text-center">Comments</h1>
    <?php
            // Select All User Excepe4t Admin
            $stmt = $con->prepare("SELECT 
                                    comments.*,
                                    users.Username AS Member
                                FROM 
                                    comments
                                INNER JOIN 
                                    items
                                ON
                                    items.Item_ID = comments.item_id
                                INNER JOIN 
                                    users
                                ON
                                    users.UserID = comments.user_id
                                WHERE
                                    comments.item_id = ?
                                ORDER BY
                                    c_id DESC");
            // Execute The Statment
            $stmt->execute(array($item['Item_ID']));
            // Assign To Variable
            $comments = $stmt->fetchAll();
    ?>
        
    <?php foreach($comments as $comment) {?>
                <div id="comment" class="comment-box">
                    <div class="row">
                        <div class="col-sm-2 text-center">
                            <img class="img-responsive img-thumbnail img-circle center-block" src="p2.png" alt="" />
                            <?php echo $comment['Member']?>
                        </div>
                        <div class="col-sm-10">
                            <p class="lead">
                                <?php echo $comment['comment'] ?>
                            </p>
                        </div>
                    </div>
                </div>
                <hr>
        <?php }?>
</div>

<?php
    }else{
        echo '<div class="container">';
        $theMsg = '<div class="alert alert-danger">Ther\'s No Such ID Or This Item Waiting Approval</div>';
        redirectHome("$theMsg", 'back');
        echo '</div>';
    }

include $tpl . "footer.php";

ob_end_flush();

?>