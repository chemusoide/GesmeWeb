<!DOCTYPE html>
<html ng-app>
  <head>
    <meta charset="UTF-8">
    <title>@yield('title') | GesmeWeb</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Faivon items -->
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
    <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="/manifest.json">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="/private/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/private/assets/css/bootstrap-extend.min.css">
    <link rel="stylesheet" href="/private/assets/css/site.css">

    <link rel="stylesheet" href="/private/assets/vendor/animsition/animsition.css">
    <link rel="stylesheet" href="/private/assets/vendor/asscrollable/asScrollable.css">
    <link rel="stylesheet" href="/private/assets/vendor/switchery/switchery.css">
    <link rel="stylesheet" href="/private/assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">
    <link rel="stylesheet" href="/private/assets/vendor/imageResizeCrop/component.css">
    <link rel="stylesheet" href="/private/assets/vendor/bootstrap-sweetalert/sweet-alert.css">
    
    <!-- File input -->
    <link rel="stylesheet" href="/private/assets/vendor/blueimp-file-upload/jquery.fileupload.css">

    <!-- Fonts -->
    <link rel="stylesheet" href="/private/assets/fonts/web-icons/web-icons.min.css">
    <link rel="stylesheet" href="/private/assets/fonts/brand-icons/brand-icons.min.css">
    <link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900'>
    <link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Raleway:900,700,500,300,400'>
  
    <!-- Inline -->
    <link rel="stylesheet" href="/private/assets/css/pages/register.css">
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
<body class="page-register layout-full page-dark">
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
  <!-- Plugins For This Page -->
  <script src="/private/assets/vendor/jquery-placeholder/jquery.placeholder.min.js"></script>
  <script src="/private/assets/vendor/formatter-js/jquery.formatter.js"></script>
  <script src="/private/assets/vendor/imageResizeCrop/component.js"></script>

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
  <!-- <script src="/private/assets/js/components/bootstrap-datepicker.min.js"></script>-->
  <script src="/private/assets/js/components/formatter-js.js"></script>
  <!-- Scripts For This Page -->
  <script src="/app/lib/angular/angular.min.js"></script>
  <!-- Custom styles for this page -->
  <script src="@yield('js')"></script>
  <script src="@yield('js1')"></script>
  <script>
    (function(document, window, $) {
      'use strict';

      var Site = window.Site;
      $(document).ready(function() {
        Site.run();
      });

    })(document, window, jQuery);
  </script>

</body>
</html>