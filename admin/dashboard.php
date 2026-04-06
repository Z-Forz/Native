<?php
include '../config.php';
if (!isset($_SESSION['username'])) {
    header('Location: ../auth/login.php');
    exit;
}
if (isset($_POST['add-projects'])) {
    $judul_project = $_POST['judul_project'];
    $deskripsi = $_POST['deskripsi'];
    $link = $_POST['link'];
    $dibuat = $_POST['dibuat'];
    $selesai = $_POST['selesai'];
    $teknologi = $_POST['teknologi'];
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
    $stmt = $conn->prepare('INSERT INTO projects (judul_project, gambar_project, deskripsi, link, dibuat, selesai, teknologi) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('sssssss', $judul_project, $nama_file_baru, $deskripsi, $link, $dibuat, $selesai, $teknologi);
    $stmt->execute();
    header('location: ../admin/dashboard.php');
    exit;
}
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM projects WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $editData = $result->fetch_assoc();
}
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $get = $conn->query("SELECT gambar_project FROM projects WHERE id=$id");
    $old = $get->fetch_assoc();
    if (file_exists('../uploads/' . $old['gambar_project'])) {
        unlink('../uploads/' . $old['gambar_project']);
    }
    $conn->query("DELETE FROM projects WHERE id=$id");
    header("Location: ../admin/dashboard.php");
    exit;
}
if (isset($_POST['update-project'])) {
    $id = $_POST['id'];
    $judul = $_POST['judul_project'];
    $deskripsi = $_POST['deskripsi'];
    $link = $_POST['link'];
    $dibuat = $_POST['dibuat'];
    $selesai = $_POST['selesai'];
    $teknologi = $_POST['teknologi'];
    if ($_FILES['gambar_project']['name']) {
        $nama_file = $_FILES['gambar_project']['name'];
        $tmp = $_FILES['gambar_project']['tmp_name'];
        $baru = time() . '_' . $nama_file;
        move_uploaded_file($tmp, '../uploads/' . $baru);
        $conn->query("UPDATE projects SET judul_project='$judul', gambar_project='$baru', deskripsi='$deskripsi', link='$link', dibuat='$dibuat', selesai='$selesai', teknologi='$teknologi' WHERE id=$id");
    } else {
        $conn->query("UPDATE projects SET judul_project='$judul', deskripsi='$deskripsi', link='$link', dibuat='$dibuat', selesai='$selesai', teknologi='$teknologi' WHERE id=$id");
    }
    header("Location: ../admin/dashboard.php");
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
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --c-bg:       #12012e;
            --c-surface:  #1a0840;
            --c-border:   rgba(139,92,246,0.20);
            --c-border-h: rgba(139,92,246,0.45);
            --c-purple:   #7c3aed;
            --c-purple2:  #8b5cf6;
            --c-purple3:  #a78bfa;
            --c-purple4:  #c4b5fd;
            --c-text:     #e2d9f3;
            --c-muted:    #c4b5fd;
            --grad:       linear-gradient(135deg,#7c3aed,#a78bfa);
        }

        body { font-family:'DM Sans',sans-serif; background:var(--c-bg); color:var(--c-text); min-height:100vh; display:flex !important; overflow:hidden; }

        /* ── Page anim ── */
        .page { display:none; }
        .page.active { display:block; animation:fadeIn .35s ease; }
        @keyframes fadeIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }

        /* ── Sidebar ── */
        #sidebar {
            width:240px !important; flex-shrink:0;
            background:var(--c-surface);
            border-right:1px solid var(--c-border);
            display:flex; flex-direction:column;
            height:100vh;
        }
        .sidebar-logo {
            padding:24px 20px 20px;
            border-bottom:1px solid var(--c-border);
            display:flex; align-items:center; gap:10px;
        }
        .logo-icon {
            width:36px; height:36px; border-radius:10px;
            background:var(--grad);
            display:flex; align-items:center; justify-content:center; flex-shrink:0;
        }
        .logo-text { font-family:'Syne',sans-serif; font-weight:700; font-size:15px; color:#fff; }
        .logo-sub  { font-size:10px; color:var(--c-purple3); letter-spacing:.08em; text-transform:uppercase; }
        nav { flex:1; padding:16px 12px; overflow-y:auto; }
        .nav-section-label { font-size:10px; font-weight:600; letter-spacing:.12em; text-transform:uppercase; color:var(--c-purple); padding:0 8px; margin:12px 0 6px; }
        .nav-item {
            display:flex; align-items:center; gap:10px;
            padding:10px 12px; border-radius:10px;
            font-size:13.5px; font-weight:500; color:var(--c-muted);
            cursor:pointer; transition:all .2s; margin-bottom:2px;
            border:1px solid transparent; position:relative;
        }
        .nav-item:hover { background:rgba(139,92,246,0.12); color:#e9d5ff; }
        .nav-item.active { background:linear-gradient(135deg,rgba(109,40,217,0.35),rgba(139,92,246,0.2)); border-color:rgba(139,92,246,0.35); color:#fff; }
        .nav-dot { width:4px; height:4px; border-radius:50%; background:var(--c-purple3); position:absolute; right:12px; display:none; }
        .nav-item.active .nav-dot { display:block; }
        .nav-icon { width:16px; height:16px; flex-shrink:0; opacity:.8; }
        .nav-item.active .nav-icon { opacity:1; }
        .sidebar-bottom { padding:16px 12px; border-top:1px solid var(--c-border); }
        .user-card { display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:10px; background:rgba(139,92,246,0.10); border:1px solid rgba(139,92,246,0.20); }
        .avatar { width:32px; height:32px; border-radius:8px; background:var(--grad); display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; color:#fff; flex-shrink:0; }
        .user-name { font-size:13px; font-weight:500; color:var(--c-text); }
        .user-role { font-size:10px; color:var(--c-purple2); text-transform:uppercase; letter-spacing:.06em; }

        /* ── FIX: #main tidak boleh menyusut ── */
        #main { min-width: 0; }

        /* ── Topbar ── */
        #topbar { height:60px; flex-shrink:0; background:var(--c-surface); border-bottom:1px solid var(--c-border); display:flex; align-items:center; justify-content:space-between; padding:0 28px; }
        .topbar-title { font-family:'Syne',sans-serif; font-size:16px; font-weight:700; color:#fff; }
        .icon-btn { width:34px; height:34px; border-radius:8px; background:rgba(139,92,246,0.12); border:1px solid rgba(139,92,246,0.20); display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all .2s; color:var(--c-purple3); text-decoration:none; }
        .icon-btn:hover { background:rgba(139,92,246,0.25); color:#fff; }

        /* ── Stat cards ── */
        .stat-card { background:var(--c-surface); border:1px solid var(--c-border); border-radius:14px; padding:18px 20px; transition:border-color .2s; }
        .stat-card:hover { border-color:var(--c-border-h); }
        .stat-icon { width:36px; height:36px; border-radius:9px; display:flex; align-items:center; justify-content:center; margin-bottom:12px; }
        .stat-label { font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.1em; color:var(--c-purple); margin-bottom:8px; }
        .stat-value { font-family:'Syne',sans-serif; font-size:26px; font-weight:700; color:#fff; margin-bottom:4px; }
        .stat-change { font-size:12px; }
        .stat-change.up   { color:#4ade80; }
        .stat-change.down { color:#f87171; }
        .stat-change.warn { color:#fbbf24; }

        /* ── Buttons ── */
        .btn-sm { font-size:12px; font-weight:600; padding:7px 14px; border-radius:8px; cursor:pointer; border:none; transition:all .2s; font-family:'DM Sans',sans-serif; }
        .btn-primary { background:var(--grad); color:#fff; }
        .btn-primary:hover { box-shadow:0 4px 15px rgba(124,58,237,0.5); transform:translateY(-1px); }
        .btn-outline { background:transparent; color:var(--c-purple3); border:1px solid rgba(139,92,246,0.35); }
        .btn-outline:hover { background:rgba(139,92,246,0.12); color:#fff; }
        .btn-danger { background:rgba(239,68,68,0.15); color:#f87171; border:1px solid rgba(239,68,68,0.30); text-decoration:none; }
        .btn-danger:hover { background:rgba(239,68,68,0.25); }

        /* ── Table ── */
        .table-wrap { background:var(--c-surface); border:1px solid var(--c-border); border-radius:14px; overflow:hidden; }
        table { width:100%; border-collapse:collapse; }
        thead tr { background:rgba(109,40,217,0.20); border-bottom:1px solid rgba(139,92,246,0.20); }
        th { padding:12px 16px; text-align:left; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.1em; color:var(--c-purple2); }
        td { padding:13px 16px; font-size:13px; color:var(--c-muted); border-bottom:1px solid rgba(139,92,246,0.08); }
        tr:last-child td { border-bottom:none; }
        tbody tr:hover td { background:rgba(139,92,246,0.07); color:#e9d5ff; }
        .td-name { font-weight:500; color:var(--c-text); }
        .prog-wrap { width:100px; height:4px; background:rgba(139,92,246,0.15); border-radius:2px; }
        .prog-fill { height:100%; border-radius:2px; }
        .prog-v { background:linear-gradient(90deg,#7c3aed,#a78bfa); }
        .prog-y { background:linear-gradient(90deg,#fbbf24,#f59e0b); }
        .prog-r { background:linear-gradient(90deg,#f87171,#ef4444); }
        .badge { font-size:10px; font-weight:600; padding:3px 9px; border-radius:20px; text-transform:uppercase; letter-spacing:.06em; }
        .badge-active  { background:rgba(74,222,128,0.15); color:#4ade80; border:1px solid rgba(74,222,128,0.25); }
        .badge-pending { background:rgba(251,191,36,0.12); color:#fbbf24; border:1px solid rgba(251,191,36,0.20); }
        .badge-done    { background:rgba(139,92,246,0.15); color:var(--c-purple3); border:1px solid rgba(139,92,246,0.25); }

        /* ── Project cards ── */
        .project-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; }
        .project-card { background:var(--c-surface); border:1px solid var(--c-border); border-radius:14px; padding:20px; cursor:pointer; transition:all .25s; position:relative; overflow:hidden; }
        .project-card::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; opacity:0; transition:opacity .25s; }
        .project-card:hover { border-color:var(--c-border-h); transform:translateY(-2px); }
        .project-card:hover::before { opacity:1; }
        .project-name { font-family:'Syne',sans-serif; font-size:24px; font-weight:700; color:#fff; margin-bottom:6px; }
        .project-desc { font-size:12px; color:var(--c-purple2); line-height:1.5; margin-bottom:14px; }
        .project-meta { display:flex; align-items:center; justify-content:space-between; font-size:12px; color:var(--c-muted); }

        /* ── Form ── */
        .form-card { background:var(--c-surface); border:1px solid var(--c-border); border-radius:14px; padding:24px; }
        .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:18px; }
        .form-group { display:flex; flex-direction:column; gap:6px; }
        .form-group.full { grid-column:1/-1; }
        .form-label { font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.1em; color:var(--c-purple2); }
        .form-input { background:rgba(255,255,255,0.05); border:1px solid rgba(139,92,246,0.25); border-radius:9px; padding:10px 14px; font-size:13px; color:var(--c-text); font-family:'DM Sans',sans-serif; transition:all .2s; width:100%; }
        .form-input::placeholder { color:rgba(167,139,250,0.35); }
        .form-input:focus { outline:none; border-color:rgba(139,92,246,0.70); background:rgba(255,255,255,0.08); box-shadow:0 0 0 3px rgba(124,58,237,0.15); }
        textarea.form-input { resize:vertical; min-height:90px; line-height:1.6; }
        .form-actions { display:flex; gap:10px; justify-content:flex-end; margin-top:24px; padding-top:20px; border-top:1px solid var(--c-border); }
        .btn-lg { padding:10px 22px; border-radius:10px; font-size:13px; font-weight:600; cursor:pointer; border:none; transition:all .2s; font-family:'DM Sans',sans-serif; text-decoration:none; display:inline-block; }

        /* Section header */
        .section-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; }
        .section-title { font-family:'Syne',sans-serif; font-size:15px; font-weight:700; color:#fff; }

        /* Scrollbar */
        ::-webkit-scrollbar { width:4px; }
        ::-webkit-scrollbar-track { background:transparent; }
        ::-webkit-scrollbar-thumb { background:rgba(139,92,246,0.3); border-radius:2px; }
    </style>
</head>
<body>

    <!-- ═══ SIDEBAR ═══ -->
    <div id="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon"><img src="" alt=""></div>
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
                Dashboard <span class="nav-dot"></span>
            </div>
            <div class="nav-section-label" style="margin-top:16px;">Proyek</div>
            <div class="nav-item" onclick="showPage('projects',this)">
                <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 7a2 2 0 012-2h3l2 2h9a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                </svg>
                Project <span class="nav-dot"></span>
            </div>
            <div class="nav-item" onclick="showPage('createproject',this)">
                <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Tambah Project <span class="nav-dot"></span>
            </div>
            <div class="nav-section-label" style="margin-top:16px;">Sistem</div>
            <div class="nav-item" onclick="alert('Halaman Pengaturan belum tersedia.')">
                <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
                </svg>
                Pengaturan <span class="nav-dot"></span>
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

    <!-- ═══ MAIN ═══ -->
    <div id="main" class="flex-1 flex flex-col h-screen overflow-hidden">
        <div id="topbar">
            <span class="topbar-title" id="topbar-title">Dashboard</span>
            <div class="flex items-center gap-3">
                <a href="../auth/logout.php" class="icon-btn">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-7" style="flex: 1; overflow-y: auto; padding: 28px;">
            <?php while ($row = $project->fetch_assoc()): ?>
            <!-- ══ DASHBOARD ══ -->
            <div id="page-dashboard" class="page active">
                <div class="section-header">
                    <span class="section-title">Project Terbaru</span>
                    <button class="btn-sm btn-primary" onclick="showPage('projects', document.querySelectorAll('.nav-item')[1])">Lihat Semua</button>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr><th>Nama Project</th><th>Teknologi</th><th>Tanggal Dibuat</th><th>Tanggal Selesai</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= $row['judul_project']; ?></td>
                                <td><?= $row['teknologi']; ?></td>
                                <td><?= $row['dibuat'] ; ?></td>
                                <td><?= $row['selesai']; ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ══ PROJECTS ══ -->
            <div id="page-projects" class="page">
                <div class="section-header" style="margin-bottom:20px;">
                    <span class="section-title" style="font-size:18px;">Semua Project</span>
                    <button class="btn-sm btn-primary" onclick="showPage('createproject', document.querySelectorAll('.nav-item')[2])">+ Tambah Project</button>
                </div>
                <div class="project-grid">
                    <div class="project-card">
                        <img src="../uploads/<?= $row['gambar_project']; ?>"
                            style="width:100%;height:250px;object-fit:cover;border-radius:10px;margin-bottom:10px;" alt="">
                        <div class="project-name"><?= $row['judul_project']; ?></div>
                        <div class="project-desc"><?= $row['deskripsi']; ?></div>
                        <div class="project-meta">
                            <span><?= $row['teknologi']; ?></span>
                            <span><?= $row['dibuat']; ?></span>
                        </div>
                        <div class="form-actions" style="margin-top:15px;">
                            <a href="?edit=<?= $row['id']; ?>" class="btn-lg btn-primary">Edit</a>
                            <a href="?hapus=<?= $row['id']; ?>" onclick="return confirm('Yakin hapus?')" class="btn-lg btn-danger">Hapus</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php endwhile; ?>
            <!-- ══ CREATE / EDIT PROJECT ══ -->
            <div id="page-createproject" class="page">
                <div class="section-header" style="margin-bottom:20px;">
                    <span class="section-title" style="font-size:18px;"><?= $editData ? 'Edit Project' : 'Tambah Project'; ?></span>
                    <span style="font-size:12px;color:var(--c-purple);">* wajib diisi</span>
                </div>
                <div class="form-card">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Nama Project</label>
                                <input type="text" class="form-input" name="judul_project" placeholder="Nama project" value="<?= $editData['judul_project'] ?? ''; ?>" required/>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Upload Image</label>
                                <input type="file" class="form-input" name="gambar_project"/>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Link Project</label>
                                <input type="text" class="form-input" name="link" placeholder="Link Project" value="<?= $editData['link'] ?? ''; ?>" required/>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Teknologi</label>
                                <input type="text" class="form-input" name="teknologi" placeholder="Teknologi yang digunakan" value="<?= $editData['teknologi'] ?? ''; ?>" required/>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tanggal Dibuat</label>
                                <input type="date" class="form-input" name="dibuat" value="<?= $editData['dibuat'] ?? ''; ?>" required/>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-input" name="selesai" value="<?= $editData['selesai'] ?? ''; ?>" required/>
                            </div>
                            <div class="form-group full">
                                <label class="form-label">Deskripsi Project</label>
                                <textarea class="form-input" name="deskripsi" placeholder="Deskripsi singkat project..." required><?= $editData['deskripsi'] ?? ''; ?></textarea>
                            </div>
                            <input type="hidden" name="id" value="<?= $editData['id'] ?? ''; ?>"/>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn-lg btn-outline" onclick="resetForm()">Reset</button>
                            <button type="submit" class="btn-lg btn-primary" name="<?= $editData ? 'update-project' : 'add-projects'; ?>">
                                <?= $editData ? 'Update Project' : 'Simpan Project'; ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        function showPage(id, el) {
            document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
            document.getElementById('page-' + id).classList.add('active');
            if (el && el.classList) el.classList.add('active');
            const titles = { dashboard:'Dashboard', projects:'Project', createproject:'Create Project' };
            document.getElementById('topbar-title').textContent = titles[id] || id;
        }
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('edit')) {
            showPage('createproject', document.querySelectorAll('.nav-item')[2]);
            window.history.replaceState({}, document.title, window.location.pathname);
        }
        function resetForm() {
            document.querySelectorAll('#page-createproject .form-input').forEach(el => el.value = '');
        }
    </script>
</body>
</html>