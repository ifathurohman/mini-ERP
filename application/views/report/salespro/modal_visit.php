<!-- Modal -->
<div id="modal-visit" class="modal modal-primary fade modal-fade-in-scale-up" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" style="margin-top: 20px;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
        <!-- <form id="form" autocomplete="off">
          <input type="hidden" name="TransactionRouteID">
          <div class="row">
            <div class="form-group col-sm-12">
              <label class="control-label">Search</label>
              <input name="pac-input" id="pac-input" type="text" class="form-control disabled">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <div id="map" style="height: 500px;width: 100%"></div>
            </div>
          </div>
        </form> -->
        <div class="row">
          <div class="col-sm-5 col-routing_sales">
            <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#home">Customer Route</a></li>
              <li><a data-toggle="tab" href="#menu1">Route Segment</a></li>
            </ul>
            <div class="tab-content padding-10">
              <div id="home" class="tab-pane fade in active">
                <div id="directions-panel2" style="overflow-y: scroll;max-height: 500px;">
                  <ul class="list-route-customer"></ul>
                </div>
              </div>
              <div id="menu1" class="tab-pane fade">
                <h3>Total Distance : <span id="TotalKM"></span></h3>
                <div id="directions-panel" style="overflow-y: scroll;max-height: 500px;min-height: 500px;"></div>       
              </div>
            </div>    
          </div>
          <div class="col-sm-5 col-sales_visit">
            <input type="hidden" name="radius" />
            <h5>Info Detail</h5>
            <table class="table table-strip table-hover">
              <tr>
                <td style="width: 35%">Transaction No</td>
                <td><div id="vCode"></div></td>
              </tr>
               <tr>
                <td>Date</td>
                <td><div id="vDate"></div></td>
              </tr>
              <tr>
                <td>Sales</td>
                <td>
                  <div id="vSalesName"></div>
                </td>
              </tr>
              <tr>
                <td>Customer</td>
                <td>
                  
                  <b id="vCustomerName"></b>
                  <div id="vCustomerAddress"></div>
                </td>
              </tr>
              <tr>
                <td>Check In</td>
                <td>
                  <b id="vCheckInDate"></b>
                  <div id="vCheckInAddress"></div>
                </td>
              </tr>
              <tr>
                <td>Check Out</td>
                <td>
                  <b id="vCheckOutDate"></b>
                  <div id="vCheckOutAddress"></div>
                </td>
              </tr>
              <tr>
                <td>Duration</td>
                <td><div id="vDuration"></div></td>
              </tr>
              <tr>
                <td>Customer Place</td>
                <td><a id="vCustomerPlaceLink" target="_blank"> <img id="vCustomerPlace" style="height: 200px;"></a></td>
              </tr>
            </table>
            <!-- <div id="directions-panel" style="overflow-y: scroll;max-height: 500px;min-height: 500px;"></div>         -->
          </div>
          <div class="col-sm-7" id="">
            <div id="map" style="height: 500px;width: 100%"></div>
            <div id="info-marker-visit">
              <ul class="ul-line-info">
                <li>
                  <img class="radius-red" style="display: inline-block;" /> <span>Customer Radius</span>
                </li>
                <li>
                  <img src="<?= base_url('img/icon/marker-red.svg'); ?>" style="height: 30px"> Customer Location
                </li>
                <li>
                  <img src="<?= base_url('img/icon/marker-blue.svg'); ?>" style="height: 30px"> Check In
                </li>
              </ul>
            </div>      
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button type="button" class="btn btn-outline btn-default margin-0 kotak" data-dismiss="modal">Close</button>     
        </div>
      </div>
    </div>
  </div>
</div>