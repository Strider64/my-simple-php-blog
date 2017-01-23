<?php

/*
 * Registration function of user:
 */

function registration(array $data, $pdo = NULL) {

    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $query = 'INSERT INTO mysimpleregistration(name, email, password, confirmation, dateCreated) VALUES ( :name, :email, :password, :confirmation, NOW())';
    $stmt = $pdo->prepare($query);
    $result = $stmt->execute([':name' => $data['name'], ':email' => $data['email'], ':password' => $password, ':confirmation' => $data['confirmation']]);
    if ($result) {
        header("Location: mysimpleblog.php");
        exit();
    } else {
        echo 'Error, Something went wrong!';
    }
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

function createTables() {
    try {
        $conn = new PDO("mysql:host=localhost:8889;dbname=mysimpleblog", DATABASE_USERNAME, DATABASE_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $table1 = "CREATE TABLE IF NOT EXISTS mysimpleblog (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                userid INT(11) NOT NULL,
                name VARCHAR(60) NOT NULL,
                title VARCHAR(60) NOT NULL,
                message TEXT NOT NULL,
                dateCreated DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00')";
        $conn->exec($table1);
        $use = 'use mysimpleblog';
        $conn->exec($use);
        $table2 = "CREATE TABLE IF NOT EXISTS mysimpleregistration (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(60) NOT NULL,
                email VARCHAR(120) NOT NULL,
                password VARCHAR(255) NOT NULL,
                confirmation VARCHAR(255) NOT NULL,
                security VARCHAR(11) NOT NULL DEFAULT 'public',
                dateCreated DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00')";
        $conn->exec($table2);
        $conn = NULL;
    } catch (PDOException $e) {
        echo "Something went wrong" . $e->getMessage();
    }
}

function generateRandom() {
    $bytes = random_bytes(10); // length in bytes
    return bin2hex($bytes);
}
