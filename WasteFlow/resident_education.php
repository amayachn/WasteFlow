<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'resident'){
    header("Location: login.php");
    exit();
}
include("includes/db.php");
include("includes/header_resident.php");

// Safely get resident's purok from session
$purok = isset($_SESSION['purok']) ? $_SESSION['purok'] : '';
?>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <aside class="col-md-3 col-lg-2 bg-light vh-100 sidebar">
      <div class="p-3">
        <h4>Resident Dashboard</h4>
        <ul class="nav flex-column">
          <li class="nav-item"><a class="nav-link" href="dashboard_resident.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="resident_schedules.php">Collection Schedules</a></li>
          <li class="nav-item"><a class="nav-link" href="resident_reports.php">Submit Waste Report</a></li>
          <li class="nav-item"><a class="nav-link" href="resident_notifications.php">Notifications</a></li>
          <li class="nav-item"><a class="nav-link active" href="resident_education.php">Educational Content</a></li>
          <li class="nav-item"><a class="nav-link" href="resident_print.php">Print My Reports</a></li>
          <li class="nav-item"><a class="nav-link text-danger" href="dashboard_resident.php">Exit</a></li>
        </ul>
      </div>
    </aside>

    <!-- Main -->
    <main class="col-md-9 col-lg-10 px-4">
      <h2 class="text-success mb-3 mt-3">Educational Content</h2>
      <?php
      // Fetch educational content tagged for this resident's purok OR for "All"
      $stmt = $conn->prepare("SELECT * FROM education WHERE tag = ? OR tag = 'All' ORDER BY created_at DESC");
      $stmt->bind_param("s", $purok);
      $stmt->execute();
      $result = $stmt->get_result();

      if($result && $result->num_rows > 0){
          echo '<div class="list-group">';
          while($row = $result->fetch_assoc()){
              echo "<div class='list-group-item'>
                      <h5 class='mb-1 text-success'>{$row['title']}</h5>
                      <p class='mb-1'>{$row['content']}</p>
                      <small class='text-muted'>".date("F d, Y h:i A", strtotime($row['created_at']))."</small>
                    </div>";
          }
          echo '</div>';
      } else {
          echo '<div class="alert alert-info">No educational content available for your Purok.</div>';
      }
      ?>
    </main>
  </div>
</div>
<?php include("includes/footer.php"); ?>
