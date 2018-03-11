<?php
    define("USERNAME","omarseguvi");
    define("USER_INFORMATION_FILE","user_info_file.txt");
    define("INDEX_USER_INFORMATION_FILE","index_user_info_file.txt");

    init();

    function init(){
        if(isFileSubmitted()){
             saveFileInformation();
        }
    }

    function isFileSubmitted(){
        return (isset($_FILES['userfile']['name']))? true : false;
    }

    function saveFileInformation(){
        $file_path = getFilePath() . $_FILES['userfile']['name'];
        if(move_uploaded_file($_FILES['userfile']['tmp_name'], $file_path)){
            $file_info = getConcatenatedFileInformationByCommasAsString($file_path);
            $file_txt_path = getUserPath() . "\\" . USER_INFORMATION_FILE;
            $position_file_info = getSavedInformationPosition($file_txt_path,$file_info);
            $index_file_info_data = $_FILES['userfile']['name'] . "," . $position_file_info;
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

    function getCurrentUserName(){
        return USERNAME;
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
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>PHP File Management</title>
        <link rel="stylesheet" href="styles/style.css">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Sonsie+One" rel="stylesheet" type="text/css">
    </head>
    <body>
        <header>
            <h1>PHP File Management</h1>
            <nav>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Share</a></li>
                    <li><a href="#">About</a></li>
                </ul>
            </nav>
        <header>
        <main>
            <section id="save-file">
                <form enctype="multipart/form-data" method="post" action="index.php">
                    <label for="author">Author</label>
                    <input type="text" id="author" name="author">
                    <label for="description">Description</label>
                    <input type="text" id="description" name="description">
                    <label for="clasification">Clasification</label>
                    <input type="text" id="clasification" name="clasification">
                    Add file:<input name="userfile" type="file"/> <br/>
                    <input type="submit" value="Save" />
                </form>
            </section>
            <section id="show-files">

            </section>
        </main>
        <footer></footer>
    </body>
</html>

