<?php

    /*
    ** Get All Function 
    ** Function To Get All Records From Any Database Table
    */

    function getAllFrom($field, $table, $where = NULL, $and = NULL, $orderfield, $ordering = 'DESC') {

        Global $con;

        $getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $ORDERFIELD $ordering");

        $getAll->execute();

        $all = $getAll->fetchAll();

        return $all;

    } 


    /*
    ** Title Function That Echo The Page Title In Case The Page
    ** Has The Variable $PageTitle And Echo Title For Outher Pages
    */

    function getTitle() {

        global $pageTitle;

        if(isset($pageTitle)) {

            echo $pageTitle;

        }else {
            echo 'Defult';
        }

    }

/*
** Home Redirect Function v2.0
** This Function Accept Parameters
** $TheMsg= Echo The Message [Error | success | Warning]
** $url = The Link You Want To Redirect To
** $secounds = Seconds Before Redirecting 
*/

function redirectHome($theMsg, $url = null ,$seconds = 3) {

    if ($url === null) {

        $url = 'index.php';

        $link = 'Homepage';
    }else{

        if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== ''){
            $url = $_SERVER['HTTP_REFERER'];
            $link = 'Previouce Page';
        }else {
            $url = 'index.php';

        }

    }

    echo $theMsg;

    echo "<div class='alert alert-info'>You Will Be Redirected To $link After $seconds secods</div>";

    header("refresh:$seconds;url=$url");

    exit();
}

/*
** Check Item Function v1.0
** Function to Check Item In Database
** This Function Accept Parameters
** $select = The Item To Select [Example: user, item, category]
** $from = The Table To Select From [example: users, items, categories]
** $value = The Value Of Select [ example: Loai, box, electronics ]
*/

function checkitem($select, $from, $value) {

    global $con;

    $statment = $con->prepare("SELECT $select FROM $from WHERE $select = ?");

    $statment->execute(array($value));

    $count = $statment->rowCount();

    return $count;

}

/*
** Count Number Of Items Function v1.0
** function To Count Of Items Rows
** $item The Item To Count
** $table The Table To Choose From
*/

function countItems($item, $table) {

    global $con;

    $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");

    $stmt2->execute();

    return $stmt2->fetchColumn();

}

/*
** Get Lastest Records Function v1.0
** Fuunction To Get Lastest Items From Database [ Users, Items, Comments]
** $select = Field To Select
** $table = The Table To Chosse From
** $order = The Desc Ordering
** $limit = Number Of Records To Get
*/

function getLastest($select, $table, $order, $limit = 5) {

    global $con;

    $getStmt = $con->prepare("SELECT $select FROM $table  ORDER BY $order DESC LIMIT $limit");

    $getStmt->execute();

    $row = $getStmt->fetchAll();

    return $row;

}