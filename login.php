<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">

    <title>PHP File Management</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Sonsie+One" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="styles/style.css">

  </head>

  <body>

    <header>
      <h1>PHP File Management login</h1>

    </header>
    <main>
    <div class="loginDiv">
    
    <?php

    loginButtons();
    ?>

    </div>
    </main>


    <footer>
      <p>©Copyright 2050 by nobody. All rights reversed.</p>
    </footer>

  </body>
</html>

<?php

function loginButtons(){

    if( isset($_POST['userNameLogin']) ){
        login();
        return true;
    }else if( isset($_POST['userNameSignIn']) ){
        signIn();
        return true;
    }else if(isset($_POST['signInForm'])   ){ 
        showSignInForm();
        return true;
    }else if( isset($_POST['loginForm']) ){ 
        showLoginForm();
        return true;
    }else{ 
        showButtons();
    }

}

function login(){
    // si ya esta en session solo redirigirlo
    if(isset( $_POST['userNameLogin']) ){
        $userName = $_POST['userNameLogin'];
        $userPassword = $_POST['userPasswordLogin'];

        $userExist = checkUserFile($userName);

        if($userExist){
            createSession($userName);
            #redirectHome();
    }else{
        showFailedLoginForm();   
    }
    }else{
        showFailedLoginForm();   
    }
}

function signIn(){
    if(isset( $_POST['userNameSignIn']) ){

        $userName = $_POST['userNameSignIn'];
        $userPassword = $_POST['userPasswordSignIn'];

        $userExist = checkUserFile($userName); 

        if($userExist){
            showLogInMessage($userName); // change the logic of the msg
            createSession($userName); 
            #redirectHome();
        }else{
       createUser($userName, $userPassword); 
       createSession($userName); 
      # redirectHome();
        }
    }else{
        showFailedSignInForm(); 
        showSignInMessage($userName); // change the logic of the msg
    }
}

function checkUserFile($userName){ 

    $usersFile = fopen('./users/usersFile.txt','w') or die("Unable to open file!");
    fclose($usersFile);
    
    $usersFile = fopen('./users/usersFile.txt','r') or die("Unable to open file!");
    while(!feof($usersFile)){
        $entry_array = fgets($usersFile); 
        if(strpos($entry_array, $userName) !== false ){
            fclose($usersFile);
            return true;
        }
        
    }

    fclose($usersFile);
    return false;        
    }
    

function createSession($userName){ 
    session_start();
    $_SESSION["userName"] = $userName;
    echo  $_SESSION["userName"];
}

function  createUser($userName, $userPassword){ 
    echo ($userName . $userPassword);
    $data = str_pad( ($userName . ',' . $userPassword),40," ");
    $wasUploadedSuccessfully = file_put_contents("./users/usersFile.txt" ,$data, FILE_APPEND | LOCK_EX);
    if ($wasUploadedSuccessfully === false){
        echo "There was an error writing in index.txt file";
    }
    else{
       
    }
}


function showLogInMessage($userName){ //check if works
    echo "User already exists </br>";
    echo "Loggin in <b>" . $userName . "</b></br>";
    echo "Redirecting...";

}

function showSignInMessage($userName){ //check if works
    echo "Signing in " . $userName . "</br>";
    echo "Redirecting...";

}

function redirectHome(){
    $location = "./home.php";
    redirect( $location);
}

function redirect($url, $statusCode = 303)
{
   header('Location: ' . $url, true, $statusCode);
   die();
}

function showButtons(){
    echo '
    <table name="loginTableButtons" class="loginTableButtons">
    <tr>
    <td>  
    <form action="login.php" method="post"> 
    <input type="hidden" value="true" name="loginForm" id="loginForm"> 
    <input class="loginButton" type="submit" value="Login"> 
    </form>
    </td>

    <td>  
    <form action="login.php" method="post"> 
    <input type="hidden" value="true" name="signInForm" id="signInForm"> 
    <input class="loginButton" type="submit" value="Sign In"> 
    </form>
    <td>
    </tr>
    </table>';
}

function showFailedLoginForm(){
    echo '<form action="login.php" method="post"> 
    <table name="loginTable" class="loginTable">
    <tr>
    <td> <label for="userNameLogin"> User </label> <td>
    <td> <input name="userNameLogin" id="userNameLogin" > </td>
    </tr>

    <tr>
    <td> <label for="userPasswordLogin"> Password </label> <td>
    <td> <input type="password" name="userPasswordLogin" id="userPasswordLogin" > </td>
    </tr>
    <tr><td class="failedLogin"> Failed to login </td></tr>
    <tr>
    <td>  <input type="submit" value="Login"> <td>
    </tr>
    </table>
    </form> ';
}

function showFailedSignInForm(){
    echo '<form action="login.php" method="post"> 
    <table name="loginTable" class="loginTable">
    <tr>
    <td> <label for="userNameLogin"> User </label> <td>
    <td> <input name="userNameLogin" id="userNameLogin" > </td>
    </tr>

    <tr>
    <td> <label for="userPasswordLogin"> Password </label> <td>
    <td> <input type="password" name="userPasswordLogin" id="userPasswordLogin" > </td>
    </tr>
    <tr><td class="failedLogin"> Failed to Sign In </td></tr>
    <tr>
    <td>  <input type="submit" value="Login"> <td>
    </tr>
    </table>
    </form> ';
}

function showLoginForm(){

    echo '<form action="login.php" method="post"> 
    <table name="loginTable" class="loginTable">
    <tr>
    <td> <label for="userNameLogin"> User </label> <td>
    <td> <input name="userNameLogin" id="userNameLogin" > </td>
    </tr>

    <tr>
    <td> <label for="userPasswordLogin"> Password </label> <td>
    <td> <input type="password" name="userPasswordLogin" id="userPasswordLogin" > </td>
    </tr>

    <tr>
    <td>  <input type="submit" value="Login"> <td>
    </tr>
    </table>
    </form> ';
}

function showSignInForm(){
    
        echo '<form action="login.php" method="post"> 
        <table name="loginTable" class="loginTable">
        <tr>
        <td> <label for="userNameSignIn"> User </label> <td>
        <td> <input name="userNameSignIn" id="userNameSignIn" > </td>
        </tr>
    
        <tr>
        <td> <label for="userPasswordSignIn"> Password </label> <td>
        <td> <input type="password" name="userPasswordSignIn" id="userPasswordSignIn" > </td>
        </tr>
    
        <tr>
        <td>  <input type="submit" value="Create User"> <td>
        </tr>
        </table>
        </form> ';
    }

?>