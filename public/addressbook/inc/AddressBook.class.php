<?php

require_once('../../data/address_connect.php');
require_once('Person.class.php');

class AddressBook {

    protected $dbc;

    // CONSTRUCTOR FOR ADDRESSBOOK OBJECTS
    public function __construct($dbc) {
        $this->dbc = $dbc;
    }

    // METHOD FOR LOADING ALL OF THE CONTACTS FOR DISPLAY
    public function loadContacts() {
        // SELECT STATEMENT FOR RETURNING ALL OF THE CONTACTS
        $contactsStmt = $this->dbc->query('SELECT id FROM person');

        // INITIALIZE EMPTY ARRAY TO HOLD CONTACTS
        $contacts = [];

        // FETCH ROW OF CONTACTS FOR EACH ID IN TABLE
        while($row = $contactsStmt->fetch(PDO::FETCH_ASSOC)) {
            // CREATE PERSON OBJECT FOR EACH ROW
            $contact = new Person($this->dbc, $row['id']);
            // PUSH CONTACT ON THE THE CONTACTS ARRAY
            $contacts[] = $contact;
        }

        return $contacts;
    }
}


