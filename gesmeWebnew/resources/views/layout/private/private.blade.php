<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="apple-touch-icon" href="/private/assets/images/apple-touch-icon.png">
    <link rel="shortcut icon" href="/favicon.ico">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="/private/assets/css/bootstrap.min.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/css/bootstrap-extend.min.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/css/calendar.min.css?<?= $version ?>">
  
    
    <link rel="stylesheet" href="/private/assets/css/site.min.css?<?= $version ?>">

    <link rel="stylesheet" href="/private/assets/vendor/animsition/animsition.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/vendor/asscrollable/asScrollable.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/vendor/switchery/switchery.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/vendor/intro-js/introjs.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/vendor/slidepanel/slidePanel.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/vendor/flag-icon-css/flag-icon.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/vendor/summernote/summernote.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/vendor/select2/select2.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/vendor/flag-icon-css/flag-icon.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/vendor/fullcalendar/fullcalendar.min.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css?<?= $version ?>">

    <!-- Plugin -->
    <!-- <link rel="stylesheet" href="/private/assets/vendor/chartist-js/chartist.css?<?= $version ?>"> -->
    <link rel="stylesheet" href="/private/assets/vendor/jvectormap/jquery-jvectormap.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/vendor/footable/footable.core.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/vendor/jquery-selective/jquery-selective.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/vendor/bootstrap-sweetalert/sweet-alert.css?<?= $version ?>">

    <!-- Plugin tables-->
    <link rel="stylesheet" href="/private/assets/vendor/datatables-bootstrap/dataTables.bootstrap.css?<?= $version ?>">
    
    <link rel="stylesheet" href="/private/assets/vendor/datatables-fixedheader/dataTables.fixedHeader.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/vendor/datatables-responsive/dataTables.responsive.css?<?= $version ?>">

    <!-- Fonts -->
    <link rel="stylesheet" href="/private/assets/fonts/web-icons/web-icons.min.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/fonts/font-awesome/font-awesome.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/fonts/font-awesome/font-awesome.min.css?<?= $version ?>">
    <link rel="stylesheet" href="/private/assets/fonts/brand-icons/brand-icons.min.css?<?= $version ?>">
    <link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900'>
    <link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Raleway:900,700,500,300,400'>

    <!-- Page -->
    @if (trim($__env->yieldContent('css')))
    <link href="@yield('css')?<?= $version ?>" rel="stylesheet">
	@endif
    @if (trim($__env->yieldContent('css1')))
    <link href="@yield('css1')?<?= $version ?>" rel="stylesheet">
	@endif
	@if (trim($__env->yieldContent('css2')))
    <link href="@yield('css2')?<?= $version ?>" rel="stylesheet">
	@endif
	@if (trim($__env->yieldContent('css3')))
    <link href="@yield('css3')?<?= $version ?>" rel="stylesheet">
	@endif


    <!-- Custom Styles for site -->
    <link rel="stylesheet" href="/private/assets/css/cpq.css?<?= $version ?>" media="all">


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
    function redirectPerfil(){
    	window.location.href = generarUrl('/private/perfil');
    }

    function matarSession(){
        $.ajax({
             url:   generarUrl('/private/mataSesiones') ,
             type:  'GET',
             dataType: 'json',
             success:  function (data) {
                 localStorage.clear();
                 location.reload();
             }
            });
     }
    
    Breakpoints();

  </script>
