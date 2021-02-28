<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// start session
ini_set('session.gc_maxlifetime', 1000);
session_start();
// git ripo to get all code
// https://github.com/RemcoSmitsDev/template-framework/archive/master.zip

function recursive_files_copy($source_dir, $destination_dir)
{
    // Open the source folder / directory
    $dir = opendir($source_dir);

    // Create a destination folder / directory if not exist
    @mkdir($destination_dir);

    // Loop through the files in source directory
    while ($file = readdir($dir)) {
        // Skip . and ..
        if (($file != '.') && ($file != '..')) {
            // Check if it's folder / directory or file
            if (is_dir($source_dir.'/'.$file)) {
                // Recursively calling this function for sub directory
                recursive_files_copy($source_dir.'/'.$file, $destination_dir.'/'.$file);
            } else {
                // Copying the files
                copy($source_dir.'/'.$file, $destination_dir.'/'.$file);
            }
        }
    }
    closedir($dir);
}

function rrmdir($dir)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir."/".$object) == "dir") {
                    rrmdir($dir."/".$object);
                } else {
                    unlink($dir."/".$object);
                }
            }
        }
        reset($objects);
        rmdir($dir);
    }
}

if (isset($_POST["db_name"],$_POST["website_name"],$_POST["username"],$_POST["password"]) && !empty($_POST["db_name"]) && !empty($_POST["website_name"])) {
    // unset($_SESSION);
    // session_unset();
    // define variables
    function db_make_connection()
    {
        //DB Inlog
        $db_servername = "localhost";
        $db_username = "root";
        $db_password = "root";

        // Create connection
        $connection = new mysqli($db_servername, $db_username, $db_password);
        $connection->set_charset("utf8");

        // Check connection
        if ($connection->connect_error) {
            $errors_database[] = $connection->connect_error." <b>Try to change the username/password of this installer for your phpmyadmin</b>";
            $_SESSION['errors_database'] = $errors_database;
            $_SESSION['errors_bestanden'] = [];
            echo "<script>window.location.href = 'installer.php';</script>";
            exit;
            return false;
        }

        //Return connection
        return $connection;
    }
    $website_name = $_POST["website_name"];
    $db_name = $_POST["db_name"];
    $db_username = $_POST["username"];
    $db_password = $_POST["password"];
    $errors_bestanden = [];
    $errors_database = [];


    // make connection to database
    $connection = db_make_connection();

    function createDatebase($db_name)
    {
        $connection = db_make_connection();

        $query = "CREATE DATABASE ".$db_name;

        global $errors_database;

        if ($connection->query($query)) {
            $errors_database[] = "<b no-error>Database is added!</b>";
            $connection->select_db($db_name);
            $user_query = "CREATE TABLE `users` (`Id` int(11) NOT NULL,`Name` varchar(255) NOT NULL,`Email` varchar(255) NOT NULL,`Password` varchar(255) NOT NULL,`Salt` varchar(255) NOT NULL,`Is_Admin` enum('true','false') NOT NULL DEFAULT 'false',`Token` varchar(255) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            if($connection->query($user_query)){
                $errors_database[] = "<b no-error>User table created!</b>";
            }else{
                $errors_database[] = "<b>Somthing went wrong when making user table!</b>";
            }
        } else {
            $errors_database[] = "<b>Database already exists!</b>";
        }

        $connection->close();
        return;
    }

    function makeDBUser($db_username, $db_password)
    {
        global $errors_database;
        $connection = db_make_connection();

        $query = "CREATE USER '".$db_username."'@'localhost' IDENTIFIED BY '".$db_password."'";

        if ($connection->query($query)) {
            // add all rechten
            $errors_database[] = "<b no-error>User added to database!</b>";

            $query_pref = "GRANT ALL PRIVILEGES ON  *.* TO '".$db_username."'@'localhost' REQUIRE NONE WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0";

            if (!$connection->query($query_pref)) {
                $errors_database[] = "<b>Somthing went wrong when changing permissions for the database user!</b>";
            } else {
                $errors_database[] = "<b no-error>User permissions are changed!</b>";
            }
            $connection->close();
            return true;
        } else {
            $connection->close();
            $errors_database[] = "<b>DB user already exists! OR Something went wrong when making a new database user!</b>";
            // default userinfo to passed in to functions.php
            return false;
        }
    }

    // create new database
    createDatebase($db_name);
    if (!empty($_POST["username"])) {
        $db_username = $_POST["username"];
        $db_password = $_POST["password"];
    } else {
        $db_username = "root";
        $db_password = "root";
    }


    // make db user if the user has passed in
    if (isset($_POST["username"],$_POST["password"]) && !empty($_POST["username"]) && !empty($_POST["password"])) {
        makeDBUser($_POST["username"], $_POST["password"]);
    }

    // close connection
    $connection->close();

    if (copy('https://github.com/RemcoSmitsDev/template-framework/archive/master.zip', 'temp.zip')) {
        $zip = new ZipArchive;
        $res = $zip->open(__dir__.'/temp.zip');
        if ($res === true) {
            // extract zip file
            $zip->extractTo(__dir__);
            $zip->close();

            // remove zip
            unlink('temp.zip');

            // // place folder content to main folder
            $source_dir = __dir__."/template-framework-master";
            $destination_dir = "./";
            recursive_files_copy($source_dir, $destination_dir);
            rrmdir(__dir__."/template-framework-master");
            $errors_bestanden[] = "<b no-error>Files are saved!</b>";
        } else {
            $errors_bestanden[] = "<b>Somthing went wrong when copying the files!</b>";
        }
    }

    // default files and content
    $files = array(
        array(
            "file" => $_SERVER['DOCUMENT_ROOT']."/includes/php/config.php",
            "replace" => [
                "{username}" => $db_username,
                "{password}" => $db_password,
                "{db_name}" => $db_name
                ]
        ),
    );

    // make all files and add content to it
    foreach ($files as $key => $file) {
        if (isset($file['replace'])) {
            if(!$temp_string = file_get_contents($file['file'])){
                $errors_bestanden[] = "<b>File not found/File is empty!</b>";
            }
            foreach ($file['replace'] as $key => $value) {
                $temp_string = str_replace($key, $value, $temp_string);
            }
            if(file_put_contents($file['file'], $temp_string)){
                $errors_bestanden[] = "<b no-error>Database config updated!</b>";
            }else{
                $errors_bestanden[] = "<b>Something went wrong when chaning Database config!</b>";
            }
        }
    }
    // put errors in session
    $_SESSION['errors_database'] = $errors_database;
    $_SESSION['errors_bestanden'] = $errors_bestanden;

    // prevent resubmission
    unset($connection);
    unset($query);
    unset($query_pref);
    unset($errors_database);
    unset($errors_bestanden);
    echo "<script>if(window.history.replaceState){window.history.replaceState( null, null, window.location.href );}</script>";
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Make folder structure and new database/user</title>
        <link rel="icon" href="https://hjmediagroep.nl/wp-content/uploads/2019/09/favicon.png" sizes="192x192">
        <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" media="all" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(() => {
                $("#switch > button").click(({target}) => {
                    $("#switch > button").toggleClass('bg-gray-300');
                    $(".switch-content").toggle();
                });
            })
        </script>
    </head>
    <body class="px-4 h-screen w-full flex items-center justify-center overflow-hidden">
        <?php if (isset($_SESSION['errors_bestanden'],$_SESSION['errors_database'])): ?>
        <div class="sm:m-4 p-4 sm:p-0 space-x-4 flex max-w-sm w-full absolute top-0 right-0" id="switch">
            <button class="block w-full px-4 py-2 rounded bg-gray-100 text-gray-700 focus:outline-none transition duration-500 ease-in-out" type="button" name="button">Bestanden</button>
            <button class="block w-full px-4 py-2 rounded bg-gray-100 bg-gray-300 text-gray-700 focus:outline-none transition duration-500 ease-in-out" type="button" name="button">Database</button>
        </div>
        <?php if (isset($_SESSION['errors_bestanden']) && !empty($_SESSION['errors_bestanden'])): ?>
        <div class="mt-16 p-4 space-y-4 absolute top-0 right-0 overflow-y-auto h-64 hidden switch-content">
            <?php foreach ($_SESSION['errors_bestanden'] as $error): ?>
                <div class="px-4 py-2 max-w-sm <?php if (strpos($error, 'exists') && !strpos($error, 'no-error')): echo 'bg-yellow-500'; elseif (strpos($error, 'no-error')): echo 'bg-green-500'; else: echo 'bg-red-500'; endif; ?> rounded">
                    <p class="break-all text-white"><?= $error; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['errors_database']) && !empty($_SESSION['errors_database'])): ?>
        <div class="mt-16 p-4 space-y-4 absolute top-0 right-0 overflow-y-auto h-64 switch-content">
            <?php foreach ($_SESSION['errors_database'] as $error): ?>
                <div class="px-4 py-2 max-w-sm <?php if (strpos($error, 'exists') && !strpos($error, 'no-error')): echo 'bg-yellow-500'; elseif (strpos($error, 'no-error')): echo 'bg-green-500'; else: echo 'bg-red-500'; endif; ?> rounded">
                    <p class="break-all text-white"><?= $error; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <?php endif; ?>
        <form class="space-y-8 max-w-md" method="post">
            <h2 class="font-semibold text-xl">Make database and folder structure (if user && pass has passed in make database user)</h2>
            <div class="space-y-4 w-full">
                <span class="block relative">
                    <label for="website_name" class="absolute top-0 -mt-2.5 ml-4 z-10 text-sm whitespace-nowrap bg-white">* Website naam</label>
                    <input class="block w-full px-4 py-2 border rounded appearance-none focus:outline-none" type="text" id="website_name" name="website_name" value="<?php if (isset($_POST['website_name'])): echo $_POST['website_name']; endif; ?>" required>
                </span>
                <span class="block relative">
                    <label for="db_name" class="absolute top-0 -mt-2.5 ml-4 z-10 text-sm whitespace-nowrap bg-white">* Database naam</label>
                    <input class="block w-full px-4 py-2 border rounded appearance-none focus:outline-none" type="text" name="db_name" id="db_name" value="<?php if (isset($_POST['db_name'])): echo $_POST['db_name']; endif; ?>" required>
                </span>
                <span class="block relative">
                    <label for="username" class="absolute top-0 -mt-2.5 ml-4 z-10 text-sm whitespace-nowrap bg-white">DB Gebruikers naam</label>
                    <input class="block w-full px-4 py-2 border rounded appearance-none focus:outline-none" type="username" name="username" id="username" value="<?php if (isset($_POST['username'])): echo $_POST['username']; endif; ?>">
                </span>
                <span class="block relative">
                    <label for="password" class="absolute top-0 -mt-2.5 ml-4 z-10 text-sm whitespace-nowrap bg-white">DB Wachtwoord</label>
                    <input class="block w-full px-4 py-2 border rounded appearance-none focus:outline-none" type="password" name="password" id="password" value="<?php if (isset($_POST['password'])): echo $_POST['password']; endif; ?>">
                </span>
                <button class="block w-full px-4 py-2 border rounded font-semibold" type="submit" name="button">Make website</button>
            </div>
        </form>
    </body>
</html>
