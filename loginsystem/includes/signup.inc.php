<?php

if (isset($_POST['submit'])) {
  #so that people must click the button before prcoeeding
    include_once 'dbh.inc.php';

    $first = mysqli_real_escape_string($conn, $_POST['first']);
    $last = mysqli_real_escape_string($conn, $_POST['last']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $uid = mysqli_real_escape_string($conn, $_POST['uid']);
    $pwd = mysqli_real_escape_string($conn, $_POST['pwd']);

    //Error handlers
    //Check for empty fields, check errors first before checking for success
    if (empty($first) || empty($last) || empty($email) || empty($uid) || empty($pwd)) {
    header("Location: ../signup.php?signup=empty");
    exit();
   } else {
//chck if input characters are valid
    if (!preg_match("/^[a-zA-Z]*$/", $first) || !preg_match("/^[a-zA-Z]*$/", $last)) {
    header("Location: ../signup.php?signup=invalid");
    exit();
   } else {
     //check if email is a valid one
     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../signup.php?signup=invalidtaken");
        exit();
//check if there is any duplicate user id
     } else {
        $sql = "SELECT * FROM users WHERE user_uid='$uid'";
        $result = mysqli_query($conn, $sql);
        $resultCheck  = mysqli_num_rows($result);

        if ($resultCheck > 0) {
            header("Location: ../signup.php?signup=usertaken");
            exit();
        } else {
            //hashing the Password
            $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
            //this is to insert the user inside the database
            $sql = "INSERT INTO users (user_first, user_last, user_email, user_uid, user_pwd) VALUES ('$first', '$last',  '$email', '$uid', '$hashedPwd');";
            $result = mysqli_query($conn, $sql);
            header("Location: ../signup.php?signup=Success");
            exit();
       }
     }
    }
  }

} else {
    header("Location: ../signup.php");
    exit();
}
