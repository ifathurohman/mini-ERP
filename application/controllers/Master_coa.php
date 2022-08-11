<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_coa extends CI_Controller {
	var $title = 'Chart of Account';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_coa",'coa');
		$this->load->library(array('PHPExcel','IOFactory'));
		$this->main->cek_session();
	}

	public function index()
	{
		$this->main->cek_session();
		$url 	= $this->uri->segment(1); 
		$id_url = $this->main->id_menu($url);
		$read 	= $this->main->read($id_url);
		$tambah = ""; 
		if($read == 0){ redirect(); }
		$tambah_coa = $this->main->menu_tambah($id_url);
		if($tambah_coa > 0):
            $tambah = $this->main->general_button('add','Add New '.$this->title);
		endif;
		#ini untuk session halaman aturan user privileges;
		$data['title']  	= $this->title;
		$data['tambah'] 	= $tambah;
		$data['page'] 		= 'coa/list';
		$data['modal'] 		= 'coa/modal';
		$data['modul'] 		= 'master_coa';
		$data['url_modul'] 	= $url;
		$this->load->view('index',$data);
	}

	public function ajax_list($url_modul="",$modul=""){
		$id_url = $this->main->id_menu($url_modul);
		$edit 	= $this->main->menu_ubah($id_url);
		$delete = $this->main->menu_hapus($id_url);
		$list 	= $this->coa->get_datatables();
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		foreach ($list as $a) {
			$btn_edit 	= '';
			$btn_delete = '';
			$active 	= '';

			if($edit>0):
				$btn_edit = $this->main->button_action("edit", $a->COAID);
			endif;

			if($delete>0):
				if($a->Active == 1):
					$btn_delete = $this->main->button_action("delete2", $a->COAID);
				else:
					$btn_delete = $this->main->button_action("undelete", $a->COAID);
				endif;
			endif;

			if($a->Active == 0):
            	$active = '<i class="icon fa-trash pull-right" aria-hidden="true"></i>';
            endif;

			$button  = '<div class="btn-group btn-group-xs" aria-label="Basic example" role="group">';
            $button .= $btn_edit;
            $button .= $btn_delete;
            $button .= '</div>';

            $code = '<a href="javascript:void(0)" type="button" class="" title="View Detail" onclick="view_coa('."'".$a->COAID."'".')">'.$a->Code.'</a>';

			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $code.$active;
			$row[] 	= $a->Name;
			$row[] 	= $a->Position;
			$row[] 	= $a->parentName;
			$row[] 	= $a->Remark;
			// $row[] 	= $button;
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->coa->count_all(),
			"recordsFiltered" => $this->coa->count_filtered(),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function save(){
		$this->_validate();
		$CompanyID 	= $this->session->CompanyID;
		$crud  		= $this->input->post("crud");
		$COAID 		= $this->input->post("COAID");
		$Name 		= $this->input->post("Name");
		$Code 		= $this->input->post("Code");
		$Level 		= $this->input->post("Level");
		$ParentID 	= $this->input->post("ParentID");
		$Remark 	= $this->input->post("Remark");
		$PaymentType= $this->input->post('PaymentType');

		if($Level == 1):
			$ParentID = 0;
		endif;

		$data = array(
			"Code" 			=> $Code,
			"Name" 			=> $Name,
			"ParentID" 		=> $ParentID,
			"Remark" 		=> $Remark,
		);

		if($crud == "insert"):
			$data['Position']	= $Level;
			$data['Active'] 	= 1;
			$data['CompanyID'] 	= $this->session->CompanyID;
			$this->coa->save($data);
		else:
			// cek ke coa setting jika ada datanya update cValue
			$cek = $this->db->count_all("UT_Rule where nValue = '$COAID' and CompanyID = '$CompanyID'");
			if($cek>0):
				$data_setting = array(
					"cValue"	=> $Code,
				);
				$this->db->where("CompanyID", $CompanyID);
				$this->db->where("nValue", $COAID);
				$this->db->update("UT_Rule", $data_setting);
			endif;
			$used 	= $this->db->count_all("AC_COA where CompanyID = '$CompanyID' and ParentID = '$COAID' and Active = '1'");
			if($used<=0):
				$data['Position']	= $Level;
			endif;
			$this->coa->update(array("COAID" => $COAID), $data);
		endif;

		$res = array(
			'status' 	=> true,
			'message' 	=> 'Success',
		);
		$this->main->echoJson($res);

	}

	private function _validate(){
		$crud  	= $this->input->post("crud");
		$COAID 	= $this->input->post("COAID");
		$Name 	= $this->input->post("Name");
		$Code 	= $this->input->post("Code");
		$Level 	= $this->input->post("Level");
		$Parent = $this->input->post("ParentID");
		$Remark = $this->input->post("Remark");
		$CompanyID = $this->session->CompanyID;

		$data 					= array();
		$data['error_string'] 	= array();
		$data['inputerror'] 	= array();
		$data['status'] 		= TRUE;
		
		if($Name == ''):
			$data['inputerror'][] 	= 'Name';
			$data['error_string'][] = 'Name cannot be null';
			$data['status'] 		= FALSE;
		else:
			// if($crud == "insert"):
			// 	$cek = $this->db->count_all("AC_COA where Name = '$Name' and CompanyID = '$CompanyID'");
			// 	if($cek>0):
			// 		$data['inputerror'][] 	= 'Name';
			// 		$data['error_string'][] = 'Name has been already exist';
			// 		$data['status'] 		= FALSE;
			// 	endif;
			// else:
			// 	$cek = $this->db->count_all("AC_COA where Name = '$Name' and CompanyID = '$CompanyID' and COAID != '$COAID'");
			// 	if($cek>0):
			// 		$data['inputerror'][] 	= 'Name';
			// 		$data['error_string'][] = 'Name has been already exist';
			// 		$data['status'] 		= FALSE;
			// 	endif;
			// endif;
		endif;
		if($Code == ''):
			$data['inputerror'][] 	= 'Code';
			$data['error_string'][] = 'Code cannot be null';
			$data['status'] 		= FALSE;
		else:
			if($crud == "insert"):
				$cek = $this->db->count_all("AC_COA where Code = '$Code' and CompanyID = '$CompanyID'");
				if($cek>0):
					$data['inputerror'][] 	= 'Code';
					$data['error_string'][] = 'Code has been already exist';
					$data['status'] 		= FALSE;
				endif;
			else:
				$cek = $this->db->count_all("AC_COA where Code = '$Code' and CompanyID = '$CompanyID' and COAID != '$COAID'");
				if($cek>0):
					$data['inputerror'][] 	= 'Code';
					$data['error_string'][] = 'Code has been already exist';
					$data['status'] 		= FALSE;
				endif;
			endif;
		endif;
		if($Level != 1):
			if($Parent == "none"):
				$data['inputerror'][] 	= 'ParentID';
				$data['error_string'][] = 'ParentID cannot be null';
				$data['status'] 		= FALSE;
			endif;
		endif;

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	public function ajax_edit($id){
		$CompanyID = $this->session->CompanyID;
		$data   = $this->coa->get_by_id($id);
		$edit   = $this->main->button_action("edit2",$id);
		$delete = $this->main->button_action("delete4",$id);
		$used 	= $this->db->count_all("AC_COA where CompanyID = '$CompanyID' and ParentID = '$id' and Active = '1'");
		
		if($data->Active == 0):
			$delete 	 = $this->main->button_action("undelete2",$id);
		elseif($used>0):
			$delete = '';
		endif;
		if($data->Active == 0):
			$edit 	 	 = '';
		endif;
		$output = array(
			"data" 		  => $data,
			"edit" 	 	  => $edit,
			"delete" 	  => $delete,
			"used"		  => $used,
			"hakakses"	  => $this->session->hak_akses,
		);
		echo json_encode($output);
	}

	public function ajax_active($id){
		$a = $this->coa->get_by_id($id);
		if($a):
			$active = 0;
			$title 	= "Delete!";
			if($a->Active != 1):
				$active = 1;
				$title 	= "Undelete!";
			endif;
			$data = array("Active" => $active);
			$this->coa->update(array("COAID" => $id), $data);
			$status  = true;
			$message = "Your data has been deleted.";
		else:
			$title 	= "";
			$status  = FALSE;
			$message = "Error deleting data";
		endif;

		$res = array("status" => $status,"message"=>$message,"title" => $title);
		$this->main->echoJson($res);
	}

	public function export($page=""){
		$list = $this->api->coa_select("active","all");
		$file_name = "SampleCOA".date("Ymd_His").".xls";
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
        foreach(range('A','E') as $columnID):
	        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
	    endforeach;
	    $objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValue('A1', 'Code')
			            ->setCellValue('B1', 'Name')
			            ->setCellValue('C1', 'Level')
			            ->setCellValue('D1', 'Parent COA')
			            ->setCellValue('E1', 'Remark');
		if($page != "template"):
			foreach ($list as $a):                
		      	$no              	= $i++; 
		      	$urut            	= $ii++;
		      	$objPHPExcel->setActiveSheetIndex(0)
		                  ->setCellValue('A'.$urut, $a->Code)
		                  ->setCellValue('B'.$urut, $a->Name)
		                  ->setCellValue('C'.$urut, $a->Level)
		                  ->setCellValue('D'.$urut, $a->parentCode)
		                  ->setCellValue('E'.$urut, $a->Remark);
		    endforeach;
		endif;

	    // Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Template COA');
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
				"message"	=> "import data failed",
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
			// $list 		= array();

			$status 	= true;
			$message 	= "Success";

			for ($row = 2; $row <= $highestRow; $row++){
				$rowData 		= $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);

				$Code 			= $rowData[0][0];
				$Name 			= $rowData[0][1];
				$Level 			= $rowData[0][2];
				$Parent 		= $rowData[0][3];
				$Remark 		= $rowData[0][4];

				if($Level>4):
					$status 	= false;
					$message 	= "Level COA max 4 ";
					break;
				elseif($Level<=0 or !$Level):
					$status 	= false;
					$message 	= "Level COA min 1";
					break;
				elseif(!$Name):
					$status 	= false;
					$message 	= "Name cann't be null";
					break;
				elseif($Level != 1 and !$Parent):
					$status 	= false;
					$message 	= "Parent cann't be null";
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
					$Level 			= $rowData[0][2];
					$Parent 		= $rowData[0][3];
					$Remark 		= $rowData[0][4];

					$data_parent 	= $this->api->coa_list("detail",$Parent,"code");
					$Parent 		= null;
					if(count($data_parent)>0):
						$Parent 	= $data_parent->ID;
					endif;

					$data = array(
						"CompanyID"	=> $CompanyID,
						"Code"		=> $Code,
						"Name"		=> $Name,
						"Position" 	=> $Level,
						"ParentID"	=> $Parent,
						"Remark"	=> $Remark,
						"Active"	=> 1,
					);

					$cek = $this->db->count_all("AC_COA where Code = '$Code' and CompanyID = '$CompanyID'");
					if($cek>0):
						$data['UserCh'] = $this->session->NAMA;
						$data['DateCh'] = date("Y-m-d H:i:s");
						$this->db->where("CompanyID", $CompanyID);
						$this->db->where("Code", $Code);
						$this->db->update("AC_COA", $data);
						$COAID = $this->main->get_one_column("AC_COA","COAID", array("Code" => $Code, "CompanyID" => $CompanyID))->COAID;
					else:
						$data['UserAdd'] = $this->session->NAMA;
						$data['DateAdd'] = date("Y-m-d H:i:s");
						$this->db->insert("AC_COA", $data);
						$COAID = $this->db->insert_id();
					endif;


					// update ke coa setting
					$cek_coa_setting = $this->db->count_all("UT_Rule where CompanyID = '$CompanyID' and cValue = '$Code'");
					if($cek_coa_setting>0):
						$data_setting = array("nValue" => $COAID);
						$this->db->where("CompanyID", $CompanyID);
						$this->db->where("cValue", $Code);
						$this->db->update("UT_Rule", $data_setting);
					endif;
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
		$this->import_coa();
	}

	private function import_coa(){
		$fileName                 = $this->input->post('file', TRUE);
		$CompanyID 				  = $this->session->CompanyID;
		$userid 				  = $this->session->id_user;
		$folder 				  = $this->main->create_folder_coa();
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

			$arrCoa1 = array();
			$arrCoa2 = array();
			$arrCoa3 = array();
			$arrCoa4 = array();

			for ($row = 2; $row <= $highestRow; $row++){
				$rowData 		= $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
				if(count($rowData[0]) == 5):
					$Code 			= $this->main->checkInputData($rowData[0][0]);
					$Level 			= $this->main->checkInputData($rowData[0][2]);
					$Parent 		= $this->main->checkInputData($rowData[0][3]);
					if($Level == 1):
						if(!in_array($Code, $arrCoa1)): array_push($arrCoa1, $Code); endif;
					elseif($Level == 2):
						if(!in_array($Code, $arrCoa2)): array_push($arrCoa2, $Code); endif;
					elseif($Level == 3):
						if(!in_array($Code, $arrCoa3)): array_push($arrCoa3, $Code); endif;
					elseif($Level == 4):
						if(!in_array($Code, $arrCoa4)): array_push($arrCoa4, $Code); endif;
					endif;
				endif;
			}

			for ($row = 2; $row <= $highestRow; $row++){
				$total_data += 1;
				$rowData 		= $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
				if(count($rowData[0]) == 5):
					$status_coa = true;

					$Code 			= $this->main->checkInputData($rowData[0][0]);
					$Name 			= $this->main->checkInputData($rowData[0][1]);
					$Level 			= $this->main->checkInputData($rowData[0][2]);
					$Parent 		= $this->main->checkInputData($rowData[0][3]);
					$Remark 		= $this->main->checkInputData($rowData[0][4]);
					$Message 		= '';

					$status_data = "insert";
					$ck_code = $this->db->count_all("AC_COA where CompanyID = '$CompanyID' and Code = '$Code'");
					if(!$Code):
			        	$status_data = "insert";
			        elseif($ck_code>0):
			        	$status_data = "update";
			        elseif($ck_code<=0):
			        	$status_data = "insert";
			        endif;

					if(!$Code):
						$status_coa 	= false;
						$Message 		.= "- "."Code can't be null <br>";
					elseif($ck_code>0):
						$ck_levelnya 	= $this->db->count_all("AC_COA where CompanyID = '$CompanyID' and Code = '$Code' and Position = '$Level'");
						$getCOAID		= $this->main->get_one_column("AC_COA","COAID",array("CompanyID" => $CompanyID, "Code" => $Code))->COAID;
						$ck_parentnya 	= $this->db->count_all("AC_COA where CompanyID = '$CompanyID' and ParentID = '$getCOAID' and Active = '1'");
						$ck_kasbank 	= $this->db->count_all("AC_KasBank_Det where CompanyID = '$CompanyID' and COAID = '$getCOAID'");
						$ck_active 		= $this->db->count_all("AC_COA where CompanyID = '$CompanyID' and Code = '$Code' and Active = '1'");
						if($ck_levelnya<=0 && $ck_parentnya>0):
							$status_coa 	= false;
							// $Message 		.= "- "."Can't change level, because Coa Code has been used as Parent <br>";
							$Message 		.= "- "."Coa Code has been used as Parent, can't be changed <br>";
						endif;
						if($ck_levelnya<=0 && $ck_kasbank>0):
							$status_coa 	= false;
							// $Message 		.= "- "."Can't change level, because Coa Code has been used as Transaction Journal <br>";
							$Message 		.= "- "."Data is in use, can't be changed <br>";
						endif;
						if($ck_active<=0):
							$status_coa 	= false;
							$Message 		.= "- "."Coa Codee has been inactive <br>";
						endif;
					endif;
					if(!$Name):
						$status_coa 	= false;
						$Message 		.= "- "."Name can't be null <br>";
					endif;
					if($Level>4):
						$status_coa 	= false;
						$Message 		.= "- "."Level COA max 4 <br>";
					endif;
					if($Level<=0 or !$Level):
						$status_coa 	= false;
						$Message 		.= "- "."Level COA min 1 <br>";
					endif;
					if($Level != 1 and !$Parent and $Level <= 4):
						$status_coa 	= false;
						$level2 	= $Level - 1;
						$Message 		.= "- "."Level ".$Level." need Parent Code level ".$level2."<br>";
					endif;
					$ck_ParentID = 0;
					if(is_numeric($Level) and $Level > 1):
						$level2 = $Level - 1;
						$ck_ParentID = $this->main->get_one_column("AC_COA","COAID",array("CompanyID" => $CompanyID, "Code" => $Parent, "Position" => $level2));
					endif;
					if($Level == 2):
						$ParentID = 0;
						if($ck_ParentID):
							$ParentID = $ck_ParentID->COAID;
						endif;
						if(!$ParentID and !in_array($Parent,$arrCoa1)):
							$status_coa 	= false;
							$level2 		= $Level - 1;
							$Message 		.= "- "."Parent COA Level ".$level2." not entry in Database or Excell import <br>";
						endif;
					endif;
					if($Level == 3):
						$ParentID = 0;
						if($ck_ParentID):
							$ParentID = $ck_ParentID->COAID;
						endif;
						if(!$ParentID and !in_array($Parent,$arrCoa2)):
							$status_coa 	= false;
							$level2 		= $Level - 1;
							$Message 		.= "- "."Parent COA Level ".$level2." not entry in Database or Excell import <br>";
						endif;
					endif;
					if($Level == 4):
						$ParentID = 0;
						if($ck_ParentID):
							$ParentID = $ck_ParentID->COAID;
						endif;
						if(!$ParentID and !in_array($Parent,$arrCoa3)):
							$status_coa 	= false;
							$level2 		= $Level - 1;
							$Message 		.= "- "."Parent COA Level ".$level2." not entry in Database or Excell import <br>";
						endif;
					endif;

					$h = array(
				    	"status"		=> $status_coa,
				    	"status_data"	=> $status_data,
				    	"Code"  		=> $Code,
				    	"Name" 			=> $Name,
				    	"Level"			=> $Level,
				    	"Parent"		=> $Parent,
				    	"Remark"		=> $Remark,
				    	"Message" 		=> $Message,
				    );

				    array_push($arrData, $h);

				else:
					$status  = false;
					$message = "Column not match.";
				endif;
			}

			if($total_data<=0):
				$status = false;
				$message = 'Data Not Found';
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

	// 20190718 MW
	// import coa
	// penjelasan validasi ada di fungsi import_coa()
	// bisa di pahami dengan membaca source nya.
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

			$arrCoa1 = array();
			$arrCoa2 = array();
			$arrCoa3 = array();
			$arrCoa4 = array();

			for ($row = 2; $row <= $highestRow; $row++){
				$rowData 		= $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
				if(count($rowData[0]) == 5):
					$Code 			= $this->main->checkInputData($rowData[0][0]);
					$Level 			= $this->main->checkInputData($rowData[0][2]);
					$Parent 		= $this->main->checkInputData($rowData[0][3]);
					if($Level == 1):
						if(!in_array($Code, $arrCoa1)): array_push($arrCoa1, $Code); endif;
					elseif($Level == 2):
						if(!in_array($Code, $arrCoa2)): array_push($arrCoa2, $Code); endif;
					elseif($Level == 3):
						if(!in_array($Code, $arrCoa3)): array_push($arrCoa3, $Code); endif;
					elseif($Level == 4):
						if(!in_array($Code, $arrCoa4)): array_push($arrCoa4, $Code); endif;
					endif;
				endif;
			}

			for ($row = 2; $row <= $highestRow; $row++){
				$rowData 		= $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
				if(count($rowData[0]) == 5):
					$status_coa = true;

					$Code 			= $this->main->checkInputData($rowData[0][0]);
					$Name 			= $this->main->checkInputData($rowData[0][1]);
					$Level 			= $this->main->checkInputData($rowData[0][2]);
					$Parent 		= $this->main->checkInputData($rowData[0][3]);
					$Remark 		= $this->main->checkInputData($rowData[0][4]);

					$ck_code = $this->db->count_all("AC_COA where CompanyID = '$CompanyID' and Code = '$Code'");

					if(!$Code):
						$status_coa 	= false;
					elseif($ck_code>0):
						$ck_levelnya 	= $this->db->count_all("AC_COA where CompanyID = '$CompanyID' and Code = '$Code' and Position = '$Level'");
						$getCOAID		= $this->main->get_one_column("AC_COA","COAID",array("CompanyID" => $CompanyID, "Code" => $Code))->COAID;
						$ck_parentnya 	= $this->db->count_all("AC_COA where CompanyID = '$CompanyID' and ParentID = '$getCOAID' and Active = '1'");
						$ck_kasbank 	= $this->db->count_all("AC_KasBank_Det where CompanyID = '$CompanyID' and COAID = '$getCOAID'");
						$ck_active 		= $this->db->count_all("AC_COA where CompanyID = '$CompanyID' and Code = '$Code' and Active = '1'");
						if($ck_levelnya<=0 && $ck_parentnya>0):
							$status_coa 	= false;
						endif;
						if($ck_levelnya<=0 && $ck_kasbank>0):
							$status_coa 	= false;
						endif;
						if($ck_active<=0):
							$status_coa 	= false;
						endif;
					endif;
					if(!$Name):
						$status_coa 	= false;
					endif;
					if($Level>4):
						$status_coa 	= false;
					endif;
					if($Level<=0 or !$Level):
						$status_coa 	= false;
					endif;
					if($Level != 1 and !$Parent and $Level <= 4):
						$status_coa 	= false;
						$level2 	= $Level - 1;
					endif;
					$ck_ParentID = 0;
					if(is_numeric($Level) and $Level > 1):
						$level2 = $Level - 1;
						$ck_ParentID = $this->main->get_one_column("AC_COA","COAID",array("CompanyID" => $CompanyID, "Code" => $Parent, "Position" => $level2));
					endif;
					if($Level == 2):
						$ParentID = 0;
						if($ck_ParentID):
							$ParentID = $ck_ParentID->COAID;
						endif;
						if(!$ParentID and !in_array($Parent,$arrCoa1)):
							$status_coa 	= false;
							$level2 		= $Level - 1;
						endif;
					endif;
					if($Level == 3):
						$ParentID = 0;
						if($ck_ParentID):
							$ParentID = $ck_ParentID->COAID;
						endif;
						if(!$ParentID and !in_array($Parent,$arrCoa2)):
							$status_coa 	= false;
							$level2 		= $Level - 1;
						endif;
					endif;
					if($Level == 4):
						$ParentID = 0;
						if($ck_ParentID):
							$ParentID = $ck_ParentID->COAID;
						endif;
						if(!$ParentID and !in_array($Parent,$arrCoa3)):
							$status_coa 	= false;
							$level2 		= $Level - 1;
						endif;
					endif;

					if($status_coa):

						$data = array(
							"CompanyID"	=> $CompanyID,
							"Code"		=> $Code,
							"Name"		=> $Name,
							"Position" 	=> $Level,
							"Remark"	=> $Remark,
							"Active"	=> 1,
						);

						if($Level != 1):
							$data['ParentID'] = $ParentID;
						endif;

						if($ck_code>0):
							$data['UserCh'] = $this->session->NAMA;
							$data['DateCh'] = date("Y-m-d H:i:s");
							$this->db->where("CompanyID", $CompanyID);
							$this->db->where("Code", $Code);
							$this->db->update("AC_COA", $data);
							$COAID = $this->main->get_one_column("AC_COA","COAID", array("Code" => $Code, "CompanyID" => $CompanyID))->COAID;
						else:
							$data['UserAdd'] = $this->session->NAMA;
							$data['DateAdd'] = date("Y-m-d H:i:s");
							$this->db->insert("AC_COA", $data);
							$COAID = $this->db->insert_id();
						endif;

						// update ke coa setting
						$cek_coa_setting = $this->db->count_all("UT_Rule where CompanyID = '$CompanyID' and nValue = '$COAID'");
						if($cek_coa_setting>0):
							$data_setting = array("cValue" => $Code);
							$this->db->where("CompanyID", $CompanyID);
							$this->db->where("nValue", $COAID);
							$this->db->update("UT_Rule", $data_setting);
						endif;

					endif;

				else:
					$status  = false;
					$message = "Column not match.";
				endif;
			}
		else:
			$status  = false;
			$message = "File not found, please reupload import file";
		endif;

		$output = array(
			"status" 	=> $status,
			"message"	=> $message,
			"hakakses"	=> $this->session->hak_akses,
		);

		$this->main->echoJson($output);
	}
}