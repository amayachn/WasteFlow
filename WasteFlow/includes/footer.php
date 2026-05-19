  <footer class="footer bg-light text-center fixed-bottom py-2">
    <div class="d-flex justify-content-between px-3">
      <?php if(isset($backLink)): ?>
        <a href="<?php echo $backLink; ?>" class="btn btn-secondary btn-sm">Back</a>
      <?php else: ?><span></span><?php endif; ?>

      <?php if(isset($nextLink)): ?>
        <a href="<?php echo $nextLink; ?>" class="btn btn-primary btn-sm">Next</a>
      <?php else: ?><span></span><?php endif; ?>
    </div>
    <p class="mt-2 mb-0 text-muted">WasteFlow © 2026 Barangay Ampayon</p>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
