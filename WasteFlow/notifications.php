<?php 
include("includes/db.php"); 
include("includes/header_admin.php");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Notifications</title>
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
          <li class="nav-item"><a class="nav-link" href="schedules.php">Schedules</a></li>
          <li class="nav-item"><a class="nav-link active" href="notifications.php">Notifications</a></li>
          <li class="nav-item"><a class="nav-link" href="education.php">Educational Content</a></li>
          <li class="nav-item"><a class="nav-link" href="print.php">Print Reports</a></li>
          <li class="nav-item"><a class="nav-link text-danger" href="dashboard_admin.php">Exit</a></li>
        </ul>
      </div>
    </aside>

    <!-- Main -->
    <main class="col-md-9 col-lg-10 px-4">
      <div class="pt-3 pb-2 mb-3 border-bottom">
        <h2>Notifications</h2>
      </div>

      <!-- Action Buttons -->
      <div class="mb-4 d-flex gap-2">
        <button class="btn btn-success" data-bs-toggle="collapse" data-bs-target="#addForm">Add Notification</button>
        <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#searchForm">Search Notification</button>
        <button class="btn btn-info" data-bs-toggle="collapse" data-bs-target="#viewTable">View All</button>
      </div>

      <!-- Add Form -->
      <div id="addForm" class="collapse mb-4">
        <form method="POST" class="d-flex gap-2 flex-wrap">
          <input type="text" name="title" placeholder="Title" class="form-control" required>
          <input type="text" name="content" placeholder="Content" class="form-control" required>
          <select name="tag" class="form-select" required>
            <option value="">Select Purok</option>
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
          <button type="submit" name="add" class="btn btn-success">Save</button>
        </form>
      </div>

      <!-- Search Form -->
      <div id="searchForm" class="collapse mb-4">
        <form method="GET" action="notifications.php" class="d-flex gap-2">
          <input type="text" name="search" class="form-control" placeholder="Search Title">
          <button type="submit" class="btn btn-primary">Search</button>
        </form>
      </div>

      <?php
      // Create
      if(isset($_POST['add'])){
        $title = $conn->real_escape_string($_POST['title']);
        $content = $conn->real_escape_string($_POST['content']);
        $tag = $conn->real_escape_string($_POST['tag']);
        $conn->query("INSERT INTO notifications (title, content, tag) VALUES ('$title','$content','$tag')");
      }

      // Delete
      if(isset($_GET['delete'])){
        $id = intval($_GET['delete']);
        $conn->query("DELETE FROM notifications WHERE id=$id");
      }

      // Edit
      if(isset($_POST['edit'])){
        $id = intval($_POST['id']);
        $title = $conn->real_escape_string($_POST['title']);
        $content = $conn->real_escape_string($_POST['content']);
        $tag = $conn->real_escape_string($_POST['tag']);
        $conn->query("UPDATE notifications SET title='$title', content='$content', tag='$tag' WHERE id=$id");
      }

      // Search or View
      $query = "SELECT * FROM notifications";
      if(isset($_GET['search']) && $_GET['search'] != ""){
        $search = $conn->real_escape_string($_GET['search']);
        $query = "SELECT * FROM notifications WHERE title LIKE '%$search%'";
      }
      $result = $conn->query($query);
      ?>

      <!-- View Table -->
      <div id="viewTable" class="collapse">
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead class="table-dark">
              <tr><th>ID</th><th>Title</th><th>Content</th><th>Tag</th><th>Created At</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php
            while($row = $result->fetch_assoc()){
              echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['title']}</td>
                <td>{$row['content']}</td>
                <td>{$row['tag']}</td>
                <td>{$row['created_at']}</td>
                <td>
                  <a href='?delete={$row['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Delete this notification?');\">Delete</a>
                  <button class='btn btn-warning btn-sm' data-bs-toggle='collapse' data-bs-target='#editForm{$row['id']}'>Edit</button>
                </td>
              </tr>
              <tr class='collapse' id='editForm{$row['id']}'>
                <td colspan='6'>
                  <form method='POST' class='d-flex gap-2 flex-wrap'>
                    <input type='hidden' name='id' value='{$row['id']}'>
                    <input type='text' name='title' value='{$row['title']}' class='form-control' required>
                    <input type='text' name='content' value='{$row['content']}' class='form-control' required>
                    <select name='tag' class='form-select' required>
                      <option value='{$row['tag']}' selected>{$row['tag']}</option>
                      <option value='1'>Purok 1</option>
                      <option value='1A'>Purok 1A</option>
                      <option value='1B'>Purok 1B</option>
                      <option value='1C'>Purok 1C</option>
                      <option value='1D'>Purok 1D</option>
                      <option value='1E'>Purok 1E</option>
                      <option value='2'>Purok 2</option>
                      <option value='2A'>Purok 2A</option>
                      <option value='2B'>Purok 2B</option>
                      <option value='3'>Purok 3</option>
                      <option value='3A'>Purok 3A</option>
                      <option value='3B'>Purok 3B</option>
                      <option value='3C'>Purok 3C</option>
                      <option value='4'>Purok 4</option>
                      <option value='5'>Purok 5</option>
                      <option value='6'>Purok 6</option>
                      <option value='7'>Purok 7</option>
                      <option value='8'>Purok 8</option>
                      <option value='All'>All</option>
                    </select>
                    <button type='submit' name='edit' class='btn btn-warning'>Update</button>
                  </form>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
