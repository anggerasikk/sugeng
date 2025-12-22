</div> <!-- End content-wrapper -->
    </main>

    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);

        // Confirm delete actions
        function confirmDelete(message = 'Are you sure you want to delete this item?') {
            return confirm(message);
        }

        // Auto-submit forms with filters
        function initFilterAutoSubmit() {
            const filterInputs = document.querySelectorAll('input[name*="filter"], select[name*="filter"], input[name*="search"]');
            filterInputs.forEach(input => {
                input.addEventListener('change', function() {
                    this.closest('form').submit();
                });
            });
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initFilterAutoSubmit();
        });
    </script>
</body>
</html>
