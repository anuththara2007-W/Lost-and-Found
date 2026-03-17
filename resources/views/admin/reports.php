<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports — Lost & Found</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --white:        #FAFAF8;       
            --off-white:    #F2F0EB;       
            --parchment:    #E8E3D8;       
            --warm-mid:     #C9C0AE;       
            --clay:         #9C8C78;       
            --terracotta:   #C96442;       
            --terracotta-lt:#EDD5C8;       
            --sage:         #5C7A65;       
            --sage-lt:      #D1DFCF;       
            --midnight:     #1E2027;       
            --ink:          #3A3830;       
            --amber:        #D4940A;       
            --amber-lt:     #F5E9C8;       
            --shadow-card:  0 2px 14px rgba(30,32,39,0.07), 0 0 0 1px var(--parchment);
            --shadow-hover: 0 8px 32px rgba(30,32,39,0.12), 0 0 0 1px var(--warm-mid);
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: var(--white);
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            display: flex;
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: 260px;
            background: var(--midnight);
            color: var(--white);
            padding: 32px 24px;
            display: flex;
            flex-direction: column;
            gap: 24px;
            position: fixed;
            height: 100vh;
        }
        .sidebar-logo {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.5rem; font-weight: 400; color: var(--white);
            letter-spacing: .2px;
            margin-bottom: 24px;
            padding-left: 10px;
        }
        .sidebar-logo span { color: var(--terracotta); font-style: italic; }
        .nav-menu { list-style: none; display: flex; flex-direction: column; gap: 8px; }
        .nav-item {
            padding: 12px 16px;
            border-radius: 8px;
            color: var(--warm-mid);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all .2s;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .nav-item:hover {
            background: rgba(255,255,255,0.05);
            color: var(--white);
        }
        .nav-item.active {
            background: var(--terracotta);
            color: #fff;
        }

        /* ── Main Content ── */
        .main-content {
            flex: 1;
            padding: 48px;
            background: var(--white);
            margin-left: 260px;
            max-width: calc(100% - 260px);
        }
        .ds-header {
            display: flex; align-items: flex-end; justify-content: space-between;
            border-bottom: 1px solid var(--parchment);
            padding-bottom: 24px; margin-bottom: 40px;
        }
        .ds-header h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.5rem; font-weight: 300; letter-spacing: -.5px;
            color: var(--midnight);
            line-height: 1;
        }
        .ds-header p { color: var(--clay); font-size: 13px; margin-bottom: 6px; }

        /* ── Cards / Tabs ── */
        .tab-nav {
            display: flex; gap: 16px; margin-bottom: 32px;
            border-bottom: 1px solid var(--parchment);
            padding-bottom: 12px;
        }
        .tab-link {
            font-size: 13px; font-weight: 500; color: var(--clay);
            cursor: pointer; padding: 8px 16px; border-radius: 20px; transition: all .2s;
        }
        .tab-link.active { background: var(--off-white); color: var(--midnight); box-shadow: var(--shadow-card); }
        .tab-link:hover:not(.active) { color: var(--ink); }

        /* ── Table Layout ── */
        .table-container {
            background: var(--off-white);
            border-radius: 16px;
            padding: 24px;
            box-shadow: var(--shadow-card);
        }
        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }
        .admin-table th, .admin-table td {
            padding: 16px 8px;
            text-align: left;
            border-bottom: 1px solid var(--parchment);
        }
        .admin-table th {
            color: var(--clay);
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: .8px;
        }
        .admin-table td { color: var(--ink); font-size: 13px; }
        .admin-table tr:last-child td { border-bottom: none; }

        .btn-secondary {
            font-family: 'DM Sans', sans-serif;
            font-size: 11px; font-weight: 500; letter-spacing: .3px;
            padding: 6px 12px; border-radius: 6px; border: 1px solid var(--parchment); cursor: pointer;
            background: transparent; color: var(--ink);
            transition: all .18s;
        }
        .btn-secondary:hover { background: var(--parchment); }
        
        .badge {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 11px; font-weight: 500; letter-spacing: .8px; text-transform: uppercase;
            padding: 4px 10px; border-radius: 20px;
        }
        .badge-urgent { background: var(--amber-lt); color: var(--amber); }

    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-logo">Lost &amp; <span>Found</span></div>
        <nav>
            <ul class="nav-menu">
                <li><a href="dashboard.php" class="nav-item">Dashboard</a></li>
                <li><a href="items.php" class="nav-item">Manage Items</a></li>
                <li><a href="reports.php" class="nav-item active">Reports</a></li>
                <li><a href="users.php" class="nav-item">Users</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="ds-header">
            <div>
                <h1>Issue <em>Reports</em></h1>
            </div>
            <button class="btn-secondary" style="padding: 10px 18px; font-size:12px;">Export CSV</button>
        </header>

        <div class="tab-nav">
            <div class="tab-link active">Item Matches</div>
            <div class="tab-link">User Reports</div>
            <div class="tab-link">System Errors</div>
        </div>

        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Report ID</th>
                        <th>Type</th>
                        <th>Subject</th>
                        <th>Reported By</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#RPT-0092</td>
                        <td><span style="color:var(--sage); font-weight:600;">Match Claim</span></td>
                        <td>Leather Bifold Wallet</td>
                        <td>John Smith (john@example.com)</td>
                        <td>Today, 11:20 AM</td>
                        <td><span class="badge badge-urgent">Pending Review</span></td>
                        <td><button class="btn-secondary">View Case</button></td>
                    </tr>
                    <tr>
                        <td>#RPT-0091</td>
                        <td>User Report</td>
                        <td>Inappropriate Image Content</td>
                        <td>Alice R. (alice@example.com)</td>
                        <td>Yesterday, 2:30 PM</td>
                        <td style="color:var(--clay);">Resolved</td>
                        <td><button class="btn-secondary">View Case</button></td>
                    </tr>
                    <tr>
                        <td>#RPT-0090</td>
                        <td><span style="color:var(--sage); font-weight:600;">Match Claim</span></td>
                        <td>Blue Water Bottle</td>
                        <td>Tim F. (tim@example.com)</td>
                        <td>Sep 10, 8:45 AM</td>
                        <td style="color:var(--sage);">Approved</td>
                        <td><button class="btn-secondary">View Case</button></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </main>

</body>
</html>
