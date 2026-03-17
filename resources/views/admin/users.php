<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users — Lost & Found</title>
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

        /* ── Filters ── */
        .filter-bar {
            display: flex; gap: 16px; align-items: center; justify-content: space-between;
            margin-bottom: 32px;
        }
        .input-field {
            padding: 10px 14px;
            background: var(--parchment); border: 1px solid var(--warm-mid);
            border-radius: 8px; font-family: 'DM Sans', sans-serif; font-size: 13px;
            color: var(--ink); outline: none; transition: border-color .15s, box-shadow .15s;
            width: 320px;
        }
        .input-field:focus { border-color: var(--terracotta); box-shadow: 0 0 0 3px var(--terracotta-lt); }

        .btn-primary { 
            font-family: 'DM Sans', sans-serif;
            font-size: 12px; font-weight: 500; letter-spacing: .3px;
            padding: 9px 20px; border-radius: 8px; border: none; cursor: pointer;
            background: var(--terracotta); color: #fff; transition: all .18s; 
        }
        .btn-primary:hover { background: #b5572f; transform: translateY(-1px); box-shadow: 0 4px 14px rgba(201,100,66,.35); }

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
        
        .user-cell { display: flex; align-items: center; gap: 12px; }
        .avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: var(--parchment); color: var(--clay);
            display: flex; align-items: center; justify-content: center;
            font-weight: 600; font-size: 14px;
        }

        .badge-active { background: var(--sage-lt); color: var(--sage); padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 500; text-transform: uppercase; }
        .badge-banned { background: var(--terracotta-lt); color: var(--terracotta); padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 500; text-transform: uppercase; }

        .btn-secondary {
            font-family: 'DM Sans', sans-serif;
            font-size: 11px; font-weight: 500; letter-spacing: .3px;
            padding: 6px 12px; border-radius: 6px; border: 1px solid var(--parchment); cursor: pointer;
            background: transparent; color: var(--ink);
            transition: all .18s;
        }
        .btn-secondary:hover { background: var(--parchment); }
        .action-cell { display: flex; gap: 8px; }

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
                <li><a href="reports.php" class="nav-item">Reports</a></li>
                <li><a href="users.php" class="nav-item active">Users</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="ds-header">
            <div>
                <h1>Manage <em>Users</em></h1>
            </div>
            <button class="btn-primary">+ Invite User</button>
        </header>

        <div class="filter-bar">
            <input class="input-field" type="text" placeholder="Search by name or email address...">
        </div>

        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="avatar">JD</div>
                                <strong>Jane Doe</strong>
                            </div>
                        </td>
                        <td style="color:var(--clay);">jane.doe@example.com</td>
                        <td>Regular User</td>
                        <td><span class="badge-active">Active</span></td>
                        <td>Sep 01, 2023</td>
                        <td class="action-cell">
                            <button class="btn-secondary">Edit</button>
                            <button class="btn-secondary">Ban</button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="avatar" style="background:var(--midnight); color:var(--white);">A</div>
                                <strong>Admin User</strong>
                            </div>
                        </td>
                        <td style="color:var(--clay);">admin@lostfound.com</td>
                        <td><strong style="color:var(--midnight);">Administrator</strong></td>
                        <td><span class="badge-active">Active</span></td>
                        <td>Jan 15, 2023</td>
                        <td class="action-cell">
                            <button class="btn-secondary">Edit</button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="avatar">MK</div>
                                <strong>Mike Kramer</strong>
                            </div>
                        </td>
                        <td style="color:var(--clay);">mike.kramer@example.com</td>
                        <td>Regular User</td>
                        <td><span class="badge-banned">Banned</span></td>
                        <td>Jul 22, 2023</td>
                        <td class="action-cell">
                            <button class="btn-secondary">Edit</button>
                            <button class="btn-secondary">Unban</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </main>

</body>
</html>
