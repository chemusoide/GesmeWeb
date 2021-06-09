<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>@yield('title') | private Dpicode</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="apple-touch-icon" href="/private/assets/images/apple-touch-icon.png">
    <link rel="shortcut icon" href="/favicon.ico">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="/private/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/private/assets/css/bootstrap-extend.min.css">
    <link rel="stylesheet" href="/private/assets/css/site.min.css">

    <link rel="stylesheet" href="/private/assets/vendor/animsition/animsition.css">
    <link rel="stylesheet" href="/private/assets/vendor/asscrollable/asScrollable.css">
    <link rel="stylesheet" href="/private/assets/vendor/switchery/switchery.css">
    <link rel="stylesheet" href="/private/assets/vendor/intro-js/introjs.css">
    <link rel="stylesheet" href="/private/assets/vendor/slidepanel/slidePanel.css">
    <link rel="stylesheet" href="/private/assets/vendor/flag-icon-css/flag-icon.css">
    <link rel="stylesheet" href="/private/assets/vendor/bootstrap-markdown/bootstrap-markdown.css">
    <link rel="stylesheet" href="/private/assets/vendor/select2/select2.css">
    <link rel="stylesheet" href="/private/assets/vendor/flag-icon-css/flag-icon.css">

    <!-- Plugin -->
    <!-- <link rel="stylesheet" href="/private/assets/vendor/chartist-js/chartist.css"> -->
    <link rel="stylesheet" href="/private/assets/vendor/jvectormap/jquery-jvectormap.css">
    <link rel="stylesheet" href="/private/assets/vendor/footable/footable.core.css">
    <link rel="stylesheet" href="/private/assets/vendor/jquery-selective/jquery-selective.css">
    <link rel="stylesheet" href="/private/assets/vendor/bootstrap-sweetalert/sweet-alert.css">

    <!-- Plugin tables-->
    <link rel="stylesheet" href="/private/assets/vendor/datatables-bootstrap/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/private/assets/vendor/datatables-fixedheader/dataTables.fixedHeader.css">
    <link rel="stylesheet" href="/private/assets/vendor/datatables-responsive/dataTables.responsive.css">

    <!-- Fonts -->
    <link rel="stylesheet" href="/private/assets/fonts/web-icons/web-icons.min.css">
    <link rel="stylesheet" href="/private/assets/fonts/font-awesome/font-awesome.css">
    <link rel="stylesheet" href="/private/assets/fonts/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/private/assets/fonts/brand-icons/brand-icons.min.css">
    <link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900'>
    <link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Raleway:900,700,500,300,400'>

    <!-- Page -->
    <link href="@yield('css')" rel="stylesheet">
    <link href="@yield('css1')" rel="stylesheet">
    <link href="@yield('css2')" rel="stylesheet">
    <link href="@yield('css3')" rel="stylesheet">

    <!-- Custom Styles for site -->
    <link rel="stylesheet" href="/private/assets/css/aula-virtual.css" media="all">


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
    function matarSession(){

         $.ajax({
              url:   '/private/mataSesiones',
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
  <body class="">

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

  <script src="/private/assets/vendor/skycons/skycons.js"></script>
  <!-- <script src="/private/assets/vendor/chartist-js/chartist.min.js"></script> -->
  <script src="/private/assets/vendor/aspieprogress/jquery-asPieProgress.min.js"></script>
  <script src="/private/assets/vendor/bootstrap-sweetalert/sweet-alert.js"></script>
  <script src="/private/assets/vendor/bootstrap-markdown/bootstrap-markdown.js"></script>
  <script src="/private/assets/vendor/to-markdown/to-markdown.js"></script>
  <script src="/private/assets/vendor/marked/marked.js"></script>

  <script src="/private/assets/vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="/private/assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>
  <script src="/private/assets/vendor/datatables-responsive/dataTables.responsive.js"></script>

  <!-- Scripts -->
  <script src="/private/assets/js/core.js"></script>
  <script src="/private/assets/js/site.js"></script>

  <script src="/private/assets/js/sections/menu.js"></script>
  <script src="/private/assets/js/sections/menubar.js"></script>
  <script src="/private/assets/js/sections/sidebar.js"></script>

  <script src="/private/assets/js/configs/config-colors.js"></script>
  <script src="/private/assets/js/configs/config-tour.js"></script>

  <script src="/private/assets/vendor/select2/select2.min.js"></script>
  <script src="/private/assets/js/components/asscrollable.js"></script>
  <script src="/private/assets/js/components/animsition.js"></script>
  <script src="/private/assets/js/components/slidepanel.js"></script>
  <script src="/private/assets/js/components/switchery.js"></script>
  <script src="/private/assets/js/components/matchheight.js"></script>
  <script src="/private/assets/js/components/jvectormap.js"></script>
  <script src="/private/assets/js/components/bootstrap-sweetalert.js"></script>
  <script src="/private/assets/js/components/select2.js"></script>
  <script src="/private/assets/js/components/buttons.js"></script>
  <script src="/private/assets/js/components/datatables.js"></script>

    

  <!-- <script src="/js/app/private/indexbackend.js"></script> -->

  <script>

  function crearMenu(rol){
        if(rol =='Administrador' || rol =='SuperAdministrador'){
          //Menu lateral
          $('#menuLeft').append('<li class="site-menu-item" name = "liMenuUsr">' 
            +'<a class="animsition-link" href="/private/listado-usuarios" data-slug="app-users">'
              +'<i class="site-menu-icon wb-user" aria-hidden="true"></i>'
             +' <span class="site-menu-title">Usuarios</span>'
            +'</a>'
          +'</li>');

          $('#menuLeft').append('<li class="site-menu-item" name = "liMenuUsr">' 
                  +'<a class="animsition-link" href="/private/listado-usuarios" data-slug="app-users">'
                    +'<i class="site-menu-icon wb-user" aria-hidden="true"></i>'
                   +' <span class="site-menu-title">Usuarios</span>'
                  +'</a>'
                +'</li>');
          


           //Menu Pequeño

            $('#menuPeque').append('<li><a href="/private/listado-usuarios"><i class="icon wb-user"></i><span>Usuarios</span></a></li>');
        }
          
      }

      function obtenerDatos(){

          console.info('AKI');
         if(localStorage.getItem("src")){
                //$('#imgHead').attr('src', localStorage.getItem("data.src"));
                crearMenu( localStorage.getItem("data.rolUsr"));
          }else{
             $.ajax({
              url:   '/private/obtenerDatosUsuarioSession',
              type:  'GET',
              dataType: 'json',
              success:  function (data) {

                  if(data){
                    localStorage.setItem("data", 'data:image/jpeg;base64,'+data.usuario.urlFot);
                    localStorage.setItem("data.rolUsr", data.usuario.rolusr);
                   // $('#imgHead').attr('src', 'data:image/jpeg;base64,'+data.usuario.urlFot );
                    crearMenu(data.usuario.rolusr);
                   
                     return data;
                  }
              }
             });
          }
       
    }
    obtenerDatos();
    $(document).ready(function($) {
      Site.run();

      // Table Tools
      // -----------
      (function() {
        $(document).ready(function() {
          var defaults = $.components.getDefaults("dataTable");

          var options = $.extend(true, {}, defaults, {
            "aoColumnDefs": [{
              'bSortable': false,
              'aTargets': [-1]
            }],
            "iDisplayLength": 5,
            "aLengthMenu": [
              [5, 10, 25, 50, -1],
              [5, 10, 25, 50, "All"]
            ],
            "sDom": '<"dt-panelmenu clearfix"Tfr>t<"dt-panelfooter clearfix"ip>',
            "oTableTools": {
              "sSwfPath": "/private/assets/vendor/datatables-tabletools/swf/copy_csv_xls_pdf.swf"
            }
          });

          $('#exampleTableTools').dataTable(options);
        });
      })();

      (function() {
        $('.deleteProject').on("click", function() {
          swal({
              title: "¿Estás seguro de borrar?",
              text: "No podrás recuperar esta información!",
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: '#DD6B55',
              confirmButtonText: 'Si, borrarlo!',
              cancelButtonText: "No, no quiero!",
              closeOnConfirm: false,
              closeOnCancel: false
            },
            function(isConfirm) {
              if (isConfirm) {
                swal("Que le den!!!",
                  "El proyecto ha sido borrado!",
                  "success");
              } else {
                swal("Vaya susto!", "No has borrado el proyecto",
                  "error");
              }
            });
        });

        $('.projectCreated').on("click", function() {
          swal({
            title: "Seeee!",
            text: "Proyecto creado!",
            imageUrl: 'http://i.imgur.com/4NZ6uLY.jpg',
            timer: 3000,
          });
        });

        $('.claveCreated').on("click", function() {
          swal({
            title: "Yeah!",
            text: "Clave creada!",
            imageUrl: 'http://img4.wikia.nocookie.net/__cb20140618015402/clubpenguin/es/images/7/74/Emoticon_meme_Aww_Yeah.png',
            timer: 3000,
          });
        });
      })();
    });
  </script>

  <!-- Custom styles for this page -->
  <script src="@yield('js')"></script>
  <script src="@yield('js1')"></script>
  <script src="@yield('js2')"></script>
  <script src="@yield('js3')"></script>
  <script src="@yield('js4')"></script>





 
  </body>
</html>