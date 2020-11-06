<?php
/*
================================================
== Manage Comments Page
== You Can Edit | Delete Comments From Here
================================================
*/

ob_start();

session_start();

$pageTitle = 'Comments';

if(isset($_SESSION['Username'])) {

include 'init.php';

$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

// Start Manage Page

if($do == 'Manage') { // Manage Page 

    // Select All User Excepe4t Admin

    $stmt = $con->prepare("SELECT 
                                comments.*,
                                items.Name AS Item_Name,
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
                            ORDER BY
                                c_id DESC");

    // Execute The Statment

    $stmt->execute();

    // Assign To Variable

    $comments = $stmt->fetchAll();

    if(! empty($comments)){

?>

    <h1 class="text-center">Manage Comments</h1>
    <div class="container">
        <div class="table-responsive">
            <table class="main-table text-center table table-bordered">
                <tr>
                    <td>ID</td>
                    <td>Comment</td>
                    <td>Item Name</td>
                    <td>User Name</td>
                    <td>Added Date</td>
                    <td>Control</td>
                </tr>

                <?php
                    foreach($comments as $comment) {

                        echo "<tr>";
                            echo "<td>" . $comment['c_id'] . "</td>";
                            echo "<td>" . $comment['comment'] . "</td>";
                            echo "<td>" . $comment['Item_Name'] . "</td>";
                            echo "<td>" . $comment['Member'] . "</td>";
                            echo "<td>" . $comment['comment_date'] ."</td>";
                            echo "<td> 
                                <a href='comments.php?do=Edit&comid=". $comment['c_id'] ."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                <a href='comments.php?do=Delete&comid=". $comment['c_id'] ."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                                if ($comment['status'] == 0){
                                    echo "<a href='comments.php?do=Approve&comid=". $comment['c_id'] ."' class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";
                                }
                            echo "</td>";
                        echo "</tr>";
                    }
                ?>
            </table>
        </div>
    </div>

<?php
    }else{
            echo '<div class="container">';
            echo '<div class="nice-message">Ther\'s No Record To Show</div>';
            echo '</div>';
    }
} elseif ($do == 'Edit') { //Edit Page 
    
        // check if Get Request userid Is Numeric & Get The Integer Value Of It
        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? $_GET['comid'] : 0;

        // Select All Data Depend On This ID
        $stmt = $con->prepare("SELECT * FROM comments WHERE c_id = ?");

        // Execute Query
        $stmt->execute(array($comid));

        // Fetch The Data
        $row = $stmt->fetch();

        // The Row Count
        $count = $stmt->rowCount();

        // If There's ID Show The Form
        if($stmt->rowCount() > 0){ ?>

            <h1 class="text-center">Edit Comments</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="comid" value="<?php echo $comid ?>" />
                    <!-- Start Comment Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Comment</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="comment"><?php echo $row['comment'] ?></textarea>
                        </div>
                    </div>
                    <!-- End Comment Field -->
                    <!-- Start Submit Field -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Save" class="btn btn-primary btn-lg">
                        </div>
                    </div>
                    <!-- End Submit Field -->
                </form>
            </div>
    <?php 
    }else {
        echo '<div class="container">';
        $theMsg = '<div class="alert alert-danger">Theres No Such ID</div>';
        redirectHome($theMsg);
        echo '</div>';
    }   
// If There's Not Such ID Show Error Message

    }elseif ($do == 'Update') {

        echo '<h1 class="text-center">Update Comments</h1>';
        echo '<div class="container">';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Get Variables From The Form
        $comid     = $_POST['comid'];
        $comment   = $_POST['comment'];

        // If There's No Error Proceed The Update Operation

        $stmt = $con->prepare("UPDATE comments SET comment = ? WHERE c_id = ?");

        $stmt->execute(array($comment,$comid));

        // Echo Success Message

        $theMsg = '<div class="alert alert-success text-center">'. '<i class="fa fa-check" aria-hidden="true" fa-2x>' . "</i> " . $stmt->rowCount() . ' Record Updated' . "</div>";

        redirectHome($theMsg, 'back');

    }else {
        $theMsg = "Sorry  You Cant Browse This Page Directly";

        redirectHome($theMsg);
    }

    }elseif($do == 'Delete'){
        // Delete Memeber Page?>
        <h1 class="text-center">Delete Comments</h1>
        <div class="container"><?php
    // check if Get Request comid Is Numeric & Get The Integer Value Of It
    $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? $_GET['comid'] : 0;

    // Select All Data Depend On This ID

    $check = checkitem("c_id","comments",$comid);

    // If There's ID Show The Form

    if($check > 0){
        $stmt = $con->prepare("DELETE FROM comments WHERE c_id = :zcomid");
        $stmt->bindParam(':zcomid', $comid);
        $stmt->execute();
        $theMsg = '<div class="alert alert-success text-center">'. '<i class="fa fa-check" aria-hidden="true" fa-2x>' . "</i> " . $stmt->rowCount() . ' Record Deleted' . "</div>";
        redirectHome("$theMsg",'back');
    }else {
        $theMsg = "<div class='alert alert-danger'>This ID Is Not Exist</div>";
        redirectHome("$theMsg");
    }
    echo "</div>";
}elseif($do == 'Approve') {
        // Activate Memeber Page?>
        <h1 class="text-center">Approve Comments</h1>
        <div class="container"><?php
        // check if Get Request comid Is Numeric & Get The Integer Value Of It
        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? $_GET['comid'] : 0;

        // Select All Data Depend On This ID
        
        $check = checkitem("c_id","comments",$comid);

        // If There's ID Show The Form

        if($check > 0){
            $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ?");
            $stmt->execute(array($comid));
            $theMsg = '<div class="alert alert-success text-center">'. '<i class="fa fa-check" aria-hidden="true" fa-2x>' . "</i> " . $stmt->rowCount() . ' Record Activated' . "</div>";
            redirectHome("$theMsg",'back');
        }else {
            $theMsg = "<div class='alert alert-danger'>This ID Is Not Exist</div>";
            redirectHome("$theMsg");
        }
        echo "</div>";
}
include $tpl . "footer.php";
} else {
header('Location: index.php');
exit();
}

ob_end_flush();