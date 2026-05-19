<?php include("includes/db.php"); include("includes/header_admin.php");?>
<!DOCTYPE html>
<html>
<head>
  <title>Educational Content</title>
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
          <li class="nav-item"><a class="nav-link" href="notifications.php">Notifications</a></li>
          <li class="nav-item"><a class="nav-link active" href="education.php">Educational Content</a></li>
          <li class="nav-item"><a class="nav-link" href="print.php">Print Reports</a></li>
          <li class="nav-item"><a class="nav-link text-danger" href="dashboard_admin.php">Exit</a></li>
        </ul>
      </div>
    </aside>

    <!-- Main -->
    <main class="col-md-9 col-lg-10 px-4">
      <div class="pt-3 pb-2 mb-3 border-bottom">
        <h2>Educational Content</h2>
      </div>

      <!-- Action Buttons -->
      <div class="mb-4 d-flex gap-2">
        <button class="btn btn-success" data-bs-toggle="collapse" data-bs-target="#addForm">Add Content</button>
        <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#searchForm">Search Content</button>
        <button class="btn btn-info" data-bs-toggle="collapse" data-bs-target="#viewTable">View All</button>
      </div>

      <?php
      $message = "";

      // Create
      if(isset($_POST['add'])){
        $title = $_POST['title'];
        $content = $_POST['content'];
        $tag = $_POST['tag'];
        if($conn->query("INSERT INTO education (title, content, tag) VALUES ('$title','$content','$tag')")){
          $message = "<div class='alert alert-success'>Successfully added educational content!</div>";
        } else {
          $message = "<div class='alert alert-danger'>Failed to add content.</div>";
        }
      }

      // Update
      if(isset($_POST['update'])){
        $id = $_POST['id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $tag = $_POST['tag'];
        if($conn->query("UPDATE education SET title='$title', content='$content', tag='$tag' WHERE id=$id")){
          $message = "<div class='alert alert-info'>Content updated successfully!</div>";
        } else {
          $message = "<div class='alert alert-danger'>Failed to update content.</div>";
        }
      }

      // Delete
      if(isset($_GET['delete'])){
        $id = $_GET['delete'];
        if($conn->query("DELETE FROM education WHERE id=$id")){
          $message = "<div class='alert alert-warning'>Content deleted successfully!</div>";
        } else {
          $message = "<div class='alert alert-danger'>Failed to delete content.</div>";
        }
      }

      // Search or View
      $query = "SELECT * FROM education";
      if(isset($_GET['search']) && $_GET['search'] != ""){
        $search = $_GET['search'];
        $query = "SELECT * FROM education WHERE title LIKE '%$search%' OR tag LIKE '%$search%'";
      }
      $result = $conn->query($query);
      ?>

      <!-- Response Message -->
      <?php if(!empty($message)) echo $message; ?>

      <!-- Add Form -->
      <div id="addForm" class="collapse mb-4">
        <form method="POST" class="d-flex gap-2 flex-wrap">
          <input type="text" name="title" placeholder="Title" class="form-control" required>
          <input type="text" name="content" placeholder="Content" class="form-control" required>
          <select name="tag" class="form-select" required>
            <option value="">Select Tag (Purok)</option>
            <option value="1">1</option><option value="1A">1A</option><option value="1B">1B</option>
            <option value="1C">1C</option><option value="1D">1D</option><option value="1E">1E</option>
            <option value="2">2</option><option value="2A">2A</option><option value="2B">2B</option>
            <option value="3">3</option><option value="3A">3A</option><option value="3B">3B</option><option value="3C">3C</option>
            <option value="4">4</option><option value="5">5</option><option value="6">6</option>
            <option value="7">7</option><option value="8">8</option>
            <option value="ALL">ALL</option>
          </select>
          <button type="submit" name="add" class="btn btn-success">Save</button>
        </form>
      </div>

      <!-- Search Form -->
      <div id="searchForm" class="collapse mb-4">
        <form method="GET" action="education.php" class="d-flex gap-2">
          <input type="text" name="search" class="form-control" placeholder="Search Title or Tag">
          <button type="submit" class="btn btn-primary">Search</button>
        </form>
      </div>

      <!-- View Table -->
      <div id="viewTable" class="collapse">
        <?php
        if($result && $result->num_rows > 0){
        ?>
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
                  <button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editModal{$row['id']}'>Edit</button>
                  <a href='education.php?delete={$row['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this content?');\">Delete</a>
                </td>
              </tr>

              <!-- Edit Modal -->
              <div class='modal fade' id='editModal{$row['id']}' tabindex='-1'>
                <div class='modal-dialog'>
                  <div class='modal-content'>
                    <div class='modal-header'>
                      <h5 class='modal-title'>Edit Content</h5>
                      <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                    </div>
                    <form method='POST'>
                      <div class='modal-body'>
                        <input type='hidden' name='id' value='{$row['id']}'>
                        <div class='mb-3'>
                          <label class='form-label'>Title</label>
                          <input type='text' name='title' value='{$row['title']}' class='form-control' required>
                        </div>
                        <div class='mb-3'>
                          <label class='form-label'>Content</label>
                          <input type='text' name='content' value='{$row['content']}' class='form-control' required>
                        </div>
                        <div class='mb-3'>
                          <label class='form-label'>Tag</label>
                          <select name='tag' class='form-select' required>";
                          $tags = ["1","1A","1B","1C","1D","1E",
         "2","2A","2B",
         "3","3A","3B","3C",
         "4","5","6","7","8",
         "ALL"];
foreach($tags as $tag){
  $selected = ($row['tag']==$tag) ? "selected" : "";
  echo "<option value='$tag' $selected>$tag</option>";
}
echo "</select>
</div>
<div class='modal-footer'>
  <button type='submit' name='update' class='btn btn-primary'>Save Changes</button>
  <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
</div>
</form>
</div>
</div>
</div>";
 }
            ?>
            </tbody>
          </table>
        </div>
        <?php
        } else {
          echo "<div class='alert alert-warning'>No educational content found.</div>";
        }
        ?>
      </div>
    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
