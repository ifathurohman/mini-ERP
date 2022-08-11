<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_report extends CI_Model {
	var $order_good_receipt 	= array('DateAdd' => 'desc');
	var $column_good_receipt 	= array('ReceiveNo','ReceiveNo','ReceiveNo','Date','ReceiveName');


	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	public function get_datatables($table)
	{
		$this->_get_datatables_query($table);
		if(!empty($_POST['length']) && $_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}
	public function count_filtered($table)
	{
		$this->_get_datatables_query($table);
		$query = $this->db->get();
		return $query->num_rows();
	}
	private function _get_datatables_query($table)
	{
		$this->filter($table);
		if($table == "AP_GoodReceipt_Det"):
			$order 	= $this->order_good_receipt;
			$column = $this->column_good_receipt;
		elseif($table == "AP_GoodReceipt"):
			$order 	= $this->order_good_receipt;
			$column = $this->column_good_receipt;
		elseif($table == "sales_visiting"):
			$table = "SP_TransactionRoute";
		elseif($table == "sales_visiting_time"):
			$table = "SP_TransactionRouteDetail";
		endif;
		$this->db->from($table);
	}
	private function filter($table = ""){

		#jangan lupa bedakan mana filter report sama table

		$report 	= $this->input->post("report");
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$group 		= $this->input->post("group");
		if($this->input->get("group")):
			$group = $this->input->get("group");
		endif;
		if($this->input->get("report")):
			$report = $this->input->get("report");
		endif;

		if($table == "AP_GoodReceipt_Det"):
			$this->select_good_receipt();
		elseif($table == "PS_Mutation_Detail"):
			$this->select_mutation();
		elseif($table == "AP_GoodReceipt"):
			$this->select_example();
		elseif($table == "PS_Payment_Detail"):
		if($this->session->report_page == "payment"):
			$this->select_payment();
		elseif($this->session->report_page == "payment_payable"):
			$this->select_payment_payable();
		endif;
		elseif($table == "PS_Invoice_Detail"):
			if($this->session->report_page == "debtors_account"):
				$this->select_debtors_account();	
			else:
				$this->select_creditors_account();			
			endif;
		elseif($table == "PS_Sell"):
			if($this->session->report_page == "sales_book"):
				$this->select_sales_book();
			elseif($this->session->report_page == "outstanding_delivery"):
				$this->select_outstanding_delivery();
			elseif($this->session->report_page == "distributor_selling"):
				$this->select_distributor_selling();
			endif;
		elseif($table == "PS_Purchase"):
			if($this->session->report_page == "purchase_book"):
				$this->select_purchase_book();
			elseif($this->session->report_page == "purchase"):
				$this->select_purchase();
			endif;
		elseif($table == "Voucher"):
			if($this->session->report_page == "voucher"):
				$this->select_voucher();
			endif;
		elseif($table == "PS_Payment"):
			if($this->session->report_page == "saldo_receivable"):
				$this->select_saldo_receivable();
			else:
				$this->select_saldo_ap();
			endif;
		elseif($table == "PS_Sell_Detail"):
			$this->select_selling();
		elseif($table == "PS_Invoice"):
			if($this->session->report_page == "invoice_vendor"):
				$this->select_invoice_vendor();
			else:
				$this->select_invoice_customer();
			endif;
		elseif($table == "AC_BalancePayable"):
			if($this->session->report_page == "correction_ap"):
				$this->select_correction_ap();
			else:
				$this->select_correction_ar();
			endif;
		elseif($table == "AP_Retur"):
			if($this->session->report_page == "return_selling"):
				$this->select_return_selling();
			elseif($this->session->report_page == "return"):
				$this->select_return();
			else:
				$this->select_return_distributor();
			endif;
		elseif($table == "AC_CorrectionPR_Det"):
			$this->select_account_receive();
		elseif($report == "serial_number"):
			$this->select_serial_number();
		elseif($report == "stock"):
			$this->select_stock();
		elseif($report == "correction_stock"):
			$this->select_correction_stock();
		elseif($report == "cash"):
			$this->select_casch($table);
		elseif($report == "bank"):
			$this->select_bank($table);
		#ini untuk salespro
		elseif($table == "sales_visiting"):
			$this->select_sales_visiting();
		elseif($table == "sales_visiting_time"):
			$this->select_sales_visiting_time();
		endif;
	}
	private function select_example()
	{
		$this->db->select("
			AP_GoodReceipt.ReceiveNo 	as receiveno, 
			AP_GoodReceipt.ReceiveName	as receivename,
			AP_GoodReceipt.Date 		as date,
		");
	}
	private function select_good_receipt()
	{
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$group 		= $this->input->post("group");
		$search 	= $this->input->post("search");
		if($this->input->get("group")):
            $group 	= $this->input->get("group");
        	$search = $this->input->get("search");
        endif;
		#ini mengatur selct
		if($group == "date"):
			$this->db->select("
				AP_GoodReceipt.Date 			as date,
				SUM(AP_GoodReceipt_Det.Qty)		as qty,
				AP_GoodReceipt_Det.Subtotal 	as subtotal,		
			");
			$this->db->group_by("AP_GoodReceipt.Date, AP_GoodReceipt_Det.Subtotal");
			$this->db->order_by("AP_GoodReceipt.Date","ASC");
		elseif($group == "gr_code"):
			$this->db->select("
				(case
					when AP_GoodReceipt.Type = 1 then purchasedet.PurchaseNo
					else ''
				end)	 					    as transactioncode,
				AP_GoodReceipt.Date 			as date,
				AP_GoodReceipt.ReceiveNo 		as receiveno,
				SUM(AP_GoodReceipt_Det.Qty)		as qty,
				SUM(AP_GoodReceipt_Det.Subtotal)as total_price,
				AP_GoodReceipt.Total 			as sub_total,
				AP_GoodReceipt.Discount 		as total_discount,
				AP_GoodReceipt.TotalPPN,
				AP_GoodReceipt.Payment 			as payment,
				AP_GoodReceipt.DeliveryCost,
				Branch.Name 					as branchName,
				PS_Vendor.Name 					as receivename,
			");
			$this->db->group_by("AP_GoodReceipt.ReceiveNo,purchasedet.PurchaseNo,");
			$this->db->order_by("AP_GoodReceipt.date_add","ASC");
		elseif($group == "purchase_code"):
			$this->db->where("AP_GoodReceipt.Type", 1);
			$this->db->select("
				AP_GoodReceipt.ReceiveNo 		as receiveno, 
				AP_GoodReceipt.Type,
				AP_GoodReceipt.Date 			as date,
				AP_GoodReceipt.PurchaseNo 		as PurchaseNo, 
				SUM(AP_GoodReceipt_Det.Qty)		as qty,
				AP_GoodReceipt.Payment 			as subtotal,		
			");
			$this->db->group_by("AP_GoodReceipt.ReceiveNo,AP_GoodReceipt.PurchaseNo");
			$this->db->order_by("AP_GoodReceipt.Date","ASC");
		elseif($group == "receipt_name"):
			$this->db->select("
				PS_Vendor.VendorID,
				ifnull(PS_Vendor.Name,'') 		as receivename,
				SUM(AP_GoodReceipt_Det.Qty)		as qty,
				AP_GoodReceipt.Payment 			as subtotal,
			");
			$this->db->group_by("AP_GoodReceipt.VendorID,AP_GoodReceipt.Payment");
			$this->db->order_by("PS_Vendor.Name","ASC");
			
		elseif($group == "product_name"):
			$this->db->select("
				ps_product.Code 				as product_code,
				ps_product.Name 				as product_name,
				ifnull(unit.Uom,'') 			as unit_name,
				sum(AP_GoodReceipt_Det.Qty) 	as qty,
				AP_GoodReceipt_Det.conversion 	as Conversion,
			");

			$this->db->group_by("ps_product.productid,ps_product.Code,ps_product.Name,ifnull(unit.Uom,''),AP_GoodReceipt_Det.conversion");
		elseif($group == "store"):
			$this->db->select("
				AP_GoodReceipt.BranchID,
				ifnull(Branch.Name,'') 			as branchName,
				SUM(AP_GoodReceipt_Det.Qty) 	as qty,
				AP_GoodReceipt.Payment 			as subtotal,
			");
			$this->db->group_by("AP_GoodReceipt.BranchID,AP_GoodReceipt.Payment");
			$this->db->order_by("Branch.Name","ASC");
		else:
			$this->db->select("
				(case
					when AP_GoodReceipt.Type = 1 then purchasedet.PurchaseNo
					else ''
				end)	 					    as transactioncode,
				AP_GoodReceipt.ReceiveNo 		as receiveno, 
				ifnull(PS_Vendor.Name,'') 		as receivename,
				AP_GoodReceipt.Date 			as date,
				AP_GoodReceipt_Det.Qty 			as qty,
				AP_GoodReceipt_Det.Conversion	as conversion,
				AP_GoodReceipt_Det.Price		as price,
				AP_GoodReceipt_Det.SubTotal		as subtotal,
				AP_GoodReceipt_Det.Discount	 	as discount,
				AP_GoodReceipt.Type				as type,
				AP_GoodReceipt.Tax 				as tax,
				AP_GoodReceipt.TotalPPN 		as totalppn,
				AP_GoodReceipt.DeliveryCost		as deliverycost,
				ps_product.Code 				as product_code,
				ps_product.Name 				as product_name,
				ifnull(unit.Uom,'')				as unit_name,
				ifnull(Branch.Name,'') 			as branchName,

			");
			$this->db->order_by("
				AP_GoodReceipt.Date,
				AP_GoodReceipt.ReceiveNo,
				AP_GoodReceipt.ReceiveName,
				ps_product.Name","ASC");
		endif;
		$this->db->join("AP_GoodReceipt","AP_GoodReceipt_Det.ReceiveNo = AP_GoodReceipt.ReceiveNo and AP_GoodReceipt_Det.CompanyID = AP_GoodReceipt.CompanyID","left");
		 $this->db->join("PS_Purchase_Detail as purchasedet", "AP_GoodReceipt_Det.PurchaseDet = purchasedet.PurchaseDet and AP_GoodReceipt_Det.CompanyID = purchasedet.CompanyID", "left");
		// $this->db->join("ps_unit","AP_GoodReceipt_Det.UnitID = ps_unit.UnitID","left");
		$this->db->join("ps_product_unit as unit", "AP_GoodReceipt_Det.Uom = unit.ProductUnitID", "left");
		$this->db->join("ps_product","AP_GoodReceipt_Det.ProductID = ps_product.ProductID","left");
		$this->db->join("Branch", "AP_GoodReceipt.BranchID = Branch.BranchID and AP_GoodReceipt.CompanyID = Branch.CompanyID", "left");
		$this->db->join("PS_Vendor", "AP_GoodReceipt.VendorID = PS_Vendor.VendorID and AP_GoodReceipt.CompanyID = PS_Vendor.CompanyID", "left");

		#ini filter where
		$this->db->where("AP_GoodReceipt.Status",1);
		$this->db->where("AP_GoodReceipt.CompanyID",$this->session->CompanyID);
		$this->db->where("AP_GoodReceipt_Det.CompanyID",$this->session->CompanyID);
		if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE(AP_GoodReceipt.Date) >=",$start_date);
			$this->db->where("DATE(AP_GoodReceipt.Date) <=",$end_date);
		endif;

		$vendor = $this->input->post('vendor');
		if($vendor != "all" and $vendor):
			$this->db->where("AP_GoodReceipt.VendorID", $vendor);
		endif;

		$branch = $this->input->post('branch');
		if($branch != "all" && $branch):
			$this->db->where("AP_GoodReceipt.BranchID", $branch);
		endif;

		$product = $this->input->post('product');
		if($product != "all" && $product):
			$this->db->where("AP_GoodReceipt_Det.ProductID", $product);
		endif;

		if($search):
		$this->db->group_start();
			if($group == "all"):
				$this->db->like("ps_product.Name",$search);
				$this->db->or_like("DATE(AP_GoodReceipt.Date)",$search);
				$this->db->or_like("AP_GoodReceipt.ReceiveNo",$search);
				$this->db->or_like("AP_GoodReceipt.ReceiveName",$search);
			elseif($group == "date"):
				$this->db->like("DATE(AP_GoodReceipt.Date)",$search);
			elseif($group == "gr_code"):
				$this->db->like("AP_GoodReceipt.ReceiveNo",$search);
			elseif($group == "receipt_name"):
				$this->db->like("AP_GoodReceipt.ReceiveName",$search);
			elseif($group == "product_name"):
				$this->db->like("ps_product.Name",$search);
			endif;
		$this->db->group_end();
		endif;
	}

	private function select_purchase(){
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$group 		= $this->input->post("group");
		$search 	= $this->input->post("search");
		if($this->input->get("group")):
            $group 	= $this->input->get("group");
        	$search = $this->input->get("search");
        endif;
        $table = "PS_Purchase_Detail";
        if($group == "all"):
        	$this->db->select("
        		PS_Purchase.PurchaseNo,
        		PS_Purchase.Date,
        		vendor.Name 		as vendor_name,
        		ps_product.Code 	as product_code,
        		ps_product.Name 	as product_name,
        		$table.Qty 			as qty,
        		ifnull(unit.Uom,'') as unit_name,
        		$table.Conversion 	as conversion,
        		$table.Price 		as price,
        		ifnull($table.Discount,0) 		as discount,
        		ifnull($table.DiscountValue,0) 	as discount_value,
        		$table.TotalPrice 	as total,
        		PS_Purchase.Tax 		as tax,
        		PS_Purchase.DeliveryCost 		as deliverycost,
        		sales.Name 			as sales_name,
        		$table.Remark 		as remark,
        		Branch.Name 		as branchName,
    		");
    	elseif($group == "gr_purchase"):
    		$this->db->select("
    			PS_Purchase.PurchaseNo,
    			PS_Purchase.Date,
    			vendor.Name 	 	as vendor_name,
    			sales.Name 			as sales_name,
    			PS_Purchase.Remark 	as remark,
    			sum($table.Qty) 	as qty,
    			PS_Purchase.Total 	as subtotal,
    			PS_Purchase.Discount as discount,
    			PS_Purchase.TotalPPN,
    			PS_Purchase.DeliveryCost,
    			PS_Purchase.Payment as payment,
    			Branch.Name as branchName,
			");
			$this->db->group_by("
				PS_Purchase.PurchaseNo,
				PS_Purchase.Date,
				vendor.Name,
				sales.Name,
				PS_Purchase.Remark,
				");
		elseif($group == "product_name"):
			$this->db->select("
    			ps_product.Code 	as product_code,
        		ps_product.Name 	as product_name,
        		sum($table.Qty)		as qty,
        		ifnull(unit.Uom,'') as unit_name,
        		PS_Purchase_Detail.Conversion 	as conversion,
			");
			$this->db->group_by("ps_product.ProductID,ps_product.Code,ps_product.Name,ifnull(unit.Uom,''),PS_Purchase_Detail.Conversion");
		elseif($group == "vendor"):
			$this->db->select("  			
				vendor.Name 					as vendor_name,
				count(PS_Purchase.PurchaseNo) 	as totalpurchase,
        		sum((select sum(Qty) from PS_Purchase_Detail where PurchaseNo = PS_Purchase.PurchaseNo and CompanyID = PS_Purchase.CompanyID)) as qty,
        		sum((select sum(Price * Qty) from PS_Purchase_Detail where PurchaseNo = PS_Purchase.PurchaseNo and CompanyID = PS_Purchase.CompanyID))	as price,
        		sum(PS_Purchase.Payment) as total,
			");
			$this->db->group_by("
				vendor.VendorID,
			");
		elseif($group == "store"):
			$this->db->select("
				Branch.Name as branchName,
				(select count(mt.BranchID) from PS_Purchase mt where mt.BranchID = PS_Purchase.BranchID and mt.Status = 1 and mt.Date >= '$start_date' and mt.Date <= '$end_date') 	as totalpurchase,
				sum(PS_Purchase_Detail.Qty) as qty,
				sum(PS_Purchase_Detail.Qty * PS_Purchase_Detail.Price) as price,
				(select sum(mt.Payment) from PS_Purchase mt where mt.BranchID = PS_Purchase.BranchID and mt.Status = 1 and mt.Date >= '$start_date' and mt.Date <= '$end_date') 	as total,
			");
			$this->db->group_by("PS_Purchase.BranchID");
			$this->db->order_by("Branch.Name");
        endif;

        $this->db->join("PS_Vendor as vendor", "vendor.VendorID = PS_Purchase.VendorID", "left");
        $this->db->join("Branch", "Branch.BranchID = PS_Purchase.BranchID", "left");
        if($group != "vendor"):
	        $this->db->join("$table", "PS_Purchase.PurchaseNo = PS_Purchase_Detail.PurchaseNo and PS_Purchase.CompanyID = PS_Purchase_Detail.CompanyID", "left");
	        $this->db->join("ps_product", "ps_product.ProductID = PS_Purchase_Detail.ProductID", "left");
	        // $this->db->join("ps_unit", "ps_unit.UnitID = PS_Purchase_Detail.UnitID", "left");
	        $this->db->join("ps_product_unit as unit", "PS_Purchase_Detail.Uom = unit.ProductUnitID", "left");
	        $this->db->join("ps_unit", "ps_unit.UnitID = PS_Purchase_Detail.UnitID", "left");
	        $this->db->join("PS_Sales as sales", "sales.SalesID = PS_Purchase.SalesID", "left");
        endif;

        $this->db->where("PS_Purchase.CompanyID", $this->session->CompanyID);
        $this->db->where("PS_Purchase.Status", 1);

        if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE(PS_Purchase.Date) >=",$start_date);
			$this->db->where("DATE(PS_Purchase.Date) <=",$end_date);
		endif;

		$vendor = $this->input->post('vendor');
		if($vendor != "all" and $vendor):
			$this->db->where("PS_Purchase.VendorID", $vendor);
		endif;

		$branch = $this->input->post('branch');
		if($branch != "all" && $branch):
			$this->db->where("PS_Purchase.BranchID", $branch);
		endif;

		$product = $this->input->post('product');
		if($product != "all" && $product):
			$this->db->where("PS_Purchase_Detail.ProductID", $product);
		endif;
	}

	private function select_correction_stock(){
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$group 		= $this->input->post("group");
		$search 	= $this->input->post("search");
		$table 		= "PS_Correction";

		$not_detail = array('store');
		if($group == "all"):
			$this->db->select("
				$table.CorrectionNo,
				$table.Date,
				ps_product.Code as product_code,
				ps_product.Name as product_name,
				detail.Qty,
				detail.CorrectionQty,
				Branch.Name 	as branchName,
			");
		elseif($group == "transaction"):
			$this->db->select("
				$table.CorrectionNo,
				$table.Date,
				Branch.Name 	as branchName,
				SUM(detail.Qty) as Qty,
				SUM(detail.CorrectionQty) as CorrectionQty,
			");
			$this->db->group_by("
				$table.CorrectionNo,
			");
		elseif($group == "store"):
			$this->db->select("
				Branch.Name as branchName,
				count($table.CorrectionNo) as totalTransaction,
				sum((select sum(detail.Qty) from PS_Correction_Detail as detail where $table.CorrectionNo = detail.CorrectionNo and $table.CompanyID = detail.CompanyID)) as Qty,
				sum((select sum(detail.CorrectionQty) from PS_Correction_Detail as detail where $table.CorrectionNo = detail.CorrectionNo and $table.CompanyID = detail.CompanyID)) as CorrectionQty,
			");
			$this->db->group_by("
				Branch.BranchID,
			");
		endif;
		if(!in_array($group,$not_detail)):
			$this->db->join("PS_Correction_Detail as detail", "$table.CorrectionNo = detail.CorrectionNo and $table.CompanyID = detail.CompanyID", "left");
			$this->db->join("ps_product", "ps_product.ProductID = detail.ProductID", "left");
		endif;
		$this->db->join("Branch", "Branch.BranchID = $table.BranchID and Branch.CompanyID = $table.CompanyID", "left");

		$this->db->where("$table.CompanyID", $this->session->CompanyID);
		$this->db->where("$table.Type", 1);

		if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE($table.Date) >=",$start_date);
			$this->db->where("DATE($table.Date) <=",$end_date);
		endif;

		$branch = $this->input->post('branch');
		if($branch != "all" && $branch):
			$this->db->where("Branch.BranchID", $branch);
		endif;

		$product = $this->input->post('product');
		if($product != "all" && $product):
			$this->db->where("ps_product.ProductID", $product);
		endif;
	}

	private function select_mutation()
	{
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$group 		= $this->input->post("group");
		$search 	= $this->input->post("search");
		if($this->input->get("group")):
            $group 	= $this->input->get("group");
        	$search = $this->input->get("search");
        endif;
		#ini mengatur selct
		if($group == "date"):
			$this->db->select("
				PS_Mutation.Date 				as date,
				SUM(PS_Mutation_Detail.Qty)		as qty,
				PS_Mutation_Detail.Price		as price,
				SUM(PS_Mutation_Detail.Price * PS_Mutation_Detail.Qty)		as subtotal,
			");
			#ini group by
			$this->db->group_by("PS_Mutation.Date");
			#ini order
			$this->db->order_by("PS_Mutation.Date","ASC");
		elseif($group == "mutation_code"):
			$this->db->select("
				PS_Mutation.MutationNo 			as mutationno,
				SUM(PS_Mutation_Detail.Qty)		as qty,
				PS_Mutation_Detail.Price		as price,
				SUM(PS_Mutation_Detail.Price * PS_Mutation_Detail.Qty)		as subtotal,
			");
			$this->db->group_by("PS_Mutation.MutationNo,PS_Mutation_Detail.Price");
			$this->db->order_by("PS_Mutation.MutationNo","ASC");
		elseif($group == "mutation_from" || $group == "mutation_to"):
			$this->db->select("
				(CASE
	            WHEN PS_Mutation.Type = 1 THEN b1.Name
	            WHEN PS_Mutation.Type = 2 THEN b1.Name
	            ELSE  user.nama
	            END) AS mutationfrom,
	            (CASE
	            WHEN PS_Mutation.Type = 1 THEN b2.Name
	            WHEN PS_Mutation.Type = 2 THEN user.nama
	            ELSE  b2.Name
	            END) AS mutationto,

				SUM(PS_Mutation_Detail.Qty)		as qty,
				PS_Mutation_Detail.Price		as price,
				SUM(PS_Mutation_Detail.Price * PS_Mutation_Detail.Qty)		as subtotal,
			");
			$this->db->group_by("b1.Name,b2.Name,PS_Mutation.Type,PS_Mutation_Detail.Price");
			// $this->db->order_by("","ASC");
		else:
			$this->db->select("
				PS_Mutation.MutationNo 			as mutationno, 
				(CASE
	            WHEN PS_Mutation.Type = 1 THEN b1.Name
	            WHEN PS_Mutation.Type = 2 THEN b1.Name
	            ELSE  user.nama
	            END) AS mutationfrom,
	            (CASE
	            WHEN PS_Mutation.Type = 1 THEN b2.Name
	            WHEN PS_Mutation.Type = 2 THEN user.nama
	            ELSE  b2.Name
	            END) AS mutationto,


				PS_Mutation.Date 				as date,
				PS_Mutation_Detail.Qty 			as qty,
				PS_Mutation_Detail.Conversion	as conversion,
				PS_Mutation_Detail.Price		as price,
				(PS_Mutation_Detail.Price * PS_Mutation_Detail.Qty)		as subtotal,
				ps_product.Code 				as product_code,
				ps_product.Name 				as product_name,
				ifnull(unit.Uom,'') 			as unit_name
			");
			$this->db->order_by("
				PS_Mutation.Date,
				PS_Mutation.MutationNo,b1.Name,b2.Name,ps_product.Name","ASC");
		endif;
		$this->db->join("PS_Mutation","PS_Mutation_Detail.MutationNo = PS_Mutation.MutationNo and PS_Mutation_Detail.CompanyID = PS_Mutation.CompanyID","left");
		// $this->db->join("ps_unit","PS_Mutation_Detail.UnitID = ps_unit.UnitID","left");
		$this->db->join("ps_product_unit as unit", "PS_Mutation_Detail.Uom = unit.ProductUnitID", "left");
		$this->db->join("ps_product","PS_Mutation_Detail.ProductID = ps_product.ProductID","left");

		$this->db->join("Branch as b1","PS_Mutation.BranchID = b1.BranchID","left");
		$this->db->join("Branch as b2","PS_Mutation.BranchIDTo = b2.BranchID","left");
		$this->db->join("user","PS_Mutation.CompanyID = user.id_user","left");

		#ini filter where
		$this->db->where("PS_Mutation.CompanyID",$this->session->CompanyID);
		$this->db->where("PS_Mutation_Detail.CompanyID",$this->session->CompanyID);
		if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE(PS_Mutation.Date) >=",$start_date);
			$this->db->where("DATE(PS_Mutation.Date) <=",$end_date);
		endif;
		if($search):
		$this->db->group_start();
			if($group == "all"):
				$this->db->like("ps_product.Name",$search);
				$this->db->or_like("DATE(PS_Mutation.Date)",$search);
				$this->db->or_like("PS_Mutation.MutationNo",$search);
				$this->db->or_like("b1.Name",$search);
				$this->db->or_like("b2.Name",$search);
			elseif($group == "date"):
				$this->db->like("DATE(PS_Mutation.Date)",$search);
			elseif($group == "gr_code"):
				$this->db->like("PS_Mutation.MutationNo",$search);
			elseif($group == "mutation_from"):
				$this->db->like("b1.Name",$search);
			elseif($group == "mutation_to"):
				$this->db->like("b2.Name",$search);
			endif;
		// // $this->db->or_like("transaction.date",$_POST['search']);
		$this->db->group_end();
		endif;
	}
	private function select_return()
	{
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$group 		= $this->input->post("group");
		$search 	= $this->input->post("search");
		if($this->input->get("group")):
            $group 	= $this->input->get("group");
        	$search = $this->input->get("search");
        endif;
		#ini mengatur selct
		if($group == "date"):
			$this->db->select("
				AP_Retur.Date 				as date,
				SUM(AP_Retur_Det.Qty)		as qty,
				SUM(AP_Retur_Det.Qty * AP_Retur_Det.Price) as subtotal
			");
			#ini group by
			$this->db->group_by("AP_Retur.Date");
			#ini order
			$this->db->order_by("AP_Retur.Date","ASC");
		elseif($group == "return_code"):
			$this->db->select("
				AP_Retur.ReturNo 			as returno, 
				AP_Retur.ReceiveNo 			as receiveno, 
				PS_Vendor.Name 				as vendorname,
				SUM(AP_Retur_Det.Total)		as total,
				SUM(AP_Retur_Det.Qty) 		as qty,
				SUM(AP_Retur_Det.Qty * AP_Retur_Det.Price) as subtotal,
				SUM(AP_Retur_Det.Discount) 	as discount,
				SUM((case when AP_Retur.Tax = 1 then ((AP_Retur_Det.Qty * AP_Retur_Det.Price)-AP_Retur_Det.Discount)*0.1 else 0 end)) as TotalPPN,
				AP_Retur.Date 				as date,
				Branch.Name 				as branchName,
			");
			#ini group by
			$this->db->group_by("AP_Retur.ReturNo");
			#ini order
			$this->db->order_by("AP_Retur.ReturNo","ASC");
		elseif($group == "vendor_name"):
			$this->db->select("
				PS_Vendor.Name 				as vendorname,
				SUM((select SUM(Qty) from AP_Retur_Det where ReturNo = AP_Retur.ReturNo and CompanyID = AP_Retur.CompanyID)) 	as qty,
				SUM((select SUM(Total) from AP_Retur_Det where ReturNo = AP_Retur.ReturNo and CompanyID = AP_Retur.CompanyID)) 	as total,
				count(AP_Retur.ReturNo) 	as total_return,
			");
			#ini group by
			$this->db->group_by("PS_Vendor.VendorID");
			#ini order
			$this->db->order_by("PS_Vendor.Name","ASC");
		elseif($group == "store"):
			$this->db->select("
				Branch.Name 			as branchName,
				SUM(AP_Retur_Det.Qty) 	as qty,
				SUM(AP_Retur_Det.Total) as total,
				(select count(mt.BranchID) from AP_Retur mt where mt.BranchID = AP_Retur.BranchID and mt.Status = 1 and mt.Date >= '$start_date' and mt.Date <= '$end_date') 	as total_return,

			");
			$this->db->group_by("AP_Retur.BranchID");
			$this->db->order_by("Branch.Name");
		elseif($group == "product_name"):
			$this->db->select("
				ps_product.Code 				as product_code,
				ps_product.Name 				as product_name,
				ifnull(unit.Uom,'') 			as unit_name,
				AP_Retur_Det.Conversion 		as conversion,
				sum(AP_Retur_Det.Qty) 			as qty,

			");
			$this->db->group_by("ps_product.productid,ps_product.Code,ps_product.Name,ifnull(unit.Uom,''),AP_Retur_Det.Conversion");
			$this->db->order_by("ps_product.Code","ASC");
		else:
			$this->db->select("
				AP_Retur.ReturNo 			as returno,
				AP_Retur.ReceiveNo 			as receiveno, 
				AP_Retur.Date 				as date,
				AP_Retur.Tax,
				AP_Retur_Det.Qty 			as qty,
				AP_Retur_Det.Conversion		as conversion,
				AP_GoodReceipt.TotalPPN     as totalppn,
				AP_Retur_Det.Price			as price,
				AP_Retur_Det.DiscountPersent	as discount,
				AP_Retur_Det.Total			as total,
				(AP_Retur_Det.Price * AP_Retur_Det.Qty)		as subtotal,
				ps_product.Code 				as product_code,
				ps_product.Name 				as product_name,
				ifnull(unit.Uom,'') 			as unit_name,
				PS_Vendor.Name 					as vendorname,
				Branch.Name 					as branchName,
			");
			$this->db->order_by("AP_Retur.ReturNo","ASC");
		endif;

		$this->db->join("PS_Vendor","AP_Retur.VendorID = PS_Vendor.VendorID","left");
		$this->db->join("Branch", "Branch.BranchID = AP_Retur.BranchID and Branch.CompanyID = AP_Retur.CompanyID", "left");
		if($group != "vendor_name"):
			$this->db->join("AP_Retur_Det","AP_Retur_Det.ReturNo = AP_Retur.ReturNo and AP_Retur_Det.CompanyID = AP_Retur.CompanyID","left");
			$this->db->join("AP_GoodReceipt","AP_Retur.ReceiveNo = AP_GoodReceipt.ReceiveNo and AP_Retur.CompanyID = AP_GoodReceipt.CompanyID","left");
			// $this->db->join("ps_unit","AP_Retur_Det.UnitID = ps_unit.UnitID","left");
			$this->db->join("ps_product_unit as unit", "AP_Retur_Det.Uom = unit.ProductUnitID", "left");
			$this->db->join("ps_product","AP_Retur_Det.ProductID = ps_product.ProductID","left");
			$this->db->where("AP_Retur_Det.CompanyID",$this->session->CompanyID);
		endif;
		#ini filter where
		$this->db->where("AP_Retur.Status", 1);
		$this->db->where("AP_Retur.Type", 1);
		$this->db->where("AP_Retur.CompanyID",$this->session->CompanyID);
		if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE(AP_Retur.Date) >=",$start_date);
			$this->db->where("DATE(AP_Retur.Date) <=",$end_date);
		endif;

		$vendor = $this->input->post('vendor');
		if($vendor != "all" and $vendor):
			$this->db->where("AP_Retur.VendorID", $vendor);
		endif;

		$branch = $this->input->post('branch');
		if($branch != "all" && $branch):
			$this->db->where("AP_Retur.BranchID", $branch);
		endif;

		$product = $this->input->post('product');
		if($product != "all" && $product):
			$this->db->where("AP_Retur_Det.ProductID", $product);
		endif;

		if($search):
		$this->db->group_start();
			if($group == "all"):
				$this->db->like("ps_product.Name",$search);
				$this->db->or_like("DATE(AP_Retur.Date)",$search);
				$this->db->or_like("AP_Retur.ReturNo",$search);
				$this->db->or_like("AP_Retur.ReceiveNo",$search);
				$this->db->or_like("PS_Vendor.Name",$search);
			elseif($group == "date"):
				$this->db->like("DATE(AP_Retur.Date)",$search);
			elseif($group == "return_code"):
				$this->db->like("AP_Retur.ReturNo",$search);
				// $this->db->like("AP_Retur.ReceiveNo",$search);
			elseif($group == "vendor_name"):
				$this->db->or_like("PS_Vendor.Name",$search);
			elseif($group == "product_name"):
				$this->db->like("ps_product.Name",$search);
				$this->db->or_like("ps_product.Code",$search);
			endif;
		// // $this->db->or_like("transaction.date",$_POST['search']);
		$this->db->group_end();
		endif;
	}
	private function select_payment()
	{
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$group 		= $this->input->post("group");
		$search 	= $this->input->post("search");
		if($this->input->get("group")):
            $group 	= $this->input->get("group");
        	$search = $this->input->get("search");
        endif;
		#ini mengatur selct
		if($group == "date"):
			$this->db->select("
				PS_Payment.Status,
				PS_Payment.Type,
				PS_Payment.Date 				as date,
				SUM(PS_Payment.GrandTotal)		as grandtotal,
				SUM(PS_Payment.Giro)			as giro,
				SUM(PS_Payment.Credit) 			as credit,
				SUM(PS_Payment.Cash) 			as cash,
				SUM(PS_Payment.AdditionalCost)	as addcost,
				SUM(PS_Payment.Giro + PS_Payment.Credit + PS_Payment.Cash + PS_Payment.AdditionalCost)	as total_payment
			");
			#ini group by
			$this->db->group_by("PS_Payment.Date");
			#ini order
			$this->db->order_by("PS_Payment.Date","ASC");
		elseif($group == "payment_code"):
			$this->db->select("
				PS_Payment.Status,
				PS_Payment.Type,
				PS_Payment.Date,
				PS_Payment.PaymentNo 	as paymentno,
				PS_Payment.GrandTotal	as grandtotal,
				PS_Payment.Giro			as giro,
				PS_Payment.Credit 		as credit,
				PS_Payment.Cash			as cash,
				PS_Payment.AdditionalCost	as addcost,
				(PS_Payment.Giro + PS_Payment.Credit + PS_Payment.Cash + PS_Payment.AdditionalCost)	as total_payment
			");
			#ini group by
			$this->db->group_by("PS_Payment.PaymentNo");
			#ini order
			$this->db->order_by("PS_Payment.PaymentNo","ASC");
		elseif($group == "store_name"):
			$this->db->select("
				Branch.Name 					as store_name,
				SUM(PS_Payment.GrandTotal)		as grandtotal,
				SUM(PS_Payment.Giro)			as giro,
				SUM(PS_Payment.Credit) 			as credit,
				SUM(PS_Payment.Cash) 			as cash,
				SUM(PS_Payment.AdditionalCost)	as addcost,
				SUM(PS_Payment.Giro + PS_Payment.Credit + PS_Payment.Cash + PS_Payment.AdditionalCost)	as total_payment
			");
			#ini group by
			$this->db->where("Branch.Name is not null");
			$this->db->group_by("PS_Payment.BranchID");
			#ini order
			$this->db->order_by("Branch.Name","ASC");
		elseif($group == "sales_code"):
			$this->db->select("
				PS_Payment_Detail.SellNo 		as sellno,
				SUM(PS_Payment.GrandTotal)		as grandtotal,
				SUM(PS_Payment.Giro)			as giro,
				SUM(PS_Payment.Credit) 			as credit,
				SUM(PS_Payment.Cash) 			as cash,
				SUM(PS_Payment.AdditionalCost)	as addcost,
				SUM(PS_Payment.Giro + PS_Payment.Credit + PS_Payment.Cash + PS_Payment.AdditionalCost)	as total_payment
			");
			#ini group by
			$this->db->group_by("PS_Payment_Detail.SellNo");
			#ini order
			$this->db->order_by("PS_Payment_Detail.SellNo","ASC");
		else:
			$this->db->select("
				PS_Payment.Status,
				PS_Payment.Type,
				PS_Payment.Date 			as date,
				PS_Payment.PaymentNo 		as paymentno, 
				(case
					when PS_Payment.Type = 0 then PS_Payment_Detail.SellNo
					else
						(case
							when PS_Payment_Detail.Type = 1 then PS_Payment_Detail.InvoiceNo
							else balance.Code
						end)
				end) as sellno,
				(case
					when PS_Payment.Type = 0 then PS_Sell.Date
					else
						(case
							when PS_Payment_Detail.Type = 1 then PS_Invoice.Date
							else balance.Date
						end)
				end) as transactionDate,
				Branch.Name 				as store_name,
				(case
					when PS_Payment.Type = 0 then PS_Payment_Detail.Total
					else PS_Payment_Detail.TotalPay
				end)  as grandtotal,
				ifnull(PS_Payment_Detail.TotalUnpaid,PS_Payment_Detail.Total) as unpaid,
				PS_Payment_Detail.Total	as total_payment,
				vendor.Name as vendorName,
			");
			$this->db->order_by("PS_Payment.PaymentNo","ASC");
		endif;
		$this->db->join("PS_Payment","PS_Payment_Detail.PaymentNo = PS_Payment.PaymentNo and PS_Payment_Detail.CompanyID = PS_Payment.CompanyID","left");
		$this->db->join("AC_BalancePayable as balance", "balance.BalanceID = PS_Payment_Detail.BalanceID", "left");
		$this->db->join("PS_Sell", "PS_Payment_Detail.SellNo = PS_Sell.SellNo and PS_Payment_Detail.CompanyID = PS_Sell.CompanyID", "left");
		$this->db->join("PS_Invoice", "PS_Payment_Detail.InvoiceNo = PS_Invoice.InvoiceNo and PS_Payment_Detail.CompanyID = PS_Invoice.CompanyID", "left");
		$this->db->join("Branch","PS_Payment.BranchID = Branch.BranchID","left");
		$this->db->join("PS_Vendor as vendor", "vendor.VendorID = PS_Payment.VendorID", "left");
		#ini filter where
		$this->db->where("PS_Payment.CompanyID",$this->session->CompanyID);
		$this->db->where_in("PS_Payment.Type",array(0,3));
		$this->db->where("PS_Payment.Status",1);
		$this->db->where("PS_Payment_Detail.CompanyID",$this->session->CompanyID);
		if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE(PS_Payment.Date) >=",$start_date);
			$this->db->where("DATE(PS_Payment.Date) <=",$end_date);
		endif;
	}

	private function select_payment_payable()
	{
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$group 		= $this->input->post("group");
		$search 	= $this->input->post("search");
		if($this->input->get("group")):
            $group 	= $this->input->get("group");
        	$search = $this->input->get("search");
        endif;
		#ini mengatur selct
		if($group == "payment_code"):
			$this->db->select("
				PS_Payment.Status,
				PS_Payment.Type,
				PS_Payment.PaymentNo 			as paymentno,
				PS_Payment.Date,
				sum(PS_Payment_Detail.TotalPay)	as grandtotal,
				PS_Payment.Giro				as giro,
				PS_Payment.Credit 			as credit,
				PS_Payment.Cash 			as cash,
				PS_Payment.AdditionalCost	as addcost,
				PS_Payment.Total			as total_payment,
				vendor.Name as vendorName,
			");
			#ini group by
			$this->db->group_by("PS_Payment.PaymentNo");
			#ini order
			$this->db->order_by("PS_Payment.PaymentNo","ASC");
		else:
			$this->db->select("
				PS_Payment.Status,
				PS_Payment.Type,
				PS_Payment.Date 			as date,
				PS_Payment.PaymentNo 		as paymentno, 
				Branch.Name 				as store_name,
				PS_Payment_Detail.TotalPay 	as grandtotal,
				PS_Payment_Detail.TotalUnpaid 	as unpaid,
				PS_Payment_Detail.Total		as total_payment,
				
				vendor.Name as vendorName,
				ifnull(balance.Date,invoice.Date) 		as transactionDate,
				ifnull(balance.Code,invoice.InvoiceNo) 	as transactionCode,
			");
			$this->db->order_by("PS_Payment.PaymentNo","ASC");
		endif;
		$this->db->join("PS_Payment","PS_Payment_Detail.PaymentNo = PS_Payment.PaymentNo and PS_Payment_Detail.CompanyID = PS_Payment.CompanyID","left");
		$this->db->join("PS_Invoice as invoice","invoice.InvoiceNo = PS_Payment_Detail.InvoiceNo and invoice.CompanyID = PS_Payment_Detail.CompanyID", "left");
		$this->db->join("AC_BalancePayable as balance", "balance.BalanceID = PS_Payment_Detail.BalanceID", "left");
		$this->db->join("Branch","PS_Payment.BranchID = Branch.BranchID","left");
		$this->db->join("PS_Vendor as vendor", "PS_Payment.VendorID = vendor.VendorID");
		#ini filter where
		$this->db->where("PS_Payment.CompanyID",$this->session->CompanyID);
		$this->db->where("PS_Payment_Detail.Type",1);
		$this->db->where("PS_Payment.Type",2);
		$this->db->where("PS_Payment.Status",1);
		$this->db->where("PS_Payment_Detail.CompanyID",$this->session->CompanyID);
		if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE(PS_Payment.Date) >=",$start_date);
			$this->db->where("DATE(PS_Payment.Date) <=",$end_date);
		endif;
		if($this->input->post("vendor") != "all"):
			$this->db->where("PS_Payment.VendorID", $this->input->post("vendor"));
		endif;
	}

	private function select_selling()
	{
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$group 		= $this->input->post("group");
		$search 	= $this->input->post("search");
		if($this->input->get("group")):
            $group 	= $this->input->get("group");
        	$search = $this->input->get("search");
        endif;
		#ini mengatur selct
		if($group == "date"):
			$this->db->select("
				PS_Sell.Date 				as date,
				SUM(PS_Sell_Detail.Qty)		as qty,
				SUM(PS_Sell_Detail.Price * PS_Sell_Detail.Qty)		as subtotal
			");
			#ini group by
			$this->db->group_by("PS_Sell.Date");
			#ini order
			$this->db->order_by("PS_Sell.Date","ASC");
		elseif($group == "store_name"):
			$this->db->select("
				Branch.Name 				as store_name,
				SUM(PS_Sell_Detail.Qty)		as qty,
				SUM(PS_Sell.Payment)		as payment,
			");
			#ini group by
			$this->db->group_by("PS_Sell.BranchID");
			#ini order
			$this->db->order_by("Branch.Name","ASC");
		elseif($group == "product_name"):
			$this->db->select("
				ps_product.Code 			as product_code,
				ps_product.Name 			as product_name,
				ifnull(unit.Uom,'') 		as unit_name,
				PS_Sell_Detail.Conversion	as conversion,
				sum(PS_Sell_Detail.Qty) 	as qty,
			");
			#ini group by
			$this->db->group_by("ps_product.ProductID,ps_product.Code,ps_product.Name,ifnull(unit.Uom,''),PS_Sell_Detail.Conversion");
			#ini order
			$this->db->order_by("ps_product.Code","ASC");
		else:
			$this->db->select("
				PS_Sell.Date 				as date,
				PS_Sell.SellNo 				as sellno, 
				Branch.Name 				as store_name,
				ps_product.Code 			as product_code,
				ps_product.Name 			as product_name,
				PS_Sell_Detail.Qty 			as qty,
				ifnull(unit.Uom,'') 		as unit_name,
				PS_Sell_Detail.Conversion	as conversion,
				PS_Sell_Detail.Price		as price,
				(PS_Sell_Detail.Price * PS_Sell_Detail.Qty)		as subtotal,
				PS_Sell_Detail.Discount		as discount,
				PS_Sell.TotalPPN			as tax,
				PS_Sell.Tax,
				PS_Sell_Detail.TotalPrice	as payment,
			");
			$this->db->order_by("PS_Sell.SellNo","ASC");
		endif;
		$this->db->join("PS_Sell","PS_Sell_Detail.SellNo = PS_Sell.SellNo and PS_Sell_Detail.CompanyID = PS_Sell.CompanyID","left");
		$this->db->join("Branch","PS_Sell.BranchID = Branch.BranchID","left");
		// $this->db->join("ps_unit","PS_Sell_Detail.UnitID = ps_unit.UnitID","left");
		$this->db->join("ps_product_unit as unit", "PS_Sell_Detail.Uom = unit.ProductUnitID", "left");
		$this->db->join("ps_product","PS_Sell_Detail.ProductID = ps_product.ProductID","left");
		#ini filter where
		$this->db->where("PS_Sell.CompanyID",$this->session->CompanyID);
		$this->db->where("PS_Sell_Detail.CompanyID",$this->session->CompanyID);
		$this->db->where("PS_Sell.Mobile", 1);
		if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE(PS_Sell.Date) >=",$start_date);
			$this->db->where("DATE(PS_Sell.Date) <=",$end_date);
		endif;

		$branch = $this->input->post('branch');
		if($branch != "all" && $branch):
			$this->db->where("PS_Sell.BranchID", $branch);
		endif;

		$product = $this->input->post('product');
		if($product != "all" && $product):
			$this->db->where("PS_Sell_Detail.ProductID", $product);
		endif;

		if($search):
		$this->db->group_start();
			if($group == "all"):
				$this->db->like("ps_product.Name",$search);
				$this->db->or_like("ps_product.Code",$search);
				$this->db->or_like("DATE(PS_Sell.Date)",$search);
				$this->db->or_like("PS_Sell.SellNo",$search);
				$this->db->or_like("Branch.Name",$search);
			elseif($group == "date"):
				$this->db->like("DATE(PS_Sell.Date)",$search);
			elseif($group == "selling_name"):
				$this->db->like("PS_Sell.SellNo",$search);
				$this->db->or_like("Branch.Name",$search);
			elseif($group == "product_name"):
				$this->db->like("ps_product.Name",$search);
				$this->db->or_like("ps_product.Code",$search);
			endif;
		// // $this->db->or_like("transaction.date",$_POST['search']);
		$this->db->group_end();
		endif;
	}
		private function select_distributor_selling()
	{
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$group 		= $this->input->post("group");
		$search 	= $this->input->post("search");
		if($this->input->get("group")):
            $group 	= $this->input->get("group");
        	$search = $this->input->get("search");
        endif;	
		#ini mengatur selct
		if($group == "selling"):
			$this->db->select("
				PS_Sell.SellNo 						as sellno,
				PS_Sell.Date 						as date,
				PS_Sell.Tax 						as tax,
				PS_Sell.PPN 						as ppn,
				PS_Vendor.Name 						as customerName,
				SUM(PS_Sell_Detail.Qty)				as qty,
				sales.Name 							as salesName,
				PS_Sell.Remark				 		as remark,
				PS_Sell.Payment 					as payment,
				PS_Sell.Total 						as subtotal,
				PS_Sell.Discount  					as totaldiscount,
				PS_Sell.DeliveryCost 				as deliverycost,
				PS_Sell.TotalPPN 					as totalppn,
				SUM(PS_Sell_Detail.TotalPrice) 	    as TotalPrice,
				Branch.Name 						as branchName,
				PS_Sell.DeliveryCity
			");
			#ini group by
			$this->db->group_by("
				PS_Sell.SellNo,
				");
			#ini order
			$this->db->order_by("PS_Sell.SellNo","ASC");
		// elseif($group == "customer_name"):
		elseif($group == "all"):
			$this->db->select("
				PS_Sell.Date 					as date,
				PS_Sell.SellNo 					as sellno,
				PS_Sell.Tax 					as tax,
				PS_Sell.PPN 					as ppn,
				PS_Vendor.Name 					as customerName,
				ps_product.Code 				as product_code,
				ps_product.Name 				as product_name,
				PS_Sell_Detail.Qty				as qty,
				ifnull(unit.Uom,'') 			as unit_name,
				PS_Sell_Detail.Conversion		as conversion,
				PS_Sell_Detail.Price			as price,
				PS_Sell_Detail.TotalPrice 		as TotalPrice,
				PS_Sell_Detail.Discount 		as diskon,
				PS_Sell_Detail.DiscountValue 	as diskonValue,
				PS_Sell_Detail.Remark			as remark,
				PS_Sell.Payment 				as payment,
				sales.Name 						as salesName,
				Branch.Name 					as branchName,
				PS_Sell.DeliveryCity
			");
			$this->db->order_by("PS_Sell.SellNo","ASC");
		elseif($group == "product_name"):
			$this->db->select("
				ps_product.Code 				as product_code,
				ps_product.Name 				as product_name,
				ifnull(unit.Uom,'') 			as unit_name,
				PS_Sell_Detail.Conversion 		as conversion,
				sum(PS_Sell_Detail.Qty) 		as qty,
			");
			#ini group by
			$this->db->group_by("
				ps_product.Code,
				ps_product.Name,
				ifnull(unit.Uom,''),
				PS_Sell_Detail.Conversion,
				");
			$this->db->order_by("ps_product.Code","ASC");
		elseif($group == "vendor"):
			$this->db->select("  			
				PS_Vendor.Name 			as vendor_name,
				count(PS_Sell.SellNo) 	as totalTransaction,
				sum((select sum(Qty) from PS_Sell_Detail where SellNo = PS_Sell.SellNo and CompanyID = PS_Sell.CompanyID)) as qty,
				sum((select sum(Price * Qty) from PS_Sell_Detail where SellNo = PS_Sell.SellNo and CompanyID = PS_Sell.CompanyID)) as price,
				sum(PS_Sell.Payment) 	as payment,
        		
			");
			$this->db->group_by("
				PS_Vendor.Name,
			");
			#ini order
			$this->db->order_by("PS_Vendor.Name","ASC");
		elseif($group == "store"):
			$this->db->select("
				Branch.Name 			as branchName,
				count(PS_Sell.SellNo) 	as totalTransaction,
				sum((select sum(Qty) from PS_Sell_Detail where SellNo = PS_Sell.SellNo and CompanyID = PS_Sell.CompanyID)) as qty,
				sum((select sum(Price * Qty) from PS_Sell_Detail where SellNo = PS_Sell.SellNo and CompanyID = PS_Sell.CompanyID)) as price,
				sum(PS_Sell.Payment) 	as payment,
			");
			$this->db->group_by("
				PS_Sell.BranchID,
			");
		endif;

		$not_detail = array('vendor','store');
		if(!in_array($group, $not_detail)):
			$this->db->join("PS_Sell_Detail","PS_Sell_Detail.SellNo = PS_Sell.SellNo and PS_Sell_Detail.CompanyID = PS_Sell.CompanyID","left");
			// $this->db->join("ps_unit","PS_Sell_Detail.UnitID = ps_unit.UnitID","left");
			$this->db->join("ps_product_unit as unit", "PS_Sell_Detail.Uom = unit.ProductUnitID", "left");
			$this->db->join("ps_product","PS_Sell_Detail.ProductID = ps_product.ProductID","left");
		endif;
		
		$this->db->join("PS_Vendor","PS_Sell.VendorID = PS_Vendor.VendorID","left");
		$this->db->join("Branch","PS_Sell.BranchID = Branch.BranchID","left");
	 	$this->db->join("PS_Sales   as sales", "PS_Sell.SalesID = sales.SalesID", "left");
		#ini filter where
		$this->db->where("PS_Sell.Mobile",0);
		$this->db->where("PS_Sell.CompanyID",$this->session->CompanyID);
		// $this->db->where("PS_Sell.BranchID !=", null);
		if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE(PS_Sell.Date) >=",$start_date);
			$this->db->where("DATE(PS_Sell.Date) <=",$end_date);
		endif;
		if($this->input->post("tax") != "all"):
            $tax = $this->input->post("tax");
            $this->db->where("PS_Sell.Tax", $tax);
        endif;
        if($this->input->post("customer") != "all"):
            $customer = $this->input->post("customer");
            $this->db->where("PS_Vendor.VendorID", $customer);
        endif;
        if($this->input->post("product") != "all" and !in_array($group, $not_detail)):
            $product = $this->input->post("product");
            $this->db->where("ps_product.ProductID", $product);
        endif;
        if($this->input->post("city")):
        	$this->db->where("PS_Sell.DeliveryCity", $this->input->post("city"));
        endif;

        $branch = $this->input->post("branch");
        if($branch != "all" and $branch):
        	$this->db->where("PS_Sell.BranchID", $branch);
        endif;
	}

	private function select_outstanding_delivery()
	{
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$group 		= $this->input->post("group");
		$search 	= $this->input->post("search");
		if($this->input->get("group")):
            $group 	= $this->input->get("group");
        	$search = $this->input->get("search");
        endif;
		#ini mengatur selct
		if($group == "transaction"):
			$this->db->select("
				PS_Sell.SellNo,
	            PS_Sell.Date,
	            vendor.Name  as vendorName,
	            Branch.Name  as branchName,
	            SUM(PS_Sell_Detail.Qty) as Qty,
	            SUM(ifnull(PS_Sell_Detail.DeliveryQty, 0)) as DeliveryQty,
	            (SUM(PS_Sell_Detail.Qty) - SUM(ifnull(PS_Sell_Detail.DeliveryQty, 0))) as qtyResidue,
			");
			#ini group by
			$this->db->group_by("
				PS_Sell.SellNo,
				PS_Sell.Date,
				vendor.Name
				");
			#ini order
			$this->db->order_by("PS_Sell.SellNo","ASC");
		elseif($group == "all"):
			$this->db->select("
				PS_Sell_Detail.SellNo,
	            PS_Sell.Date,
	            vendor.Name  as vendorName,
	            Branch.Name  as branchName,
	            product.Code as productCode,
	            product.Name as productName,
	            PS_Sell_Detail.Qty,
	            ifnull(PS_Sell_Detail.DeliveryQty, 0) as DeliveryQty,
	            (PS_Sell_Detail.Qty - ifnull(PS_Sell_Detail.DeliveryQty, 0)) as qtyResidue,
			");
			// #ini group by
			// $this->db->group_by("
			// 	sell.SellNo,
			// 	sell.Date,
			// 	vendor.Name,
			// 	product.Code
			// 	");
			$this->db->order_by("PS_Sell.SellNo","ASC");
		elseif($group == "product_name"):
			$this->db->select("
				product.Code 					as product_code,
				product.Name 					as product_name,
				ifnull(unit.Uom,'') 			as unit_name,
				PS_Sell_Detail.Conversion 		as conversion,
				sum(PS_Sell_Detail.Qty) 		as Qty,
				sum(PS_Sell_Detail.DeliveryQty) as DeliveryQty,
				sum(PS_Sell_Detail.Qty - ifnull(PS_Sell_Detail.DeliveryQty,0)) as qtyResidue,
			");
			#ini group by
			$this->db->group_by("
				product.Code,
				product.Name,
				ifnull(unit.Uom,''),
				PS_Sell_Detail.Conversion,
			");
			$this->db->order_by("product.Code","ASC");
		elseif($group == "store"):
			$this->db->select("
				Branch.Name as branchName,
				SUM(PS_Sell_Detail.Qty) as Qty,
				sum(PS_Sell_Detail.DeliveryQty) as DeliveryQty,
				sum(PS_Sell_Detail.Qty - ifnull(PS_Sell_Detail.DeliveryQty,0)) as qtyResidue,
			");
			$this->db->group_by("
				PS_Sell.BranchID,
			");
		endif;
		$this->db->join("PS_Sell_Detail", "PS_Sell_Detail.SellNo = PS_Sell.SellNo and PS_Sell_Detail.CompanyID = PS_Sell.CompanyID", "left");
        $this->db->join("PS_Vendor as vendor", "PS_Sell.VendorID = vendor.VendorID", "left");
        $this->db->join("Branch", "PS_Sell.BranchID = Branch.BranchID", "left");
        $this->db->join("ps_product as product", "PS_Sell_Detail.ProductID = product.ProductID", "left");
        // $this->db->join("ps_unit", "PS_Sell_Detail.UnitID = ps_unit.UnitID", "left");
        $this->db->join("ps_product_unit as unit", "PS_Sell_Detail.Uom = unit.ProductUnitID", "left");
		#ini filter where
		$this->db->where("(PS_Sell_Detail.Qty - ifnull(PS_Sell_Detail.DeliveryQty, 0))>0");
		$this->db->where("PS_Sell.CompanyID",$this->session->CompanyID);
		$this->db->where("PS_Sell.DeliveryParameter",1);
		$this->db->where("PS_Sell.Mobile",0);
		// $this->db->where("sell.BranchID",null);
		if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE(PS_Sell.Date) >=",$start_date);
			$this->db->where("DATE(PS_Sell.Date) <=",$end_date);
		endif;

		$customer = $this->input->post("customer");
		if($customer != "all" && $customer):
            $this->db->where("vendor.VendorID", $customer);
        endif;

        $product = $this->input->post("product");
		if($product != "all" && $product):
            $this->db->where("PS_Sell_Detail.ProductID", $product);
        endif;

        $branch = $this->input->post("branch");
		if($branch != "all" && $branch):
            $this->db->where("PS_Sell.BranchID", $branch);
        endif;

		if($search):
		$this->db->group_start();
			if($group == "all"):
				$this->db->like("product.Name",$search);
				$this->db->or_like("product.Code",$search);
				$this->db->or_like("DATE(PS_Delivery.Date)",$search);
				$this->db->or_like("PS_Delivery.SellNo",$search);
			elseif($group == "transaction"):
				$this->db->like("PS_Delivery.SellNo",$search);
				$this->db->or_like("product.Code",$search);
				$this->db->or_like("DATE(PS_Delivery.Date)",$search);
				
			endif;
		$i = 0;
        $column = $this->column;
        $Search = $this->input->post("Search");
        foreach ($column as $item) // loop column 
        {
            if($Search){
                
                if($i===0){
                    $this->db->group_start();
                    $this->db->like($item, $Search);
                }
                else{
                    $this->db->or_like($item, $Search);
                }
                if(count($column) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $column[$i] = $item; // set column array variable to order processing
            $i++;
        }
		$this->db->group_end();
		endif;
	}

	private function select_return_selling()
	{
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$group 		= $this->input->post("group");
		$search 	= $this->input->post("search");
		if($this->input->get("group")):
            $group 	= $this->input->get("group");
        	$search = $this->input->get("search");
        endif;	
		#ini mengatur selct
		if($group == "selling"):
			$this->db->select("
				AP_Retur.ReturType,
				(case
		            when AP_Retur.ReturType = 3 then sell.BranchID
		            when AP_Retur.ReturType = 4 then PS_Delivery.BranchID
		            else AP_Retur.ReceiveNo
		        end) 									as BranchID,
				AP_Retur.ReturNo 						as returno, 
				AP_Retur.Date 							as date,
				sum(AP_Retur_Det.Qty) 					as qty,
				(case
		            when AP_Retur.ReturType = 3 then sell.SellNo
		            when AP_Retur.ReturType = 4 then PS_Delivery.DeliveryNo
		            else AP_Retur.ReceiveNo
		        end)	 								as SellNo,
				AP_Retur.Remark 			 			as remark,
				PS_Vendor.Name 							as vendorname,
				(case
		            when AP_Retur.ReturType = 3 then sell.Date
		            when AP_Retur.ReturType = 4 then PS_Delivery.Date
		            else AP_Retur.ReceiveNo
		        end)									as sellDate,
				sales.Name      						as sales_name,
				sum(AP_Retur_Det.Total) 				as total,
			");
			
			#ini group by
			$this->db->group_by("
				AP_Retur.ReturNo,	
			");
			#ini order
			$this->db->order_by("AP_Retur.ReturNo ","ASC");
		elseif($group == "all"):
			$this->db->select("
				AP_Retur.BranchID,
				AP_Retur.ReturNo 				as returno, 
				AP_Retur.Date 					as date,
				AP_Retur_Det.Qty 				as qty,
				AP_Retur_Det.Conversion			as conversion,

				(case
		            when AP_Retur.ReturType = 3 then sell.SellNo
		            when AP_Retur.ReturType = 4 then PS_Delivery.DeliveryNo
		            else AP_Retur.ReceiveNo
		        end)	 						as SellNo,
				AP_Retur.Remark 			 	as remark,
				PS_Vendor.Name 					as vendorname,
				(case
		            when AP_Retur.ReturType = 3 then sell.Date
		            when AP_Retur.ReturType = 4 then PS_Delivery.Date
		            else sell.Date
		        end)							as sellDate,

				AP_Retur_Det.Remark 			as remark,
				AP_Retur_Det.Price 				as price,
				AP_Retur_Det.Discount 			as discount,
				AP_Retur.Tax 					as tax,
				AP_Retur_Det.Total 				as total,
				ps_product.Code 				as product_code,
				ps_product.Name 				as product_name,
				ifnull(unit.Uom,'') 			as unit_name,
				PS_Vendor.Name 					as vendorname,
				branch.Name 					as branch,
			");
			$this->db->order_by("AP_Retur.ReturNo","ASC");
		endif;
		$this->db->join("PS_Sell as sell", "AP_Retur.sellno = sell.sellno and AP_Retur.CompanyID = sell.CompanyID", "left");

		$this->db->join("PS_Sell", "AP_Retur.SellNo = PS_Sell.SellNo and AP_Retur.CompanyID = PS_Sell.CompanyID", "left");
		$this->db->join("PS_Delivery", "AP_Retur.DeliveryNo = PS_Delivery.DeliveryNo and AP_Retur.CompanyID = PS_Delivery.CompanyID", "left");
		$this->db->join("AP_GoodReceipt", "AP_Retur.ReceiveNo = AP_GoodReceipt.ReceiveNo and AP_Retur.CompanyID = AP_GoodReceipt.CompanyID", "left");

		$this->db->join("AP_Retur_Det","AP_Retur.ReturNo = AP_Retur_Det.ReturNo and AP_Retur.CompanyID = AP_Retur_Det.CompanyID","left");
		$this->db->join("Branch as branch", "AP_Retur_Det.BranchID = branch.BranchID", "left");
		$this->db->join("PS_Vendor","AP_Retur.VendorID = PS_Vendor.VendorID","left");
		// $this->db->join("ps_unit","AP_Retur_Det.UnitID = ps_unit.UnitID","left");
		$this->db->join("ps_product_unit as unit", "AP_Retur_Det.Uom = unit.ProductUnitID", "left");
		$this->db->join("ps_product","AP_Retur_Det.ProductID = ps_product.ProductID","left");
		$this->db->join("PS_Sales as sales", "AP_Retur.SalesID = sales.SalesID", "left");

		#ini filter where
		// $this->db->where("AP_Retur.BranchID",null);
		$this->db->where_in("AP_Retur.Type", array(2));
		// $this->db->where("AP_Retur.ReturType !=", 1);

		$this->db->where("PS_Vendor.CompanyID",$this->session->CompanyID);
		$this->db->where("ps_product.CompanyID",$this->session->CompanyID);
		$this->db->where("AP_Retur_Det.CompanyID",$this->session->CompanyID);
		if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE(AP_Retur.Date) >=",$start_date);
			$this->db->where("DATE(AP_Retur.Date) <=",$end_date);
		endif;
		if($this->input->post("customer") != "all"):
            $customer = $this->input->post("customer");
            $this->db->where("PS_Vendor.VendorID", $customer);
        endif;
        if($this->input->post("product") != "all"):
            $product = $this->input->post("product");
            $this->db->where("ps_product.ProductID", $product);
        endif;

        $branch = $this->input->post('branch');
        if($branch != "all" && $branch):
            $this->db->where("AP_Retur_Det.BranchID", $branch);
        endif;

		if($search):
		$this->db->group_start();
			if($group == "all"):
				$this->db->like("r.ReturNo",$search);
				$this->db->or_like("ps_product.Code",$search);
				$this->db->or_like("DATE(r.Date)",$search);
				$this->db->or_like("r.SellNo",$search);
			elseif($group == "selling"):
				$this->db->like("r.SellNo",$search);
				$this->db->or_like("ps_product.Code",$search);
				$this->db->or_like("DATE(r.Date)",$search);
				
			endif;
		$i = 0;
        $column = $this->column;
        $Search = $this->input->post("Search");
        foreach ($column as $item) // loop column 
        {
            if($Search){
                
                if($i===0){
                    $this->db->group_start();
                    $this->db->like($item, $Search);
                }
                else{
                    $this->db->or_like($item, $Search);
                }
                if(count($column) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $column[$i] = $item; // set column array variable to order processing
            $i++;
        }
		$this->db->group_end();
		endif;
	}

	private function select_return_distributor()
	{
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$group 		= $this->input->post("group");
		$search 	= $this->input->post("search");
		if($this->input->get("group")):
            $group 	= $this->input->get("group");
        	$search = $this->input->get("search");
        endif;	
		#ini mengatur selct
		if($group == "distributor"):
			$this->db->select("
				AP_Retur.ReturType,
				AP_Retur.BranchID,
				AP_Retur.ReturNo 				as returno, 
				AP_Retur.Date 					as date,
				AP_Retur_Det.Qty 				as qty,
				sell.SellNo 					as SellNo,
				AP_Retur_Det.Remark 			as remark,
				PS_Vendor.Name 					as vendorname,
				sell.Date       				as sellDate,
				branch.Name 					as branch,
				sales.Name      				as sales_name,
				AP_Retur_Det.Total 				as total_qty
			");
			
			#ini group by
			$this->db->group_by("
				AP_Retur.ReturType,
				AP_Retur.BranchID,
				AP_Retur.ReturNo, 
				AP_Retur.Date, 	
				AP_Retur_Det.Qty, 
				sell.SellNo,
				AP_Retur_Det.Remark, 
				PS_Vendor.Name, 	
				sell.Date,
				branch.Name, 
				sales.Name,  
				AP_Retur_Det.Total	
			");
			#ini order
			$this->db->order_by("AP_Retur.ReturNo ","ASC");
		elseif($group == "all"):
			$this->db->select("
				AP_Retur.ReturType,
				AP_Retur.BranchID,
				AP_Retur.ReturNo 				as returno, 
				AP_Retur.Date 					as date,
				AP_Retur_Det.Qty 				as qty,
				AP_Retur_Det.Conversion			as conversion,
				sell.SellNo 					as SellNo,
				AP_Retur_Det.Remark 			as remark,
				ps_product.Code 				as product_code,
				ps_product.Name 				as product_name,
				ps_unit.Name 					as unit_name,
				PS_Vendor.Name 					as vendorname,
				branch.Name 					as branch,
				sell.Date       				as sellDate
			");
			$this->db->order_by("AP_Retur.ReturNo","ASC");
		endif;
		$this->db->join("Branch as branch", "AP_Retur.BranchID = branch.BranchID", "left");
		$this->db->join("AP_Retur_Det","AP_Retur.ReturNo = AP_Retur_Det.ReturNo and AP_Retur.CompanyID = AP_Retur_Det.CompanyID","left");
		$this->db->join("PS_Vendor","AP_Retur.VendorID = PS_Vendor.VendorID","left");
		$this->db->join("ps_unit","AP_Retur_Det.UnitID = ps_unit.UnitID","left");
		$this->db->join("ps_product","AP_Retur_Det.ProductID = ps_product.ProductID","left");
		$this->db->join("PS_Sell as sell", "AP_Retur.sellno = sell.sellno and AP_Retur.CompanyID = sell.CompanyID", "left");
		$this->db->join("PS_Sales as sales", "sell.SalesID = sales.SalesID", "left");

		#ini filter where
		$this->db->where("AP_Retur.Type !=", 1);
		$this->db->where("PS_Vendor.CompanyID",$this->session->CompanyID);
		$this->db->where("ps_product.CompanyID",$this->session->CompanyID);
		$this->db->where("AP_Retur_Det.CompanyID",$this->session->CompanyID);
		if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE(AP_Retur.Date) >=",$start_date);
			$this->db->where("DATE(AP_Retur.Date) <=",$end_date);
		endif;
		if($this->input->post("customer") != "all"):
            $customer = $this->input->post("customer");
            $this->db->where("PS_Vendor.VendorID", $customer);
        endif;
        if($this->input->post("product") != "all"):
            $product = $this->input->post("product");
            $this->db->where("ps_product.ProductID", $product);
        endif;
		if($search):
		$this->db->group_start();
			if($group == "all"):
				$this->db->like("r.ReturNo",$search);
				$this->db->or_like("ps_product.Code",$search);
				$this->db->or_like("DATE(r.Date)",$search);
				$this->db->or_like("r.SellNo",$search);
			elseif($group == "selling"):
				$this->db->like("r.SellNo",$search);
				$this->db->or_like("ps_product.Code",$search);
				$this->db->or_like("DATE(r.Date)",$search);
				
			endif;
		$i = 0;
        $column = $this->column;
        $Search = $this->input->post("Search");
        foreach ($column as $item) // loop column 
        {
            if($Search){
                
                if($i===0){
                    $this->db->group_start();
                    $this->db->like($item, $Search);
                }
                else{
                    $this->db->or_like($item, $Search);
                }
                if(count($column) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $column[$i] = $item; // set column array variable to order processing
            $i++;
        }
		$this->db->group_end();
		endif;
	}

	private function select_invoice_customer()
	{
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$group 		= $this->input->post("group");
		$search 	= $this->input->post("search");
		if($this->input->get("group")):
            $group 	= $this->input->get("group");
        	$search = $this->input->get("search");
        endif;	
		#ini mengatur selct
		if($group == "transaction"):
			$this->db->select("
				customer.CompanyID,
		        customer.Name,

				PS_Invoice.Type,
				PS_Invoice.OrderType,
				PS_Invoice.Date,
				PS_Invoice.InvoiceNo,
		        PS_Invoice_Detail.Subtotal,
		        PS_Invoice_Detail.Discount,
		        PS_Invoice_Detail.PPN,
		        PS_Invoice_Detail.DeliveryCost,
		        PS_Invoice_Detail.Total,
		        PS_Invoice_Detail.Remark
			");
			
			#ini group by
			$this->db->group_by("
				customer.Name,
				customer.CompanyID,

				PS_Invoice.OrderType,
				PS_Invoice.Type,
				PS_Invoice.Date,
				PS_Invoice.InvoiceNo,
				PS_Invoice_Detail.Subtotal,
		        PS_Invoice_Detail.Discount,
		        PS_Invoice_Detail.PPN,
		        PS_Invoice_Detail.DeliveryCost,
		        PS_Invoice_Detail.Total,
		        PS_Invoice_Detail.Remark
			");
			#ini order
			$this->db->order_by("PS_Invoice.InvoiceNo","ASC");
		elseif($group == "all"):
			$this->db->select("
				customer.CompanyID,
				PS_Invoice.Type,
				PS_Invoice.OrderType,
				PS_Invoice.Date,
				PS_Invoice.InvoiceNo,
				ifnull(retur.ReturNo,'')   		    as ReturNo,
           		ifnull(sell.SellNo,retur.SellNo)    as SellNo,
				(case 
                	when PS_Invoice_Detail.SellNo is not null then sell.SellNo
                	when PS_Invoice_Detail.DeliveryNo is not null then delivery.DeliveryNo
                	else retur.ReturNo
          	 	end) 									as transactionCode,
		        (case
		            when PS_Invoice_Detail.SellNo is not null then sell.Date
                	when PS_Invoice_Detail.DeliveryNo is not null then delivery.Date
                	else retur.Date
		        end)										as transactionDate,
		        customer.Name,
		        PS_Invoice_Detail.Subtotal,
		        PS_Invoice_Detail.Discount,
		        PS_Invoice_Detail.PPN,
		        PS_Invoice_Detail.DeliveryCost,
		        PS_Invoice_Detail.Total,
		        PS_Invoice_Detail.Remark
			");
			
			#ini group by
			$this->db->group_by("
				delivery.DeliveryNo,
				delivery.Date,

				retur.ReturNo,
				retur.Date,
				retur.SellNo,

				customer.CompanyID,
				customer.Name,

				sell.SellNo,
				sell.Date,

				PS_Invoice.OrderType,
				PS_Invoice.Type,
				PS_Invoice.Date,
				PS_Invoice.InvoiceNo,
				PS_Invoice_Detail.SellNo,
				PS_Invoice_Detail.DeliveryNo,
				PS_Invoice_Detail.ReturNo,
				PS_Invoice_Detail.Subtotal,
		        PS_Invoice_Detail.Discount,
		        PS_Invoice_Detail.PPN,
		        PS_Invoice_Detail.DeliveryCost,
		        PS_Invoice_Detail.Total,
		        PS_Invoice_Detail.Remark
			");
			$this->db->order_by("PS_Invoice.InvoiceNo","ASC");
		endif;
		
		$this->db->join("PS_Vendor as customer","PS_Invoice.VendorID = customer.VendorID","left");
		$this->db->join("PS_Invoice_Detail","PS_Invoice.InvoiceNo = PS_Invoice_Detail.InvoiceNo and PS_Invoice.CompanyID = PS_Invoice_Detail.CompanyID","left");
		$this->db->join("PS_Sell as sell","PS_Invoice_Detail.SellNo = sell.SellNo and PS_Invoice_Detail.CompanyID = sell.CompanyID","left");
		$this->db->join("PS_Delivery as delivery","PS_Invoice_Detail.DeliveryNo = delivery.DeliveryNo and PS_Invoice_Detail.CompanyID = delivery.CompanyID","left");
		$this->db->join("AP_Retur as retur","PS_Invoice_Detail.ReturNo = retur.ReturNo and PS_Invoice_Detail.CompanyID = retur.CompanyID","left");
		#ini filter where
		$this->db->where("PS_Invoice.Type =", 2);
		$this->db->where("customer.CompanyID",$this->session->CompanyID);
		if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE(PS_Invoice.Date) >=",$start_date);
			$this->db->where("DATE(PS_Invoice.Date) <=",$end_date);
		endif;
		if($this->input->post("customer") != "all"):
            $customer = $this->input->post("customer");
            $this->db->where("customer.VendorID", $customer);
        endif;
		if($search):
		// $this->db->group_start();
		// 	if($group == "all"):
		// 		$this->db->like("PS_Invoice.InvoiceNo",$search);
		// 		$this->db->or_like("ps_product.Code",$search);
		// 		$this->db->or_like("DATE(r.Date)",$search);
		// 		$this->db->or_like("r.SellNo",$search);
		// 	elseif($group == "transaction"):
		// 		$this->db->like("r.SellNo",$search);
		// 		$this->db->or_like("ps_product.Code",$search);
		// 		$this->db->or_like("DATE(r.Date)",$search);
				
		// 	endif;
		$i = 0;
        $column = $this->column;
        $Search = $this->input->post("Search");
        foreach ($column as $item) // loop column 
        {
            if($Search){
                
                if($i===0){
                    $this->db->group_start();
                    $this->db->like($item, $Search);
                }
                else{
                    $this->db->or_like($item, $Search);
                }
                if(count($column) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $column[$i] = $item; // set column array variable to order processing
            $i++;
        }
		$this->db->group_end();
		endif;
	}

	private function select_invoice_vendor()
	{
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$group 		= $this->input->post("group");
		$search 	= $this->input->post("search");
		if($this->input->get("group")):
            $group 	= $this->input->get("group");
        	$search = $this->input->get("search");
        endif;	
		#ini mengatur selct
		if($group == "transaction"):
			$this->db->select("
				customer1.CompanyID,
		        customer1.Name,
		        customer1.Position,

				PS_Invoice.Type,
				PS_Invoice.OrderType,
				PS_Invoice.Date,
				PS_Invoice.InvoiceNo,
		        PS_Invoice.Subtotal,
		        PS_Invoice.Discount,
		        PS_Invoice.PPN,
		        PS_Invoice.DeliveryCost,
		        PS_Invoice.Total,
		        PS_Invoice.Remark
			");
			
			#ini group by
			$this->db->group_by("
				customer1.Name,
				customer1.Position,
				customer1.CompanyID,

				PS_Invoice.OrderType,
				PS_Invoice.Type,
				PS_Invoice.Date,
				PS_Invoice.InvoiceNo,
				PS_Invoice.Subtotal,
		        PS_Invoice.Discount,
		        PS_Invoice.PPN,
		        PS_Invoice.DeliveryCost,
		        PS_Invoice.Total,
		        PS_Invoice.Remark
			");
			#ini order
			$this->db->order_by("PS_Invoice.InvoiceNo","ASC");
		elseif($group == "all"):
			$this->db->select("
				customer1.CompanyID,
				PS_Invoice.Type,
				PS_Invoice.OrderType,
				PS_Invoice.Date,
				PS_Invoice.InvoiceNo,
				ifnull(retur.ReturNo,'')   		    		  as ReturNo,
           		ifnull(receive.ReceiveNo,retur.ReceiveNo)     as ReceiveNo,
				(case 
                	when pid.ReceiveNo is not null then receive.ReceiveNo
                	else retur.ReturNo
          	 	end) 										  as transactionCode,
		        (case
		            when pid.ReceiveNo is not null then receive.Date
                	else retur.Date
		        end)										  as transactionDate,
		        customer1.Name,
		        customer1.Position,
		        pid.Subtotal,
		        pid.Discount,
		        pid.PPN,
		        pid.DeliveryCost,
		        pid.Total,
		        pid.Remark
			");
			
			#ini group by
			$this->db->group_by("
				
				retur.ReturNo,
				retur.Date,
				retur.ReceiveNo,

				customer1.CompanyID,
				customer1.Name,
				customer1.Position,

				receive.ReceiveNo,
				receive.Date,
				PS_Invoice.OrderType,
				PS_Invoice.Type,
				PS_Invoice.Date,
				PS_Invoice.InvoiceNo,
				pid.ReceiveNo,
				pid.DeliveryNo,
				pid.ReturNo,
				pid.Subtotal,
		        pid.Discount,
		        pid.PPN,
		        pid.DeliveryCost,
		        pid.Total,
		        pid.Remark
			");
			$this->db->order_by("PS_Invoice.InvoiceNo","ASC");
		endif;
		
		$this->db->join("PS_Vendor as customer1","PS_Invoice.VendorID = customer1.VendorID","left");
		$this->db->join("PS_Invoice_Detail as pid","PS_Invoice.InvoiceNo = pid.InvoiceNo and PS_Invoice.CompanyID = pid.CompanyID","left");
		$this->db->join("AP_GoodReceipt as receive","pid.ReceiveNo = receive.ReceiveNo and pid.CompanyID = receive.CompanyID","left");
		$this->db->join("AP_Retur as retur","pid.ReturNo = retur.ReturNo and pid.CompanyID = retur.CompanyID","left");
		#ini filter where
		$this->db->where("customer1.Position",1);
		$this->db->where("PS_Invoice.Type", 1);
		$this->db->where("PS_Invoice.Status", 1);
		$this->db->where("customer1.CompanyID",$this->session->CompanyID);
		if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE(PS_Invoice.Date) >=",$start_date);
			$this->db->where("DATE(PS_Invoice.Date) <=",$end_date);
		endif;
		if($this->input->post("vendor") != "all"):
            $vendor = $this->input->post("vendor");
            $this->db->where("customer1.VendorID", $vendor);
        endif;
		if($search):
		$i = 0;
        $column = $this->column;
        $Search = $this->input->post("Search");
        foreach ($column as $item) // loop column 
        {
            if($Search){
                
                if($i===0){
                    $this->db->group_start();
                    $this->db->like($item, $Search);
                }
                else{
                    $this->db->or_like($item, $Search);
                }
                if(count($column) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $column[$i] = $item; // set column array variable to order processing
            $i++;
        }
		$this->db->group_end();
		endif;
	}

	private function select_correction_ap()
	{
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$group 		= $this->input->post("group");
		$search 	= $this->input->post("search");
		if($this->input->get("group")):
            $group 	= $this->input->get("group");
        	$search = $this->input->get("search");
        endif;	
		#ini mengatur selct
		if($group == "all"):
			$this->db->select("
				abd.BalanceID,
				abd.BranchID 			as branchid,
				abd.VendorID 			as vendorid,
				abd.TotalReal 			as total,
				abd.TotalCorrection 	as totalcorrection,
				abd.Type,
				abd.Payment 			as totalpayment,
				abd.Remark,
				b.Name 					as branchname,
				vendor.Name 			as vendorName,
				AC_BalancePayable.Date,
				AC_BalancePayable.Code,
			");
			
			#ini group by
			$this->db->group_by("
				abd.BalanceID,
				abd.BranchID,
				abd.VendorID,
				abd.TotalReal,
				abd.TotalCorrection,
				abd.Type,
				abd.Payment,
				abd.Remark,
				b.Name,
				vendor.Name,
				vendor.Position,
				AC_BalancePayable.Date,
				AC_BalancePayable.Code,
			");
			#ini order
			$this->db->order_by("AC_BalancePayable.BalanceID","ASC");
		elseif($group == "transaction"):
			$this->db->select("
				
				AC_BalancePayable.Code,
				AC_BalancePayable.Date,
				AC_BalancePayable.TotalCorrection,
				abd.BalanceID,
				(select sum(Payment) from AC_BalancePayable_Det where BalanceID = AC_BalancePayable.BalanceID) as totalpayment,
				(select sum(TotalCorrection - Payment) from AC_BalancePayable_Det where BalanceID = AC_BalancePayable.BalanceID) as total,
				vendor.Name 		as vendorName,
			");
			#ini group by
			$this->db->group_by("
				
				AC_BalancePayable.Code,
				AC_BalancePayable.Date,
				AC_BalancePayable.TotalCorrection,
				abd.BalanceID,
				vendor.Name,
				
			");
			$this->db->order_by("","ASC");
		elseif($group == "vendor"):
			$this->db->select("				
				(select sum(VendorID) from AC_BalancePayable_Det where BalanceID = vendor.VendorID),
				abd.CompanyID,
				sum(AC_BalancePayable.TotalCorrection) as TotalCorrection,
				vendor.Name 		as vendorName,
			");
			#ini group by
			$this->db->group_by("
				abd.VendorID,
				abd.CompanyID,
				vendor.Name,
			");
		$this->db->order_by("","ASC");
		endif;
		$this->db->join("AC_BalancePayable_Det as abd","AC_BalancePayable.BalanceID = abd.BalanceID and AC_BalancePayable.CompanyID = abd.CompanyID","left");
		$this->db->join("Branch as b","abd.BranchID = b.BranchID and abd.CompanyID = b.CompanyID","left");
        $this->db->join("PS_Vendor as vendor","abd.VendorID = vendor.VendorID and abd.CompanyID = vendor.CompanyID","left");
		#ini filter where
		$this->db->where("abd.CompanyID",$this->session->CompanyID);
		$this->db->where("vendor.Position ",1);
		$this->db->where("AC_BalancePayable.Type",1);
		// $this->db->where("AC_BalancePayable.OrderType",3);
		if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE(AC_BalancePayable.Date) >=",$start_date);
			$this->db->where("DATE(AC_BalancePayable.Date) <=",$end_date);
		endif;
		if($this->input->post("vendor") != "all"):
            $vendor = $this->input->post("vendor");
            $this->db->where("abd.VendorID", $vendor);
        endif;
		if($search):
		$i = 0;
        $column = $this->column;
        $Search = $this->input->post("Search");
        foreach ($column as $item) // loop column 
        {
            if($Search){
                
                if($i===0){
                    $this->db->group_start();
                    $this->db->like($item, $Search);
                }
                else{
                    $this->db->or_like($item, $Search);
                }
                if(count($column) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $column[$i] = $item; // set column array variable to order processing
            $i++;
        }
		$this->db->group_end();
		endif;
	}

	private function select_correction_ar()
	{
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$group 		= $this->input->post("group");
		$search 	= $this->input->post("search");
		if($this->input->get("group")):
            $group 	= $this->input->get("group");
        	$search = $this->input->get("search");
        endif;	
		#ini mengatur selct
		if($group == "all"):
			$this->db->select("
				abd.BalanceID,
				abd.BranchID 			as branchid,
				abd.VendorID 			as vendorid,
				abd.TotalReal 			as total,
				abd.TotalCorrection 	as totalcorrection,
				abd.Type,
				abd.Payment 			as totalpayment,
				abd.Remark,
				b.Name 					as branchname,
				vendor.Name 			as vendorName,
				AC_BalancePayable.Date,
				AC_BalancePayable.Code,
			");
			
			#ini group by
			$this->db->group_by("
				abd.BalanceID,
				abd.BranchID,
				abd.VendorID,
				abd.TotalReal,
				abd.TotalCorrection,
				abd.Type,
				abd.Payment,
				abd.Remark,
				b.Name,
				vendor.Name,
				vendor.Position,
				AC_BalancePayable.Date,
				AC_BalancePayable.Code,
			");
			#ini order
			$this->db->order_by("AC_BalancePayable.BalanceID","ASC");
		elseif($group == "transaction"):
			$this->db->select("
				
				AC_BalancePayable.Code,
				AC_BalancePayable.Date,
				AC_BalancePayable.TotalCorrection,
				abd.BalanceID,
				(select sum(Payment) from AC_BalancePayable_Det where BalanceID = AC_BalancePayable.BalanceID) as totalpayment,
				(select sum(TotalCorrection - Payment) from AC_BalancePayable_Det where BalanceID = AC_BalancePayable.BalanceID) as total,
				vendor.Name 		as vendorName,
			");
			#ini group by
			$this->db->group_by("
				
				AC_BalancePayable.Code,
				AC_BalancePayable.Date,
				AC_BalancePayable.TotalCorrection,
				abd.BalanceID,
				vendor.Name,
				
			");
			$this->db->order_by("","ASC");
		elseif($group == "vendor"):
			$this->db->select("				
				(select sum(VendorID) from AC_BalancePayable_Det where BalanceID = vendor.VendorID),
				abd.CompanyID,
				sum(AC_BalancePayable.TotalCorrection) as TotalCorrection,
				vendor.Name 		as vendorName,
			");
			#ini group by
			$this->db->group_by("
				abd.VendorID,
				abd.CompanyID,
				vendor.Name,
			");
		$this->db->order_by("","ASC");
		endif;
		$this->db->join("AC_BalancePayable_Det as abd","AC_BalancePayable.BalanceID = abd.BalanceID and AC_BalancePayable.CompanyID = abd.CompanyID","left");
		$this->db->join("Branch as b","abd.BranchID = b.BranchID and abd.CompanyID = b.CompanyID","left");
        $this->db->join("PS_Vendor as vendor","abd.VendorID = vendor.VendorID and abd.CompanyID = vendor.CompanyID","left");
		#ini filter where
		$this->db->where("abd.CompanyID",$this->session->CompanyID);
		$this->db->where("vendor.Position",2);
		$this->db->where("AC_BalancePayable.Type",2);
		$this->db->where("AC_BalancePayable.OrderType",2);
		if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE(AC_BalancePayable.Date) >=",$start_date);
			$this->db->where("DATE(AC_BalancePayable.Date) <=",$end_date);
		endif;
		if($this->input->post("vendor") != "all"):
            $vendor = $this->input->post("vendor");
            $this->db->where("abd.VendorID", $vendor);
        endif;
		if($search):
		$i = 0;
        $column = $this->column;
        $Search = $this->input->post("Search");
        foreach ($column as $item) // loop column 
        {
            if($Search){
                
                if($i===0){
                    $this->db->group_start();
                    $this->db->like($item, $Search);
                }
                else{
                    $this->db->or_like($item, $Search);
                }
                if(count($column) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $column[$i] = $item; // set column array variable to order processing
            $i++;
        }
		$this->db->group_end();
		endif;
	}

	private function select_sales_book()
	{
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$search 	= $this->input->post("search");
		#ini mengatur selct
			$this->db->select("
			
				PS_Sell.DeliveryCost 											as deliverycost, 
				PS_Sell.Discount 												as diskon,
				PS_Sell.SellNo													as transactionCode,
				PS_Sell.DeliveryStatus,
				PS_Sell.PPN														as tax,
				customer.Name 													as customerName,
				PS_Sell.Date 													as date,
				PS_Sell.Payment													as total,
				PS_Sell.CostPaid												as grandtotal,
				PS_Sell.Total 													as subtotal
			");
			#ini group by
			$this->db->group_by("
			
				PS_Sell.DeliveryCost, 
				PS_Sell.Discount,
				PS_Sell.SellNo,
				PS_Sell.DeliveryStatus,
				PS_Sell.PPN,
				customer.Name,
				PS_Sell.Date, 
				PS_Sell.Payment,
				PS_Sell.CostPaid,
				PS_Sell.Total		
			");
	
		$this->db->join("PS_Vendor as customer","PS_Sell.VendorID = customer.VendorID","left");
		$this->db->join("PS_Sell_Detail as psd","PS_Sell.SellNo = psd.SellNo and PS_Sell.CompanyID = psd.CompanyID","left");
		// $this->db->join("PS_Delivery_Det","PS_Delivery.DeliveryNo = PS_Delivery_Det.DeliveryNo","left");
		// $this->db->join("PS_Delivery","PS_Sell.SellNo = PS_Delivery.SellNo","left");
		// $this->db->join("PS_Invoice","PS_Payment_Detail.InvoiceNo = PS_Invoice.InvoiceNo","left");
		#ini filter where
		// $this->db->where("PS_Sell.CompanyID",$this->session->CompanyID);
		// $this->db->where("PS_Sell.BranchID", null);
		$this->db->where("PS_Sell.Status",1,0);
		$this->db->where("PS_Sell.CompanyID",$this->session->CompanyID);
		if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE(PS_Sell.Date) >=",$start_date);
			$this->db->where("DATE(PS_Sell.Date) <=",$end_date);
		endif;
		if($this->input->post("customer") != "all"):
            $customer = $this->input->post("customer");
            $this->db->where("customer.VendorID", $customer);
        endif;
        if($this->input->post("sellno") != "all"):
            $sellno = $this->input->post("sellno");
            $this->db->where("PS_Sell.SellNo", $sellno);
        endif;
	}

	private function select_purchase_book()
	{
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$search 	= $this->input->post("search");
		#ini mengatur selct
			$this->db->select("
				PS_Purchase.PurchaseNo			as transactionCode,
			
				PS_Purchase.DeliveryCost 		as deliverycost, 
				PS_Purchase.Discount 			as diskon,
				PS_Purchase.DeliveryStatus,
				PS_Purchase.PPN					as tax,
				customer.Name 					as customerName,
				PS_Purchase.Date 				as date,
				PS_Purchase.Payment				as total,
				apg.CostPaid 					as grandtotal,
				PS_Purchase.Total 				as subtotal
			");
			#ini group by
			$this->db->group_by("
				PS_Purchase.PurchaseNo,
			
				PS_Purchase.DeliveryCost, 
				PS_Purchase.Discount,
				PS_Purchase.DeliveryStatus,
				PS_Purchase.PPN,
				customer.Name,
				PS_Purchase.Date, 
				PS_Purchase.Payment,
				apg.CostPaid,
				PS_Purchase.Total		
			");

		$this->db->join("PS_Vendor as customer","PS_Purchase.VendorID = customer.VendorID","left");
		$this->db->join("PS_Purchase_Detail as ppd","PS_Purchase.PurchaseNo = ppd.PurchaseNo and PS_Purchase.CompanyID = ppd.CompanyID","left");
		$this->db->join("AP_GoodReceipt as apg","PS_Purchase.PurchaseNo = apg.PurchaseNo and PS_Purchase.CompanyID = apg.CompanyID","left");
		// $this->db->join("PS_Delivery_Det","PS_Delivery.DeliveryNo = PS_Delivery_Det.DeliveryNo","left");
		// $this->db->join("PS_Delivery","PS_Sell.SellNo = PS_Delivery.SellNo","left");
		// $this->db->join("PS_Invoice","PS_Payment_Detail.InvoiceNo = PS_Invoice.InvoiceNo","left");
		#ini filter where
		// $this->db->where("PS_Sell.CompanyID",$this->session->CompanyID);
		// $this->db->where("PS_Purchase.BranchID", null);
		$this->db->where("PS_Purchase.Status",1,0);
		$this->db->where("PS_Purchase.CompanyID",$this->session->CompanyID);
		if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE(PS_Purchase.Date) >=",$start_date);
			$this->db->where("DATE(PS_Purchase.Date) <=",$end_date);
		endif;
		if($this->input->post("vendor") != "all"):
            $vendor = $this->input->post("vendor");
            $this->db->where("customer.VendorID", $vendor);
        endif;
         if($this->input->post("purchaseno") != "all"):
            $purchaseno = $this->input->post("purchaseno");
            $this->db->where("PS_Purchase.PurchaseNo", $purchaseno);
        endif;
	}

	private function select_voucher()
	{
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$search 	= $this->input->post("search");
		#ini mengatur selct
			$this->db->select("
				Voucher.VoucherID 	as VoucherID,
				Voucher.Code 		as Code,
				Voucher.Date 		as Date,
				Voucher.ExpireDate 	as ExpireDate,
				Voucher.Status 		as Status,
				Voucher.UseDate 	as UseDate,
				Voucher.Active 		as Active,
				Voucher.Qty 		as Qty,
				Voucher.Bank 				as Bank,
				(case
	                when Voucher.Module = 'android' then Voucher.Price
	                else parent.Price
	            end)                    	as Price,
				(case
	                when Voucher.Module = 'module' then Voucher.Price
	                else parent.Price
	            end)                    	as PriceModule,
				(Voucher.TotalPrice + ifnull(parent.TotalPrice,0))	as TotalPrice,
				Voucher.Module,
				Voucher.StatusTransfer 		as StatusTransfer,
				Voucher.Remark 				as Remark,
				user.nama,
				(case 
				when Voucher.App ='pipesys' then 'Pipesys'
				else 'Pipesys & People Shape Sales' end) as App,
				(case 
				when Voucher.Type = 24 THEN '2 Year'
				when Voucher.Type = 12 THEN '1 Year'
				when Voucher.Type = 6 THEN '6 Month'
				when Voucher.Type = 3 THEN '3 Month'
				when Voucher.Type = 1 THEN '1 Month' else 'none' end) 	as Type,

				ifnull(parent.Qty,0) as parentQty
			");
			$this->db->order_by("Voucher.Code","desc");
			#ini group by
			$this->db->group_by("
					
			");
		$this->db->join("user", "Voucher.CompanyID = user.id_user", "left");
		$this->db->join("Voucher as parent", "parent.ParentID = Voucher.VoucherID","left");		
		$this->db->where("Voucher.Code != ", null);
		
		if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE(Voucher.Date) >=",$start_date);
			$this->db->where("DATE(Voucher.Date) <=",$end_date);
		endif;
		if($search):
			$this->db->group_start();
			$this->db->like("Voucher.Code",$search);
			$this->db->or_like("Voucher.Date",$search);
			$this->db->or_like("Voucher.Module",$search);
			$this->db->group_end();
		endif;
	}

	public function select_age_off_debt()
	{
		$CompanyID = $this->session->CompanyID;
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$group 		= $this->input->post("group");
		$VendorID 	= $this->input->post('customer');
		
		$table = "PS_Invoice";
		$this->db->select("
			$table.InvoiceNo 	as transactionCode,
			$table.Date 		as transactionDate,
			$table.VendorID 	as VendorID,
			vendor.Name 		as vendorName,
			ifnull($table.Term,0) as Term,
			($table.Total - ifnull(
                (select sum(pd.Total) from PS_Payment_Detail as pd 
                left join PS_Payment as p on pd.PaymentNo = p.PaymentNo and pd.CompanyID = p.CompanyID
                where pd.InvoiceNo = $table.InvoiceNo and p.Status = '1' and p.CompanyID = $table.CompanyID
                )
            , 0)) as Unpaid,
		");
		$this->db->join("PS_Vendor as vendor", "vendor.VendorID = $table.VendorID and vendor.CompanyID = $table.CompanyID");
		$this->db->where("$table.Status", 1);
		$this->db->where("$table.CompanyID", $CompanyID);
		$this->db->where("$table.Type", 2);
		$this->db->where("$table.PaymentStatus", 0);
		if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE($table.Date) >=",$start_date);
			$this->db->where("DATE($table.Date) <=",$end_date);
		endif;
		if($VendorID != "all"):
			$this->db->where("$table.VendorID", $VendorID);
		endif;
		$this->db->from($table);
		$invoice = $this->db->get_compiled_select();
		// $query = $this->db->query($invoice);

		$table2 = "AC_BalancePayable_Det";
		$this->db->select("
			balance.Code 		as transactionCode,
			balance.Date 		as transactionDate,
			$table2.VendorID 	as VendorID,
			vendor.Name 		as vendorName,
			ifnull(vendor.AP_Max,0) as Term,
			case
				when balance.BalanceType = 1 then ($table2.TotalCorrection - ifnull($table2.Payment, 0) )
				else ($table2.TotalCorrection - ifnull($table2.Payment, 0) ) * -1
			end as Unpaid,
		");
		$this->db->join("AC_BalancePayable balance", "balance.BalanceID = $table2.BalanceID and balance.CompanyID = $table2.CompanyID");
		$this->db->join("PS_Vendor as vendor", "vendor.VendorID = $table2.VendorID and vendor.CompanyID = $table2.CompanyID");
		$this->db->where("balance.Active",1);
		$this->db->where("$table2.CompanyID", $CompanyID);
		$this->db->where("balance.Type", 2);
		$this->db->where("balance.OrderType", 2);
		$this->db->where("$table2.PaymentStatus",0);
		if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE(balance.Date) >=",$start_date);
			$this->db->where("DATE(balance.Date) <=",$end_date);
		endif;
		if($VendorID != "all"):
			$this->db->where("$table2.VendorID", $VendorID);
		endif;
		$this->db->from($table2);
		$balance = $this->db->get_compiled_select();
		// $query = $this->db->query($balance);
		if($group == "all"):
			$query = $this->db->query('SELECT Z.VendorID,Z.vendorName,sum(Z.Unpaid) as Unpaid FROM ('.$invoice . ' UNION ' . $balance.') AS Z group by Z.VendorID,Z.vendorName');
		else:
			$query = $this->db->query('SELECT * FROM ('.$invoice . ' UNION ' . $balance.') AS Z');
		endif;

		return $query->result();
	}

	public function select_age_off_credit(){
		$CompanyID = $this->session->CompanyID;
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$group 		= $this->input->post("group");
		$VendorID 	= $this->input->post('customer');

		$table = "PS_Invoice";
		$this->db->select("
			$table.InvoiceNo 	as transactionCode,
			$table.Date 		as transactionDate,
			$table.VendorID 	as VendorID,
			vendor.Name 		as vendorName,
			ifnull($table.Term,0) as Term,
			($table.Total - ifnull(
                (select sum(pd.Total) from PS_Payment_Detail as pd 
                left join PS_Payment as p on pd.PaymentNo = p.PaymentNo and pd.CompanyID = p.CompanyID
                where pd.InvoiceNo = $table.InvoiceNo and p.Status = '1' and p.CompanyID = $table.CompanyID
                )
            , 0)) as Unpaid,
		");
		$this->db->join("PS_Vendor as vendor", "vendor.VendorID = $table.VendorID and vendor.CompanyID = $table.CompanyID");
		$this->db->where("$table.Status", 1);
		$this->db->where("$table.CompanyID", $CompanyID);
		$this->db->where("$table.Type", 1);
		$this->db->where("$table.PaymentStatus", 0);
		if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE($table.Date) >=",$start_date);
			$this->db->where("DATE($table.Date) <=",$end_date);
		endif;
		if($VendorID != "all"):
			$this->db->where("$table.VendorID", $VendorID);
		endif;
		$this->db->from($table);
		$invoice = $this->db->get_compiled_select();
		// $query = $this->db->query($invoice);

		$table2 = "AC_BalancePayable_Det";
		$this->db->select("
			balance.Code 		as transactionCode,
			balance.Date 		as transactionDate,
			$table2.VendorID 	as VendorID,
			vendor.Name 		as vendorName,
			ifnull(vendor.AP_Max,0) as Term,
			case
				when balance.BalanceType = 1 then ($table2.TotalCorrection - ifnull($table2.Payment, 0) )
				else ($table2.TotalCorrection - ifnull($table2.Payment, 0) ) * -1
			end as Unpaid,
		");
		$this->db->join("AC_BalancePayable balance", "balance.BalanceID = $table2.BalanceID and balance.CompanyID = $table2.CompanyID");
		$this->db->join("PS_Vendor as vendor", "vendor.VendorID = $table2.VendorID and vendor.CompanyID = $table2.CompanyID");
		$this->db->where("balance.Active",1);
		$this->db->where("$table2.CompanyID", $CompanyID);
		$this->db->where("balance.Type", 1);
		$this->db->where("balance.OrderType", 3);
		$this->db->where("$table2.PaymentStatus",0);
		if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE(balance.Date) >=",$start_date);
			$this->db->where("DATE(balance.Date) <=",$end_date);
		endif;
		if($VendorID != "all"):
			$this->db->where("$table2.VendorID", $VendorID);
		endif;
		$this->db->from($table2);
		$balance = $this->db->get_compiled_select();
		// $query = $this->db->query($balance);

		if($group == "all"):
			$query = $this->db->query('SELECT Z.VendorID,Z.vendorName,sum(Z.Unpaid) as Unpaid FROM ('.$invoice . ' UNION ' . $balance.') AS Z group by Z.VendorID,Z.vendorName');
		else:
			$query = $this->db->query('SELECT * FROM ('.$invoice . ' UNION ' . $balance.') AS Z');
		endif;

		return $query->result();
	}

	public function select_debtors_account()
	{
		$CompanyID 	= $this->session->CompanyID;
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$VendorID 	= '';
		$Pilihan 	= 1; // kartu piutang

		if(!empty($start_date) && !empty($end_date)):
			
		endif;
		if($this->input->post("customer") != "all"):
            $VendorID = $this->input->post('customer');    
        endif;

		$query = $this->db->query("call  sp_piutang('$start_date','$end_date','$CompanyID','$VendorID', '$Pilihan')");
		return $query->result();
	}

	public function select_creditors_account()
	{
		$CompanyID 	= $this->session->CompanyID;
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$VendorID 	= '';
		$Pilihan 	= 1; // kartu piutang

		
		#ini order
		$this->db->join("PS_Vendor","sp_hutang.VendorID = PS_Vendor.VendorID","left");

		if($this->input->post("vendor") != "all"):
  			$VendorID = $this->input->post("vendor");
		endif;

		if(!empty($start_date) && !empty($end_date)):
			
		endif;
		

		$query = $this->db->query("call  sp_hutang('$start_date','$end_date','$CompanyID','$VendorID', '$Pilihan')");
		return $query->result();
	}

	public function select_saldo_receivable()
	{
		$CompanyID 	= $this->session->CompanyID;
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$VendorID 	= 0;
		$Pilihan 	= 2; // kartu piutang
		$group 		= $this->input->post("group");
		$search 	= $this->input->post("search");
		if($this->input->get("group")):
            $group 	= $this->input->get("group");
        	$search = $this->input->get("search");
        endif;	

        if($group == "transaction"):
        	$VendorID 	= '';
			$Pilihan 	= 3; // saldo ar transaction
		elseif($group == "all"):
			$VendorID 	= '';
			$Pilihan 	= 2; // saldo ar all
		endif;

		if(!empty($start_date) && !empty($end_date)):
			
		endif;
		if($this->input->post("customer") != "all"):
            $VendorID = $this->input->post('customer');    
        endif;
		$query = $this->db->query("call  sp_piutang('$start_date','$end_date','$CompanyID','$VendorID', '$Pilihan')");
		return $query->result();
	}

	public function select_saldo_ap()
	{
		$CompanyID 	= $this->session->CompanyID;
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$VendorID 	= 0;
		$Pilihan 	= 2; // kartu piutang
		$group 		= $this->input->post("group");
		$search 	= $this->input->post("search");
		if($this->input->get("group")):
            $group 	= $this->input->get("group");
        	$search = $this->input->get("search");
        endif;	

        if($group == "transaction"):
        	$VendorID   = '';
			$Pilihan 	= 3; // saldo ap transaction
		elseif($group == "all"):
			$VendorID 	= '';
			$Pilihan 	= 2; // saldo ap all
		endif;

		if(!empty($start_date) && !empty($end_date)):
			
		endif;
		if($this->input->post("vendor") != "all"):
            $VendorID = $this->input->post('vendor');    
        endif;

		$query = $this->db->query("call  sp_hutang('$start_date','$end_date','$CompanyID','$VendorID', '$Pilihan')");

		return $query->result();
	}

	private function select_account_receive()
	{
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$group 		= $this->input->post("group");
		$search 	= $this->input->post("search");
		if($this->input->get("group")):
            $group 	= $this->input->get("group");
        	$search = $this->input->get("search");
        endif;
		#ini mengatur selct
		if($group == "date"):
			$this->db->select("
				AC_CorrectionPR_Det.Date  		as date,
				SUM(AC_CorrectionPR_Det.Total) 	as total
			");
			#ini group by
			$this->db->group_by("AC_CorrectionPR_Det.Date");
			#ini order
			$this->db->order_by("AC_CorrectionPR_Det.Date","ASC");
			$this->db->order_by("AC_CorrectionPR_Det.Date","ASC");
		elseif($group == "store_name"):
			$this->db->select("
				Branch.Name 					as store_name,
				SUM(AC_CorrectionPR_Det.Total)	as total
			");
			#ini group by_
			$this->db->group_by("Branch.Name");
			#ini order
			$this->db->order_by("Branch.Name","ASC");
		elseif($group == "ar_code"):
			$this->db->select("
				AC_CorrectionPR_Det.BalanceNo 	as arcode,
				SUM(AC_CorrectionPR_Det.Total)	as total
			");
			#ini group by
			$this->db->group_by("AC_CorrectionPR_Det.BalanceNo");
			#ini order
			$this->db->order_by("AC_CorrectionPR_Det.BalanceNo","ASC");
		else:
			$this->db->select("
				AC_CorrectionPR_Det.Date 		as date,
				Branch.Name 					as store_name,
				AC_CorrectionPR_Det.BalanceNo 	as arcode,
				AC_CorrectionPR_Det.Total		as total
			");
			$this->db->order_by("AC_CorrectionPR_Det.BalanceNo","ASC");
		endif;
		$this->db->join("Branch","AC_CorrectionPR_Det.BranchID = Branch.BranchID","left");
		#ini filter where
		$this->db->where("AC_CorrectionPR_Det.CompanyID",$this->session->CompanyID);
		if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE(AC_CorrectionPR_Det.Date) >=",$start_date);
			$this->db->where("DATE(AC_CorrectionPR_Det.Date) <=",$end_date);
		endif;
		if($search):
		$this->db->group_start();
			if($group == "all"):
				$this->db->like("AC_CorrectionPR_Det.BalanceNo",$search);
				$this->db->or_like("DATE(AC_CorrectionPR_Det.Date)",$search);
				$this->db->or_like("Branch.Name",$search);
			elseif($group == "date"):
				$this->db->like("DATE(AC_CorrectionPR_Det.Date)",$search);
			elseif($group == "store_name"):
				$this->db->like("Branch.Name",$search);
			elseif($group == "ar_code"):
				$this->db->like("AC_CorrectionPR_Det.BalanceNo",$search);
			endif;
		$this->db->group_end();
		endif;
	}
	#2018-02-03
	private function select_serial_number()
	{
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$group 		= $this->input->post("group");
		$search 	= $this->input->post("search");
		if($this->input->get("group")):
            $group 	= $this->input->get("group");
        	$search = $this->input->get("search");
        endif;
		#ini mengatur selct
		if($group == "all" || $group == "good_receipt"):
			$this->db->select("
				PS_Product_Serial.Date 		as date,
				ps_product.Code 			as product_code,
				ps_product.Name 			as product_name,
				(CASE 
				WHEN ps_product.Type = 1 THEN 'unique'
				WHEN ps_product.Type = 2 THEN 'serial'
				ELSE 'general' END) 			as type_serial,
				PS_Product_Serial.SerialNo 		as serialnumber,
				SUM(PS_Product_Serial.Qty)		as qty,
			");
			$this->db->group_by("PS_Product_Serial.SerialNo,PS_Product_Serial.Date,ps_product.Code,ps_product.Name,ps_product.Type");
			$this->db->order_by("PS_Product_Serial.Date","ASC");
		endif;
		$this->db->join("ps_product","PS_Product_Serial.ProductID = ps_product.ProductID","left");
		$this->db->join("AP_GoodReceipt_Det","PS_Product_Serial.ReceiveDet = AP_GoodReceipt_Det.ReceiveDet and PS_Product_Serial.CompanyID = AP_GoodReceipt_Det.CompanyID");
		// $this->db->join("AP_GoodReceipt_Det","PS_Product_Serial.ReceiveDet = AP_GoodReceipt_Det.ReceiveDet");
		#ini filter where
		$this->db->where("PS_Product_Serial.CompanyID",$this->session->CompanyID);
		if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE(PS_Product_Serial.Date) >=",$start_date);
			$this->db->where("DATE(PS_Product_Serial.Date) <=",$end_date);
		endif;
		if($search):
			$this->db->group_start();
			$this->db->like("ps_product.Name",$search);
			$this->db->or_like("ps_product.Code",$search);
			$this->db->or_like("PS_Product_Serial.Date",$search);
			$this->db->or_like("PS_Product_Serial.SerialNo",$search);
			$this->db->group_end();
		endif;
	}
	public function serial_number_report()
	{
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$group 		= $this->input->post("group");
		$search 	= $this->input->post("search");
		$CompanyID 	= $this->session->CompanyID;
		if($this->input->get("group")):
            $group 	= $this->input->get("group");
        	$search = $this->input->get("search");
        endif;
        $inventory_serial = array("correction","stock_opname", "stock_receipt","stock_issue");
        if($group == "mutation"):
        	$table = "PS_Mutation_Detail";
        	$tablex = "PS_Mutation";
	        	$this->db->select("
	        	PS_Mutation.Date 		as date,
	        	PS_Mutation.BranchIDTo 	as BranchID,
	        	ps_product.Code 		as product_code,
	        	ps_product.Name 		as product_name,
	        	(CASE 
					WHEN ps_product.Type = 1 THEN 'Unique'
					WHEN ps_product.Type = 2 THEN 'Serial'
					ELSE 'general' END) 			as type_serial,
				serial.Qty 				as qty,
	        	serial.SN 				as serialnumber,
	        	branch_from.Name 		as branchName_from,
	        	branch_to.Name 			as branchName_to,
	        ");
	        $this->db->join("PS_Mutation","$table.MutationNo = PS_Mutation.MutationNo and PS_Mutation.CompanyID = $table.CompanyID","left");
	        $this->db->join("Branch as branch_from", "branch_from.BranchID = PS_Mutation.BranchID and branch_from.CompanyID = PS_Mutation.CompanyID", "left");
	       	$this->db->join("Branch as branch_to", "branch_to.BranchID = PS_Mutation.BranchIDTo and branch_to.CompanyID = PS_Mutation.CompanyID", "left");
	        $this->db->join("PS_Mutation_Detail_SN as serial","$table.MutationNo = serial.MutationNo and serial.CompanyID = $table.CompanyID and $table.MutationDet = serial.MutationDet","left");
	        $this->db->join("ps_product","$table.ProductID = ps_product.ProductID","left");
	        $this->db->where("ps_product.Type", 2);
	        $this->db->where("PS_Mutation.CompanyID", $CompanyID);
	       	$this->db->order_by("PS_Mutation.Date","ASC");
	    elseif($group == "return" || $group == "return_ar"):
	    	$table = "AP_Retur_Det";
	    	$tablex	= "AP_Retur";

	       	$this->db->select("
	        	AP_Retur.Date 		as  date,
	        	ps_product.Code 	as product_code,
	        	ps_product.Name 	as product_name,
	        	(CASE 
					WHEN ps_product.Type = 1 THEN 'Unique'
					WHEN ps_product.Type = 2 THEN 'Serial'
					ELSE 'general' END) as type_serial,
	        	serial.SN 				as serialnumber,
	        	Branch.Name 			as branchName,
	        ");
	        $this->db->join("AP_Retur","$table.ReturNo = AP_Retur.ReturNo and $table.CompanyID = AP_Retur.CompanyID","left");
	        $this->db->join("ps_product","$table.ProductID = ps_product.ProductID","left");
	        $this->db->join("AP_Retur_Det_SN as serial","$table.ReturNo = serial.ReturNo and serial.CompanyID = $table.CompanyID and $table.ReturDet = serial.ReturDet","left");
	       	$this->db->where("AP_Retur.CompanyID",$CompanyID);
	       	$this->db->where("AP_Retur.Status",1);
	       	$this->db->where("ps_product.Type", 2);
	       	if($group == "return"):
	       		$this->db->join("Branch", "Branch.BranchID = AP_Retur.BranchID and Branch.CompanyID = AP_Retur.CompanyID", "left");
        		$this->db->where_in("AP_Retur.Type", array(1));
	       	elseif($group == "return_ar"):
	       		$this->db->join("Branch", "Branch.BranchID = $table.BranchID and Branch.CompanyID = $table.CompanyID", "left");
        		$this->db->where_in("AP_Retur.Type", array(2));
	       	endif;
        	$this->db->order_by("AP_Retur.Date","ASC");
	    elseif($group == "sale"):
	    	$table 	= "PS_Sell_Detail";
	    	$tablex	= "PS_Sell";
	        $this->db->select("
	        	PS_Sell.Date 		as  date,
	        	ps_product.Code 	as product_code,
	        	ps_product.Name 	as product_name,
	        	(CASE 
					WHEN ps_product.Type = 1 THEN 'Unique'
					WHEN ps_product.Type = 2 THEN 'Serial'
					ELSE 'general' END) 			as type_serial,
	        	Branch.Name 			as branchName,
	        	serial.SN 				as serialnumber,
	        ");
	        $this->db->join("PS_Sell","$table.SellNo = PS_Sell.SellNo and $table.CompanyID = PS_Sell.CompanyID","left");
	        $this->db->join("Branch", "Branch.BranchID = PS_Sell.BranchID and Branch.CompanyID = PS_Sell.CompanyID", "left");
	        $this->db->join("ps_product","$table.ProductID = ps_product.ProductID","left");
	        $this->db->join("PS_Sell_Detail_SN as serial","$table.SellNo = serial.SellNo and serial.CompanyID = $table.CompanyID and $table.SellDet = serial.SellDet","left");
	        $this->db->where("PS_Sell.CompanyID", $CompanyID);
	        $this->db->where("PS_Sell.Status", 1);
	        $this->db->where("PS_Sell.Mobile", 0 );
	        $this->db->where("ps_product.Type", 2);
	       	$this->db->order_by("PS_Sell.Date","ASC");
	    elseif($group == "good_receipt"):
	    	$table 	= "AP_GoodReceipt_Det";
	    	$tablex	= "AP_GoodReceipt";
	    	$this->db->select("
	    		AP_GoodReceipt.Date 	as date,
	    		ps_product.Code 		as product_code,
	    		ps_product.Name 		as product_name,
	    		(CASE 
					WHEN ps_product.Type = 1 THEN 'Unique'
					WHEN ps_product.Type = 2 THEN 'Serial'
					ELSE 'general' END) as type_serial,
				Branch.Name 			as branchName,
	        	serial.SN 				as serialnumber,
    		");
    		$this->db->join("$tablex", "$table.ReceiveNo = $tablex.ReceiveNo and $table.CompanyID = $tablex.CompanyID", "left");
    		$this->db->join("Branch", "Branch.BranchID = $tablex.BranchID and Branch.CompanyID = $tablex.CompanyID", "left");
    		$this->db->join("ps_product","$table.ProductID = ps_product.ProductID","left");
    		$this->db->join("AP_GoodReceipt_Det_SN as serial","$table.ReceiveNo = serial.ReceiveNo and serial.CompanyID = $table.CompanyID and $table.ReceiveDet = serial.ReceiveDet","left");
    		$this->db->where("$tablex.CompanyID", $CompanyID);
	        $this->db->where("$tablex.Status", 1);
	        $this->db->where("ps_product.Type", 2);
	        $this->db->order_by("$tablex.Date","ASC");
	    elseif(in_array($group,$inventory_serial)):
	    	$table 	= "PS_Correction_Detail";
	    	$tablex	= "PS_Correction";
	    	$this->db->select("
	    		$tablex.Date 			as date,
	    		ps_product.Code 		as product_code,
	    		ps_product.Name 		as product_name,
	    		(CASE 
					WHEN ps_product.Type = 1 THEN 'Unique'
					WHEN ps_product.Type = 2 THEN 'Serial'
					ELSE 'general' END) as type_serial,
				Branch.Name 			as branchName,
	        	serial.SN 				as serialnumber,
    		");
    		$this->db->join("$tablex", "$table.CorrectionNo = $tablex.CorrectionNo and $table.CompanyID = $tablex.CompanyID", "left");
    		$this->db->join("Branch", "Branch.BranchID = $tablex.BranchID and Branch.CompanyID = $tablex.CompanyID", "left");
    		$this->db->join("ps_product","$table.ProductID = ps_product.ProductID","left");
    		$this->db->join("PS_Correction_Detail_SN as serial","$table.CorrectionNo = serial.CorrectionNo and serial.CompanyID = $table.CompanyID and $table.CorrectionDetID = serial.CorrectionDetID","left");
    		if($group == "correction"):
    			$this->db->where("$tablex.Type", 1);
    		elseif($group == "stock_opname"):
    			$this->db->where("$tablex.Type", 2);
    		elseif($group == "stock_receipt"):
    			$this->db->where("$tablex.Type", 3);
    		elseif($group == "stock_issue"):
    			$this->db->where("$tablex.Type", 4);
    		endif;
    		$this->db->where("$tablex.CompanyID", $CompanyID);
	        $this->db->where("$tablex.Status", 1);
	        $this->db->where("ps_product.Type", 2);
	        $this->db->order_by("$tablex.Date","ASC");
	    elseif($group == "all"):
	    	$table = "PS_Product_Serial";
	    	$this->db->select("
	    		ps_product.Code 		as product_code,
	    		ps_product.Name 		as product_name,
	    		(CASE 
					WHEN ps_product.Type = 1 THEN 'Unique'
					WHEN ps_product.Type = 2 THEN 'Serial'
					ELSE 'general' END) as type_serial,
				Branch.Name 			as branchName,
	        	$table.SerialNo 		as serialnumber,
    		");
    		$this->db->join("Branch", "Branch.BranchID = $table.BranchID and Branch.CompanyID = $table.CompanyID", "left");
    		$this->db->join("ps_product","$table.ProductID = ps_product.ProductID","left");
    		$this->db->where("ps_product.Type", 2);
    		$this->db->where("$table.CompanyID", $CompanyID);
    		$this->db->group_by("
    			$table.ProductID,
    			ps_product.Code,
    			ps_product.Name,
    			Branch.Name,
    			$table.SerialNo,
			");
        endif;
        if($group != "all"):
        	if(!empty($start_date) && !empty($end_date)):
				$this->db->where("DATE($tablex.Date) >=",$start_date);
				$this->db->where("DATE($tablex.Date) <=",$end_date);
			endif;
        endif;

        $branch = $this->input->post('branch');
        if($branch != "all" and $branch):
        	$this->db->where("Branch.BranchID", $branch);
        endif;

		if($search):
			$this->db->group_start();
			$this->db->like("DATE($tablex.Date)",$search);
			$this->db->or_like("$table.SerialNumber",$search);
			$this->db->or_like("ps_product.Code",$search);
			$this->db->or_like("ps_product.Name",$search);
			$this->db->group_end();
		endif;
        $query = $this->db->get($table);
        return $query->result();
	}

	public function stock_report1()
	{
		$CompanyID 	= $this->session->CompanyID;
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$BranchID 	= '';
		$ProductID 	= 0;
		$Pilih 		= 1; // transaksi
		$group 		= $this->input->post("group");
		$search 	= $this->input->post("search");
		if($this->input->get("group")):
            $group 	= $this->input->get("group");
        	$search = $this->input->get("search");
        endif;	

        if($group == "transaction"):
			$Pilih 	= 1; // transaksi
		elseif($group == "all"):
			$Pilih 	= 2; // rekap
		elseif($group == "store"):
			$Pilih  = 3; // rekap store
		endif;
		if($this->input->post("product") != "all" && $this->input->post("product")):
  			$ProductID = $this->input->post("product");
		endif;

		$branch = $this->input->post('branch');
		if($branch != 'all' && $branch):
			$BranchID = $branch;
		endif;

		$query = $this->db->query("call sp_detailstok('$start_date','$end_date','$CompanyID','$BranchID','$ProductID', '$Pilih')");
		return $query->result();
	}

	#2018-02-03
	public function stock_report(){
		$where = "";
		$CompanyID 	= $this->session->CompanyID;
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$search 	= $this->input->post("search");
		if($search):
			$where = "
			AND ps_product.Name LIKE '%$search%' 
			OR stock.Date LIKE '%$search%' 
			OR category.Name LIKE '%$search%' 
			OR category.Code LIKE '%$search%'
			OR ps_unit.Name LIKE '%$search%'
			";
		endif;
		$query = $this->db->query("SELECT 
			stock.date					as date,
			stock.productid 			as productid,
			category.Code 				as category_code,
			category.Name 				as category_name,
			ps_product.Name 			as product_name,
			ps_unit.Name 				as unit_name,
			ps_unit.unitid 				as unitid,
			stock.conversion 			as conversion,
			SUM(stock.qty_gr) 			as qty_in,
			SUM(stock.qty_mutation + stock.qty_return) 	as qty_out,
			SUM(stock.qty_gr - (stock.qty_mutation + stock.qty_return)) as qty,
			ps_product.MinimumStock as min_qty
			FROM 
			(
				(SELECT 
				AP_GoodReceipt_Det.ProductID 	as productid,
				AP_GoodReceipt.Date 				as date,
				AP_GoodReceipt_Det.Conversion as conversion,
				AP_GoodReceipt_Det.UnitID 		as unitid,		
				SUM(AP_GoodReceipt_Det.Qty) 	as qty,
				SUM(AP_GoodReceipt_Det.Qty) 	as qty_gr,
				0 										as qty_mutation,
				0										as qty_return
				FROM AP_GoodReceipt_Det
				LEFT JOIN AP_GoodReceipt ON AP_GoodReceipt_Det.ReceiveNo = AP_GoodReceipt.ReceiveNo and AP_GoodReceipt_Det.CompanyID = AP_GoodReceipt.CompanyID
				GROUP BY AP_GoodReceipt_Det.ProductID,AP_GoodReceipt.Date,AP_GoodReceipt_Det.Conversion,AP_GoodReceipt_Det.UnitID)
				
				UNION
				
				(SELECT 
				PS_Mutation_Detail.ProductID 	as productid,
				PS_Mutation.Date 					as date,
				PS_Mutation_Detail.Conversion as conversion,
				PS_Mutation_Detail.UnitID 		as unitid,		
				SUM(PS_Mutation_Detail.Qty) 	as qty,
				0 										as qty_gr,
				SUM(PS_Mutation_Detail.Qty) 	as qty_mutation,
				0										as qty_return
				
				FROM PS_Mutation_Detail
				LEFT JOIN PS_Mutation ON PS_Mutation_Detail.MutationNo = PS_Mutation.MutationNo and PS_Mutation_Detail.CompanyID = PS_Mutation.CompanyID
				GROUP BY PS_Mutation_Detail.ProductID,PS_Mutation.Date,PS_Mutation_Detail.Conversion,PS_Mutation_Detail.UnitID)
				
				UNION

				(SELECT 
				AP_Retur_Det.ProductID 		as productid,
				AP_Retur.Date 					as date,
				AP_Retur_Det.Conversion 	as conversion,
				AP_Retur_Det.UnitID 			as unitid,		
				SUM(AP_Retur_Det.Qty) 		as qty,
				0 									as qty_gr,
				0 									as qty_mutation,
				SUM(AP_Retur_Det.Qty)		as qty_return
				
				FROM AP_Retur_Det
				LEFT JOIN AP_Retur ON AP_Retur_Det.ReturNo = AP_Retur.ReturNo and AP_Retur_Det.CompanyID = AP_Retur.CompanyID
				GROUP BY AP_Retur_Det.ProductID,AP_Retur.Date,AP_Retur_Det.Conversion,AP_Retur_Det.UnitID)

			)
			as stock 

			left join ps_product on stock.productid	= ps_product.ProductID
			left join ps_product category on ps_product.ParentCode = category.Code
			left join ps_unit 	on stock.unitid		= ps_unit.UnitID
			
			WHERE 
			ps_product.CompanyID ='$CompanyID' 
			AND stock.Date >= '$start_date'
			AND stock.Date <= '$end_date'
			$where

			group by 
			stock.date,
			stock.productid,
			ps_product.Name,
			stock.conversion,
			ps_unit.unitid,
			ps_product.MinimumStock,
			category.Name,
			category.Code

			order by stock.date,ps_product.Name asc
		");
		return $query->result();
	}
	public function stock_initial($productid,$date,$conversion,$unitid)
	{
		$CompanyID 	= $this->session->CompanyID;
		$query 		= $this->db->query("SELECT 

			stock.productid 			as productid,
			category.Code 				as category_code,
			category.Name 				as category_name,
			ps_product.Name 			as product_name,
			ps_unit.Name 				as unit_name,
			ps_unit.unitid 				as unitid,
			stock.conversion 			as conversion,
			SUM(stock.qty_gr) 			as qty_in,
			SUM(stock.qty_mutation + stock.qty_return) 	as qty_out,
			SUM(stock.qty_gr - (stock.qty_mutation + stock.qty_return)) as qty,
			ps_product.MinimumStock as min_qty
			FROM 
			(
				(SELECT 
				AP_GoodReceipt_Det.ProductID 	as productid,
				AP_GoodReceipt.Date 				as date,
				AP_GoodReceipt_Det.Conversion as conversion,
				AP_GoodReceipt_Det.UnitID 		as unitid,		
				SUM(AP_GoodReceipt_Det.Qty) 	as qty,
				SUM(AP_GoodReceipt_Det.Qty) 	as qty_gr,
				0 										as qty_mutation,
				0										as qty_return
				FROM AP_GoodReceipt_Det
				LEFT JOIN AP_GoodReceipt ON AP_GoodReceipt_Det.ReceiveNo = AP_GoodReceipt.ReceiveNo and AP_GoodReceipt_Det.CompanyID = AP_GoodReceipt.CompanyID
				GROUP BY AP_GoodReceipt_Det.ProductID,AP_GoodReceipt.Date,AP_GoodReceipt_Det.Conversion,AP_GoodReceipt_Det.UnitID)
				
				UNION
				
				(SELECT 
				PS_Mutation_Detail.ProductID 	as productid,
				PS_Mutation.Date 					as date,
				PS_Mutation_Detail.Conversion as conversion,
				PS_Mutation_Detail.UnitID 		as unitid,		
				SUM(PS_Mutation_Detail.Qty) 	as qty,
				0 										as qty_gr,
				SUM(PS_Mutation_Detail.Qty) 	as qty_mutation,
				0										as qty_return
				
				FROM PS_Mutation_Detail
				LEFT JOIN PS_Mutation ON PS_Mutation_Detail.MutationNo = PS_Mutation.MutationNo and PS_Mutation_Detail.CompanyID = PS_Mutation.CompanyID
				GROUP BY PS_Mutation_Detail.ProductID,PS_Mutation.Date,PS_Mutation_Detail.Conversion,PS_Mutation_Detail.UnitID)
				
				UNION

				(SELECT 
				AP_Retur_Det.ProductID 		as productid,
				AP_Retur.Date 					as date,
				AP_Retur_Det.Conversion 	as conversion,
				AP_Retur_Det.UnitID 			as unitid,		
				SUM(AP_Retur_Det.Qty) 		as qty,
				0 									as qty_gr,
				0 									as qty_mutation,
				SUM(AP_Retur_Det.Qty)		as qty_return
				
				FROM AP_Retur_Det
				LEFT JOIN AP_Retur ON AP_Retur_Det.ReturNo = AP_Retur.ReturNo and AP_Retur_Det.CompanyID = AP_Retur.CompanyID
				GROUP BY AP_Retur_Det.ProductID,AP_Retur.Date,AP_Retur_Det.Conversion,AP_Retur_Det.UnitID)

			)
			as stock 

			left join ps_product on stock.productid	= ps_product.ProductID
			left join ps_product category on ps_product.ParentCode = category.Code
			left join ps_unit 	on stock.unitid		= ps_unit.UnitID
			
			where 
			ps_product.CompanyID	= '$CompanyID' AND
			stock.productid 		= '$productid' AND
			stock.conversion 		= '$conversion' AND
			stock.unitid 			= '$unitid' 	AND
			stock.Date < '$date'

			group by 
			stock.productid,
			ps_product.Name,
			stock.conversion,
			ps_unit.unitid,
			ps_product.MinimumStock,
			category.Name,
			category.Code

			order by ps_product.Name asc
		")->row();
		return $query;
	}
	public function count_all($table)
	{	
		if($table == "sales_visiting"):
			$table = "SP_TransactionRoute";
		elseif($table == "sales_visiting_time"):
			$table = "SP_TransactionRouteDetail";
		endif;

		$this->db->from($table);
		$this->filter($table);
		return $this->db->count_all_results();
	}

	#ini untuk salespro #salespro
	public function select_sales_visiting(){
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$group 		= $this->input->post("group");
		$search 	= $this->input->post("search");
		$company 	= $this->input->post("company");
		if($this->input->get("group")):
            $group 	= $this->input->get("group");
        	$search = $this->input->get("search");
        endif;
        $ParentID = $this->getParentID();

    	$table 	= "SP_TransactionRoute";

    	#ini mengatur selct
		if($group == "date"):
			$this->db->select("
				$table.BranchID				as ID,
				$table.Date 				as Date,
				Branch.Name 				as SalesName,
				user.nama 					as nama,
				sum(
					CASE
				        WHEN IFNULL(sp_td.CheckIn, 1) = 1 OR IFNULL(sp_td.CheckOut, 1) = 1 THEN 0
				     	ELSE 1
				    END
				) as total_visit,
	        ");
			$this->db->group_by("$table.Date,$table.BranchID,Branch.BranchID");
			$this->db->order_by("$table.Date","ASC");
		else:
	    	$this->db->select("
				sp_td.TransactionRouteID 	as ID,
				$table.Date 				as Date,
				$table.Code 				as Code,
				Branch.Name 				as SalesName,
				Branch.BranchID 			as BranchID,
				user.nama 					as nama,
				TIME_TO_SEC(timediff(sp_td.CheckOut,sp_td.CheckIn)) as total_checkin,
				sp_td.CheckInLatlng 		as CheckInLatlng,
				PS_Vendor.VendorID 			as VendorID,
				PS_Vendor.Lat 				as Lat,
				PS_Vendor.Lng 				as Lng,	
				PS_Vendor.Name 				as customer
	        ");
	    	$this->db->order_by("Branch.Name");
		endif;	
        $this->db->join("Branch","$table.BranchID = Branch.BranchID","left");
        if($group == "date"):
        	$this->db->join("user", "Branch.CompanyID = user.id_user", "left");
        	$this->db->join("SP_TransactionRouteDetail as sp_td", "$table.TransactionRouteID = sp_td.TransactionRouteID", "left");
        	$this->db->order_by("$table.Date","DESC");
        else:
        	$this->db->join("user", "$table.CompanyID = user.id_user", "left");
        	$this->db->join("SP_TransactionRouteDetail as sp_td", "$table.TransactionRouteID = sp_td.TransactionRouteID", "left");
        	$this->db->join("PS_Vendor", "sp_td.VendorID = PS_Vendor.VendorID", "left");
        	$this->db->order_by("sp_td.CheckIn");
        	// $this->db->group_by("sp_td.TransactionRouteID,$table.Date,$table.Code,Branch.Name,user.nama");
        endif;
       	
       	$this->db->where("sp_td.CheckIn != ", null);
		$this->db->where("sp_td.CheckOut != ", null);
       	if($company != "all" and $company):
       		$this->db->where("$table.CompanyID",$company);
       	else:
       		$this->db->where_in("$table.CompanyID",$ParentID);
       	endif;
       	
       	$this->db->where_in("$table.CompanyID",$ParentID);
        if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE($table.Date) >=",$start_date);
			$this->db->where("DATE($table.Date) <=",$end_date);
		endif;
		if($this->input->post("Sales") != "all"):
			$this->db->where("$table.BranchID",$this->input->post("Sales"));
		endif;
		if($search):
			$this->db->group_start();
			$this->db->like("DATE($table.Date)",$search);
			$this->db->or_like("Branch.Name",$search);
			$this->db->group_end();
		endif;
	}
	public function select_sales_visiting_time(){
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$group 		= $this->input->post("group");
		$search 	= $this->input->post("search");
		$company 	= $this->input->post("company");
		if($this->input->get("group")):
            $group 	= $this->input->get("group");
        	$search = $this->input->get("search");
        endif;

        $ParentID = $this->getParentID();
    	$table 	= "SP_TransactionRouteDetail";
    	$tablex = "SP_TransactionRoute";
    	$this->db->select("
			$table.TransactionRouteDetailID as ID,
			$tablex.Date 		as Date,
			$tablex.Code 		as Code,
			Branch.Name 		as SalesName,
			PS_Vendor.Name 		as CustomerName,
			PS_Vendor.Address 	as CustomerAddress,
			$table.CheckIn 		as CheckIn,
			$table.CheckOut 	as CheckOut,
			$table.CheckInAddress 		as CheckInAddress,
			$table.CheckOutAddress 		as CheckOutAddress,
			$table.Remark 		as Remark,
			$table.RemarkSales 	as RemarkSales,
			user.nama 			as nama,
        ");
        $this->db->join("$tablex","$table.TransactionRouteID = $tablex.TransactionRouteID","left");
        $this->db->join("Branch","$tablex.BranchID = Branch.BranchID","left");
        $this->db->join("PS_Vendor","$table.VendorID = PS_Vendor.VendorID","left");
        $this->db->join("user", "$table.CompanyID = user.id_user", "left");

       	if($company != "all" and $company):
       		$this->db->where("$table.CompanyID",$company);
       	else:
       		$this->db->where_in("$table.CompanyID",$ParentID);
       	endif;

       	$this->db->where("IFNULL(PS_Vendor.BaseCamp, 0) = ", "0");
        if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE($tablex.Date) >=",$start_date);
			$this->db->where("DATE($tablex.Date) <=",$end_date);
		endif;
		if($this->input->post("Sales") != "all"):
			$this->db->where("$tablex.BranchID",$this->input->post("Sales"));
		endif;
		if($search):
			$this->db->group_start();
			$this->db->like("DATE($tablex.Date)",$search);
			$this->db->or_like("Branch.Name",$search);
			$this->db->or_like("PS_Vendor.Name",$search);
			$this->db->group_end();
		endif;
       	$this->db->order_by("$tablex.DateAdd","DESC");
	}
	public function get_transaction_detail($id)
	{
    	$table 	= "SP_TransactionRouteDetail";
    	$tablex = "SP_TransactionRoute";
    	$this->db->select("
			$table.TransactionRouteDetailID as ID,
			$tablex.Date 					as Date,
			$tablex.Code 					as Code,
			$tablex.CompanyID 				as CompanyID,
			Branch.Name 					as SalesName,
			PS_Vendor.VendorID 				as CustomerID,
			PS_Vendor.Name 					as CustomerName,
			PS_Vendor.Address 				as CustomerAddress,
			PS_Vendor.Lat 					as CustomerLat,
			PS_Vendor.Lng 					as CustomerLng,
			PS_Vendor.Radius 				as Radius,
			$table.CheckIn 					as CheckIn,
			$table.CheckOut 				as CheckOut,
			$table.CheckInAddress 			as CheckInAddress,
			$table.CheckOutAddress 			as CheckOutAddress,
			$table.Remark 					as Remark,
			$table.RemarkSales 				as RemarkSales,
			$table.CheckInLatlng 			as CheckInLatlng,
			$table.CheckOutLatlng 			as CheckOutLatlng,
			$table.ImgSales 				as CustomerImage,
        ");
        $this->db->join("$tablex","$table.TransactionRouteID = $tablex.TransactionRouteID","left");
        $this->db->join("Branch","$tablex.BranchID = Branch.BranchID","left");
        $this->db->join("PS_Vendor","$table.VendorID = PS_Vendor.VendorID","left");
       	$this->db->where("$table.TransactionRouteDetailID",$id);
       	// $this->db->where("$tablex.CompanyID",$this->session->CompanyID);
       	$query = $this->db->get("$table");
		return $query->row();
	}
	public function get_transaction_route_id($ID,$date)
	{
		$this->db->select("TransactionRouteID");
		$this->db->where("BranchID",$ID);
		$this->db->where("Date",$date);
		$query = $this->db->get("SP_TransactionRoute");
		return $query->result();
	}
	public function get_transaction_route_detail($filter="",$id,$except = "")
	{
		$this->db->select("
			SP_TransactionRouteDetail.TransactionRouteDetailID,
			SP_TransactionRouteDetail.VendorID,
			SP_TransactionRouteDetail.CheckIn,
			SP_TransactionRouteDetail.CheckInLatlng,
			SP_TransactionRouteDetail.CheckInAddress,
			SP_TransactionRouteDetail.CheckOut,
			PS_Vendor.Name,
			PS_Vendor.Address,
			PS_Vendor.Lat,
			PS_Vendor.Lng,
		");
		$this->db->join("PS_Vendor","SP_TransactionRouteDetail.VendorID = PS_Vendor.VendorID","left");
		// $this->db->where("SP_TransactionRouteDetail.CompanyID",$this->session->CompanyID);
		$this->db->where("IFNULL(PS_Vendor.BaseCamp, 0) = ", "0");
		$this->db->where("SP_TransactionRouteDetail.CheckIn != ", null);
		$this->db->where("SP_TransactionRouteDetail.CheckOut != ", null);
		if($filter == "TransactionRouteIDArray"):
			$this->db->where_in("TransactionRouteID",$id);
		else:
			$this->db->where("TransactionRouteID",$id);
		endif;
		if($except){
			$this->db->where_not_in("SP_TransactionRouteDetail.TransactionRouteDetailID",$except);
		}
		$this->db->order_by("SP_TransactionRouteDetail.CheckIn");
		$query = $this->db->get("SP_TransactionRouteDetail");
		return $query->result();
	}

	private function getParentID(){
		$this->db->select("id_user");
		$this->db->where("ParentID", $this->session->CompanyID);
		$query = $this->db->get("user");
		$data =  array($this->session->CompanyID);
		foreach ($query->result() as $d) {
			array_push($data, $d->id_user);
		}
		return $data;
	}

	public function TransactionRouteKm($id){
		$this->db->select("Km,KmValue");
		$this->db->where_in("TransactionRouteID", $id);
		$this->db->where("Km !=", null);
		$query = $this->db->get("SP_TransactionRoute");

		return $query;
	}

	// contoh pemanggilan ke jurnal
	public function example_get_jurnal(){
		$CompanyID 	= $this->session->CompanyID;
		$start_date = date("Y-01-01");
		$end_date 	= date("Y-m-d");
		$VendorID 	= '';
		$Pilihan 	= 2; // kartu piutang


		$query = $this->db->query("call  sp_piutang('$start_date','$end_date','$CompanyID','$VendorID', '$Pilihan')");

		return $query->result();
	}


	#akunting
	public function select_casch($table){
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$group 		= $this->input->post("group");
		$search 	= $this->input->post("search");
		$this->db->select("
			$table.KasBankNo,
			$table.Date,
			$table.DebitTotal,
			$table.CreditTotal,

			dt.Debit,
			dt.Credit,
			dt.Remark,

			coa.Name as coaName,
			coa.Code as coaCode,
		");
		$this->db->join("AC_KasBank_Det as dt", "dt.KasBankNo = $table.KasBankNo and dt.CompanyID = $table.CompanyID");
		$this->db->join("AC_COA 		as coa", "coa.COAID = dt.COAID and coa.CompanyID = dt.CompanyID");
		$this->db->where("$table.CompanyID", $this->session->CompanyID);
		$this->db->where("$table.Type", 1);
		if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE($table.Date) >=",$start_date);
			$this->db->where("DATE($table.Date) <=",$end_date);
		endif;
	}

	public function select_bank($table){
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$group 		= $this->input->post("group");
		$search 	= $this->input->post("search");
		$this->db->select("
			$table.KasBankNo,
			$table.Date,
			$table.DebitTotal,
			$table.CreditTotal,

			dt.Debit,
			dt.Credit,
			dt.Remark,

			coa.Name as coaName,
			coa.Code as coaCode,
		");
		$this->db->join("AC_KasBank_Det as dt", "dt.KasBankNo = $table.KasBankNo and dt.CompanyID = $table.CompanyID");
		$this->db->join("AC_COA 		as coa", "coa.COAID = dt.COAID and coa.CompanyID = dt.CompanyID");
		$this->db->where("$table.CompanyID", $this->session->CompanyID);
		$this->db->where("$table.Type", 2);
		if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE($table.Date) >=",$start_date);
			$this->db->where("DATE($table.Date) <=",$end_date);
		endif;
	}

	public function select_jurnal(){
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$CompanyID 	= $this->session->CompanyID;

		// call sp_FinanceReport(tglAwal,tglAkhir,CompanyID,COAID,nChoose);
		$query = $this->db->query("call sp_FinanceReport('$start_date','$end_date',$CompanyID,0,1)");
		return $query->result();
	}

	public function select_balance_sheet(){
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$CompanyID 	= $this->session->CompanyID;

		// call sp_FinanceReport(tglAwal,tglAkhir,CompanyID,COAID,nChoose);
		$query = $this->db->query("call sp_FinanceReport('$start_date','$end_date',$CompanyID,0,2)");
		return $query->result();
	}

	public function select_loss_and_profit(){
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$CompanyID 	= $this->session->CompanyID;

		// call sp_FinanceReport(tglAwal,tglAkhir,CompanyID,COAID,nChoose);
		$query = $this->db->query("call sp_FinanceReport('$start_date','$end_date',$CompanyID,0,3)");
		return $query->result();
	}

	public function select_ledger(){
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$CompanyID 	= $this->session->CompanyID;
		// call sp_FinanceReport(tglAwal,tglAkhir,CompanyID,COAID,nChoose);
		$query = $this->db->query("call sp_FinanceReport('$start_date','$end_date',$CompanyID,0,4)");
		return $query->result();
	}
	#end akunting

	#good receipt
	#mengolah data per vendor / branch
	public function good_receipt_manage($list,$p1,$p2){
		${$p1} = array();
		foreach ($list as $v) {
			if(!in_array($v->{$p1},${$p1})):
				array_push(${$p1},$v->{$p1});
			endif;
		}
		$data = array();
		foreach (${$p1} as $v) {
			${$p2} = '';
			$qty 		= 0;
			$total 		= 0;
			foreach ($list as $v2) {
				if($v == $v2->{$p1}):
					${$p2} = $v2->{$p2};
					$qty 	+= $v2->qty;
					$total 	+= $v2->subtotal;
				endif;
			}
			$h = array(
				$p2 			=> ${$p2},
				"qty"			=> $qty,
				"subtotal"		=> $total,
			);
			array_push($data, $h);
		}
		$list = json_encode($data);
		$list = json_decode($list);

		return $list;
	}

	#stock opname
	public function select_stock_opname(){
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$group 		= $this->input->post("group");
		$search 	= $this->input->post("search");
		$CompanyID 	= $this->session->CompanyID;
		if($this->input->get("group")):
            $group 	= $this->input->get("group");
        	$search = $this->input->get("search");
        endif;
        $table = "PS_Correction";
        $tablex= "PS_Correction_Detail";

        $not_detail = array('store');
        if($group == "all"):
        	$this->db->select("
        		$table.CorrectionNo,
        		$table.Date,
        		Branch.Name 	as branchName,
        		ps_product.Code as product_code,
        		ps_product.Name as product_name,
        		detail.PriceBefore,
        		detail.Price,
        		detail.Qty,
        		detail.CorrectionQty,
        		(detail.CorrectionQty - detail.Qty) as correction_stock,
        		ps_product.Uom as unit_name,
        		detail.Remark,
    		");
    	elseif($group == "transaction"):
    		$this->db->select("
    			$table.CorrectionNo,
        		$table.Date,
        		Branch.Name 	as branchName,
        		SUM(detail.Qty) as Qty,
        		SUM(detail.CorrectionQty) as CorrectionQty,
        		SUM(detail.CorrectionQty - detail.Qty) as correction_stock,
			");
			$this->db->group_by("
				$table.CorrectionNo,
			");
		elseif($group == "store"):
			$this->db->select("
				Branch.Name 	as branchName,
				count($table.CorrectionNo) as totalTransaction,
				sum((select sum(detail.Qty) from PS_Correction_Detail as detail where $table.CorrectionNo = detail.CorrectionNo and $table.CompanyID = detail.CompanyID)) as Qty,
				sum((select sum(detail.CorrectionQty) from PS_Correction_Detail as detail where $table.CorrectionNo = detail.CorrectionNo and $table.CompanyID = detail.CompanyID)) as CorrectionQty,
				sum((select sum(detail.CorrectionQty - detail.Qty) from PS_Correction_Detail as detail where $table.CorrectionNo = detail.CorrectionNo and $table.CompanyID = detail.CompanyID)) as correction_stock,

			");
			$this->db->group_by("
				Branch.BranchID,
			");
        endif;

        if(!in_array($group,$not_detail)):
        	$this->db->join("$tablex as detail", "detail.CorrectionNo = $table.CorrectionNo and detail.CompanyID = $table.CompanyID", "left");
        	$this->db->join("ps_product", "detail.ProductID = ps_product.ProductID and detail.CompanyID = ps_product.CompanyID");
        endif;
        $this->db->join("Branch", "Branch.BranchID = $table.BranchID and Branch.CompanyID = $table.CompanyID", "left");
        $this->db->where("$table.Type", 2);
        $this->db->where("$table.CompanyID", $CompanyID);

        if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE($table.Date) >=",$start_date);
			$this->db->where("DATE($table.Date) <=",$end_date);
		endif;

		$branch = $this->input->post('branch');
        if($branch != "all" and $branch):
        	$this->db->where("Branch.BranchID", $branch);
        endif;

        $product = $this->input->post('product');
        if($product != "all" and $product):
        	$this->db->where("ps_product.ProductID", $product);
        endif;

        $query = $this->db->get($table);

        return $query->result();
	}

	#stock receipt
	public function select_stock_receipt($page=""){
		$start_date = $this->input->post("start_date");
		$end_date 	= $this->input->post("end_date");
		$group 		= $this->input->post("group");
		$search 	= $this->input->post("search");
		$CompanyID 	= $this->session->CompanyID;
		if($this->input->get("group")):
            $group 	= $this->input->get("group");
        	$search = $this->input->get("search");
        endif;
        $table = "PS_Correction";
        $tablex= "PS_Correction_Detail";

        $not_detail = array('store');
        if($group == "all"):
        	$this->db->select("
        		$table.CorrectionNo,
        		$table.Date,
        		Branch.Name 	as branchName,
        		ps_product.Code as product_code,
        		ps_product.Name as product_name,
        		detail.Qty,
        		detail.Conversion,
        		ifnull(unit.Uom,'') as unit_name,
        		detail.Price,
        		(detail.Qty * detail.Conversion) as total_qty,
    		");
    	elseif($group == "transaction"):
    		$this->db->select("
        		$table.CorrectionNo,
        		$table.Date,
        		Branch.Name 	as branchName,
        		SUM(detail.Qty) as Qty,
        		SUM((detail.Qty * detail.Conversion) * detail.Price) as Price,
    		");
    		$this->db->group_by("
    			$table.CorrectionNo,
			");
		elseif($group == "store"):
			$this->db->select("
        		Branch.Name 	as branchName,
        		count($table.CorrectionNo) as totalTransaction,
        		sum((select sum(detail.Qty) from PS_Correction_Detail as detail where $table.CorrectionNo = detail.CorrectionNo and $table.CompanyID = detail.CompanyID)) as Qty,
    			sum((select sum((detail.Qty * detail.Conversion) * detail.Price) from PS_Correction_Detail as detail where $table.CorrectionNo = detail.CorrectionNo and $table.CompanyID = detail.CompanyID)) as Price,
    		");
    		$this->db->group_by("
    			$table.BranchID,
			");
    	endif;
    	if(!in_array($group,$not_detail)):
        	$this->db->join("$tablex as detail", "detail.CorrectionNo = $table.CorrectionNo and detail.CompanyID = $table.CompanyID", "left");
        	$this->db->join("ps_product", "detail.ProductID = ps_product.ProductID and detail.CompanyID = ps_product.CompanyID");
        	$this->db->join("ps_product_unit as unit", "unit.ProductUnitID = detail.Uom and unit.ProductID = detail.ProductID","left");
        endif;
    	$this->db->join("Branch", "Branch.BranchID = $table.BranchID and Branch.CompanyID = $table.CompanyID", "left");
    	if($page == "issue"):
    		$this->db->where("$table.Type", 4);
    	else:
    		$this->db->where("$table.Type", 3);
    	endif;
        $this->db->where("$table.CompanyID", $CompanyID);

        if(!empty($start_date) && !empty($end_date)):
			$this->db->where("DATE($table.Date) >=",$start_date);
			$this->db->where("DATE($table.Date) <=",$end_date);
		endif;

		$branch = $this->input->post('branch');
        if($branch != "all" and $branch):
        	$this->db->where("Branch.BranchID", $branch);
        endif;

        $product = $this->input->post('product');
        if($product != "all" and $product):
        	$this->db->where("ps_product.ProductID", $product);
        endif;

        $query = $this->db->get($table);

        return $query->result();
	}
}