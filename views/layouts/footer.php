</div>
  <footer class="text-center py-3 mt-4" id="ft">
    <p>© <?php echo date('Y'); ?> ระบบจองห้องประชุม TN-BRMS v2.2 Powered by NOGITTIS</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
  <script src="../assets/js/script.js"></script>
  <script>
    <?php if (isset($_SESSION['success_message'])): ?>
              Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: "<?php echo $_SESSION['success_message']; ?>",
                timer: 2500,
                showConfirmButton: false
              });
            <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_message'])): ?>
              Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด!',
                text: "<?php echo $_SESSION['error_message']; ?>",
              });
    <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
  </script>
</body>
</html>