<?php

require('../../data/todo_list_connect.php');
require('inc/TodoManager.class.php');

// LOAD A TODOMANAGER OBJECT FOR DISPLAYING TODO LIST
$todoManager = new TodoManager($dbc);

// ADD A TODO ITEM TO THE LIST
if (isset($_POST['content'])) {
    $newTodo = new TodoItem($dbc);
    $newTodo->insert();
}

// MARK A TODO ITEM COMPLETE
if (isset($_POST['remove_item'])) {
    $completeTodo = new TodoItem($dbc, $_POST['remove_item']);
    $completeTodo->update();
}

// WORKING ON IMPORTING A FILE OF LIST ITEMS
if (count($_FILES) > 0 && $_FILES['fileUpload']['error'] == UPLOAD_ERR_OK && $_FILES['fileUpload']['type'] == 'text/plain') {
    $test = $todoManager->importFile();
    
}

// DISPLAY ALL OF THE TODO ITEMS THAT HAVE NOT BEEN MARKED COMPLETE
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
// ASSIGNING COUNT OF RECORDS IN TABLE TO A VARIABLE FOR PAGINATION LINKS
$count = (int) $dbc->query('SELECT count(*) FROM todo_items WHERE date_completed IS NULL')->fetchColumn();

$todos = $todoManager->loadItems($offset); 

?>
<html>
<head>
    <title>Todo List</title>
    <!-- LATEST COMPILED AND MINIFIED CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <!-- CUSTOM STYLES -->
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
    <div id="page-wrap" class="container">

        <h1 id="todo">TODO LIST!</h1>
        <div id="addItem" class="container">
            <div class="row">
                <h2 class="text-center">Add a Task to the List: </h2>
                <form method="POST" role="form" class="form-horizontal">
                    <div class="form-group">
                        <label for="content" class="col-sm-2 control-label">Todo: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="content" id="content" placeholder="What needs to be done?">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-2">
                            <button type="submit" class="btn btn-primary">Add it</button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- END addItem  -->
        <div class="row">
            <div id="todoList" class="col-sm-6">
                <ol>
                    <? // LOOP THROUGH LIST OF TODO ITEMS TO DISPLAY ?>
                    <? foreach ($todos as $todoIndex => $itemContent) : ?>
                        <li><button class="btn btn-danger remove-button" data-item-id="<?= $itemContent->getId(); ?>" >Complete</button> <?= $itemContent->getContent(); ?></li>
                    <? endforeach; ?>
                </ol>
                <? // HIDE PREV BUTTON WHEN AT BEGINNING OF RECORDS ?>
                <? if ($offset != 0): ?>
                    <a href="?offset=<?=$offset-10;?>"><button class="btn btn-primary">Prev</button></a>
                <? endif; ?>
                
                <!-- HIDE NEXT BUTTON WHEN AT END OF RECORDS -->
                <? if (($offset+10) < $count): ?>
                    <a href="?offset=<?=$offset+10;?>"><button class="btn btn-primary pull-right">Next</button></a>
                <? endif; ?>
            </div>
            <div id="import-file" class="col-sm-4 col-sm-offset-2">
                <h2>Add Tasks from a File:</h2>
                <!-- FORM FOR IMPORTING A LIST OF TODO ITEMS -->
                <form method="POST" enctype="multipart/form-data">
                    <p><label for="upload">File to Upload:</label>
                    <input type="file" name="fileUpload" id="fileUpload" class="inline"></p>
                    <p><input type="radio" name="listPosition" value="top" id="addTop"> <label for="addTop">Add to Top</label><br>
                    <input type="radio" name="listPosition" value="bottom" id="addBottom" checked> <label for="addBottom">Add to Bottom</label></p>
                    <p><input type="submit" value="Upload" class="btn btn-default"></p>
                </form>
            </div><!-- END import-file -->
        </div>
        <form action="/todolist/todo_list.php" method="POST" id="remove-form">
            <input type="hidden" name="remove_item" id="remove-item">
        </form><!-- END remove-form -->
    </div><!-- END page-wrap -->
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