<?php

/*
** Get All Function v1.0
** Fuunction To Get All Field From Any Database Table
*/

function getAllFrom($tableName, $orderBy, $where = NULL) {

    global $con;

    $sql = $where == NULL ? '' : $where;

    $getAll= $con->prepare("SELECT * FROM $tableName $sql ORDER BY $orderBy DESC");

    $getAll->execute();

    $Alls = $getAll->fetchAll();

    return $Alls;

}

/*
** Get categories Function v1.0
** Fuunction To Get Categories From Database
*/

function getCat() {

    global $con;

    $getcat = $con->prepare("SELECT * FROM categories WHERE Parent = 0 ORDER BY ID ASC");

    $getcat->execute();

    $cats = $getcat->fetchAll();

    return $cats;

}


/*
** Check If User Is Not Activated
** Function To check The RegStatus Of The User
*/

function checkUserStatus($user){

    global $con;

    $stmtx = $con->prepare("SELECT 
                                Username, RegStatus 
                            FROM 
                                users 
                            WHERE 
                                Username = ? 
                            AND 
                                RegStatus = 0 ");
    $stmtx->execute(array($user));

    $status = $stmtx->rowCount();

    return $status;
}

/*
** Count Number Of Items Function v1.0
** function To Count Of Items Rows
** $item The Item To Count
** $table The Table To Choose From
*/

function countItems($item, $table,$value) {

    global $con;

    $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table WHERE item_id = ?");

    $stmt2->execute(array($value));

    return $stmt2->fetchColumn();

}

/*
** Get Items Function v1.0
** Fuunction To Get items From Database
*/

function getitem($where, $value, $approve = NULL) {

    global $con;

    if($approve == NULL){

        $sql = 'AND Approve = 1';

    }else {
        $sql = NULL;
    }

    $getitem = $con->prepare("SELECT * FROM items WHERE $where = ? $sql ORDER BY Item_ID DESC");

    $getitem->execute(array($value));

    $items = $getitem->fetchAll();

    return $items;

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