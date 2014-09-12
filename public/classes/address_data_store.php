<?php 
// CLASS DEFINITION FOR ADDRESS DATA STORE
class AddressDataStore {
    public $filename = '';
    
    // CONSTRUCTOR FOR ADDRESS DATA STORE OBJECTS
    public function __construct($filename) {
        $this->filename = $filename;
    }

    // FUNCTION TO READ CSV FILE DATA INTO AN ARRAY
    public function readAddressBook() {
        $addressBook = [];
        $handle = fopen($this->filename, 'r');
        while (!feof($handle)) {
            $row = fgetcsv($handle);
            if (!empty($row)) {
                $addressBook[] = $row;
            }
        }
        fclose($handle);
        return $addressBook;
    }

    // FUNCTION TO SAVE ADDRESS ENTRY DATA TO CSV FILE
    public function saveAddressBook($addressArray) {
        $handle = fopen($this->filename, 'w');
        
        foreach($addressArray as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);
    }
}