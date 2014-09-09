<?php
    // Define a constant filepath/name to read/write
    define('FILENAME', 'data/todo_items.txt');

    // Initialize the todoList variable
    $todoList = [];

    // Open the file to populate the list
    function listFromFile($filename = FILENAME) {
        // Open file, save content of file to a string $content and close the file.
        $handle = fopen($filename, 'r');
        // If the list file is empty, set a filesize of 100.
        $filesize = filesize($filename) == 0 ? 100 : filesize($filename);
        $content = trim(fread($handle, $filesize));
        fclose($handle);
        // Create an array from the string $content.
        $list = explode("\n", $content);
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
        <ul>
            <?php
                
                if (!empty($_POST)) {
                    $todoList[] = $_POST['add_item'];
                    saveFile($todoList);
                }
                
                if (isset($_GET['remove'])) {
                    $removeKey = $_GET['remove'];
                    unset($todoList[$removeKey]);
                    $todoList = array_values($todoList);
                    saveFile($todoList);
                }
                
                    foreach ($todoList as $key => $item) {
                        echo '<li><a href=' . "?remove=$key" . ">Mark Complete</a> - $item</li>";
                        // saveFile($todoList);
                    }
            ?>
        </ul>
        <div id="form">
            <h2>Add a Task to the List</h2>
            <form method="POST" action="/todo_list.php">
                <input type="text" name="add_item" id="add_item" placeholder="fill me out, yo.">

                <input type="submit" value="Put me in, Coach">
            </form>
        </div>
    </div>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>


