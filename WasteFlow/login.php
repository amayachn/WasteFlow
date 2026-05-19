<?php
include("includes/db.php");

// ✅ Only start session if none is active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contact = trim($_POST['contact']);
    $password = trim($_POST['password']);

    // Password validation: must be exactly 8 characters, only letters and numbers
    if (strlen($password) !== 8 || !ctype_alnum($password)) {
        $error = "Password must be exactly 8 characters long and contain only letters or numbers.";
    } else {
        // Prepared statement for security
        $stmt = $conn->prepare("SELECT id, role, purok, name, password FROM users WHERE contact_number = ?");
        $stmt->bind_param("s", $contact);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // ✅ Verify hashed password
            if (password_verify($password, $user['password'])) {
                // Store user info in session
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['role']      = $user['role'];
                $_SESSION['purok']     = $user['purok']; // ✅ store resident's purok
                $_SESSION['name']      = $user['name'];  // ✅ store admin/resident name

                // ✅ Redirect based on role
                if ($user['role'] === 'admin') {
                    header("Location: dashboard_admin.php");
                } else {
                    header("Location: dashboard_resident.php");
                }
                exit();
            } else {
                $error = "Wrong password or contact number not found.";
            }
        } else {
            $error = "Wrong password or contact number not found.";
        }
    }
}
?>

<?php include("includes/header.php"); ?>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<div class="container mt-5 d-flex justify-content-center">
  <div class="card shadow" style="width: 400px;">
    <div class="card-body">
      <h3 class="card-title text-center mb-4">Login</h3>
      <?php if(isset($error)) echo "<div class='alert alert-danger text-center'>$error</div>"; ?>
      <form method="POST" id="loginForm">
        <div class="mb-3">
          <input type="text" name="contact" id="contact" placeholder="Contact Number" class="form-control" required>
          <small id="contactFeedback" class="text-danger"></small>
        </div>
        <div class="mb-3 position-relative">
          <input type="password" name="password" id="password" placeholder="Password (8 characters, no special symbols)" class="form-control" required>
          
          <!-- Eye icon toggle -->
          <button type="button" class="btn btn-sm btn-outline-secondary position-absolute top-0 end-0 mt-1 me-2" 
                  onclick="togglePassword()" aria-label="Show/Hide Password">
            <i class="bi bi-eye-fill" id="toggleIcon"></i>
          </button>
          
          <small id="passwordFeedback" class="text-danger"></small>
        </div>
        <div class="d-flex justify-content-between">
          <button type="submit" class="btn btn-primary">Login</button>
          <a href="index.php" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// Real-time validation
document.getElementById('contact').addEventListener('input', function() {
  const feedback = document.getElementById('contactFeedback');
  if (this.value.length < 10) {
    feedback.textContent = "Contact number must be at least 10 digits.";
  } else {
    feedback.textContent = "";
  }
});

document.getElementById('password').addEventListener('input', function() {
  const feedback = document.getElementById('passwordFeedback');
  if (this.value.length !== 8 || !/^[a-zA-Z0-9]+$/.test(this.value)) {
    feedback.textContent = "Password must be exactly 8 letters/numbers.";
  } else {
    feedback.textContent = "";
  }
});

// Toggle password visibility
function togglePassword() {
  const pwd = document.getElementById('password');
  const icon = document.getElementById('toggleIcon');
  
  if (pwd.type === "password") {
    pwd.type = "text";
    icon.classList.remove("bi-eye-fill");
    icon.classList.add("bi-eye-slash-fill");
  } else {
    pwd.type = "password";
    icon.classList.remove("bi-eye-slash-fill");
    icon.classList.add("bi-eye-fill");
  }
}
</script>

<?php include("includes/footer.php"); ?>
