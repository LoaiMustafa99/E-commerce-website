<?php

/*
================================================
== Items Page
================================================
*/

ob_start();

session_start();

$pageTitle = 'Items';

if(isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // Start Manage Page

    if($do == 'Manage') { 
    
        $stmt = $con->prepare("SELECT 
                                    items.*, 
                                    categories.Name AS cate_name, 
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
                                ORDER BY 
                                    Item_ID DESC");

        // Execute The Statment

        $stmt->execute(array());

        // Assign To Variable

        $items = $stmt->fetchAll();
        if(! empty($items)){
    ?>
        <h1 class="text-center">Manage Items</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Name</td>
                        <td>Description</td>
                        <td>Price</td>
                        <td>Adding Date</td>
                        <td>Category</td>
                        <td>Username</td>
                        <td>Comments</td>
                        <td>Control</td>
                    </tr>
                    <?php
                        foreach($items as $item) {
                            echo "<tr>";
                                echo "<td>" . $item['Item_ID'] . "</td>";
                                echo "<td>" . $item['Name'] . "</td>";
                                echo "<td>" . $item['Description'] . "</td>";
                                echo "<td>$" . $item['Price'] . "</td>";
                                echo "<td>" . $item['Add_Date'] ."</td>";
                                echo "<td>" . $item['cate_name'] ."</td>";
                                echo "<td>" . $item['Username'] ."</td>";
                                echo "<td>". '<a style="text-decoration: none;color: #f44336;" href="items.php?do=comment&itemid=' . $item['Item_ID'] . '">' . checkitem('item_id','comments', $item['Item_ID']) . '</a>' ."</td>";
                                echo "<td> 
                                    <a href='items.php?do=Edit&itemid=". $item['Item_ID'] ."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                    <a href='items.php?do=Delete&itemid=". $item['Item_ID'] ."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                                    if ($item['Approve'] == 0){
                                        echo "<a href='items.php?do=Approve&itemid=". $item['Item_ID'] ."' class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";
                                    }
                                echo "</td>";
                            echo "</tr>";
                        }
                    ?>
                </table>
            </div>
            <a href="items.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add New Item</a>
        </div>

    <?php
        }else{
            echo '<div class="container">';
            echo '<div class="nice-message">Ther\'s No Record To Show</div>';
            echo '<a href="items.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add New Item</a>';
            echo '</div>';
        }
    } elseif($do == 'Add') {?>
        
        <h1 class="text-center">Add New Item </h1>
        <div class="container">
        <form class="form-horizontal" action="?do=Insert" method="POST">
            <!-- Start Name Field -->
            <div class="form-group form-group-lg">
                <label class="col-sm-3 control-label">Name</label>
                <div class="col-sm-8">
                    <input 
                    type="text" 
                    name="name" 
                    class="form-control" 
                    required="required" 
                    placeholder="Name Of The Item"> 
                </div>
            </div>
            <!-- End Name Field -->
            <!-- Start Description Field -->
            <div class="form-group form-group-lg">
                <label class="col-sm-3 control-label">Description</label>
                <div class="col-sm-8">
                    <input 
                    type="text" 
                    name="description" 
                    class="form-control" 
                    required="required" 
                    placeholder="DEscription Of The Item"> 
                </div>
            </div>
            <!-- End Description Field -->
            <!-- Start Price Field -->
            <div class="form-group form-group-lg">
                <label class="col-sm-3 control-label">Price</label>
                <div class="col-sm-8">
                    <input 
                    type="text" 
                    name="price" 
                    class="form-control" 
                    required="required" 
                    placeholder="Price Of The Item"> 
                </div>
            </div>
            <!-- End Price Field -->
            <!-- Start Country_made Field -->
            <div class="form-group form-group-lg">
                <label class="col-sm-3 control-label">Country</label>
                <div class="col-sm-8">
                    <input 
                    type="text" 
                    name="country" 
                    class="form-control" 
                    required="required" 
                    placeholder="Country Of Made"> 
                </div>
            </div>
            <!-- End Country_made Field -->
            <!-- Start Status Field -->
            <div class="form-group form-group-lg">
                <label class="col-sm-3 control-label">Status</label>
                <div class="col-sm-8">
                    <select name="status">
                        <option value="0">...</option>
                        <option value="1">New</option>
                        <option value="2">Link New</option>
                        <option value="3">Used</option>
                        <option value="4">Very Old</option>
                    </select>
                </div>
            </div>
            <!-- End Status Field -->
            <!-- Start Members Field -->
            <div class="form-group form-group-lg">
                <label class="col-sm-3 control-label">Members</label>
                <div class="col-sm-8">
                    <select name="members">
                        <option value="0">...</option>
                        <?php

                            $stmt = $con->prepare("SELECT * FROM users");
                            $stmt->execute();
                            $users = $stmt->fetchAll();
                            foreach($users as $user){
                                echo "<option value= '" . $user['UserID'] . "'>" . $user['Username'] . "</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>
            <!-- End Members Field -->
            <!-- Start Category Field -->
            <div class="form-group form-group-lg">
                <label class="col-sm-3 control-label">Category</label>
                <div class="col-sm-8">
                    <select name="category">
                        <option value="0">...</option>
                        <?php
                            $stmt = $con->prepare("SELECT * FROM categories WHERE Parent = 0 ORDER BY ID DESC");
                            $stmt->execute();
                            $allcat = $stmt->fetchAll();
                            foreach($allcat as $cat){
                                echo "<option value= '" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                                $stmt = $con->prepare("SELECT * FROM categories WHERE Parent = {$cat['ID']} ORDER BY ID DESC");
                                $stmt->execute();
                                $childCat = $stmt->fetchAll();
                                foreach($childCat as $child) {
                                    echo "<option value= '" . $child['ID'] . "'>-- " . $child['Name'] . "</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
            <!-- End Category Field -->
            <!-- Start Tags Field -->
            <div class="form-group form-group-lg">
                <label class="col-sm-3 control-label">Tags</label>
                <div class="col-sm-8">
                    <input 
                    type="text" 
                    name="tags" 
                    class="form-control"  
                    placeholder="Sperate Tags With Comma (,)"> 
                </div>
            </div>
            <!-- End Tags Field -->
            <!-- Start Submit Field -->
            <div class="form-group form-group-lg">
                <div class="col-sm-offset-3 col-sm-10">
                    <input type="submit" value="Add Item" class="btn btn-primary btn-sm">
                </div>
            </div>
            <!-- End Submit Field -->
            </form>
        </div>
    <?php
    } elseif($do == 'Insert') {?>
        
        <div class="text-center">
        <h1 class="text-center">Insert Item</h1>
    <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Get Variables From The Form

            $name       = $_POST['name'];
            $desc       = $_POST['description'];
            $price      = $_POST['price'];
            $country    = $_POST['country'];
            $status     = $_POST['status'];
            $member     = $_POST['members'];
            $category   = $_POST['category'];
            $tags       = $_POST['tags'];

            // Validate The Form
            
            $forErrors = array();

            if (empty($name)){
                $forErrors[] = 'Name Can\'t be <strong>Empty</strong>';
            }
            if (empty($desc)){
                $forErrors[] = 'Description Can\'t be <strong>Empty</strong>';
            }
            if (empty($price)){
                $forErrors[] = 'Price Can\'t be <strong>Empty</strong>';
            }
            if (empty($country)){
                $forErrors[] = 'country Can\'t be <strong>Empty</strong>';
            }
            if ($status == 0){
                $forErrors[] = 'You Must Choose The <strong>Status</strong>';
            }
            if ($category == 0){
                $forErrors[] = 'You Must Choose The <strong>Category</strong>';
            }
            foreach($forErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>' ;
            }

            // If There's No Error Proceed The Insert Operation

            if(empty($forErrors)) {

                // Insert The Database With This Info

                $stmt = $con->prepare("INSERT INTO 
                                        items (Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID, tags)
                                        VALUES(:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember, :ztags) ");
                $stmt->execute(array(

                    'zname'     => $name,
                    'zdesc'     => $desc,
                    'zprice'    => $price,
                    'zcountry'  => $country,
                    'zstatus'   => $status,
                    'zcat'      => $category,
                    'zmember'   => $member,
                    'ztags'     => $tags

                ));
            

                // Echo Success Message

                $theMsg = '<div class="alert alert-success text-center">'. '<i class="fa fa-check" aria-hidden="true" fa-2x>' . "</i> " . $stmt->rowCount() . ' Record Inserted' . "</div>";

                redirectHome($theMsg, 'back');

        }

        }else {
            $theMsg = "<div class='alert alert-danger'>Sorry  You Cant Browse This Page Directly</div>";
            
            redirectHome($theMsg);
        }
        echo "</div>";

    }elseif($do == 'Edit'){

        // check if Get Request userid Is Numeric & Get The Integer Value Of It
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? $_GET['itemid'] : 0;
        

        // Select All Data Depend On This ID
        $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ?");
        
        // Execute Query
        $stmt->execute(array($itemid));
        
        // Fetch The Data
        $item = $stmt->fetch();
        
        // The Row Count
        $count = $stmt->rowCount();
        
        // If There's ID Show The Form
        if($count > 0){ ?>
            <h1 class="text-center">Edit Item </h1>
            <div class="container">
            <form class="form-horizontal" action="?do=Update" method="POST">
                <input type="hidden" name="itemid" value="<?php echo $itemid ?>" />
                <!-- Start Name Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-3 control-label">Name</label>
                    <div class="col-sm-8">
                        <input 
                        type="text" 
                        name="name" 
                        class="form-control" 
                        required="required" 
                        placeholder="Name Of The Item" 
                        value="<?php echo $item['Name'] ?>"
                        >
                    </div>
                </div>
                <!-- End Name Field -->
                <!-- Start Description Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-3 control-label">Description</label>
                    <div class="col-sm-8">
                        <input 
                        type="text" 
                        name="description" 
                        class="form-control" 
                        required="required" 
                        placeholder="DEscription Of The Item"
                        value="<?php echo $item['Description'] ?>"
                        > 
                    </div>
                </div>
                <!-- End Description Field -->
                <!-- Start Price Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-3 control-label">Price</label>
                    <div class="col-sm-8">
                        <input 
                        type="text" 
                        name="price" 
                        class="form-control" 
                        required="required" 
                        placeholder="Price Of The Item"
                        value="<?php echo $item['Price'] ?>"
                        > 
                    </div>
                </div>
                <!-- End Price Field -->
                <!-- Start Country_made Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-3 control-label">Country</label>
                    <div class="col-sm-8">
                        <input 
                        type="text" 
                        name="country" 
                        class="form-control" 
                        required="required" 
                        placeholder="Country Of Made"
                        value="<?php echo $item['Country_Made'] ?>"
                        > 
                    </div>
                </div>
                <!-- End Country_made Field -->
                <!-- Start Status Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-3 control-label">Status</label>
                    <div class="col-sm-8">
                        <select name="status">
                            <option value="1" <?php if($item['Status'] == 1){echo 'selected';} ?>>New</option>
                            <option value="2" <?php if($item['Status'] == 2){echo 'selected';} ?>>Link New</option>
                            <option value="3" <?php if($item['Status'] == 3){echo 'selected';} ?>>Used</option>
                            <option value="4" <?php if($item['Status'] == 4){echo 'selected';} ?>>Very Old</option>
                        </select>
                    </div>
                </div>
                <!-- End Status Field -->
                <!-- Start Members Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-3 control-label">Members</label>
                    <div class="col-sm-8">
                        <select name="members">
                            <?php
                                $stmt = $con->prepare("SELECT * FROM users");
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach($users as $user){
                                    echo "<option value= '" . $user['UserID'] . "'";
                                    if($item['Member_ID'] == $user['UserID']){echo 'selected';}
                                    echo ">" . $user['Username'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- End Members Field -->
                <!-- Start Category Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-3 control-label">Category</label>
                    <div class="col-sm-8">
                        <select name="category">
                            <?php
                                $stmt = $con->prepare("SELECT * FROM categories");
                                $stmt->execute();
                                $cats = $stmt->fetchAll();
                                foreach($cats as $cat){
                                    echo "<option value= '" . $cat['ID'] . "'";
                                    if($item['Cat_ID'] == $cat['ID']){echo 'selected';}
                                    echo ">" . $cat['Name'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- End Category Field -->
                <!-- Start Tags Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-3 control-label">Tags</label>
                    <div class="col-sm-8">
                        <input 
                        type="text" 
                        name="tags" 
                        class="form-control"  
                        placeholder="Sperate Tags With Comma (,)"
                        value="<?php echo $item['tags'] ?>" > 
                    </div>
                </div>
                <!-- End Tags Field -->
                <!-- Start Submit Field -->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-3 col-sm-10">
                        <input type="submit" value="Edit Item" class="btn btn-primary btn-sm">
                    </div>
                </div>
                <!-- End Submit Field -->
                </form>
            
    <?php 
    }else {
        echo "<div class='container'>";
        $theMsg = '<div class="alert alert-danger">Theres No Such ID</div>';
        redirectHome($theMsg);
        echo "</div>";
    }
    // If There's Not Such ID Show Error Message

    }elseif($do == 'Update'){ 

            echo '<h1 class="text-center">Update Item </h1>';
            echo '<div class="container">';

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Get Variables From The Form

            $id         = $_POST['itemid'];
            $name       = $_POST['name'];
            $desc       = $_POST['description'];
            $price      = $_POST['price'];
            $country    = $_POST['country'];
            $status     = $_POST['status'];
            $members    = $_POST['members'];
            $cat        = $_POST['category'];
            $tags        = $_POST['tags'];


            // Validate The Form
            $forErrors = array();
            if (empty($name)){
                $forErrors[] = 'Name Can\'t be <strong>Empty</strong>';
            }
            if (empty($desc)){
                $forErrors[] = 'Description Can\'t be <strong>Empty</strong>';
            }
            if (empty($price)){
                $forErrors[] = 'Price Can\'t be <strong>Empty</strong>';
            }
            if (empty($country)){
                $forErrors[] = 'country Can\'t be <strong>Empty</strong>';
            }
            if ($status == 0){
                $forErrors[] = 'You Must Choose The <strong>Status</strong>';
            }
            if ($cat == 0){
                $forErrors[] = 'You Must Choose The <strong>Category</strong>';
            }
            foreach($forErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>' ;
            }

            // If There's No Error Proceed The Update Operation
            if(empty($forErrors)) {

                $stmt = $con->prepare("UPDATE
                                            items 
                                        SET
                                            Name = ?,
                                            Description = ?,
                                            Price = ?,
                                            Country_Made = ?,
                                            Status = ?,
                                            Cat_ID  = ?,
                                            Member_ID  = ?,
                                            tags = ?
                                        WHERE
                                            Item_ID = ?");

                $stmt->execute(array($name, $desc, $price, $country, $status, $cat, $members, $tags, $id));

                // Echo Success Message

                $theMsg = '<div class="alert alert-success text-center">' . '<i class="fa fa-check" aria-hidden="true" fa-2x>' . "</i> " . $stmt->rowCount() . ' Record Updated' . "</div>";
                redirectHome($theMsg, 'back');
            }

        }else {
            $theMsg = "<div class='alert alert-danger'>Sorry  You Cant Browse This Page Directly</div>";

            redirectHome($theMsg);
        }
    
        echo "</div>";

    }elseif($do == 'Delete'){ // Delete Memeber Page?>
            <h1 class="text-center">Delete Items</h1>
            <div class="container"><?php
        // check if Get Request userid Is Numeric & Get The Integer Value Of It
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? $_GET['itemid'] : 0;

        // Select All Data Depend On This ID

        $check = checkitem("Item_ID","items",$itemid);



        // If There's ID Show The Form

        if($check > 0){
            $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :zid");
            $stmt->bindParam(':zid', $itemid);
            $stmt->execute();
            $theMsg = '<div class="alert alert-success text-center">'. '<i class="fa fa-check" aria-hidden="true" fa-2x>' . "</i> " . $stmt->rowCount() . ' Record Deleted' . "</div>";
            redirectHome("$theMsg", 'back');
        }else {
            $theMsg = "<div class='alert alert-danger'>This ID Is Not Exist</div>";
            redirectHome("$theMsg");
        }
        echo "</div>";
    }elseif($do == 'comment'){

                echo '<h1 class="text-center">Comment Items</h1>';
                echo '<div class="container">';

                $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? $_GET['itemid'] : 0;

                $check = checkitem('item_id','comments', $itemid);

                if($check > 0){
                    $stmt = $con->prepare("SELECT 
                    comments.*,
                    users.Username AS Member
                FROM 
                    comments
                INNER JOIN 
                    users
                ON
                    users.UserID = comments.user_id
                WHERE item_id = ?");
        // Execute The Statment
        $stmt->execute(array($itemid));
        // Assign To Variable
        $rows = $stmt->fetchAll();

        if($rows > 0) {
        ?>
        <div class="table-responsive">
        <table class="main-table text-center table table-bordered">
            <tr>
                <td>Comment</td>
                <td>User Name</td>
                <td>Added Date</td>
                <td>Control</td>
            </tr>
            <?php
                foreach($rows as $row) {
                    echo "<tr>";
                        echo "<td>" . $row['comment'] . "</td>";
                        echo "<td>" . $row['Member'] . "</td>";
                        echo "<td>" . $row['comment_date'] ."</td>";
                        echo "<td> 
                            <a href='comments.php?do=Edit&comid=". $row['c_id'] ."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                            <a href='comments.php?do=Delete&comid=". $row['c_id'] ."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                            if ($row['status'] == 0){
                                echo "<a href='comments.php?do=Approve&comid=". $row['c_id'] ."' class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";
                            }
                        echo "</td>";
                    echo "</tr>";
                }
            ?>
        </table>
        </div>
        <?php }else{
            echo '<div class="container">';
            echo '<div class="alert alert-danger">NO Comment</div>';
            echo '</div>';
        }
}

    }elseif($do == 'Approve') {

    // Activate Memeber Page?>
    <h1 class="text-center">Approve Items</h1>
    <div class="container"><?php
    // check if Get Request userid Is Numeric & Get The Integer Value Of It
    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? $_GET['itemid'] : 0;

    // Select All Data Depend On This ID
    
    $check = checkitem("Item_ID","items",$itemid);



    // If There's ID Show The Form

    if($check > 0){
        $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ?");
        $stmt->execute(array($itemid));
        $theMsg = '<div class="alert alert-success text-center">'. '<i class="fa fa-check" aria-hidden="true" fa-2x>' . "</i> " . $stmt->rowCount() . ' Record Activated' . "</div>";
        redirectHome("$theMsg", 'back');
    }else {
        $theMsg = "<div class='alert alert-danger'>This ID Is Not Exist</div>";
        redirectHome("$theMsg");
    }
    echo "</div>";

    }
    include $tpl . 'footer.php';
}else{
    header('location: index.php');
    exit();
}

ob_end_flush();

?>