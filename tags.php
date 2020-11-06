<?php 
ob_start();
session_start();
$pageTitle = 'Categories Page';
include 'init.php'; 
?>

<div class="container">
    <div class="row">
        <?php
            if(isset($_GET['name'])){
            $tag = $_GET['name'];
            echo '<h1 class="text-center">'. $tag .'</h1>';
            $getitem = $con->prepare("SELECT * FROM items WHERE tags Like '%$tag%' AND Approve = 1 ORDER BY Item_ID DESC");
            $getitem->execute();
            $items = $getitem->fetchAll();
            foreach($items as $item) {
                echo '<div class="col-sm-6 col-md-3">';
                    echo '<div class="thumbnail item-box">';
                        echo '<span class="price-tag">$' . $item["Price"] . '</span>';
                        echo '<img class="img-responsive" src="p2.png" alt="" />';
                        echo '<div class="caption">';
                            echo '<h3><a href="items.php?itemid='.$item['Item_ID'].'">'. $item['Name'] .'</a></h3>';
                            echo '<p>'. $item['Description'] .'</p>';
                            echo '<div class="comment"><a href="items.php?itemid=' . $item['Item_ID'] . '#comment">' . countItems("c_id", "comments", $item['Item_ID']) . ' Comments</a></div>';
                            echo '<dive class="data">'. $item['Add_Date'] .'</dive>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            }
            }else {
                echo '<div class="alert alert-danger">You Did\'nt Specify Page ID</div>';
            }
        ?>
    </div>
</div>


<?php include $tpl . "footer.php";
ob_end_flush();
?>