<?php

    /*
    ================================================
    == Category Page
    ================================================
    */

    ob_start();

session_start();

$pageTitle = 'Categories';

if(isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // Start Manage Page

    if($do == 'Manage') { 

        $sort = 'ASC';

        $sort_array = array('ASC', 'DESC');

        if(isset($_GET['sort']) && in_array($_GET['sort'] ,$sort_array)){

            $sort = $_GET['sort'];

        }

        $stmt2 = $con->prepare("SELECT * FROM categories WHERE Parent = 0 ORDER BY Ordering $sort");

        $stmt2->execute();

        $cats = $stmt2->fetchAll();
        if(! empty($cats)){
        ?>

            <h1 class="text-center">Manage Categories</h1>
            <div class="container categories">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-edit"></i> Manage Categories
                        <div class="option pull-right">
                            <i class="fa fa-sort"></i> Ordereing:[
                            <a class="<?php if($sort == 'ASC'){echo 'active';} ?>" href="?sort=ASC">ASC</a> |
                            <a class="<?php if($sort == 'DESC'){echo 'active';} ?>" href="?sort=DESC">DESC</a>]
                            <i class="fa fa-eye"></i> View:[
                            <span class="active" data-view="full">Full</span> |
                            <span data-view="classic">Classic</span>]
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php
                        foreach($cats as $cat){
                            echo "<div class='cat'>";
                            echo '<div class="hidden-buttons">';
                                echo '<a href="categories.php?do=Edit&catid='.$cat['ID'].'" class="btn btn-primary"><i class="fa fa-edit"></i>Edit</a>';
                                echo '<a href="categories.php?do=Delete&catid='.$cat['ID'].'" class="confirm btn btn-danger"><i class="fa fa-close"></i>Delete</a>';
                            echo '</div>';
                            echo "<h3>" . $cat['Name'] . '</h3>';
                            echo '<div class="full-view">';
                            echo "<p>";  if($cat['Description'] == ''){ echo 'This category Has no Description'; }else{ echo $cat['Description']; } echo "</p>";
                            if($cat['Visibility']  == 1){echo "<span class='visibility'><i class='fa fa-eye'></i> Hidden</span>";}
                            if($cat['Allow_Comment']  == 1){echo "<span class='Commenting'><i class='fa fa-close'></i> Comment Disable</span>";}
                            if($cat['Allow_Ads']  == 1){echo "<span class='Advertises'><i class='fa fa-close'></i> Ads Disable</span>";}
                            // Get Child Category
                            $getAll = $con->prepare("SELECT * FROM categories WHERE Parent = {$cat['ID']} ORDER BY ID ASC");
                            $getAll->execute();
                            $allCats = $getAll->fetchAll();
                            if(! empty($allCats)){
                            echo '<h4 class="child-head">Child Categories</h4>';
                            echo '<ul class="list-unstyled child-cats">';
                            foreach($allCats as $c){
                                echo '<li class="child-link">
                                <a href="categories.php?do=Edit&catid='.$c['ID'].'">' . $c['Name'] . '</a>
                                <a href="categories.php?do=Delete&catid='.$cat['ID'].'" class="show-delete confirm">Delete</a>
                                </li>';
                            }
                            echo '</ul>';
                        }
                            echo "</div>";
                            echo "</div>";
                        echo "<hr>";
                    }
                        ?>
                    </div>
                </div>
                <a href="categories.php?do=Add" class="add-category btn btn-primary"><i class="fa fa-plus"></i>Add Categories</a>
            </div>
            <?php
                    }else{
                        echo '<div class="container">';
                        echo '<div class="nice-message">Ther\'s No Record To Show</div>';
                        echo '<a href="categories.php?do=Add" class="add-category btn btn-primary"><i class="fa fa-plus"></i>Add Categories</a>';
                        echo '</div>';
                    }
        

    } elseif($do == 'Add') {?>

        <h1 class="text-center">Add New Categories </h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">
                <!-- Start Name Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-3 control-label">Name</label>
                    <div class="col-sm-8">
                        <input type="text" name="name" class="form-control"  autocomplete="off" required="required" placeholder="Name Of The Category"> 
                    </div>
                </div>
                <!-- End Name Field -->
                <!-- End Description Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-3 control-label">Description</label>
                    <div class="col-sm-8">
                        <input type="Text" name="description" class="form-control" placeholder="Describe The Category">
                    </div>
                </div>
                <!-- End Description Field -->
                <!-- Start  Ordering Field -->
                    <div class="form-group form-group-lg">
                    <label class="col-sm-3 control-label">Ordering</label>
                    <div class="col-sm-8">
                        <input type="Text" name="Ordering" class="form-control" placeholder="Number To Arrange The Categories">
                    </div>
                </div>
                <!-- End Ordering Field -->
                <!-- Start Category Type -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-3 control-label">Parent?</label>
                    <div class="col-sm-8">
                        <select name="parent">
                            <option value="0">None</option>
                            <?php
                            $getAll = $con->prepare("SELECT * FROM categories WHERE Parent = 0  ORDER BY ID ASC");
                            $getAll->execute();
                            $allCats = $getAll->fetchAll();
                            foreach($allCats as $cat){
                                echo "<option value='" . $cat['ID'] . "'>". $cat['Name'] ."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- End Category Type -->
                <!-- Start Visiblity Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-3 control-label">Visible</label>
                    <div class="col-sm-8">
                        <div>
                            <input id="vis-yes" type="radio" name="Visiblity" value="0" checked />
                            <label for="vis-yes">Yes</label>
                        </div>
                        <div>
                            <input id="vis-no" type="radio" name="Visiblity" value="1" />
                            <label for="vis-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- End Visiblity Field -->
                <!-- Start Commenting Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-3 control-label">Allow Commenting</label>
                    <div class="col-sm-8">
                        <div>
                            <input id="com-yes" type="radio" name="commenting" value="0" checked />
                            <label for="com-yes">Yes</label>
                        </div>
                        <div>
                            <input id="com-no" type="radio" name="commenting" value="1" />
                            <label for="com-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- End Commenting Field -->
                <!-- Start Ads Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-3 control-label">Allow Ads</label>
                    <div class="col-sm-8">
                        <div>
                            <input id="ads-yes" type="radio" name="ads" value="0" checked />
                            <label for="ads-yes">Yes</label>
                        </div>
                        <div>
                            <input id="ads-no" type="radio" name="ads" value="1" />
                            <label for="ads-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- End Ads Field -->
                <!-- Start Submit Field -->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-3 col-sm-10">
                        <input type="submit" value="Add Category" class="btn btn-primary btn-lg">
                    </div>
                </div>
                <!-- End Submit Field -->

        <?php
    } elseif($do == 'Insert') { 

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

        echo '<h1 class="text-center">Add Categories</h1>';
        echo '<d class="text-center">';

        // Get Variables From The Form

        $name       = $_POST['name'];
        $desc       = $_POST['description'];
        $parent       = $_POST['parent'];
        $order      = $_POST['Ordering'];
        $visible    = $_POST['Visiblity'];
        $comment    = $_POST['commenting'];
        $ads        = $_POST['ads'];

            // Check If Categories Exists In Database

            $check = checkitem("Name","categories", $name);

            if($check == 1){
                $theMsg = "<div class='alert alert-danger text-center'>Sorry This Category Is Exist</div>";
                redirectHome($theMsg, 'back');

            } else {

                // Insert The Database With This Info

                $stmt = $con->prepare("INSERT INTO 
                                        categories (Name, Description, Parent, Ordering, Visibility, Allow_Comment, Allow_Ads)
                                        VALUES(:zname, :zdesc, :zparent, :zorder, :zvisible, :zcomment, :zads) ");
                $stmt->execute(array(

                    'zname'     => $name,
                    'zdesc'     => $desc,
                    'zparent'   => $parent,
                    'zorder'    => $order,
                    'zvisible'  => $visible,
                    'zcomment'  => $comment,
                    'zads'      => $ads
                    
            ));
        

            // Echo Success Message

            $theMsg = '<div class="alert alert-success text-center">'. '<i class="fa fa-check" aria-hidden="true" fa-2x>' . "</i> " . $stmt->rowCount() . ' Record Inserted' . "</div>";

            redirectHome($theMsg, 'back');
        
        }
    

    }else {
        $theMsg = "<div class='alert alert-danger'>Sorry  You Cant Browse This Page Directly</div>";
        
        redirectHome($theMsg, 'back');
    }
    echo "</>";
    }elseif($do == 'Edit'){
        // check if Get Request catid Is Numeric & Get The Integer Value Of It
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? $_GET['catid'] : 0;
        

        // Select All Data Depend On This ID
        $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ? LIMIT 1");
        
        // Execute Query
        $stmt->execute(array($catid));
        
        // Fetch The Data
        $cat = $stmt->fetch();
        
        // The Row Count
        $count = $stmt->rowCount();
        
        // If There's ID Show The Form
        if($stmt->rowCount() > 0){ ?>

            <h1 class="text-center">Edit Categories </h1>
            <div class="container">
            <form class="form-horizontal" action="?do=Update" method="POST">
            <input type="hidden" name="catid" value="<?php echo $catid ?>" />
            <!-- Start Name Field -->
            <div class="form-group form-group-lg">
                <label class="col-sm-3 control-label">Name</label>
                <div class="col-sm-8">
                    <input type="text" name="name" class="form-control" required="required" placeholder="Name Of The Category" value="<?php echo $cat['Name']; ?>" /> 
                </div>
            </div>
            <!-- End Name Field -->
            <!-- End Description Field -->
            <div class="form-group form-group-lg">
                <label class="col-sm-3 control-label">Description</label>
                <div class="col-sm-8">
                    <input type="Text" name="description" class="form-control" placeholder="Describe The Category" value="<?php echo $cat['Description']; ?>" />
                </div>
            </div>
            <!-- End Description Field -->
            <!-- Start  Ordering Field -->
                <div class="form-group form-group-lg">
                <label class="col-sm-3 control-label">Ordering</label>
                <div class="col-sm-8">
                    <input type="Text" name="Ordering" class="form-control" placeholder="Number To Arrange The Categories" value="<?php echo $cat['Ordering']; ?>" />
                </div>
            </div>
            <!-- End Ordering Field -->
            <!-- Start Category Type -->
            <div class="form-group form-group-lg">
                    <label class="col-sm-3 control-label">Parent?</label>
                    <div class="col-sm-8">
                        <select name="parent">
                            <option value="0">None</option>
                            <?php
                            $getAll = $con->prepare("SELECT * FROM categories WHERE Parent = 0  ORDER BY ID ASC");
                            $getAll->execute();
                            $allCats = $getAll->fetchAll();
                            foreach($allCats as $c){
                                echo "<option value='" . $c['ID'] . "'";
                                    if($cat['Parent'] == $c['ID']) { echo 'Selected'; }
                                echo ">" . $c['Name'] ."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- End Category Type -->
            <!-- Start Visiblity Field -->
            <div class="form-group form-group-lg">
                <label class="col-sm-3 control-label">Visible</label>
                <div class="col-sm-8">
                    <div>
                        <input id="vis-yes" type="radio" name="Visiblity" value="0" <?php if ($cat['Visibility'] == 0 ){echo 'checked';} ?> />
                        <label for="vis-yes">Yes</label>
                    </div>
                    <div>
                        <input id="vis-no" type="radio" name="Visiblity" value="1" <?php if($cat['Visibility'] == 1 ){echo 'checked';} ?> />
                        <label for="vis-no">No</label>
                    </div>
                </div>
            </div>
            <!-- End Visiblity Field -->
            <!-- Start Commenting Field -->
            <div class="form-group form-group-lg">
                <label class="col-sm-3 control-label">Allow Commenting</label>
                <div class="col-sm-8">
                    <div>
                        <input id="com-yes" type="radio" name="commenting" value="0" <?php if ($cat['Allow_Comment'] == 0 ){echo 'checked';} ?>/>
                        <label for="com-yes">Yes</label>
                    </div>
                    <div>
                        <input id="com-no" type="radio" name="commenting" value="1" <?php if ($cat['Allow_Comment'] == 1 ){echo 'checked';} ?>/>
                        <label for="com-no">No</label>
                    </div>
                </div>
            </div>
            <!-- End Commenting Field -->
            <!-- Start Ads Field -->
            <div class="form-group form-group-lg">
                <label class="col-sm-3 control-label">Allow Ads</label>
                <div class="col-sm-8">
                    <div>
                        <input id="ads-yes" type="radio" name="ads" value="0" <?php if ($cat['Allow_Ads'] == 0 ){echo 'checked';} ?>/>
                        <label for="ads-yes">Yes</label>
                    </div>
                    <div>
                        <input id="ads-no" type="radio" name="ads" value="1" <?php if ($cat['Allow_Ads'] == 1 ){echo 'checked';} ?>/>
                        <label for="ads-no">No</label>
                    </div>
                </div>
            </div>
            <!-- End Ads Field -->
            <!-- Start Submit Field -->
            <div class="form-group form-group-lg">
                <div class="col-sm-offset-3 col-sm-10">
                    <input type="submit" value="Save" class="btn btn-primary btn-lg">
                </div>
            </div>
            <!-- End Submit Field -->
    <?php 
    }else {
        echo '<div class="container">';
        $theMsg = '<div class="alert alert-danger"> Theres No Such ID</div>';

        redirectHome($theMsg);
        echo '</div>';
    }
    // If There's Not Such ID Show Error Message

    }elseif($do == 'Update'){ ?>
            <h1 class="text-center">Update Categories </h1>
            <div class="container">
        <?php

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Get Variables From The Form

            $id         = $_POST['catid'];
            $name       = $_POST['name'];
            $desc       = $_POST['description'];
            $order       = $_POST['Ordering'];
            $parent       = $_POST['parent'];
            $visible    = $_POST['Visiblity'];
            $comment     = $_POST['commenting'];
            $ads        = $_POST['ads'];

            

            $stmt = $con->prepare("UPDATE categories SET Name = ?, Description = ?, Ordering = ?, Parent = ?, Visibility = ?, Allow_Comment = ?, Allow_Ads = ? WHERE ID = ?");

            $stmt->execute(array($name , $desc, $order, $parent, $visible, $comment, $ads, $id));


            $theMsg = '<div class="alert alert-success text-center">'. '<i class="fa fa-check" aria-hidden="true" fa-2x>' . "</i> " . $stmt->rowCount() . ' Record Updated' . "</div>";
            redirectHome($theMsg,'back');

        }else {
            $theMsg = "<div class='alert alert-danger'>Sorry  You Cant Browse This Page Directly</div>";

            redirectHome($theMsg);
        }
        echo '</div>';

    }elseif($do == 'Delete'){

        // Delete Memeber Page
        echo '<h1 class="text-center">Delete category</h1>';
        echo '<div class="container">';
        // check if Get Request catid Is Numeric & Get The Integer Value Of It
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? $_GET['catid'] : 0;

        // Select All Data Depend On This ID

        $check = checkitem("ID", "categories", $catid);



        // If There's ID Show The Form

        if($check > 0){
            $stmt = $con->prepare("DELETE FROM categories WHERE ID = :zid");
            $stmt->bindParam(':zid', $catid);
            $stmt->execute();
            $theMsg = '<div class="alert alert-success text-center">'. '<i class="fa fa-check" aria-hidden="true" fa-2x>' . "</i> " . $stmt->rowCount() . ' Record Deleted' . "</div>";
            redirectHome("$theMsg", "back");
        }else {
            $theMsg = "<div class='alert alert-danger'>This ID Is Not Exist</div>";
            redirectHome("$theMsg", 'back');
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