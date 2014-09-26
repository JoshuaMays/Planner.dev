<?php

require_once('../../data/address_connect.php');
require_once('inc/AddressBook.class.php');

$newContact = new Person($dbc);

// CAPTURE USER INPUT DATA AND ADD TO THE DATABASE
if(!empty($_POST)) {
    // $newContact 
}


require_once('header.php');

?>
<div class="container">
    <h2 class="text-center">Add a Contact</h2>
    <div class="row">
        <form method="POST" id="addContactForm" role="form" class="form-horizontal col-sm-offset-1">
            <div class="form-group">
                <label for="first_name" class="col-sm-2 control-label">First Name:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First" value="<?= !empty($_POST) ? $_POST['first_name'] : ""; ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="last_name" class="col-sm-2 control-label">Last Name:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name" value="<?= !empty($_POST) ? $_POST['last_name'] : ""; ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">Phone:</label>
                <div class="col-sm-6">
                    <input type="tel" class="form-control" name="phone" id="phone" placeholder="(999)999-9999" pattern="[\(]\d{3}[\)]\d{3}[\-]\d{4}" title="phone number (format: (999)999-9999)" value="<?= !empty($_POST) ? $_POST['phone'] : ""; ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="address" class="col-sm-2 control-label">Address:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="address" id="address" placeholder="321 Main St." value="<?= !empty($_POST) ? $_POST['address'] : "";?>">
                </div>
            </div>
            <div class="form-group">
                <label for="city" class="col-sm-2 control-label">City:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="city" id="city" placeholder="Anywhereville" value="<?= !empty($_POST) ? $_POST['city'] : "";?>">
                </div>
            </div>
            <div class="form-group">
                <label for="state" class="col-sm-2 control-label">State:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="state" id="state" placeholder="TX" value="<?= !empty($_POST) ? $_POST['state'] : "";?>">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-10 col-sm-offset-2">
                    <a href="/addressbook/" class="btn btn-default">Go Back</a>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </div>
        </form><!-- END #addContactForm -->
    </div>
</div>
<? require_once ('footer.php');