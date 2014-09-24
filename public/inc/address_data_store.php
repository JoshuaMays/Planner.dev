<?php 

require('filestore.php');

// CLASS DEFINITION FOR ADDRESS DATA STORE
class AddressDataStore extends Filestore {

    // OVERRIDE PARENT CONSTRUCTOR TO FORCE FILENAME TO BE LOWERCASE
    public function __construct($filename = '') {
        parent::__construct(strtolower($filename));
    }

    // CHECK LENGTH OF EACH INPUT, THROW EXCEPTION IF OVER 125
    public function checkLength($array) {
        foreach ($array as $input) {
            if (strlen($input) == 0) {
                throw new Exception ("<img src='/img/sparklepizza.gif' alt='Error Pizza'><br><p>\"$input was empty. <br>At least you tried. Have some pizza.</p>");
            } else if (strlen($input) > 125) {
                throw new Exception("<img src='/img/sparklepizza.gif' alt='Error Pizza'><br><p>\"$input was empty. <br>At least you tried. Have some pizza.</p>");
            }
        }
    }
}