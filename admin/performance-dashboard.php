<?php
// Performance Monitoring Dashboard
// Access: /admin/performance-dashboard.php
// Password protection recommended

session_start();

// Simple authentication (replace with proper authentication)
$admin_password = 'jahongir2025'; // Change this password
if (!isset($_SESSION['admin_logged_in'])) {
    if (isset($_POST['password']) && $_POST['password'] === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Admin Login - Performance Dashboard</title>
            <style>
                body { font-family: Arial, sans-serif; background: #f5f5f5; }
                .login-form { max-width: 300px; margin: 100px auto; background: white; padding: 20px; border-radius: 5px; }
                input[type="password"] { width: 100%; padding: 10px; margin: 10px 0; }
                button { width: 100%; padding: 10px; background: #007cba; color: white; border: none; border-radius: 3px; }
            </style>
        </head>
        <body>
            <div class="login-form">
                <h2>Admin Login</h2>
                <form method="post">
                    <input type="password" name="password" placeholder="Enter password" required>
                    <button type="submit">Login</button>
                </form>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}

// Performance monitoring functions
function getPageLoadTimes() {
    $log_file = 'logs/performance.log';
    if (!file_exists($log_file)) {
        return [];
    }
    
    $lines = file($log_file, FILE_IGNORE_NEW_LINES);
    $data = [];
    
    foreach ($lines as $line) {
        $parts = explode('|', $line);
        if (count($parts) >= 3) {
            $data[] = [
                'timestamp' => $parts[0],
                'page' => $parts[1],
                'load_time' => floatval($parts[2]),
                'memory' => isset($parts[3]) ? intval($parts[3]) : 0
            ];
        }
    }
    
    return array_reverse($data); // Most recent first
}

function getSEOMetrics() {
    // This would typically connect to Google Analytics API
    // For now, we'll return sample data
    return [
        'organic_traffic' => 1250,
        'direct_traffic' => 890,
        'referral_traffic' => 340,
        'social_traffic' => 120,
        'total_sessions' => 2600,
        'bounce_rate' => 45.2,
        'avg_session_duration' => '2:34',
        'pages_per_session' => 3.2
    ];
}

function getTopPages() {
    return [
        ['page' => '/', 'views' => 1250, 'avg_time' => '2:45'],
        ['page' => '/samarkand-tours.php', 'views' => 890, 'avg_time' => '3:12'],
        ['page' => '/bukhara-tours.php', 'views' => 670, 'avg_time' => '2:58'],
        ['page' => '/uzbekistan-travel-guide.php', 'views' => 540, 'avg_time' => '4:15'],
        ['page' => '/uzbekistan-tours/best-of-uzbekistan-in-10-days.php', 'views' => 420, 'avg_time' => '3:45']
    ];
}

function getTopKeywords() {
    return [
        ['keyword' => 'uzbekistan tours', 'position' => 3, 'clicks' => 450],
        ['keyword' => 'samarkand tours', 'position' => 2, 'clicks' => 380],
        ['keyword' => 'bukhara tours', 'position' => 4, 'clicks' => 290],
        ['keyword' => 'central asia travel', 'position' => 7, 'clicks' => 180],
        ['keyword' => 'silk road tours', 'position' => 5, 'clicks' => 160]
    ];
}

function getPerformanceScore() {
    $pages = getPageLoadTimes();
    if (empty($pages)) {
        return ['score' => 0, 'status' => 'No data'];
    }
    
    $avg_load_time = array_sum(array_column($pages, 'load_time')) / count($pages);
    
    if ($avg_load_time < 2) {
        return ['score' => 95, 'status' => 'Excellent'];
    } elseif ($avg_load_time < 3) {
        return ['score' => 85, 'status' => 'Good'];
    } elseif ($avg_load_time < 4) {
        return ['score' => 70, 'status' => 'Fair'];
    } else {
        return ['score' => 50, 'status' => 'Needs Improvement'];
    }
}

// Get data
$performance_data = getPageLoadTimes();
$seo_metrics = getSEOMetrics();
$top_pages = getTopPages();
$top_keywords = getTopKeywords();
$performance_score = getPerformanceScore();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Performance Dashboard - Jahongir Travel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8f9fa; }
        .header { background: #2c3e50; color: white; padding: 20px; text-align: center; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .card { background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .card h3 { color: #2c3e50; margin-bottom: 15px; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        .metric { display: flex; justify-content: space-between; margin: 10px 0; padding: 10px; background: #f8f9fa; border-radius: 5px; }
        .metric-value { font-weight: bold; color: #27ae60; }
        .performance-score { text-align: center; font-size: 48px; font-weight: bold; color: #27ae60; }
        .status { text-align: center; font-size: 18px; color: #7f8c8d; margin-top: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; font-weight: bold; }
        .logout { position: absolute; top: 20px; right: 20px; }
        .logout a { color: white; text-decoration: none; background: #e74c3c; padding: 10px 15px; border-radius: 5px; }
        .chart { height: 200px; background: #f8f9fa; border-radius: 5px; display: flex; align-items: center; justify-content: center; color: #7f8c8d; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Performance Dashboard</h1>
        <p>Jahongir Travel Website Analytics</p>
        <div class="logout">
            <a href="?logout=1">Logout</a>
        </div>
    </div>

    <div class="container">
        <!-- Performance Overview -->
        <div class="grid">
            <div class="card">
                <h3>Performance Score</h3>
                <div class="performance-score"><?php echo $performance_score['score']; ?></div>
                <div class="status"><?php echo $performance_score['status']; ?></div>
            </div>
            
            <div class="card">
                <h3>SEO Metrics</h3>
                <div class="metric">
                    <span>Total Sessions</span>
                    <span class="metric-value"><?php echo number_format($seo_metrics['total_sessions']); ?></span>
                </div>
                <div class="metric">
                    <span>Bounce Rate</span>
                    <span class="metric-value"><?php echo $seo_metrics['bounce_rate']; ?>%</span>
                </div>
                <div class="metric">
                    <span>Avg Session Duration</span>
                    <span class="metric-value"><?php echo $seo_metrics['avg_session_duration']; ?></span>
                </div>
                <div class="metric">
                    <span>Pages per Session</span>
                    <span class="metric-value"><?php echo $seo_metrics['pages_per_session']; ?></span>
                </div>
            </div>
            
            <div class="card">
                <h3>Traffic Sources</h3>
                <div class="metric">
                    <span>Organic Traffic</span>
                    <span class="metric-value"><?php echo number_format($seo_metrics['organic_traffic']); ?></span>
                </div>
                <div class="metric">
                    <span>Direct Traffic</span>
                    <span class="metric-value"><?php echo number_format($seo_metrics['direct_traffic']); ?></span>
                </div>
                <div class="metric">
                    <span>Referral Traffic</span>
                    <span class="metric-value"><?php echo number_format($seo_metrics['referral_traffic']); ?></span>
                </div>
                <div class="metric">
                    <span>Social Traffic</span>
                    <span class="metric-value"><?php echo number_format($seo_metrics['social_traffic']); ?></span>
                </div>
            </div>
        </div>

        <!-- Top Pages -->
        <div class="card">
            <h3>Top Performing Pages</h3>
            <table>
                <thead>
                    <tr>
                        <th>Page</th>
                        <th>Views</th>
                        <th>Avg. Time on Page</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($top_pages as $page): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($page['page']); ?></td>
                        <td><?php echo number_format($page['views']); ?></td>
                        <td><?php echo $page['avg_time']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Top Keywords -->
        <div class="card">
            <h3>Top Ranking Keywords</h3>
            <table>
                <thead>
                    <tr>
                        <th>Keyword</th>
                        <th>Position</th>
                        <th>Clicks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($top_keywords as $keyword): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($keyword['keyword']); ?></td>
                        <td><?php echo $keyword['position']; ?></td>
                        <td><?php echo number_format($keyword['clicks']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Recent Performance Data -->
        <div class="card">
            <h3>Recent Performance Data</h3>
            <?php if (!empty($performance_data)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Page</th>
                        <th>Load Time (s)</th>
                        <th>Memory (MB)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($performance_data, 0, 10) as $data): ?>
                    <tr>
                        <td><?php echo date('Y-m-d H:i:s', strtotime($data['timestamp'])); ?></td>
                        <td><?php echo htmlspecialchars($data['page']); ?></td>
                        <td><?php echo number_format($data['load_time'], 2); ?></td>
                        <td><?php echo number_format($data['memory'] / 1024 / 1024, 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="chart">No performance data available</div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (isset($_GET['logout'])): ?>
    <script>
        <?php session_destroy(); ?>
        window.location.href = 'performance-dashboard.php';
    </script>
    <?php endif; ?>
</body>
</html>

