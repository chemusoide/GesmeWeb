@extends('layout.private.private')

@section('title', '404 Página no encontrada')

@section('content')
    <!-- Right side column. Contains the navbar and content of the page -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
        <h1>
        Página no encontrada 
        </h1>
        <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Página no encontrada</li>
        </ol>
        </section>

        <!-- Main content -->
        <section class="content">
        <div class="row">
        <div class="col-md-12">
            <div class="error-template">
                <h1>
                    Oops!</h1>
                <h2>
                    404 Página no encontrada</h2>
                <div class="error-details">
                    Vaya, parece que ha ocurrido un error, no encontramos la página solicitada!
                </div>
                <div class="error-actions">
                    <a href="./index" class="btn btn-primary btn-lg">
                        Ir a inicio 
                    </a>
                    <a href="#mailContacto" data-toggle="modal" class="btn btn-default btn-lg">
                        <span class="fa fa-envelope"></span> Contacta con soporte 
                    </a>
                </div>
            </div>
        </div>
        </div>
        </section><!-- /.content -->

    </div><!-- /.content-wrapper -->

    <!-- Modal borrar -->
    <div class="modal fade" id="mailContacto">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Enviar mensaje</h4>
              </div>
              <div class="modal-body">
                <form role="form" class="form-horizontal">
                  <div class="form-group">
                    <label class="col-sm-2" for="inputTo">Para</label>
                    <div class="col-sm-10">hola@dpicode.com</div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2" for="inputSubject">Asunto</label>
                    <div class="col-sm-10"><input class="form-control" id="inputSubject" placeholder="Subject" value="Página no encontrada" type="text"></div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-12" for="inputBody">Mensaje</label>
                    <div class="col-sm-12"><textarea class="form-control" id="inputBody" rows="8"></textarea></div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button> 
                <button type="button" class="btn btn-primary ">Enviar</button>
              </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal compose message -->

@stop
