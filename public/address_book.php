<?php
// DEFINE A CONSTANT FILEPATH/NAME TO READ/WRITE
define('FILENAME', '../data/address_book.csv');

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

// FUNCTION TO STRIP PHONE NUMBERS OF NON-NUMERIC CHARACTERS
function phoneReplace($phone) {

    $nums = preg_replace('/[^0-9]/','',$phone);
    
    return $nums;
}

// INSTANTIATE AN ADDRESS DATA STORE OBJECT
$addressDS = new AddressDataStore(FILENAME);

// ASSIGN ADDRESS ARRAY TO A VARIABLE
$addressBook = $addressDS->readAddressBook();

$addressDS->readAddressBook($addressBook);

// CAPTURE FORM DATA TO AN ADDRESS BOOK
// if (!empty($_FILES)) 
if (!empty($_POST['name']) && !empty($_POST['address']) && !empty($_POST['city']) && !empty($_POST['state']) && !empty($_POST['zip']) && !empty($_POST['phone'])) {
    // PREVENT CODE INJECTION ON EACH INPUT
    foreach ($_POST as $key => $input) {
        $_POST[$key] = strip_tags(trim($input));
    }

    // ASSIGN FORM INPUT DATA TO SPECIFIC INDEXES
    $newEntry[0] = $_POST['name'];
    $newEntry[1] = $_POST['address'];
    $newEntry[2] = $_POST['city'];
    $newEntry[3] = $_POST['state'];
    $newEntry[4] = $_POST['zip'];
    $newEntry[5] = phoneReplace($_POST['phone']);
    
    // PUSH FORM ADDRESS BOOK ENTRY INTO ADDRESS BOOK ARRAY.
    $addressBook[] = $newEntry;
    // SAVE NEW ENTRY TO CSV FILE
    $addressDS->saveAddressBook($addressBook);
}

// ALLOW USER TO UPLOAD A CSV FILE TO IMPORT CONTACTS INTO THE ADDRESS BOOK
if (count($_FILES) > 0 && $_FILES['fileUpload']['error'] == UPLOAD_ERR_OK) {
        // UPLOAD DIRECTORY PATH
        $uploadDir = '/vagrant/sites/planner.dev/public/uploads/';
        $uploadFilename = basename($_FILES['fileUpload']['name']);
        // UPLOADED PATH AND FILENAME
        $savedFile = $uploadDir . $uploadFilename;
        // MOVE TMP FILE TO UPLOADS DIRECTORY
        move_uploaded_file($_FILES['fileUpload']['tmp_name'], $savedFile);
}

// AFTER FILE IS UPLOADED, SAVE NEW CONTACTS TO THE ADDRESS BOOK 
if (!empty($_FILES) && $_FILES['fileUpload']['error'] == UPLOAD_ERR_OK) {
    // CREATE A NEW ADDRESSDATASTORE OBJECT TO COPY THE NEW CONTACTS
    $importedList = new AddressDataStore($savedFile);
    $newlist = $importedList->readAddressBook();
    // MERGE THE TWO CONTACT LISTS
    $addressBook = array_merge($addressBook, $newlist);
    $addressDS->saveAddressBook($addressBook);
}

// ALLOW USER TO DELETE A CONTACT ENTRY 
if (isset($_POST['remove_item'])) {
    $removeRow = $_POST['remove_item'];
    unset($addressBook[$removeRow]);
    $addressBook = array_values($addressBook);
    $addressDS->saveAddressBook($addressBook);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Address Book</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/address_styles.css" >
</head>
<body>
    <div id="wrap">
        <div class="container">
            <div id ="addressBook" class="row">
                <div class="col-md-8 table-responsive">
                    <h2>Contacts</h2>
                    <table class="table table-striped table-bordered table-condensed">
                        <tr>
                            <th>Name</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Zip Code</th>
                            <th>Phone Number</th>
                            <th>Delete</th>
                        </tr>
                        <!-- LOOPING THROUGH TOP LEVEL ARRAY OF ADDRESS BOOK ENTRIES -->
                        <? foreach($addressBook as $entry => $row): ?>
                            
                            <tr>
                                <!-- LOOPING THROUGH ARRAY OF CONTACT ENTRIES, PRINTING DATA INTO TABLE COLUMNS -->
                                <? foreach($row as $columnData): ?>
                                    <td>
                                        <?= $columnData ?>
                                    </td>
                                <? endforeach; ?>
                                <td><button class="btn btn-danger remove-button" data-item-id="<?= $entry; ?>" >Delete</button></td>
                        <? endforeach; ?>
                    </table>
                </div>
                <div class="container">
                    <div class="col-md-3">
                        <h2>Add a Contact</h2>
                        <form method="POST" action="/address_book.php" class="form-horizontal">
                            <label for="name">Name: </label> 
                            <input type="text" name="name" id="name" class="form-control" placeholder="Name"> <br>
                            <label for="address">Address: </label> 
                            <input type="text" name="address" id="address" class="form-control" placeholder="Address"> <br>
                            <label for="city">City: </label> 
                            <input type="text" name="city" id="city" class="form-control" placeholder="City"> <br>
                            <label for="state">State: </label> 
                            <input type="text" name="state" id="state" class="form-control" placeholder="State"> <br>
                            <label for="zip">Zip: </label> 
                            <input type="text" name="zip" id="zip" class="form-control" placeholder="Zip Code"> <br>
                            <label for="phone">Phone: </label> 
                            <input type="tel" name="phone" id="phone" class="form-control" placeholder="Phone Number"> <br>
                            <input type="submit" value="Submit" class="btn btn-primary">
                        </form>
                        <h2>Import Contacts</h2>
                        <form method="POST" action="/address_book.php" enctype="multipart/form-data" class="form-horizontal">
                            <label for="upload">File to Import:</label>
                            <input type="file" name="fileUpload" id="fileUpload" class="form-control"><br>
                            <input type="submit" value="Import" class="btn btn-primary">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <footer class="text-center">
            &copy; 2014 &bull; Joshua Mays
            </footer>
        </div>
    </div>
    <form action="/address_book.php" method="POST" id="remove-form">
        <input type="hidden" name="remove_item" id="remove-item">
    </form>
    <script src="/js/jquery-1.11.0.js"></script>
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