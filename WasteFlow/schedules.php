<?php include("includes/db.php");include("includes/header_admin.php"); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Schedules</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <aside class="col-md-3 col-lg-2 bg-light vh-100 sidebar">
      <div class="p-3">
        <h4>Admin Dashboard</h4>
        <ul class="nav flex-column">
          <li class="nav-item"><a class="nav-link" href="dashboard_admin.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="reports.php">Waste Reports</a></li>
          <li class="nav-item"><a class="nav-link active" href="schedules.php">Schedules</a></li>
          <li class="nav-item"><a class="nav-link" href="notifications.php">Notifications</a></li>
          <li class="nav-item"><a class="nav-link" href="education.php">Educational Content</a></li>
          <li class="nav-item"><a class="nav-link" href="print.php">Print Reports</a></li>
          <li class="nav-item"><a class="nav-link text-danger" href="dashboard_admin.php">Exit</a></li>
        </ul>
      </div>
    </aside>

    <!-- Main -->
    <main class="col-md-9 col-lg-10 px-4">
      <div class="pt-3 pb-2 mb-3 border-bottom">
        <h2>Garbage Collection Schedules</h2>
      </div>

      <!-- Add & Search -->
      <div class="row mb-4">
        <!-- Add Box -->
        <div class="col-md-6 mb-3">
          <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
              <h5 class="mb-0">Add Schedule</h5>
            </div>
            <div class="card-body">
              <form method="POST" class="d-flex flex-column gap-2">
                <input type="date" name="collection_date" class="form-control" required>
                <select name="status" class="form-select" required>
                  <option value="">Select Status</option>
                  <option value="Pending">Pending</option>
                  <option value="On-going">On-going</option>
                  <option value="Completed">Completed</option>
                  <option value="Cancelled">Cancelled</option>
                </select>
                <select name="purok" class="form-select" required>
                  <option value="">Select Purok</option>
                  <option>1</option><option>1A</option><option>1B</option><option>1C</option><option>1D</option><option>1E</option>
                  <option>2</option><option>2A</option><option>2B</option>
                  <option>3</option><option>3A</option><option>3B</option><option>3C</option>
                  <option>4</option><option>5</option><option>6</option><option>7</option><option>8</option>
                  <option>ALL</option>
                </select>
                <select name="activity" class="form-select" required>
                  <option value="">Select Activity</option>
                  <option value="Collection of Garbage">Collection of Garbage</option>
                </select>
                <button type="submit" name="add" class="btn btn-success">Add</button>
              </form>
            </div>
          </div>
        </div>

        <!-- Search Box -->
        <div class="col-md-6 mb-3">
          <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0">Search Schedule</h5>
            </div>
            <div class="card-body">
              <form method="GET" action="schedules.php" class="d-flex gap-2">
                <select name="search" class="form-select">
                  <option value="">Select Purok</option>
                  <option>1</option><option>1A</option><option>1B</option><option>1C</option><option>1D</option><option>1E</option>
                  <option>2</option><option>2A</option><option>2B</option>
                  <option>3</option><option>3A</option><option>3B</option><option>3C</option>
                  <option>4</option><option>5</option><option>6</option><option>7</option><option>8</option>
                  <option>ALL</option>
                </select>
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="schedules.php?view=all" class="btn btn-info">View All</a>
              </form>
            </div>
          </div>
        </div>
      </div>

      <?php
      // Create
      if(isset($_POST['add'])){
        $date = $_POST['collection_date'];
        $status = $_POST['status'];
        $purok = $_POST['purok'];
        $activity = $_POST['activity'];
        $conn->query("INSERT INTO schedules (collection_date, status, purok, activity) VALUES ('$date','$status','$purok','$activity')");
        echo "<script>
          var myModal = new bootstrap.Modal(document.getElementById('notifModal'));
          myModal.show();
        </script>";
      }

      // Delete
      if(isset($_GET['delete'])){
        $id = $_GET['delete'];
        $conn->query("DELETE FROM schedules WHERE id=$id");
      }

      // Update
      if(isset($_POST['update'])){
        $id = $_POST['id'];
        $date = $_POST['collection_date'];
        $status = $_POST['status'];
        $purok = $_POST['purok'];
        $activity = $_POST['activity'];
        $conn->query("UPDATE schedules SET collection_date='$date', status='$status', purok='$purok', activity='$activity' WHERE id=$id");
      }

      // Quick Status Update
      if(isset($_POST['update_status'])){
        $id = $_POST['id'];
        $status = $_POST['status'];
        $conn->query("UPDATE schedules SET status='$status' WHERE id=$id");
      }

      // Search or View
      $query = "SELECT * FROM schedules";
      if(isset($_GET['search']) && $_GET['search'] != ""){
        $search = $_GET['search'];
        if($search == "ALL"){
          $query = "SELECT * FROM schedules";
        } else {
          $query = "SELECT * FROM schedules WHERE purok='$search'";
        }
      }
      $result = $conn->query($query);
      ?>

      <!-- Data Table -->
      <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
          <h5 class="mb-0">Schedules List</h5>
        </div>
        <div class="card-body table-responsive">
          <table class="table table-bordered table-hover">
            <thead class="table-dark">
              <tr><th>ID</th><th>Date</th><th>Status</th><th>Purok</th><th>Activity</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php
            while($row = $result->fetch_assoc()){
              echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['collection_date']}</td>
                <td>{$row['status']}</td>
                <td>{$row['purok']}</td>
                <td>{$row['activity']}</td>
                <td>
                  <a href='schedules.php?delete={$row['id']}' class='btn btn-danger btn-sm'>Delete</a>
                  <button class='btn btn-primary btn-sm' data-bs-toggle='collapse' data-bs-target='#update{$row['id']}'>Update</button>
                  <div id='update{$row['id']}' class='collapse mt-2'>
                    <form method='POST' class='d-flex gap-2'>
                      <input type='hidden' name='id' value='{$row['id']}'>
                      <select name='status' class='form-select form-select-sm'>
                        <option value='Pending' ".($row['status']=='Pending'?'selected':'').">Pending</option>
                        <option value='On-going' ".($row['status']=='On-going'?'selected':'').">On-going</option>
                        <option value='Completed' ".($row['status']=='Completed'?'selected':'').">Completed</option>
                        <option value='Cancelled' ".($row['status']=='Cancelled'?'selected':'').">Cancelled</option>
                      </select>
                                       <button type='submit' name='update_status' class='btn btn-success btn-sm'>Save</button>
                    </form>
                  </div>

                  <!-- Edit Modal for Date, Purok & Activity -->
                  <button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editModal{$row['id']}'>Edit</button>
                  <div class='modal fade' id='editModal{$row['id']}' tabindex='-1'>
                    <div class='modal-dialog'>
                      <div class='modal-content'>
                        <div class='modal-header'>
                          <h5 class='modal-title'>Edit Schedule</h5>
                          <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                        </div>
                        <div class='modal-body'>
                          <form method='POST'>
                            <input type='hidden' name='id' value='{$row['id']}'>
                            <input type='date' name='collection_date' value='{$row['collection_date']}' class='form-control mb-2'>
                            <select name='status' class='form-select mb-2'>
                              <option value='Pending' ".($row['status']=='Pending'?'selected':'').">Pending</option>
                              <option value='On-going' ".($row['status']=='On-going'?'selected':'').">On-going</option>
                              <option value='Completed' ".($row['status']=='Completed'?'selected':'').">Completed</option>
                              <option value='Cancelled' ".($row['status']=='Cancelled'?'selected':'').">Cancelled</option>
                            </select>
                            <select name='purok' class='form-select mb-2'>
                              <option ".($row['purok']=='1'?'selected':'').">1</option>
                              <option ".($row['purok']=='1A'?'selected':'').">1A</option>
                              <option ".($row['purok']=='1B'?'selected':'').">1B</option>
                              <option ".($row['purok']=='1C'?'selected':'').">1C</option>
                              <option ".($row['purok']=='1D'?'selected':'').">1D</option>
                              <option ".($row['purok']=='1E'?'selected':'').">1E</option>
                              <option ".($row['purok']=='2'?'selected':'').">2</option>
                              <option ".($row['purok']=='2A'?'selected':'').">2A</option>
                              <option ".($row['purok']=='2B'?'selected':'').">2B</option>
                              <option ".($row['purok']=='3'?'selected':'').">3</option>
                              <option ".($row['purok']=='3A'?'selected':'').">3A</option>
                              <option ".($row['purok']=='3B'?'selected':'').">3B</option>
                              <option ".($row['purok']=='3C'?'selected':'').">3C</option>
                              <option ".($row['purok']=='4'?'selected':'').">4</option>
                              <option ".($row['purok']=='5'?'selected':'').">5</option>
                              <option ".($row['purok']=='6'?'selected':'').">6</option>
                              <option ".($row['purok']=='7'?'selected':'').">7</option>
                              <option ".($row['purok']=='8'?'selected':'').">8</option>
                              <option ".($row['purok']=='ALL'?'selected':'').">ALL</option>
                            </select>
                            <select name='activity' class='form-select mb-2'>
                              <option value='Collection of Garbage' ".($row['activity']=='Collection of Garbage'?'selected':'').">Collection of Garbage</option>
                            </select>
                            <button type='submit' name='update' class='btn btn-primary btn-sm'>Save</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>";
            }
            ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- Notification Modal -->
<div class="modal fade" id="notifModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Notification</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Schedule successfully added!</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
