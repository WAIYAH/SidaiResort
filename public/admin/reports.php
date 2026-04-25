<?php declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/app/includes/init.php';

if (!isset($_SESSION['staff'])) {
    header('Location: ' . WEB_ROOT . '/admin/login.php');
    exit;
}

$staff = $_SESSION['staff'];
$pageTitle = 'Reports';
$extraJs = ['https://cdn.jsdelivr.net/npm/chart.js'];

$db = \App\Core\Database::getInstance();

// Revenue by month (last 6 months)
$monthlyRevenue = $db->queryAll(
    "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COALESCE(SUM(total_amount), 0) as revenue
     FROM bookings
     WHERE status NOT IN ('cancelled') AND deleted_at IS NULL AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
     GROUP BY month ORDER BY month ASC"
);

// Bookings by type
$bookingsByType = $db->queryAll(
    "SELECT booking_type, COUNT(*) as count FROM bookings WHERE deleted_at IS NULL GROUP BY booking_type ORDER BY count DESC"
);

// Payments by method
$paymentsByMethod = $db->queryAll(
    "SELECT method, COUNT(*) as count, SUM(amount) as total FROM payments WHERE status = 'completed' GROUP BY method"
);

// Summary stats
$thisMonth = $db->queryOne("SELECT COALESCE(SUM(total_amount), 0) as revenue, COUNT(*) as bookings FROM bookings WHERE MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW()) AND status NOT IN ('cancelled') AND deleted_at IS NULL");
$lastMonth = $db->queryOne("SELECT COALESCE(SUM(total_amount), 0) as revenue, COUNT(*) as bookings FROM bookings WHERE MONTH(created_at) = MONTH(DATE_SUB(NOW(), INTERVAL 1 MONTH)) AND YEAR(created_at) = YEAR(DATE_SUB(NOW(), INTERVAL 1 MONTH)) AND status NOT IN ('cancelled') AND deleted_at IS NULL");
?>
<?php include APP_PATH . '/includes/admin-head.php'; ?>

<?php include APP_PATH . '/includes/admin-header.php'; ?>

<div class="admin-layout">
    <?php include APP_PATH . '/includes/admin-sidebar.php'; ?>

    <main class="admin-main">
        <div class="admin-page-header">
            <h2 class="admin-page-title">Analytics & Reports</h2>
        </div>

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-label">This Month Revenue</div>
                <div class="stat-value"><?php echo format_kes($thisMonth['revenue'] ?? 0); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📅</div>
                <div class="stat-label">This Month Bookings</div>
                <div class="stat-value"><?php echo (int)($thisMonth['bookings'] ?? 0); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📈</div>
                <div class="stat-label">Last Month Revenue</div>
                <div class="stat-value"><?php echo format_kes($lastMonth['revenue'] ?? 0); ?></div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem;">
            <!-- Revenue Chart -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Monthly Revenue</h3>
                </div>
                <div class="admin-card-body">
                    <canvas id="revenueChart" height="250"></canvas>
                </div>
            </div>

            <!-- Bookings by Type -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Bookings by Type</h3>
                </div>
                <div class="admin-card-body">
                    <canvas id="bookingsChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Payments by Method -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Payment Methods</h3>
            </div>
            <div style="overflow-x:auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Method</th>
                            <th>Transactions</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($paymentsByMethod as $method): ?>
                            <tr>
                                <td><strong><?php echo ucfirst($method['method']); ?></strong></td>
                                <td><?php echo number_format((int)$method['count']); ?></td>
                                <td><?php echo format_kes($method['total']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($paymentsByMethod)): ?>
                            <tr><td colspan="3" style="text-align:center;color:#9CA3AF;padding:1rem;">No payment data</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof Chart === 'undefined') return;

    const gold = '#D4AF37';
    const forest = '#1A4D2E';
    const colors = ['#D4AF37', '#1A4D2E', '#8B2500', '#1E6FAC', '#2D7A4A', '#F0D060', '#A08020', '#3D1C02'];

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($monthlyRevenue, 'month')); ?>,
                datasets: [{
                    label: 'Revenue (KES)',
                    data: <?php echo json_encode(array_map('floatval', array_column($monthlyRevenue, 'revenue'))); ?>,
                    backgroundColor: gold + '80',
                    borderColor: gold,
                    borderWidth: 2,
                    borderRadius: 6,
                }],
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { callback: v => 'KES ' + v.toLocaleString() } },
                },
            },
        });
    }

    // Bookings by Type Chart
    const bookingsCtx = document.getElementById('bookingsChart');
    if (bookingsCtx) {
        new Chart(bookingsCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode(array_map(fn($b) => ucfirst(str_replace('_', ' ', $b['booking_type'])), $bookingsByType)); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_map('intval', array_column($bookingsByType, 'count'))); ?>,
                    backgroundColor: colors.slice(0, <?php echo count($bookingsByType); ?>),
                    borderWidth: 2,
                    borderColor: '#fff',
                }],
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom', labels: { padding: 16, font: { size: 12 } } } },
            },
        });
    }
});
</script>

<?php include APP_PATH . '/includes/admin-footer.php'; ?>
