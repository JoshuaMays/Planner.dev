<?php
    // require_once('../data/todo_item_connect.php');
    // require_once('inc/TodoManager.class.php');
    require('inc/filestore.php');

    // DEFINE A CONSTANT FILEPATH/NAME TO READ/WRITE
    define('FILENAME', '../data/todo_items.txt');

    // INSTANTIATE FILESTORE OBJECT $todo
    $todo = new Filestore(FILENAME);

    // INITIALIZE THE $todoList ARRAY
    $todoList = [];

    // POPULATE $todoList BY READING FROM FILE
    $todoList = $todo->read();

    // ALLOW USER TO UPLOAD A TODO LIST (.TXT ONLY) FILE
    if (count($_FILES) > 0 && $_FILES['fileUpload']['error'] == UPLOAD_ERR_OK && $_FILES['fileUpload']['type'] == 'text/plain') {

        // UPLOAD DIRECTORY PATH
        $upload_dir = '/vagrant/sites/planner.dev/public/uploads/';

        // UPLOADED FILENAME
        $uploadfilename = basename($_FILES['fileUpload']['name']);
        $savedfile = $upload_dir . $uploadfilename;

        // MOVE TMP FILE TO UPLOADS DIRECTORY
        move_uploaded_file($_FILES['fileUpload']['tmp_name'], $savedfile);
    }

    // GIVE USER ACCESS TO THE FILE THEY UPLOADED
    if (isset($savedfile)) {
        echo "Well hello there. You just uploaded a file. Download it @ <a href='/uploads/{$uploadfilename}'>here</a>";
    }

    try {
        // ADD SINGLE ITEM FROM INPUT FORM
        if (isset($_POST['additem'])) {
            // THROW EXCEPTION IF TODO ITEM IS EMPTY OR LONGER THAN 240 CHARS
            if (strlen($_POST['additem']) == 0) {
                throw new Exception("<img src='/img/sparklepizza.gif' alt='Error Pizza'><br><p>Your todo item was empty.<br>At least you tried. Have some pizza.</p>");
            }
            else if (strlen($_POST['additem']) > 240) {
                throw new Exception("<img src='/img/sparklepizza.gif' alt='Error Pizza'><br><p>Your todo item was longer than 240 characters.<br>At least you tried. Have some pizza.</p><br>");
            }
            $_POST['additem'] = strip_tags($_POST['additem']);
            $todoList[] = $_POST['additem'];
            $todo->write($todoList);
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }

    // REMOVE SINGLE ITEM FROM $todoList
    if (isset($_POST['remove_item'])) {
        $removeKey = $_POST['remove_item'];
        unset($todoList[$removeKey]);
        $todoList = array_values($todoList);
        $todo->write($todoList);
    }

    // ADD ITEMS FROM UPLOADED FILE TO $todoList
    if (isset($_FILES['fileUpload']['name']) && $_FILES['fileUpload']['error'] == UPLOAD_ERR_OK) {
        $importTodo = new Filestore($savedfile);
        $newlist = $importTodo->read();

        // ADD ITEMS TO THE TOP OR BOTTOM OF THE LIST
        if ($_POST['listPosition'] == 'top') {
            $todoList = array_merge($newlist, $todoList);
        }
        else {
            $todoList = array_merge($todoList, $newlist);
        }

        // SAVE TODOLIST AFTER IMPORT
        $todo->write($todoList);
    }

?>
<!DOCTYPE html>
<html>
<head>
    <title>Todo List</title>
    <!-- LATEST COMPILED AND MINIFIED CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <!-- CUSTOM STYLES -->
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
    <div id="container">
        <? if (isset($error)): ?>
            <h1><?= $error; ?></h1>
        <? endif; ?>
        <h1 id="todo">Todo List!</h1>
        <ol>
            <!-- LIST OUT EACH OF THE TODO ITEMS -->
            <? foreach ($todoList as $key => $item): ?>
                <li><button class="btn btn-danger remove-button" data-item-id="<?= $key; ?>" >Mark Complete</button> <?= $item ?></li>
            <? endforeach; ?>
        </ol>
        <div id="form" class="container">
            <h2>Add a Task to the List:</h2>
            <form method="POST" action="/todo_list.php">
                <p><input type="text" name="additem" id="additem" placeholder="fill me out, yo."></p>

                <p><input type="submit" class="btn btn-default" value="Put me in, Coach"></p>
            </form>
        </div>
        <div id="upload-container" class="container">
            <h2>Add Tasks from a File:</h2>
            <form method="POST" enctype="multipart/form-data">
                <p><label for="upload">File to Upload:</label>
                <input type="file" name="fileUpload" id="fileUpload" class="inline"></p>
                <p><input type="radio" name="listPosition" value="top" id="addTop"> <label for="addTop">Add to Top</label><br>
                <input type="radio" name="listPosition" value="bottom" id="addBottom" checked> <label for="addBottom">Add to Bottom</label></p>
                <p><input type="submit" value="Upload" class="btn btn-default"></p>
            </form>
        </div>
    </div>
    <form action="/todo_list.php" method="POST" id="remove-form">
        <input type="hidden" name="remove_item" id="remove-item">
    </form>
    <!-- LATEST COMPILED AND MINIFIED JAVASCRIPT -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script>
        // ADDING REMOVE-BUTTON CLASS TO ALLOW USER TO DELETE A TASK
        var removeButtons = document.getElementsByClassName("remove-button");
        for (var i=0; i < removeButtons.length; i++) {
            removeButtons[i].addEventListener("click", function() {
                var itemId = this.attributes['data-item-id'].value;
                
                document.getElementById("remove-item").value = itemId;
                document.getElementById("remove-form").submit();
            });
        }
    </script>
</body>
</html>