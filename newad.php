<?php
    ob_start();
    session_start();
    $pageTitle = 'Create New Ad';
    include 'init.php';
    if(isset($_SESSION['user'])){

        
        

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            $formErrors = array();

            $name      = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $desc       = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
            $price      = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
            $country    = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
            $status     = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
            $category   = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
            $tags       = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);

            if(strlen($name) < 4){

                $formErrors[] = "Item name Must Be At Least 4 Character";

            }
            if(strlen($desc) < 10){

                $formErrors[] = "Item Description Must Be At Least 4 Character";

            }
            if(strlen($country) < 2){

                $formErrors[] = "Item country Must Be At Least 4 Character";

            }
            if(empty($price)){

                $formErrors[] = "Item Price Must Be Not Empty";

            }
            if(empty($status)){

                $formErrors[] = "Item status Must Be Not Empty";

            }
            if(empty($category)){

                $formErrors[] = "Item category Must Be Not Empty";

            }

            
            if(empty($formErrors)) {

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
                    'zmember'   => $_SESSION['uid'],
                    'ztags'     => $tags

                ));
            

                // Echo Success Message
                if($stmt) {

                    $succesMsg = 'item Added, Pleas Wite To Approvel';

                }
        }

        }

?>
<h1 class="text-center">Create New Item</h1>
<div class="create-ad block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Create New Item</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">
                    <form class="form-horizontal main-form" action="?do=Insert" method="POST">
                    <!-- Start Name Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-9">
                            <input 
                            pattern = ".{4,}"
                            title = "This Field Require At Least 4 Characters"
                            type="text" 
                            name="name" 
                            class="form-control live-name" 
                            required="required" 
                            placeholder="Name Of The Item"> 
                        </div>
                    </div>
                    <!-- End Name Field -->
                    <!-- Start Description Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-9">
                            <input
                            pattern = ".{10,}"
                            title = "This Field Require At Least 10 Characters"
                            type="text" 
                            name="description" 
                            class="form-control live-desc" 
                            required="required" 
                            placeholder="DEscription Of The Item"> 
                        </div>
                    </div>
                    <!-- End Description Field -->
                    <!-- Start Price Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10 col-md-9">
                            <input 
                            type="text" 
                            name="price" 
                            class="form-control live-price" 
                            required="required" 
                            placeholder="Price Of The Item"> 
                        </div>
                    </div>
                    <!-- End Price Field -->
                    <!-- Start Country_made Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Country</label>
                        <div class="col-sm-10 col-md-9">
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
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-9">
                            <select name="status" required>
                                <option value="">...</option>
                                <option value="1">New</option>
                                <option value="2">Link New</option>
                                <option value="3">Used</option>
                                <option value="4">Very Old</option>
                            </select>
                        </div>
                    </div>
                    <!-- End Status Field -->
                    <!-- Start Category Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-10 col-md-9">
                            <select name="category" required>
                                <option value="">...</option>
                                <?php
                                    $cats = getAllFrom('categories', 'ID');
                                    foreach($cats as $cat){
                                        echo "<option value= '" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Category Field -->
                    <!-- Start Tags Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-3 control-label">Tags</label>
                        <div class="col-sm-10 col-md-9">
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
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add Item" class="btn btn-primary btn-sm">
                        </div>
                    </div>
                <!-- End Submit Field -->
                </form>
                    </div>
                    <div class="col-md-4">
                        <div class="thumbnail item-box live-preview">
                            <span class="price-tag">$0</span>
                                <img class="img-responsive" src="p2.png" alt="" />
                                <div class="caption">
                                <h3> Title</h3>
                                <p>Description</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Starting Looping Through Errors -->
                <?php
                    if(! empty($formErrors)){
                        foreach($formErrors as $error) {
                            echo '<div class="alert alert-danger">' . $error . '</div>';
                        }
                    }
                    if(isset($succesMsg)){
                        echo '<div class="alert alert-success">' . $succesMsg . '</div>';
                    }
                ?>
                <!-- Starting Looping Through Errors -->
            </div>
        </div>
    </div>
</div>
<?php
    }else{

        header('Location: login.php');

        exit();

    }

include $tpl . "footer.php";

ob_end_flush();

?>