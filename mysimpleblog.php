<?php
/*
 * Create Database Tables (if needed) and a constant PDO connection:
 */
require_once "lib/includes/config.php";

/*
 * Registration function of user:
 */

function registration($pdo = NULL) {

    $name = filter_input(INPUT_POST, 'name');
    $email = filter_input(INPUT_POST, 'email');
    $password = password_hash(filter_input(INPUT_POST, 'password'), PASSWORD_DEFAULT);
    $query = 'INSERT INTO mysimpleregistration(name, email, password, dateCreated) VALUES ( :name, :email, :password, NOW())';
    $stmt = $pdo->prepare($query);
    $result = $stmt->execute([':name' => $name, ':email' => $email, ':password' => $password]);
    if ($result) {
        header("Location: mysimpleblog.php");
        exit();
    } else {
        echo 'Error, Something went wrong!';
    }
}

$enter = filter_input(INPUT_POST, 'enter');
if (isset($enter) && $enter === 'register') {
    registration($pdo);
}

/*
 * Login function of user:
 */

function login($pdo = NULL) {

    $email = filter_input(INPUT_POST, 'email');
    $password = filter_input(INPUT_POST, 'password');

    $query = 'SELECT * FROM mysimpleregistration WHERE email=:email';

    $stmt = $pdo->prepare($query); // Prepare the query:
    $stmt->execute([':email' => $email]); // Execute the query with the supplied user's parameter(s):

    $stmt->setFetchMode(PDO::FETCH_OBJ);
    $user = $stmt->fetch();

    /*
     * If password matches database table match send back true otherwise send back false.
     */
    if (password_verify($password, $user->password)) {

        $userArray['id'] = $user->id;
        $userArray['name'] = $user->name;
        $userArray['email'] = $user->email;
        //$userArray['csrf_token'] = $_SESSION['csrf_token'];

        $_SESSION['user'] = (object) $userArray;
        header("Location: mysimpleblog.php");
        exit();
    } else {
        echo "Login Failed!";
    }
}

/*
 * Logout user:
 */
if (isset($enter) && $enter === 'login') {
    login($pdo);
}


if (isset($enter) && $enter === 'logout') {
    unset($_SESSION['user']);
    $_SESSION['user'] = NULL;
    session_destroy();
    header("Location: mysimpleblog.php");
    exit();
}


/*
 * Write to blog:
 */
$submit = filter_input(INPUT_POST, 'submit');

if (isset($submit) && $submit === "Submit") {
    /* Create a query using prepared statements */
    $query = 'INSERT INTO mysimpleblog( userid, name, title, message, dateCreated) VALUES ( :userid, :name, :title, :message, NOW())';
    /* Prepared the Statement */
    $stmt = $pdo->prepare($query);
    /* Excute the statement with the prepared values */
    $result = $stmt->execute([':userid' => $user->id, ':name' => $user->name, ':title' => filter_input(INPUT_POST, 'title'), ':message' => filter_input(INPUT_POST, 'message')]);
    /* Check to see it was successfully entered into the database table. */
    if ($result) {
        header("Location: mysimpleblog.php");
        exit();
    } else {
        echo 'Error, Something went wrong';
    }
}


/*
 * Display Blog PDO and Setup the query 
 */
$query = 'SELECT id, userid, name, title, message, dateCreated FROM mysimpleblog ORDER BY id DESC';
/*
 * Prepare the query 
 */
$stmt = $pdo->prepare($query);
/*
 * Execute the query 
 */
$result = $stmt->execute();
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>My Simple Blog</title>
        <link rel="stylesheet" href="lib/css/reset.css">
        <link rel="stylesheet" href="lib/css/grids.css">
        <link rel="stylesheet" href="lib/css/mysimpleblog.css">
    </head>
    <body>
        <?php if (!$user) { ?>
            <div class="container topBar">
                <h5>Welcome to My Simple Blog!</h5>
                <button id="box1" class="buttonStyle" onclick="toggleClass('maindiv1', 'hideBoxes', 'box1');">Open Login / Register</button>
            </div>
            <div id='maindiv1' class="container">
                <form id="login" class="span6" name="mysimpleblog.php" method="post" autocomplete="off">
                    <fieldset>
                        <legend>Login</legend>
                        <label for="loginEmail">email address</label>
                        <input id="loginEmail" type="text" name="email" value="" tabindex="1" autofocus>
                        <label for="loginPassword">password</label>
                        <input id="loginPassword" type="password" name="password" tabindex="2">
                        <input type="submit" name="enter" value="login" tabindex="3">               
                    </fieldset>
                </form>
                <form id="register" class="span6" name="mysimpleblog.php" method="post" autocomplete="off">
                    <fieldset>
                        <legend>Register</legend>
                        <label for="name">name</label>
                        <input id="name" type="text" name="name" value="" tabindex="4">
                        <label for="email">email address</label>
                        <input id="email" type="email" name="email" value="" tabindex="5">
                        <label for="password">password</label>
                        <input id="password" type="password" name="password" tabindex="6">
                        <input type="submit" name="enter" value="register" tabindex="7">
                    </fieldset>
                </form>
            </div>
        <?php } else { ?>
            <div class="container topBar">
                <h5>Welcome <?= $user->name ?>!</h5>
                <button id="box2" class="buttonStyle" onclick="toggleClass('maindiv2', 'hideBoxes', 'box2', 'Open Enter Blog / Logout', 'Close Enter Blog / Logout');">Open Enter Blog / Logout</button>
            </div>
            <div id="maindiv2" class="container">
                <form id="mySimpleBlogForm" class="span6" action="mysimpleblog.php" method="post" autocomplete="off">
                    <fieldset>
                        <legend>Enter Blog</legend>
                        <label for="title">title</label>
                        <input id="title" type="text" name="title" tabindex="1">
                        <label id="labelTextarea"  for="message">Message</label>
                        <textarea id="message" name="message" tabindex="2"></textarea>
                        <input type="submit" name="submit" value="Submit" tabindex="3">
                    </fieldset>
                </form>
                <form id="logout" class="span6" action="mysimpleblog.php" method="post" autocomplete="off">
                    <fieldset>
                        <legend>Logout</legend>
                        <label for="viewEmail">email address</label>
                        <input id="viewEmail" type="text" name="email" value="<?= $user->email; ?>" readonly>
                        <input type="submit" name="enter" value="logout" tabindex="4">
                    </fieldset>
                </form>
            </div>
        <?php } ?>
        <?php
        /*
         * Display the output
         */
        echo "\n";
        while ($record = $stmt->fetch(PDO::FETCH_OBJ)) {
            echo "\t" . '<div class = "container mySimpleBlog span5">' . "\t\n";
            $myDate = new DateTime($record->dateCreated);
            echo "\t\t<h2>" . $record->title . '<span>Created by ' . $record->name . ' on  ' . $myDate->format("F j, Y") . "</span></h2>\n";
            echo "\t\t<p>" . strip_tags(nl2br($record->message), '<br>') . "</p>\n";
            echo "\t</div>\n";
        }
        ?>
        <script src="lib/js/toggleButton.js"></script>
    </body>
</html>