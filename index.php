<?php
    @session_start();
    define("USERNAME","omarseguvi");
    define("USER_INFORMATION_FILE","user_info_file.txt");
    define("INDEX_USER_INFORMATION_FILE","index_user_info_file.txt");
    $indexUserFilesArray = array();

    init();

    function setIndexArrayFromFile(){
        global $indexUserFilesArray; 
        $indexUserFilesArray = array();
        $file_path = getUserPath() . "\\" . INDEX_USER_INFORMATION_FILE;
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

    function getFileUserInformationByLimits($name){
        global $indexUserFilesArray;
        $file_path = getUserPath() . "\\" . USER_INFORMATION_FILE;
        $limitsArray = getLimitsArray($name);
        if(file_exists($file_path)){
            $contactsFile = fopen($file_path,"r");
            $isSuccess = fseek($contactsFile, $limitsArray[0]);
            if($isSuccess == 0){
                $lenght = $limitsArray[1] - $limitsArray[0];
                $file_data = fread($contactsFile,$lenght);
                return $file_data;
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

    function getCurrentFileIndexPos($filename){
        global $indexUserFilesArray;
        $index = 0;
        foreach($indexUserFilesArray as $item){
            $dataArray = explode(",",$item);
            if($dataArray[0] == $filename){
                return $index;
            }
            $index++;
        }
        return false;
    }

    function getCurrentUserName(){
        if ( isset($_SESSION['userName']) ){
            $userName =  $_SESSION["userName"] ;
            return $userName;
        }else{
            redirectUnauthorized();
        } 
    }

    function redirectUnauthorized(){
        $location = "./unauthorized.php";
        redirect( $location);
    }
    
    function redirect($url, $statusCode = 303)
    {
       header('Location: ' . $url, true, $statusCode);
       die();
    }


    function init(){
        $result = setIndexArrayFromFile();

        if(isFileSubmitted()){
             saveFileInformation();
        }

        if(isDeletedFileFormSubmitted()){
            global $indexUserFilesArray;
            $file_name = $_POST["delete-file-name"];
            $file_data_bundle = getFileUserInformationByLimits($file_name);
            $file_data_array = explode(",",$file_data_bundle);
            $indexPos = getCurrentFileIndexPos($file_data_array[0]);
            $index_data_bundle = $indexUserFilesArray[$indexPos];
            $index_data_array = explode(",",$index_data_bundle);
            //Update the index value with the bit is active as 0
            $index_data_array[2] = 0;
            $updated_index_data = $index_data_array[0] . "," . $index_data_array[1] . "," . $index_data_array[2];
            $updated_index_data = str_pad($updated_index_data,40," ");
            //Deletes the file
            unlink($file_data_array[6]);
            //Re-writes the file with the new index information
            $file_path = getUserPath() . "\\" . INDEX_USER_INFORMATION_FILE;
            if(file_exists($file_path)){
                $indexFile = fopen($file_path,"r+");
                if($indexFile){
                    fseek($indexFile,$indexPos*40);
                    fwrite($indexFile, $updated_index_data,40);
                }
            }
        }


    }

    function isFileSubmitted(){
        return (isset($_FILES['userfile']['name']))? true : false;
    }

    function isDeletedFileFormSubmitted(){
        return (isset($_POST['delete-file-name']))? true : false;
    }

    function isEditFileFormSubmitted(){
        return (isset($_POST['edit-file-name']))? true : false;
    }

    function saveFileInformation(){
        $file_path = getFilePath() . $_FILES['userfile']['name'];
        if(move_uploaded_file($_FILES['userfile']['tmp_name'], $file_path)){
            $file_info = getConcatenatedFileInformationByCommasAsString($file_path);
            $file_txt_path = getUserPath() . "\\" . USER_INFORMATION_FILE;
            $position_file_info = getSavedInformationPosition($file_txt_path,$file_info);
            //Adding a bit at the end of data to notify that the data is active (Will help to delete records)
            $index_file_info_data = $_FILES['userfile']['name'] . "," . $position_file_info . ",1";
            insertIndexintoFile($index_file_info_data);
        }
        else{
            echo "no se pudo mover";
        }
    }

    function insertIndexintoFile($data){
        $data = str_pad($data,40," ");
        $file_path = getUserPath() . "\\" . INDEX_USER_INFORMATION_FILE;
        $indexWasSavedSuccessfully   = file_put_contents($file_path,$data,FILE_APPEND | LOCK_EX);
        return ($indexWasSavedSuccessfully)? true : false;
    }

    function getFilePath(){
        $directory_path = getUserPath() . "\\files\\";
        if(!file_exists($directory_path)){
            mkdir($directory_path);
        }
        return $directory_path;
    }

    //Validates if current user directory exists
    function getUserPath(){
        $directory_path = getUsersPath() . getCurrentUserName();
        if(!file_exists($directory_path)){
            mkdir($directory_path,0777); 
        }
        return $directory_path;
    }

    //Validates if users directory exists
    function getUsersPath(){
        $directory_path = getcwd() . "\\users\\";
        if(!file_exists($directory_path)){
            mkdir($directory_path,0777);
        }
        return $directory_path;
    }

    function getSavedInformationPosition($file_txt_path,$file_data){
        $informationWasSavedSuccessfully = file_put_contents($file_txt_path, $file_data, FILE_APPEND | LOCK_EX);
        return ($informationWasSavedSuccessfully)? filesize($file_txt_path) : false;
    }

    function getConcatenatedFileInformationByCommasAsString($file_path){
        $name = $_FILES['userfile']['name']; //pos 0
        $author = $_POST["author"]; //pos 1
        $description = $_POST["description"]; //pos 2
        $clasification = $_POST["clasification"]; //pos 3
        $size = $_FILES['userfile']['size']; //pos 4
        $date = date('Y-m-d H:i:s'); //pos 5
        $file_info = "$name,$author,$description,$clasification,$size,$date," . $file_path;
        return $file_info;
    }

    function showUserFiles(){
        setIndexArrayFromFile();
        global $indexUserFilesArray;
        echo "<table>";
        foreach($indexUserFilesArray as $item){
            $dataArray = explode(",", $item);
            if(strcmp($dataArray[2],"1") !== -1){ //Reads the isActive bit
                $filename = $dataArray[0];
                echo "<td><tr>$filename";
                echo "<form method='POST' action='index.php'><input type='hidden' name='delete-file-name' value='$filename'><input class='delete-button' type='submit' value='X'></form>";
                echo "<form method='POST' action='index.php'><input type='hidden' name='edit-file-name' value='$filename'><input class='edit-button' type='submit' value='Edit'></form>";
                echo "</tr></td></br>";
            }
        }
        echo "</table>";
    }

    function showForm(){
        if(isEditFileFormSubmitted()){
            $file_name = $_POST['edit-file-name'];
            $data_bundle = getFileUserInformationByLimits($file_name);
            $data_array = explode(",",$data_bundle);
            echo "<label for='author'>Author</label>";
            echo "<input type='text' id='author' name='author' value='$data_array[1]'>";
            echo "<label for='description'>Description</label>";
            echo "<input type='text' id='description' name='description' value='$data_array[2]'>";
            echo "<label for='clasification'>Clasification</label>";
            echo "<input type='text' id='clasification' name='clasification' value='$data_array[3]'>";
            echo "<label for='userfile'>Add file</label>";
            echo "<input name='userfile' type='file' id='userfile'/> <br/>";
            echo "<input type='submit' value='Save' />";
        }
        else{
            echo "<label for='author'>Author</label>";
            echo "<input type='text' id='author' name='author'>";
            echo "<label for='description'>Description</label>";
            echo "<input type='text' id='description' name='description'>";
            echo "<label for='clasification'>Clasification</label>";
            echo "<input type='text' id='clasification' name='clasification'>";
            echo "<label for='userfile'>Add file</label>";
            echo "<input name='userfile' type='file' id='userfile'/> <br/>";
            echo "<input type='submit' value='Save' />";
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>PHP File Management</title>
        <link rel="stylesheet" href="styles/style.css">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    </head>
    <body>
        <header class="main-title">
            <h1>PHP File Management</h1>
        </header>
        <main id="main-index">
            <nav>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Share</a></li>
                    <li><a href="#">About</a></li>
                </ul>
            </nav>
            <div class="content">
                <section id="save-file">
                    <form enctype="multipart/form-data" method="post" action="index.php">
                        <?php
                            showForm();
                        ?>
                    </form>
                </section>
                <aside id="show-files">
                    <p>Saved Files</p>
                    <?php
                        showUserFiles();
                    ?>
                </aside>
            </div>
        </main>
        <footer></footer>
    </body>
</html>

