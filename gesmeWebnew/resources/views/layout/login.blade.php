<!DOCTYPE html>
<html ng-app>
  <head>
    <meta charset="UTF-8">
    <title>@yield('title') | Gesmeweb</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Faivon items -->
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="manifest" href="/manifest.json">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="/private/assets/css/bootstrap.min.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/css/bootstrap-extend.min.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/css/site.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/vendor/bootstrap-sweetalert/sweet-alert.css?<?= $version ?>">

    <link rel="stylesheet" href="/private/assets/vendor/animsition/animsition.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/vendor/asscrollable/asScrollable.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/vendor/switchery/switchery.css?<?= $version ?>">
    <!-- Fonts -->
    <link rel="stylesheet" href="/private/assets/fonts/web-icons/web-icons.min.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/fonts/brand-icons/brand-icons.min.css?<?= $version ?>">
    <link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900'>
    <link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Raleway:900,700,500,300,400'>
  
    <!-- Inline -->
    <link rel="stylesheet" href="/private/assets/css/pages/login.css?<?= $version ?>">
  <!--[if lt IE 9]>
  <script src="/private/assets/vendor/html5shiv/html5shiv.min.js"></script>
  <![endif]-->
  <!--[if lt IE 10]>
  <script src="/private/assets/vendor/media-match/media.match.min.js"></script>
  <script src="/private/assets/vendor/respond/respond.min.js"></script>
  <![endif]-->
  <!-- Scripts -->
  <script src="/private/assets/vendor/modernizr/modernizr.js"></script>
  <script src="/private/assets/vendor/breakpoints/breakpoints.js"></script>
  <script>
  Breakpoints();
  </script>
</head>
<body class="page-login layout-full page-dark">
  <!--[if lt IE 8]>
  <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
  <![endif]-->
  <!-- Page -->
  @yield('content')
  <!-- End Page -->
  <!-- Core  -->
  <script src="/private/assets/vendor/jquery/jquery.js"></script>
  <script src="/private/assets/vendor/bootstrap/bootstrap.js"></script>
  <script src="/private/assets/vendor/animsition/jquery.animsition.js"></script>
  <script src="/private/assets/vendor/asscroll/jquery-asScroll.js"></script>
  <script src="/private/assets/vendor/mousewheel/jquery.mousewheel.js"></script>
  <script src="/private/assets/vendor/asscrollable/jquery.asScrollable.all.js"></script>
  <script src="/private/assets/vendor/ashoverscroll/jquery-asHoverScroll.js"></script>

  <!-- Plugins -->
  <script src="/private/assets/vendor/switchery/switchery.min.js"></script>
  <script src="/private/assets/vendor/intro-js/intro.js"></script>
  <script src="/private/assets/vendor/screenfull/screenfull.js"></script>
  <script src="/private/assets/vendor/slidepanel/jquery-slidePanel.js"></script>
  <script src="/private/assets/vendor/bootstrap-sweetalert/sweet-alert.js"></script>
  <!-- Plugins For This Page -->
  <script src="/private/assets/vendor/jquery-placeholder/jquery.placeholder.min.js"></script>
  <!-- Scripts -->
  <script src="/private/assets/js/core.min.js"></script>
  <script src="/private/assets/js/site.min.js"></script>
  <script src="/private/assets/js/sections/menu.min.js"></script>
  <script src="/private/assets/js/sections/menubar.min.js"></script>
  <script src="/private/assets/js/sections/gridmenu.min.js"></script>
  <script src="/private/assets/js/sections/sidebar.min.js"></script>
  <script src="/private/assets/js/configs/config-colors.min.js"></script>
  <script src="/private/assets/js/configs/config-tour.min.js"></script>
  <script src="/private/assets/js/components/asscrollable.min.js"></script>
  <script src="/private/assets/js/components/animsition.min.js"></script>
  <script src="/private/assets/js/components/slidepanel.min.js"></script>
  <script src="/private/assets/js/components/switchery.min.js"></script>
  <script src="/private/assets/js/components/switchery.min.js"></script>
  <!-- Scripts For This Page -->
  <script src="/app/lib/angular/angular.min.js"></script>
  <!-- Custom styles for this page -->
  <script src="@yield('js')?<?= $version ?>"></script>
  <script>
    (function(document, window, $) {
      'use strict';

      var Site = window.Site;
      $(document).ready(function() {
        Site.run();
      });
    })(document, window, jQuery);
  </script>

<script>
  

</script>
</body>
</html>