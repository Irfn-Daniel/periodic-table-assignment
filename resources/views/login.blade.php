<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sign in</title>
  <style>
    body{margin:0;min-height:100vh;display:grid;place-items:center;background:#fff;font-family:Arial,Helvetica,sans-serif;color:#333}
    .card{width:min(440px,90vw);padding:56px 32px;border:1px solid #eee;border-radius:8px;text-align:center;box-shadow:0 2px 12px rgba(0,0,0,.04)}
    h1{font-weight:400;font-size:22px;margin:0 0 12px}
    p{font-size:12px;color:#555;margin:0 0 32px}
    a.btn{display:inline-flex;align-items:center;gap:12px;background:#4285f4;color:#fff;text-decoration:none;font-size:14px;padding:8px 20px 8px 8px;border-radius:4px;box-shadow:0 1px 3px rgba(0,0,0,.2)}
    a.btn:hover{background:#3b78e0}
    .g{width:32px;height:32px;background:#fff;border-radius:3px;display:grid;place-items:center}
  </style>
</head>
<body>
  <div class="card">
    <h1>Welcome Back</h1>
    <p>Sign in with your Google account to view the dashboard.</p>
    <a class="btn" href="{{ route('google.redirect') }}">
      <span class="g">
        <svg width="18" height="18" viewBox="0 0 48 48"><path fill="#EA4335" d="M24 9.5c3.5 0 6.6 1.2 9.1 3.6l6.8-6.8C35.9 2.4 30.4 0 24 0 14.6 0 6.5 5.4 2.6 13.2l7.9 6.1C12.4 13.6 17.7 9.5 24 9.5z"/><path fill="#4285F4" d="M46.5 24.5c0-1.6-.1-3.1-.4-4.5H24v9h12.7c-.6 3-2.3 5.5-4.8 7.2l7.6 5.9c4.4-4.1 7-10.1 7-17.6z"/><path fill="#FBBC05" d="M10.5 28.7c-.5-1.4-.8-3-.8-4.7s.3-3.2.8-4.7l-7.9-6.1C.9 16.5 0 20.1 0 24s.9 7.5 2.6 10.8l7.9-6.1z"/><path fill="#34A853" d="M24 48c6.5 0 11.9-2.1 15.9-5.8l-7.6-5.9c-2.1 1.4-4.9 2.3-8.3 2.3-6.3 0-11.6-4.1-13.5-9.8l-7.9 6.1C6.5 42.6 14.6 48 24 48z"/></svg>
      </span>
      Sign in with Google
    </a>
  </div>
</body>
</html>
