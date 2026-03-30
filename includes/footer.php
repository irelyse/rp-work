    </div> <!-- Close layout -->

    <!-- Global Floating Logout -->
    <div class="logout-fixed" onclick="window.location.href='logout.php'">
        <i data-lucide="log-out" size="18"></i>
        <span>Logout</span>
    </div>

    <!-- Generic Edit Modal -->
    <div class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <h2>Edit Resource</h2>
                <button class="close-modal"><i data-lucide="x"></i></button>
            </div>
            <div class="modal-body">
                <!-- Form populated by JS -->
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary close-modal">Cancel</button>
                <button class="btn btn-primary btn-save">Save Changes</button>
            </div>
        </div>
    </div>

    <!-- Main Logic -->
    <script src="assets/js/main.js?v=<?php echo time(); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
