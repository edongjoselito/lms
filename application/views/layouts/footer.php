        </div><!-- /.content-area -->
    </div><!-- /.main-content -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function isMobileSidebar() {
            return window.matchMedia('(max-width: 768px)').matches;
        }

        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            var overlay = document.getElementById('sidebarOverlay');

            if (isMobileSidebar()) {
                document.body.classList.remove('sidebar-collapsed');
                sidebar.classList.toggle('open');
                overlay.classList.toggle('active', sidebar.classList.contains('open'));
                return;
            }

            sidebar.classList.remove('open');
            overlay.classList.remove('active');
            document.body.classList.toggle('sidebar-collapsed');
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('active');
        }

        window.addEventListener('resize', function () {
            if (!isMobileSidebar()) {
                closeSidebar();
            }
        });
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
