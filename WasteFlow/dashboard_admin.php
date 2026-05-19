<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php"); 
    exit();
}
?>
<?php include("includes/header_admin.php"); ?>

<div class="container-fluid">
  <div class="row">
    <!-- Left Section: Dashboard Features -->
    <aside class="col-md-3 col-lg-2 bg-light vh-100 sidebar">
      <div class="p-3">
        <h4>Admin Dashboard</h4>
        <ul class="nav flex-column">
          <li class="nav-item"><a class="nav-link" href="schedules.php">Manage Schedules</a></li>
          <li class="nav-item"><a class="nav-link" href="reports.php">View & Manage Reports</a></li>
          <li class="nav-item"><a class="nav-link" href="notifications.php">Post Notifications</a></li>
          <li class="nav-item"><a class="nav-link" href="education.php">Update Educational Content</a></li>
        </ul>
      </div>
    </aside>

    <!-- Right Section: Data Display -->
    <main class="col-md-9 col-lg-10 px-4">
      <div class="pt-3 pb-2 mb-3 border-bottom">
        <h2>
          Welcome, Admin 
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
              <h5 class="card-title">Manage Schedules</h5>
              <p class="card-text">Create, update, and monitor garbage collection schedules.</p>
              <a href="schedules.php" class="btn btn-primary">Go to Schedules</a>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
          <div class="card shadow">
            <div class="card-body">
              <h5 class="card-title">Reports</h5>
              <p class="card-text">View and manage resident waste reports.</p>
              <a href="reports.php" class="btn btn-success">Go to Reports</a>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
          <div class="card shadow">
            <div class="card-body">
              <h5 class="card-title">Notifications</h5>
              <p class="card-text">Post announcements and alerts for residents.</p>
              <a href="notifications.php" class="btn btn-warning">Go to Notifications</a>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
          <div class="card shadow">
            <div class="card-body">
              <h5 class="card-title">Educational Content</h5>
              <p class="card-text">Update guides and articles about waste management.</p>
              <a href="education.php" class="btn btn-info">Go to Education</a>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

<?php include("includes/footer.php"); ?>
