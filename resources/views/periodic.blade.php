<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Periodic Table</title>
  @vite(['resources/css/periodic.css', 'resources/js/periodic.js'])
</head>
<body>
  <div id="container"></div>

  <div id="menu">
    <button id="table">TABLE</button>
    <button id="sphere">SPHERE</button>
    <button id="helix">HELIX</button>
    <button id="grid">GRID</button>
    <div id="legend">
      <span><i style="background:#EF3022"></i>&lt; $100K</span>
      <span><i style="background:#F7931E"></i>$100K – $200K</span>
      <span><i style="background:#3A9F48"></i>&gt; $200K</span>
    </div>
  </div>

  <form id="logout" method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Sign out ({{ auth()->user()->name }})</button>
  </form>
</body>
</html>
