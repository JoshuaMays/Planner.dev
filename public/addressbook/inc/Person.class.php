<?php

class Person {

    protected $dbc;
    protected $id;
    protected $first_name = '';
    protected $last_name = '';
    protected $phone = '';

    // CONSTRUCTOR FOR PERSON OBJECTS
    public function __construct($dbc, $id = null) {
        $this->dbc = $dbc;

        // CHECK IF THE ID EXISTS IN THE DB
        if (isset($id)) {

            $this->id = $id;

            // READ IN CONTENT OF PERSON FROM THE DB
            $selectStmt = $this->dbc->prepare('SELECT * FROM person WHERE id = ?');
            $selectStmt->execute([$this->id]);
            
            $row = $selectStmt->fetch(PDO::FETCH_ASSOC);

            // ASSIGN PERSON PROPERTIES TO THE OBJECT
            $this->first_name = $row['first_name'];
            $this->last_name = $row['last_name'];
            $this->phone = $row['phone'];
            
        }
    }

    // ALLOW ACCESS TO THE ID OF THE PERSON
    public function getId() {
        return $this->id;
    }

    // ALLOW ACCESS TO PERSON PROPERTIES
    public function getContactInfo() {
        $personInfo = [];
        
        $personInfo['first_name'] = $this->first_name;
        $personInfo['last_name'] = $this->last_name;
        $personInfo['phone'] = $this->phone;

        return $personInfo;
    }
}


?>