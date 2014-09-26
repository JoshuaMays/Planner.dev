<?php

require_once('../../data/address_connect.php');
require_once('inc/AddressBook.class.php');

// CREATE ADDRESSBOOK AND CONTACTS ARRAY
$addressBook = new AddressBook($dbc);
$contacts = $addressBook->loadContacts();

require_once('header.php');
?>
    <div id="page-wrap">
        <div class="container">
            <h1 class="text-center">Address Book</h1>
            <div class="col-sm-8 col-sm-offset-2">
                <div class="row">
                    <table class="table table-striped">
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Phone Number</th>
                        <th>View</th>
                        <? // LOOP THROUGH CONTACTS TO DISPLAY THEM ?>
                        <? foreach ($contacts as $index => $contact): ?>
                            <tr>
                                <? foreach ($contact->getContactInfo() as $info): ?>
                                <td><?= $info; ?></td>
                                <? endforeach; ?>
                                <td><a href="view_contact.php?id=<?= $contact->getId(); ?>" class="btn btn-primary">View Contact</a>
                            </tr>
                        <? endforeach; ?>
                    </table>
                </div>
            </div>
        
        
        
    </div>

<? require_once('footer.php'); ?>