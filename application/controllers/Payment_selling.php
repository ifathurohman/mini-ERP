<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_selling extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->main->cek_session();
		$this->load->model("M_payment_selling",'payment');
	}

	public function save_pay(){
		$this->pay_validate();
		$CompanyID 		= $this->session->CompanyID;
		$pay_sellno  	= $this->input->post('pay_sellno');
		$pay_page 		= $this->input->post('pay_page');
		$pay_type 		= $this->input->post('pay_type');
		$pay_customer 	= $this->input->post('pay_customer');
		$date 			= $this->input->post('pay_date');

		$pay_payment 	= $this->main->checkDuitInput($this->input->post('pay_payment'));
		$pay_additional = $this->main->checkDuitInput($this->input->post('pay_additional'));
		$pay_unpayment 	= $this->main->checkDuitInput($this->input->post('pay_unpayment'));
		
		$pay_paymentType 	= $this->input->post('pay_paymentType');
		$pay_girono 		= $this->input->post('pay_girono');
		$pay_accountno 		= $this->input->post('pay_accountno');
		$pay_bankname 		= $this->input->post('pay_bankname');
		$pay_accountname 	= $this->input->post('pay_accountname');
		$pay_paymentmethod 	= $this->input->post('pay_paymentmethod');

		$PaymentNo 		= $this->main->autoNumber("PS_Payment", "PaymentNo", 5, "PS".date("ymd"));
		$unpayment 		= $pay_unpayment - $pay_payment;

		$data = array(
			'PaymentNo'		=> $PaymentNo,
			'CompanyID' 	=> $CompanyID,
			'SellNo'		=> $pay_sellno,
			'Date'			=> date($date." H:i:s"),
			'TotalAwal'		=> $pay_payment,
			'Total'			=> $pay_payment,
			'GrandTotal'	=> $pay_payment,
			'UnPayment'		=> $unpayment,
			'Status'		=> 1,
			'PaymentMethod'	=> $pay_paymentmethod,
			'Type'			=> $pay_type,
			'PaymentType'	=> $pay_paymentType,
		);

		$data_detail = array(
			'PaymentNo'		=> $PaymentNo,
			'CompanyID'		=> $CompanyID,
			'SellNo'		=> $pay_sellno,
			'Total'			=> $pay_payment,
			'Date'			=> date($date),
		);
		if($pay_page == "selling"):
			$data_detail['Type'] = 1;
		endif;

		if($pay_customer):
			$pay_customer = explode("-", $pay_customer);
			$data['VendorID'] = $pay_customer[0];
			$data_detail['VendorID'] = $pay_customer[0];
		endif;

		if($pay_paymentType != 0):
			$data['BankName']		= $pay_bankname;
			$data['AccountName'] 	= $pay_accountname;
		endif;
		if($pay_paymentType == 0):
			$data['Cash'] = $pay_payment;
		elseif($pay_paymentType == 1):
			$data['Giro'] 	= $pay_payment;
			$data['GiroNo'] = $pay_girono;
		elseif($pay_paymentType == 2):
			$data['Credit']   = $pay_payment;
			$data['AcountNo'] = $pay_accountno;
		endif;

		$this->payment->save($data);
		$this->payment->save_det($data_detail);
		$this->check_paid($pay_sellno,$pay_page,$pay_type);

		$res = array(
			'status'	=> true,
			'message'	=> 'success',
		);
		$this->main->echoJson($res);
	}

	private function pay_validate(){
		$data = array();
		$data['status'] = TRUE;

		$pay_payment 	= $this->main->checkDuitInput($this->input->post('pay_payment'));
		$pay_additional = $this->main->checkDuitInput($this->input->post('pay_additional'));
		$pay_unpayment 	= $this->main->checkDuitInput($this->input->post('pay_unpayment'));
		$pay_paymentType= $this->input->post('pay_paymentType');

		if($pay_payment>$pay_unpayment):
			$data['inputerror'][] 	= 'pay_payment';
			$data['error_string'][] = 'payment cost is greater than unpayment';
			$data['type'][]			= '';
			$data['status'] 		= FALSE;
		elseif($pay_payment == 0):
			$data['inputerror'][] 	= 'pay_payment';
			$data['error_string'][] = 'payment cost cannot be null';
			$data['type'][]			= '';
			$data['status'] 		= FALSE;
		endif;

		// payment method
		$pay_girono 		= $this->input->post('pay_girono');
		$pay_accountno 		= $this->input->post('pay_accountno');
		$pay_bankname 		= $this->input->post('pay_bankname');
		$pay_accountname 	= $this->input->post('pay_accountname');
		$pay_paymentmethod 	= $this->input->post('pay_paymentmethod');

		if($pay_paymentType):
			if($pay_accountname == ''):
				$data['inputerror'][] 	= 'pay_accountname';
				$data['error_string'][] = 'Account Name cannot be null';
				$data['type'][]			= '';
				$data['status'] 		= FALSE;
			endif;
			if($pay_bankname == ''):
				$data['inputerror'][] 	= 'pay_bankname';
				$data['error_string'][] = 'Bank Name cannot be null';
				$data['type'][]			= '';
				$data['status'] 		= FALSE;
			endif;
		endif;
		if($pay_paymentType == 1):
			if($pay_girono == ''):
				$data['inputerror'][] 	= 'pay_girono';
				$data['error_string'][] = 'Giro no cannot be null';
				$data['type'][]			= '';
				$data['status'] 		= FALSE;
			endif;
		elseif($pay_paymentType == 2):
			if($pay_accountno == ''):
				$data['inputerror'][] 	= 'pay_accountno';
				$data['error_string'][] = 'Account No cannot be null';
				$data['type'][]			= '';
				$data['status'] 		= FALSE;
			endif;
		endif;
		if($pay_paymentmethod == "none"):
			$data['inputerror'][] 	= 'pay_paymentmethod';
			$data['error_string'][] = 'Account No cannot be null';
			$data['type'][]			= 'select';
			$data['status'] 		= FALSE;
		endif;

		if($data['status'] === FALSE)
        {
            header('Content-Type: application/json');
            echo json_encode($data,JSON_PRETTY_PRINT);  
            exit();
        }
	}

	private function check_paid($id,$page){
		$total_pay  = 0;
        $total      = 0;
        $unpayment  = 0;
        $data 		= array();

		$list       = $this->api->payment_list($id,$page);
        foreach ($list as $k => $v) {
            $total_pay += $v->TotalAwal;
        }
        if($page == "selling"):
            $selling    = $this->api->selling($id,"detail");
            $total      = $selling->Payment;
        endif;
        if($total_pay == $total):
        	$data['Paid'] = 1;
        	if($page == "selling"):
        		$this->db->where("SellNo",$id);
        		$this->db->update("PS_Sell",$data);
        	endif;
        endif;
	}
}