</head>
  <body class="site-menubar-fold">

      @include('layout.private.header') 

      @include('layout.private.sidebar') 
      
      @yield('content')


    <!-- Modal Modificar -->
    <div class="modal fade" id="fmod" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" onclick = "javascript:modTextoPaginaCerrar()" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><span  id="formModLabel"></span></h4>
            </div>
            <div class="modal-body">
              <form class="form">

                <div role="tabpanel" class="tabs-idiomas">

                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#es" aria-controls="español" role="tab" data-toggle="tab">Español</a></li>
                    <li role="presentation"><a href="#en" aria-controls="english" role="tab" data-toggle="tab">Inglés</a></li>
                    <li role="presentation"><a href="#cat" aria-controls="catalan" role="tab" data-toggle="tab">Catalán</a></li>
                  </ul>

                  <!-- Tab panes -->
                  <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="es">
                      <span id="formModId" style="display:none;"></span>
                      <textarea id="modArea" class="form-control editorTexto" style="resize:vertical;" rows="3" placeholder="Introduzca el texto que desea para esta sección"></textarea>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="en">
                      <span id="formModIdEN" style="display:none;"></span>
                      <textarea id="modAreaEN" class="form-control editorTexto" style="resize:vertical;" rows="3"></textarea>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="cat">
                      <span id="formModIdCAT" style="display:none;"></span>
                      <textarea id="modAreaCAT" class="form-control editorTexto" style="resize:vertical;" rows="3"></textarea>
                    </div>
                      
                  </div>
                </div>
                <div class="form-group video_url" id='divUrl'>
                  <label>URL del Video: </label>
                  <input type="text" id="urlVideo" class="form-control" placeholder="Url del video para el tratamiento">
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-success" onclick = "javascript:modTextoPagina()">Guardar todo</button>
              <button type="button" class="btn btn-default" onclick="document.getElementById('modArea').value = '';">Limpiar</button>
              <button type="button" class="btn btn-danger" onclick = "javascript:modTextoPaginaCerrar()" data-dismiss="modal">Cancelar</button>
            </div>
          </div>
        </div>
    </div>
      

      @include('layout.private.footer') 
      
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
  <script src="/private/assets/vendor/formatter-js/jquery.formatter.js?<?= $version ?>"></script>
  <script src="/private/assets/vendor/slidepanel/jquery-slidePanel.js?<?= $version ?>"></script>
  
  
  
  

  <script src="/private/assets/vendor/skycons/skycons.js?<?= $version ?>"></script>
  <!-- <script src="/private/assets/vendor/chartist-js/chartist.min.js?<?= $version ?>"></script> -->
  <script src="/private/assets/vendor/aspieprogress/jquery-asPieProgress.min.js?<?= $version ?>"></script>
  <script src="/private/assets/vendor/bootstrap-sweetalert/sweet-alert.js?<?= $version ?>"></script>
  <script src="/private/assets/vendor/summernote/summernote.min.js?<?= $version ?>"></script>
  <script src="/private/assets/vendor/marked/marked.js?<?= $version ?>"></script>

  <script src="/private/assets/vendor/datatables/jquery.dataTables.min.js?<?= $version ?>"></script>
  <script src="/private/assets/vendor/datatables-bootstrap/dataTables.bootstrap.js?<?= $version ?>"></script>
  <script src="/private/assets/vendor/datatables-responsive/dataTables.responsive.js?<?= $version ?>"></script>

  <!-- Scripts -->
  <script src="/private/assets/js/core.js?<?= $version ?>"></script>
  <script src="/private/assets/js/site.js?<?= $version ?>"></script>

  <script src="/private/assets/js/sections/menu.js?<?= $version ?>"></script>
  <script src="/private/assets/js/sections/menubar.js?<?= $version ?>"></script>
  <script src="/private/assets/js/sections/sidebar.js?<?= $version ?>"></script>

  <script src="/private/assets/js/configs/config-colors.js?<?= $version ?>"></script>
  <script src="/private/assets/js/configs/config-tour.js?<?= $version ?>"></script>

  <script src="/private/assets/vendor/select2/select2.min.js?<?= $version ?>"></script>
  <script src="/private/assets/js/components/asscrollable.js?<?= $version ?>"></script>
  <script src="/private/assets/js/components/animsition.js?<?= $version ?>"></script>
  <script src="/private/assets/js/components/slidepanel.js?<?= $version ?>"></script>
  <script src="/private/assets/js/components/switchery.js?<?= $version ?>"></script>
  <script src="/private/assets/js/components/summernote.js?<?= $version ?>"></script>
  <script src="/private/assets/js/components/matchheight.js?<?= $version ?>"></script>
  <script src="/private/assets/js/components/jvectormap.js?<?= $version ?>"></script>
  <script src="/private/assets/js/components/bootstrap-sweetalert.js?<?= $version ?>"></script>
  <script src="/private/assets/js/components/select2.js?<?= $version ?>"></script>
  <script src="/private/assets/js/components/buttons.js?<?= $version ?>"></script>
  <script src="/private/assets/js/components/datatables.js?<?= $version ?>"></script>
  <script src="/private/assets/js/components/formatter-js.js?<?= $version ?>"></script>

  <script src="/private/assets/vendor/moment/moment.min.js?<?= $version ?>"></script>
  <script src="/private/assets/vendor/fullcalendar/fullcalendar.min.js?<?= $version ?>"></script>
  
  <script src="/private/assets/js/components/bootstrap-datepicker.js?<?= $version ?>"></script>
  
  

  <script src="/js/app/private/general.js?<?= $version ?>"></script>
  
  <script src="/private/assets/js/apps/app.min.js?<?= $version ?>"></script>
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

  </body>
</html>