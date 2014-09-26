<?php

require_once('../../data/address_connect.php');
require_once('inc/Person.class.php');

$contactID = $_GET['id'];
$contact = new Person($dbc, $contactID);

$contactInfo = $contact->getContactInfo();

require_once('header.php');

?>
    <div class="container">
        <div class="row">
            <div id="contactInfo" class="col-sm-5 col-sm-offset-1 well">
                <!-- DISPLAY CONTACT -->
                <h1 id="contactName"><?= $contactInfo['first_name'] . " " . $contactInfo['last_name']; ?></h1>
                <address>
                    <p id="contactPhone">Phone: <?= $contactInfo['phone']; ?></p>
                    <p id="contactAddress">Address:</p>
                    <p id="contactCityState"><span id="contactCity"></span>, <span id="contactState"></span><span id="contactZip"></span></p>
                </address>
            </div>
            <div id="photoWrap" class="col-sm-3 col-sm-offset-1">
                <div class="thumbnail">
                    <img src="http://placehold.it/150x150" alt="placeholder img">
                </div>
            </div>
        </div>
<? require_once('footer.php'); ?>