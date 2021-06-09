@extends('layout.default')

@section('title', 'Error')
@section('css', '/css/acadManu_404.css')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="error-template text-center">
                    <div class="text-in-bg clearfix">
                        <span class="bigText">404</span>
                        <h2>Vaya, esta página no existe</h2>
                    </div>
                    <a href="/" class="btn btn-primary">Ir a inicio</a>
                </div>
            </div>
        </div>
    </div><!-- /.content -->


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
                <button type="button" class="btn btn-black pull-left" data-dismiss="modal">Cancelar</button> 
                <button type="button" class="btn btn-primary ">Enviar</button>
              </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal compose message -->

@stop

@section('js', './js/app/blog/blog.js')
