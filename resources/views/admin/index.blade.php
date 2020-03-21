<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="../favicon.ico">
    <title>后台管理</title>
    <link rel="stylesheet" href="/css/custom.css">
  </head>
  <body>
      <div id="app"></div>

      <script src="/static/config/index.js"></script>
      <script src="{{ mix('js/manifest.js') }}"></script>
      <script src="{{ mix('js/vendor.js') }}"></script>
      <script src="{{ mix('js/app.js') }}"></script>
  </body>
</html>
