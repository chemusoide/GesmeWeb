@extends('layout.private.private')
@section('title', 'Dashboard')
@section('css', '/private/assets/css/dashboard/v1.css')

@section('content')
  <!-- Page -->
  <div class="page">
    <div class="page-content padding-30 container-fluid">
      <div class="row" >
        <div class="col-xlg-8 col-md-12">
          <!-- Panel Predictions -->
          <div class="widget widget-shadow widget-responsive" id="usuariosRegistrados">
            <div class="widget-content widget-radius bg-white">
              <div class="padding-top-30 padding-30" style="height:calc(100% - 250px);">
                <div class="row">
                  <div class="col-xs-7">
                    <p class="font-size-20 blue-grey-700">Usuarios Registrados</p>
                    <p>Quisque volutpat condimentum velit. Class aptent taciti</p>
                    <div class="counter counter-md text-left">
                      <div class="counter-number-group">
                        <span class="counter-icon red-600"><i class="icon wb-triangle-up" aria-hidden="true"></i></span>
                        <span class="counter-number red-600">2,250</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-xs-5">
                    <div class="pull-right clearfix">
                      <ul class="list-unstyled">
                        <li class="margin-bottom-5 text-truncate">
                          <i class="icon wb-medium-point green-600 margin-right-5" aria-hidden="true"></i>Usuarios Registrados
                        </li>
                        <li class="margin-bottom-5 text-truncate">
                          <i class="icon wb-medium-point orange-600 margin-right-5" aria-hidden="true"></i>Aceptados
                        </li>
                        <li class="margin-bottom-5 text-truncate">
                          <i class="icon wb-medium-point red-600 margin-right-5" aria-hidden="true"></i>Denegados
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <div class="ct-chart height-250"></div>
            </div>
          </div>
          <!-- End Panel Predictions -->
        </div>

        <div class="col-xlg-4 col-md-12">
          <div class="row height-full">
            <div class="col-xlg-12 col-md-6" style="height:50%;">
              <!-- Panel Today Sale's -->
              <div class="widget widget-shadow" id="widgetLinepoint">
                <div class="widget-content widget-radius bg-blue-600 white">
                  <div class="padding-top-25 padding-horizontal-30" style="height:calc(100% - 80px);">
                    <p>Today Sale's</p>
                    <p class="font-size-30" style="line-height: 1;">450 USD</p>
                    <p class="blue-200">Last Sale 23.45 USD</p>
                  </div>
                  <div class="ct-chart" style="height: 80px;"></div>
                </div>
              </div>
              <!-- End Panel Today Sale's -->
            </div>
            <div class="col-xlg-12 col-md-6" style="height:50%;">
              <!-- Panel Today Sale's -->
              <div class="widget widget-shadow" id="widgetSaleBar">
                <div class="widget-content widget-radius bg-purple-600 white">
                  <div class="padding-top-25 padding-horizontal-30">
                    <div class="row no-space">
                      <div class="col-xs-6">
                        <p>Today Sale's</p>
                        <p class="purple-200">2% higher than last month</p>
                      </div>
                      <div class="col-xs-6 text-right">
                        <p class="font-size-30 text-nowrap">$ 14,500</p>
                      </div>
                    </div>
                  </div>
                  <div class="ct-chart" style="height: 120px;"></div>
                </div>
              </div>
              <!-- End Panel Today Sale's -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End Page -->
    


@stop