<!DOCTYPE html>
<html>
<head>
  <title>WasteFlow System </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="index.php">
        <img src="images/csu_logo.png" alt="CSU Logo" height="40" class="me-2">
        <img src="images/wasteflow_logo.png" alt="WasteFlow Logo" height="40" class="me-2">
        WasteFlow Web-based System
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
              data-bs-target="#navbarNav" aria-controls="navbarNav" 
              aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <!-- Home dropdown -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="index.php" id="homeDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Home
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="homeDropdown">
              <li><a class="dropdown-item" href="login.php">Login</a></li>
              <li><a class="dropdown-item" href="register.php">Register</a></li>
              
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
