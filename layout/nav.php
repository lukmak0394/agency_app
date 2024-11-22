
<?php require_once("./configs/globalconst.php");   ?>
<?php require_once(SYSROOT.'app.php'); ?>

<nav class="navbar navbar-expand-lg bg-primary">
  <div class="container d-flex justify-content-end">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-white" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="<?php echo App::getConf("pages", "client_form"); ?>">Add client</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="<?php echo App::getConf("pages", "clients_list"); ?>">Clients list</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="<?php echo App::getConf("pages", "packages_list"); ?>">Packages list</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="<?php echo App::getConf("pages", "contacts_list"); ?>">Contact persons list</a>
        </li>
      </ul>
    </div>
  </div>
</nav>