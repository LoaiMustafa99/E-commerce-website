
<?php
ob_start();
session_start();
$pageTitle = 'Login';
if(isset($_SESSION['user'])) {
    header('Location: index.php'); // Register To Dashbord Page
}

include 'init.php';  

// Check If User Coming From HTTP Post Request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if(isset($_POST['login'])){

        $user = $_POST['username'];
        $pass = $_POST['password'];

        $hashedPass = sha1($pass);

        // Check If The User Exist IN Database

        $stmt = $con->prepare("SELECT 
                                    UserID,Username, Password 
                                FROM 
                                    users 
                                WHERE 
                                    Username = ? 
                                AND 
                                    Password = ? ");
        $stmt->execute(array($user, $hashedPass));

        $get = $stmt->fetch();

        $count = $stmt->rowCount();

        // If Count > 0 This Mean The Database Contain Information About This Username

        if ($count > 0) {
            $_SESSION['user'] = $user; // Register Session Name
            $_SESSION['uid'] = $get['UserID']; //  Register UserID Session
            header('Location: index.php'); // Register To Dashbord Page
            exit();
        }
    }else{

        $forerrors = array();

        $username = $_POST['username'];
        $password = $_POST['password'];
        $password2 = $_POST['password-agin'];
        $email = $_POST['email'];

        if(isset($username)) {

            $filterUser = filter_var($username, FILTER_SANITIZE_STRING);

            if(strlen($filterUser) < 4) {
                $forerrors[] = "Username Must be Larger than 4 Characters ";

            }

        }
        if(isset($password) && isset($password2)) {
            if(empty($password)) {
                $forerrors[] = 'Sorry Password cant Be Empty';
            }
            
            if(sha1($password) !== sha1($password2) ) {
                $forerrors[] = "Sorry Password is not Match ";

            }

        }
        if(isset($username)) {

            $filterEmail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

            if(strlen($filterUser) < 4) {
                $forerrors[] = "Username Must be Larger than 4 Characters ";

                if(filter_var($filterEmail, FILTER_SANITIZE_EMAIL) != true){

                    $forerrors[] = "This Email Is Not Valid";
                }
            }
        }
        if(empty($forerrors)) {

            // Check If User Exists In Database

            $check = checkitem("Username","users",$username);

            if($check == 1){
                
                $forerrors[] = "Sorry This User Is Exist";
                
            }else {
                
            // Insert The Database With This Info

            $stmt = $con->prepare("INSERT INTO 
                                    users (Username, Password, Email, RegStatus, Date)
                                    VALUES(:zuser, :zpass, :zmail, 0, now()) ");
            $stmt->execute(array(

                'zuser' => $username,
                'zpass' => sha1($password),
                'zmail' => $email

            ));
        

            // Echo Success Message

            $succesMsg = 'Congrats You Are Now Register User';
            
            
            }
        }
    }

}

?>

<div class="container login-page">
    <h1 class="text-center"><span class="active" data-class="login">Login | </span> <span data-class="signup">Signup</span></h1>
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <div class="input-container">
            <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Username" required="required" />
        </div>
        <div class="input-container">
            <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Password" required="required"/>
        </div>
            <input class="btn btn-primary btn-block" name="login" type="submit" value="Login" />
    </form>
    <form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <div class="input-container">
            <input pattern=".{4,}" title="Username Must Be 4 Character" class="form-control" type="text" name="username" autocomplete="off" placeholder="Username" required="required" />
        </div>
        <div class="input-container">
            <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Password" required="required"/>
        </div>
        <div class="input-container">
            <input class="form-control" type="password" name="password-agin" autocomplete="new-password" placeholder="confecration Password" required="required"/>
        </div>
        <div class="input-container">
            <input class="form-control" type="email" name="email" autocomplete="off" placeholder="Email" required="required"/>
        </div>
        <input class="btn btn-success btn-block" name="signup" type="submit" value="Signup" />
    </form>
    <div class="the-errors text-center msg">
        <?php
        if(! empty($forerrors)){
            foreach($forerrors as $error) {
                echo "<div class='msg error'>" . $error . '</div>';
            }
        }
        if(isset($succesMsg)){
            echo '<div class="msg succes">' . $succesMsg . '</div>';
        }
            ?>
    </div>
</div>

<?php include $tpl . "footer.php";  
ob_end_flush();
?>