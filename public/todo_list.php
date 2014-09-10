<?php
    // Define a constant filepath/name to read/write
    define('FILENAME', '../data/todo_items.txt');

    // Initialize the todoList variable
    $todoList = [];

    // Open the file to populate the list
    function listFromFile($filename = FILENAME) {
        $list = [];
        if (filesize($filename) > 0) {
            // Open file, save content of file to a string $content and close the file.
            $handle = fopen($filename, 'r');
            $content = trim(fread($handle, filesize($filename)));
            fclose($handle);
            // Create an array from the string $content.
            $list = explode("\n", $content);
        }
        return $list;
    }

    function saveFile($todoList, $filename = FILENAME) {
        // Open file and overwrite contents.
        $handle = fopen($filename, 'w');
        // Loop through the list and write each list item to file.
        foreach ($todoList as $listItem) {
            fwrite($handle, $listItem . PHP_EOL);
        }
        fclose($handle);
    }
    // Populate todoList with items from the file.
    $todoList = listFromFile();
    
    // Allow user to 
    if (count($_FILES) > 0 && $_FILES['fileUpload']['error'] == 0 && $_FILES['fileUpload']['type'] == 'text/plain') {
        // upload directory path
        $upload_dir = '/vagrant/sites/planner.dev/public/uploads/';
        // uploaded file name
        $uploadfilename = basename($_FILES['fileUpload']['name']);
        $savedfile = $upload_dir . $uploadfilename;
        // move tmp file to uploads directory
        move_uploaded_file($_FILES['fileUpload']['tmp_name'], $savedfile);
    }
    // Give user access to the file they just uploaded
    if (isset($savedfile)) {
        echo "OMG YOU UPLOADED A FILE! Download your file <a href='/uploads/{$uploadfilename}'>here</a>";
    }
    // Add single item from input form.
    if (isset($_POST['additem'])) {
        $_POST['additem'] = strip_tags($_POST['additem']);
        $todoList[] = $_POST['additem'];
        saveFile($todoList);
    }

    // Remove single item from $todoList
    if (isset($_POST['remove_item'])) {
        $removeKey = $_POST['remove_item'];
        unset($todoList[$removeKey]);
        $todoList = array_values($todoList);
        saveFile($todoList);
    }

    // Add items from uploaded file to $todoList
    if (isset($_FILES['fileUpload']['name'])) {
        $newlist = listFromFile($savedfile);
        if ($_POST['listPosition'] == 'top') {
            $todoList = array_merge($newlist, $todoList);
        }
        else {
            $todoList = array_merge($todoList, $newlist);
        }
        saveFile($todoList);
    }

?>
<!DOCTYPE html>
<html>
<head>
    <title>Todo List</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
    <div id="container">
        <h1 id="todo">Todo List!</h1>
        <ol><!-- List out each of the todo items from files or form input -->
            <? foreach ($todoList as $key => $item): ?>
                <? if ($key != 0): ?>
                    <li><button class="btn btn-danger remove-button" data-item-id="<?= $key; ?>" >Mark Complete</button> <?= $item ?></li>
                <? endif; ?>
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
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script>
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