</div> <!-- Penutup content-wrapper -->

<footer class="footer">
    <div class="footer-content">
        <p>
            <i class="fas fa-gamepad"></i>
            &copy; <?php echo date('Y'); ?> Gaming Inventory System. Hak cipta dilindungi.
        </p>
    </div>
</footer>

<script>
    // Update waktu secara real-time
    function updateDateTime() {
        const now = new Date();
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        const dateElement = document.getElementById('current-date');
        if (dateElement) {
            dateElement.textContent = now.toLocaleDateString('id-ID', options);
        }
    }

    setInterval(updateDateTime, 1000);
    updateDateTime();

    // Auto-dismiss notifikasi setelah 5 detik
    setTimeout(function() {
        const notifications = document.querySelectorAll('.notification');
        notifications.forEach(function(notification) {
            notification.style.display = 'none';
        });
    }, 5000);
</script>

</div> <!-- Penutup container -->

</body>
</html>
