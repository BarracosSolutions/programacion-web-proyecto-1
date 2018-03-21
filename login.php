<?php
define("USER_FILE","./users/usersFile.txt", true);
define("INDEX_USER_FILE","./users/indexUsersFile.txt", true);
$indexUserFilesArray = array();
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">

    <title>PHP File Management</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="styles/style.css">

  </head>

  <body>

    <header class="main-title">
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
    setIndexArrayFromFile();
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
    if(isset( $_POST['userNameLogin']) ){

        $userName = $_POST['userNameLogin'];
        $userPassword = $_POST['userPasswordLogin'];

        $userExist = checkUserFile($userName);
        $validPassword = checkPassword($userName, $userPassword);

    if($userExist){
        if( $validPassword ){
            createSession($userName);
            redirectHome();
        }else{
            showFailedLoginForm();
        }

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
        $validPassword = checkPassword($userName, $userPassword); 

        if($userExist){

            if( $validPassword ){
                createSession($userName);
                redirectHome();
            }else{
                showFailedSignInPasswordForm();
            }

        }else{
            createUser($userName, $userPassword); 
            createSession($userName); 
            redirectHome();
        }
    }else{
        showFailedSignInForm(); 
        showSignInMessage($userName); // change the logic of the msg
    }
}


function checkUserFile($userName){ 
        if (file_exists(INDEX_USER_FILE)) {
            $usersFile = fopen(INDEX_USER_FILE,'r') or die("Unable to open file!");
            while(!feof($usersFile)){
                $entry_array = fgets($usersFile); 
                if(strpos($entry_array, $userName) !== false ){
                    fclose($usersFile);
                    return true;
                }
            }
            fclose($usersFile);
            return false; 
        } else {
            return false;
        }  
}

function checkPassword($userName, $userPassword){

    $userFilePassword = getFileUserPasswordByLimits($userName);
    if( $userPassword == $userFilePassword){
        return true;
    }
    return false;
} 

//--------------------------------------------------------------------

function getFileUserPasswordByLimits($name){
    global $indexUserFilesArray;
    $file_path =  USER_FILE;
    $limitsArray = getLimitsArray($name);
    if(file_exists($file_path)){
        $contactsFile = fopen($file_path,"r");
        $isSuccess = fseek($contactsFile, $limitsArray[0]);
        if($isSuccess == 0){
            $lenght = $limitsArray[1] - $limitsArray[0];
            $file_data = fread($contactsFile,$lenght);
            $pass = explode(",",$file_data);
            return $pass[1];
        }
        else{
            return false;
        }

    }
    else{
        return false;
    }
}

function getLimitsArray($name){
    global $indexUserFilesArray;
    $start = -1;
    $finish = 0;

    foreach($indexUserFilesArray as $item){
        $dataArray = explode(",",$item);
        $start = $finish;
        $finish = $dataArray[1];
        if($dataArray[0] == $name){
            break;
        }
    }
    $limits = array((int)$start,(int)$finish);
    return $limits;
}

function setIndexArrayFromFile(){
    global $indexUserFilesArray; 
    $indexUserFilesArray = array();
    $file_path = INDEX_USER_FILE;
    if(!file_exists($file_path)){
        return false;
    }
    else{
        $indexFile = fopen($file_path,"r");
        if(!$indexFile){
            return false;
        }
        else{
            $index = 0;
            fseek($indexFile,$index*40);
            $data = fread($indexFile,40);
            while(!feof($indexFile)){
                $indexUserFilesArray[$index] = $data;
                $index++; 
                fseek($indexFile,$index*40);
                $data = fread($indexFile,40);
            }
            return true;
        }
    }
}
//--------------------------------------------------------------------

function createSession($userName){ 
    session_start();
    $_SESSION["userName"] = $userName;
    echo  $_SESSION["userName"];
}

function  createUser($userName, $userPassword){

    $data = ($userName . ',' . $userPassword);
    $position_file_info = getSavedInformationPosition(USER_FILE, $data);// 
    $index_file_info_data = $userName . "," . $position_file_info;
    insertIndexintoFile($index_file_info_data);

    if ($wasUploadedSuccessfully === false){
        echo "There was an error writing in index.txt file";
    }
    else{
       
    }
}

function insertIndexintoFile($data){

    if (!file_exists(INDEX_USER_FILE)) {
        $usersFile = fopen(INDEX_USER_FILE,'w') or die("Unable to open file!");
        fclose($usersFile);
    }
    $data = str_pad($data,40," ");
    $file_path = INDEX_USER_FILE;
    $indexWasSavedSuccessfully   = file_put_contents($file_path,$data,FILE_APPEND | LOCK_EX);
    return ($indexWasSavedSuccessfully)? true : false;
}

function getSavedInformationPosition($file_txt_path,$file_data){ //cantidad de caracteres que se guardo nombre + password
    $informationWasSavedSuccessfully = file_put_contents($file_txt_path, $file_data, FILE_APPEND | LOCK_EX);
    //xq lo inserta de nuevo? y guarda el caracter final 
    return ($informationWasSavedSuccessfully)? filesize($file_txt_path) : false;
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
    $location = "./index.php";
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

function showFailedSignInPasswordForm(){
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
    <tr><td class="failedLogin"> Failed to Sign In, </td></tr>
    <tr><td class="failedLogin"> The user already exists </td></tr>
    <tr><td class="failedLogin"> and the password is incorrect </td></tr>
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