<?php

include '../config.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../auth/login.php');
    exit;
}

// tambahkan project

if (isset($_POST['add-projects'])) {
    $judul_project = $_POST['judul_project'];
    $deskripsi = $_POST['deskripsi'];
    $link = $_POST['link'];
    $dibuat = $_POST['dibuat'];
    $selesai = $_POST['selesai'];
    $teknologi = $_POST['teknologi'];

    // img project
    $nama_file = $_FILES['gambar_project']['name'];
    $tmp_file = $_FILES['gambar_project']['tmp_name'];

    $nama_file_baru = time() . '_' . $nama_file;

    $target = '../uploads/' . $nama_file_baru;

    if (move_uploaded_file($tmp_file, $target)) {
        echo "Upload sukses";
    } else {
        echo "Upload gagal";
        var_dump($_FILES);
        exit;
    }

    $stmt = $conn -> prepare('INSERT INTO projects ( judul_project, gambar_project, deskripsi, link, dibuat, selesai, teknologi) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt -> bind_param('sssssss', $judul_project, $nama_file_baru, $deskripsi, $link, $dibuat, $selesai, $teknologi);
    $stmt -> execute();
    header('location: ../admin/dashboard.php');
    exit;
}

$project = $conn->query("SELECT * FROM projects ORDER BY id DESC");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Admin Panel</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,400&display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
        theme: {
            extend: {
            colors: {
                v: {
                950: '#12012e',
                900: '#1e0a45',
                800: '#2d1260',
                700: '#4c1d95',
                600: '#6d28d9',
                500: '#7c3aed',
                400: '#8b5cf6',
                300: '#a78bfa',
                200: '#c4b5fd',
                100: '#ede9fe',
                }
            }
            }
        }
        }
    </script>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'DM Sans',sans-serif;background:#12012e;color:#e2d9f3;min-height:100vh;display:flex;overflow:hidden}
        h1,h2,h3,.font-display{font-family:'Syne',sans-serif}

        /* ── Sidebar ── */
        #sidebar{
        width:240px;flex-shrink:0;
        background:#1a0840;
        border-right:1px solid rgba(139,92,246,0.18);
        display:flex;flex-direction:column;
        height:100vh;position:relative;z-index:20;
        }
        .sidebar-logo{
        padding:24px 20px 20px;
        border-bottom:1px solid rgba(139,92,246,0.15);
        display:flex;align-items:center;gap:10px;
        }
        .logo-icon{
        width:36px;height:36px;border-radius:10px;
        background:linear-gradient(135deg,#7c3aed,#a78bfa);
        display:flex;align-items:center;justify-content:center;flex-shrink:0;
        }
        .logo-text{font-family:'Syne',sans-serif;font-weight:700;font-size:15px;color:#fff;}
        .logo-sub{font-size:10px;color:#a78bfa;letter-spacing:.08em;text-transform:uppercase;}

        nav{flex:1;padding:16px 12px;overflow-y:auto;}
        .nav-section-label{
        font-size:10px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;
        color:#6d28d9;padding:0 8px;margin:12px 0 6px;
        }
        .nav-item{
        display:flex;align-items:center;gap:10px;
        padding:10px 12px;border-radius:10px;
        font-size:13.5px;font-weight:500;color:#c4b5fd;
        cursor:pointer;transition:all .2s;margin-bottom:2px;
        border:1px solid transparent;position:relative;
        }
        .nav-item:hover{background:rgba(139,92,246,0.12);color:#e9d5ff;}
        .nav-item.active{
        background:linear-gradient(135deg,rgba(109,40,217,0.35),rgba(139,92,246,0.2));
        border-color:rgba(139,92,246,0.35);color:#fff;
        }
        .nav-item.active .nav-dot{
        width:4px;height:4px;border-radius:50%;background:#a78bfa;
        position:absolute;right:12px;
        }
        .nav-icon{width:16px;height:16px;flex-shrink:0;opacity:.8;}
        .nav-item.active .nav-icon{opacity:1;}

        .sidebar-bottom{
        padding:16px 12px;
        border-top:1px solid rgba(139,92,246,0.15);
        }
        .user-card{
        display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;
        background:rgba(139,92,246,0.1);border:1px solid rgba(139,92,246,0.2);
        }
        .avatar{
        width:32px;height:32px;border-radius:8px;
        background:linear-gradient(135deg,#7c3aed,#c4b5fd);
        display:flex;align-items:center;justify-content:center;
        font-size:12px;font-weight:700;color:#fff;flex-shrink:0;
        }
        .user-name{font-size:13px;font-weight:500;color:#e2d9f3;}
        .user-role{font-size:10px;color:#8b5cf6;text-transform:uppercase;letter-spacing:.06em;}

        /* ── Main content ── */
        #main{flex:1;display:flex;flex-direction:column;height:100vh;overflow:hidden;}

        /* Topbar */
        #topbar{
        height:60px;flex-shrink:0;
        background:#1a0840;
        border-bottom:1px solid rgba(139,92,246,0.15);
        display:flex;align-items:center;justify-content:space-between;
        padding:0 28px;
        }
        .topbar-title{font-family:'Syne',sans-serif;font-size:16px;font-weight:700;color:#fff;}
        .topbar-right{display:flex;align-items:center;gap:12px;}
        .icon-btn{
        width:34px;height:34px;border-radius:8px;
        background:rgba(139,92,246,0.12);border:1px solid rgba(139,92,246,0.2);
        display:flex;align-items:center;justify-content:center;cursor:pointer;
        transition:all .2s;color:#a78bfa;
        }
        .icon-btn:hover{background:rgba(139,92,246,0.25);color:#fff;}
        .notif-dot{
        width:7px;height:7px;border-radius:50%;background:#8b5cf6;
        position:absolute;top:6px;right:6px;border:1.5px solid #1a0840;
        }

        /* Content area */
        #content{flex:1;overflow-y:auto;padding:28px;}

        /* ── Page views ── */
        .page{display:none;animation:fadeIn .35s ease;}
        .page.active{display:block;}
        @keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}

        /* ── Stat cards ── */
        .stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;}
        .stat-card{
        background:#1a0840;border:1px solid rgba(139,92,246,0.2);
        border-radius:14px;padding:18px 20px;
        transition:border-color .2s;
        }
        .stat-card:hover{border-color:rgba(139,92,246,0.45);}
        .stat-label{font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.1em;color:#7c3aed;margin-bottom:8px;}
        .stat-value{font-family:'Syne',sans-serif;font-size:26px;font-weight:700;color:#fff;margin-bottom:4px;}
        .stat-change{font-size:12px;color:#a78bfa;}
        .stat-change.up{color:#4ade80;}
        .stat-change.down{color:#f87171;}
        .stat-icon{
        width:36px;height:36px;border-radius:9px;
        display:flex;align-items:center;justify-content:center;
        margin-bottom:12px;
        }

        /* ── Section header ── */
        .section-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;}
        .section-title{font-family:'Syne',sans-serif;font-size:15px;font-weight:700;color:#fff;}
        .btn-sm{
        font-size:12px;font-weight:600;padding:7px 14px;border-radius:8px;cursor:pointer;
        border:none;transition:all .2s;font-family:'DM Sans',sans-serif;
        }
        .btn-primary{background:linear-gradient(135deg,#7c3aed,#a78bfa);color:#fff;}
        .btn-primary:hover{box-shadow:0 4px 15px rgba(124,58,237,0.5);transform:translateY(-1px);}
        .btn-outline{background:transparent;color:#a78bfa;border:1px solid rgba(139,92,246,0.35);}
        .btn-outline:hover{background:rgba(139,92,246,0.12);color:#fff;}

        /* ── Project cards ── */
        .project-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;}
        .project-card{
        background:#1a0840;border:1px solid rgba(139,92,246,0.18);
        border-radius:14px;padding:20px;cursor:pointer;
        transition:all .25s;position:relative;overflow:hidden;
        }
        .project-card::before{
        content:'';position:absolute;top:0;left:0;right:0;height:3px;
        background:linear-gradient(90deg,#7c3aed,#a78bfa);
        opacity:0;transition:opacity .25s;
        }
        .project-card:hover{border-color:rgba(139,92,246,0.4);transform:translateY(-2px);}
        .project-card:hover::before{opacity:1;}
        .project-name{font-family:'Syne',sans-serif;font-size:14px;font-weight:700;color:#fff;margin-bottom:6px;}
        .project-desc{font-size:12px;color:#8b5cf6;line-height:1.5;margin-bottom:14px;}
        .project-meta{display:flex;align-items:center;justify-content:space-between;}
        .badge{
        font-size:10px;font-weight:600;padding:3px 9px;border-radius:20px;
        text-transform:uppercase;letter-spacing:.06em;
        }
        .badge-active{background:rgba(74,222,128,0.15);color:#4ade80;border:1px solid rgba(74,222,128,0.25);}
        .badge-pending{background:rgba(251,191,36,0.12);color:#fbbf24;border:1px solid rgba(251,191,36,0.2);}
        .badge-done{background:rgba(139,92,246,0.15);color:#a78bfa;border:1px solid rgba(139,92,246,0.25);}
        .progress-wrap{margin-top:14px;}
        .progress-label{display:flex;justify-content:space-between;font-size:11px;color:#7c3aed;margin-bottom:5px;}
        .progress-bar{height:4px;border-radius:2px;background:rgba(139,92,246,0.15);}
        .progress-fill{height:100%;border-radius:2px;background:linear-gradient(90deg,#7c3aed,#a78bfa);}

        /* ── Table ── */
        .table-wrap{background:#1a0840;border:1px solid rgba(139,92,246,0.18);border-radius:14px;overflow:hidden;}
        table{width:100%;border-collapse:collapse;}
        thead tr{background:rgba(109,40,217,0.2);border-bottom:1px solid rgba(139,92,246,0.2);}
        th{padding:12px 16px;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.1em;color:#8b5cf6;}
        td{padding:13px 16px;font-size:13px;color:#c4b5fd;border-bottom:1px solid rgba(139,92,246,0.08);}
        tr:last-child td{border-bottom:none;}
        tbody tr:hover td{background:rgba(139,92,246,0.07);color:#e9d5ff;}
        .td-name{font-weight:500;color:#e2d9f3;}

        /* ── Chart bars (CSS only) ── */
        .chart-wrap{background:#1a0840;border:1px solid rgba(139,92,246,0.18);border-radius:14px;padding:20px;}
        .chart-bars{display:flex;align-items:flex-end;gap:8px;height:120px;padding-top:8px;}
        .bar-col{flex:1;display:flex;flex-direction:column;align-items:center;gap:6px;}
        .bar{
        width:100%;border-radius:4px 4px 0 0;
        background:linear-gradient(180deg,#8b5cf6,rgba(124,58,237,0.4));
        transition:height .6s cubic-bezier(.34,1.56,.64,1);
        }
        .bar-label{font-size:10px;color:#7c3aed;text-align:center;}

        /* ── Edit form ── */
        .form-card{background:#1a0840;border:1px solid rgba(139,92,246,0.18);border-radius:14px;padding:24px;}
        .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px;}
        .form-group{display:flex;flex-direction:column;gap:6px;}
        .form-group.full{grid-column:1/-1;}
        label.form-label{font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.1em;color:#8b5cf6;}
        .form-input{
        background:rgba(255,255,255,0.05);
        border:1px solid rgba(139,92,246,0.25);
        border-radius:9px;padding:10px 14px;
        font-size:13px;color:#e2d9f3;
        font-family:'DM Sans',sans-serif;
        transition:all .2s;
        }
        .form-input::placeholder{color:rgba(167,139,250,0.35);}
        .form-input:focus{
        outline:none;
        border-color:rgba(139,92,246,0.7);
        background:rgba(255,255,255,0.08);
        box-shadow:0 0 0 3px rgba(124,58,237,0.15);
        }
        textarea.form-input{resize:vertical;min-height:90px;line-height:1.6;}
        select.form-input{cursor:pointer;}
        select.form-input option{background:#1a0840;}
        .form-actions{display:flex;gap:10px;justify-content:flex-end;margin-top:24px;padding-top:20px;border-top:1px solid rgba(139,92,246,0.15);}
        .btn-lg{padding:10px 22px;border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;border:none;transition:all .2s;font-family:'DM Sans',sans-serif;}

        /* Activity feed */
        .activity-item{display:flex;gap:12px;padding:10px 0;border-bottom:1px solid rgba(139,92,246,0.08);}
        .activity-item:last-child{border-bottom:none;}
        .act-dot{width:8px;height:8px;border-radius:50%;background:#7c3aed;margin-top:5px;flex-shrink:0;}
        .act-text{font-size:13px;color:#c4b5fd;line-height:1.5;}
        .act-time{font-size:11px;color:#6d28d9;margin-top:2px;}

        /* Scrollbar */
        ::-webkit-scrollbar{width:4px;}
        ::-webkit-scrollbar-track{background:transparent;}
        ::-webkit-scrollbar-thumb{background:rgba(139,92,246,0.3);border-radius:2px;}
    </style>
</head>
<body>

    <!-- ═══════════════════════════════ SIDEBAR ═══════════════════════════════ -->
    <div id="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">
                <img src="" alt="">
            </div>
            <div>
            <div class="logo-text">AdminPanel</div>
            <div class="logo-sub">Welcome, Admin</div>
            </div>
        </div>

        <nav>
            <div class="nav-section-label">Menu Utama</div>

            <div class="nav-item active" onclick="showPage('dashboard',this)">
                <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>
                </svg>
                Dashboard
                <span class="nav-dot"></span>
            </div>

            <div class="nav-section-label" style="margin-top:16px;">Proyek</div>

            <div class="nav-item" onclick="showPage('projects',this)">
                <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 7a2 2 0 012-2h3l2 2h9a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                </svg>
                Project
                <span class="nav-dot"></span>
            </div>

            <div class="nav-item" onclick="showPage('createproject',this)">
                <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Tambah Project
                <span class="nav-dot"></span>
            </div>

            <div class="nav-section-label" style="margin-top:16px;">Sistem</div>

            <div class="nav-item" onclick="alert('Halaman Pengaturan belum tersedia.')">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="3"/>
                <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
            </svg>
            Pengaturan
            <span class="nav-dot"></span>
            </div>
        </nav>

    <div class="sidebar-bottom">
        <div class="user-card">
        <div class="avatar">AD</div>
        <div>
            <div class="user-name">Admin</div>
            <div class="user-role">King of Admin</div>
        </div>
        </div>
    </div>
    </div>

    <!-- ═══════════════════════════════ MAIN ═══════════════════════════════ -->
    <div id="main">

        <!-- Topbar -->
        <div id="topbar">
            <span class="topbar-title" id="topbar-title">Dashboard</span>
            <div class="topbar-right">
                <a href="../auth/logout.php" class="icon-btn">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Content -->
        <div id="content">

            <!-- ══════════ DASHBOARD ══════════ -->
            <div id="page-dashboard" class="page active">
            <!-- Stat cards -->
                <div class="stat-grid">
                    <div class="stat-card">
                        <div class="stat-icon" style="background:rgba(139,92,246,0.15);">
                            <svg width="16" height="16" fill="none" stroke="#a78bfa" stroke-width="2" viewBox="0 0 24 24"><path d="M3 7a2 2 0 012-2h3l2 2h9a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                        </div>
                        <div class="stat-label">Total Project</div>
                        <div class="stat-value">24</div>
                        <div class="stat-change up">↑ 3 bulan ini</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background:rgba(74,222,128,0.1);">
                            <svg width="16" height="16" fill="none" stroke="#4ade80" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div class="stat-label">Selesai</div>
                        <div class="stat-value">18</div>
                        <div class="stat-change up">↑ 75% selesai</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background:rgba(251,191,36,0.1);">
                            <svg width="16" height="16" fill="none" stroke="#fbbf24" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <div class="stat-label">Berjalan</div>
                        <div class="stat-value">4</div>
                        <div class="stat-change" style="color:#fbbf24;">● Aktif sekarang</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background:rgba(248,113,113,0.1);">
                            <svg width="16" height="16" fill="none" stroke="#f87171" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        </div>
                        <div class="stat-label">Pending</div>
                        <div class="stat-value">2</div>
                        <div class="stat-change down">↓ perlu perhatian</div>
                    </div>
                </div>

                <!-- Table -->
                <div class="section-header">
                    <span class="section-title">Project Terbaru</span>
                    <button class="btn-sm btn-primary" onclick="showPage('projects', document.querySelector('[onclick*=\"projects\"]'))">Lihat Semua</button>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr><th>Nama Project</th><th>Deadline</th><th>Progress</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="td-name">Aplikasi Mobile</td>
                                <td>30 Mei 2026</td>
                                <td><div style="width:100px;height:4px;background:rgba(139,92,246,0.15);border-radius:2px;"><div style="width:55%;height:100%;background:linear-gradient(90deg,#7c3aed,#a78bfa);border-radius:2px;"></div></div></td>
                                <td><span class="badge badge-active">Aktif</span></td>
                            </tr>
                            <tr>
                                <td class="td-name">Aplikasi Mobile</td>
                                <td>30 Mei 2026</td>
                                <td><div style="width:100px;height:4px;background:rgba(139,92,246,0.15);border-radius:2px;"><div style="width:55%;height:100%;background:linear-gradient(90deg,#7c3aed,#a78bfa);border-radius:2px;"></div></div></td>
                                <td><span class="badge badge-active">Aktif</span></td>
                            </tr>
                            <tr>
                                <td class="td-name">Dashboard Analytics</td>
                                <td>1 Jun 2026</td>
                                <td><div style="width:100px;height:4px;background:rgba(139,92,246,0.15);border-radius:2px;"><div style="width:30%;height:100%;background:linear-gradient(90deg,#fbbf24,#f59e0b);border-radius:2px;"></div></div></td>
                                <td><span class="badge badge-pending">Pending</span></td>
                            </tr>
                            <tr>
                                <td class="td-name">API Gateway</td>
                                <td>20 Apr 2026</td>
                                <td><div style="width:100px;height:4px;background:rgba(139,92,246,0.15);border-radius:2px;"><div style="width:10%;height:100%;background:linear-gradient(90deg,#f87171,#ef4444);border-radius:2px;"></div></div></td>
                                <td><span class="badge badge-pending">Pending</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ══════════ PROJECTS ══════════ -->
            <div id="page-projects" class="page">
                <div class="section-header" style="margin-bottom:20px;">
                    <span class="section-title" style="font-size:18px;">Semua Project</span>
                    <button class="btn-sm btn-primary" onclick="showPage('createproject', document.querySelector('[onclick*=createproject]'))">+ Tambah Project</button>
                </div>

                <div class="project-grid">

                <?php while ($row = $project->fetch_assoc()): ?>

                    <div class="project-card">

                        <img src="../uploads/<?php echo $row['gambar_project']; ?>
                            "style="width:100%; height:150px; object-fit:cover; border-radius:10px; margin-bottom:10px;">

                        <div class="project-name">
                            <?php echo $row['judul_project']; ?>
                        </div>

                        <div class="project-desc">
                            <?php echo $row['deskripsi']; ?>
                        </div>

                        <div class="project-meta">
                            <span><?php echo $row['teknologi']; ?></span>
                            <span><?php echo $row['dibuat']; ?></span>
                        </div>

                    </div>

                <?php endwhile; ?>

                </div>
            </div>

            <!-- ══════════ CREATE PROJECT ══════════ -->
            <div id="page-createproject" class="page">
                <div class="section-header" style="margin-bottom:20px;">
                    <span class="section-title" style="font-size:18px;">Create</span>
                    <span style="font-size:12px;color:#7c3aed;">* wajib diisi</span>
                </div>

                <div class="form-card">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Nama Project</label>
                                <input type="text" class="form-input" name="judul_project" placeholder="Nama project" required/>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Upload Image</label>
                                <input type="file" class="form-input" name="gambar_project" required/>
                            </div>
                            <div class="form-group">
                                <label class="form-label">github</label>
                                <input type="text" class="form-input" name="link" placeholder="Link github" required/>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Teknologi</label>
                                <input type="text" class="form-input" name="teknologi" placeholder="teknologi yang digunakan" required/>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tanggal dibuat</label>
                                <input type="date" class="form-input" name="dibuat" required/>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tanggal selesai</label>
                                <input type="date" class="form-input" name="selesai" required/>
                            </div>
                            <div class="form-group full">
                                <label class="form-label">Deskripsi Project</label>
                                <textarea class="form-input" name="deskripsi" placeholder="Deskripsi singkat project..."></textarea>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn-lg btn-outline" onclick="resetForm()">Reset</button>
                            <button type="submit" name="add-projects" class="btn-lg btn-primary" style="background:linear-gradient(135deg,#7c3aed,#a78bfa);">Simpan Project</button>
                        </div>
                    </form>
                </div>

                <!-- Save confirmation -->
                <div style="display:none;position:fixed;bottom:28px;right:28px;background:linear-gradient(135deg,#059669,#34d399);color:white;padding:12px 20px;border-radius:12px;font-size:13px;font-weight:600;font-family:'DM Sans',sans-serif;box-shadow:0 8px 24px rgba(5,150,105,0.4);z-index:100;">
                    ✓ Project berhasil disimpan!
                </div>
            </div>

        </div><!-- /content -->
    </div><!-- /main -->

    <script>
    // ── Navigation ──
    function showPage(id, el) {
        document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
        document.getElementById('page-' + id).classList.add('active');
        if (el && el.classList) el.classList.add('active');
        const titles = { dashboard:'Dashboard', projects:'Project', createproject:'Create Project' };
        document.getElementById('topbar-title').textContent = titles[id] || id;
    }

    // ── Animated bar chart ──
    const chartData = [3, 5, 4, 8, 6, 9];
    const maxVal = Math.max(...chartData);
    const barsEl = document.getElementById('chart-bars');
    chartData.forEach(v => {
        const col = document.createElement('div');
        col.className = 'bar-col';
        const bar = document.createElement('div');
        bar.className = 'bar';
        bar.style.height = '0px';
        col.appendChild(bar);
        barsEl.appendChild(col);
        setTimeout(() => { bar.style.height = Math.round((v / maxVal) * 100) + 'px'; }, 200);
    });

    // ── Load project data ──
    function resetForm() {
        document.querySelectorAll('#page-createproject .form-input').forEach(el => el.value = '');
    }
    </script>
</body>
</html>