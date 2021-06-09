<!DOCTYPE html>
<html ng-app>
  <head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
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
    <link rel="stylesheet" href="/private/assets/vendor/select2/select2.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/css/site.css?<?= $version ?>">

    <link rel="stylesheet" href="/private/assets/vendor/animsition/animsition.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/vendor/asscrollable/asScrollable.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/vendor/switchery/switchery.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/vendor/imageResizeCrop/component.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/vendor/bootstrap-sweetalert/sweet-alert.css?<?= $version ?>">
    
    <!-- File input -->
    <link rel="stylesheet" href="/private/assets/vendor/blueimp-file-upload/jquery.fileupload.css?<?= $version ?>">

    <!-- Fonts -->
    <link rel="stylesheet" href="/private/assets/fonts/web-icons/web-icons.min.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/fonts/brand-icons/brand-icons.min.css?<?= $version ?>">
    <link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900'>
    <link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Raleway:900,700,500,300,400'>
  
    <!-- Inline -->
    <link rel="stylesheet" href="/private/assets/css/pages/register.css?<?= $version ?>">
    @if (trim($__env->yieldContent('css')))
    <link href="@yield('css')?<?= $version ?>" rel="stylesheet">
	@endif
  <!--[if lt IE 9]>
  <script src="/private/assets/vendor/html5shiv/html5shiv.min.js?<?= $version ?>"></script>
  <![endif]-->
  <!--[if lt IE 10]>
  <script src="/private/assets/vendor/media-match/media.match.min.js?<?= $version ?>"></script>
  <script src="/private/assets/vendor/respond/respond.min.js?<?= $version ?>"></script>
  <![endif]-->
  <!-- Scripts -->
  <script src="/private/assets/vendor/modernizr/modernizr.js?<?= $version ?>"></script>
  <script src="/private/assets/vendor/breakpoints/breakpoints.js?<?= $version ?>"></script>
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
  <script src="/private/assets/vendor/jquery/jquery.js?<?= $version ?>"></script>
  <script src="/private/assets/vendor/bootstrap/bootstrap.js?<?= $version ?>"></script>
  <script src="/private/assets/vendor/animsition/jquery.animsition.js?<?= $version ?>"></script>
  <script src="/private/assets/vendor/asscroll/jquery-asScroll.js?<?= $version ?>"></script>
  <script src="/private/assets/vendor/mousewheel/jquery.mousewheel.js?<?= $version ?>"></script>
  <script src="/private/assets/vendor/asscrollable/jquery.asScrollable.all.js?<?= $version ?>"></script>
  <script src="/private/assets/vendor/ashoverscroll/jquery-asHoverScroll.js?<?= $version ?>"></script>

  <!-- Plugins -->
  <script src="/private/assets/vendor/switchery/switchery.min.js?<?= $version ?>"></script>
  <script src="/private/assets/vendor/intro-js/intro.js?<?= $version ?>"></script>
  <script src="/private/assets/vendor/screenfull/screenfull.js?<?= $version ?>"></script>
  <script src="/private/assets/vendor/slidepanel/jquery-slidePanel.js?<?= $version ?>"></script>
  <script src="/private/assets/vendor/sketch/sketch.min.js?<?= $version ?>"></script>
  
  <!-- Plugins For This Page -->
  <script src="/private/assets/vendor/jquery-placeholder/jquery.placeholder.min.js?<?= $version ?>"></script>
  <script src="/private/assets/vendor/formatter-js/jquery.formatter.js?<?= $version ?>"></script>
  <script src="/private/assets/vendor/imageResizeCrop/component.js?<?= $version ?>"></script>
  <script src="/private/assets/vendor/bootstrap-sweetalert/sweet-alert.js?<?= $version ?>"></script>

  <!-- Scripts -->
  <script src="/private/assets/js/core.min.js?<?= $version ?>"></script>
  <script src="/private/assets/js/site.min.js?<?= $version ?>"></script>
 
  <script src="/private/assets/vendor/select2/select2.min.js?<?= $version ?>"></script>
  
  <script src="/private/assets/js/sections/menu.min.js?<?= $version ?>"></script>
  <script src="/private/assets/js/sections/menubar.min.js?<?= $version ?>"></script>
  <script src="/private/assets/js/sections/gridmenu.min.js?<?= $version ?>"></script>
  <script src="/private/assets/js/sections/sidebar.min.js?<?= $version ?>"></script>
  <script src="/private/assets/js/configs/config-colors.min.js?<?= $version ?>"></script>
  <script src="/private/assets/js/configs/config-tour.min.js?<?= $version ?>"></script>
  <script src="/private/assets/js/components/asscrollable.min.js?<?= $version ?>"></script>
  <script src="/private/assets/js/components/animsition.min.js?<?= $version ?>"></script>
  <script src="/private/assets/js/components/slidepanel.min.js?<?= $version ?>"></script>
  <script src="/private/assets/js/components/switchery.min.js?<?= $version ?>"></script>
  <script src="/private/assets/js/components/select2.js?<?= $version ?>"></script>
  
  <!-- <script src="/private/assets/js/components/bootstrap-datepicker.min.js?<?= $version ?>"></script>-->
  <script src="/private/assets/js/components/formatter-js.js?<?= $version ?>"></script>
  <!-- Scripts For This Page -->
  <script src="/app/lib/angular/angular.min.js?<?= $version ?>"></script>
  <!-- Custom styles for this page -->
   <!-- Custom styles for this page -->
  @if (trim($__env->yieldContent('js')))
    <script src="@yield('js')?<?= $version ?>"></script>
  @endif
  
  @if (trim($__env->yieldContent('js1')))
    <script src="@yield('js1')?<?= $version ?>"></script>
  @endif
  
  @if (trim($__env->yieldContent('js2')))
    <script src="@yield('js2')?<?= $version ?>"></script>
  @endif
  
  
  @if (trim($__env->yieldContent('js3')))
    <script src="@yield('js3')?<?= $version ?>"></script>
  @endif
  
  
  @if (trim($__env->yieldContent('js4')))
    <script src="@yield('js4')?<?= $version ?>"></script>
  @endif
  
  
  @if (trim($__env->yieldContent('js5')))
    <script src="@yield('js5')?<?= $version ?>"></script>
  @endif
  
  
  @if (trim($__env->yieldContent('js6')))
    <script src="@yield('js6')?<?= $version ?>"></script>
  @endif
  
  
  @if (trim($__env->yieldContent('js7')))
    <script src="@yield('js7')?<?= $version ?>"></script>
  @endif
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