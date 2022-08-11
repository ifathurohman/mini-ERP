<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('upload'); 

    }
    public function login()
    {
        $data = $this->main->login("api");
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    public function register()
    {
        $data = $this->main->register("api");
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    public function forgot_password()
    {
        $data = $this->main->forgot_password("api");
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    public function reset_password()
    {
        $data = $this->main->reset_password("api");
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }

    public function verification_account()
    {
        $data = $this->main->VerificationAccount("api");
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    public function send_verification_code($page = ""){
        $output = $this->main->SendVerificationCode();
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);   
    }
    public function change_verification(){
       $output = $this->main->ChangeVerification();
       header('Content-Type: application/json');
       echo json_encode($output,JSON_PRETTY_PRINT);      
    }

    public function customer_select(){
        $Data = $this->api->customer_select();
        $output     = array(
            "Data"      => $Data,
            // "HakAkses"  => $this->session->HakAkses,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }

     public function warehouse_select(){
        $Data = $this->api->warehouse_select();
        $output     = array(
            "Data"      => $Data,
            // "HakAkses"  => $this->session->HakAkses,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }

    public function sellno_select(){
        $Data = $this->api->sellno_select();
        $output     = array(
            "Data"      => $Data,
            // "HakAkses"  => $this->session->HakAkses,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }

    public function purchaseno_select(){
        $Data = $this->api->purchaseno_select();
        $output     = array(
            "Data"      => $Data,
            // "HakAkses"  => $this->session->HakAkses,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }

    public function vendor_select(){
        $Data = $this->api->vendor_select();
        $output     = array(
            "Data"      => $Data,
            // "HakAkses"  => $this->session->HakAkses,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }

    public function product_select(){
        $Data = $this->api->product_select();
        $output     = array(
            "Data"      => $Data,
            // "HakAkses"  => $this->session->HakAkses,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }

     public function sales_select(){
        $Data = $this->api->sales_select();
        $output     = array(
            "Data"      => $Data,
            // "HakAkses"  => $this->session->HakAkses,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }

    public function tax_select(){
        $Data = $this->api->tax_select();
        $output     = array(
            "Data"      => $Data,
            // "HakAkses"  => $this->session->HakAkses,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }

    public function city_select(){
        $list = $this->api->city_select();
        $output = array(
            "list"      => $list,
            "hakakses"  => $this->session->hak_akses,
        );

        $this->main->echoJson($output);
    }

    // public function pegawai_select(){
    //     $Datax  = $this->api->pegawai_select();
    //     $Data   = array();
    //     foreach($Datax as $key => $a):
    //         $Skill = json_decode($a->Skill);
    //         // $Skill = $this->api->status_pekerjaan_select("groupid",$Skill);
    //         $item = array(
    //             "ID"    => $a->ID,
    //             "Name"  => $a->Name,
    //             "Skill" => $Skill
    //         );
    //         $Data[] = $item;
    //     endforeach;
    //     $output     = array(
    //         "Data"      => $Data,
    //         "HakAkses"  => $this->session->HakAkses,
    //     );
    //     header('Content-Type: application/json');
    //     echo json_encode($output,JSON_PRETTY_PRINT);  
    // }
    #list_data
    public function category()
    {
        $data = $this->main->category("api");
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    public function unit()
    {
        $data = $this->main->unit("api");
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
     public function uom()
    {
        $data = $this->main->uom("api");
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    public function product()
    {	

    	$classnya 	= $this->input->post("classnya");
    	$version 	= $this->input->post("version");
    	$status 	= $this->input->post("status");
        
        if($version == "serverSide"):
        	$data 	= array();

        	$dServer= $this->product_data_serverside("list");
    		$list 	= $this->main->product("serverSide",$dServer);

    		foreach ($list as $k => $v) {
    			$row 	= array();
    			$tag_data = 'data-row="'.$classnya.'"
                            data-productid="'.$v->productid.'"
                            data-code="'.$v->product_code.'"
                            data-name="'.$v->product_name.'"
                            data-type="'.$v->product_type.'"
                            data-serial_auto="'.$v->serial_auto.'"
                            data-unitid="'.$v->unitid.'"
                            data-unit="'.$v->unit_name.'"
                            data-conversion="'.$v->conversion.'"
                            data-qty="'.$v->qty.'"
                            data-sellingprice="'.(float)$v->sellingprice.'"
                            data-average_price="'.(float)$v->average_price.'"
                            data-status="'.$status.'"
                            data-purchaseprice="'.(float)$v->purchaseprice.'"
                            ';

    			$row[] = '<a href="javascript:void(0)"  '.$tag_data.' onclick="chose_product(this)">'.$v->product_code.'</a>';
    			$row[] = '<a href="javascript:void(0)"  '.$tag_data.' onclick="chose_product(this)">'.$v->product_name.'</a>';

    			$data[] = $row;
    		}

			$count_all 		 = $this->product_data_serverside("count_all");
    		$count_filtered  = $this->product_data_serverside("count_filtered");
			$output = array(
				"draw"  		  => $_POST['draw'],
				"recordsTotal" 	  => $this->main->product("serverSide",$count_all),
				"recordsFiltered" => $this->main->product("serverSide",$count_filtered),
				"data"			  => $data,
			);
			echo json_encode($output);
        else:
        	$data = $this->main->product("api");
	        $list_data = array();
	        foreach($data as $a):
	            $item = array(
	                "productid"         => $a->productid,
	                "product_code"      => $a->product_code,
	                "unitid"            => $a->unitid,
	                "product_name"      => $a->product_name,
	                "product_type"      => $a->product_type,
	                "serial_format"     => $a->serial_format,
	                "min_qty"           => $this->main->qty($a->min_qty),
	                "qty"               => $this->main->qty($a->qty),
	                "sellingprice"      => $a->sellingprice,
	                "purchaseprice"     => $a->purchaseprice,
	                "category_name"     => $a->category_name,
	                "unit_name"         => $a->unit_name,
	                "conversion"        => $a->conversion,
	                "average_price"     => $a->average_price,
	            );
	            array_push($list_data,$item);
	        endforeach;
	        $data = array("list_product" => $list_data);
	        header('Content-Type: application/json');
	        echo json_encode($data,JSON_PRETTY_PRINT);
        endif;  
    }
    private function product_data_serverside($page){
    	$dServer = array(
			"column"	=> array("ps_product.Code","ps_product.Name"),
			"page"		=> $page,
			"order_by"	=> array('ps_product.Name' => "asc"),
		);

		return $dServer;
    }
    public function product_unit(){
        $CompanyID = $this->session->CompanyID;
        $ProductID = $this->input->post('ProductID');
        if($CompanyID):
            $unit = $this->api->product_unit($ProductID);
            $output = array(
                'status'    => true,
                'message'   => $this->lang->line('lb_success'),
                'list'      => $unit,
            );
        else:
            $output = array(
                "status"    => false,
                "message"   => $this->lang->line('lb_data_not_found'),
            );
        endif;

        $this->main->echoJson($output);
    }
    public function company()
    {
        $data       = $this->main->company("api");
        $data->bank = $this->main->get_bank_company();
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    public function vendor()
    {	
    	$classnya 	= $this->input->post("classnya");
    	$version 	= $this->input->post("version");
    	$page_post = $this->input->post('page');

    	if($version == "serverSide"):
    		$data 	= array();

    		$dServer= $this->vendor_data_serverside("list");
    		$list 	= $this->main->vendor("serverSide",$dServer);

    		foreach ($list as $k => $v) {
    			$i = $k + 1;
    			$row 	= array();

    			$d_address   = '';
                $d_city      = '';
                $d_province  = '';

                if($page_post == "delivery" || $page_post == "invoice"):
                	$d_address 	= $v->d_address;
                	$d_city 	= $v->d_city;
                	$d_province = $v->d_province;
                endif;

    			$tag_data    = ' data-classnya="'.$classnya.'" ';
                $tag_data    .=' data-vendorid="'.$v->vendorid.'" ';
                $tag_data    .=' data-code="'.$v->code.'" ';
                $tag_data    .=' data-name="'.$v->name.'" ';
                $tag_data    .=' data-address="'.$v->address.'" ';
                $tag_data    .=' data-npwp="'.$v->npwp.'" ';
                $tag_data    .=' data-term="'.$v->term.'" ';
                $tag_data    .=' data-productcustomer="'.$v->productcustomer.'" ';
                $tag_data    .=' data-d_address="'.$d_address.'" ';
                $tag_data    .=' data-d_city="'.$d_city.'" ';
                $tag_data    .=' data-d_province="'.$d_province.'" ';

    			$row[] = '<a href="javascript:void(0)"  '.$tag_data.' onclick="chose_vendor(this)">'.$v->code.'</a>';
    			$row[] = '<a href="javascript:void(0)"  '.$tag_data.' onclick="chose_vendor(this)">'.$v->name.'</a>';

    			$data[] = $row;
    		}

    		$count_all 		= $this->vendor_data_serverside("count_all");
    		$count_filtered = $this->vendor_data_serverside("count_filtered");
    		$output = array(
				"draw"  		  => $_POST['draw'],
				"recordsTotal" 	  => $this->main->vendor("serverSide",$count_all),
				"recordsFiltered" => $this->main->vendor("serverSide",$count_filtered),
				"data"			  => $data,
				"a"				  => $this->input->post(),
			);
			echo json_encode($output);

    	else:
    		$data = $this->main->vendor("api");
	        $data = array(
	            "app" => $this->session->app,
	            "list_data" => $data);
	        header('Content-Type: application/json');
	        echo json_encode($data,JSON_PRETTY_PRINT);
    	endif;  
    }
    private function vendor_data_serverside($page){
    	$dServer = array(
			"column"	=> array("psv.Code","psv.Name"),
			"page"		=> $page,
			"order_by"	=> array('psv.Name' => "asc"),
		);

		return $dServer;
    }
    public function vendor_address(){
        $data = $this->main->vendor_address();
        $data = array(
            "app" => $this->session->app,
            "list_data" => $data);
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT); 
    }

     public function vendor_bank(){
        $data = $this->main->vendor_bank();
        $data = array(
            "app" => $this->session->app,
            "list_data" => $data);
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT); 
    }

    public function warehouse()
    {   
        $classnya   = $this->input->post("classnya");
        $version    = $this->input->post("version");
        $page_post = $this->input->post('page');

        if($version == "serverSide"):
            $data   = array();

            $dServer= $this->warehouse_data_serverside("list");
            $list   = $this->main->warehouse("serverSide",$dServer);

            foreach ($list as $k => $v) {
                $i = $k + 1;
                $row    = array();

                // $d_address   = '';
                // $d_city      = '';
                // $d_province  = '';

                // if($page_post == "delivery" || $page_post == "invoice"):
                //     $d_address  = $v->d_address;
                //     $d_city     = $v->d_city;
                //     $d_province = $v->d_province;
                // endif;

                $tag_data    = ' data-classnya="'.$classnya.'" ';
                $tag_data    .=' data-warehouseid="'.$v->warehouseid.'" ';
                $tag_data    .=' data-code="'.$v->code.'" ';
                $tag_data    .=' data-name="'.$v->name.'" ';
                $tag_data    .=' data-address="'.$v->address.'" ';
                $tag_data    .=' data-description="'.$v->description.'" ';

                $row[] = '<a href="javascript:void(0)"  '.$tag_data.' onclick="chose_warehouse(this)">'.$v->code.'</a>';
                $row[] = '<a href="javascript:void(0)"  '.$tag_data.' onclick="chose_warehouse(this)">'.$v->name.'</a>';

                $data[] = $row;
            }

            $count_all      = $this->warehouse_data_serverside("count_all");
            $count_filtered = $this->warehouse_data_serverside("count_filtered");
            $output = array(
                "draw"            => $_POST['draw'],
                "recordsTotal"    => $this->main->warehouse("serverSide",$count_all),
                "recordsFiltered" => $this->main->warehouse("serverSide",$count_filtered),
                "data"            => $data,
                "a"               => $this->input->post(),
            );
            echo json_encode($output);

        else:
            $data = $this->main->warehouse("api");
            $data = array(
                "app" => $this->session->app,
                "list_data" => $data);
            header('Content-Type: application/json');
            echo json_encode($data,JSON_PRETTY_PRINT);
        endif;  
    }

    private function warehouse_data_serverside($page){
        $dServer = array(
            "column"    => array("psw.Code","psw.Name"),
            "page"      => $page,
            "order_by"  => array('psw.Name' => "asc"),
        );

        return $dServer;
    }

    public function customer()
    {
        $data = $this->main->customer("api");
        $data = array("list_data" => $data);
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    public function invoiceno()
    {
        $search = str_replace("-", "/", $search);
        $data = $this->main->invoiceno("api");
        $data = array("list_data" => $data);
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    public function payno()
    {
        $data = $this->main->payno("api");
        $data = array("list_data" => $data);
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    public function sales(){
    	$classnya 	= $this->input->post("classnya");
    	$version 	= $this->input->post("version");

    	if($version == "serverSide"):
    		$data 	= array();
    		$dServer= $this->sales_data_serverside("list");
    		$list 	= $this->main->sales("serverSide",$dServer);

    		foreach ($list as $k => $v) {
    			$row 	= array();

    			$tag_data    = ' data-classnya="'.$classnya.'" ';
                $tag_data    .=' data-salesid="'.$v->salesid.'" ';
                $tag_data    .=' data-code="'.$v->code.'" ';
                $tag_data    .=' data-name="'.$v->name.'" ';
                $tag_data    .=' data-address="'.$v->address.'" ';

    			$row[] = '<a href="javascript:void(0)"  '.$tag_data.' onclick="chose_sales(this)">'.$v->code.'</a>';
    			$row[] = '<a href="javascript:void(0)"  '.$tag_data.' onclick="chose_sales(this)">'.$v->name.'</a>';

    			$data[] = $row;
    		}

    		$count_all 		 = $this->sales_data_serverside("count_all");
    		$count_filtered  = $this->sales_data_serverside("count_filtered");
    		$output = array(
				"draw"  		  => $_POST['draw'],
				"recordsTotal" 	  => $this->main->sales("serverSide",$count_all),
				"recordsFiltered" => $this->main->sales("serverSide",$count_filtered),
				"data"			  => $data,
			);
			echo json_encode($output);
    	else:
    		$data = $this->main->sales("api");
	        $data = array(
	            "app" => $this->session->app,
	            "list_data" => $data);
	        header('Content-Type: application/json');
	        echo json_encode($data,JSON_PRETTY_PRINT);
    	endif;  
    }
    private function sales_data_serverside($page){
    	$dServer = array(
			"column"	=> array("pss.Code","pss.Name"),
			"page"		=> $page,
			"order_by"	=> array('pss.Name' => "asc"),
		);

		return $dServer;
    }
    public function branch()
    {
        $data = $this->main->branch("api");
        $data = array("list_data" => $data);
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    public function sell($page = "",$search = "")
    {   $classnya   = $this->input->post("classnya");
        $version    = $this->input->post("version");

        if($version == "serverSide"):
            $data   = array();
            $dServer= $this->sell_data_serverside("list");
            $list   = $this->main->sell($page,$search,"","serverSide",$dServer);

            foreach ($list as $k => $v) {
                $row    = array();

                $tag_data    = ' data-classnya="'.$classnya.'" ';
                $tag_data    .=' data-code="'.$v->sellno.'" ';
                $tag_data    .=' data-vendor="'.$v->vendorname.'" ';
                $tag_data    .=' data-salesid="'.$v->salesid.'" ';
                $tag_data    .=' data-salesname="'.$v->salesName.'" ';
                $tag_data    .=' data-term="'.(float)$v->Term.'" ';
                $tag_data    .=' data-deliverycost="'.(float)$v->DeliveryCost.'" ';
                $tag_data    .=' data-tax="'.(float)$v->Tax.'" ';
                $tag_data    .=' data-ppn="'.(float)$v->ppn.'" ';
                $tag_data    .=' data-deladdress="'.$v->DeliveryAddress.'" ';
                $tag_data    .=' data-delcity="'.$v->DeliveryCity.'" ';
                $tag_data    .=' data-delprovince="'.$v->DeliveryProvince.'" ';
                $tag_data    .=' data-branch_id="'.$v->BranchID.'" ';
                $tag_data    .=' data-branch_name="'.$v->branchName.'" ';

                $row[] = '<a href="javascript:void(0)"  '.$tag_data.' onclick="chose_selling(this)">'.$v->sellno.'</a>';
                $row[] = '<a href="javascript:void(0)"  '.$tag_data.' onclick="chose_selling(this)">'.$v->vendorname.'</a>';
                $row[] = '<a href="javascript:void(0)"  '.$tag_data.' onclick="chose_selling(this)">'.date("Y-m-d",strtotime($v->date)).'</a>';

                $data[] = $row;
            }

            $count_all       = $this->sell_data_serverside("count_all");
            $count_filtered  = $this->sell_data_serverside("count_filtered");
            $output = array(
                "draw"            => $_POST['draw'],
                "recordsTotal"    => $this->main->sell($page,$search,"","serverSide",$count_all),
                "recordsFiltered" => $this->main->sell($page,$search,"","serverSide",$count_filtered),
                "data"            => $data,
            );
            echo json_encode($output);
        else:
            $data = $this->main->sell($page,$search);
            $list_data = $data;
            $output = array(
                "app"       => $this->session->app,
                "status"    => TRUE,
                "message"   => "",
                "hakakses"  => $this->session->hak_akses,
                "list_data" => $list_data,
            );

            header('Content-Type: application/json');
            echo json_encode($output,JSON_PRETTY_PRINT);
        endif;  
    }
    private function sell_data_serverside($page){
        $dServer = array(
            "column"    => array("sell.SellNo","pv.Name","sell.Date"),
            "page"      => $page,
            "order_by"  => array('DATE(sell.Date)' => "DESC"),
        );

        return $dServer;
    }
    public function sell_detail($page = "",$search = "")
    {   
        $search = str_replace("-", "/", $search);
        $data = $this->main->sell_detail($page,$search);
        $list_data = array();
        foreach($data as $a):
            array_push($list_data,$a);
        endforeach;
        $output = array(
            "status"    => TRUE,
            "message"   => "",
            "app"       => $this->session->app,
            "hakakses"  => $this->session->hak_akses,
            "list_data" => $list_data,
        );

        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }

    // purchase
    public function purchase($page="",$search=""){
        $classnya   = $this->input->post("classnya");
        $version    = $this->input->post("version");
        if($version == "serverSide"):
            $data   = array();

            $dServer= $this->purchase_data_serverside("list");
            $list   = $this->main->purchase($page,$search,"serverSide",$dServer);
            foreach ($list as $k => $v) {
                $row    = array();

                $tag_data    = ' data-classnya="'.$classnya.'" ';
                $tag_data    .=' data-code="'.$v->PurchaseNo.'" ';
                $tag_data    .=' data-vendor="'.$v->vendorname.'" ';
                $tag_data    .=' data-deliverycost="'.(float) $v->DeliveryCost.'" ';
                $tag_data    .=' data-tax="'.(float) $v->Tax.'" ';
                $tag_data    .=' data-ppn="'.(float) $v->ppn.'" ';
                $tag_data    .=' data-branch_id="'.$v->BranchID.'" ';
                $tag_data    .=' data-branch_name="'.$v->branchName.'" ';

                $row[] = '<a href="javascript:void(0)"  '.$tag_data.' onclick="chose_purchase(this)">'.$v->PurchaseNo.'</a>';
                $row[] = '<a href="javascript:void(0)"  '.$tag_data.' onclick="chose_purchase(this)">'.$v->vendorname.'</a>';
                $row[] = '<a href="javascript:void(0)"  '.$tag_data.' onclick="chose_purchase(this)">'.date("Y-m-d",strtotime($v->Date)).'</a>';

                $data[] = $row;
            }
            
            $count_all       = $this->purchase_data_serverside("count_all");
            $count_filtered  = $this->purchase_data_serverside("count_filtered");
            $output = array(
                "draw"            => $_POST['draw'],
                "recordsTotal"    => $this->main->purchase($page,$search,"serverSide",$count_all),
                "recordsFiltered" => $this->main->purchase($page,$search,"serverSide",$count_all),
                "data"            => $data,
            );
            echo json_encode($output);
        else:
            $data = $this->main->purchase($page,$search);
            $list_data = $data;
            $output = array(
                "app"       => $this->session->app,
                "status"    => TRUE,
                "message"   => "",
                "hakakses"  => $this->session->hak_akses,
                "list_data" => $list_data,
            );

            header('Content-Type: application/json');
            echo json_encode($output,JSON_PRETTY_PRINT);
        endif;
    }
    private function purchase_data_serverside($page){
        $dServer = array(
            "column"    => array("purchase.PurchaseNo","pv.Name","purchase.Date"),
            "page"      => $page,
            "order_by"  => array('DATE(purchase.Date)' => "DESC"),
        );

        return $dServer;
    }
    public function purchase_detail($page="",$search=""){
        $search = str_replace("-", "/", $search);
        $data = $this->main->purchase_detail($page,$search);
        $list_data = array();
        foreach($data as $a):
            array_push($list_data,$a);
        endforeach;
        $output = array(
            "status"    => TRUE,
            "message"   => "",
            "app"       => $this->session->app,
            "hakakses"  => $this->session->hak_akses,
            "list_data" => $list_data,
        );

        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);
    }
    // end purchase
    
    public function company_save($page = "")
    {

        $this->company_validation($page);
        $CompanyID = $this->session->CompanyID;
        $data           = array();
        $data_rekening  = array();
        if($page == "company"):
            if($page != 'setting_parameter'):$this->main->upload_validation('photo','image');endif;
            $nama                       = str_replace(" ", "_", $this->input->post("nama"));
            $nmfile                     = "pipesys_".$nama.date("ymd");
            $config['upload_path']      = './img/logo'; //path folder 
            $config['allowed_types']    = 'gif|jpg|png|jpeg|bmp|PNG|JPG'; //type yang dapat diakses bisa anda sesuaikan 
            $config['max_size']         = '99999'; //maksimum besar file 2M 
            $config['max_width']        = '99999'; //lebar maksimum 1288 px 
            $config['max_height']       = '99999'; //tinggi maksimu 768 px 
            $config['file_name']        = $nmfile; //nama yang terupload nantinya 
            $this->upload->initialize($config); 
            $upload                     = $this->upload->do_upload('photo');
            $gbr                        = $this->upload->data();
            $data = array(
                "nama"                  => $this->input->post("nama"),
                "address"               => $this->input->post("address"),
                "city"                  => $this->input->post("city"),
                "province"              => $this->input->post("province"),
                "country"               => $this->input->post("country"),
                "postal"                => $this->input->post("postal"),
                "npwp"                  => $this->input->post("npwp"),                
                "fax"                   => $this->input->post("fax"),
                "phone_company"         => $this->input->post("phone"),
            );

            if($upload):        
                $image              = "img/logo/".$gbr['file_name'];
                $data['img_bin']    = $gbr['file_name'];
                $data['img_url']    = $image;
                $image              = site_url($image);
                if(in_array($this->session->hak_akses, array("company", "super_admin"))):
                    $this->main->delete_img("user",array("id_user"=>$this->session->id_user),'img/logo/');
                else:
                    $this->main->delete_img("user",array("id_user"=>$this->session->CompanyID),'img/logo/');
                endif;
            else:
                if(in_array($this->session->hak_akses, array("company", "super_admin"))):
                    $this->api->update_company(array("id_user"=>$this->session->id_user), $data);
                else:
                    $this->api->update_company(array("id_user"=>$this->session->CompanyID), $data);
                endif;
            endif;

            $BankName    = $this->input->post("CBankName");
            $rekeningID  = array();
            if(count($BankName) > 0):
                foreach ($this->input->post("CBankName") as $key => $value):
                    $CompanyID      = $this->session->CompanyID;
                    $ID             = $this->input->post("ID")[$key];
                    $BankName       = $this->input->post("CBankName")[$key];
                    $BankCabang     = $this->input->post("BankCabang")[$key];
                    $anRekening     = $this->input->post("CAnRekening")[$key];
                    $NoRekening     = $this->input->post("CNoRekening")[$key];

                    $data_rekening  = array(
                        'CompanyID'     => $CompanyID,
                        'BankName'      => $BankName,
                        'BankBranch'    => $BankCabang,
                        'AnRekening'    => $anRekening,
                        'NoRekening'    => $NoRekening,
                        'Active'        => 1,
                        );

                    if($ID == ''):
                        $ID = $this->api->save_rekening($data_rekening);
                    else:
                        $this->api->update_rekening(array("UserRekID" => $ID), $data_rekening);
                    endif;
                    array_push($rekeningID, $ID);
                endforeach;
            endif;
            if(in_array($this->session->hak_akses, array("company", "super_admin"))):
                $this->db->where("id_user",$this->session->id_user);
            else:
                $this->db->where("id_user",$this->session->CompanyID);
            endif;
            $this->db->update("user",$data);
            $this->api->delete_rekening($rekeningID);
        elseif($page == "user_account"):
            $pass       = $this->input->post("password");
            $password   = $this->main->hash($pass);
            if($pass && $pass != "undefined"):
                $data["password"] = $password;
                $this->db->where("id_user",$this->session->id_user);
                $this->db->update("user",$data);
            endif;
        elseif($page == "setting_parameter"):
            $this->main->default_template2();
            if($this->input->post('Days')):
                $Days = $this->input->post('Days');
                $Days = json_encode($Days);
            else:
                $Days = null;
            endif;
            $settingid      = $this->input->post("settingparameterid");
            $amountdecimal  = $this->input->post("amountdecimal");
            $qtydecimal     = $this->input->post("qtydecimal");
            $Module         = $this->main->get_module_company();
            $dt_CompanyID   = $this->main->get_one_column("SettingParameter","ifnull(AmountDecimal,0) as AmountDecimal,ifnull(QtyDecimal,0) as QtyDecimal",array("CompanyID" => $CompanyID));

            if($amountdecimal <= 0):
                $amountdecimal = 0;
            elseif($amountdecimal <= 2):
                $amountdecimal = 2;
            elseif($amountdecimal>2):
                $amountdecimal = 4;
            else:
                $amountdecimal = 0;
            endif;

            if($qtydecimal <= 0):
                $qtydecimal = 0;
            elseif($qtydecimal >= 4):
                $qtydecimal = 4;
            endif;

            if($dt_CompanyID):
                if($amountdecimal < $dt_CompanyID->AmountDecimal):
                    $amountdecimal = $dt_CompanyID->AmountDecimal;
                endif;
                if($qtydecimal < $dt_CompanyID->QtyDecimal):
                    $qtydecimal = $dt_CompanyID->QtyDecimal;
                endif;
            endif;

            ## 20190718 MW
            ## catatan
            ## jika costmethod tipe standard sudah ada mohon cek ke import dan export master product dan transaksi

            $data = array(
                "Currency"      => $this->input->post("currency"),
                "AmountDecimal" => $amountdecimal,
                "QtyDecimal"    => $qtydecimal,
                "NegativeStock" => $this->input->post("negativestock"),
                "CostMethod"    => $this->input->post("costmethod"),
                "DataSetting"   => $this->input->post("datasetting"),
                "Days"          => $Days,
            );
            $datenow = date("Y-m-d");
            if($Module->ap->status == 1 and $Module->ap->expire >= $datenow):
                $ap             = $this->input->post('ap');
                $po             = $this->input->post('po');
                $receipt        = $this->input->post('receipt');
                $return_ap      = $this->input->post('return_ap');
                $invoice_ap     = $this->input->post('invoice_ap');
                $correction_ap  = $this->input->post('correction_ap');
                $payment_ap     = $this->input->post('payment_ap');

                $arrAP = array();
                if($ap):
                    array_push($arrAP, "ap");
                    if($po): array_push($arrAP, "po"); endif;
                    if($receipt): array_push($arrAP, "receipt"); endif;
                    if($return_ap): array_push($arrAP, "return_ap"); endif;
                    if($invoice_ap): array_push($arrAP, "invoice_ap"); endif;
                    if($correction_ap): array_push($arrAP, "correction_ap"); endif;
                    if($payment_ap): array_push($arrAP, "payment_ap"); endif;
                endif;
                $data['AP'] = json_encode($arrAP);
            endif;
            if($Module->ar->status == 1 and $Module->ar->expire >= $datenow):
                $ar             = $this->input->post('ar');
                $so             = $this->input->post('so');
                $delivery       = $this->input->post('delivery');
                $return_ar      = $this->input->post('return_ar');
                $invoice_ar     = $this->input->post('invoice_ar');
                $correction_ar  = $this->input->post('correction_ar');
                $payment_ar     = $this->input->post('payment_ar');

                $arrAr = array();
                if($ar):
                    array_push($arrAr, "ar");
                    if($so): array_push($arrAr, "so"); endif;
                    if($delivery): array_push($arrAr, "delivery"); endif;
                    if($return_ar): array_push($arrAr, "return_ar"); endif;
                    if($invoice_ar): array_push($arrAr, "invoice_ar"); endif;
                    if($correction_ar): array_push($arrAr, "correction_ar"); endif;
                    if($payment_ar): array_push($arrAr, "payment_ar"); endif;
                endif;
                $data['AR'] = json_encode($arrAr);
            endif;
            if($Module->inventory->status == 1 and $Module->inventory->expire >= $datenow):
                $inventory      = $this->input->post('inventory');
                $mutation       = $this->input->post('mutation');
                $stock          = $this->input->post('stock');
                $good_receipt   = $this->input->post('inventory_goodreceipt');
                $good_issue     = $this->input->post('good_issue');

                $arrInventory = array();
                if($inventory):
                    array_push($arrInventory, "inventory");
                    if($mutation): array_push($arrInventory, "mutation"); endif;
                    if($stock): array_push($arrInventory, "stock"); endif;
                    if($good_receipt): array_push($arrInventory, "inventory_goodreceipt"); endif;
                    if($good_issue): array_push($arrInventory, "good_issue"); endif;
                endif;
                $data['Inventory'] = json_encode($arrInventory);
            endif;
            if($Module->ac->status == 1 and $Module->ac->expire >= $datenow):
                $ac         = $this->input->post('ac');
                $cash_bank  = $this->input->post('cash_bank');
                $jurnal     = $this->input->post('jurnal');

                $arrAc = array();
                if($ac):
                    array_push($arrAc, "ac");
                    if($cash_bank): array_push($arrAc, "cash_bank"); endif;
                    if($jurnal): array_push($arrAc, "jurnal"); endif;
                endif;
                $data['AC'] = json_encode($arrAc);
            endif;
            if($settingid):
                $settingdata= $this->main->get_one_column("SettingParameter","AmountDecimal,QtyDecimal", array("SettingParameterID" => $settingid, "CompanyID" => $this->session->CompanyID));
                $AmountDecimal  = $settingdata->AmountDecimal;
                $QtyDecimal     = $settingdata->QtyDecimal;
                if($amountdecimal <= $AmountDecimal):
                    $data['AmountDecimal'] = $AmountDecimal;
                endif;
                if($qtydecimal <= $QtyDecimal):
                    $data['QtyDecimal'] = $QtyDecimal;
                endif;

                $this->db->where("CompanyID",$this->session->CompanyID);
                $this->db->where("SettingParameterID",$settingid);
                $this->db->update("SettingParameter",$data);
                $this->db->set("StatusParameter",1);
                $this->db->where("id_user",$this->session->id_user);
                $this->db->update("user");

                $data_session = array(
                    "StatusParameter" => 1,
                    "CostMethod"      => $this->input->post("costmethod"),
                    "DataSetting"     => $this->input->post("datasetting"),
                );
                $this->session->set_userdata($data_session);
            else:
                $data["CompanyID"] = $this->session->CompanyID;
                $this->db->insert("SettingParameter",$data);
            endif;

            $this->main->setting_parameter();
        endif;
        $respon = array(
            "status"    => TRUE,
            "message"   => "Saving data success",
            "data"      => $data,
            "page"      => $page
        );
        header('Content-Type: application/json');
        echo json_encode($respon,JSON_PRETTY_PRINT);   
    }

    private function company_validation($page = "")
    {
        $data                   = array();
        $data['error_string']   = array();
        $data['inputerror']     = array();
        $data['status']         = TRUE;
        if($page == "company" && $this->input->post('nama') == '')
        {
            $data['inputerror'][]   = 'nama';
            $data['error_string'][] = 'Company name must be filled';
            $data['status']         = FALSE;
        }
        // if($page == "user_account" && $this->input->post('last_name') == '')
        // {
        //     $data['inputerror'][]   = 'last_name';
        //     $data['error_string'][] = 'Last name must be filled';
        //     $data['status']         = FALSE;
        // }
        // if($page == "user_account" && $this->input->post('first_name') == '')
        // {
        //     $data['inputerror'][]   = 'first_name';
        //     $data['error_string'][] = 'First name must be filled';
        //     $data['status']         = FALSE;
        // }
        // if($page == "user_account" && $this->input->post('phone') == '')
        // {
        //     $data['inputerror'][]   = 'phone';
        //     $data['error_string'][] = 'Phone must be filled';
        //     $data['status']         = FALSE;
        // }
        if($page == "user_account" && $this->input->post('password') != '')
        {
            if($this->input->post('password') != $this->input->post('password_kon')){
                $data['inputerror'][]   = 'password_kon';
                $data['error_string'][] = 'Password Confirmation doesnt match';
                $data['status']         = FALSE;
            }
        }
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }
    #user-account
    public function user_account_save()
    {

    }
    #autocomplete
    public function autocomplete_product(){
        $search   = $this->input->post('search',TRUE);
        $query  = $data = $this->main->product("autocomplete",$search);
        $data       =  array();
        foreach ($query as $d) {
            $data[]     = array(
                'label'                 => $d->product_code,
                'productid'             => $d->productid,
                'product_code'          => $d->product_code,
                'product_name'          => $d->product_name,
                'product_type'          => $d->product_type,
                'product_unitid'        => $d->unitid,
                'product_unit'          => $d->unit_name,
                'product_conversion'    => $d->conversion,
                'product_sellingprice'  => $d->sellingprice,
                'product_qty'           => $d->qty,
                'average_price'         => $d->average_price,
            );
        }
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    #autocomplete
    public function autocomplete_vendor(){
        $search   = $this->input->post('search',TRUE);
        $query  = $data = $this->main->vendor("autocomplete",$search);
        $data       =  array();
        foreach ($query as $d) {
            $data[]     = array(
                'label'        => $d->vendorid."-".$d->name,
                'vendorid'     => $d->vendorid,
                'usercode'     => $d->usercode,
                'parentid'     => $d->parentid,
                'position'     => $d->position,
                'email'        => $d->email,
            );
        }
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }

    public function autocomplete_vendor_price(){
        $search   = $this->input->post('search',TRUE);
        $query    = $this->main->autocomplete_vendor_price("autocomplete",$search);

        $data = array();
        foreach ($query as $a) {
            $data[] = array(
                'label'     => $a->Name,
            );
        }
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }

     public function autocomplete_product_unit(){
        $search   = $this->input->post('search',TRUE);
        $query    = $this->main->autocomplete_product_unit("autocomplete",$search);

        $data = array();
        foreach ($query as $a) {
            $data[] = array(
                'label'     => $a->Name,
            );
        }
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }

    public function autocomplete_branch(){
        $search   = $this->input->post('search',TRUE);
        $query  = $data = $this->main->branch("autocomplete",$search);
        $data       =  array();
        foreach ($query as $d) {
            $data[]     = array(
                'label'     => $d->branchid."-".$d->name,
                'branchid'  => $d->branchid,
                'companyid' => $d->companyid,
                'name'      => $d->name,
            );
        }
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    #autocomplete serial number 2018-02-21 
    public function autocomplete_serialnumber(){
        $search     = $this->input->post('search',TRUE);
        $productid  = $this->input->post('productid',TRUE);
        $query      = $data = $this->main->product_serial("autocomplete",$search,$productid);
        $data       =  array();
        foreach ($query as $d) {
            if($d->serialnumber != ""){
                $data[]     = array(
                    'label'     => $d->serialnumber,
                );                
            }
        }
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    public function email()
    {
        $data["message"] = "tes";
        $data["page"] = "email/register";
        $this->load->view("email/email",$data);
    }
    public function emailtes()
    {
        $this->main->send_email("buy_voucher",3,"ya");
    }
    public function tes(){
        $data = array();
        for ($i=0; $i <1000 ; $i++) { 
            array_push($data,$i);
        }
        
        $folder     = 'file/';
        $file_name  = 'result_json_'.$this->session->id_user.".json";
        $temp_file  = $folder.$file_name;
        $fp = fopen($temp_file, 'w');
        fwrite($fp, json_encode($data));
        fclose($fp);
    }
    public function emailtes1()
    {
        $this->main->send_email("register",1,"ya");
    }
     public function emailregister()
    {
        $this->load->view("email/register");
    }

    public function serial_number_datatables()
    {
        $page   = "serial_number";
        $list   = $this->main->serial_number_datatables($page);
        $data   = array();
        $no     = $_POST['start'];
        $i      = 1;
        foreach ($list as $a) {
            $no++;
            $row    = array();
            $row[]  = $i++;
            $row[]  = $a->serialno;      
            $data[] = $row;
        }
        $output = array(
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->main->serial_count_all($page),
            "recordsFiltered" => $this->main->serial_count_filtered($page),
            "data"            => $data,
        );
        echo json_encode($output);
    }
    public function ar_correction($page = "",$search = ""){
        $data = $this->main->ar_correction($page,$search);
        $list_data = array();
        foreach($data as $a):
            $item = array(
                "branchid"      => $a->branchid,
                "branchname"    => $a->branchname,
                "total"         => $a->total,
                "grandtotal"    => $a->grandtotal,
                "lbl_sisatotal" => $this->main->currency($a->sisatotal,TRUE),
                "sisatotal"     => $a->sisatotal
            );
            array_push($list_data,$item);
        endforeach;
        $output = array(
            "status"    => TRUE,
            "hakakses"  => $this->session->hak_akses,
            "list_data" => $list_data,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }


    // #2018-01-25 iqbal
    // public function receive()
    // {
    //     $data       = $this->main->receive();
    //     // $list_data  = array();
    //     $list_data  = $data;

    //     $output = array(
    //         "status"    => TRUE,
    //         "hakakses"  => $this->session->hak_akses,
    //         "list_data" => $list_data,
    //     );
    //     header('Content-Type: application/json');
    //     echo json_encode($output,JSON_PRETTY_PRINT);  
    // }
    // public function receive_detail()
    // {
    //     $data       = $this->main->receive_detail();
    //     $list_data  = array();
    //     foreach($data as $a):
    //         $item = array(
    //             "product_code"     => $a->product_code, 
    //             "product_konv"     => $a->product_konv,
    //             "product_name"     => $a->product_name,
    //             "product_price"    => $a->product_price,
    //             "product_qty"      => $a->product_qty,
    //             "product_subtotal" => $a->product_subtotal,
    //             "product_type"     => $a->product_type,
    //             "productid"        => $a->productid,
    //             "receive_det"      => $a->receive_det,
    //             "receive_no"       => $a->receive_no,
    //             "unit_name"        => $a->unit_name,
    //             "unitid"           => $a->unitid
    //         );
    //         array_push($list_data, $item);
    //     endforeach;
    //     $output     = array(
    //         "status"    => TRUE,
    //         "hakakses"  => $this->session->hak_akses,
    //         "list_data" => $list_data,
    //     );
    //     header('Content-Type: application/json');
    //     echo json_encode($output,JSON_PRETTY_PRINT);  

    // }

    // purchase
    public function receive($page="",$search=""){
        $classnya   = $this->input->post("classnya");
        $version    = $this->input->post("version");

        if($version == "serverSide"):
            $data   = array();

            $dServer= $this->receive_data_serverside("list");
            $list   = $this->main->receive($page,$search,"serverSide",$dServer);

            foreach ($list as $k => $v) {
                $row = array();
                
                $tag_data     = ' data-classnya="'.$classnya.'" ';
                $tag_data    .=' data-tax="'.(float) $v->Tax.'" ';
                $tag_data    .=' data-ppn="'.(float) $v->ppn.'" ';
                $tag_data    .=' data-receiveno="'.$v->receiveno.'" ';
                $tag_data    .=' data-receivename="'.$v->receivename.'" ';
                $tag_data    .=' data-vendorid="'.$v->vendorid.'" ';
                $tag_data    .=' data-vendorname="'.$v->vendorname.'" ';
                $tag_data    .=' data-branch_id="'.$v->BranchID.'" ';
                $tag_data    .=' data-branch_name="'.$v->branchName.'" ';

                $row[] = '<a href="javascript:void(0)"  '.$tag_data.' onclick="chose_receive(this)">'.$v->receiveno.'</a>';
                $row[] = '<a href="javascript:void(0)"  '.$tag_data.' onclick="chose_receive(this)">'.$v->vendorname.'</a>';
                $row[] = '<a href="javascript:void(0)"  '.$tag_data.' onclick="chose_receive(this)">'.date("Y-m-d",strtotime($v->date)).'</a>';

                $data[] = $row;
            }

            $count_all       = $this->receive_data_serverside("count_all");
            $count_filtered  = $this->receive_data_serverside("count_filtered");
            $output = array(
                "draw"            => $_POST['draw'],
                "recordsTotal"    => $this->main->receive($page,$search,"serverSide",$count_all),
                "recordsFiltered" => $this->main->receive($page,$search,"serverSide",$count_all),
                "data"            => $data,
            );
            echo json_encode($output);
        else:
            $data = $this->main->receive($page,$search);
            $list_data = $data;
            $output = array(
                "app"       => $this->session->app,
                "status"    => TRUE,
                "message"   => "",
                "hakakses"  => $this->session->hak_akses,
                "list_data" => $list_data,
            );

            header('Content-Type: application/json');
            echo json_encode($output,JSON_PRETTY_PRINT);
        endif;
    }
    private function receive_data_serverside($page){
        $dServer = array(
            "column"    => array("gr.DeliveryNo","v.Name","gr.Date"),
            "page"      => $page,
            "order_by"  => array('DATE(gr.Date)' => "DESC"),
        );

        return $dServer;
    }
    public function receive_detail($page="",$search=""){
        $search = str_replace("-", "/", $search);
        $data = $this->main->receive_detail($page,$search);
        $list_data = array();
        foreach($data as $a):
            array_push($list_data,$a);
        endforeach;
        $output = array(
            "status"    => TRUE,
            "message"   => "",
            "app"       => $this->session->app,
            "hakakses"  => $this->session->hak_akses,
            "list_data" => $list_data,
        );

        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);
    }

    #ini untuk salespro
    #2018-02-04
    public function dashboard_salespro()
    {
        $data = $this->main->dashboard();
        $user_detail            = $this->main->user_detail($this->session->UserID);
        $branch                 = $this->main->sp_sales_location();
        $total_customer         = $this->main->customer("count");
        $total_sales            = $this->main->sp_total_route_transaction("count_sales");
        $total_route            = $this->main->sp_total_route_transaction("all");
        $total_route_complete   = $this->main->sp_total_route_transaction("complete");
        $top_sales              = $this->main->sp_top_sales();
        $total_route1           = $data["total_route"];
        $total_hour             = $data["total_hour"];
        $list_store             = array();
        $list_checkin           = array();
        $list_checkout          = array();
        foreach($branch as $a):
            $CheckTime = "";
            if($a->CheckTime):
                $CheckTime = date("d M Y H:i",strtotime($a->CheckTime));
            endif;
            $Duration = $this->main->selisih_waktu(date("Y-m-d H:i",strtotime($a->CheckTime)),date("Y-m-d H:i"));
            $item_branch = array(
                "App"           => $this->session->app,
                "ID"            => $a->BranchID,
                "Name"          => $a->Name,
                "Phone"         => $a->Phone,
                "Email"         => $a->Email,
                "Lat"           => $a->Lat,
                "Lng"           => $a->Lng,
                "Check"         => $a->Check,
                "CheckTime"     => $CheckTime,
                "CheckAddress"  => $a->CheckAddress,
                "Duration"      => $Duration
            );
            array_push($list_store,$item_branch);


            if($a->Check == "in"):
                $item_branch["CheckTime"] = date("H:i",strtotime($CheckTime));
                array_push($list_checkin,$item_branch);
            endif;
            if($a->Check == "out"):
                $item_branch["CheckTime"] = $CheckTime;
                array_push($list_checkout,$item_branch);
            endif;
        endforeach;
        $JoinDate           = date("Y-m-d",strtotime($user_detail->JoinDate));
        $VerificationExpire = date('Y-m-d', strtotime("+6 day", strtotime($JoinDate)));
        $VerificationExpire = $this->main->tanggal("D, d M Y",$VerificationExpire);
        $output = array(
            "status"                    => TRUE,
            "VerificationExpire"        => $VerificationExpire,
            "StatusVerify"              => $this->session->StatusVerify,
            "JoinDate"                  => $JoinDate,
            "StatusAccount"             => $this->session->StatusAccount,
            "AlertVerification"         => $this->main->AlertVerification(),
            "ExpireAccount"             => $this->session->ExpireAccount,
            "ExpireAccountDayLeft"      => $this->main->selisih_hari(date("Y-m-d"),date("Y-m-d",strtotime($this->session->ExpireAccount))),
            "hakakses"                  => $this->session->hak_akses,
            "total_sales"               => $total_sales,
            "total_route_transaction"   => $total_route,
            "total_complete_route"      => $total_route_complete,
            "total_customer"            => $total_customer,
            "top_sales"                 => $top_sales,
            'total_route1'              => $total_route1,
            'total_hour'                => $total_hour,
            "list_sales"                => $list_store,
            "list_checkin"              => $list_checkin,
            "list_checkout"             => $list_checkout,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }
    public function push_notif_transaction($transactionID,$BranchID)
    {
        $this->firebase->push_new_route_transaction($transactionID,$BranchID); #transaction branchid
    }
    public function selisih_waktu()
    {
        $selish_waktu = $this->main->selish_waktu(date("Y-m",strtotime("2017-02-01")),date("Y-m"));
        print_r($selish_waktu);
    }
    public function get_voucher_price()
    {
        $App            = $this->input->post("App");
        $Type           = $this->input->post("Type");
        $Qty            = $this->input->post("Qty");
        $QtyModule      = $this->input->post("QtyModule");
        $price          = 0.00;
        $price_total    = 0.00;
        $price_module   = 0.00;
        $module_total   = 0.00;
        $device_total   = 0.00;
        $a              = $this->main->voucher_package($App,$Type,"2");
        $module         = $this->main->voucher_package($App,$Type,"1");

        if($a):
            $price          = $a->Price;
            $device_total  = $price * $Qty * $Type;
        endif;

        if($module):
            $price_module = $module->Price;
            $module_total = $price_module * $QtyModule * $Type;
        endif;

        $price_total = $device_total + $module_total;

        $output = array(
            "price_total"       => $price_total,
            "price"             => $price,
            "price_total_txt"   => $this->main->currency($price_total),
            "price_txt"         => $this->main->currency($price)."/Month",
            "price_module_txt"  => $this->main->currency($price_module)."/Month",
            "device_total_txt"  => $this->main->currency($device_total),
            "module_total_txt"  => $this->main->currency($module_total),
            "status"            => TRUE,
            "message"           => "Success",
            "hakakses"          => $this->session->hak_akses,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }
    public function voucher(){
        $list = $this->api->voucher();

        $output = array(
            "list_data" => $list,
            "hakakses"  => $this->session->hak_akses,
        );

        $this->main->echoJson($output);
    }

    public function ListSalesExpire(){
        $data = $this->main->ListSalesExpire();
        return $data;
    }


    //tes untuk total route pakai temp table
    public function tes_total_route(){
         //create temporary table Date
        $this->main->TempDate();
        $date = $this->main->TempDateInsert();
        $list = $this->main->TempDateSelect();

        $StartDate  = $this->input->post("StartDate");
        $EndDate    = $this->input->post("EndDate");

        $StartDate  = "2018-05-08";
        $EndDate    = "2018-05-09";

        $this->db->select("
            Year(temp.Date) as year,
            Month(temp.Date) as month,
            Day(temp.Date)as day,
            count(sp_td.TransactionRouteDetailID ) as total,
            IFNULL(sum(
                CASE
                   WHEN IFNULL(sp_td.CheckIn, 1) != 1 AND IFNULL(sp_td.CheckOut, 1) != 1 THEN 1
                   ELSE 0
                END
            ), 0)as complete,
            IFNULL(sum(
               CASE
                   WHEN IFNULL(sp_td.CheckIn, 1) = 1 OR IFNULL(sp_td.CheckOut, 1) = 1 THEN 1
                   ELSE 0
               END
            ), 0)as miss,
            temp.TempDateID,
            temp.Date,
            sp_t.CompanyID,
        ");
        $this->db->join("SP_TransactionRoute as sp_t", "temp.Date = sp_t.Date", "left");
        $this->db->join("SP_TransactionRouteDetail as sp_td", "sp_t.TransactionRouteID = sp_td.TransactionRouteID", "left");
        if($StartDate):
            $this->db->where("temp.Date >=",$StartDate);
            $this->db->where("temp.Date <=",$EndDate);
        endif;
        $this->db->where("sp_t.CompanyID", $this->session->CompanyID);
        $this->db->or_where("sp_t.CompanyID",null);
        $this->db->group_by("temp.TempDateID");
        $this->db->group_by("temp.Date");
        $this->db->group_by("sp_t.CompanyID");
        $this->db->order_by("temp.Date");
        $query = $this->db->get("TempDate as temp");

        header('Content-Type: application/json');
        echo json_encode($query->result(),JSON_PRETTY_PRINT);
        echo json_encode($list);
    }

    public function tes_loop_date(){
        $data = $this->main->dashboard();

        echo json_encode($data["total_route"]);
    }

    #coa
    public function coa_select(){
        $this->main->cek_session();
        $classnya   = $this->input->post("classnya");
        $version    = $this->input->post("version");
        if($version == "serverSide"):
            $data   = array();

            $dServer= $this->coa_data_serverside("list");
            $list   = $this->api->coa_select("","","","serverSide",$dServer);

            foreach ($list as $k => $v) {
                $row = array();

                $tag_data    = ' data-classnya="'.$classnya.'" ';
                $tag_data    .=' data-id="'.$v->ID.'" ';
                $tag_data    .=' data-code="'.$v->Code.'" ';
                $tag_data    .=' data-name="'.$v->Name.'" ';
                $tag_data    .=' data-level="'.$v->Level.'" ';

                $row[] = '<a href="javascript:void(0)"  '.$tag_data.' onclick="chose_coa(this)">'.$v->Code.'</a>';
                $row[] = '<a href="javascript:void(0)"  '.$tag_data.' onclick="chose_coa(this)">'.$v->Name.'</a>';
                $row[] = '<a href="javascript:void(0)"  '.$tag_data.' onclick="chose_coa(this)">'.$v->Level.'</a>';
                $row[] = '<a href="javascript:void(0)"  '.$tag_data.' onclick="chose_coa(this)">'.$v->parentName.'</a>';

                $data[] = $row;
            }
            
            $count_all       = $this->coa_data_serverside("count_all");
            $count_filtered  = $this->coa_data_serverside("count_filtered");
            $output = array(
                "draw"            => $_POST['draw'],
                "recordsTotal"    => $this->api->coa_select("","","","serverSide",$count_all),
                "recordsFiltered" => $this->api->coa_select("","","","serverSide",$count_filtered),
                "data"            => $data,
            );
            echo json_encode($output);
        else:
            $data = $this->api->coa_select();
            $this->main->echoJson($data);
        endif;
    }
    private function coa_data_serverside($page){
        $dServer = array(
            "column"    => array("AC_COA.Code","AC_COA.Name","AC_COA.Position","parent.Name"),
            "page"      => $page,
            "order_by"  => array('AC_COA.Code' => "asc"),
        );

        return $dServer;
    }
    public function template_select(){
        // $this->main->cek_session();
        $position   = $this->input->post("position");
        $type       = $this->input->post("type");
        $data       = $this->api->template_select();
        if($position != "group"):
            $output = array(
                "hakakses"  => $this->session->hak_akses,
                "data"      => $data,
                "default"   => $this->main->get_default_template($type),
            );
        else:

            $arrType = array();

            $purchase = $penerimaan = $retur = $invoice_ap = $ap_correction = $payment_ap = array(); // Payable
            $selling = $delivery = $return_sales = $invoice_ar = $ar_correction = $payment_ar = array();

            foreach ($data as $v) {
                if($v->Type == 'purchase'): array_push($purchase, $v); if(!in_array("purchase",$arrType)): array_push($arrType, "purchase"); endif;
                elseif($v->Type == "penerimaan"): array_push($penerimaan, $v); if(!in_array("penerimaan",$arrType)): array_push($arrType, "penerimaan"); endif;
                elseif($v->Type == "retur"): array_push($retur, $v); if(!in_array("retur",$arrType)): array_push($arrType, "retur"); endif;
                elseif($v->Type == "invoice_ap"): array_push($invoice_ap, $v); if(!in_array("invoice_ap",$arrType)): array_push($arrType, "invoice_ap"); endif;
                elseif($v->Type == "ap_correction"): array_push($ap_correction, $v); if(!in_array("ap_correction",$arrType)): array_push($arrType, "ap_correction"); endif;
                elseif($v->Type == "payment_ap"): array_push($payment_ap, $v); if(!in_array("payment_ap",$arrType)): array_push($arrType, "payment_ap"); endif;
                elseif($v->Type == "selling"): array_push($selling, $v); if(!in_array("selling",$arrType)): array_push($arrType, "selling"); endif;
                elseif($v->Type == "delivery"): array_push($delivery, $v); if(!in_array("delivery",$arrType)): array_push($arrType, "delivery"); endif;
                elseif($v->Type == "return_sales"): array_push($return_sales, $v); if(!in_array("return_sales",$arrType)): array_push($arrType, "return_sales"); endif;
                elseif($v->Type == "invoice_ar"): array_push($invoice_ar, $v); if(!in_array("invoice_ar",$arrType)): array_push($arrType, "invoice_ar"); endif;
                elseif($v->Type == "ar_correction"): array_push($ar_correction, $v); if(!in_array("ar_correction",$arrType)): array_push($arrType, "ar_correction"); endif;
                elseif($v->Type == "payment_ar"): array_push($payment_ar, $v); if(!in_array("payment_ar",$arrType)): array_push($arrType, "payment_ar"); endif;
                endif;
            }

            $output = array(
                "hakakses"  => $this->session->hak_akses,
                "page"      => "template",
            );

            foreach ($arrType as $v) {
                $output[$v]       = array("list" => ${$v}, "default" => $this->main->get_default_template($v));
            }
        endif;

        $this->main->echoJson($output);
    }
    public function save_coa(){
        $CompanyID  = $this->session->CompanyID;
        $post       = $this->input->post();
        
        foreach ($post as $k => $v) {
            $Key        = $k;
            $Value      = $this->input->post($Key);
            $PostName   = str_replace("_", " ", $Key);
            $cek = $this->db->count_all("UT_Rule where CompanyID = '$CompanyID' and Code = '$PostName'");
            if($Value):
                $Value  = explode("||", $Value);
                $data = array(
                    "CompanyID" => $CompanyID,
                    "Code"      => $PostName,
                    "nValue"    => $Value[0],
                    "cValue"    => $Value[1],
                );
                $this->save_coa_db($cek,$PostName,$data);
            else:
                $this->db->where("CompanyID", $CompanyID);
                $this->db->where("Code", $PostName);
                $this->db->delete("UT_Rule");
            endif;
        }   

        $res = array(
            "status"    => true,
            "message"   => "Success",
        );

        $this->main->echoJson($res);
    }
    
    private function save_coa_db($cek,$Code,$data){
        if($cek > 0):
            $data["UserCh"] = $this->session->nama;
            $data["DateCh"] = date("Y-m-d H:i:s");
            $this->db->where("Code",$Code);
            $this->db->where("CompanyID",$this->session->CompanyID);
            $this->db->update("UT_Rule",$data);
        else:
            $data["UserAdd"] = $this->session->nama;
            $data["DateAdd"] = date("Y-m-d H:i:s");
            $this->db->insert("UT_Rule",$data);
        endif;
    }

    public function get_coa_setting(){
        $post       = $this->input->post();
        $PostName   = array();
        foreach ($post as $k => $v) {
            $Key = str_replace("_", " ", $k);
            array_push($PostName, $Key);
        }
        $list = $this->api->get_coa_setting($PostName);
        $output = array(
            "hakakses"  => $this->session->hak_akses,
            "list"      => $list,
        );
        $this->main->echoJson($output);
    }
    #end coa

    // 20190102 MW
    # payment
    public function payment_list(){
        $list = $this->api->payment_list();
        $data = array(
            "app"       => $this->session->app,
            "list_data" => $list
        );
        $this->main->echoJson($data);
    }

    public function payment_add(){
        $id     = $this->input->post('id');
        $page   = $this->input->post('page');
        $data   = array();

        $total_pay  = 0;
        $total      = 0;
        $unpayment  = 0;

        // $id     = 'S190100001';
        // $page   = 'selling';

        if($id):
            $list       = $this->api->payment_list();
            foreach ($list as $k => $v) {
                $total_pay += $v->TotalAwal;
            }
            if($page == "selling"):
                $selling    = $this->api->selling($id,"detail");
                $data       = $selling;
                $total      = $selling->Payment;
            endif;
            $unpayment      = $total - $total_pay;
        endif;

        $data = array(
            "app"           => $this->session->app,
            "page"          => $page,
            "total_pay"     => $total_pay,
            "total"         => $total,
            "unpayment"     => $unpayment,
            "data"          => $data,
        );
        $this->main->echoJson($data);  
    }

    // 20190103 MW
    #return
    public function return_list(){
        $list = $this->api->return_list();
        $data = array(
            "app"       => $this->session->app,
            "list_data" => $list
        );
        $this->main->echoJson($data);
    }

    public function return_add(){
        $id     = $this->input->post('id');
        $page   = $this->input->post('page');
        $data   = array();
        $detail = array();

        $total_pay  = 0;
        $total      = 0;
        $unpayment  = 0;

        // $id     = 'S190100001';
        // $page   = 'selling';

        if($id):
            if($page == "selling"):
                $data    = $this->api->selling($id,"detail");
                $detail  = $this->api->selling_detail($id);
            elseif($page == "detail"):
                $data    = $this->api->return_detail($id,"detail");
                $detail  = $this->api->return_by_detail($id);
            endif;
        endif;

        $data = array(
            "app"           => $this->session->app,
            "page"          => $page,
            "data"          => $data,
            "detail"        => $detail,
        );
        $this->main->echoJson($data);  
    }

    public function return_view(){
        $id     = $this->input->post('id');
        $page   = $this->input->post('page');
        $data   = array();
        $detail = array();

        $total_pay  = 0;
        $total      = 0;
        $unpayment  = 0;

        // $id     = 'S190100001';
        // $page   = 'selling';

        if($id):
            if($page == "selling"):
                $data    = $this->api->return_detail($id,"detail");
                $detail  = $this->api->return_by_detail($id);
            endif;
        endif;

        $data = array(
            "app"           => $this->session->app,
            "page"          => $page,
            "data"          => $data,
            "detail"        => $detail,
        );
        $this->main->echoJson($data); 
    }

    public function php_info(){
        phpinfo();
    }

    // invoice delivery
    public function invoice_delivery(){
        $list = $this->main->invoice_delivery();
        // foreach ($list as $k => $v) {
        //     $d = $this->main->get_invoice_delivery_detail($v);
        //     $v->price           = $d['price'];
        //     $v->discount        = $d['discount'];
        //     $v->ppn             = $d['ppn'];
        //     $v->deliverycost    = $d['deliverycost'];
        //     $v->total           = $d['total'];
        // }
        $data = array(
            "hakakses"  => $this->session->hak_akses,
            "app"       => $this->session->app,
            "list"      => $list,
        );
        $this->main->echoJson($data);
    }

    // invoice delivery
    public function invoice_receive(){
        $list = $this->main->invoice_receive();
        // foreach ($list as $k => $v) {
        //     $d = $this->main->get_invoice_delivery_detail($v);
        //     $v->price           = $d['price'];
        //     $v->discount        = $d['discount'];
        //     $v->ppn             = $d['ppn'];
        //     $v->deliverycost    = $d['deliverycost'];
        //     $v->total           = $d['total'];
        // }
        $data = array(
            "hakakses"  => $this->session->hak_akses,
            "app"       => $this->session->app,
            "list"      => $list,
        );
        $this->main->echoJson($data);
    }

    // invoice selling
    public function invoice_sell(){
        $list = $this->main->invoice_sell();
        $CompanyID  = $this->session->CompanyID;
        foreach ($list as $k => $v) {
            if($v->invoiceType != "return"):
                $d = $this->main->get_invoice_sell_detail($v);
                $v->SubTotal = $d['sub_total'];
                $v->Discount = $d['discount'];
                $v->PPN      = $d['ppn'];
                $v->Total    = $d['total'];
            endif;
            $v->Date     = date("Y-m-d", strtotime($v->Date));
        }
        $data = array(
            "hakakses"  => $this->session->hak_akses,
            "app"       => $this->session->app,
            "list"      => $list,
        );
        $this->main->echoJson($data);
    }

    // invoice purchase
    public function invoice_purchase(){
        $list = $this->main->invoice_purchase();
        $CompanyID  = $this->session->CompanyID;
        foreach ($list as $k => $v) {
            if($v->invoiceType != "return"):
                $d = $this->main->get_invoice_purchase_detail($v);
                $v->SubTotal = $d['sub_total'];
                $v->Discount = $d['discount'];
                $v->PPN      = $d['ppn'];
                $v->Total    = $d['total'];
            endif;
            $v->Date     = date("Y-m-d", strtotime($v->Date));
        }
        $data = array(
            "hakakses"  => $this->session->hak_akses,
            "app"       => $this->session->app,
            "list"      => $list,
        );
        $this->main->echoJson($data);
    }

    public function invoice(){
        $invoice    = $this->main->invoice();
        $koreksi    = $this->main->ar_correction_invoice();
        $list  = $this->db->query('SELECT * FROM ('.$invoice.' UNION '.$koreksi .') AS Z order by Date');
        // $query  = $this->db->query('SELECT * FROM ('.$query1 . ' UNION '.$query2 . ' UNION ' . $query3.') AS Z order by No, ID,InvoiceID desc');
        $data = array(
            "hakakses"  => $this->session->hak_akses,
            "app"       => $this->session->app,
            "list"      => $list->result(),
        );
        $this->main->echoJson($data);
    }

    #delivery
    public function delivery($page="",$search=""){
        $classnya   = $this->input->post("classnya");
        $version    = $this->input->post("version");
        if($version == "serverSide"):
            $data   = array();

            $dServer= $this->delivery_data_serverside("list");
            $list   = $this->main->delivery($page,$search,"serverSide",$dServer);

            foreach ($list as $k => $v) {
                $row    = array();

                $tag_data    = ' data-classnya="'.$classnya.'" ';
                $tag_data    .=' data-code="'.$v->DeliveryNo.'" ';

                $row[] = '<a href="javascript:void(0)"  '.$tag_data.' onclick="chose_delivery(this)">'.$v->DeliveryNo.'</a>';
                $row[] = '<a href="javascript:void(0)"  '.$tag_data.' onclick="chose_delivery(this)">'.date("Y-m-d",strtotime($v->Date)).'</a>';

                $data[] = $row;
            }

            $count_all       = $this->delivery_data_serverside("count_all");
            $count_filtered  = $this->delivery_data_serverside("count_filtered");
            $output = array(
                "draw"            => $_POST['draw'],
                "recordsTotal"    => $this->main->delivery($page,$search,"serverSide",$count_all),
                "recordsFiltered" => $this->main->delivery($page,$search,"serverSide",$count_filtered),
                "data"            => $data,
            );
            echo json_encode($output);
        else:
            $list = $this->main->delivery($page,$search);
            $data = array(
                "hakakses"  => $this->session->hak_akses,
                "app"       => $this->session->app,
                "list_data" => $list,
            );
            $this->main->echoJson($data);
        endif;
    }
    private function delivery_data_serverside($page){
        $dServer = array(
            "column"    => array("PS_Delivery.DeliveryNo","PS_Delivery.Date"),
            "page"      => $page,
            "order_by"  => array('DATE(PS_Delivery.Date)' => "DESC"),
        );

        return $dServer;
    }
    public function delivery_detail($page="",$search=""){
        $search   = str_replace("-", "/", $search);
        $data = $this->main->delivery_detail($page,$search);
        
        $output = array(
            "status"    => TRUE,
            "message"   => "",
            "app"       => $this->session->app,
            "hakakses"  => $this->session->hak_akses,
            "list_data" => $data,
        );

        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT); 
    }
    #end delivery

    public function avrage(){
        $ProductID = 6;
        $this->db->select("
            ifnull(sum(grdet.Qty * grdet.Price), 0) as total,
            ifnull(sum(grdet.Qty), 0)               as qty,
            ");
        $this->db->join("AP_GoodReceipt as gr", "gr.ReceiveNo = grdet.ReceiveNo", "left");
        $this->db->where("grdet.CompanyID", $this->session->CompanyID);
        // $this->db->where("grdet.ProductID", $produtidnya);
        $this->db->where("grdet.ProductID", $ProductID);
        $this->db->where("gr.Status", 1);
        $this->db->from("AP_GoodReceipt_Det as grdet");
        $query  = $this->db->get();
        $d      = $query->row();

        // $qty_gr     = 1;
        // $price_gr   = 100000;

        // $sub_total      = $qty_gr * $price_gr;
        // $total_price    = $sub_total + $d->total;
        // $total_qty      = $d->qty + $qty_gr;

        // $harganya = $total_price / $total_qty;

        $harganya = $d->total / $d->qty;

        $data = array(
            "AveragePrice" => $harganya,
        );
        $this->db->where("CompanyID", $this->session->CompanyID);
        $this->db->where("ProductID", $ProductID);
        $this->db->update("ps_product", $data);

        // echo "1. qty transaksi sebelumnya : ".$d->qty." / total price sebelumnya : ".$d->total."<br>";
        // echo "2. qty gr : ".$qty_gr." / price gr :".$price_gr."<br>";
        // echo "3. sub total gr : ".$sub_total."<br>";
        // echo "4. total price : ".$total_price." / total qty : ".$total_qty."<br>";
        echo "5. hasilnya : ".$harganya;
    }


    public function outstanding_all(){
        $table = "PS_Sell_Detail";
        
        // ini untuk filter group
        $this->db->select("
            PS_Sell_Detail.SellNo,
            sell.Date,
            vendor.Name  as vendorName,
            product.Code as productCode,
            product.Name as productName,
            PS_Sell_Detail.Qty,
            ifnull(PS_Sell_Detail.DeliveryQty, 0) as DeliveryQty,
            (PS_Sell_Detail.Qty - ifnull(PS_Sell_Detail.DeliveryQty, 0)) as qtyResidue,
        ");
        $this->db->join("PS_Sell as sell", "PS_Sell_Detail.SellNo = sell.SellNo", "left");
        $this->db->join("PS_Vendor as vendor", "sell.VendorID = vendor.VendorID", "left");
        $this->db->join("ps_product as product", "PS_Sell_Detail.ProductID = product.ProductID", "left");
        $this->db->from($table);
        $this->db->where("(PS_Sell_Detail.Qty - ifnull(PS_Sell_Detail.DeliveryQty, 0))>0");

        $query = $this->db->get()->result();

        // $this->main->echoJson($query);

        // ini untuk filter sum
        $this->db->select("
            sell.SellNo,
            sell.Date,
            vendor.Name  as vendorName,
            SUM(PS_Sell_Detail.Qty) as Qty,
            SUM(ifnull(PS_Sell_Detail.DeliveryQty, 0)) as DeliveryQty,
            (SUM(PS_Sell_Detail.Qty) - SUM(ifnull(PS_Sell_Detail.DeliveryQty, 0))) as qtyResidue,
        ");
        $this->db->join("PS_Sell as sell", "PS_Sell_Detail.SellNo = sell.SellNo", "left");
        $this->db->join("PS_Vendor as vendor", "sell.VendorID = vendor.VendorID", "left");
        $this->db->from($table);
        $this->db->group_by("sell.SellNo,sell.Date,vendor.Name");
        $this->db->where("(PS_Sell_Detail.Qty - ifnull(PS_Sell_Detail.DeliveryQty, 0))>0");

        
        $query = $this->db->get()->result();

        $this->main->echoJson($query);

    }

    #conversion_balance
    public function conversion_balance(){
        $list = $this->api->coa_select();
        $output = array(
            "hak_akses" => $this->session->hak_akses,
            "list"      => $list,
        );

        $this->main->echoJson($output);
    }

    #check_menu_modul
    #20190517 MW
    public function check_menu_modul(){
        $this->main->cek_session("parameter");
        $modul = $this->input->post("modul");

        $arr_modul = array();
        $modul = json_encode($modul);
        $modul = json_decode($modul);
        foreach ($modul as $k => $a) {
            if($a->modul):
                $modul1 = $this->main->relpace_str($a->modul,"'",'"');
                $modul2 = $this->main->relpace_str($a->modul2,"'",'"');
                $modul1 = json_decode($modul1);
                $modul2 = json_decode($modul2);
                $view   = 0;
                foreach ($modul1 as $b) {
                    if($a->modul2):
                        foreach ($modul2 as $c) {
                            $list = $this->main->check_parameter_module($b,$c);
                            if($list->view == 1):
                                $view = 1;
                            endif;
                        }
                    else:
                        $list = $this->main->check_parameter_module($b,$b);
                        if($list->view == 1):
                            $view = 1;
                        endif;
                    endif;
                }
                // $list = $this->main->check_parameter_module($a->modul,$a->modul2);
                $a->view    = $view;
                $a->modul   = $modul1;
                array_push($arr_modul, $a);
            endif;
        }

        $output = array(
            "hakakses"  => $this->session->hak_akses,
            "modul"     => $arr_modul,
            "m"         => $modul,
        );

        $this->main->echoJson($output);
    }

    public function test_module()
    {
        $data = $this->main->random_number("api");
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }

    public function test_days_send1()
    {
        $data = $this->main->get_days();
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }

    public function ck_module_expire(){
        $this->main->cek_session();
        $date_now           = date("Y-m-d");
        $module             = $this->input->post("module");
        $parameter_modul    = $this->main->parameter_modul($module);
            
        $output = array(
            "status"    => false,
            "message"   => "data module not found",
            "hakakses" => $this->session->hak_akses,
        );

        if(in_array($module,$parameter_modul)):
            $Module             = $this->main->get_module_company();
            $parameter_modul    = $this->main->parameter_modul($module);

            if($Module->{$module}->status == 1):
                $date = date($Module->{$module}->expire);
                $days = ((strtotime ($date) - strtotime ($date_now))/(60*60*24));

                if($days<=30):
                    $output['status']   = true;
                    $output['message']  = "success";
                    $output['module']   = $this->main->label_modul2($module);
                    $output['date']     = $date;
                    $output['hari']     = $days;
                endif;
            endif;
        endif;

        $this->main->echoJson($output);
    }

    public function check_company_report_saldo($key=""){
        $key_temp = $this->main->hash("pipesys");
        if($key == $key_temp):
            $date_now   = date("Y-m-d");

            $this->db->select("id_user as ID,email");
            $this->db->where_in("App", array("all","pipesys"));
            $this->db->where_in("hak_akses", array("company","super_admin"));
            $this->db->where_in("id_user", array(243,180,1));
            $this->db->where("status", 1);
            $query  = $this->db->get("user");
            $data   = $query->result();

            $ck_log_saldo = $this->db->count_all("UT_Log where Page = 'report_saldo' and Date(DateAdd) = '$date_now'");

            $status = true;
            $message = "success";
            if(count($data)<=0 || $ck_log_saldo >0):
                $status     = false;
                $message    = "data not found";
                $data       = array();
            endif;
        else:
            $status  = false;
            $message = "403 Forbidden";
            $data    = array();
        endif;
        $output = array(
            "status"    => $status,
            "message"   => $message,
            "list"      => $data,
        );

        $this->main->echoJson($output);

    }

    public function send_report_saldo($id="",$key="",$p1=""){
        $day_now    = date("D");
        $date_now   = date("Y-m-d");
        $key_temp   = $this->main->hash("pipesys");
        
        if($key == $key_temp):
            $status = true;
            $message = "success";
            $this->db->select("dt.CompanyID,dt.Days,mt.Email,mt.nama");
            $this->db->join("user mt", "mt.id_user = dt.CompanyID");
            $this->db->from("SettingParameter dt");
            $this->db->where("mt.status", 1);
            $this->db->where("mt.id_user", $id);
            $this->db->where_in("mt.App", array("all","pipesys"));
            $setting_parameter = $this->db->get()->row();
            if($setting_parameter):
                $days = array();
                if($setting_parameter->Days):
                    $days = json_decode($setting_parameter->Days);
                endif;

                if(in_array($day_now,$days)):
                    $CompanyID = $id;
                    $total_receivable   = 0;
                    $total_payable      = 0;

                    $nama_laporan   = "";
                    $datacompany    = $this->main->company("detail", $CompanyID);

                    $file_name      = "";
                    $file_name_ap   = "";
                    // saldo_receivable
                    $saldo_receivable = $this->db->query("call sp_piutang('1990-01-01','$date_now','1','', '2')")->result();
                    mysqli_next_result( $this->db->conn_id );
                    // $this->db->close();

                    if(count($saldo_receivable)>0):
                        $file_name = $this->saldo_receivable($datacompany);
                    endif;

                    foreach ($saldo_receivable as $k2 => $v2) {
                        if(!$v2->VendorID):
                            $total_receivable += $v2->Saldo;
                        endif;
                    }

                    //saldo_ap
                    $saldo_ap = $this->db->query("call sp_hutang('1990-01-01','$date_now','1','', '2')")->result();
                    $this->db->close();
                    if(count($saldo_ap)>0):
                        $file_name_ap = $this->saldo_ap($datacompany);
                    endif;

                    foreach ($saldo_ap as $k3 => $v3) {
                        if(!$v3->VendorID):
                            $total_payable += $v3->Saldo;
                        endif;
                    }

                    if($total_receivable>0 || $total_payable>0):
                        $data = array(
                            "Supplier"      => $setting_parameter->nama,
                            "VendorEmail"   => $setting_parameter->Email,
                            "total_receivable"  => $total_receivable,
                            "total_payable"     => $total_payable,
                            "file_name_ar"      => $file_name,
                            "file_name_ap"      => $file_name_ap,
                        );

                        $data = json_encode($data);
                        $data = json_decode($data);
                        $this->main->send_email("saldo_receivable",$data,$p1);
                    endif;
                endif;
            endif;

            // $this->main->insert_log("7","report_saldo","");
        else:
            $status = false;
            $message = "403 Forbidden";
        endif;

        $output = array(
            "status"    => $status,
            "message"   => $message,
            "id"        => $id,
        );

        // $this->main->echoJson($output);
    }

    public function send_report_days(){
        // $select_days = array();
        // $type        = CAL_GREGORIAN;
        // $month       = date('n'); // Month ID, 1 through to 12.
        // $year        = date('Y'); // Year in 4 digit 2009 format.
        // $day_count   = cal_days_in_month($type, $month, $year); // Get the amount of days

        // //loop through all days
        // for ($i = 1; $i <= $day_count; $i++) {

        //     $date       = $year.'/'.$month.'/'.$i; //format date
        //     $get_name   = date('l', strtotime($date)); //get week day
        //     $day_name   = substr($get_name, 0, 3); // Trim day name to 3 chars
        //     //if not a weekend add day to array
        //      if($day_name == 'Sun'){
        //         $select_days[] = $i;
        //     }

        // }

        // // look at items in the array uncomment the next line
        // print_r($select_days);
        $day_now    = date("D");
        $date_now   = date("Y-m-d");

        $this->db->select("dt.CompanyID,dt.Days,mt.Email,mt.nama");
        $this->db->join("user mt", "mt.id_user = dt.CompanyID");
        $this->db->from("SettingParameter dt");
        $this->db->where("mt.status", 1);
        $this->db->where_in("mt.App", array("all","pipesys"));
        $setting_parameter = $this->db->get()->result();

        $d = array();
        foreach ($setting_parameter as $k => $v) {
            $days = array();
            if($v->Days):
                $days = json_decode($v->Days);
            endif;

            if(in_array($day_now,$days)):
                $CompanyID = $v->CompanyID;
                $total_receivable   = 0;
                $total_payable      = 0;

                $nama_laporan   = "";
                $datacompany    = $this->main->company("detail", $CompanyID);

                $file_name      = "";
                $file_name_ap   = "";
                // saldo_receivable
                $saldo_receivable = $this->db->query("call sp_piutang('1990-01-01','$date_now','1','', '2')")->result();
                mysqli_next_result( $this->db->conn_id );
                // $this->db->close();

                if(count($saldo_receivable)>0):
                    $file_name = $this->saldo_receivable($datacompany);
                endif;

                // foreach ($saldo_receivable as $k2 => $v2) {
                //     if(!$v2->VendorID):
                //         $total_receivable += $v2->Saldo;
                //     endif;
                // }

                // saldo_ap
                // $saldo_ap = $this->db->query("call sp_hutang('1990-01-01','$date_now','1','', '2')")->result();
                // $this->db->close();
                // if(count($saldo_ap)>0):
                    // $file_name_ap = $this->saldo_ap($datacompany);
                // endif;

                // foreach ($saldo_ap as $k3 => $v3) {
                //     if(!$v3->VendorID):
                //         $total_payable += $v3->Saldo;
                //     endif;
                // }

                if($total_receivable>0 || $total_payable>0):

                    $data = array(
                        "Supplier"      => $v->nama,
                        "VendorEmail"   => $v->Email,
                        "total_receivable"  => $total_receivable,
                        "total_payable"     => $total_payable,
                        "file_name_ar"      => $file_name,
                        "file_name_ap"      => $file_name_ap,
                    );
                    $data = json_encode($data);
                    $data = json_decode($data);
                    $this->main->send_email("saldo_receivable",$data);
                endif;
                // echo "<pre>";
                // print_r($saldo_ap);
                // echo "</pre>";
            endif;
        }
    }

    public function show_attachment(){
        $this->main->cek_session();
        $status  = false;
        $message = 'Data not found';
        $data    = array();

        $ID     = $this->input->post("ID");
        $modul  = $this->input->post("modul");

        if($ID):
            $ID = str_replace("-", "/", $ID);
            $status = true;
            $message= "success";
            $data = $this->main->attachment_show($modul,$ID);
        endif;

        $output = array(
            "status"    => $status,
            "message"   => $message,
            "attach"    => $data,
        );

        $this->main->echoJson($output);
    }

    private function saldo_receivable($datacompany){
        $this->load->library('dompdf_gen');
        $day_now        = date("D");
        $date_now       = date("Y-m-d");
        $nama_laporan   = "Saldo Receivable";
        $company_name   = $datacompany->nama;
        $company_imgbin = $datacompany->img_bin;
        $company_imgurl = $datacompany->img_url;
        $logo           = base_url($company_imgurl);

        $saldo_receivable2 = $this->db->query("call sp_piutang('1990-01-01','$date_now','$datacompany->CompanyID','', '3')")->result();
        mysqli_next_result( $this->db->conn_id );
        $file_name              = "saldo_receivable".date("YmdHis").".pdf";
        $data['group']          = "transaction";
        $data['i']              = 1;
        $data['no']             = 1;
        $data['list']           = $saldo_receivable2;
        $data["company_name"]   = $company_name;
        $data["title"]          = $nama_laporan;
        $data["nama_laporan"]   = $nama_laporan;
        $data["logo"]           = $logo;
        $data["table"]          = "report/table_saldo_receivable";
        $data['cetak']          = "print";
        $data['start_date']     = "-";
        $data['end_date']       = $date_now;
        $html = $this->load->view("report/index_cetak",$data,true);
        $this->dompdf->set_paper('a4', 'landscape');
        $this->dompdf->load_html($html);
        $this->dompdf->render();
        $output = $this->dompdf->output();
        file_put_contents('file/'.$file_name, $output);
        // $this->dompdf->reset_pdf();
        return $file_name;
    }

    private function saldo_ap($datacompany){
        $this->load->library('dompdf_gen2');

        $day_now        = date("D");
        $date_now       = date("Y-m-d");
        $nama_laporan   = "Saldo Payable";
        $company_name   = $datacompany->nama;
        $company_imgbin = $datacompany->img_bin;
        $company_imgurl = $datacompany->img_url;
        $logo           = base_url($company_imgurl);

        $saldo_ap2 = $this->db->query("call sp_hutang('1990-01-01','$date_now','1','', '3')")->result();
        $this->db->close();
        $file_name              = "saldo_ap".date("YmdHis").".pdf";
        $data['group']          = "transaction";
        $data['i']              = 1;
        $data['no']             = 1;
        $data['list']           = $saldo_ap2;
        $data["company_name"]   = $company_name;
        $data["title"]          = $nama_laporan;
        $data["nama_laporan"]   = $nama_laporan;
        $data["logo"]           = $logo;
        $data["table"]          = "report/table_saldo_ap";
        $data['cetak']          = "print";
        $html2 = $this->load->view("report/index_cetak",$data,true);
        $this->dompdf2->set_paper('a4', 'landscape');
        $this->dompdf2->load_html($html2);
        $this->dompdf2->render();
        $output2 = $this->dompdf2->output();
        file_put_contents('file/'.$file_name, $output2);

        return $file_name;
    }

    private function generate_vendor_code(){
        $this->db->select("id_user,nama,email,hak_akses,App");
        $this->db->where_in("hak_akses", array("company","super_admin"));
        $this->db->where_in("App", array("pipesys","all"));
        // $this->db->where_in("id_user", array(1));
        $user = $this->db->get("user")->result();

        foreach ($user as $k => $v) {
            $this->db->select("VendorID,Name,Code,Position,CompanyID");
            $this->db->where("App", "pipesys");
            $this->db->where("CompanyID", $v->id_user);
            $vendor = $this->db->get("PS_Vendor")->result();

            $vendor_code_int    = 1;
            $customer_code_int  = 1;
            $vendor_code        = '';
            $customer_code      = '';
            $lebar              = 4;
            $vendor_awalan      = "VND";
            $customer_awalan    = "CST";

            foreach ($vendor as $kk => $vv) {
                if($vv->Position == 1):
                    if($vendor_code_int != 1):
                        $nomor = intval(substr($vendor_code,strlen($vendor_awalan))) + 1;
                    else:
                        $nomor = $vendor_code_int;
                    endif;
                    $vendor_code = $vendor_awalan.str_pad($nomor,$lebar,"0",STR_PAD_LEFT);
                    $vendor_code_int += 1;

                    $code = $vendor_code;
                else:
                    if($customer_code_int != 1):
                        $nomor = intval(substr($customer_code,strlen($customer_awalan))) + 1;
                    else:
                        $nomor = $customer_code_int;
                    endif;
                    $customer_code = $customer_awalan.str_pad($nomor,$lebar,"0",STR_PAD_LEFT);
                    $customer_code_int += 1;

                    $code = $customer_code;
                endif;

                $data_address = array(
                    "VendorID"      => $vv->VendorID,
                    "VendorCode"    => $code,
                    "CompanyID"     => $v->id_user,
                );
                $this->db->where("VendorCode", $vv->Code);
                $this->db->update("ps_vendor_address", $data_address);


                $data_contact = array(
                    "VendorID"      => $vv->VendorID,
                    "VendorCode"    => $code,
                    "CompanyID"     => $v->id_user,
                );
                $this->db->where("VendorCode", $vv->Code);
                $this->db->update("ps_vendor_contact", $data_contact);

                $data = array(
                    "Code"  => $code,
                );
                $this->db->where("VendorID", $vv->VendorID);
                $this->db->update("PS_Vendor", $data);
            }
        }
    }

    private function generate_payment_type(){
        $this->db->select("PaymentNo,CompanyID,PaymentType,PaymentMethod,Type");
        $this->db->where_in("PaymentType", array(1,2));
        $this->db->where("Type", 2);
        $list = $this->db->get("PS_Payment")->result();

        $arrData = array();
        foreach ($list as $k => $v) {
            $data = array(
                "PaymentType"       => null,
                "PaymentMethod"     => null,
            );
            if($v->PaymentType == 1):
                $data['PaymentType1']       = 1;
                $data['PaymentMethod1']     = $v->PaymentMethod;
            else:
                $data['PaymentType2']       = 2;
                $data['PaymentMethod2']     = $v->PaymentMethod;
            endif;
            array_push($arrData, $data);
            // $this->db->where("CompanyID", $v->CompanyID);
            // $this->db->where("PaymentNo", $v->PaymentNo);
            // $this->db->where("Type", 2);
            // $this->db->update("PS_Payment", $data);
        }
        $this->main->echoJson($list);
    }

    private function generate_product_unit(){
        $this->db->select("id_user,nama,email,hak_akses,App");
        $this->db->where_in("hak_akses", array("company","super_admin"));
        $this->db->where_in("App", array("pipesys","all"));
        // $this->db->where_in("id_user", array(1));
        $user = $this->db->get("user")->result();

        foreach ($user as $k => $v) {
            $CompanyID = $v->id_user;

            $this->db->select("ps_product.ProductID,ps_unit.name as unit_name,ps_product.Uom,unit.ProductUnitID");
            $this->db->join("ps_product_unit as unit", "ps_product.Uom = unit.Uom and ps_product.ProductID = unit.ProductID", "left");
            $this->db->join("ps_unit", "ps_unit.UnitID = ps_product.UnitID", "left");
            $this->db->where("ps_product.CompanyID", $CompanyID);
            $this->db->where("ps_product.Position", 0);
            $product = $this->db->get("ps_product")->result();

            foreach ($product as $v2) {
                if(!$v2->ProductUnitID):
                    $uom = 'pcs';
                    if($v2->Uom):
                        $uom = $v2->Uom;
                    elseif($v2->unit_name):
                        $uom = $v2->unit_name;
                    endif;
                    $data = array(
                        "Uom"           => $uom,
                        "Uom2"          => $uom,
                        "Conversion"    => 1,
                        "ProductID"     => $v2->ProductID,
                        "CompanyID"     => $CompanyID,
                        "User_Add"      => $v->nama,
                        "Active"        => 1,
                        "Date_Add"      => date("Y-m-d H:i:s"),
                    );
                    $this->db->insert("ps_product_unit", $data);
                endif;
            }
        }

    }

    public function generate_product_unit2(){
        $CompanyID = '1';

        $this->db->select("ps_product.ProductID,ps_unit.name as unit_name,ps_product.Uom,unit.ProductUnitID");
        $this->db->join("ps_product_unit as unit", "ps_product.Uom = unit.Uom and ps_product.ProductID = unit.ProductID", "left");
        $this->db->join("ps_unit", "ps_unit.UnitID = ps_product.UnitID", "left");
        $this->db->where("ps_product.CompanyID", $CompanyID);
        $this->db->where("ps_product.Position", 0);
        $product = $this->db->get("ps_product")->result();
        foreach ($product as $v2) {
            if(!$v2->ProductUnitID):
                $uom = 'pcs';
                if($v2->Uom):
                    $uom = $v2->Uom;
                elseif($v2->unit_name):
                    $uom = $v2->unit_name;
                endif;
                $data = array(
                    "Uom"           => $uom,
                    "Uom2"          => $uom,
                    "Conversion"    => 1,
                    "ProductID"     => $v2->ProductID,
                    "CompanyID"     => $CompanyID,
                    "User_Add"      => $v->nama,
                    "Active"        => 1,
                    "Date_Add"      => date("Y-m-d H:i:s"),
                );
                $this->db->insert("ps_product_unit", $data);
            endif;
        }
    }

    public function create_language(){
        $CompanyID = $this->session->CompanyID;
        if($CompanyID):
            $this->main->create_file_language();
            $output = array(
                "status"    => true,
                "message"   => $this->lang->line("lb_success"),
            );
        else:
            $output                   = array();
            $output['status']         = FALSE;
            $output['message']        = $this->lang->line("lb_data_not_found");
        endif;

        $this->main->echoJson($output);
    }

    public function create_site(){
        $CompanyID = $this->session->CompanyID;
        if($CompanyID):
            $this->main->create_site_file();
            $output = array(
                "status"    => true,
                "message"   => $this->lang->line("lb_success"),
            );
        else:
            $output                   = array();
            $output['status']         = FALSE;
            $output['message']        = $this->lang->line("lb_data_not_found");
        endif;

        $this->main->echoJson($output);
    }

    #20190823 MW
    #create file bahasa
    #kali aja berguna
    private function create_file_language_php(){
        $a = "<?php defined('BASEPATH') OR exit('No direct script access allowed'); \n";
        $a .= "$"."lang"."['ora'] = 'ORA ORA 3'; \n";
        $a .= "$"."lang"."['ora2'] = 'lalala'; \n";
        $a .= "$"."lang"."['ora3'] = 'aiaiaaia'; \n";

        $file_nm      = "asiap_lang.php";
        $folder       = "system/language/english/";
        if(is_file($folder.$file_nm)){
            unlink($folder.$file_nm);
        }
        file_put_contents($folder.$file_nm, $a);
    }

    // 20190724 MW
    #voucher
    # simpan beli voucher frontend
    public function voucher_save(){
        $s_level    = $this->input->post('s_level');
        $status     = true;
        $message    = "Success";
        $s_level2   = "checkout";
        $data       = array();
        if($s_level):
            $this->validate_voucher_save();
            if($s_level == "checkout"):
                $s_level2 = "user_info";
                $Type       = $this->input->post('Type');
                $Qty        = $this->input->post('Qty');
                $QtyModule  = $this->input->post('QtyModule');
                $data = array(
                    's_level'               => $s_level2,
                    'voucher_qty'           => $Qty,
                    'voucher_qty_module'    => $QtyModule,
                    'voucher_type'          => $Type,
                );
                $this->session->set_userdata($data);
            elseif($s_level == "user_info"):
                $s_level2   = "confirm";
                $Name       = $this->input->post('Name');
                $Email      = $this->input->post('Email');
                $Address    = $this->input->post('Address');
                $City       = $this->input->post('City');
                $State      = $this->input->post('State');
                $Country    = $this->input->post('Country');
                $Agree      = $this->input->post('Agree');

                // proses insert
                $insert         = 0;
                $CompanyID      = 0;
                $App            = "pipesys";
                $Type           = $this->session->voucher_type;
                $Qty            = $this->session->voucher_qty;
                $QtyModule      = $this->session->voucher_qty_module;
                $price          = 0.00;
                $price_total    = 0.00;
                $price_module   = 0.00;
                $module_total   = 0.00;
                $device_total   = 0.00;
                
                $company = $this->main->get_one_column("user","id_user,hak_akses",array("email" => $Email));
                if($company):
                    if(in_array($company->hak_akses, array("super_admin","company"))):
                        $CompanyID = $company->id_user;
                    endif;
                endif;

                $a              = $this->main->voucher_package($App,$Type,"2"); // additional
                $module         = $this->main->voucher_package($App,$Type,"1");
                if($a):
                    $price       = $a->Price;
                    $price_total = $price * $Qty * $Type;
                endif;
                if($module):
                    $price_module = $module->Price;
                    $module_total = $price_module * $QtyModule * $Type;
                endif;

                $TrxUnique       = mt_rand(200, 499);
                if($TrxUnique):
                    $TrxUniqueLg    = strlen($TrxUnique);
                    $price_totalLg  = strlen($price_total);
                    $substr         = $price_totalLg - $TrxUniqueLg;
                    // $price_total     = substr($price_total, 0,$substr)+$TrxUnique;
                    $price_total    = $price_total+$TrxUnique;
                    $module_total   = $module_total + $TrxUnique;
                endif;
                $code = $this->main->transaction_voucher_generate();

                if($QtyModule != "none"):
                    $data_module = array(
                        "CompanyID"     => $CompanyID,
                        "Code"          => $code,
                        'Date'          => date("Y-m-d"),
                        'App'           => $App,
                        'Type'          => $Type,
                        'Bank'          => "OCBC - NISP",
                        'Price'         => str_replace(",", "", $price_module),
                        'TotalPrice'    => str_replace(",", "", $module_total),
                        'Qty'           => $QtyModule,
                        'ExpirePurchase'=> date("Y-m-d",strtotime("+7days")),
                        'TrxUnique'     => $TrxUnique,
                        'Module'        => "1",
                        'Name'          => $Name,
                        'Email'         => $Email,
                        'Address'       => $Address,
                        'City'          => $City,
                        'State'         => $State,
                        'Country'       => $Country,
                    );
                    $insert = $this->insert_voucher($data_module);
                endif;

                if($Qty != "none"):
                    $data_voucher   = array(
                        'CompanyID'     => $CompanyID,
                        'Date'          => date("Y-m-d"),
                        'App'           => $App,
                        'Type'          => $Type,
                        'Bank'          => "OCBC - NISP",
                        'Price'         => str_replace(",", "", $price),
                        'TotalPrice'    => str_replace(",", "", $price_total),
                        'Qty'           => $Qty,
                        'ExpirePurchase'=> date("Y-m-d",strtotime("+7days")),
                        'TrxUnique'     => $TrxUnique,
                        'Module'        => "2",
                        'Name'          => $Name,
                        'Email'         => $Email,
                        'Address'       => $Address,
                        'City'          => $City,
                        'State'         => $State,
                        'Country'       => $Country,
                    );
                    if($QtyModule == "none"):
                        $data_voucher['Code']       = $code;
                        $insert = $this->insert_voucher($data_voucher);
                    else:
                        $data_voucher['ParentID']   = $insert;
                        $insert2 = $this->insert_voucher($data_voucher);
                    endif;
                endif;

                // data untuk session
                $data = array(
                    "s_level"           => $s_level2,
                    "voucher_name"      => $Name,
                    "voucher_email"     => $Email,
                    "voucher_address"   => $Address,
                    "voucher_city"      => $City,
                    "voucher_state"     => $State,
                    "voucher_country"   => $Country,
                    "voucher_agree"     => $Agree,
                    "voucher_code"      => $code,
                );
                $this->session->set_userdata($data);
                if($insert):
                    $this->main->send_email("buy_voucher",$insert);
                endif;
            elseif($s_level == "confirm"):
                $s_level2 = "";
                $message = "complete";
                $this->main->session_voucher_reset();
            endif;

            $output = array(
                "status"    => $status,
                "message"   => $message,
                "s_level"   => $s_level2,
                "data"      => $data,
            );
        else:
            $output = array(
                "status"    => $status,
                "message"   => $message,
            );
        endif;
        $this->main->echoJson($output);
    }

    private function validate_voucher_save(){
        $data                   = array();
        $data['error_string']   = array();
        $data['inputerror']     = array();
        $data['status']         = TRUE;

        $s_level    = $this->input->post('s_level');

        if($s_level == "checkout"):
            if($this->input->post('Type') == 'none'):
                $data['inputerror'][]   = 'Type';
                $data['error_string'][] = $this->lang->line('lb_voucher_type_choose');
                $data['status']         = FALSE;
            endif;
            if($this->input->post('Qty') == 'none' and $this->input->post("QtyModule") == "none"):
                $data['inputerror'][]   = 'Qty';
                $data['error_string'][] = $this->lang->line('lb_choose_qty');
                $data['inputerror'][]   = 'Module';
                $data['error_string'][] = $this->lang->line('lb_choose_qty');
                $data['status']         = FALSE;
            endif;
        elseif($s_level == "user_info"):
            // if(!preg_match("/^[a-zA-Z ]*$/",$this->input->post("Name"))):
            //     $data['inputerror'][]   = 'Name';
            //     $data['error_string'][] = $this->lang->line('lb_name_validate');
            //     $data['status']         = FALSE;
            // endif;
            if($this->input->post("Name") == ''):
                $data['inputerror'][]   = 'Name';
                $data['error_string'][] = $this->lang->line('lb_name_empty');
                $data['status']         = FALSE;
            endif;
            if (!filter_var($this->input->post("Email"), FILTER_VALIDATE_EMAIL)):
                $data['inputerror'][]   = 'Email';
                $data['error_string'][] = $this->lang->line('lb_email_format');
                $data['status']         = FALSE;
            endif;
            if($this->input->post("Email") == ''):
                $data['inputerror'][]   = 'Email';
                $data['error_string'][] = $this->lang->line('lb_email_empty');
                $data['status']         = FALSE;
            endif;
        endif;

        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }

    private function insert_voucher($data)
    {
        $this->db->set("UserAdd",$this->session->NAMA);
        $this->db->set("DateAdd",date("Y-m-d H:i:s"));
        $this->db->insert("Voucher", $data);
        return $this->db->insert_id();
    }

    public function back_voucher(){
        $s_level = 'checkout';
        $page    = $this->input->post("page");
        $status  = false;
        if($this->session->s_level and $page == "voucher"):
            if($this->session->s_level == "user_info"):
                $data = array("s_level" => "checkout");
                $this->session->set_userdata($data);
                $status     = true;
                $s_level    = "checkout";
            elseif($this->session->s_level == "confirm"):
                $data = array(
                    "s_level" => "user_info",
                );
                $this->session->set_userdata($data);
                $status = true;
                $s_level    = "user_info";
            endif;
        endif;
        $output = array(
            "status"    => $status,
        );
        if($status):
            $output['s_level'] = $s_level;
        endif;

        $this->main->echoJson($output);
    }

    #20190731 MW
    #voucher use save
    public function voucher_save_use(){
        $Module     = $this->input->post('Module');
        $Voucher    = $this->input->post("Voucher");
        $ID         = $this->input->post("ID");

        if(in_array($Module, array('ap','ar','ac','inventory'))):
            $this->validate_voucher_save_use("module", 1);
            $ExpireDate = $this->main->UseVoucher($Voucher,$Module,"module");
            $output = array(
                "status"    => true,
                "message"   => $this->lang->line('lb_success'),
                "Module"    => $Module,
                "Expire"    => $ExpireDate,
            );
        else:
            $this->validate_voucher_save_use("additional", 2);
            $this->main->UseVoucher($Voucher,$ID,"additional");
            $output = array(
                "status"    => true,
                "message"   => $this->lang->line('lb_success'),
            );
        endif;

        $this->main->echoJson($output);
    }

    // validasi untuk pengecekan insert voucher additional user
    private function validate_voucher_save_use($page,$type){
        $data                   = array();
        $data['error_string']   = array();
        $data['inputerror']     = array();
        $data['status']         = TRUE;

        $Voucher    = $this->input->post("Voucher");
        $ID         = $this->input->post("ID");
        $CompanyID  = $this->session->CompanyID;

        $ck_voucher = $this->db->count_all("VoucherDetail dt join Voucher mt on dt.VoucherID = mt.VoucherID where dt.Code = '$Voucher' and dt.Status = 'not' and mt.Module = '$type'");

        if($ck_voucher<=0):
            $data['inputerror'][]   = 'Voucher';
            $data['error_string'][] = $this->lang->line('lb_voucher_not_found');
            $data['message']        = $this->lang->line('lb_voucher_not_found');
            $data['status']         = FALSE;
        endif;

        if($Voucher == ''):
            $data['inputerror'][]   = 'Voucher';
            $data['error_string'][] = $this->lang->line('lb_voucher_empty');
            $data['message']        = $this->lang->line('lb_voucher_empty');
            $data['status']         = FALSE;
        endif;

        if($page == "additional"):
            $ck_user    = $this->db->count_all("user where CompanyID = '$CompanyID' and id_user = '$ID' and hak_akses = 'additional'");
            if($ck_user<=0):
                $data['inputerror'][]   = '';
                $data['error_string'][] = '';
                $data['status']         = FALSE;
                $data['message']        = $this->lang->line('lb_user_not_found');
            endif;

            if($ID == ''):
                $data['inputerror'][]   = '';
                $data['error_string'][] = '';
                $data['status']         = FALSE;
                $data['message']        = $this->lang->line('lb_error_data');
            endif;
        endif;
        
        if(!$CompanyID):
            $data['inputerror'][]   = '';
            $data['error_string'][] = '';
            $data['status']         = FALSE;
            $data['message']        = $this->lang->line('lb_error_data');
        endif;

        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }

    #20190808 MW
    #save temp serial number
    public function save_serial_temp(){
        $CompanyID  = $this->session->CompanyID;
        $UserID     = $this->session->UserID;

        if($CompanyID):
            $this->validate_serial_number();

            $ID             = $this->input->post("ID");
            $header_code    = $this->input->post("header_code");
            $page           = $this->input->post("page");
            $serial         = $this->input->post("serial");
            $detailsn       = $this->input->post('detailsn');
            $headerID       = $this->input->post('headerID');
            $detailID       = $this->input->post('detailID');

            $form       = json_decode($serial);
            
            $this->db->where("CompanyID", $CompanyID);
            $this->db->where("UserID", $UserID);
            $this->db->where("Class", $header_code);
            $this->db->where("ProductID", $ID);
            $this->db->where("Page", $page);
            $this->db->delete("Temp_Serial_Number");

            foreach ($form as $k => $v) {
                $data = array(
                    "CompanyID" => $CompanyID,
                    "UserID"    => $UserID,
                    "Class"     => $header_code,
                    "ProductID" => $ID,
                    "Page"      => $page,
                    "SN"        => $v,
                );
                $this->db->insert("Temp_Serial_Number",$data);
            }

            $output = array(
                "status"    => true,
                "message"   => $this->lang->line('lb_success'),
            );
        else:
            $output                   = array();
            $output['error_string']   = array();
            $output['inputerror']     = array();
            $output['list']           = array();
            $output['status']         = FALSE;
            $output['message']        = $this->lang->line("lb_data_not_found");
        endif;

        $this->main->echoJson($output);
    }

    private function validate_serial_number(){
        $data                   = array();
        $data['error_string']   = array();
        $data['inputerror']     = array();
        $output['list']         = array();
        $output['index']        = array();
        $data['status']         = TRUE;

        $CompanyID  = $this->session->CompanyID;
        $UserID     = $this->session->UserID;

        $ID             = $this->input->post("ID");
        $header_code    = $this->input->post("header_code");
        $page           = $this->input->post("page");
        $page2          = $this->input->post("page2");
        $serial         = $this->input->post("serial");
        $tempID         = $this->input->post("tempID");
        $detailsn       = $this->input->post('detailsn');
        $headerID       = $this->input->post('headerID');
        $detailID       = $this->input->post('detailID');
        $BranchID       = $this->input->post('BranchID');
        if($BranchID):
            $BranchID = explode("-", $BranchID); $BranchID = $BranchID[0];
        endif;

        $form       = json_decode($serial);

        if(!$CompanyID):
            $data['inputerror'][]   = "";
            $data['error_string'][] = '';
            $data['list'][]         = '';
            $data['status']         = FALSE;
            $data['message']        = "Please check again serial number";
            $this->main->echoJson($data);
            exit();
        endif;
        if(count($form)<=0):
            $data['inputerror'][]   = "";
            $data['error_string'][] = '';
            $data['list'][]         = '';
            $data['status']         = FALSE;
            $data['message']        = "Please check again serial number";
            $this->main->echoJson($data);
            exit();
        endif;

        $temp_data  = $this->api->temp_serial($page,$form,$header_code,$ID);
        $serialtxt  = 'serialno';
        if($detailsn == "active"):
            if($page == "retur"): $sn_data = $this->api->good_receipt_serial("data_active",$ID,$headerID,$detailID); $serialtxt = 'SN';
            elseif($page == "delivery" && $page2 == "active"):
                $sn_data = $this->api->selling_serial("data_active",$ID,$headerID,$detailID); $serialtxt = 'SN';
            elseif($page == "return_sales" && $page2 == "active"):
                $sn_data = $this->api->selling_serial("data_active",$ID,$headerID,$detailID); $serialtxt = 'SN';
            elseif($page == "return_sales"):
                $sn_data = $this->api->delivery_serial("data_active",$ID,$headerID,$detailID); $serialtxt = 'SN';
            elseif($page == "stock_correction" || $page == "stock_opname"): $sn_data = $this->main->product_serial("array",$form,0,"active",$BranchID);
            elseif($page == "mutation" && $page2):
                $BranchID = explode("-", $page2); $BranchID = $BranchID[0];
                $sn_data = $this->main->product_serial("array",$form,$ID,"active",$BranchID);
            elseif($page == "good_issue" && $BranchID):
                $sn_data = $this->main->product_serial("array",$form,$ID,"active",$BranchID);
            else:
                if($BranchID):
                    $sn_data    = $this->main->product_serial("array",$form,$ID,"active",$BranchID);
                else: $sn_data    = $this->main->product_serial("array",$form,$ID,"active");
                endif;
            endif;
        else:
            if($BranchID):
                $sn_data    = $this->main->product_serial("array",$form,$ID,"active",$BranchID);
            else: $sn_data    = $this->main->product_serial("array",$form,$ID,"active");
            endif;
        endif;

        foreach ($form as $k => $v) {
            if(!$v):
                $data['inputerror'][]   = $header_code;
                $data['error_string'][] = "Serial number can't be null";
                $data['list'][]         = 'list';
                $data['index'][]        = $k;
                $data['status']         = FALSE;
            else:
                $temp_sn = array_search($v, array_column($temp_data, 'SN'));
                $temp_sn_length = strlen($temp_sn);

                $sn = array_search($v, array_column($sn_data, $serialtxt));
                $sn_length = strlen($sn);

                if($temp_sn_length>0):
                    $temp_id = $temp_data[$temp_sn]->Class;
                    if($temp_id != $header_code):
                        $data['inputerror'][]   = $header_code;
                        $data['error_string'][] = "Serial number aleardy exists";
                        $data['list'][]         = 'list';
                        $data['index'][]        = $k;
                        $data['status']         = FALSE;
                    endif;
                else:
                    $arr_modul_page = array('stock_opname','stock_correction');
                    if($detailsn == "active"):
                        if($sn_length<=0 && !in_array($page,$arr_modul_page)):
                            $data['inputerror'][]   = $header_code;
                            $data['error_string'][] = "Serial number not found";
                            $data['list'][]         = 'list';
                            $data['index'][]        = $k;
                            $data['status']         = FALSE;
                        endif;
                    else:
                        if($sn_length>0):
                            $data['inputerror'][]   = $header_code;
                            $data['error_string'][] = "Serial number aleardy exists";
                            $data['list'][]         = 'list';
                            $data['index'][]        = $k;
                            $data['status']         = FALSE;
                        endif;
                    endif;
                endif;
            endif;
        }


        if($data['status'] === FALSE)
        {   
            // $data['a']  = $form;
            // $data['b']  = $temp_data;
            $data['message']        = "Please check again serial number";
            echo json_encode($data);
            exit();
        }
    }

    public function temp_serial_number_list(){
        $CompanyID  = $this->session->CompanyID;
        $UserID     = $this->session->UserID;

        $ID             = $this->input->post("ID");
        $header_code    = $this->input->post("header_code");
        $page           = $this->input->post("page");
        $dt_rowid       = $this->input->post("dt_rowid");
        $arrProductID   = $this->input->post("arrProductID");

        if($CompanyID):
            $dt_rowid       = json_decode($dt_rowid);
            $arrProductID   = json_decode($arrProductID);
            if(count($dt_rowid)>0):
                $this->db->where("CompanyID", $CompanyID);
                $this->db->where("UserID", $UserID);
                $this->db->where("Page", $page);
                $this->db->where_not_in("Class", $dt_rowid);
                $this->db->delete("Temp_Serial_Number");
                foreach ($dt_rowid as $k => $v) {
                    $this->db->where("CompanyID", $CompanyID);
                    $this->db->where("UserID", $UserID);
                    $this->db->where("Page", $page);
                    $this->db->where("Class", $v);
                    $this->db->where("ProductID !=", $arrProductID[$k]);
                    $this->db->delete("Temp_Serial_Number");
                }
            else:
                $this->db->where("CompanyID", $CompanyID);
                $this->db->where("UserID", $UserID);
                $this->db->where("Page", $page);
                $this->db->delete("Temp_Serial_Number");
            endif;

            $list = $this->api->temp_serial($page,"",$header_code,$ID,"list");
            $output = array(
                "status"    => true,
                "message"   => $this->lang->line("lb_success"),
                "list"      => $list,
            );
        else:
            $output                   = array();
            $output['status']         = FALSE;
            $output['message']        = $this->lang->line("lb_data_not_found");
        endif;

        $this->main->echoJson($output);
    }

    public function select_serial_autocomplete(){
        $ID             = $this->input->post("ID");
        $header_code    = $this->input->post("header_code");
        $page           = $this->input->post("page");
        $page2          = $this->input->post("page2");
        $detailsn       = $this->input->post('detailsn');
        $headerID       = $this->input->post('headerID');
        $detailID       = $this->input->post('detailID');
        $serial         = $this->input->post('serial');
        $CompanyID      = $this->session->CompanyID;
        $BranchID       = $this->input->post('BranchID');
        if($BranchID):
            $BranchID = explode("-", $BranchID); $BranchID = $BranchID[0];
        endif;

        $serialtxt  = 'serialno';
        if($detailsn == "active" && $CompanyID):
            if($page == "retur"): $sn_data = $this->api->good_receipt_serial("autocomplete",$ID,$headerID,$detailID,$serial); $serialtxt = 'SN';
            elseif($page == "delivery" && $page2 == "active"):
                $sn_data = $this->api->selling_serial("autocomplete",$ID,$headerID,$detailID,$serial); $serialtxt = 'SN';
            elseif($page == "return_sales" && $page2 == "active"):
                $sn_data = $this->api->selling_serial("autocomplete",$ID,$headerID,$detailID,$serial); $serialtxt = 'SN';
            elseif($page == "return_sales"):
                $sn_data = $this->api->delivery_serial("autocomplete",$ID,$headerID,$detailID,$serial); $serialtxt = 'SN';
            elseif($page == "stock_correction" || $page == "stock_opname"): $sn_data = $this->main->product_serial("autocomplete",$serial,$ID,"",$BranchID);
            elseif($page == "mutation" && $page2):
                $BranchID = explode("-", $page2); $BranchID = $BranchID[0];
                $sn_data = $this->main->product_serial("autocomplete",$serial,$ID,"active",$BranchID);
            elseif($page == "good_issue" && $BranchID):
                $sn_data = $this->main->product_serial("autocomplete",$serial,$ID,"active",$BranchID);
            else:
                if($BranchID):
                    $sn_data    = $this->main->product_serial("autocomplete",$serial,$ID,"active",$BranchID);
                else: $sn_data    = $this->main->product_serial("autocomplete",$serial,$ID,"active");
                endif;
            endif;

            $data       =  array();
            foreach ($sn_data as $k => $v) {
                $data[]     = $v->{$serialtxt};
            }
            $output = array(
                'status'    => true,
                'message'   => $this->lang->line('lb_success'),
                'list'      => $data,
            );
        else:
            $output = array(
                'status'    => false,
                'message'   => $this->lang->line('lb_data_not_found'),
            );
        endif;

        $this->main->echoJson($output);
    }

    #20190819 MW
    #genenal setting
    public function save_general_setting(){
        $CompanyID = $this->session->CompanyID;
        $table  = "UT_General";
        if($CompanyID):

            $form = $this->input->post();
            $arrPostName = array();

            foreach ($form as $k => $v) {
                if(!in_array($k,$arrPostName)):
                    array_push($arrPostName,$k);
                endif;
            }

            $genenal_data = $this->api->general_settings("array", $arrPostName);

            foreach ($form as $k => $v) {
                $nmPost = $k;
                $value  = $v;

                $keyPost = array_search($nmPost, array_column($genenal_data, 'Code'));
                $key_length = strlen($keyPost);

                $data = array(
                    "Code"  => $nmPost,
                    "Value" => $value,
                );

                // insert data
                if($key_length<=0):
                    $this->db->set("UserAdd",$this->session->NAMA);
                    $this->db->set("DateAdd",date("Y-m-d H:i:s"));
                    $this->db->insert($table, $data);
                // update data
                else:
                    $where = array("Code" => $nmPost);
                    $this->db->set("UserCh",$this->session->NAMA);
                    $this->db->set("DateCh",date("Y-m-d H:i:s"));
                    $this->db->update($table, $data, $where);
                endif;

            }

            $config['allowed_types']    = '*';
            $config['upload_path']      = './img/attachment'; //path folder 
            $config['max_size']         = '9999'; //maksimum besar file 2M 
            $config['max_width']        = '9999'; //lebar maksimum 1288 px 
            $config['max_height']       = '9999'; //tinggi maksimu 768 px 

            $files = $_FILES;
            $files = json_encode($files);
            $files = json_decode($files);
            $no = 0;
            foreach ($files as $k2 => $v2) {
                if($v2->size>0):
                    $_FILES['userfile']['name'] =  $v2->name;
                    $_FILES['userfile']['type']= $v2->type;
                    $_FILES['userfile']['tmp_name']= $v2->tmp_name;
                    $_FILES['userfile']['error']= $v2->error;
                    $_FILES['userfile']['size']= $v2->size;

                    $nmfile                     = "pipesys_".time();
                    $config['file_name']        = $nmfile; //nama yang terupload nantinya 
                    $this->upload->initialize($config);
                    $upload =  $this->upload->do_upload();
                    $resizeImage = '';
                    $gbr    = $this->upload->data();
                    if($v2->size>2000000):
                        $info = getimagesize($v2->tmp_name);
                        $resizeImage = $this->main->resizeImage2($gbr['file_name'],'./img/attachment/',$info);
                    endif;

                    $data = array(
                        'CompanyID'         => $this->session->CompanyID,
                        "Cek"               => 1,
                        "Type"              => $k2,
                        "UserAdd"           => $this->session->NAMA,
                        "DateAdd"           => date("Y-m-d H:i:s"),
                    );
                    if($upload):
                        $image              = "img/attachment/".$gbr['file_name'];

                        $data['Name']       = $v2->name;
                        $data['Image']      = $image;
                        if(in_array($k2,array('SiteLogoSmall','SiteLogo'))):
                            $ck_logo = $this->db->count_all("PS_Attachment where Type = '$k2'");
                            if($ck_logo>0):
                                $file_lama = $this->main->get_one_column("PS_Attachment","Image", array("Type" => $k2))->Image;
                                $this->main->delete_file($file_lama);
                                $this->db->where("Type", $k2);
                                $this->db->update("PS_Attachment",$data);
                            else:
                                $this->db->insert("PS_Attachment",$data);
                            endif;
                        else:
                            $this->db->insert("PS_Attachment",$data);
                        endif;
                    else:
                    endif;

                endif;
                $no += 1;
            }


            $page = "general_settings(".$this->input->post('modul_page').")";
            $this->main->insert_log(3,$page,json_encode($form));

            $output = array(
                "status"    => true,
                "message"   => $this->lang->line("lb_success"),
            );
            $this->main->create_site_file();
        else:
            $output                   = array();
            $output['status']         = FALSE;
            $output['message']        = $this->lang->line("lb_data_not_found");
        endif;

        $this->main->echoJson($output);
    }

    public function get_general_setting(){
        $CompanyID = $this->session->CompanyID;
        $form = $this->input->post();
        if($CompanyID && count($form)>0):

            $form           = $this->input->post();
            $modul_page     = $this->input->post('modul_page');
            $arrPostName    = array();

            foreach ($form as $k => $v) {
                if(!in_array($k,$arrPostName)):
                    array_push($arrPostName,$k);
                endif;
            }

            $file_data  = array();
            if($modul_page == "general"):
                $file_ = $this->api->attachment_list(array('SiteLogo','SiteLogoSmall'),"","array");
                if(count($file_)>0):
                    foreach ($file_ as $v) {
                        if(is_file($v->Image)):
                            $image = site_url($v->Image);
                        else:
                            $image = site_url('img/pipesys.png');
                        endif;

                        $h = array(
                            "Code"  => $v->Type,
                            "Name"  => "",
                            "Value" => $image,
                        );
                        array_push($file_data,$h);
                    }
                else:
                    $image = site_url('img/pipesys.png');
                    $h = array(
                        "Code"  => '',
                        "Name"  => "",
                        "Value" => $image,
                    );
                    array_push($h);
                endif;
            endif;


            $genenal_data = $this->api->general_settings("array", $arrPostName);
            foreach ($genenal_data as $k => $v) {
                # code...
            }
            $result = array_merge($genenal_data, $file_data);
            $output = array(
                "status"    => true,
                "message"   => $this->lang->line("lb_success"),
                "list"      => $result,
            );
        else:
            $output                   = array();
            $output['status']         = FALSE;
            $output['message']        = $this->lang->line("lb_data_not_found");
        endif;

        $this->main->echoJson($output);
    }

    #20190905 MW
    #unit
    public function select_unit_autocomplete(){
        $CompanyID = $this->session->CompanyID;
        if($CompanyID):
            $unit = $this->input->post('unit');
            $list = $this->api->product_unit($unit,"autocomplete");
            $data       =  array();
            foreach ($list as $k => $v) {
                $data[]     = $v->Uom;
            }
            $output = array(
                'status'    => true,
                'message'   => $this->lang->line('lb_success'),
                'list'      => $data,
            );
        else:
            $output = array(
                "status"    => false,
                "message"   => $this->lang->line('lb_data_not_found'),
            );
            
        endif;

        $this->main->echoJson($output);
    }

    #20190917 MW
    #branch 
    #create index company
    private function create_branch_index(){
        $user = $this->db->query("select a.id_user from user a where (a.App = 'pipesys' or a.App = 'all') and (a.hak_akses = 'super_admin' or a.hak_akses = 'company')")->result();
        foreach ($user as $k => $v) {
            $this->db->select("BranchID");
            $this->db->where("CompanyID", $v->id_user);
            $this->db->limit(1);
            $this->db->from("Branch");
            $query  = $this->db->get();
            $d      = $query->row();
            if($d):
                $data = array("Index" => 1);
                $this->db->where("BranchID", $d->BranchID);
                $this->db->update("Branch", $data);
            endif;
        }
    }

    #20191001 MW
    #memindahkan qty,purchase price, dan average ke branch
    #karena default nya adalah branch HO
    private function pindah_qty(){
        $CompanyID = 203;
        $this->db->select("ProductID,Qty,PurchasePrice,AveragePrice");
        $this->db->where("CompanyID", $CompanyID);
        $this->db->where("Position", 0);
        $this->db->where("ifnull(AveragePrice,0) > ",0);
        $query = $this->db->get("ps_product")->result();

        $BranchID = 262;
        foreach ($query as $key => $v) {
            $data = array(
                "Qty"               => $v->Qty,
                "PurchasePrice"     => $v->PurchasePrice,
                "AveragePrice"      => $v->AveragePrice,
            );

            $this->db->where("ProductID", $v->ProductID);
            $this->db->where("BranchID", $BranchID);
            $this->db->where("CompanyID", $CompanyID);
            $this->db->update("PS_Product_Branch", $data);

            $data = array(
                "Qty"               => 0,
                "PurchasePrice"     => 0,
                "AveragePrice"      => 0,
            );
            $this->db->where("ProductID", $v->ProductID);
            $this->db->where("CompanyID", $CompanyID);
            $this->db->update("ps_product", $data);
        }
    }
    // 29192919
    public function get_setting($modul = "")
    { 
        $post       = $this->input->post();
        $ListData   = array(); 
        $CodeArray  = array();
        // $data->module = $this->api->get_module();
        // header('Content-Type: application/json');
        // echo json_encode($data,JSON_PRETTY_PRINT);  

        if($modul == "slideshow"):
            $ListData = $this->api->slideshow('list_data');
        elseif($modul == "module_user"):
            $ListData = $this->api->module_user('list_data');
        else:
            foreach($post as $key => $a):
                $CodeArray[] = $key;
            endforeach;
            foreach($_FILES as $key => $a):
                $CodeArray[] = $key;
            endforeach;
            if(count($CodeArray) > 0):
                $this->db->where_in("Code",$CodeArray);
                $query = $this->db->get("UT_General");
                $Listdatax = $query->result();
                foreach($Listdatax as $a):
                    $ListData[] = array(
                        "Code"  => $a->Code,
                        "Value" => $a->Value,
                    );
                endforeach;
            endif;
        endif;
        $output     = array(
            "HakAkses"  => $this->session->hak_akses,
            "Status"    => TRUE,
            "ListData"  => $ListData
        );
        $this->main->echoJson($output);
    }
    public function save_setting($modul = "")
    {
        $post       = $this->input->post();
        $postar     = array();
        $data             = array();
        $data_module      = array();
        // $dataeng    = array();
        $PostName   = array();
        if($modul == "slideshow"):
            $AttachmentID               = $this->input->post("AttachmentID");
            $AttachmentIDeng            = $this->input->post("AttachmentIDeng");
            $Name                       = $this->input->post("Name");
            $Nameeng                    = $this->input->post("Nameeng");
            $NameColor                  = $this->input->post("NameColor");
            $NameColoreng               = $this->input->post("NameColoreng");
            $Description                = $this->input->post("Description");
            $Descriptioneng             = $this->input->post("Descriptioneng");
            $Position                   = $this->input->post("Position");
            $Positioneng                = $this->input->post("Positioneng");

            $ButtonLink                 = array();
            $BtnID                      = $this->input->post("BtnID");
            $BtnName                    = $this->input->post("BtnName");
            $BtnLink                    = $this->input->post("BtnLink");
            $BtnColor                   = $this->input->post("BtnColor");
            foreach($BtnID as $key => $a):
                $ButtonLink[] = array(
                    "BtnID"     => $BtnID[$key],
                    "BtnName"   => $BtnName[$key],
                    "BtnLink"   => $BtnLink[$key],
                    "BtnColor"  => $BtnColor[$key]
                );
            endforeach;
            $ButtonLink = json_encode($ButtonLink);


            $nmfile                     = 'Aplikasi Penjualan Aplikasi Gudang Program Penjualan Accounting Software-'.$Name."-".time();
            $nmfile                     = $this->frontend->link($nmfile,'file');
            $config['upload_path']      = './img/slideshow'; //path folder 
            $config['allowed_types']    = 'gif|jpg|png|jpeg|bmp|PNG|JPG';
            $config['max_size']         = '99999'; //maksimum besar file 2M 
            $config['max_width']        = '99999'; //lebar maksimum 1288 px 
            $config['max_height']       = '99999'; //tinggi maksimu 768 px 
            $config['file_name']        = $nmfile; //nama yang terupload nantinya 
            $this->upload->initialize($config); 
            $upload = $this->upload->do_upload("Image");
            $gbr    = $this->upload->data();
            #---------------------------------------------------------------------------------------------------------------
            $data   = array(
                "Name"          => $Name,
                "NameColor"     => $NameColor,
                "Description"   => $Description,
                "Position"      => $Position,
                "Type"          => "slideshow",
                "ButtonLink"    => $ButtonLink
            );

            if(!$Nameeng): $Nameeng = null; endif;
            if(!$NameColoreng): $NameColoreng = null; endif;
            if(!$Descriptioneng): $Descriptioneng = null; endif;
            if(!$Positioneng): $Positioneng = null; endif;

            $dataeng   = array(
                "Name"       => $Nameeng,
                "NameColor"  => $NameColoreng,
                "Description"=> $Descriptioneng,
                "Position"   => $Positioneng,
                "Type"          => "slideshow",
                "ButtonLink"    => $ButtonLink,
                "Language"      => 2
            );

            if($upload):        
                $image          = "img/".$modul."/".$gbr['file_name'];
                $data["Patch"]  = $image;
                $dataeng["Patch"]  = $image;
                if($AttachmentID > 0):
                    $this->main->hapus_gambar('UT_Attachment','Patch',array('AttachmentID' => $AttachmentID));
                endif;
            endif;
            if($AttachmentID > 0):
                $this->db->where("AttachmentID",$AttachmentID);
                $this->db->update("UT_Attachment",$data);

                $this->db->where("ParentID",$AttachmentID);
                $this->db->update("UT_Attachment",$dataeng);
            else:
                $data["Sort"] = $this->api->slideshow("last_sort") + 1;
                // $this->db->insert("UT_Attachment",$data);
                $insert = $this->api->save_slideshow($data);
                $dataeng['ParentID'] = $insert;
                $id     = $this->api->save_slideshow($dataeng);
            endif;
        endif;
        if($modul == "module_user"):

            $Type              = $this->input->post("Type");
            $VoucherPackageID  = array();
            if(count($Type) > 0):
                foreach ($this->input->post("Type") as $key => $value):
                    $ID             = $this->input->post("ID")[$key];
                    $Type           = $this->input->post("Type")[$key];
                    $Module         = $this->input->post("Module")[$key];
                    $Price          = $this->input->post("Price")[$key];

                    $data_module  = array(
                        'Type'          => $Type,
                        'Module'        => $Module,
                        'Price'         => $this->main->checkDuitInput($Price),
                        'App'           => 'oneapp',
                        );

                    if($ID == ''):
                        $ID = $this->api->save_module($data_module);
                    else:
                        $this->api->update_module(array("VoucherPackageID" => $ID), $data_module);
                    endif;
                    array_push($VoucherPackageID, $ID);
                endforeach;
            endif;
            $this->api->delete_module($VoucherPackageID);
        endif;
        $output = array(
            "HakAkses"  => $this->session->HakAkses,
            "Status"    => TRUE,
            "Modul"     => $modul,
            "Post"      => $PostName,
            "Data"      => $data
        );
        $this->main->echoJson($output);
    }
    public function save_setting_db($cek,$Code,$Value){
        $data["Value"]  = $Value;
        if($cek > 0):
            $data["UserCh"] = $this->session->Name;
            $data["DateCh"] = date("Y-m-d H:i:s");
            $this->db->where("Code",$Code);
            $this->db->update("UT_General",$data);
        else:
            $data["Code"]    = $Code;
            $data["UserAdd"] = $this->session->Name;
            $data["DateAdd"] = date("Y-m-d H:i:s");
            $this->db->insert("UT_General",$data);
        endif;
    }
    public function slideshow($page = "",$id = ""){
        $data = array();
        if($page == "ubah_urutan"):
            $ArrayID = $this->input->post("ArrayID");
            $ArrayUrutan = $this->input->post("ArrayUrutan");
            foreach($ArrayID as $key => $a):
                $datax = array(
                    "Sort" => $ArrayUrutan[$key]
                );
                $data[] = $a;
                $this->db->where("Type","slideshow");
                $this->db->where("AttachmentID",$a);
                $this->db->update("UT_Attachment",$datax);
            endforeach;
        elseif($page == "delete"):
            $this->main->hapus_gambar('UT_Attachment','Patch',array('AttachmentID' => $id));

            $this->db->where("AttachmentID",$id);
            $this->db->or_where("ParentID",$id);
            $this->db->delete("UT_Attachment");
        elseif($page == "edit"):
            $data = $this->api->slideshow($page,$id);
            $a    = $data;
            $data = array(
                "Active"           => $a->Active,
                "AttachmentID"     => $a->AttachmentID,
                "AttachmentIDeng"  => $a->AttachmentIDeng,
                "ButtonLink"       => json_decode($a->ButtonLink),
                "Description"      => $a->Description,
                "Descriptioneng"   => $a->Descriptioneng,
                "ID"               => $a->AttachmentID,
                "Name"             => $a->Name,
                "Nameeng"          => $a->Nameeng,
                "NameColor"        => $a->NameColor,
                "NameColoreng"     => $a->NameColoreng,
                "Patch"            => $a->Patch,
                "Position"         => $a->Position,
                "Positioneng"      => $a->Positioneng,
                "Sort"             => $a->Sort,
                "Sorteng"          => $a->Sorteng,
                "Type"             => $a->Type,
                "Typeeng"          => $a->Typeeng
            );
        endif;
        $output = array(
            "HakAkses"  => $this->session->HakAkses,
            "Status"    => TRUE,
            "Data"      => $data,
            "Page"      => $page
        );
        $this->main->echoJson($output);
    }
    // 29192919

    // 30192919
    public function module_user_list($VoucherPackageID = ""){

        $Data       = array();
        $ListData   = array();
        $Status     = FALSE;
        $Message    = "Get data failed";
        if($VoucherPackageID):
            $ListData = $this->api->module_user("list_data",$VoucherPackageID);
            if(count($ListData) > 0):
                $Status = TRUE;
                $Message = "get data success";
            endif;
        endif;
        $output     = array(
            "Data"              => $Data,
            "ListData"          => $ListData,
            "HakAkses"          => $this->session->HakAkses,
            "Status"            => $Status,
            "Message"           => $Message,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT); 
    }
    // 30192919
}

