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
        echo "1";
        login();
    }else if( isset($_POST['userNameSignIn']) ){
        echo "2";
        signIn();
    }else if(isset($_POST['signInForm'])   ){ 
        echo "3";
        showSignInForm();
        
    }else if( isset($_POST['loginForm']) ){ 
        echo "4";
        showLoginForm();
       
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
            redirectHome();
    }else{
        showFailedLoginForm();   
    }
    }else{
        showFailedLoginForm();   
    }
}

function signIn(){
    echo "crear";
    if(isset( $_POST['userNameSignIn']) ){

        $userName = $_POST['userNameSignIn'];
        $userPassword = $_POST['userPasswordSignIn'];

        $userExist = checkUserFile($userName); 

        if($userExist){
            showLogInMessage(); //check if works
            createSession($userName);  
            redirectHome();
    }else{
       
       createUser($userName, $userPassword); 
       createSession($userName); 
       redirectHome();
    }
    }else{
        showFailedSignInForm(); 
        showSignInMessage(); //check if works
    }
}

function checkUserFile($userName){ //check if works

    $usersFile = fopen('./users/usersFile.txt','wr');
    while(!feof($usersFile)){
        $entry_array = fgets($usersFile); //
        if( preg_match('/\b' . $userName .  '\b+/', $entry_array)){
            fclose($usersFile);
            return true;
        }
    }

    fclose($usersFile);
    return false;        
    }
    

function createSession($userName){ //check if works
    session_start();
    $_SESSION["userName"] = $userName;
    
}

function  createUser($userName, $userPassword){ //check if works
    echo ($userName . $userPassword);
    $data = str_pad(($userName . $userPassword),40," ");
    $wasUploadedSuccessfully = file_put_contents("users/usersFile.txt",$data,FILE_APPEND | LOCK_EX);
    if ($wasUploadedSuccessfully === false){
        echo "There was an error writing in index.txt file";
    }
    else{
        //echo $wasUploadedSuccessfully;
    }
}


function showLogInMessage(){ //check if works
    echo "Loggin in " . $_POST['userNamesignIn'] . "</br>";
    echo "Redirecting...";
    sleep(1,5);
}

function showSignInMessage(){ //check if works
    echo "Signing in " . $_POST['userNamesignIn'] . "</br>";
    echo "Redirecting...";
    sleep(1,5);
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
    <input type="submit" value="Login"> 
    </form>
    </td>

    <td>  
    <form action="login.php" method="post"> 
    <input type="hidden" value="true" name="signInForm" id="signInForm"> 
    <input type="submit" value="Sign In"> 
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