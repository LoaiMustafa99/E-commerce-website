<?php

    /*
    ================================================
    == Manage Members Page
    == You Can Add | Edit | Delete Members From Here
    ================================================
    */

    ob_start();

session_start();

$pageTitle = 'Members';

if(isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // Start Manage Page

    if($do == 'Manage') { // Manage Page 

        $query = '';

        if(isset($_GET['page']) && $_GET['page'] == 'Pending') {
            $query = 'AND RegStatus = 0';
        }

        // Select All User Excepe4t Admin
    
        $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");

        // Execute The Statment

        $stmt->execute(array());

        // Assign To Variable

        $rows = $stmt->fetchAll();

        if(! empty($rows)){    
    ?>

        <h1 class="text-center">Manage Member</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table manage-members text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Avatar</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Full Name</td>
                        <td>Registerd Date</td>
                        <td>Control</td>
                    </tr>

                    <?php
                        foreach($rows as $row) {

                            echo "<tr>";
                                echo "<td>" . $row['UserID'] . "</td>";
                                echo "<td>";
                                if(empty($row['avatar'])){
                                    echo '<img src="p2.png">';
                                }else {echo "<img src='Uploads/avatars/" . $row['avatar'] . "'>";}
                                echo "</td>";
                                echo "<td>" . $row['Username'] . "</td>";
                                echo "<td>" . $row['Email'] . "</td>";
                                echo "<td>" . $row['FullName'] . "</td>";
                                echo "<td>" . $row['Date'] ."</td>";
                                echo "<td> 
                                    <a href='members.php?do=Edit&userid=". $row['UserID'] ."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                    <a href='members.php?do=Delete&userid=". $row['UserID'] ."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                                    if ($row['RegStatus'] == 0){
                                        echo "<a href='members.php?do=Activate&userid=". $row['UserID'] ."' class='btn btn-info activate'><i class='fa fa-check'></i> Activate</a>";
                                    }
                                echo "</td>";
                            echo "</tr>";

                        }

                    ?>
                </table>
            </div>
            <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>
        </div>

    <?php
        }else{
            echo '<div class="container">';
            echo '<div class="nice-message">Ther\'s No Record To Show</div>';
            echo '<a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>';
            echo '</div>';
        }
 }elseif($do == 'Add'){ // Add Members Page ?>

        

        <h1 class="text-center">Add New Member</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
                    <!-- Start Username Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-3 control-label">Username</label>
                        <div class="col-sm-8">
                            <input type="text" name="username" class="form-control"  autocomplete="off" required="required" placeholder="Enter Username"> 
                        </div>
                    </div>
                    <!-- End Username Field -->
                    <!-- End Password Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-3 control-label">Password</label>
                        <div class="col-sm-8">
                            <input type="password" name="password" class="password form-control" autocomplete="new-password" required="required" placeholder="Enter Password">
                            <i class="show-pass fa fa-eye fa-2x"></i>
                        </div>
                    </div>
                    <!-- End Password Field -->
                    <!-- Start Confirmation Password Field -->
                        <div class="form-group form-group-lg">
                        <label class="col-sm-3 control-label">Password Confirmation</label>
                        <div class="col-sm-8">
                            <input type="password" name="con-password" class="password form-control" autocomplete="new-password" placeholder="Re-type Password" required="required">
                            <i class="show-pass fa fa-eye fa-2x"></i>
                        </div>
                    </div>
                    <!-- End Confirmation Password Field -->
                    <!-- Start Email Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-8">
                            <input type="email" name="email" class="form-control"   autocomplete="off" required="required" placeholder="Enter  Email">
                        </div>
                    </div>
                    <!-- End Email Field -->
                    <!-- Start Full Name Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-3 control-label">Full Name</label>
                        <div class="col-sm-8">
                            <input type="text" name="fullname" class="form-control"  required="required" placeholder= "Enter Full Name">
                        </div>
                    </div>
                    <!-- End Full Name Field -->
                    <!-- Start Avatar Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-3 control-label">User Avatat</label>
                        <div class="col-sm-8">
                            <input type="file" name="avatar" class="form-control"  required="required">
                        </div>
                    </div>
                    <!-- End Avatar Field -->
                    <!-- Start Submit Field -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-3 col-sm-10">
                            <input type="submit" value="Add Members" class="btn btn-primary btn-lg">
                        </div>
                    </div>
                    <!-- End Submit Field -->
                    
    <?php
        } elseif($do == 'Insert'){?>
            <div class="text-center">
            <h1 class="text-center">Insert Member</h1>
        <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                // Upload Variables
                $avatarName = $_FILES['avatar']['name'];
                $avatarSize = $_FILES['avatar']['size'];
                $avatarTmp = $_FILES['avatar']['tmp_name'];
                $avatarType = $_FILES['avatar']['type'];

                // Get Variables From The Form

                $user   = $_POST['username'];
                $pss    = $_POST['password'];
                $email  = $_POST['email'];
                $name   = $_POST['fullname'];

                // List Of Allowed File Typed To Upload
                $avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");

                // Get Avatar Extention
                $avatarExtension = explode('.', $avatarName);
                $file_Exebtion = strtolower(end($avatarExtension)); 

                $shaPass = sha1($_POST['password']);

                // Validate The Form
                
                $forErrors = array();

                if($_POST['con-password'] != $_POST['password']) {
                    $forErrors[] = '<div class="alert alert-danger text-center">The password does not match Please Re-type The Password</div>';
                }
                if (strlen($user) < 4){
                    $forErrors[] = '<div class="alert alert-danger text-center">Error</div>';
                }
                if(! empty($avatarName) && ! in_array($file_Exebtion, $avatarAllowedExtension)){
                    $forErrors[] = '<div class="alert alert-danger text-center">Tihs Extension Is NOT <strong>Allowed</strong></div>';
                }
                if(empty($avatarName)){
                    $forErrors[] = '<div class="alert alert-danger text-center">avatar Is <strong>Required</strong></div>';
                }
                if($avatarSize > 4194304){
                    $forErrors[] = '<div class="alert alert-danger text-center">Avatar Cant Be Larger Than <strong>4MB</strong></div>';
                }
                foreach($forErrors as $error) {
                    echo $error ;
                }

                // If There's No Error Proceed The Insert Operation
                if(empty($forErrors)) {

                    $avatar = rand(0, 100000) . "_" . $avatarName;

                    move_uploaded_file($avatarTmp, "Uploads\avatars\\" . $avatar);
                    
                    // Check If User Exists In Database

                    $check = checkitem("Username","users",$user);

                    if($check == 1){
                        echo "<div class='container'>";
                        $theMsg = "<div class='alert alert-danger text-center'>Sorry This User Is Exist</div>";
                        redirectHome($theMsg, 'back');
                        echo "</div>";

                    }else {

                    // Insert The Database With This Info

                    $stmt = $con->prepare("INSERT INTO 
                                            users (Username, Password, Email, FullName, RegStatus, Date, avatar)
                                            VALUES(:zuser, :zpass, :zmail, :zname, 1, now(), :zavatar) ");
                    $stmt->execute(array(

                        'zuser'     => $user,
                        'zpass'     => $shaPass,
                        'zmail'     => $email,
                        'zname'     => $name,
                        'zavatar'   => $avatar
                    ));
                

                    // Echo Success Message

                    $theMsg = '<div class="alert alert-success text-center">'. '<i class="fa fa-check" aria-hidden="true" fa-2x>' . "</i> " . $stmt->rowCount() . ' Record Inserted' . "</div>";

                    redirectHome($theMsg, 'back');

                }
            }
            
            }else {
                $theMsg = "<div class='alert alert-danger'>Sorry  You Cant Browse This Page Directly</div>";
                
                redirectHome($theMsg);
            }
            echo "</div>";
    } elseif ($do == 'Edit' || $do == 'Update') { //Edit Page 
        $forErrors = array();
        $userid = 0;
        if($do == 'Update' && $_SERVER['REQUEST_METHOD'] == 'POST') {
            $userid = $_POST['userid'];
        }elseif ($do == 'Edit') {
            // check if Get Request userid Is Numeric & Get The Integer Value Of It
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? $_GET['userid'] : 0;
        }

        // Select All Data Depend On This ID
        $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
        
        // Execute Query
        $stmt->execute(array($userid));
        
        // Fetch The Data
        $row = $stmt->fetch();
        
        // The Row Count
        $count = $stmt->rowCount();
        
        // If There's ID Show The Form
        if($stmt->rowCount() > 0){ ?>

            <h1 class="text-center">Edit Member</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="userid" value="<?php echo $userid ?>" />
                    <!-- Start Username Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-9">
                            <span style="color: #e74c72;font-size: 25px;position: relative;height: 0;right: 10px;top: 12px;display: inherit;direction: rtl;user-select: none;" class="req">*</span>
                            <input type="text" name="username" class="form-control" value="<?php echo $row['Username'] ?>" autocomplete="off" required="required"> 
                        </div>
                    </div>
                    <!-- End Old Username Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Old Password</label>
                        <div class="col-sm-9">
                        <span style="color: #e74c72;font-size: 25px;position: relative;height: 0;right: 10px;top: 12px;display: inherit;direction: rtl;user-select: none;" class="req">*</span>
                            <input type="password" name="oldpassword" class="form-control" autocomplete="new-password" required="required">
                        </div>
                    </div>
                    <!-- End Old Password Field -->
                    <!-- Start New Password Field -->
                        <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">New Password</label>
                        <div class="col-sm-9">
                            <input type="hidden" name="oldpass" value="<?php echo $row['Password'] ?>" />
                            <input type="password" name="Newpassword" class="form-control" autocomplete="new-password" placeholder="Leave Blank If You Dont Want To Change">
                        </div>
                    </div>
                    <!-- End New Password Field -->
                    <!-- Start Email Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-9">
                            <span style="color: #e74c72;font-size: 25px;position: relative;height: 0;right: 10px;top: 12px;display: inherit;direction: rtl;user-select: none;" class="req">*</span>
                            <input type="email" name="email" class="form-control" value="<?php echo $row['Email'] ?>"  autocomplete="off" required="required">
                        </div>
                    </div>
                    <!-- End Email Field -->
                    <!-- Start Full Name Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-9">
                            <span style="color: #e74c72;font-size: 25px;position: relative;height: 0;right: 10px;top: 12px;display: inherit;direction: rtl;user-select: none;" class="req">*</span>
                            <input type="text" name="fullname" class="form-control" value="<?php echo $row['FullName'] ?>" required="required">
                        </div>
                    </div>
                    <!-- End Full Name Field -->
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
        echo ' Theres No Such ID';
    }
    // If There's Not Such ID Show Error Message
    
        if ($do == 'Update') {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Get Variables From The Form

            $id     = $_POST['userid'];
            $user   = $_POST['username'];
            $email  = $_POST['email'];
            $name   = $_POST['fullname'];

            // Password Trick

            $pass = empty($_POST['Newpassword']) ? $_POST['oldpass'] :  sha1($_POST['Newpassword']);

            // Validate The Form

            

        if(sha1($_POST['oldpassword']) != $_POST['oldpass']) {
                $forErrors[] = '<div class="alert alert-danger text-center">Please enter the old password correctly</div>';
            }

            foreach($forErrors as $error) {
                echo $error . '</br>';
            }

            // If There's No Error Proceed The Update Operation

            if(empty($forErrors)) {

                $stmt2 = $con->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ?");

                $stmt2->execute(array($user, $id));

                $count = $stmt2->rowCount();

                if($count == 1){
                    echo '<div class="container">';
                    echo "<div class='alert alert-danger'>Sorry This User Is Exist</div>";
                    echo "</div>";
                }else{

                    // Update The Database With This Info
                    $stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?");

                    $stmt->execute(array($user, $email, $name, $pass, $id ));
    
                    // Echo Success Message
    
                    echo '<div class="alert alert-success text-center">'. '<i class="fa fa-check" aria-hidden="true" fa-2x>' . "</i> " . $stmt->rowCount() . ' Record Updated' . "</div>";
                }

            }

        }else {
            $theMsg = "Sorry  You Cant Browse This Page Directly";

            redirectHome($theMsg);
        }
    }
    }elseif($do == 'Delete'){
            // Delete Memeber Page?>
            <h1 class="text-center">Delete Member</h1>
            <div class="container"><?php
        // check if Get Request userid Is Numeric & Get The Integer Value Of It
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? $_GET['userid'] : 0;

        // Select All Data Depend On This ID

        $check = checkitem("userid","users",$userid);

        // If There's ID Show The Form

        if($check > 0){
            $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");
            $stmt->bindParam(':zuser', $userid);
            $stmt->execute();
            $theMsg = '<div class="alert alert-success text-center">'. '<i class="fa fa-check" aria-hidden="true" fa-2x>' . "</i> " . $stmt->rowCount() . ' Record Deleted' . "</div>";
            redirectHome("$theMsg", 'back');
        }else {
            $theMsg = "<div class='alert alert-danger'>This ID Is Not Exist</div>";
            redirectHome("$theMsg");
        }
        echo "</div>";
    }elseif($do == 'Activate') {
            // Activate Memeber Page?>
            <h1 class="text-center">Activate Member</h1>
            <div class="container"><?php
            // check if Get Request userid Is Numeric & Get The Integer Value Of It
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? $_GET['userid'] : 0;

            // Select All Data Depend On This ID
            
            $check = checkitem("userid","users",$userid);

            // If There's ID Show The Form

            if($check > 0){
                $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
                $stmt->execute(array($userid));
                $theMsg = '<div class="alert alert-success text-center">'. '<i class="fa fa-check" aria-hidden="true" fa-2x>' . "</i> " . $stmt->rowCount() . ' Record Activated' . "</div>";
                redirectHome("$theMsg");
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