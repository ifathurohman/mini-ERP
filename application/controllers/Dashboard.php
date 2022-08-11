<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->main->cek_session();

    }
    public function index()
    {
        $page = "";
        if($this->session->app == "pipesys"):
            $page            = "page/dashboard";
        elseif($this->session->app == "pipesys"):
            $page            = "purchase_order/list";
        elseif($this->session->app == "salespro"):
            $page = "page/dashboard_salespro";
        endif;
        $ap             = $this->main->check_parameter_module("ap","ap");
        $ar             = $this->main->check_parameter_module("ar","ar");
        $ac             = $this->main->check_parameter_module("ac","ac");
        $inventory      = $this->main->check_parameter_module("inventory","inventory");
        $data["index"]  = "";
        $data["page"]   = $page;
        $data['ap']     = $ap->view;
        $data['ar']     = $ar->view;
        $data['ac']     = $ac->view;
        $data['inventory'] = $inventory->view;
        $this->load->view("index",$data);
    }

    #ini dashboard pipesys
    public function dashboard()
    {
        $page = $this->input->post('page');

        $status = true;
        $res   = array(
            "status"     => TRUE,
            "hakakses"   => $this->session->hak_akses,
            "page"       => $page,
        );
        if($page == "expire"):
            $res['list_expire'] = $this->list_expire();
        endif;

        if($page == "outstanding_delivery"):
            $outstanding = $this->get_outstanding_delivery();
            $data['outstanding_delivery'] = $outstanding;
        elseif($page == "sales_store"):
            $sales_store        = $this->sales_store();
            $data['sales_store']= $sales_store;
        elseif($page == "omset_cost"):
            $sales_cost         = $this->selling("sales_cost");
            $data['sales_cost'] = $sales_cost;
        elseif($page == "sales_city"):
            $sales_city         = $this->selling();
            $data['sales_city'] = $sales_city;
        elseif($page == "sales_customer"):
            $sales_customer = $this->sales_customer();
            $data['sales_customer'] = $sales_customer;
        elseif($page == "sales_open"):
            $sales_open         = $this->sales_open();
            $data['sales_open'] = $sales_open;
        elseif($page == "sales_overdude"):
            $sales_overdude         = $this->sales_open("sales_overdude");
            $data['sales_overdude'] = $sales_overdude;
        elseif($page == "sales_payment"):
            $sales_payment         = $this->sales_payment();
            $data['sales_payment'] = $sales_payment;
        elseif($page == "item_delivery"):
            $item_delivery         = $this->item_delivery();
            $data['item_delivery'] = $item_delivery;
        elseif($page == "sales_return"):
            $sales_return         = $this->sales_return();
            $data['sales_return'] = $sales_return;
        elseif($page == "sellheader"):
            $data = $this->get_all_selling();
        elseif($page == "purchase_order"):
            $purchase_order         = $this->purchase("purchase_order");
            $data[$page]            = $purchase_order;
        elseif($page == "purchase_transaction"):
            $list         = $this->purchase("purchase_transaction");
            $data[$page]  = $list;
        elseif($page == "purchase_return"):
            $list         = $this->purchase_return();
            $data[$page]  = $list;
        elseif($page == "purchase_open"):
            $list         = $this->purchase_open();
            $data[$page]  = $list;
        elseif($page == "purchase_payment"):
            $list         = $this->sales_payment("purchase_payment");
            $data[$page]  = $list;
        elseif($page == "purchase_overdude"):
            $list         = $this->purchase_open("purchase_overdude");
            $data[$page]  = $list;
        elseif($page == "goodreceipt"):
            $list         = $this->penerimaan("goodreceipt");
            $data[$page]  = $list;
        elseif($page == "purchaseheader"):
            $data   = $this->get_all_purchase();
        elseif($page == "inventory"):
            $data   = $this->get_all_inventory();
        elseif($page == "accounting"):
            $data   = $this->get_all_accounting();
        elseif($page == "loss_profit"):
            $list   = $this->loss_and_profit();
            $data[$page]  = $list;
        elseif($page == "balance_sheet"):
            $list   = $this->balance_sheet();
            $data[$page] = $list;
        elseif($page == "expire"):
            $data   = $res;
        else:
            $data   = $this->get_all_selling();
            $data2  = $this->get_all_purchase();
            $data3  = $this->get_all_inventory();
            $data4  = $this->get_all_accounting();
            $data   = array_merge($data,$res);
            $data   = array_merge($data,$data2);
            $data   = array_merge($data,$data3);
            $data   = array_merge($data,$data4);
        endif;

        if($status):
            $this->main->echoJson($data);
        endif;
    }

    private function get_all_selling(){
        $page = $this->input->post('page');
        #selling
        $total_sell_qty         = $this->sell_detail_sum("qty");
        $total_sell_amount      = $this->sell_detail_sum("total_amount");
        $total_purchase_amount  = $this->main->purchase_detail_sum("total_amount");
        $total_sell             = $this->sell_detail_sum("count");
        $total_customer         = $this->main->customer("count");
        $total_product          = $this->main->product("count");
        $branch                 = $this->main->branch("","","1");
        $total_vendor           = $this->total_vendor();
        $list_store             = array();
        foreach($branch as $branch):
            $item_branch = array(
                "App"       => $this->session->app,
                "branchid"  => $branch->branchid,
                "companyid" => $branch->companyid,
                "Name"      => $branch->name,
                "Lat"       => $branch->lat,
                "Lng"       => $branch->lng
            );
            array_push($list_store,$item_branch);
        endforeach;

        // 20190311 MW

        // $sales_hour         = $this->selling("sales_hour");
        // $sales_store        = $this->sales_store();
        $outstanding        = $this->get_outstanding_delivery();
        // $top_sales_branch   = $this->top_sales_branch();
        $top_sales_category = $this->top_sales_category();
        $sales_city         = $this->selling("sales_city");
        // $sales_customer     = $this->sales_customer();
        $sales_open         = $this->sales_open();
        $sales_overdude     = $this->sales_open("sales_overdude");
        $sales_payment      = $this->sales_payment();
        $item_delivery      = $this->item_delivery();
        $sales_return       = $this->sales_return();
        $user_detail        = $this->main->user_detail($this->session->UserID);
        $JoinDate           = date("Y-m-d",strtotime($user_detail->JoinDate));
        $VerificationExpire = date('Y-m-d', strtotime("+6 day", strtotime($JoinDate)));
        $VerificationExpire = $this->main->tanggal("D, d M Y",$VerificationExpire);
        $top_sales_customer = $this->top_sales_customer();

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
            "total_purchase_amount"     => $this->main->currency($total_purchase_amount),
            "total_sell_amount"         => $this->main->currency($total_sell_amount),
            "total_sell_qty"            => $this->main->qty($total_sell_qty),
            "total_sell"                => $total_sell,
            "total_product"             => $total_product,
            "total_customer"            => $total_customer,
            "total_vendor"              => $total_vendor,
            "list_store"                => $list_store,
            "top_sales_category"        => $top_sales_category,
            // "top_sales_branch"          => $top_sales_branch,
            "outstanding_delivery"      => $outstanding,
            // "sales_hour"                => $sales_hour,
            // "sales_store"               => $sales_store,
            "sales_city"                => $sales_city,
            // "sales_customer"            => $sales_customer,
            "sales_open"                => $sales_open,
            "sales_overdude"            => $sales_overdude,
            "sales_payment"             => $sales_payment,
            "item_delivery"             => $item_delivery,
            "sales_return"              => $sales_return,
            "top_sales_customer"		=> $top_sales_customer,

        );
        #end selling
        $data['page'] = $page;
        return $output;
    }

   
    private function get_all_purchase(){
        $page = $this->input->post('page');

        $purchase_order         = $this->purchase("purchase_order");
        $purchase_transaction   = $this->purchase("purchase_transaction");
        $goodreceipt            = $this->penerimaan("goodreceipt");
        $purchase_return        = $this->purchase_return();
        $purchase_open          = $this->purchase_open();
        $purchase_overdude      = $this->purchase_open("purchase_overdude");
        $purchase_payment       = $this->sales_payment("purchase_payment");

        $output = array(
            "status"                => TRUE,
            "hakakses"              => $this->session->hak_akses,
            "purchase_order"        => $purchase_order,
            "purchase_transaction"  => $purchase_transaction,
            "goodreceipt"           => $goodreceipt,
            "purchase_return"       => $purchase_return,
            "purchase_open"         => $purchase_open,
            "purchase_overdude"     => $purchase_overdude,
            "purchase_payment"      => $purchase_payment,
        );

        #end selling
        $data['page'] = $page;
        return $output;
    }

    private function get_all_inventory(){
        $page = $this->input->post('page');

        $minimal_product    = $this->product("minimal_product");
        $stock_product      = $this->stock_product();
        $product_branch     = $this->product_branch();

        $output = array(
            "status"                => TRUE,
            "hakakses"              => $this->session->hak_akses,
            "minimal_product"       => $minimal_product,
            "stock_product"         => $stock_product,
            "product_branch"        => $product_branch,
        );
        #end selling
        $data['page'] = $page;
        return $output;
    }

    private function get_all_accounting(){
        $page = $this->input->post('page');

        $total_net_omset    = $this->payment("total_net_omset");
        $sales_cost         = $this->selling("sales_cost");
        // $store_receivable   = $this->sales_payment("store_receivable");
        $account_watchlist  = $this->account_watchlist();
        $loss_and_profit    = $this->loss_and_profit();
        $balance_sheet      = $this->balance_sheet();

        $output = array(
            "status"                => TRUE,
            "hakakses"              => $this->session->hak_akses,
            "total_net_omset"       => $this->main->currency($total_net_omset->total),
            "sales_cost"            => $sales_cost,
            // "store_receivable"      => $store_receivable,
            "account_watchlist"     => $account_watchlist,
            "loss_profit"           => $loss_and_profit,
            "balance_sheet"         => $balance_sheet,
        );
        #end selling
        $data['page'] = $page;
        return $output;
    }

    private function top_sales_category(){
        $CompanyID  = $this->session->CompanyID;
        $table      = "PS_Sell_Detail";
        $this->db->select("
            sum($table.TotalPrice)     as qty,
            product.ParentCode  as category,
            ");
        $this->db->join("PS_Sell as sell", "sell.SellNo = $table.SellNo and sell.CompanyID = $table.CompanyID", "left");
        $this->db->join("ps_product as product", "product.ProductID = $table.ProductID", "left");
        $this->db->where("$table.CompanyID", $CompanyID);
        $this->db->where("sell.Status", 1);
        if($this->input->post('StartDate')):
            $StartDate = $this->input->post('StartDate');
            $this->db->where("sell.Date >=", $StartDate);
        endif;
        if($this->input->post('EndDate')):
            $EndDate = $this->input->post('EndDate');
            $this->db->where("sell.Date <=", $EndDate." 23:59:59");
        endif;
        $this->db->limit(5);
        $this->db->order_by("sum($table.TotalPrice)", "desc");
        $this->db->group_by("
            product.ParentCode,
            ");
        $this->db->from($table);
        $query = $this->db->get();
        return $query->result();
    }

    private function top_sales_customer(){
        $CompanyID  = $this->session->CompanyID;
        $table      = "PS_Sell";
        $this->db->select("
            sum($table.Payment)     as total,
            PS_Vendor.Name,
            ");
        $this->db->join("PS_Vendor", "$table.VendorID = PS_Vendor.VendorID");
        $this->db->where("$table.CompanyID", $CompanyID);
        $this->db->where("$table.Status", 1);
        if($this->input->post('StartDate')):
            $StartDate = $this->input->post('StartDate');
            $this->db->where("$table.Date >=", $StartDate);
        endif;
        if($this->input->post('EndDate')):
            $EndDate = $this->input->post('EndDate');
            $this->db->where("$table.Date <=", $EndDate." 23:59:59");
        endif;
        $this->db->limit(10);
        $this->db->order_by("sum($table.Payment)", "desc");
        $this->db->group_by("
            $table.VendorID,
            ");
        $this->db->from($table);
        $query = $this->db->get();
        return $query->result();
    }

    private function top_sales_branch(){
        $CompanyID = $this->session->CompanyID;
        $table     = "PS_Sell_Detail";
        $this->db->select("
            sum($table.TotalPrice)     as total,
            branch.Name                as branchName,
        ");
        $this->db->join("PS_Sell as sell", "sell.SellNo = $table.SellNo and sell.CompanyID = $table.CompanyID", "left");
        $this->db->join("Branch as branch", "branch.BranchID = sell.BranchID", "left");
        $this->db->where("$table.CompanyID", $CompanyID);
        $this->db->where("sell.BranchID != ", null);
        if($this->input->post('StartDate')):
            $StartDate = $this->input->post('StartDate');
            $this->db->where("sell.Date >=", $StartDate);
        endif;
        if($this->input->post('EndDate')):
            $EndDate = $this->input->post('EndDate');
            $this->db->where("sell.Date <=", $EndDate." 23:59:59");
        endif;
        $this->db->limit(5);
        $this->db->order_by("sum($table.TotalPrice)", "desc");
        $this->db->group_by("
            sell.BranchID,
            ");
        $this->db->from($table);
        $query = $this->db->get();

        $total = 0;
        foreach ($query->result() as $a) {
            $total += $a->total;
        }
        $data = array(
            "list"  => $query->result(),
            "total" => $this->main->currency($total),
        );
        return $data;
    }

    private function outstanding_delivery($date="",$id=""){
        $CompanyID  	= $this->session->CompanyID;
        $outstand_type 	= $this->input->post('outstand_type');
        // $outstand_type 	= "year";

        $table     = "PS_Sell_Detail";
        $this->db->select("
            (sum(ifnull($table.Qty, 0)) - sum(ifnull($table.DeliveryQty,0))) as qty,
            sell.VendorID   as VendorID,
            vendor.Name     as vendorName,
        ");
        $this->db->join("PS_Sell as sell", "sell.SellNo = $table.SellNo and sell.CompanyID = $table.CompanyID", "left");
        $this->db->join("PS_Vendor as vendor", "vendor.VendorID = sell.VendorID", "left");
        $this->db->where("$table.CompanyID", $CompanyID);
        $this->db->where("sell.VendorID !=", null);
        $this->db->where("($table.Qty - ifnull($table.DeliveryQty,0))>0");
        $this->db->where("sell.Status", 1);
        
        if($this->input->post('StartDate')):
            $StartDate = $this->input->post('StartDate');
            $this->db->where("sell.Date >=", $StartDate);
        endif;
        if($this->input->post('EndDate')):
            $EndDate = $this->input->post('EndDate');
            $this->db->where("sell.Date <=", $EndDate." 23:59:59");
        endif;

        if($date):
        	if($outstand_type == "month"):
        		$this->db->where("DATE_FORMAT(sell.Date,'%Y-%m')", $date);
        	elseif($outstand_type == "year"):
        		$this->db->where("DATE_FORMAT(sell.Date,'%Y')", $date);
        	else:
        		$this->db->where("Date(sell.Date)", $date);
        	endif;
        	$this->db->where("sell.VendorID", $id);
        endif;

        $this->db->group_by("
            sell.VendorID,
            ");
        if($outstand_type == "month"):
        	$this->db->select("DATE_FORMAT(sell.Date,'%Y-%m') as date");
        	$this->db->group_by("DATE_FORMAT(sell.Date,'%Y-%m')");
        	$this->db->order_by("DATE_FORMAT(sell.Date,'%Y-%m')");
        elseif($outstand_type == "year"):
        	$this->db->select("DATE_FORMAT(sell.Date,'%Y') as date");
        	$this->db->group_by("DATE_FORMAT(sell.Date,'%Y')");
        	$this->db->order_by("DATE_FORMAT(sell.Date,'%Y')");
        else:
        	$this->db->select("Date(sell.Date) as date");
        	$this->db->group_by("Date(sell.Date)");
        	$this->db->order_by("Date(sell.Date)");
        endif;
        $this->db->from($table);
        $query = $this->db->get();
        return $query->result();
    }

    private function get_outstanding_delivery(){
    	$d = $this->outstanding_delivery();

    	$ID  	= array();
    	$name 	= array();
    	$date 	= array();

    	foreach ($d as $k => $v) {
    		if(!in_array($v->VendorID, $ID)):
    			array_push($ID, $v->VendorID);
    			array_push($name, $v->vendorName);
    		endif;
    		if(!in_array($v->date, $date)):
    			array_push($date, $v->date);
    		endif;
    	}

    	$data = array();
    	foreach ($date as $k => $v) {
    		foreach ($ID as $i => $a) {
    			$d = $this->outstanding_delivery($v,$a);
    			if(count($d)>0):
    				foreach ($d as $kk => $vv) {
    					array_push($data, $vv);
    				}
    			else:
    				$h = array(
		    			"qty"			=> 0,
		    			"date"			=> $v,
		    			"VendorID"		=> $a,
		    			"vendorName"	=> $name[$i],
		    		);
		    		array_push($data, $h);
    			endif;
    		}
    	}

    	$output = array(
    		"ID" 	=> $ID,
    		"data" 	=> $data,
    	);

    	return $output;
    }

    private function account_watchlist(){
        $StartDate = "1990-01-01";
        $EndDate   = date("Y-m-d");
        $CompanyID = $this->session->CompanyID;
        if($this->input->post('StartDate')):
            $StartDate = $this->input->post('StartDate');
        endif;
        if($this->input->post('EndDate')):
            $EndDate = $this->input->post('EndDate');
        endif;

        $query = $this->db->query("call sp_FinanceReport('$StartDate','$EndDate','$CompanyID',0,5)")->result();
        $this->db->close();
        foreach ($query as $k => $v) {
            $v->total = $this->main->currency($v->total);
        }
        return $query;
    }

    private function loss_and_profit(){
        $StartDate = "1990-01-01";
        $EndDate   = date("Y-m-d");
        $CompanyID = $this->session->CompanyID;
        $method    = $this->input->post('loss_profit');
        if($this->input->post('StartDate')):
            $StartDate = $this->input->post('StartDate');
        endif;
        if($this->input->post('EndDate')):
            $EndDate = $this->input->post('EndDate');
        endif;

        $query = $this->db->query("call sp_FinanceReport('$StartDate','$EndDate','$CompanyID',0,6)")->result();
        $this->db->close();
            
        $arrDate = array();
        $arrID   = array();
        $arrData = array();
        $net_total  = 0;
        foreach ($query as $v) {
            if($v->Date):
                $date = $v->Date;
                if($method == 'month'):
                    $date = date("Y-m", strtotime($date));
                elseif($method == 'year'):
                    $date = date("Y", strtotime($date));
                endif;
                if(!in_array($date,$arrDate)):
                    array_push($arrDate,$date);
                endif;
            endif;
            if(!in_array($v->Keterangan,$arrID) && $v->Keterangan):
                array_push($arrID,$v->Keterangan);
            endif;
        }

        foreach ($arrDate as $k => $v) {
            foreach ($arrID as $kk => $vv) {
                $total = 0;
                foreach ($query as $k3 => $v3) {
                    if($v3->Keterangan == $vv):
                        $date = $v3->Date;
                        if($method == 'month'):
                            $date = date("Y-m", strtotime($date));
                        elseif($method == 'year'):
                            $date = date("Y", strtotime($date));
                        endif;
                        if($v == $date):
                            $total += (float) $v3->Total;
                        else:
                            $total += 0;
                        endif;
                    endif;
                }
                $net_total += $total;
                $h['ID']    = $vv;
                $h['Date']  = $v;
                $h['Total'] = $total;
                array_push($arrData, $h);
            }

        }
        $data = array(
            'date'  => $arrDate,
            'ID'    => $arrID,
            'data'  => $arrData,
            'total' => $this->main->currency($net_total),
        );
        return $data;
    }

    private function balance_sheet(){
        $StartDate = "1990-01-01";
        $EndDate   = date("Y-m-d");
        $CompanyID = $this->session->CompanyID;
        $method    = $this->input->post('balance_sheet');
        if($this->input->post('StartDate')):
            $StartDate = $this->input->post('StartDate');
        endif;
        if($this->input->post('EndDate')):
            $EndDate = $this->input->post('EndDate');
        endif;

        $query = $this->db->query("call sp_FinanceReport('$StartDate','$EndDate','$CompanyID',0,7)")->result();
        $this->db->close();

        $arrDate = array();
        $arrID   = array();
        $arrData = array();
        $net_total  = 0;
        foreach ($query as $v) {
            if($v->Date):
                $date = $v->Date;
                if($method == 'month'):
                    $date = date("Y-m", strtotime($date));
                elseif($method == 'year'):
                    $date = date("Y", strtotime($date));
                endif;
                if(!in_array($date,$arrDate)):
                    array_push($arrDate,$date);
                endif;
            endif;
            if(!in_array($v->ID,$arrID) && $v->ID):
                array_push($arrID,$v->ID);
            endif;
        }

        foreach ($arrDate as $k => $v) {
            foreach ($arrID as $kk => $vv) {
                $total = 0;
                foreach ($query as $k3 => $v3) {
                    if($v3->ID == $vv):
                        $date = $v3->Date;
                        if($method == 'month'):
                            $date = date("Y-m", strtotime($date));
                        elseif($method == 'year'):
                            $date = date("Y", strtotime($date));
                        endif;
                        if($v == $date):
                            $total += (float) $v3->Total;
                        else:
                            $total += 0;
                        endif;
                    endif;
                }
                $net_total += $total;
                $h['ID']    = $vv;
                $h['Date']  = $v;
                $h['Total'] = $total;
                array_push($arrData, $h);
            }

        }

        $data = array(
            'date'  => $arrDate,
            'ID'    => $arrID,
            'data'  => $arrData,
            'total' => $this->main->currency($net_total),
        );

        return $data;
    }

    private function selling($page="",$date="",$id=""){
    	$CompanyID  	= $this->session->CompanyID;
    	$sales_store 	= $this->input->post("sales_store");
    	$sales_cost 	= $this->input->post("sales_cost");
        $sales_city     = $this->input->post("sales_city");
        $sales_customer = $this->input->post("sales_customer");
    	$table          = "PS_Sell";
        $status_filter  = false;
        $method         = '';
    	if($page == "sales_hour"):
    		$this->db->select("
	    		sum(selldet.Qty) as qty,
	    		DATE_FORMAT($table.Date,'%Y-%m-%d') as date,
	    		DATE_FORMAT($table.Date,'%H') 	as hour,
			");
            $this->db->join("PS_Sell_Detail as selldet", "$table.SellNo = selldet.SellNo and $table.CompanyID = selldet.CompanyID", "left");
            $status_filter = true;
		elseif($page == "sales_store"):
			$this->db->select("
	    		sum(selldet.Qty) as qty,
	    		$table.BranchID,
	    		branch.Name as branchName,
	    	");
            $this->db->join("PS_Sell_Detail as selldet", "$table.SellNo = selldet.SellNo and $table.CompanyID = selldet.CompanyID", "left");
	    	$this->db->join("Branch as branch", "branch.BranchID = $table.BranchID", "left");
            $this->db->where("$table.BranchID !=", null);
            $this->db->group_by("$table.BranchID");
            $status_filter = true;
            $method        = $sales_store;
	    elseif($page == "sales_cost"):
	    	$this->db->select("
	    		sum(selldet.Price * selldet.Qty) as sale,
	    		sum(ifnull(selldet.Cost,0) * selldet.Qty) as cost,
			");
            $this->db->join("PS_Sell_Detail as selldet", "$table.SellNo = selldet.SellNo and $table.CompanyID = selldet.CompanyID", "left");
            // $this->db->where("$table.BranchID", null);
            $status_filter = true;
            $method        = $sales_cost;
        elseif($page == "sales_city"):
            $this->db->select("
                sum($table.Payment) as qty,
                $table.DeliveryCity as city,
            ");
            $this->db->where("$table.Status", 1);
            $this->db->group_by("$table.DeliveryCity");
            $this->db->limit(10);
            $status_filter = false;
            $method        = '';
        elseif($page == "sales_customer"):
            $this->db->select("
                sum(selldet.TotalPrice) as total,
                $table.VendorID,
                vendor.Name as vendorName,
            ");
            $this->db->join("PS_Sell_Detail as selldet", "$table.SellNo = selldet.SellNo and $table.CompanyID = selldet.CompanyID", "left");
            $this->db->join("PS_Vendor as vendor","vendor.VendorID = $table.VendorID");
            $this->db->where("$table.BranchID", null);
            $this->db->where("$table.Status", 1);
            $this->db->group_by("$table.VendorID");
            $status_filter = true;
            $method        = $sales_customer;
            $this->db->limit(10);
    	endif;
        $this->db->where("$table.CompanyID", $CompanyID);
    	if($page == "sales_hour"):
    		$this->db->where("$table.Status", 1);
    		$this->db->where("$table.BranchID", null);
    		$this->db->where("Date($table.Date)", date("Y-m-d"));
    		$this->db->group_by("DATE_FORMAT($table.Date,'%Y-%m-%d'), DATE_FORMAT($table.Date,'%H')");
    	elseif($status_filter):
    		// $this->db->where("$table.Date >=", "2019-03-05");
    		if($this->input->post('StartDate')):
	            $StartDate = $this->input->post('StartDate');
	            $this->db->where("$table.Date >=", $StartDate);
	        endif;
	        if($this->input->post('EndDate')):
	            $EndDate = $this->input->post('EndDate');
	            $this->db->where("$table.Date <=", $EndDate." 23:59:59");
	        endif;

	        if($date):
                if(in_array($page,array("sales_store","sales_cost"))):
                    $this->db->where("$table.BranchID", $id);
                elseif(in_array($page, array("sales_city"))):
                    $this->db->where("$table.DeliveryCity", $id);
                elseif(in_array($page, array("sales_customer"))):
                    $this->db->where("$table.VendorID", $id);
                endif;
	        endif;

    		if($status_filter and $method == "month"):
	        	if($date):
                    $this->db->where("DATE_FORMAT($table.Date,'%Y-%m')", $date);
                endif;
                $this->db->select("DATE_FORMAT($table.Date,'%Y-%m') as date");
	        	$this->db->group_by("DATE_FORMAT($table.Date,'%Y-%m')");
	        	$this->db->order_by("DATE_FORMAT($table.Date,'%Y-%m')");
	        elseif($status_filter and $method == "year"):
	        	if($date):
                    $this->db->where("DATE_FORMAT($table.Date,'%Y')", $date);
                endif;
                $this->db->select("DATE_FORMAT($table.Date,'%Y') as date");
	        	$this->db->group_by("DATE_FORMAT($table.Date,'%Y')");
	        	$this->db->order_by("DATE_FORMAT($table.Date,'%Y')");
    		else:
                if($date):
                    $this->db->where("Date($table.Date)", $date);
                endif;
    			$this->db->select("Date($table.Date) as date");
	        	$this->db->group_by("Date($table.Date)");
	        	$this->db->order_by("Date($table.Date)");
    		endif;
    	endif;
    	$this->db->from($table);
    	$query = $this->db->get();

    	return $query->result();
    }

    private function sales_store(){
    	$list = $this->selling("sales_store");

    	$ID  	= array();
    	$name 	= array();
    	$date 	= array();

    	foreach ($list as $k => $v) {
    		if(!in_array($v->BranchID, $ID)):
    			array_push($ID, $v->BranchID);
    			array_push($name, $v->branchName);
    		endif;
    		if(!in_array($v->date, $date)):
    			array_push($date, $v->date);
    		endif;
    	}

    	$data = array();
    	foreach ($date as $k => $v) {
    		foreach ($ID as $i => $a) {
    			$d = $this->selling("sales_store",$v,$a);
    			if(count($d)>0):
    				foreach ($d as $kk => $vv) {
    					array_push($data, $vv);
    				}
    			else:
    				$h = array(
		    			"qty"			=> 0,
		    			"date"			=> $v,
		    			"BranchID"		=> $a,
		    			"branchName"	=> $name[$i],
		    		);
		    		array_push($data, $h);
    			endif;
    		}
    	}

    	$output = array(
    		"ID" 	=> $ID,
    		"data"	=> $data,
    	);

    	return $output;
    }

    private function sales_city(){
        $list = $this->selling("sales_city");

        $name   = array();
        $date   = array();

        foreach ($list as $k => $v) {
            if(!in_array($v->city, $name)):
                array_push($name, $v->city);
            endif;
            if(!in_array($v->date, $date)):
                array_push($date, $v->date);
            endif;
        }

        $data = array();
        foreach ($date as $k => $v) {
            foreach ($name as $i => $a) {
                $d = $this->selling("sales_city",$v,$a);
                if(count($d)>0):
                    foreach ($d as $kk => $vv) {
                        array_push($data, $vv);
                    }
                else:
                    $h = array(
                        "qty"           => 0,
                        "date"          => $v,
                        "city"          => $a,
                    );
                    array_push($data, $h);
                endif;
            }
        }

        $output = array(
            "name"      => $name,
            "data"      => $data,
        );

        return $output;
    }

    private function sales_customer(){
        $list = $this->selling("sales_customer");

        $ID     = array();
        $name   = array();
        $date   = array();

        foreach ($list as $k => $v) {
            if(!in_array($v->VendorID, $ID)):
                array_push($ID, $v->VendorID);
                array_push($name, $v->vendorName);
            endif;
            if(!in_array($v->date, $date)):
                array_push($date, $v->date);
            endif;
        }

        $data = array();
        foreach ($date as $k => $v) {
            foreach ($ID as $i => $a) {
                $d = $this->selling("sales_customer",$v,$a);
                if(count($d)>0):
                    foreach ($d as $kk => $vv) {
                        array_push($data, $vv);
                    }
                else:
                    $h = array(
                        "total"         => 0,
                        "date"          => $v,
                        "VendorID"      => $a,
                        "vendorName"    => $name[$i],
                    );
                    array_push($data, $h);
                endif;
            }
        }

        $output = array(
            "ID"    => $ID,
            "data"  => $data,
        );

        return $output;
    }

    private function sales_open($page=""){
        $method = "sales_open";
        if($page):
            $method = $page;
        endif;
        $list = $this->invoice($method);

        $ID     = array();
        $name   = array();
        $date   = array();

        foreach ($list as $k => $v) {
            if(!in_array($v->VendorID, $ID)):
                array_push($ID, $v->VendorID);
                array_push($name, $v->vendorName);
            endif;
            if(!in_array($v->date, $date)):
                array_push($date, $v->date);
            endif;
        }

        $data   = array();
        $total  = 0;
        foreach ($date as $k => $v) {
            foreach ($ID as $i => $a) {
                $d = $this->invoice($method,$v,$a);
                if(count($d)>0):
                    foreach ($d as $kk => $vv) {
                        $total += $vv->total;
                        array_push($data, $vv);
                    }
                else:
                    $h = array(
                        "total"         => 0,
                        "date"          => $v,
                        "VendorID"      => $a,
                        "vendorName"    => $name[$i],
                    );
                    array_push($data, $h);
                endif;
            }
        }

        $output = array(
            "ID"    => $ID,
            "data"  => $data,
            "total" => $this->main->currency($total),
        );

        return $output;
    }

    private function sell_detail_sum($page = "")
    {
    	$table = "PS_Sell_Detail";
        $select = "*";
        if($page == "qty"):
            $select = "SUM(Qty) as sum";
        elseif($page == "total_amount"):
            $select = "SUM(Qty * Price) as sum";
        elseif($page == "count"):
        	$select = "sell.SellNo as sum";
        	$this->db->group_by("sell.SellNo");
        endif;
        $this->db->select($select);
        $this->db->where("PS_Sell_Detail.BranchID", null);
        if($this->input->post('StartDate')):
            $StartDate = $this->input->post('StartDate');
            $this->db->where("sell.Date >=", $StartDate);
        endif;
        if($this->input->post('EndDate')):
            $EndDate = $this->input->post('EndDate');
            $this->db->where("sell.Date <=", $EndDate." 23:59:59");
        endif;
        $this->db->join("PS_Sell as sell", "$table.SellNo = sell.SellNo and $table.CompanyID = sell.CompanyID", "left");                                                                                                                                 
        $this->db->where("sell.CompanyID",$this->session->CompanyID);
        $this->db->where("sell.Status", 1);
        $this->db->from($table);
        $query  = $this->db->get();
        if($page == "count"):
        	return $query->num_rows();
        else:
        	$a      = $query->row();
        	return $a->sum;
        endif;
        
    }

    private function sales_payment($page=""){
        $method = "sales_payment";
        if($page):
            $method = $page;
        endif;
        $list = $this->payment($method);

        $ID     = array();
        $name   = array();
        $date   = array();

        foreach ($list as $k => $v) {
            if(!in_array($v->VendorID, $ID)):
                array_push($ID, $v->VendorID);
                array_push($name, $v->vendorName);
            endif;
            if(!in_array($v->date, $date)):
                array_push($date, $v->date);
            endif;
        }

        $data   = array();
        $total  = 0;
        foreach ($date as $k => $v) {
            foreach ($ID as $i => $a) {
                $d = $this->payment($method,$v,$a);
                if(count($d)>0):
                    foreach ($d as $kk => $vv) {
                        $total += $vv->total;
                        array_push($data, $vv);
                    }
                else:
                    $h = array(
                        "total"         => 0,
                        "date"          => $v,
                        "VendorID"      => $a,
                        "vendorName"    => $name[$i],
                    );
                    array_push($data, $h);
                endif;
            }
        }

        $output = array(
            "ID"    => $ID,
            "data"  => $data,
            "total" => $this->main->currency($total),
        );

        return $output;
    }

    private function item_delivery(){
        $method = "item_delivery";
        $list = $this->delivery($method);
        $ID     = array();
        $name   = array();
        $date   = array();

        foreach ($list as $k => $v) {
            if(!in_array($v->VendorID, $ID)):
                array_push($ID, $v->VendorID);
                array_push($name, $v->vendorName);
            endif;
            if(!in_array($v->date, $date)):
                array_push($date, $v->date);
            endif;
        }

        $data   = array();
        $total  = 0;
        foreach ($date as $k => $v) {
            foreach ($ID as $i => $a) {
                $d = $this->delivery($method,$v,$a);
                if(count($d)>0):
                    foreach ($d as $kk => $vv) {
                        $total += $vv->qty;
                        array_push($data, $vv);
                    }
                else:
                    $h = array(
                        "qty"         => 0,
                        "date"          => $v,
                        "VendorID"      => $a,
                        "vendorName"    => $name[$i],
                    );
                    array_push($data, $h);
                endif;
            }
        }

        $output = array(
            "ID"    => $ID,
            "data"  => $data,
            "total" => $total,
        );

        return $output;
    }

    private function sales_return(){
        $method = "sales_return";
        $list = $this->return($method);
        $ID     = array();
        $name   = array();
        $date   = array();

        foreach ($list as $k => $v) {
            if(!in_array($v->VendorID, $ID)):
                array_push($ID, $v->VendorID);
                array_push($name, $v->vendorName);
            endif;
            if(!in_array($v->date, $date)):
                array_push($date, $v->date);
            endif;
        }

        $data   = array();
        $total  = 0;
        foreach ($date as $k => $v) {
            foreach ($ID as $i => $a) {
                $d = $this->return($method,$v,$a);
                if(count($d)>0):
                    foreach ($d as $kk => $vv) {
                        $total += $vv->qty;
                        array_push($data, $vv);
                    }
                else:
                    $h = array(
                        "qty"         => 0,
                        "date"          => $v,
                        "VendorID"      => $a,
                        "vendorName"    => $name[$i],
                    );
                    array_push($data, $h);
                endif;
            }
        }

        $output = array(
            "ID"    => $ID,
            "data"  => $data,
            "total" => $total,
        );

        return $output;
    }

    private function purchase_return(){
        $method = "purchase_return";
        $list = $this->return($method);
        $ID     = array();
        $name   = array();
        $date   = array();

        foreach ($list as $k => $v) {
            if(!in_array($v->VendorID, $ID)):
                array_push($ID, $v->VendorID);
                array_push($name, $v->vendorName);
            endif;
            if(!in_array($v->date, $date)):
                array_push($date, $v->date);
            endif;
        }

        $data   = array();
        $total  = 0;
        foreach ($date as $k => $v) {
            foreach ($ID as $i => $a) {
                $d = $this->return($method,$v,$a);
                if(count($d)>0):
                    foreach ($d as $kk => $vv) {
                        $total += $vv->qty;
                        array_push($data, $vv);
                    }
                else:
                    $h = array(
                        "qty"           => 0,
                        "date"          => $v,
                        "VendorID"      => $a,
                        "vendorName"    => $name[$i],
                        "total_payment" => "0",
                    );
                    array_push($data, $h);
                endif;
            }
        }

        $output = array(
            "ID"    => $ID,
            "data"  => $data,
            "total" => $total,
        );

        return $output;
    }

    private function purchase_open($page=""){
        $method = "purchase_open";
        if($page):
            $method = $page;
        endif;
        $list = $this->invoice($method);

        $ID     = array();
        $name   = array();
        $date   = array();

        foreach ($list as $k => $v) {
            if(!in_array($v->VendorID, $ID)):
                array_push($ID, $v->VendorID);
                array_push($name, $v->vendorName);
            endif;
            if(!in_array($v->date, $date)):
                array_push($date, $v->date);
            endif;
        }

        $data   = array();
        $total  = 0;
        foreach ($date as $k => $v) {
            foreach ($ID as $i => $a) {
                $d = $this->invoice($method,$v,$a);
                if(count($d)>0):
                    foreach ($d as $kk => $vv) {
                        $total += $vv->total;
                        array_push($data, $vv);
                    }
                else:
                    $h = array(
                        "total"         => 0,
                        "date"          => $v,
                        "VendorID"      => $a,
                        "vendorName"    => $name[$i],
                    );
                    array_push($data, $h);
                endif;
            }
        }

        $output = array(
            "ID"    => $ID,
            "data"  => $data,
            "total" => $this->main->currency($total),
        );

        return $output;
    }

    #purchase
    private function purchase($page="",$date="",$id=""){
        $CompanyID      = $this->session->CompanyID;
        $purchase_order = $this->input->post('purchase_order');
        $purchase_transaction = $this->input->post('purchase_transaction');
        $table          = "PS_Purchase";
        $status_filter  = false;
        $method         = '';
        if($page == "purchase_order"):
            $this->db->select("
                sum(dt.Qty) as qty,
            ");
            $this->db->join("PS_Purchase_Detail as dt", "$table.PurchaseNo = dt.PurchaseNo and $table.CompanyID = dt.CompanyID", "left");
            $this->db->where("$table.Status", 1);
            $status_filter = true;
            $method = $purchase_order;
        elseif($page == "purchase_transaction"):
            $this->db->select("
                sum((select sum(ifnull(Qty,0)) from PS_Purchase_Detail where CompanyID = $table.CompanyID and PurchaseNo = $table.PurchaseNo)) as total_qty,
                count($table.PurchaseNo) as total,
                sum($table.Payment) as total_payment,
            ");
            $this->db->where("$table.Status", 1);
            $status_filter = true;
            $method = $purchase_transaction;
        endif;
        $this->db->where("$table.CompanyID", $CompanyID);
        if($this->input->post('StartDate')):
            $StartDate = $this->input->post('StartDate');
            $this->db->where("$table.Date >=", $StartDate);
        endif;
        if($this->input->post('EndDate')):
            $EndDate = $this->input->post('EndDate');
            $this->db->where("$table.Date <=", $EndDate." 23:59:59");
        endif;

        if($status_filter):
            if($status_filter and $method == "month"):
                if($date):
                    $this->db->where("DATE_FORMAT($table.Date,'%Y-%m')", $date);
                endif;
                $this->db->select("DATE_FORMAT($table.Date,'%Y-%m') as date");
                $this->db->group_by("DATE_FORMAT($table.Date,'%Y-%m')");
                $this->db->order_by("DATE_FORMAT($table.Date,'%Y-%m')");
            elseif($status_filter and $method == "year"):
                if($date):
                    $this->db->where("DATE_FORMAT($table.Date,'%Y')", $date);
                endif;
                $this->db->select("DATE_FORMAT($table.Date,'%Y') as date");
                $this->db->group_by("DATE_FORMAT($table.Date,'%Y')");
                $this->db->order_by("DATE_FORMAT($table.Date,'%Y')");
            else:
                if($date):
                    $this->db->where("Date($table.Date)", $date);
                endif;
                $this->db->select("Date($table.Date) as date");
                $this->db->group_by("Date($table.Date)");
                $this->db->order_by("Date($table.Date)");
            endif;
        endif;

        $this->db->from($table);
        $query = $this->db->get();

        return $query->result();
    }
    #end purchase

    #penerimaan
    private function penerimaan($page="",$date="",$id=""){
        $CompanyID      = $this->session->CompanyID;
        $goodreceipt    = $this->input->post('goodreceipt');
        $table          = "AP_GoodReceipt";
        $status_filter  = false;
        $method         = '';
        if($page == "goodreceipt"):
            $this->db->select("
                sum((select sum(ifnull(Qty,0)) from AP_GoodReceipt_Det where CompanyID = $table.CompanyID and ReceiveNo = $table.ReceiveNo)) as total_qty,
                count($table.ReceiveNo) as total,
                sum($table.Payment)     as total_payment,
            ");
            $this->db->where("$table.Status", 1);
            $status_filter = true;
            $method = $goodreceipt;
        endif;
        $this->db->where("$table.CompanyID", $CompanyID);
        if($this->input->post('StartDate')):
            $StartDate = $this->input->post('StartDate');
            $this->db->where("$table.Date >=", $StartDate);
        endif;
        if($this->input->post('EndDate')):
            $EndDate = $this->input->post('EndDate');
            $this->db->where("$table.Date <=", $EndDate." 23:59:59");
        endif;

        if($status_filter):
            if($status_filter and $method == "month"):
                if($date):
                    $this->db->where("DATE_FORMAT($table.Date,'%Y-%m')", $date);
                endif;
                $this->db->select("DATE_FORMAT($table.Date,'%Y-%m') as date");
                $this->db->group_by("DATE_FORMAT($table.Date,'%Y-%m')");
                $this->db->order_by("DATE_FORMAT($table.Date,'%Y-%m')");
            elseif($status_filter and $method == "year"):
                if($date):
                    $this->db->where("DATE_FORMAT($table.Date,'%Y')", $date);
                endif;
                $this->db->select("DATE_FORMAT($table.Date,'%Y') as date");
                $this->db->group_by("DATE_FORMAT($table.Date,'%Y')");
                $this->db->order_by("DATE_FORMAT($table.Date,'%Y')");
            else:
                if($date):
                    $this->db->where("Date($table.Date)", $date);
                endif;
                $this->db->select("Date($table.Date) as date");
                $this->db->group_by("Date($table.Date)");
                $this->db->order_by("Date($table.Date)");
            endif;
        endif;

        $this->db->from($table);
        $query = $this->db->get();

        return $query->result();
    }
    #end penerimaan

    #delivery
    private function delivery($page="",$date="",$id=""){
        $CompanyID       = $this->session->CompanyID;
        $item_delivery   = $this->input->post('item_delivery');
        $table           = "PS_Delivery";
        $status_filter   = false;
        $method          = '';
        
        if($page == "item_delivery"):
            $this->db->select("
                sum(dt.Qty) as qty,
                $table.VendorID,
                vendor.Name as vendorName,
            ");
            $this->db->join("PS_Delivery_Det as dt", "$table.DeliveryNo = dt.DeliveryNo and $table.CompanyID = dt.CompanyID", "left");
            // $this->db->where("$table.Type", 1);
            // $this->db->where("$table.InvoiceStatus", 1);
            $this->db->where("$table.Status", 1);
            $this->db->group_by("$table.VendorID");
            $status_filter = true;
            $method = $item_delivery;
        endif;
        $this->db->join("PS_Vendor as vendor","vendor.VendorID = $table.VendorID");
        $this->db->where("$table.CompanyID", $CompanyID);
        if($this->input->post('StartDate')):
            $StartDate = $this->input->post('StartDate');
            $this->db->where("$table.Date >=", $StartDate);
        endif;
        if($this->input->post('EndDate')):
            $EndDate = $this->input->post('EndDate');
            $this->db->where("$table.Date <=", $EndDate." 23:59:59");
        endif;

        if($date):
            if(in_array($page, array("item_delivery"))):
                $this->db->where("$table.VendorID", $id);
            endif;
        endif;

        if($status_filter):
            if($status_filter and $method == "month"):
                if($date):
                    $this->db->where("DATE_FORMAT($table.Date,'%Y-%m')", $date);
                endif;
                $this->db->select("DATE_FORMAT($table.Date,'%Y-%m') as date");
                $this->db->group_by("DATE_FORMAT($table.Date,'%Y-%m')");
                $this->db->order_by("DATE_FORMAT($table.Date,'%Y-%m')");
            elseif($status_filter and $method == "year"):
                if($date):
                    $this->db->where("DATE_FORMAT($table.Date,'%Y')", $date);
                endif;
                $this->db->select("DATE_FORMAT($table.Date,'%Y') as date");
                $this->db->group_by("DATE_FORMAT($table.Date,'%Y')");
                $this->db->order_by("DATE_FORMAT($table.Date,'%Y')");
            else:
                if($date):
                    $this->db->where("Date($table.Date)", $date);
                endif;
                $this->db->select("Date($table.Date) as date");
                $this->db->group_by("Date($table.Date)");
                $this->db->order_by("Date($table.Date)");
            endif;
        endif;

        $this->db->from($table);
        $query = $this->db->get();

        return $query->result();
    }
    #end delivery

    #return
    private function return($page="",$date="",$id=""){
        $CompanyID       = $this->session->CompanyID;
        $purchase_return = $this->input->post('purchase_return');
        $sales_return    = $this->input->post('sales_return');
        $table           = "AP_Retur";
        $status_filter   = false;
        $method          = '';
        if($page == "purchase_return"):
            $this->db->select("
                sum(dt.Qty) as qty,
                $table.VendorID,
                vendor.Name as vendorName,
                sum(dt.Total) as total_payment,
            ");
            $this->db->join("AP_Retur_Det as dt", "$table.ReturNo = dt.ReturNo and $table.CompanyID = dt.CompanyID", "left");
            $this->db->where("$table.Type", 1);
            $this->db->where("$table.Status", 1);
            $this->db->group_by("$table.VendorID");
            $status_filter = true;
            $method = $purchase_return;
        elseif($page == "sales_return"):
            $this->db->select("
                sum(dt.Qty) as qty,
                $table.VendorID,
                vendor.Name as vendorName,
            ");
            $this->db->join("AP_Retur_Det as dt", "$table.ReturNo = dt.ReturNo and $table.CompanyID = dt.CompanyID", "left");
            $this->db->where("$table.Type", 2);
            $this->db->where("$table.Status", 1);
            $this->db->group_by("$table.VendorID");
            $status_filter = true;
            $method = $sales_return;
        endif;
        $this->db->join("PS_Vendor as vendor","vendor.VendorID = $table.VendorID");
        $this->db->where("$table.CompanyID", $CompanyID);
        if($this->input->post('StartDate')):
            $StartDate = $this->input->post('StartDate');
            $this->db->where("$table.Date >=", $StartDate);
        endif;
        if($this->input->post('EndDate')):
            $EndDate = $this->input->post('EndDate');
            $this->db->where("$table.Date <=", $EndDate." 23:59:59");
        endif;

        if($date):
            if(in_array($page, array("purchase_return"))):
                $this->db->where("$table.VendorID", $id);
            elseif(in_array($page, array("sales_return"))):
                $this->db->where("$table.VendorID", $id);
            endif;
        endif;

        if($status_filter):
            if($status_filter and $method == "month"):
                if($date):
                    $this->db->where("DATE_FORMAT($table.Date,'%Y-%m')", $date);
                endif;
                $this->db->select("DATE_FORMAT($table.Date,'%Y-%m') as date");
                $this->db->group_by("DATE_FORMAT($table.Date,'%Y-%m')");
                $this->db->order_by("DATE_FORMAT($table.Date,'%Y-%m')");
            elseif($status_filter and $method == "year"):
                if($date):
                    $this->db->where("DATE_FORMAT($table.Date,'%Y')", $date);
                endif;
                $this->db->select("DATE_FORMAT($table.Date,'%Y') as date");
                $this->db->group_by("DATE_FORMAT($table.Date,'%Y')");
                $this->db->order_by("DATE_FORMAT($table.Date,'%Y')");
            else:
                if($date):
                    $this->db->where("Date($table.Date)", $date);
                endif;
                $this->db->select("Date($table.Date) as date");
                $this->db->group_by("Date($table.Date)");
                $this->db->order_by("Date($table.Date)");
            endif;
        endif;

        $this->db->from($table);
        $query = $this->db->get();

        return $query->result();
    }
    #end return
    
    #invoice
    private function invoice($page="",$date="",$id=""){
        $CompanyID      = $this->session->CompanyID;
        $sales_open     = $this->input->post("sales_open");
        $sales_overdude = $this->input->post("sales_overdude");
        $purchase_open  = $this->input->post('purchase_open');
        $purchase_overdude = $this->input->post('purchase_overdude');
        $table          = "PS_Invoice";
        $status_filter  = false;
        $method         = '';
        if(in_array($page, array("sales_overdude","sales_open","purchase_open","purchase_overdude"))):
            $this->db->select("
                sum($table.Total - ifnull(
                    (select sum(pd.Total) from PS_Payment_Detail as pd 
                    left join PS_Payment as p on pd.PaymentNo = p.PaymentNo and pd.CompanyID = p.CompanyID
                    where pd.InvoiceNo = $table.InvoiceNo and p.Status = '1' and p.CompanyID = $table.CompanyID
                    )
                , 0)) as total,
                $table.VendorID,
                vendor.Name as vendorName,
            ");
            $this->db->join("PS_Vendor as vendor","vendor.VendorID = $table.VendorID");
            $this->db->where("$table.BranchID", null);
            $this->db->where("$table.Status", 1);
            $this->db->group_by("$table.VendorID");
            $this->db->where("$table.PaymentStatus", 0);
            
            if(in_array($page, array("sales_overdude","sales_open"))):
                $this->db->where("$table.Type", 2);
            elseif(in_array($page, array("purchase_open","purchase_overdude"))):
                $this->db->where("$table.Type", 1);
            endif;

            $StartDate = "1999-01-01";
            $status_filter = true;
            if($this->input->post('StartDate')):
                $StartDate = $this->input->post('StartDate');
            endif;
            if($page == "sales_open"):
                $this->db->where("DATE_ADD($table.Date, INTERVAL ifnull($table.Term,0) DAY) > Date(NOW()) ");
                $method        = $sales_open;
            elseif($page == "sales_overdude"):
                $this->db->where("DATE_ADD($table.Date, INTERVAL ifnull($table.Term,0) DAY) <= Date(NOW())");
                $method        = $sales_overdude;
            elseif($page == "purchase_open"):
                $this->db->where("DATE_ADD($table.Date, INTERVAL ifnull($table.Term,0) DAY) > Date(NOW()) ");
                $method        = $purchase_open;
            elseif($page == "purchase_overdude"):
                $this->db->where("DATE_ADD($table.Date, INTERVAL ifnull($table.Term,0) DAY) <= Date(NOW())");
                $method        = $purchase_overdude;
            endif;
        endif;
        // $this->db->join("PS_Invoice_Detail as dt", "$table.InvoiceNo = dt.InvoiceNo and $table.CompanyID = dt.CompanyID", "left");
        $this->db->where("$table.CompanyID", $CompanyID);
        if($status_filter):
            if(!in_array($page, array('sales_overdude','purchase_overdude'))):
                if($this->input->post('StartDate')):
                    $StartDate = $this->input->post('StartDate');
                    $this->db->where("$table.Date >=", $StartDate);
                endif;
            endif;

            if($this->input->post('EndDate')):
                $EndDate = $this->input->post('EndDate');
                $this->db->where("$table.Date <=", $EndDate." 23:59:59");
            endif;

            if($date):
                if(in_array($page, array("sales_overdude","sales_open","purchase_open","purchase_overdude"))):
                    $this->db->where("$table.VendorID", $id);
                endif;
            endif;

            if($status_filter and $method == "month"):
                if($date):
                    $this->db->where("DATE_FORMAT($table.Date,'%Y-%m')", $date);
                endif;
                $this->db->select("DATE_FORMAT($table.Date,'%Y-%m') as date");
                $this->db->group_by("DATE_FORMAT($table.Date,'%Y-%m')");
                $this->db->order_by("DATE_FORMAT($table.Date,'%Y-%m')");
            elseif($status_filter and $method == "year"):
                if($date):
                    $this->db->where("DATE_FORMAT($table.Date,'%Y')", $date);
                endif;
                $this->db->select("DATE_FORMAT($table.Date,'%Y') as date");
                $this->db->group_by("DATE_FORMAT($table.Date,'%Y')");
                $this->db->order_by("DATE_FORMAT($table.Date,'%Y')");
            else:
                if($date):
                    $this->db->where("Date($table.Date)", $date);
                endif;
                $this->db->select("Date($table.Date) as date");
                $this->db->group_by("Date($table.Date)");
                $this->db->order_by("Date($table.Date)");
            endif;
        endif;

        $this->db->from($table);
        // echo $query2 = $this->db->get_compiled_select();
        $query = $this->db->get();

        return $query->result();
    }
    #end invoice

    #payment
    private function payment($page="",$date="",$id=""){
        $CompanyID          = $this->session->CompanyID;
        $sales_payment      = $this->input->post("sales_payment");
        $purchase_payment   = $this->input->post("purchase_payment");
        $store_receivable   = $this->input->post("store_receivable");
        $table          = "PS_Payment";
        $status_filter  = false;
        $method         = '';
        if(in_array($page, array("sales_payment","purchase_payment"))):
            $this->db->select("
                sum($table.Total) as total,
                $table.VendorID,
                vendor.Name as vendorName,
            ");
            $this->db->join("PS_Vendor as vendor","vendor.VendorID = $table.VendorID");
            $this->db->where("$table.BranchID", null);
            $this->db->where("$table.Status", 1);
            $this->db->group_by("$table.VendorID");
            $status_filter = true;
            if($page == "sales_payment"):
                $this->db->where("$table.Type", 3);
                $method = $sales_payment;
            elseif($page == "purchase_payment"):
                $this->db->where("$table.Type", 2);
                $method = $purchase_payment;
            endif;
        elseif(in_array($page,array("store_receivable"))):
            $this->db->select("
                sum($table.Total) as total,
                $table.BranchID   as VendorID,
                Branch.Name       as vendorName,
            ");
            $this->db->join("Branch","Branch.BranchID = $table.BranchID");
            $this->db->where("$table.Status", 1);
            $this->db->where("$table.Type", 1);
            $this->db->group_by("$table.BranchID");
            $status_filter = true;
            $method = $store_receivable;
        elseif($page == "total_net_omset"):
            $this->db->select("
                sum($table.Total) as total,
            ");
            $this->db->where("$table.BranchID", null);
            $this->db->where("$table.Status", 1);
            $this->db->where("$table.Type", 3);
        endif;
        // $this->db->join("PS_Payment_Detail as dt", "$table.PaymentNo = dt.PaymentNo and $table.CompanyID = dt.CompanyID", "left");
        $this->db->where("$table.CompanyID", $CompanyID);
        if($this->input->post('StartDate')):
            $StartDate = $this->input->post('StartDate');
            $this->db->where("$table.Date >=", $StartDate);
        endif;

        if($this->input->post('EndDate')):
            $EndDate = $this->input->post('EndDate');
            $this->db->where("$table.Date <=", $EndDate." 23:59:59");
        endif;

        if($status_filter):
            if($date):
                if(in_array($page, array("sales_payment","purchase_payment"))):
                    $this->db->where("$table.VendorID", $id);
                elseif(in_array($page, array("store_receivable"))):
                    $this->db->where("$table.BranchID", $id);
                endif;
            endif;

            if($status_filter and $method == "month"):
                if($date):
                    $this->db->where("DATE_FORMAT($table.Date,'%Y-%m')", $date);
                endif;
                $this->db->select("DATE_FORMAT($table.Date,'%Y-%m') as date");
                $this->db->group_by("DATE_FORMAT($table.Date,'%Y-%m')");
                $this->db->order_by("DATE_FORMAT($table.Date,'%Y-%m')");
            elseif($status_filter and $method == "year"):
                if($date):
                    $this->db->where("DATE_FORMAT($table.Date,'%Y')", $date);
                endif;
                $this->db->select("DATE_FORMAT($table.Date,'%Y') as date");
                $this->db->group_by("DATE_FORMAT($table.Date,'%Y')");
                $this->db->order_by("DATE_FORMAT($table.Date,'%Y')");
            else:
                if($date):
                    $this->db->where("Date($table.Date)", $date);
                endif;
                $this->db->select("Date($table.Date) as date");
                $this->db->group_by("Date($table.Date)");
                $this->db->order_by("Date($table.Date)");
            endif;
        endif;

        $this->db->from($table);
        $query = $this->db->get();
        if(in_array($page, array("total_net_omset"))):
            return $query->row();
        else:
            return $query->result();
        endif;
    }
    #payment

    #product
    private function stock_product(){
    	$CompanyID = $this->session->CompanyID;
    	$this->db->select("sum(pb.Qty) as sum");
    	$this->db->where("p.CompanyID", $CompanyID);
    	$this->db->where("p.Active", 1);
    	$this->db->join("ps_product p", "p.ProductID = pb.ProductID and p.CompanyID = pb.CompanyID");
    	$query = $this->db->get("PS_Product_Branch pb");
    	$a = $query->row();
    	return $a->sum;
    }
    private function product($page=""){
        $CompanyID = $this->session->CompanyID;
        if($page == "minimal_product"){
            $this->db->select("
                p.Code as product_code,
                p.Name as product_name,
                ifnull(p.MinimumStock,0) as MinimumStock,
                sum(ifnull(pb.Qty,0)) as product_qty,
            ");
            $this->db->join("PS_Product_Branch pb", "pb.ProductID = p.ProductID and pb.CompanyID = p.CompanyID");
            $this->db->having("sum(ifnull(pb.Qty,0))<=10");
            $this->db->order_by("p.MinimumStock", "desc");
            $this->db->order_by("sum(ifnull(pb.Qty,0))");
            $this->db->group_by("p.ProductID");
        }
        $this->db->where("p.Active", 1);
        $this->db->where("p.CompanyID", $CompanyID);
        $this->db->where("p.Position", 0);
        $this->db->limit(10);
        $this->db->from("ps_product p");
        $query = $this->db->get();

        return $query->result();
    }

    private function product_branch($page=""){
        $CompanyID  = $this->session->CompanyID;
        $table      = "PS_Product_Branch";

        $this->db->select("
            sum(Qty) as qty,
            $table.BranchID as ID,
            Branch.Name as branchName,
        ");
        $this->db->join("Branch", "$table.BranchID = Branch.BranchID");
        $this->db->where("$table.CompanyID", $CompanyID);
        $this->db->where("Branch.Active", 1);
        $this->db->group_by("$table.BranchID");
        $this->db->order_by("sum(Qty)");
        $this->db->limit(4);
        $this->db->from($table);
        $query = $this->db->get();

        return $query->result();
    }
    #product

    #expire account
    private function list_expire(){
        $arrModule      = array();
        $arrDevice      = array();
        $arrAdditional  = array();
        $date_now       = date("Y-m-d");

        $module         = $this->main->get_module_company();
        $branch         = $this->main->branch("","",1,$this->session->CompanyID);
        $additional     = $this->api->user("additional");

        foreach ($module as $k => $v) {
            $parameter_modul    = $this->main->parameter_modul($k);
            if($module->$k->status == 1 && $k != "asset" && in_array($k, $parameter_modul)):
                $date = date($module->$k->expire);
                $days = ((strtotime ($date) - strtotime ($date_now))/(60*60*24));

                if($days<=30):
                    $data = array(
                        "module"=> $this->main->label_modul2($k),
                        "date"  => $date,
                        "hari"  => $days,
                    );
                    array_push($arrModule, $data);
                endif;
            endif;
        }

        foreach ($branch as $k => $v) {
            if($v->ExpireAccount):
                $date = date($v->ExpireAccount);
                $days = ((strtotime ($date) - strtotime ($date_now))/(60*60*24));

                if($days<=30):
                    $data = array(
                        "name"  => trim($v->name),
                        "date"  => $date,
                        "hari"  => $days,
                    );
                    array_push($arrDevice, $data);
                endif;
            endif;
        }

        foreach ($additional as $k => $v) {
            $date = date($v->VoucherExpireDate);
            $days = ((strtotime ($date) - strtotime ($date_now))/(60*60*24));
            if($days<=30):
                $data = array(
                    "name"  => trim($v->nama),
                    "date"  => $date,
                    "hari"  => $days,
                );
                array_push($arrAdditional, $data);
            endif;
        }

        $data_expire = array(
            "module"        => $arrModule,
            "devices"       => $arrDevice,
            "additional"    => $arrAdditional,
        );

        return $data_expire;
    }
    #end expire account

    private function total_vendor(){
        $CompanyID  = $this->session->CompanyID;
        $query      = $this->db->count_all("PS_Vendor where CompanyID = '$CompanyID' and Active = '1' and Position = '1'");
        $total      = 0;
        if($query):
            $total = $query;
        endif;

        return $total;
    }
}