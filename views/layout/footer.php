        </main>
        <footer class="app-footer text-center py-3 mt-auto">
            <?php $footerTagline = trim($brandTagline); ?>
            <small>&copy; <?= date('Y') ?> <?= htmlspecialchars($appName) ?><?= $footerTagline !== '' ? ' · ' . htmlspecialchars($footerTagline) : '' ?></small>
        </footer>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
