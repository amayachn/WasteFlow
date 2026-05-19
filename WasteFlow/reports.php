<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}
include("includes/db.php");
include("includes/header_admin.php");

$message = "";
$messageType = ""; // success or error

// Handle admin response to resident reports
if(isset($_POST['respond'])){
    $report_id = $_POST['report_id'];
    $admin_id = $_SESSION['user_id'];
    $response = $_POST['response'];
    $remarks = $_POST['remarks'];

    $stmt = $conn->prepare("UPDATE reports SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $response, $report_id);
    if($stmt->execute()){
        $stmt2 = $conn->prepare("INSERT INTO report_responses (report_id, admin_id, response, remarks) VALUES (?, ?, ?, ?)");
        $stmt2->bind_param("iiss", $report_id, $admin_id, $response, $remarks);
        $stmt2->execute();
        $message = "Response recorded successfully!";
        $messageType = "success";
    } else {
        $message = "Failed to record response.";
        $messageType = "error";
    }
}

// Handle admin-created report
if(isset($_POST['add'])){
    $status = $_POST['status'];
    $desc = $_POST['description'];
    $tag = $_POST['tag'];
    $purok = $tag;

    $stmt = $conn->prepare("INSERT INTO reports (resident_id, purok, description, status, tag) VALUES (NULL, ?, ?, ?, ?)");
    $stmt->bind_param("ssss", $purok, $desc, $status, $tag);

    if($stmt->execute()){
        $message = "Admin report added successfully!";
        $messageType = "success";
    } else {
        $message = "Failed to add admin report.";
        $messageType = "error";
    }
}

// Fetch reports
$adminReports = $conn->query("SELECT * FROM reports WHERE resident_id IS NULL ORDER BY id DESC");
$residentReports = $conn->query("SELECT r.*, u.name AS resident_name FROM reports r JOIN users u ON r.resident_id = u.id ORDER BY r.created_at DESC");
?>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <aside class="col-md-3 col-lg-2 bg-light vh-100 sidebar">
      <div class="p-3">
        <h4>Admin Dashboard</h4>
        <ul class="nav flex-column">
          <li class="nav-item"><a class="nav-link" href="dashboard_admin.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link active" href="reports.php">Waste Reports</a></li>
          <li class="nav-item"><a class="nav-link" href="schedules.php">Schedules</a></li>
          <li class="nav-item"><a class="nav-link" href="notifications.php">Notifications</a></li>
          <li class="nav-item"><a class="nav-link" href="education.php">Educational Content</a></li>
          <li class="nav-item"><a class="nav-link" href="print.php">Print Reports</a></li>
          <li class="nav-item"><a class="nav-link text-danger" href="dashboard_admin.php">Exit</a></li>
        </ul>
      </div>
    </aside>

    <!-- Main -->
    <main class="col-md-9 col-lg-10 px-4">
      <h2 class="text-success mb-3 mt-3">Waste Reports</h2>

      <!-- Modal Notification -->
<?php if(!empty($message)): ?>
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header <?php echo ($messageType=='success') ? 'bg-success' : 'bg-danger'; ?> text-white">
        <h5 class="modal-title" id="messageModalLabel">
          <?php echo ($messageType=='success') ? 'Success' : 'Error'; ?>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <?php echo htmlspecialchars($message); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
  document.addEventListener("DOMContentLoaded", function(){
    var msgModal = new bootstrap.Modal(document.getElementById('messageModal'));
    msgModal.show();
  });
</script>
<?php endif; ?>

<div class="row">
  <!-- Add Admin Report -->
<form method="POST" class="d-flex flex-wrap gap-2 mb-3">
  <!-- Status Dropdown -->
  <select name="status" class="form-select" required>
    <option value="">Select Status</option>
    <option value="Scheduled">Scheduled</option>
    <option value="Ongoing">Ongoing</option>
    <option value="Completed">Completed</option>
    <option value="Delayed">Delayed</option>
    <option value="Cancelled">Cancelled</option>
    <option value="Issue Reported">Issue Reported</option>
    <option value="Resolved">Resolved</option>
  </select>

  <!-- Description Dropdown -->
  <select name="description" class="form-select" required>
    <option value="">Select Description</option>
    <option value="Garbage Collection">Garbage Collection</option>
    <option value="Street Cleaning">Street Cleaning</option>
    <option value="Segregation Monitoring">Segregation Monitoring</option>
    <option value="Overflowing Bins">Overflowing Bins</option>
    <option value="Missed Pickup">Missed Pickup</option>
    <option value="Community Cleanup">Community Cleanup</option>
    <option value="Hazardous Waste">Hazardous Waste</option>
  </select>

  <!-- Purok Dropdown -->
  <select name="tag" class="form-select" required>
    <option value="">Select Tag (Purok)</option>
    <option value="1">Purok 1</option>
    <option value="1A">Purok 1A</option>
    <option value="1B">Purok 1B</option>
    <option value="1C">Purok 1C</option>
    <option value="1D">Purok 1D</option>
    <option value="1E">Purok 1E</option>
    <option value="2">Purok 2</option>
    <option value="2A">Purok 2A</option>
    <option value="2B">Purok 2B</option>
    <option value="3">Purok 3</option>
    <option value="3A">Purok 3A</option>
    <option value="3B">Purok 3B</option>
    <option value="3C">Purok 3C</option>
    <option value="4">Purok 4</option>
    <option value="5">Purok 5</option>
    <option value="6">Purok 6</option>
    <option value="7">Purok 7</option>
    <option value="8">Purok 8</option>
    <option value="All">All</option>
  </select>

  <button type="submit" name="add" class="btn btn-success">Add</button>
</form>


      <!-- Admin Reports Box -->
<div class="col-md-6">
  <div class="card mb-4">
    <div class="card-header bg-dark text-white">Admin Waste Reports</div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Purok</th>
              <th>Description</th>
              <th>Status</th>
              <th>Tag</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if($adminReports && $adminReports->num_rows > 0){
                while($row = $adminReports->fetch_assoc()){
                    echo "<tr>
                      <td>".htmlspecialchars($row['id'])."</td>
                      <td>".htmlspecialchars($row['purok'])."</td>
                      <td>".htmlspecialchars($row['description'])."</td>
                      <td>".(!empty($row['status']) ? htmlspecialchars($row['status']) : "<span class='text-muted'>No status</span>")."</td>
                      <td>".htmlspecialchars($row['tag'])."</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center text-muted'>No admin reports found.</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Resident Reports Box -->
<div class="col-md-6">
  <div class="card mb-4">
    <div class="card-header bg-success text-white">Waste Reports Submitted by Residents</div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Resident</th>
              <th>Purok</th>
              <th>Description</th>
              <th>Status</th>
              <th>Respond</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if($residentReports && $residentReports->num_rows > 0){
                while($row = $residentReports->fetch_assoc()){
                    echo "<tr>
                      <td>{$row['id']}</td>
                      <td>{$row['resident_name']}</td>
                      <td>{$row['purok']}</td>
                      <td>{$row['description']}</td>
                      <td>{$row['status']}</td>
                      <td>
                        <form method='POST' class='d-flex flex-column gap-2'>
                          <input type='hidden' name='report_id' value='{$row['id']}'>
                          <select name='response' class='form-select form-select-sm'>
                            <option value='Approved'>Approve</option>
                            <option value='Denied'>Deny</option>
                          </select>
                          <textarea name='remarks' class='form-control form-control-sm' placeholder='Remarks'></textarea>
                          <button type='submit' name='respond' class='btn btn-success btn-sm'>Submit</button>
                        </form>
                      </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='text-center text-muted'>No resident reports found.</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


<?php include("includes/footer.php"); ?>
