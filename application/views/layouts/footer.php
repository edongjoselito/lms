        </div><!-- /.content-area -->
    </div><!-- /.main-content -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        }
        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('active');
        }
    </script>
    <?php if ($this->session->userdata('logged_in')): ?>
    <script>
        (function () {
            var keepAliveUrl = '<?= site_url('auth/keep_alive') ?>';
            var keepAliveInterval = 240000;

            function keepSessionAlive() {
                if (document.visibilityState === 'hidden') {
                    return;
                }

                fetch(keepAliveUrl, {
                    credentials: 'same-origin',
                    cache: 'no-store'
                }).catch(function () {});
            }

            setInterval(keepSessionAlive, keepAliveInterval);
        })();
    </script>
    <?php endif; ?>
</body>
</html>
