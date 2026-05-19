<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'resident'){
    header("Location: login.php");
    exit();
}
include("includes/db.php");
include("includes/header_resident.php");

// Get logged-in resident's purok from session
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
          <li class="nav-item"><a class="nav-link" href="resident_education.php">Educational Content</a></li>
          <li class="nav-item"><a class="nav-link" href="resident_print.php">Print My Reports</a></li>
          <li class="nav-item"><a class="nav-link text-danger" href="dashboard_resident.php">Exit</a></li>
        </ul>
      </div>
    </aside>

    <!-- Main -->
    <main class="col-md-9 col-lg-10 px-4">
      <h2 class="text-success mb-3 mt-3">My Collection Schedules</h2>
      <?php
      // Query schedules for this resident's purok or 'All'
      $stmt = $conn->prepare("SELECT id, purok, activity, collection_date, status 
                              FROM schedules 
                              WHERE purok = ? OR purok = 'All'
                              ORDER BY collection_date ASC");
      $stmt->bind_param("s", $purok);
      $stmt->execute();
      $result = $stmt->get_result();

      if($result && $result->num_rows > 0){
          echo '<div class="table-responsive">';
          echo '<table class="table table-bordered table-striped">';
          echo '<thead class="table-dark">
                  <tr>
                    <th>ID</th>
                    <th>Purok</th>
                    <th>Activity</th>
                    <th>Date</th>
                    <th>Status</th>
                  </tr>
                </thead><tbody>';
          while($row = $result->fetch_assoc()){
              echo "<tr>
                      <td>{$row['id']}</td>
                      <td>{$row['purok']}</td>
                      <td>{$row['activity']}</td>
                      <td>".date("F d, Y", strtotime($row['collection_date']))."</td>
                      <td>{$row['status']}</td>
                    </tr>";
          }
          echo '</tbody></table></div>';
      } else {
          echo '<div class="alert alert-info">No collection schedules available for you at the moment.</div>';
      }
      ?>
    </main>
  </div>
</div>
<?php include("includes/footer.php"); ?>
