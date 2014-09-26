<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/lister/">Address Book</a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <!-- CHANGE BACKGROUND OF NAV LINK WHEN TARGET IS THE CURRENT PAGE -->
                <li class="<?= ($_SERVER['PHP_SELF'] == '/addressbook/add_contact.php') ? 'active' : ''; ?>"><a href="add_contact.php">Add Contact</a></li>
            </ul>
        </div>
    </div>
</nav>