<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'resident'){
    header("Location: login.php");
    exit();
}
include("includes/db.php");
include("includes/header_resident.php");

$message = "";

// Handle submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resident_id = $_SESSION['user_id'];
    $purok = $_SESSION['purok'];
    $details = $_POST['details'];

    $stmt = $conn->prepare("INSERT INTO reports (resident_id, purok, description, status, tag) VALUES (?, ?, ?, 'Pending', ?)");
    $stmt->bind_param("isss", $resident_id, $purok, $details, $purok);

    if($stmt->execute()){
        $message = "<div class='alert alert-success text-center'>Report submitted successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger text-center'>Submission denied. Please try again.</div>";
    }
}

// Fetch resident’s reports with admin responses (patterned like education.php)
$resident_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT r.*, rr.response, rr.remarks, rr.responded_at, u.name AS admin_name
                        FROM reports r
                        LEFT JOIN report_responses rr ON r.id = rr.report_id
                        LEFT JOIN users u ON rr.admin_id = u.id
                        WHERE r.resident_id = ?
                        ORDER BY r.created_at DESC");
$stmt->bind_param("i", $resident_id);
$stmt->execute();
$responses = $stmt->get_result();
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
          <li class="nav-item"><a class="nav-link active" href="resident_reports.php">Submit Waste Report</a></li>
          <li class="nav-item"><a class="nav-link" href="resident_notifications.php">Notifications</a></li>
          <li class="nav-item"><a class="nav-link" href="resident_education.php">Educational Content</a></li>
          <li class="nav-item"><a class="nav-link" href="resident_print.php">Print My Reports</a></li>
          <li class="nav-item"><a class="nav-link text-danger" href="dashboard_resident.php">Exit</a></li>
        </ul>
      </div>
    </aside>

    <!-- Main -->
    <main class="col-md-9 col-lg-10 px-4">
      <div class="row">
        <!-- Box 1: Submit Report -->
        <div class="col-md-6">
          <div class="card mb-4">
            <div class="card-header bg-success text-white">Submit Waste Report</div>
            <div class="card-body">
              <?php if(!empty($message)) echo $message; ?>
              <form method="POST" action="resident_reports.php" class="p-3 border rounded bg-light">
                <div class="mb-3">
                  <label class="form-label">Purok</label>
                  <input type="text" class="form-control" value="<?php echo $_SESSION['purok']; ?>" readonly>
                </div>
                <div class="mb-3">
                  <label class="form-label">Report Details</label>
                  <textarea name="details" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-success">Submit Report</button>
              </form>
            </div>
          </div>
        </div>

        <!-- Box 2: Admin Responses -->
        <div class="col-md-6">
          <div class="card mb-4">
            <div class="card-header bg-primary text-white">Admin Responses</div>
            <div class="card-body">
              <?php
              if($responses && $responses->num_rows > 0){
                  echo '<div class="list-group">';
                  while($row = $responses->fetch_assoc()){
                      echo "<div class='list-group-item' style='background-color:#e6ffe6;'>
                              <h6 class='mb-1 text-success'>Report #{$row['id']}</h6>
                              <p class='mb-1'><strong>Description:</strong> {$row['description']}</p>
                              <p class='mb-1'><strong>Response:</strong> ".($row['response'] ?? 'Pending')."</p>
                              <p class='mb-1'><strong>Remarks:</strong> ".($row['remarks'] ?? '-')."</p>
                              <small class='text-muted'>Admin: ".($row['admin_name'] ?? '-')." | ".
                              ($row['responded_at'] ? date("F d, Y h:i A", strtotime($row['responded_at'])) : 'Awaiting response')."</small>
                            </div>";
                  }
                  echo '</div>';
              } else {
                  echo '<div class="alert alert-info">No responses from admin yet.</div>';
              }
              ?>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>
<?php include("includes/footer.php"); ?>
