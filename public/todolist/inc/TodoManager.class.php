<?php

require_once('TodoItem.class.php');

class TodoManager {
    
    protected $dbc;
    
    public function __construct($dbc) {
        $this->dbc = $dbc;
    }
    
    public function loadItems() {
        $loadStmt = $this->dbc->query('SELECT * FROM todo_items WHERE date_completed IS NULL');
        
        $todoItems = [];
        
        while($row = $loadStmt->fetch(PDO::FETCH_ASSOC)) {
            $item = new TodoItem($this->dbc, $row['id']);
            $todoItems[] = $item;
        }
        
        return $todoItems;
    }
}