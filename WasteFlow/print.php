<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}
include("includes/db.php");
include("includes/header_admin.php");
?>

<div class="container-fluid">
  <div class="row">
    <aside class="col-md-3 col-lg-2 bg-light vh-100 sidebar">
      <div class="p-3">
        <h4>Admin Dashboard</h4>
        <ul class="nav flex-column">
          <li class="nav-item"><a class="nav-link" href="dashboard_admin.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="schedules.php">Manage Schedules</a></li>
          <li class="nav-item"><a class="nav-link" href="reports.php">Waste Reports</a></li>
          <li class="nav-item"><a class="nav-link" href="notifications.php">Notifications</a></li>
          <li class="nav-item"><a class="nav-link" href="education.php">Educational Content</a></li>
          <li class="nav-item"><a class="nav-link text-danger" href="dashboard_admin.php">Exit</a></li>
        </ul>
      </div>
    </aside>

    <!-- Main -->
    <main class="col-md-9 col-lg-10 px-4">
      <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
        <h2 class="text-success">Reports Overview (Admin)</h2>
        
        <!-- Dropdown menu for printing -->
        <div class="dropdown">
          <button class="btn btn-outline-primary dropdown-toggle" type="button" id="printDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            Print Reports
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="printDropdown">
            <li><a class="dropdown-item" href="#" onclick="printAll(); return false;">Print All Reports</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#" onclick="printSection('notificationsReport'); return false;">Notifications</a></li>
            <li><a class="dropdown-item" href="#" onclick="printSection('wasteReport'); return false;">Waste Reports</a></li>
            <li><a class="dropdown-item" href="#" onclick="printSection('schedulesReport'); return false;">Schedules</a></li>
            <li><a class="dropdown-item" href="#" onclick="printSection('educationReport'); return false;">Education</a></li>
          </ul>
        </div>
      </div>

      <div class="row" id="allReports">
        <!-- Notifications Report -->
        <div class="col-md-6" id="notificationsReport">
          <div class="card mb-4">
            <div class="card-header bg-info text-white">Notifications Report</div>
            <div class="card-body">
              <?php
              $stmt = $conn->prepare("SELECT * FROM notifications ORDER BY created_at DESC");
              $stmt->execute();
              $notifications = $stmt->get_result();

              if($notifications->num_rows > 0){
                  echo "<table class='table table-bordered table-sm'><thead><tr><th>ID</th><th>Title</th><th>Tag</th><th>Date</th></tr></thead><tbody>";
                  while($row = $notifications->fetch_assoc()){
                      echo "<tr><td>{$row['id']}</td><td>{$row['title']}</td><td>{$row['tag']}</td><td>{$row['created_at']}</td></tr>";
                  }
                  echo "</tbody></table>";
              } else {
                  echo "<div class='alert alert-warning'>No notifications found.</div>";
              }
              ?>
            </div>
          </div>
        </div>

        <!-- Waste Reports -->
        <div class="col-md-6" id="wasteReport">
          <div class="card mb-4">
            <div class="card-header bg-danger text-white">Waste Reports</div>
            <div class="card-body">
              <?php
              $stmt = $conn->prepare("SELECT * FROM reports ORDER BY created_at DESC");
              $stmt->execute();
              $wasteReports = $stmt->get_result();

              if($wasteReports->num_rows > 0){
                  echo "<table class='table table-bordered table-sm'><thead><tr><th>ID</th><th>Purok</th><th>Description</th><th>Status</th></tr></thead><tbody>";
                  while($row = $wasteReports->fetch_assoc()){
                      echo "<tr><td>{$row['id']}</td><td>{$row['purok']}</td><td>{$row['description']}</td><td>{$row['status']}</td></tr>";
                  }
                  echo "</tbody></table>";
              } else {
                  echo "<div class='alert alert-warning'>No waste reports found.</div>";
              }
              ?>
            </div>
          </div>
        </div>

        <!-- Schedules -->
        <div class="col-md-6" id="schedulesReport">
          <div class="card mb-4">
            <div class="card-header bg-primary text-white">Schedules Report</div>
            <div class="card-body">
              <?php
              $stmt = $conn->prepare("SELECT * FROM schedules ORDER BY collection_date DESC");
              $stmt->execute();
              $schedules = $stmt->get_result();

              if($schedules->num_rows > 0){
                  echo "<table class='table table-bordered table-sm'><thead><tr><th>ID</th><th>Purok</th><th>Date</th><th>Status</th></tr></thead><tbody>";
                  while($row = $schedules->fetch_assoc()){
                      echo "<tr><td>{$row['id']}</td><td>{$row['purok']}</td><td>{$row['collection_date']}</td><td>{$row['status']}</td></tr>";
                  }
                  echo "</tbody></table>";
              } else {
                  echo "<div class='alert alert-warning'>No schedules found.</div>";
              }
              ?>
            </div>
          </div>
        </div>

        <!-- Education -->
        <div class="col-md-6" id="educationReport">
          <div class="card mb-4">
            <div class="card-header bg-secondary text-white">Education Report</div>
            <div class="card-body">
              <?php
              $stmt = $conn->prepare("SELECT * FROM education ORDER BY created_at DESC");
              $stmt->execute();
              $education = $stmt->get_result();

              if($education->num_rows > 0){
                  echo "<table class='table table-bordered table-sm'><thead><tr><th>ID</th><th>Title</th><th>Tag</th><th>Date</th></tr></thead><tbody>";
                  while($row = $education->fetch_assoc()){
                      echo "<tr><td>{$row['id']}</td><td>{$row['title']}</td><td>{$row['tag']}</td><td>{$row['created_at']}</td></tr>";
                  }
                  echo "</tbody></table>";
              } else {
                  echo "<div class='alert alert-warning'>No educational content found.</div>";
              }
              ?>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- Print script -->
<script>
function printSection(sectionId) {
  var printContents = document.getElementById(sectionId).innerHTML;
  var originalContents = document.body.innerHTML;
  document.body.innerHTML = printContents;
  window.print();
  document.body.innerHTML = originalContents;
}

function printAll() {
  var printContents = document.getElementById('allReports').innerHTML;
  var originalContents = document.body.innerHTML;
  document.body.innerHTML = printContents;
  window.print();
  document.body.innerHTML = originalContents;
}
</script>

<?php include("includes/footer.php"); ?>
