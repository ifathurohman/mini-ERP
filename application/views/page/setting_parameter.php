<div class="page page-data" data-hakakses="<?= $this->session->hak_akses; ?>">
  <div class="page-header">
  </div>
  <div class="page-content">
    <div class="panel panel-bordered">
      <header class="panel-heading">
        <div class="panel-actions"></div>
        <h3 class="panel-title"><?= $title; ?></h3>
      </header>
      <div class="panel-body">
        <div class="row">
          <div class="col-sm-12">
            <form id="form-company" autocomplete="off">
                <input type="hidden" name="settingparameterid">
              <div class="row">
                <div class="form-group col-sm-4">
                  <label class="control-label active"><?= $this->lang->line('lb_currency'); ?> <span class="wajib"></span></label>
                  <select id="currency" name="currency" class="form-control">
                    <option value="AFN">Afghanistan Afghani (AFN)</option>
                    <option value="ALL">Albania Lek (ALL)</option>
                    <option value="DZD">Algeria Dinar (DZD)</option>
                    <option value="AOA">Angola Kwanza (AOA)</option>
                    <option value="ARS">Argentina Peso (ARS)</option>
                    <option value="AMD">Armenia Dram (AMD)</option>
                    <option value="AWG">Aruba Guilder (AWG)</option>
                    <option value="AUD">Australia Dollar (AUD)</option>
                    <option value="AZN">Azerbaijan New Manat (AZN)</option>
                    <option value="BSD">Bahamas Dollar (BSD)</option>
                    <option value="BHD">Bahrain Dinar (BHD)</option>
                    <option value="BDT">Bangladesh Taka (BDT)</option>
                    <option value="BBD">Barbados Dollar (BBD)</option>
                    <option value="BYN">Belarus Ruble (BYN)</option>
                    <option value="BZD">Belize Dollar (BZD)</option>
                    <option value="BMD">Bermuda Dollar (BMD)</option>
                    <option value="BTN">Bhutan Ngultrum (BTN)</option>
                    <option value="BOB">Bolivia Bolíviano (BOB)</option>
                    <option value="BAM">Bosnia and Herzegovina Convertible Marka (BAM)</option>
                    <option value="BWP">Botswana Pula (BWP)</option>
                    <option value="BRL">Brazil Real (BRL)</option>
                    <option value="BND">Brunei Darussalam Dollar (BND)</option>
                    <option value="BGN">Bulgaria Lev (BGN)</option>
                    <option value="BIF">Burundi Franc (BIF)</option>
                    <option value="KHR">Cambodia Riel (KHR)</option>
                    <option value="CAD">Canada Dollar (CAD)</option>
                    <option value="CVE">Cape Verde Escudo (CVE)</option>
                    <option value="KYD">Cayman Islands Dollar (KYD)</option>
                    <option value="CLP">Chile Peso (CLP)</option>
                    <option value="CNY">China Yuan Renminbi (CNY)</option>
                    <option value="COP">Colombia Peso (COP)</option>
                    <option value="XOF">Communauté Financière Africaine (BCEAO) Franc (XOF)</option>
                    <option value="XAF">Communauté Financière Africaine (BEAC) CFA Franc BEAC (XAF)</option>
                    <option value="KMF">Comoros Franc (KMF)</option>
                    <option value="XPF">Comptoirs Français du Pacifique (CFP) Franc (XPF)</option>
                    <option value="CDF">Congo/Kinshasa Franc (CDF)</option>
                    <option value="CRC">Costa Rica Colon (CRC)</option>
                    <option value="HRK">Croatia Kuna (HRK)</option>
                    <option value="CUC">Cuba Convertible Peso (CUC)</option>
                    <option value="CUP">Cuba Peso (CUP)</option>
                    <option value="CZK">Czech Republic Koruna (CZK)</option>
                    <option value="DKK">Denmark Krone (DKK)</option>
                    <option value="DJF">Djibouti Franc (DJF)</option>
                    <option value="DOP">Dominican Republic Peso (DOP)</option>
                    <option value="XCD">East Caribbean Dollar (XCD)</option>
                    <option value="EGP">Egypt Pound (EGP)</option>
                    <option value="SVC">El Salvador Colon (SVC)</option>
                    <option value="ERN">Eritrea Nakfa (ERN)</option>
                    <option value="ETB">Ethiopia Birr (ETB)</option>
                    <option value="EUR">Euro Member Countries (EUR)</option>
                    <option value="FKP">Falkland Islands (Malvinas) Pound (FKP)</option>
                    <option value="FJD">Fiji Dollar (FJD)</option>
                    <option value="GMD">Gambia Dalasi (GMD)</option>
                    <option value="GEL">Georgia Lari (GEL)</option>
                    <option value="GHS">Ghana Cedi (GHS)</option>
                    <option value="GIP">Gibraltar Pound (GIP)</option>
                    <option value="GTQ">Guatemala Quetzal (GTQ)</option>
                    <option value="GGP">Guernsey Pound (GGP)</option>
                    <option value="GNF">Guinea Franc (GNF)</option>
                    <option value="GYD">Guyana Dollar (GYD)</option>
                    <option value="HTG">Haiti Gourde (HTG)</option>
                    <option value="HNL">Honduras Lempira (HNL)</option>
                    <option value="HKD">Hong Kong Dollar (HKD)</option>
                    <option value="HUF">Hungary Forint (HUF)</option>
                    <option value="ISK">Iceland Krona (ISK)</option>
                    <option value="INR">India Rupee (INR)</option>
                    <option value="IDR" selected="selected">Indonesia Rupiah (IDR)</option>
                    <option value="XDR">International Monetary Fund (IMF) Special Drawing Rights (XDR)</option>
                    <option value="IRR">Iran Rial (IRR)</option>
                    <option value="IQD">Iraq Dinar (IQD)</option>
                    <option value="IMP">Isle of Man Pound (IMP)</option>
                    <option value="ILS">Israel Shekel (ILS)</option>
                    <option value="JMD">Jamaica Dollar (JMD)</option>
                    <option value="JPY">Japan Yen (JPY)</option>
                    <option value="JEP">Jersey Pound (JEP)</option>
                    <option value="JOD">Jordan Dinar (JOD)</option>
                    <option value="KZT">Kazakhstan Tenge (KZT)</option>
                    <option value="KES">Kenya Shilling (KES)</option>
                    <option value="KSH">Kenyan Shilling (KSH)</option>
                    <option value="KPW">Korea (North) Won (KPW)</option>
                    <option value="KRW">Korea (South) Won (KRW)</option>
                    <option value="KWD">Kuwait Dinar (KWD)</option>
                    <option value="KGS">Kyrgyzstan Som (KGS)</option>
                    <option value="LAK">Laos Kip (LAK)</option>
                    <option value="LBP">Lebanon Pound (LBP)</option>
                    <option value="LSL">Lesotho Loti (LSL)</option>
                    <option value="LRD">Liberia Dollar (LRD)</option>
                    <option value="LYD">Libya Dinar (LYD)</option>
                    <option value="MOP">Macau Pataca (MOP)</option>
                    <option value="MKD">Macedonia Denar (MKD)</option>
                    <option value="MGA">Madagascar Ariary (MGA)</option>
                    <option value="MWK">Malawi Kwacha (MWK)</option>
                    <option value="MYR">Malaysia Ringgit (MYR)</option>
                    <option value="MVR">Maldives (Maldive Islands) Rufiyaa (MVR)</option>
                    <option value="MRO">Mauritania Ouguiya (MRO)</option>
                    <option value="MUR">Mauritius Rupee (MUR)</option>
                    <option value="MXN">Mexico Peso (MXN)</option>
                    <option value="MDL">Moldova Leu (MDL)</option>
                    <option value="MNT">Mongolia Tughrik (MNT)</option>
                    <option value="MAD">Morocco Dirham (MAD)</option>
                    <option value="MZN">Mozambique Metical (MZN)</option>
                    <option value="MMK">Myanmar (Burma) Kyat (MMK)</option>
                    <option value="NAD">Namibia Dollar (NAD)</option>
                    <option value="NPR">Nepal Rupee (NPR)</option>
                    <option value="ANG">Netherlands Antilles Guilder (ANG)</option>
                    <option value="NTD">New Taiwan Dollar (NTD)</option>
                    <option value="NZD">New Zealand Dollar (NZD)</option>
                    <option value="NIO">Nicaragua Cordoba (NIO)</option>
                    <option value="NGN">Nigeria Naira (NGN)</option>
                    <option value="NOK">Norway Krone (NOK)</option>
                    <option value="OMR">Oman Rial (OMR)</option>
                    <option value="PKR">Pakistan Rupee (PKR)</option>
                    <option value="PAB">Panama Balboa (PAB)</option>
                    <option value="PGK">Papua New Guinea Kina (PGK)</option>
                    <option value="PYG">Paraguay Guarani (PYG)</option>
                    <option value="PEN">Peru Sol (PEN)</option>
                    <option value="PHP">Philippines Peso (PHP)</option>
                    <option value="PLN">Poland Zloty (PLN)</option>
                    <option value="QAR">Qatar Riyal (QAR)</option>
                    <option value="RON">Romania New Leu (RON)</option>
                    <option value="RUB">Russia Ruble (RUB)</option>
                    <option value="RWF">Rwanda Franc (RWF)</option>
                    <option value="SHP">Saint Helena Pound (SHP)</option>
                    <option value="WST">Samoa Tala (WST)</option>
                    <option value="STD">São Tomé and Príncipe Dobra (STD)</option>
                    <option value="SAR">Saudi Arabia Riyal (SAR)</option>
                    <option value="SPL">Seborga Luigino (SPL)</option>
                    <option value="RSD">Serbia Dinar (RSD)</option>
                    <option value="SCR">Seychelles Rupee (SCR)</option>
                    <option value="SLL">Sierra Leone Leone (SLL)</option>
                    <option value="SGD">Singapore Dollar (SGD)</option>
                    <option value="SBD">Solomon Islands Dollar (SBD)</option>
                    <option value="SOS">Somalia Shilling (SOS)</option>
                    <option value="ZAR">South Africa Rand (ZAR)</option>
                    <option value="LKR">Sri Lanka Rupee (LKR)</option>
                    <option value="SDG">Sudan Pound (SDG)</option>
                    <option value="SRD">Suriname Dollar (SRD)</option>
                    <option value="SZL">Swaziland Lilangeni (SZL)</option>
                    <option value="SEK">Sweden Krona (SEK)</option>
                    <option value="CHF">Switzerland Franc (CHF)</option>
                    <option value="SYP">Syria Pound (SYP)</option>
                    <option value="TWD">Taiwan New Dollar (TWD)</option>
                    <option value="TJS">Tajikistan Somoni (TJS)</option>
                    <option value="TZS">Tanzania Shilling (TZS)</option>
                    <option value="THB">Thailand Baht (THB)</option>
                    <option value="TOP">Tonga Paanga (TOP)</option>
                    <option value="TTD">Trinidad and Tobago Dollar (TTD)</option>
                    <option value="TND">Tunisia Dinar (TND)</option>
                    <option value="TRY">Turkey Lira (TRY)</option>
                    <option value="TMT">Turkmenistan Manat (TMT)</option>
                    <option value="TVD">Tuvalu Dollar (TVD)</option>
                    <option value="UGX">Uganda Shilling (UGX)</option>
                    <option value="UAH">Ukraine Hryvnia (UAH)</option>
                    <option value="AED">United Arab Emirates Dirham (AED)</option>
                    <option value="GBP">United Kingdom Pound (GBP)</option>
                    <option value="USD">United States Dollar (USD)</option>
                    <option value="UYU">Uruguay Peso (UYU)</option>
                    <option value="UZS">Uzbekistan Som (UZS)</option>
                    <option value="VUV">Vanuatu Vatu (VUV)</option>
                    <option value="VEF">Venezuela Bolivar (VEF)</option>
                    <option value="VND">Viet Nam Dong (VND)</option>
                    <option value="CFA">West &amp;amp; Central Africa Franc (CFA)</option>
                    <option value="YER">Yemen Rial (YER)</option>
                    <option value="ZMW">Zambia Kwacha (ZMW)</option>
                    <option value="ZWD">Zimbabwe Dollar (ZWD)</option>
                  </select>
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-4">
                  <label class="control-label active"><?= $this->lang->line('lb_amount_digint'); ?> <span class="wajib"></span></label>
                  <select class="form-control" name="amountdecimal" id="amountdecimal">
                      <option value="0">0</option>
                      <option value="2">2</option>
                      <option value="4">4</option>
                  </select>
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-4">
                  <label class="control-label active"><?= $this->lang->line('lb_decimal_digit'); ?> <span class="wajib"></span></label>
                  <select class="form-control" name="qtydecimal" id="qtydecimal">
                      <option value="0">0</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                  </select>
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-4">
                  <label class="control-label active"><?= $this->lang->line('lb_cost_method'); ?> <span class="wajib"></span></label>
                  <select name="costmethod" placeholder="cost_method" id="cost_method" class="form-control"> 
                    <option value="average" selected="selected"> <?= $this->lang->line('lb_average'); ?></option>
                    <!-- <option value="standard">Standard</option>                         -->
                    </option>
                  </select>
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-4">
                  <label class="control-label active"><?= $this->lang->line('lb_negative'); ?></label>
                  <select name="negativestock" placeholder="negative_stock" id="negative_stock" class="form-control"> 
                    <option value="allow"><?= $this->lang->line('lb_allow'); ?></option>
                    <option value="warning"><?= $this->lang->line('lb_warning'); ?></option>
                    <option value="block"><?= $this->lang->line('lb_block'); ?></option>
                   </select>
                  <span class="help-block"></span>
                </div>

                <div class="form-group col-sm-4">
                  <label class="control-label"><?= $this->lang->line('lb_send_debt'); ?></label>
                  <select name="Days[]" placeholder="Days" id="Days" multiple> 
                      <option value="Mon"><?= $this->lang->line('lb_monday'); ?></option>
                      <option value="Tue"><?= $this->lang->line('lb_tuesday'); ?></option>
                      <option value="Wed"><?= $this->lang->line('lb_wednesday'); ?></option>
                      <option value="Thu"><?= $this->lang->line('lb_thursday'); ?></option>
                      <option value="Fri"><?= $this->lang->line('lb_friday'); ?></option>
                      <option value="Sat"><?= $this->lang->line('lb_saturday'); ?></option>
                      <option value="Sun"><?= $this->lang->line('lb_sunday'); ?></option>
                  </select>
                  <span class="help-block"></span>
                </div>

                <!-- modul setting -->
                <div class="col-sm-12">
                    <div class="panel panel-map panel-bordered">
                        <div class="panel-heading">
                          <h3 class="panel-title"><?= $this->lang->line('lb_module_setting'); ?></h3>
                          <div class="panel-actions panel-actions-keep">
                            <!-- <a class="panel-action icon wb-refresh" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="load_data('sellheader')"></a> -->
                            <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a>
                          </div>
                        </div>
                        <div class="panel-body">
                            <!-- purchase -->
                            <div class="row vap" style="display: inline;">
                                <div class="col-sm-4">
                                    <div class="panel panel-map panel-bordered">
                                        <div class="panel-body panel-body-custom">
                                             <div class="row row-fluid box">
                                                <div class="col-sm-12">
                                                  <div class="box-top-blue height-100">
                                                    <h4 class="title">Note :</h4>
                                                    <ul class="ul-check-blue">
                                                      <!-- <li class="item">Sound decision making from fresh and official data sources.</li>
                                                      <li class="item">Fast and consistent information for credit risk assessment available 24/7.</li>
                                                      <li class="item">Be alerted to significant information changes affecting initial assessment with monitoring of accounts and portfolio management.</li>
                                                      <li class="item">Deeper analytics and insights for business planning with quality data that are fresh, complete, consistent and accurate</li> -->
                                                    </ul>
                                                  </div>                    
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form__field input_voucher" placeholder="Insert Voucher Here" />
                                                    <a href="javascript:;" onclick="insert_voucher_module('.vap')" class="btn1 btn--primary btn--inside uppercase save_voucher"><?= $this->lang->line('lb_apply') ?></a>
                                                </div>
                                              </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="panel panel-map panel-bordered">
                                        <div class="panel-body panel-body-custom">
                                            <div class="card">
                                                <div class="col-sm-12 ">
                                                    <div class="card-head">
                                                      <img src="http://qa.pipesys.rcelectronic.co.id/img/logo.png" alt="logo" class="card-logo">
                                                      <img src="http://qa.pipesys.rcelectronic.co.id/img/icon/icon-custom/023-shopping-5.png" alt="Shoe" class="product-img">
                                                      <div class="product-detail">
                                                        <h2><?= $this->lang->line('lb_purchase1'); ?></h2>
                                                        <p class="lead text-section">
                                                           Help the purchase process start from purchase order, good receipt, purchase return, until payable payment.
                                                        </p>
                                                      </div>
                                                        <div id="timer2"></div>
                                                    </div>
                                                    <button type="button" class="btn pull-right product-price" onclick="check_lock('.vap')"></button>
                                                    <div class="card-body">
                                                      <div class="product-desc">
                                                        <span class="product-title"><?= $this->lang->line('lb_purchase1'); ?>
                                                            <span class="badge"></span>
                                                        </span>
                                                      </div>
                                                      <div class="product-properties">
                                                        <div class="checkbox-custom checkbox-primary content-hide">
                                                            <input type="checkbox" id="ap" name="ap" value="1">
                                                            <label for="ap" class="uppercase"><?= $this->lang->line('lb_purchase1'); ?></label> 
                                                        </div>
                                                        <div class="form-group col-sm-12 vap vdap">
                                                            <div class="col-sm-4 col-lg-4">
                                                              <div class="checkbox-custom checkbox-primary">
                                                                <input class="icheckbox-primary" name="po" id="po" type="checkbox" value="1">
                                                                <label for="po" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_purchase'); ?></label>                      
                                                              </div>
                                                            </div>
                                                            <div class="col-sm-4 col-lg-4">
                                                              <div class="checkbox-custom checkbox-primary">
                                                                <input class="icheckbox-primary" name="receipt" id="receipt" type="checkbox" value="1">
                                                                <label for="receipt" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_goodrc'); ?></label>                      
                                                              </div>
                                                            </div>
                                                            <div class="col-sm-4 col-lg-4">
                                                              <div class="checkbox-custom checkbox-primary">
                                                                <input class="icheckbox-primary" name="return_ap" id="return_ap" type="checkbox" value="1">
                                                                <label for="return_ap" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_returnap'); ?></label>                      
                                                              </div>
                                                            </div>
                                                            <div class="col-sm-4 col-lg-4">
                                                              <div class="checkbox-custom checkbox-primary">
                                                                <input class="icheckbox-primary" name="invoice_ap" id="invoice_ap" type="checkbox" value="1">
                                                                <label for="invoice_ap" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_invoiceap'); ?></label>                      
                                                              </div>
                                                            </div>
                                                            <div class="col-sm-4 col-lg-4">
                                                              <div class="checkbox-custom checkbox-primary">
                                                                <input class="icheckbox-primary" name="correction_ap" id="correction_ap" type="checkbox" value="1">
                                                                <label for="correction_ap" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_correctionap'); ?></label>                      
                                                              </div>
                                                            </div>
                                                            <div class="col-sm-4 col-lg-4">
                                                              <div class="checkbox-custom checkbox-primary">
                                                                <input class="icheckbox-primary" name="payment_ap" id="payment_ap" type="checkbox" value="1">
                                                                <label for="payment_ap" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_paymentap'); ?></label>                      
                                                              </div>
                                                            </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                            <!-- sales -->
                            <div class="row var" style="display: inline;">
                                <div class="col-sm-4">
                                    <div class="panel panel-map panel-bordered">
                                        <div class="panel-body panel-body-custom">
                                             <div class="row row-fluid box">
                                                <div class="col-sm-12">
                                                  <div class="box-top-blue height-100">
                                                    <h4 class="title">Note :</h4>
                                                    <ul class="ul-check-blue">
                                                      <!-- <li class="item">Sound decision making from fresh and official data sources.</li>
                                                      <li class="item">Fast and consistent information for credit risk assessment available 24/7.</li>
                                                      <li class="item">Be alerted to significant information changes affecting initial assessment with monitoring of accounts and portfolio management.</li>
                                                      <li class="item">Deeper analytics and insights for business planning with quality data that are fresh, complete, consistent and accurate</li> -->
                                                    </ul>
                                                  </div>                    
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form__field input_voucher" placeholder="Insert Voucher Here" />
                                                    <a href="javascript:;" onclick="insert_voucher_module('.var')" class="btn1 btn--primary btn--inside uppercase save_voucher"><?= $this->lang->line('lb_apply') ?></a>
                                                </div>
                                              </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="panel panel-map panel-bordered">
                                        <div class="panel-body panel-body-custom">
                                            <div class="card">
                                                <div class="col-sm-12">
                                                    <div class="card-head">
                                                      <img src="http://qa.pipesys.rcelectronic.co.id/img/logo.png" alt="logo" class="card-logo">
                                                      <img src="http://qa.pipesys.rcelectronic.co.id/img/icon/icon-custom/006-shopping.png" alt="Shoe" class="product-img">
                                                      <div class="product-detail">
                                                        <h2><?= $this->lang->line('lb_sales'); ?></h2>
                                                        <p class="lead text-section">
                                                           Help the sales process start from sales order, delivery, sales return, until receivable payment.
                                                        </p>
                                                      </div>
                                                        <div id="timer2"></div>
                                                    </div>
                                                    <button type="button" class="btn pull-right product-price" onclick="check_lock('.var')"></button>
                                                    <div class="card-body">
                                                      <div class="product-desc">
                                                        <span class="product-title"><?= $this->lang->line('lb_sales'); ?>
                                                            <span class="badge"></span>
                                                        </span>
                                                      </div>
                                                      <div class="product-properties">
                                                        <div class="checkbox-custom checkbox-primary content-hide">
                                                            <input type="checkbox" id="ar" name="ar" value="1">
                                                            <label for="ar" class="uppercase"><?= $this->lang->line('lb_sales'); ?></label>
                                                        </div>
                                                        <div class="form-group col-sm-12 var vdar">
                                                            <div class="col-sm-4 col-lg-4">
                                                              <div class="checkbox-custom checkbox-primary">
                                                                <input class="icheckbox-primary" name="so" id="so" type="checkbox" value="1">
                                                                <label for="so" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_selling'); ?></label>                      
                                                              </div>
                                                            </div>
                                                            <div class="col-sm-4 col-lg-4">
                                                              <div class="checkbox-custom checkbox-primary">
                                                                <input class="icheckbox-primary" name="delivery" id="delivery" type="checkbox" value="1">
                                                                <label for="delivery" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_delivery'); ?></label>                      
                                                              </div>
                                                            </div>
                                                            <div class="col-sm-4 col-lg-4">
                                                              <div class="checkbox-custom checkbox-primary">
                                                                <input class="icheckbox-primary" name="return_ar" id="return_ar" type="checkbox" value="1">
                                                                <label for="return_ar" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_returnar'); ?></label>                      
                                                              </div>
                                                            </div>
                                                            <div class="col-sm-4 col-lg-4">
                                                              <div class="checkbox-custom checkbox-primary">
                                                                <input class="icheckbox-primary" name="invoice_ar" id="invoice_ar" type="checkbox" value="1">
                                                                <label for="invoice_ar" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_invoicear'); ?></label>                      
                                                              </div>
                                                            </div>
                                                            <div class="col-sm-4 col-lg-4">
                                                              <div class="checkbox-custom checkbox-primary">
                                                                <input class="icheckbox-primary" name="correction_ar" id="correction_ar" type="checkbox" value="1">
                                                                <label for="correction_ar" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_correctionar'); ?></label>                      
                                                              </div>
                                                            </div>
                                                            <div class="col-sm-4 col-lg-4">
                                                              <div class="checkbox-custom checkbox-primary">
                                                                <input class="icheckbox-primary" name="payment_ar" id="payment_ar" type="checkbox" value="1">
                                                                <label for="payment_ar" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_paymentar'); ?></label>                      
                                                              </div>
                                                            </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                            <!-- inventory -->
                            <div class="row vinventory" style="display: inline;">
                                <div class="col-sm-4">
                                    <div class="panel panel-map panel-bordered">
                                        <div class="panel-body panel-body-custom">
                                             <div class="row row-fluid box">
                                                <div class="col-sm-12">
                                                  <div class="box-top-blue height-100">
                                                    <h4 class="title">Note :</h4>
                                                    <ul class="ul-check-blue">
                                                      <!-- <li class="item">Sound decision making from fresh and official data sources.</li>
                                                      <li class="item">Fast and consistent information for credit risk assessment available 24/7.</li>
                                                      <li class="item">Be alerted to significant information changes affecting initial assessment with monitoring of accounts and portfolio management.</li>
                                                      <li class="item">Deeper analytics and insights for business planning with quality data that are fresh, complete, consistent and accurate</li> -->
                                                    </ul>
                                                  </div>                    
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form__field input_voucher" placeholder="Insert Voucher Here" />
                                                    <a href="javascript:;" onclick="insert_voucher_module('.vinventory')" class="btn1 btn--primary btn--inside uppercase save_voucher"><?= $this->lang->line('lb_apply') ?></a>
                                                </div>
                                              </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="panel panel-map panel-bordered">
                                        <div class="panel-body panel-body-custom">
                                            <div class="card">
                                                <div class="col-sm-12">
                                                    <div class="card-head">
                                                      <img src="http://qa.pipesys.rcelectronic.co.id/img/logo.png" alt="logo" class="card-logo">
                                                      <img src="http://qa.pipesys.rcelectronic.co.id/img/icon/icon-custom/010-delivery.png" alt="Shoe" class="product-img">
                                                      <div class="product-detail">
                                                        <h2><?= $this->lang->line('lb_inventory'); ?></h2> 
                                                        <p class="lead text-section">
                                                           Monitoring of incoming and outgoing stock, such as stock correction, stock opname, and stock mutation transactions.
                                                        </p>
                                                      </div>
                                                        <div id="timer2"></div>
                                                    </div>
                                                    <button type="button" class="btn pull-right product-price" onclick="check_lock('.vinventory')"></button>
                                                    <div class="card-body">
                                                      <div class="product-desc">
                                                        <span class="product-title"><?= $this->lang->line('lb_inventory'); ?>
                                                            <span class="badge"></span>
                                                        </span>
                                                      </div>
                                                      <div class="product-properties">
                                                        <div class="checkbox-custom checkbox-primary content-hide">
                                                            <input type="checkbox" id="inventory" name="inventory" value="1">
                                                            <label for="inventory" class="uppercase"><?= $this->lang->line('lb_inventory'); ?></label>
                                                        </div>
                                                        <div class="form-group col-sm-12 vinventory vdinventory">
                                                            <div class="col-sm-4 col-lg-4">
                                                              <div class="checkbox-custom checkbox-primary">
                                                                <input class="icheckbox-primary" name="mutation" id="mutation" type="checkbox" value="1">
                                                                <label for="mutation" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_stock_mutation'); ?></label>                      
                                                              </div>
                                                            </div>
                                                            <div class="col-sm-4 col-lg-4">
                                                              <div class="checkbox-custom checkbox-primary">
                                                                <input class="icheckbox-primary" name="stock" id="stock" type="checkbox" value="1">
                                                                <label for="stock" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_stock_correction'); ?></label>                      
                                                              </div>
                                                            </div>

                                                            <div class="col-sm-4 col-lg-4">
                                                              <div class="checkbox-custom checkbox-primary">
                                                                <input class="icheckbox-primary" name="inventory_goodreceipt" id="inventory_goodreceipt" type="checkbox" value="1">
                                                                <label for="inventory_goodreceipt" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_stock_receipt'); ?></label>                      
                                                              </div>
                                                            </div>

                                                            <div class="col-sm-4 col-lg-4">
                                                              <div class="checkbox-custom checkbox-primary">
                                                                <input class="icheckbox-primary" name="good_issue" id="good_issue" type="checkbox" value="1">
                                                                <label for="good_issue" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_stock_issue'); ?></label>                      
                                                              </div>
                                                            </div>

                                                        </div>
                                                      </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                            <!-- accounting -->
                            <div class="row vac" style="display: inline;">
                                <div class="col-sm-4">
                                    <div class="panel panel-map panel-bordered">
                                        <div class="panel-body panel-body-custom">
                                             <div class="row row-fluid box">
                                                <div class="col-sm-12">
                                                  <div class="box-top-blue height-100">
                                                    <h4 class="title">Note :</h4>
                                                    <ul class="ul-check-blue">
                                                      <!-- <li class="item">Sound decision making from fresh and official data sources.</li>
                                                      <li class="item">Fast and consistent information for credit risk assessment available 24/7.</li>
                                                      <li class="item">Be alerted to significant information changes affecting initial assessment with monitoring of accounts and portfolio management.</li>
                                                      <li class="item">Deeper analytics and insights for business planning with quality data that are fresh, complete, consistent and accurate</li> -->
                                                    </ul>
                                                  </div>                    
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form__field input_voucher" placeholder="Insert Voucher Here" />
                                                    <a href="javascript:;" onclick="insert_voucher_module('.vac')" class="btn1 btn--primary btn--inside uppercase save_voucher"><?= $this->lang->line('lb_apply') ?></a>
                                                </div>
                                              </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="panel panel-map panel-bordered">
                                        <div class="panel-body panel-body-custom">
                                            <div class="card">
                                                <div class="col-sm-12">
                                                    <div class="card-head">
                                                      <img src="http://qa.pipesys.rcelectronic.co.id/img/logo.png" alt="logo" class="card-logo">
                                                      <img src="http://qa.pipesys.rcelectronic.co.id/img/icon/icon-custom/046-accounting-1.png" alt="Shoe" class="product-img">
                                                      <div class="product-detail">
                                                        <h2><?= $this->lang->line('fitur_accounting'); ?></h2>
                                                        <p class="lead text-section">
                                                           Working scope of this module is Standard Accounting Cash Management.
                                                        </p> 
                                                      </div>
                                                        <div id="timer2"></div>
                                                    </div>
                                                    <button type="button" class="btn pull-right product-price" onclick="check_lock('.vac')"></button>
                                                    <div class="card-body">
                                                      <div class="product-desc">
                                                        <span class="product-title"><?= $this->lang->line('fitur_accounting'); ?>
                                                            <span class="badge">
                                                              Nonactive
                                                            </span>
                                                        </span>
                                                      </div>
                                                      <div class="product-properties">
                                                         <div class="checkbox-custom checkbox-primary content-hide">
                                                            <input type="checkbox" id="ac" name="ac" value="1">
                                                            <label for="ac" class="uppercase"><?= $this->lang->line('fitur_accounting'); ?></label>
                                                        </div>
                                                        <div class="form-group col-sm-12 vac vdac" style="height: 81px;">
                                                            <div class="col-sm-4 col-lg-4">
                                                              <div class="checkbox-custom checkbox-primary">
                                                                <input class="icheckbox-primary" name="cash_bank" id="cash_bank" type="checkbox" value="1">
                                                                <label for="cash_bank" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_cash_bank'); ?></label>                      
                                                              </div>
                                                            </div>
                                                            <div class="col-sm-4 col-lg-4">
                                                              <div class="checkbox-custom checkbox-primary">
                                                                <input class="icheckbox-primary" name="jurnal" id="jurnal" type="checkbox" value="1">
                                                                <label for="jurnal" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_journal_manual'); ?></label>                      
                                                              </div>
                                                            </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end modul setting -->

                <!-- <div class="form-group col-sm-12 vap">
                    <hr>
                    <h4><?= $this->lang->line('lb_module_setting'); ?></h4>
                    <div class="checkbox-custom checkbox-primary">
                        <input type="checkbox" id="ap" name="ap" value="1">
                        <label for="ap" class="uppercase"><?= $this->lang->line('lb_purchase1'); ?></label> 
                    </div>
                    <div class="form-group col-sm-12 vap vdap">
                        <div class="col-sm-4 col-lg-4">
                          <div class="checkbox-custom checkbox-primary">
                            <input class="icheckbox-primary" name="po" id="po" type="checkbox" value="1">
                            <label for="po" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_purchase'); ?></label>                      
                          </div>
                        </div>
                        <div class="col-sm-4 col-lg-4">
                          <div class="checkbox-custom checkbox-primary">
                            <input class="icheckbox-primary" name="receipt" id="receipt" type="checkbox" value="1">
                            <label for="receipt" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_goodrc'); ?></label>                      
                          </div>
                        </div>
                        <div class="col-sm-4 col-lg-4">
                          <div class="checkbox-custom checkbox-primary">
                            <input class="icheckbox-primary" name="return_ap" id="return_ap" type="checkbox" value="1">
                            <label for="return_ap" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_returnap'); ?></label>                      
                          </div>
                        </div>
                        <div class="col-sm-4 col-lg-4">
                          <div class="checkbox-custom checkbox-primary">
                            <input class="icheckbox-primary" name="invoice_ap" id="invoice_ap" type="checkbox" value="1">
                            <label for="invoice_ap" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_invoiceap'); ?></label>                      
                          </div>
                        </div>
                        <div class="col-sm-4 col-lg-4">
                          <div class="checkbox-custom checkbox-primary">
                            <input class="icheckbox-primary" name="correction_ap" id="correction_ap" type="checkbox" value="1">
                            <label for="correction_ap" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_correctionap'); ?></label>                      
                          </div>
                        </div>
                        <div class="col-sm-4 col-lg-4">
                          <div class="checkbox-custom checkbox-primary">
                            <input class="icheckbox-primary" name="payment_ap" id="payment_ap" type="checkbox" value="1">
                            <label for="payment_ap" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_paymentap'); ?></label>                      
                          </div>
                        </div>
                    </div>
                </div> -->
                <!-- <div class="form-group col-sm-12 var">
                    <div class="checkbox-custom checkbox-primary">
                        <input type="checkbox" id="ar" name="ar" value="1">
                        <label for="ar" class="uppercase"><?= $this->lang->line('lb_sales'); ?></label>
                    </div>
                    <div class="form-group col-sm-12 var vdar">
                        <div class="col-sm-4 col-lg-4">
                          <div class="checkbox-custom checkbox-primary">
                            <input class="icheckbox-primary" name="so" id="so" type="checkbox" value="1">
                            <label for="so" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_selling'); ?></label>                      
                          </div>
                        </div>
                        <div class="col-sm-4 col-lg-4">
                          <div class="checkbox-custom checkbox-primary">
                            <input class="icheckbox-primary" name="delivery" id="delivery" type="checkbox" value="1">
                            <label for="delivery" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_delivery'); ?></label>                      
                          </div>
                        </div>
                        <div class="col-sm-4 col-lg-4">
                          <div class="checkbox-custom checkbox-primary">
                            <input class="icheckbox-primary" name="return_ar" id="return_ar" type="checkbox" value="1">
                            <label for="return_ar" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_returnar'); ?></label>                      
                          </div>
                        </div>
                        <div class="col-sm-4 col-lg-4">
                          <div class="checkbox-custom checkbox-primary">
                            <input class="icheckbox-primary" name="invoice_ar" id="invoice_ar" type="checkbox" value="1">
                            <label for="invoice_ar" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_invoicear'); ?></label>                      
                          </div>
                        </div>
                        <div class="col-sm-4 col-lg-4">
                          <div class="checkbox-custom checkbox-primary">
                            <input class="icheckbox-primary" name="correction_ar" id="correction_ar" type="checkbox" value="1">
                            <label for="correction_ar" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_correctionar'); ?></label>                      
                          </div>
                        </div>
                        <div class="col-sm-4 col-lg-4">
                          <div class="checkbox-custom checkbox-primary">
                            <input class="icheckbox-primary" name="payment_ar" id="payment_ar" type="checkbox" value="1">
                            <label for="payment_ar" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_paymentar'); ?></label>                      
                          </div>
                        </div>
                    </div>
                </div> -->
                <!-- <div class="form-group col-sm-12 vinventory">
                    <div class="checkbox-custom checkbox-primary">
                        <input type="checkbox" id="inventory" name="inventory" value="1">
                        <label for="inventory" class="uppercase"><?= $this->lang->line('lb_inventory'); ?></label>
                    </div>
                    <div class="form-group col-sm-12 vinventory vdinventory">
                        <div class="col-sm-4 col-lg-4">
                          <div class="checkbox-custom checkbox-primary">
                            <input class="icheckbox-primary" name="mutation" id="mutation" type="checkbox" value="1">
                            <label for="mutation" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_stock_mutation'); ?></label>                      
                          </div>
                        </div>
                        <div class="col-sm-4 col-lg-4">
                          <div class="checkbox-custom checkbox-primary">
                            <input class="icheckbox-primary" name="stock" id="stock" type="checkbox" value="1">
                            <label for="stock" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_stock_correction'); ?></label>                      
                          </div>
                        </div>

                        <div class="col-sm-4 col-lg-4">
                          <div class="checkbox-custom checkbox-primary">
                            <input class="icheckbox-primary" name="inventory_goodreceipt" id="inventory_goodreceipt" type="checkbox" value="1">
                            <label for="inventory_goodreceipt" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_stock_receipt'); ?></label>                      
                          </div>
                        </div>

                        <div class="col-sm-4 col-lg-4">
                          <div class="checkbox-custom checkbox-primary">
                            <input class="icheckbox-primary" name="good_issue" id="good_issue" type="checkbox" value="1">
                            <label for="good_issue" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_stock_issue'); ?></label>                      
                          </div>
                        </div>

                    </div>
                </div> -->
                <!-- <div class="form-group col-sm-12 vac">
                    <div class="checkbox-custom checkbox-primary">
                        <input type="checkbox" id="ac" name="ac" value="1">
                        <label for="ac" class="uppercase"><?= $this->lang->line('fitur_accounting'); ?></label>
                    </div>
                    <div class="form-group col-sm-12 vac vdac">
                        <div class="col-sm-4 col-lg-4">
                          <div class="checkbox-custom checkbox-primary">
                            <input class="icheckbox-primary" name="cash_bank" id="cash_bank" type="checkbox" value="1">
                            <label for="cash_bank" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_cash_bank'); ?></label>                      
                          </div>
                        </div>
                        <div class="col-sm-4 col-lg-4">
                          <div class="checkbox-custom checkbox-primary">
                            <input class="icheckbox-primary" name="jurnal" id="jurnal" type="checkbox" value="1">
                            <label for="jurnal" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;" class="active"><?= $this->lang->line('lb_journal_manual'); ?></label>                      
                          </div>
                        </div>
                    </div>
                </div> -->
                <div class="form-group col-sm-12 vtemplate">
                    <hr>
                    <h4><?= $this->lang->line('lb_template_default'); ?></h4>
                </div>
                <?php
                foreach ($this->main->arrTemplate() as $v) { ?>
                <div class="form-group col-sm-6">
                  <label class="control-label active"><?= $this->main->label_arrTemplate($v) ?> <span class="wajib"></span></label>
                  <div class="input-group">
                    <select name="default_template[]" class="form-control template_select template_<?= $v ?>" data-select="active" data-position="group" data-default="active"></select>
                    <span class="input-group-addon pointer" title="View Image" onclick="template_view_image('.template_<?= $v ?>')">
                      <?= $this->lang->line('lb_view_image'); ?>
                    </span>
                  </div>
                  <span class="help-block"></span>
                </div>
                <?php }?>
                <div class="form-group col-sm-12 vdata_set">
                    <hr>
                    <h4><?= $this->lang->line('lb_data_default'); ?></h4>
                    <div class="row">
                        <div class="form-group col-sm-6">
                          <label class="control-label active"><?= $this->lang->line('lb_data_setting'); ?> <span class="wajib"></span></label>
                          <select name="datasetting" placeholder="data_setting" id="data_setting" class="form-control"> 
                            <option value="Days" selected="selected"><?= $this->lang->line('lb_days'); ?></option>
                            <option value="Month"><?= $this->lang->line('lb_month'); ?></option>                        
                            <option value="Year"><?= $this->lang->line('lb_year'); ?></option>                        
                            </option>
                          </select>
                          <span class="help-block"></span>
                        </div>
                    </div>
                </div>
                <div class="form-group col-sm-12">
                  <button class="btn btn-primary pull-right" onclick="company_save('setting_parameter')" id="btnSave"><?= $this->lang->line('lb_save_setting'); ?></button>
                </div>
              </div>
            </form>
          </div>          
        </div>
      </div>
    </div>
  </div>
</div>

<script src="<?= base_url('aset/js/page/setting_parameter.js'.$this->main->js_css_version()) ?>"></script>
<link rel="stylesheet" href="<?= base_url('aset/plugin/slimselect/slimselect.min.css') ?>">
<script src="<?= base_url('aset/plugin/slimselect/slimselect.min.js') ?>"></script>