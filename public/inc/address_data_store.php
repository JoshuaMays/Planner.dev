<?php 

require('filestore.php');

// CLASS DEFINITION FOR ADDRESS DATA STORE
class AddressDataStore extends Filestore {

    // OVERRIDE PARENT CONSTRUCTOR TO FORCE FILENAME TO BE LOWERCASE
    public function __construct($filename = '') {
        parent::__construct(strtolower($filename));
    }
    
    // METHOD TO READ ADDRESSBOOK CSV FILE DATA INTO AN ARRAY
    public function readAddressBook() {
        $addressBook = $this->readCsv();
        return $addressBook;
    }

    // METHOD TO SAVE ADDRESSBOOK ENTRY DATA TO CSV FILE
    public function writeAddressBook($addressArray) {
        $this->writeCsv($addressArray);
    }
}