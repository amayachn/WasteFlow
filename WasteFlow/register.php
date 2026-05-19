<?php
include("includes/db.php");
session_start();

$showSuccessModal = false;
$showErrorModal = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $purok = $_POST['purok'];
    $contact = $_POST['contact'];
    $password_raw = $_POST['password'];

    // Validate password: must be exactly 8 characters, only letters/numbers
    if(strlen($password_raw) != 8 || !preg_match('/^[A-Za-z0-9]+$/', $password_raw)){
        $showErrorModal = true;
    }
    // Validate contact number (11 digits only)
    elseif(!preg_match('/^[0-9]{11}$/', $contact)){
        $showErrorModal = true;
    }
    else {
        $password = password_hash($password_raw, PASSWORD_BCRYPT);

        // Check if contact number already exists
        $check = $conn->prepare("SELECT id FROM users WHERE contact_number = ?");
        $check->bind_param("s", $contact);
        $check->execute();
        $check->store_result();

        if($check->num_rows > 0){
            $showErrorModal = true;
        } else {
            $sql = $conn->prepare("INSERT INTO users (name, purok, contact_number, password, role) VALUES (?,?,?,?, 'resident')");
            $sql->bind_param("ssss", $name, $purok, $contact, $password);
            if($sql->execute()){
                $showSuccessModal = true;
            } else {
                $showErrorModal = true;
            }
        }
    }
}
?>

<?php include("includes/header.php"); ?>

<div class="container d-flex justify-content-center align-items-center mt-5">
  <div class="card shadow-lg" style="max-width: 500px; width: 100%;" id="registerCard">
    <div class="card-header bg-success text-white text-center">
      <h4>Resident Registration</h4>
    </div>
    <div class="card-body">
      <form method="POST" action="register.php" class="row g-3" id="registerForm">
        <div class="col-12">
          <label class="form-label">Full Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-12">
          <div class="col-12">
          <label class="form-label">Purok</label>
          <select name="purok" class="form-select" required>
            <option value="">Select Purok</option>
            <option value="1">1</option>
            <option value="1A">1A</option>
            <option value="1B">1B</option>
            <option value="1C">1C</option>
            <option value="1D">1D</option>
            <option value="1E">1E</option>
            <option value="2">2</option>
            <option value="2A">2A</option>
            <option value="2B">2B</option>
            <option value="3">3</option>
            <option value="3A">3A</option>
            <option value="3B">3B</option>
            <option value="3C">3C</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
          </select>
        </div>
        <div class="col-12">
          <label class="form-label">Contact Number</label>
          <input type="text" name="contact" id="contact" class="form-control" required>
        </div>
        <div class="col-12">
          <label class="form-label">Password</label>
          <div class="input-group">
            <input type="password" name="password" id="password" class="form-control" required>
            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
              <img src="https://cdn-icons-png.flaticon.com/512/709/709612.png" 
                   alt="Show/Hide" width="20" height="20">
            </button>
          </div>
        </div>
        <div class="col-12 d-flex justify-content-between">
          <button type="submit" class="btn btn-success">Register</button>
          <a href="index.php" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Registration Successful</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <p>You have successfully registered as a resident.</p>
      </div>
      <div class="modal-footer">
        <a href="login.php" class="btn btn-success">Proceed to Login</a>
      </div>
    </div>
  </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Unsuccessful Registration</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <p>Registration failed. Please check your password or contact number and try again.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Try Again</button>
      </div>
    </div>
  </div>
</div>

<script>
// Toggle password visibility
document.getElementById("togglePassword").addEventListener("click", function() {
  const passwordField = document.getElementById("password");
  if (passwordField.type === "password") {
    passwordField.type = "text";
  } else {
    passwordField.type = "password";
  }
});

// Trigger modal if registration was successful or failed
<?php if($showSuccessModal): ?>
  document.addEventListener("DOMContentLoaded", function() {
    var successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();
    document.getElementById("registerCard").style.display = "none";
  });
<?php elseif($showErrorModal): ?>
  document.addEventListener("DOMContentLoaded", function() {
    var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
    errorModal.show();
  });
<?php endif; ?>
</script>

<?php include("includes/footer.php"); ?>
