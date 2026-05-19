<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'resident'){
    header("Location: login.php"); 
    exit();
}
?>
<?php include("includes/header_resident.php"); ?>

<div class="container-fluid">
  <div class="row">
    <!-- Left Section: Resident Dashboard Features -->
    <aside class="col-md-3 col-lg-2 bg-light vh-100 sidebar">
      <div class="p-3">
        <h4>Resident Dashboard</h4>
        <ul class="nav flex-column">
          <li class="nav-item"><a class="nav-link" href="resident_schedules.php">Collection Schedules</a></li>
          <li class="nav-item"><a class="nav-link" href="resident_reports.php">Submit Waste Report</a></li>
          <li class="nav-item"><a class="nav-link" href="resident_notifications.php">Notifications</a></li>
          <li class="nav-item"><a class="nav-link" href="resident_education.php">Educational Content</a></li>
          <li class="nav-item"><a class="nav-link" href="resident_print.php">Print My Reports</a></li>
        </ul>
      </div>
    </aside>

    <!-- Right Section: Resident Data Display -->
    <main class="col-md-9 col-lg-10 px-4">
      <div class="pt-3 pb-2 mb-3 border-bottom">
        <h2>
          Welcome, Resident 
          <?php 
            if(isset($_SESSION['username'])) { 
              echo htmlspecialchars($_SESSION['username']); 
            } else { 
              echo "User"; 
            } 
          ?>!
        </h2>
        <p>Here are your available features:</p>
      </div>

      <!-- Feature Cards -->
      <div class="row">
        <div class="col-md-6 col-lg-4 mb-4">
          <div class="card shadow">
            <div class="card-body">
              <h5 class="card-title">Collection Schedules</h5>
              <p class="card-text">View upcoming garbage collection schedules in your area.</p>
              <a href="resident_schedules.php" class="btn btn-primary">View Schedules</a>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
          <div class="card shadow">
            <div class="card-body">
              <h5 class="card-title">Waste Reports</h5>
              <p class="card-text">Submit a report about waste collection issues in your purok.</p>
              <a href="resident_reports.php" class="btn btn-success">Submit Report</a>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
          <div class="card shadow">
            <div class="card-body">
              <h5 class="card-title">Notifications</h5>
              <p class="card-text">Stay updated with announcements and alerts from the barangay.</p>
              <a href="resident_notifications.php" class="btn btn-warning">View Notifications</a>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
          <div class="card shadow">
            <div class="card-body">
              <h5 class="card-title">Educational Content</h5>
              <p class="card-text">Read articles and guides about proper waste management.</p>
              <a href="resident_education.php" class="btn btn-info">Read Content</a>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
          <div class="card shadow">
            <div class="card-body">
              <h5 class="card-title">Print Reports</h5>
              <p class="card-text">Generate and print your submitted waste reports.</p>
              <a href="resident_print.php" class="btn btn-secondary">Print Reports</a>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

<?php include("includes/footer.php"); ?>
