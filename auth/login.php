<?php
include '../config.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username =? AND password = SHA2(?,256)");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $_SESSION['username'] = $username;
        header('location: ../admin/dashboard.php');
        exit;
    }else{
        $error ="username atau password anda salah";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            violet: {
              950: '#1a0533',
              900: '#2d0a5e',
              800: '#3d1278',
              700: '#5b21b6',
              600: '#7c3aed',
              500: '#8b5cf6',
              400: '#a78bfa',
              300: '#c4b5fd',
              200: '#ddd6fe',
              100: '#ede9fe',
            }
          },
          fontFamily: {
            display: ['Syne', 'sans-serif'],
            body: ['DM Sans', 'sans-serif'],
          },
          animation: {
            'float': 'float 6s ease-in-out infinite',
            'float2': 'float 8s ease-in-out infinite 2s',
            'float3': 'float 7s ease-in-out infinite 1s',
          },
          keyframes: {
            float: {
              '0%, 100%': { transform: 'translateY(0px)' },
              '50%': { transform: 'translateY(-20px)' },
            },
          }
        }
      }
    }
  </script>
  <style>
    body { font-family: 'DM Sans', sans-serif; }
    .glass {
      background: rgba(255, 255, 255, 0.06);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255,255,255,0.12);
    }
    .input-field {
      background: rgba(255,255,255,0.07);
      border: 1px solid rgba(167, 139, 250, 0.3);
      transition: all 0.3s ease;
      color: white;
    }
    .input-field::placeholder { color: rgba(196,181,253,0.45); }
    .input-field:focus {
      outline: none;
      border-color: rgba(167, 139, 250, 0.8);
      background: rgba(255,255,255,0.1);
      box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
    }
    .btn-login {
      background: linear-gradient(135deg, #7c3aed, #a78bfa);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    .btn-login::before {
      content: '';
      position: absolute;
      top: 0; left: -100%;
      width: 100%; height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
      transition: left 0.5s ease;
    }
    .btn-login:hover::before { left: 100%; }
    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 30px rgba(124, 58, 237, 0.5);
    }
    .btn-login:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }
    .orb {
      border-radius: 50%;
      filter: blur(70px);
      position: absolute;
      pointer-events: none;
    }
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(24px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .au  { animation: fadeUp 0.65s ease both; }
    .au1 { animation-delay: 0.05s; }
    .au2 { animation-delay: 0.15s; }
    .au3 { animation-delay: 0.25s; }
    .au4 { animation-delay: 0.35s; }
    .au5 { animation-delay: 0.45s; }
  </style>
</head>
<body class="min-h-screen bg-violet-950 flex items-center justify-center p-4 overflow-hidden relative">

  <!-- Background orbs -->
  <div class="orb w-96 h-96 bg-violet-700 opacity-30 animate-float top-[-6rem] left-[-6rem]"></div>
  <div class="orb w-72 h-72 bg-purple-600 opacity-20 animate-float2 bottom-[-4rem] right-[-4rem]"></div>
  <div class="orb w-52 h-52 bg-violet-500 opacity-15 animate-float3 top-[35%] right-[8%]"></div>

  <!-- Grid pattern overlay -->
  <div class="absolute inset-0 opacity-[0.06]" style="background-image: linear-gradient(rgba(167,139,250,0.6) 1px, transparent 1px), linear-gradient(90deg, rgba(167,139,250,0.6) 1px, transparent 1px); background-size: 48px 48px;"></div>

  <!-- Card -->
  <div class="glass rounded-3xl w-full max-w-md p-10 relative z-10">

    <!-- Header -->
    <div class="au au1 flex flex-col items-center mb-10">
      <h1 style="font-family:'Syne',sans-serif;" class="text-white text-3xl font-bold tracking-tight">Login Admin</h1>
      <p class="text-violet-400 text-sm mt-1.5">Masuk ke dashboard administrator</p>
    </div>        

    <!-- Form -->
    <form id="loginForm" class="space-y-5" method="post" autocomplete="off">

      <!-- Username -->
      <div class="au au2">
            <label style="font-family:'Syne',sans-serif;" class="block text-violet-300 text-xs font-semibold uppercase tracking-widest mb-2">Username</label>
            <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-violet-400">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </span>

                <input type="text" name="username" class="input-field w-full pl-10 pr-12 py-3 rounded-xl text-sm" autocomplete="new-username" required>

            </div>
      </div>

      <!-- Password -->
      <div class="au au3">
        <label style="font-family:'Syne',sans-serif;" class="block text-violet-300 text-xs font-semibold uppercase tracking-widest mb-2">Password</label>
        <div class="relative">
          <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-violet-400">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <rect x="3" y="11" width="18" height="11" rx="2"/>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
          </span>

          <input type="password" name="password" class="input-field w-full pl-10 pr-12 py-3 rounded-xl text-sm" autocomplete="new-password" required>

          <button type="button" onclick="togglePw()" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-violet-400 hover:text-violet-200 transition-colors">
            <svg id="eye-icon" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
              <circle cx="12" cy="12" r="3"/>
            </svg>
          </button>
        </div>
      </div>

      <!-- Error message -->
       <?php if (isset($error)): ?>
        <div class="au au4 rounded-xl px-4 py-3 text-sm" style="background:rgba(220,38,38,0.15); border:1px solid rgba(220,38,38,0.3); color:#fca5a5;">
          <?= $error; ?>
        </div>
      <?php endif; ?>
      <div id="error-msg" class="hidden rounded-xl px-4 py-3 text-sm" style="background:rgba(220,38,38,0.15); border:1px solid rgba(220,38,38,0.3); color:#fca5a5;">
        Email atau password salah.
      </div>

      <!-- Submit -->
      <div class="au au5 pt-1">
        <button type="submit" id="btn-submit" name="login" class="btn-login w-full py-3.5 rounded-xl text-white text-sm font-semibold tracking-wide" style="font-family:'Syne',sans-serif;">
          <span id="btn-text">Masuk</span>
        </button>
      </div>
    </form>

    <p class="text-center text-violet-600 text-xs mt-6">
      Akses terbatas &mdash; hanya untuk administrator
    </p>
  </div>
  <script>
    function togglePw() {
        const icon = document.getElementById('eye-icon');
        if (pw.type === 'password') {
            pw.type = 'text';
            icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
        } else {
            pw.type = 'password';
            icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
        }
        }
    </script>
</body>
</html>