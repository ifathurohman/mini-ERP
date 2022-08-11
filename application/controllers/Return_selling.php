<!-- return untuk perpenjualan yang sekarang di hide -->
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Return_selling extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->main->cek_session();
		$this->load->model("M_return_selling",'return');
	}

	public function save_return(){
		$this->return_validate();

		$CompanyID 	= $this->session->CompanyID;
		$page 		= $this->input->post('return_page');
		$id 		= $this->input->post('return_sellno');
		$type 		= $this->input->post('return_type');
		$date 		= $this->input->post('returndate');
		$customer 	= $this->input->post('return_customer');
		$remark 	= $this->input->post('return_remark');

		// product
		$recekbox  		= $this->input->post('recekbox');
		$product_id 	= $this->input->post('reproduct_id');
		$qty 			= $this->input->post('reproduct_qty');
		$qtyawal 		= $this->input->post('reqty');
		$iddet 			= $this->input->post('reselldet');
		$product_remark = $this->input->post('reproduct_remark');
		$product_price 	= $this->input->post('reproduct_price');
		$product_total	= $this->input->post('reproduct_total');
		$product_konv 	= $this->input->post('reproduct_konv');
		$product_type 	= $this->input->post('reproduct_type');
		$unitid 		= $this->input->post('reunitid');

		$returno 		= $this->main->returno_generate();

		$data = array(
			"ReturNo"		=> $returno,
			"CompanyID"		=> $CompanyID,
			"Date"			=> $date,
			"Remark"		=> $remark,
		);

		if($page == "selling"):
			$data['Type']	= 2;
			$data['SellNo']	= $id;
		endif;

		if($customer != ""):
			$xcustomer = explode("-", $customer);
			$data['VendorID'] = $xcustomer[0];
		endif;

		$this->return->save($data);
		foreach ($recekbox as $k => $v) {
			$data_detail = array(
				"CompanyID" 	=> $CompanyID,
				"ReturNo" 		=> $returno,
				"ProductID"		=> $product_id[$k],
				"UnitID"		=> $unitid[$k],
				"Qty"			=> $qty[$k],
				"Conversion"	=> $product_konv[$k],
				"Price"			=> $product_price[$k],
				"Total"			=> $product_total[$k],
				"Type"			=> $product_type[$k],
				"Remark"		=> $product_remark[$k],
			);

			if($page == "selling"):
				$data_detail['SellNo'] 	= $id;
				$data_detail['SellDet'] = $iddet[$k];
			endif;
			$this->return->save_det($data_detail);
			$this->api->tambahQty($product_id[$k],$qty[$k]);
		}

		$res = array(
			'status'	=> true,
			'message'	=> 'success',
		);
		$this->main->echoJson($res);
	}
	private function return_validate(){
		$data = array();
		$data['status'] = TRUE;

		$page 		= $this->input->post('return_page');
		$id 		= $this->input->post('return_sellno');
		$type 		= $this->input->post('return_type');

		$recekbox  	= $this->input->post('recekbox');
		$qty 		= $this->input->post('reproduct_qty');
		$qtyawal 	= $this->input->post('reqty');
		$iddet 		= $this->input->post('reselldet');

		if(count($recekbox)>0):
			foreach ($recekbox as $k => $v) {
				$val = $recekbox[$k];
				if($qty[$k] == '' || $qty[$k]<=0):
					$data['inputerror'][] 	= '.returnrowid_'.$val;
					$data['error_string'][] = 'Qty product cannot be null';
					$data['type'][]			= 'list';
					$data['status'] 		= FALSE;
				else:
					if($page == "selling"):
						$status = $this->calculate_qty($id,$iddet[$k],$page,$qtyawal[$k],$qty[$k]);
						if(!$status):
							$data['inputerror'][] 	= '.returnrowid_'.$val;
							$data['error_string'][] = 'qty exceeds the limit';
							$data['type'][]			= 'list';
							$data['status'] 		= FALSE;
						endif;
					endif;
				endif;
			}
		else:
			$data['inputerror'][] 	= 'recekbox';
			$data['error_string'][] = '';
			$data['type'][]			= '';
			$data['message']		= 'Please select product';
			$data['status'] 		= FALSE;
		endif;

		if($data['status'] === FALSE)
        {
            header('Content-Type: application/json');
            echo json_encode($data,JSON_PRETTY_PRINT);  
            exit();
        }
	}

	private function calculate_qty($id,$iddet,$page,$qtyawal,$qty){
		$list 		= $this->return->get_by_detail($id,$iddet,$page);
		$totalqty 	= 0;
		$status 	= true;
		foreach ($list as $k => $v) {
			$totalqty += $v->Qty;
		}

		$total = $qtyawal - $totalqty;

		if($total<$qty):
			$status = false;
		endif;

		return $status;
	}
}