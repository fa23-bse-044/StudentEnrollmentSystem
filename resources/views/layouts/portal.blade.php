<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CUI Portal Framework</title>
    <!-- Embedded high fidelity compilation export canvas engine -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        body { background: #f1f5f9; min-height: 100vh; font-family: 'Segoe UI', sans-serif; margin: 0; }
        .main-content { padding: 30px; max-width: 1300px; margin: 0 auto; }
        .panel-card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.02); margin-bottom: 25px;}
        .result-table { width: 100%; border-collapse: collapse; margin-top: 15px;}
        .result-table th, .result-table td { border: 1px solid #e2e8f0; padding: 12px; text-align: center; font-size: 14px; }
        .result-table th { background-color: #f8fafc; color: #334155; }
        .alert-error { background:#ef4444; color:white; padding:12px; border-radius:6px; margin-bottom:15px; font-weight:bold; }
    </style>
</head>
<body>
    <main class="main-content">
        @yield('content')
    </main>
</body>
</html>
