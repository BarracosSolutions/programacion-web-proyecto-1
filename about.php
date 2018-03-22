<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">

    <title>About</title>
    <link rel="stylesheet" href="./styles/style.css">
  </head>

  <body>
    <!-- Here is our main header that is used across all the pages of our website -->

    <header class="main-title">
      <h1>About</h1>
    </header>

    <main id="main-index">
    <nav>
      <ul>
        <li><a href="./index.php">Home</a></li>
        <li><a href="#">About</a></li>
      </ul>

    </nav>

   
       
            <div id="indice">
            <h3> Table of Contents </h3>
                <ul>
                    <li>
                      <a href="#login_help">Login</a>
                    </li>
                    <li>
                      <a href="#upload_PHP_Files_help">Upload PHP Files</a>
                    </li>
                    <li>
                      <a href="#edit_information_help">Edit Information</a>
                    </li>
                    <li>
                      <a href="#delete_information_help">Delete Information</a>
                    </li>
                </ul>
            </div>
      
      <div class="content">  
        <article>
            <h2>Help</h2>

            <p>This page is to help the user to use the PHP File Management application, below is the description of the application's functionalities.</p>

            <h3 id="login_help">Login</h3>

            <p>To use the application you need to create a user, when you open the 
            application for the first time it will come out the login page where 
            you can create a new user (Sign In) or you can Log In in the application 
            if you already have an user. </p>

            <p> If you have a user already created and you try to Sign In the apllication
            will verify if the user exist and if the password is correct and is going
            to use that user to log you in. </p>


            <h3 id="upload_PHP_Files_help">Upload PHP Files</h3>

            <p>Once you are in the Home page if you are going to update a file, you
            need to fill all the required spaces, and you click the "Choose File"
            button and pick you PHP file that you want you upload in the account of 
            the logged in user. </p>

            <p> Once you upload a file in your account, it is going to appear in the right 
            side of the page with the option to delete the file or edit the information. </p>

            <h3 id="edit_information_help">Edit Information</h3>

            <p>Si ya el usuario logueado tiene archivos subidos a la aplicacion se mostraran 
            del lado derecho de la pagina Home, a la par del nombre del archivo se mostraran 
            las opciones de editar la informacion y borrar el archivo, al darle el boton de 
            editar se mostrara la informacion del archivo en el formulario, y podremos editar los campos.</p>

            <p> Cuando se quiera guardar la nueva informacion le da click en el boton de save. </p>

            <h3 id="delete_information_help">Delete Information</h3>

            <p> At the same time of each file uploaded to the application is also the button to remove 
            it is a red X, click on the button will remove the file of the application. </p>

            </article>


        </main>
    </div>
    <!-- And here is our main footer that is used across all the pages of our website -->

    <footer>
      <p>©Copyright 2050 by nobody. All rights reversed.</p>
    </footer>

  </body>
</html>