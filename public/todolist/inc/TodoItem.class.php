<?php

class TodoItem {
    protected $dbc;
    protected $id;
    protected $content = '';
    protected $date_added;
    protected $date_completed;

    // CONSTRUCTOR FOR TODO ITEMS
    public function __construct($dbc, $id = null) {
        $this->dbc = $dbc;
        
        // CHECK IF THE TODO ITEM ALREADY EXISTS
        if (isset($id)) {
            // ASSIGN ID FROM EXISTING TODO ITEM
            $this->id = $id;
            
            // STATEMENT TO READ IN CONTENT OF TODO ITEM
            $selectStmt = $this->dbc->prepare('SELECT * FROM todo_items WHERE id = ?');
            $selectStmt->execute([$this->id]);
            
            // COPY ASSOCIATIVE ARRAY OF PROPERTIES FROM DATABASE RECORD
            $row = $selectStmt->fetch(PDO::FETCH_ASSOC);
            
            // ASSIGN OBJECT PROPERTIES FROM FETCH ARRAY
            $this->content        = $row['content'];
            $this->date_added     = new DateTime($row['date_added']);
            $this->date_completed = new DateTime($row['date_completed']);
        }
    }
    
    // ALLOW ACCESS TO THE TODO ITEM ID
    public function getId() {
        return $this->id;
    }
    
    // ALLOW ACCESS TO THE TODO ITEM CONTENT
    public function getContent() {
        return $this->content;
    }
    
    
    public function insert() {
        // CREATE A TIMESTAMP FOR DATE ADDED
        $this->date_added = new DateTime();
        $this->content = $_POST['content'];
        
        // PREPARED INSERT STATEMENT FOR NEW TODO ITEMS
        $insertSQL = 'INSERT INTO todo_items (content, date_added)
                      VALUES (:content, :date_added)';
        
        $insertStmt = $this->dbc->prepare($insertSQL);
        
        // BIND TODO ITEM PROPERTIES
        $insertStmt->bindValue(':content',    $this->content, PDO::PARAM_STR);
        $insertStmt->bindValue(':date_added', $this->date_added->format('c'), PDO::PARAM_STR);
        
        $insertStmt->execute();
        
        // ASSIGN AD OBJECT FROM ID OF INSERTED RECORD
        $this->id = $this->dbc->lastInsertId();
    }
    
    public function update() {
        // CREATE A TIMESTAMP FOR DATE COMPLETED
        $this->date_completed = new DateTime();
        
        // PREPARED UPDATE STATEMENT FOR NEW TODO ITEMS
        $updateSQL = 'UPDATE todo_items
                      SET date_completed = :date_completed
                      WHERE id = :id';
                      
        $updateStmt = $this->dbc->prepare($updateSQL);
        
        // BIND DATE COMPLETED PROPERTY
        $updateStmt->bindValue(':id', $this->id, PDO::PARAM_STR);
        $updateStmt->bindValue(':date_completed', $this->date_completed->format('c'), PDO::PARAM_STR);
        $updateStmt->execute();
    }
    
    
}