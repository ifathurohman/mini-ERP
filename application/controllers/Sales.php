<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales extends CI_Controller {
	var $title = 'Sales & Employee';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_sales",'sales');
		$this->load->library(array('PHPExcel','IOFactory'));
		$this->main->cek_session();
	}
	public function index()
	{	
		$ID  	= $this->input->post("ID");
		$Status = $this->input->post("Status");

		$url 						= $this->uri->segment(1); 
		$id_url 					= $this->main->id_menu($url);
		$read 						= $this->main->read($id_url);
		if($read == 0){ redirect(); }
		$Sales_tambah 				= $this->main->menu_tambah($id_url);
		if($Sales_tambah > 0):
            $tambah = $this->main->general_button('add','Add New '.$this->title);
		else: 
			$tambah = ""; 
		endif;
		#ini untuk session halaman aturan user privileges;
		$data['title']  		= $this->title;
		$data['tambah'] 		= $tambah;
		$data['modal'] 			= 'sales/modal';
		$data['modal_print']  	= 'sales/modal_print';
		$data['page'] 			= 'sales/list';
		$data['modul'] 			= 'sales';
		$data['url_modul'] 		= $url;
		$data['ID'] 			= $ID;
		$data['Status'] 		= $Status;
		$this->load->view('index',$data);
	}

	public function ajax_list($url_modul="",$modul=""){
		$id_url = $this->main->id_menu($url_modul);
		$edit 	= $this->main->menu_ubah($id_url);
		$delete = $this->main->menu_hapus($id_url);
		$list 	= $this->sales->get_datatables();
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		foreach ($list as $a) {
			$btn_edit 		= '';
			$btn_delete 	= '';
			$status 		= $this->main->label_active2($a->Status);

			if($edit>0):
				$btn_edit = $this->main->button_action("edit", $a->SalesID);
			endif;

			if($delete>0):
				if($a->Status == 1):
					$btn_delete = $this->main->button_action("delete2", $a->SalesID);
				else:
					$btn_delete = $this->main->button_action("undelete", $a->SalesID);
				endif;
			endif;

			$button  = '<div class="btn-group btn-group-xs" aria-label="Basic example" role="group">';
            $button .= $btn_edit;
            $button .= $btn_delete;
            $button .= '</div>';

            $code = '<a href="javascript:void(0)" type="button" class="" title="View Detail" onclick="view_sales('."'".$a->SalesID."'".')">'.$a->Code.'</a>';

			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $code;
			$row[] 	= $a->Name; 
			$row[] 	= $a->Contact; 
			$row[] 	= $a->City; 
			$row[] 	= $a->Address; 
			$row[] 	= $status; 
			// $row[] 	= $button; 
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->sales->count_all(),
			"recordsFiltered" => $this->sales->count_filtered(),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function save(){
		$this->validate();
		$crud  		= $this->input->post("crud");
		$Code  		= $this->input->post("Code");
		$SalesID 	= $this->input->post('SalesID');
		$Name 		= $this->input->post('Name');
		$Phone 		= $this->input->post('Phone');
		$City 		= $this->input->post('City');
		$Address 	= $this->input->post('Address');
		$Remark 	= $this->input->post('Remark');

		$data = array(
			'Name'		=> $Name,
			'Contact'	=> $Phone,
			'City' 		=> $City,
			'Address'	=> $Address,
			'Remark'	=> $Remark,
		);

		if($crud == "update"):
			$this->sales->update(array("SalesID" => $SalesID), $data);
		else:
			if(!$Code):
				$Code = $this->main->sales_generate();
				$data['CodeType'] = 0;
			else:
				$data['CodeType'] = 1;
			endif;
			$data['Code']		= $Code;
			$data['Status'] 	= 1;
			$data['CompanyID'] 	= $this->session->CompanyID;
			$SalesID = $this->sales->save($data);
		endif;

		$res = array(
			'status' 	=> true,
			'message' 	=> 'success',
			'name' 		=> $Name,
			'ID'		=> $SalesID,
			'address'	=> $Address,
		);
		$this->main->echoJson($res);
	}

	private function validate(){
		$crud  		= $this->input->post("crud");
		$Code  		= $this->input->post("Code");
		$SalesID 	= $this->input->post('SalesID');
		$Name 		= $this->input->post('Name');
		$CompanyID 	= $this->session->CompanyID;

		$data 					= array();
		$data['error_string'] 	= array();
		$data['inputerror'] 	= array();
		$data['status'] 		= TRUE;

		if(!$CompanyID):
			$data['inputerror'][] 	= '';
			$data['error_string'][] = '';
			$data['status'] 		= FALSE;
		endif;

		if(!$Name):
			$data['inputerror'][] 	= 'Name';
			$data['error_string'][] = $this->lang->line('lb_name_empty');
			$data['status'] 		= FALSE;
		endif;

		if($Code == ''):
			// $data['inputerror'][] 	= 'Code';
			// $data['error_string'][] = "Code can't be null";
			// $data['status'] 		= FALSE;
		else:
			if($crud == "update"):
				$cek = $this->db->count_all("PS_Sales where Code = '$Code' and CompanyID = '$CompanyID' and SalesID != '$SalesID'");
				if($cek>0):
					$data['inputerror'][] 	= 'Code';
					$data['error_string'][] = $this->lang->line('lb_code_exist');
					$data['status'] 		= FALSE;
				endif;
			else:
				$cek = $this->db->count_all("PS_Sales where Code = '$Code' and CompanyID = '$CompanyID'");
				if($cek>0):
					$data['inputerror'][] 	= 'Code';
					$data['error_string'][] = $this->lang->line('lb_code_exist');
					$data['status'] 		= FALSE;
				endif;
			endif;
		endif;

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	public function ajax_edit($id){
		$data 	= $this->sales->get_by_id($id);
		$edit   = $this->main->button_action("edit2",$id);
		$delete = $this->main->button_action("delete4",$id);
		
		if($data->Status == 0):
			$delete 	 = $this->main->button_action("undelete2",$id);
		endif;
		if($data->Status == 0):
			$edit 	 	 = '';
		endif;
		$output = array(
			"data" 		  => $data,
			"edit" 	 	  => $edit,
			"delete" 	  => $delete,
		);
		echo json_encode($output);
	}


	public function delete($id){
		$a = $this->sales->get_by_id($id);
		if($a):
			$active = 0;
			$title 	= "Delete!";
			if($a->Status != 1):
				$active = 1;
				$title 	= "Undelete!";
			endif;
			$data = array("Status" => $active);
			$this->sales->update(array("SalesID" => $id), $data);
			$status  = true;
			$message = $this->lang->line('lb_success');
		else:
			$title 	= "";
			$status  = FALSE;
			$message = $this->lang->line('lb_success');
		endif;

		$res = array("status" => $status,"message"=>$message,"title" => $title);
		$this->main->echoJson($res);
	}

	public function export($page=""){
		$list = $this->api->sales_select("active");
		$file_name = "SampleSales".date("Ymd_His").".xls";
		$objPHPExcel    = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("PIPESYS APPLICATION MANAGEMENT")
                    ->setLastModifiedBy("PIPESYS APPLICATION MANAGEMENT")
                    ->setTitle("Office 2003 XLS Test Document")
                    ->setSubject("Office 2003 XLS Test Document")
                    ->setDescription("Dokumen ini dari aplikasi PIPESYS")
                    ->setKeywords("office 2003 openxml php")
                    ->setCategory("Template Excel Driver");
        $border_style = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
        $i      = 1;
        $ii     = 2;

        $jumlah_kolom = count($list)+1;
        foreach(range('A','F') as $columnID):
	        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
	    endforeach;
	    $objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValue('A1', 'Sales Code')
			            ->setCellValue('B1', 'Name')
			            ->setCellValue('C1', 'Phone')
			            ->setCellValue('D1', 'City')
			            ->setCellValue('E1', 'Address')
			            ->setCellValue('F1', 'Remark');
		if($page != "template"):
			foreach ($list as $a):                
		      	$no              	= $i++; 
		      	$urut            	= $ii++;
		      	$objPHPExcel->setActiveSheetIndex(0)
		                  ->setCellValue('A'.$urut, $a->Code)
		                  ->setCellValue('B'.$urut, $a->Name)
		                  ->setCellValue('C'.$urut, $a->Contact)
		                  ->setCellValue('D'.$urut, $a->City)
		                  ->setCellValue('E'.$urut, $a->Address)
		                  ->setCellValue('F'.$urut, $a->Remark);
		    endforeach;
		endif;

	    // Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Template Sales');
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$file_name.'"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();
		$objWriter->save('php://output');
		exit;
	}

	private function import2(){
		$fileName                 = $this->input->post('file', TRUE);
		$CompanyID 				  = $this->session->CompanyID;
		$config['upload_path']    = './file/'; 
		$config['file_name']      = $fileName;
		$config['allowed_types']  = 'xls|xlsx|csv|ods|ots';
		$config['max_size']       = 10000;
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if (!$this->upload->do_upload('file')):
			$output = array(
				"status" 	=> false,
				"message"	=> "Please Upload Excel File",
				"hak_akses"	=> $this->session->hak_akses,
			);
		else:
			$media 			= $this->upload->data();
			$inputFileName 	= 'file/'.$media['file_name'];
			try {
				$inputFileType  = IOFactory::identify($inputFileName);
				$objReader      = IOFactory::createReader($inputFileType);
				$objPHPExcel  	= $objReader->load($inputFileName);
			}
			catch(Exception $e) {
				die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
			}

			$sheet         = $objPHPExcel->getSheet(0);
			$highestRow    = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();
			$list 		= array();

			$status 	= true;
			$message 	= "Success";

			for ($row = 2; $row <= $highestRow; $row++){
				$rowData 		= $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
				$Code 			= $rowData[0][0];
				$Name 			= $rowData[0][1];
				
				if(!$Name):
					$status 	= false;
					$message 	= "Name cann't be null ";
					break;
				elseif(!$Code):
					$status 	= false;
					$message 	= "Code cann't be null";
					break;
				endif;

			}

			$arrData = array();
			if($status):
				for ($row = 2; $row <= $highestRow; $row++){
					$rowData 		= $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);

					$Code 			= $rowData[0][0];
					$Name 			= $rowData[0][1];
					$Contact 		= $rowData[0][2];
					$City 			= $rowData[0][3];
					$Address 		= $rowData[0][4];
					$Remark 		= $rowData[0][5];

					$data = array(
						"Code"		=> $Code,
						"Name"		=> $Name,
						"Contact"	=> $Contact,
						"City"		=> $City,
						"Address"	=> $Address,
						"Remark"	=> $Remark,
						"Status"	=> 1,
						"CompanyID"	=> $CompanyID,
					);

					$cek = $this->db->count_all("PS_Sales where CompanyID = '$CompanyID' and Code = '$Code'");
					if($cek>0):
						$data['User_Ch'] = $this->session->NAMA;
						$data['Date_Ch'] = date("Y-m-d H:i:s");
						$this->db->where("CompanyID", $CompanyID);
						$this->db->where("Code", $Code);
						$this->db->update("PS_Sales", $data);
					else:
						$data['User_Add'] = $this->session->NAMA;
						$data['Date_Add'] = date("Y-m-d H:i:s");
						$this->db->insert("PS_Sales", $data);
					endif;

					$arrData[] = $data;
				}
			endif;

			$output = array(
				"status" 	=> $status,
				"message"	=> $message,
				"hak_akses"	=> $this->session->hak_akses,
			);

			if (!unlink($inputFileName)):

			endif;
		endif;

		$this->main->echoJson($output);
	}

	public function import(){
		// $this->import2();
		$this->import_sales();
	}

	private function import_sales(){
		$fileName                 = $this->input->post('file', TRUE);
		$CompanyID 				  = $this->session->CompanyID;
		$userid 				  = $this->session->id_user;
		$folder 				  = $this->main->create_folder_sales();
		$config['upload_path']    = './'.$folder; 
		$config['file_name']      = $fileName;
		$config['allowed_types']  = 'xls|xlsx|csv|ods|ots';
		$config['max_size']       = 10000;
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if (!$this->upload->do_upload('file')):
			$output = array(
				"status" 	=> false,
				"message"	=> "Please Upload Excel File",
				"hak_akses"	=> $this->session->hak_akses,
			);
		else:
			$media 			= $this->upload->data();
			$inputFileName 	= $folder.$media['file_name'];
			try {
				$inputFileType  = IOFactory::identify($inputFileName);
				$objReader      = IOFactory::createReader($inputFileType);
				$objPHPExcel  	= $objReader->load($inputFileName);
			}
			catch(Exception $e) {
				die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
			}
			
			$sheet         = $objPHPExcel->getSheet(0);
			$highestRow    = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();

			$status    = true;
			$message   = "success";
			$arrData   = array();
			$arrHeader = array(); 
			$rowData   = $sheet->rangeToArray('A' . 1 . ':' . $highestColumn . 1,NULL,TRUE,FALSE);
			if($rowData): $arrHeader = $rowData; endif;
			$total_data = 0;

			for ($row = 2; $row <= $highestRow; $row++){
				$total_data += 1;
				$rowData 		= $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);

				if(count($rowData[0]) == 6):
					$status_sales = true;

					$Code 			= $this->main->checkInputData($rowData[0][0]);
					$Name 			= $this->main->checkInputData($rowData[0][1]);
					$Contact 		= $this->main->checkInputData($rowData[0][2]);
					$City 			= $this->main->checkInputData($rowData[0][3]);
					$Address 		= $this->main->checkInputData($rowData[0][4]);
					$Remark 		= $this->main->checkInputData($rowData[0][5]);
					$Message 		= '';

					$status_data = "insert";
					$ck_code = $this->db->count_all("PS_Sales where CompanyID = '$CompanyID' and Code = '$Code'");
					if(!$Code):
			        	$status_data = "insert";
			        elseif($ck_code>0):
			        	$status_data = "update";
			        elseif($ck_code<=0):
			        	$status_data = "insert";
			        endif;

			        if($ck_code>0):
			        	$ck_active = $this->db->count_all("PS_Sales where CompanyID = '$CompanyID' and Code = '$Code' and Status = '1'");
			        	if($ck_active<=0):
			        		$status_sales = false;
							$Message 		.= "- ".$this->lang->line('lb_sales_inactive')." <br>";
			        	endif;
			        endif;
			        if(!$Name):
			        	$status_sales = false;
						$Message 		.= "- ".$this->lang->line('lb_name_empty')." <br>";
					endif;

					$h = array(
				    	"status"		=> $status_sales,
				    	"status_data"	=> $status_data,
				    	"Code"  		=> $Code,
				    	"Name" 			=> $Name,
				    	"Contact"		=> $Contact,
				    	"City"			=> $City,
				    	"Address"		=> $Address,
				    	"Remark"		=> $Remark,
				    	"Message" 		=> $Message,
				    );

				    array_push($arrData, $h);

				else:
					$status  = false;
					$message = $this->lang->line('lb_column_not_match').".";
				endif;
			}

			if($total_data<=0):
				$status = false;
				$message = $this->lang->line('lb_data_not_found');
			endif;

			$output = array(
				"status" 	 	=> $status,
				"message"		=> $message,
				"hak_akses"	 	=> $this->session->hak_akses,
				"data"		 	=> $arrData,
				"header"	 	=> $arrHeader,
				"inputFileName" => $inputFileName,
			);

		endif;

		$this->main->echoJson($output);
	}

	public function save_import(){
		$CompanyID 	= $this->session->CompanyID;
		$filename 	= $this->input->post("filename");
		if(is_file("./".$filename)):
			$inputFileName 	= $filename;
			try {
				$inputFileType  = IOFactory::identify($inputFileName);
				$objReader      = IOFactory::createReader($inputFileType);
				$objPHPExcel  	= $objReader->load($inputFileName);
			}
			catch(Exception $e) {
				die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
			}

			$sheet         = $objPHPExcel->getSheet(0);
			$highestRow    = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();

			$status    = true;
			$message   = "success";

			for ($row = 2; $row <= $highestRow; $row++){
				$rowData 		= $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
				if(count($rowData[0]) == 6):
					$status_sales = true;

					$Code 			= $this->main->checkInputData($rowData[0][0]);
					$Name 			= $this->main->checkInputData($rowData[0][1]);
					$Contact 		= $this->main->checkInputData($rowData[0][2]);
					$City 			= $this->main->checkInputData($rowData[0][3]);
					$Address 		= $this->main->checkInputData($rowData[0][4]);
					$Remark 		= $this->main->checkInputData($rowData[0][5]);

					$ck_code = $this->db->count_all("PS_Sales where CompanyID = '$CompanyID' and Code = '$Code'");
					if($ck_code>0):
			        	$ck_active = $this->db->count_all("PS_Sales where CompanyID = '$CompanyID' and Code = '$Code' and Status = '1'");
			        	if($ck_active<=0):
			        		$status_sales = false;
			        	endif;
			        endif;
					if(!$Name):
			        	$status_sales = false;
					endif;

					if($status_sales):
						$data = array(
							"Name"		=> $Name,
							"Contact"	=> $Contact,
							"City"		=> $City,
							"Address"	=> $Address,
							"Remark"	=> $Remark,
							"Status"	=> 1,
							"CompanyID"	=> $CompanyID,
						);

						if($ck_code>0):
							$data['User_Ch'] = $this->session->NAMA;
							$data['Date_Ch'] = date("Y-m-d H:i:s");
							$this->db->where("CompanyID", $CompanyID);
							$this->db->where("Code", $Code);
							$this->db->update("PS_Sales", $data);
						else:
							if(!$Code):
								$Code = $this->main->sales_generate();
								$data['CodeType'] = 0;
							else:
								$data['CodeType'] = 1;
							endif;
							$data['Code']	  = $Code;
							$data['User_Add'] = $this->session->NAMA;
							$data['Date_Add'] = date("Y-m-d H:i:s");
							$this->db->insert("PS_Sales", $data);
							$SalesID = $this->db->insert_id();
						endif;

					endif;

				else:
					$status  = false;
					$message = $this->lang->line('lb_column_not_match');
				endif;
			}

		else:
			$status  = false;
			$message = $this->lang->line('lb_file_not_found1');
		endif;

		$output = array(
			"status" 	=> $status,
			"message"	=> $message,
			"hakakses"	=> $this->session->hak_akses,
		);

		$this->main->echoJson($output);
	}
}