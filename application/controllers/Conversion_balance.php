<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Conversion_balance extends CI_Controller {
	var $title = 'Conversion Balance';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_conversion_balance",'conversion');
		$this->load->library(array('PHPExcel','IOFactory'));
		$this->main->cek_session();
	}

	public function index(){
		$url 	= $this->uri->segment(1); 
		$id_url = $this->main->id_menu($url);
		$tambah = '<button type="button" class="btn btn-blue btn-add save" onclick="tambah()" >Edit '.$this->title.'</button>';
		#ini untuk session halaman aturan user privileges;
		$data['title']  		= $this->title;
		$data['tambah'] 		= $tambah;
		$data['page'] 			= 'conversion_balance/list';
		$data['modul'] 			= 'conversion_balance';
		$data['url_modul'] 		= $url;
		$this->load->view('index',$data);
	}

	public function ajax_list($url_modul="",$modul=""){
		$id_url = $this->main->id_menu($url_modul);
		$list 	= $this->conversion->get_datatables();
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		$page 	= $this->input->post('page');
		foreach ($list as $a) {
			
			$item_debit  = '<span class="d-text">0</span>';
			$item_credit = '<span class="d-text">0</span>';
			if($page == "add"):
				$item_debit   = '<input type="text" name="debit[]" class="duit d-input" value="0">';
				$item_credit  = '<input type="text" name="credit[]" class="duit d-input" value="0">';
			endif;

			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $a->Code;
			$row[] 	= $a->Name;
			$row[] 	= $a->Position;
			$row[] 	= $a->parentName;
			$row[] 	= $item_debit;
			$row[] 	= $item_credit;
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->conversion->count_all(),
			"recordsFiltered" => $this->conversion->count_filtered(),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function save(){

		$this->_validate();

		$CompanyID 	= $this->session->CompanyID;
		$COAID 		= $this->input->post("COAID");
		$debit 		= $this->input->post("debit");
		$credit 	= $this->input->post("credit");
		$Date 		= $this->input->post('Date');
		$totalCredit 	= $this->input->post('totalCredit');
		$totalDebit 	= $this->input->post('totalDebit');

		$code = $this->main->kas_bank_generate(0);
		$data = array(
			"KasBankNo"		=> $code,
			"CompanyID"		=> $CompanyID,
			"Date"			=> $Date,
			"DebitTotal" 	=> $this->main->checkDuitInput($totalDebit),
			"CreditTotal" 	=> $this->main->checkDuitInput($totalCredit),
			"Type"			=> 0,
		);
		$this->conversion->save($data);

		foreach ($COAID as $k => $v) {
			$codedet = $this->main->kas_bank_det_generate();

			$data_det = array(
				"KasBankDetNo"	=> $codedet,
				"CompanyID"		=> $CompanyID,
				"KasBankNo"		=> $code,
				"COAID"			=> $COAID[$k],
				"Debit"			=> $this->main->checkDuitInput($debit[$k]),
				"Credit"		=> $this->main->checkDuitInput($credit[$k]),
			);
			$this->conversion->save_det($data_det);
		}

		$output = array(
			"status" 	=> true,
			"hak_akses"	=> $this->session->hak_akses,
			"message" 	=> "success",
			"post"		=> $COAID,
		);

		$this->main->echoJson($output);
	}

	private function _validate(){
		$data = array();
		$data['status'] = TRUE;

		$COAID 		= $this->input->post("COAID");
		$debit 		= $this->input->post("debit");
		$credit 	= $this->input->post("credit");

		$totalCredit = 0;
		$totalDebit  = 0;

		foreach ($COAID as $k => $v) {
			$xdebit 	= $this->main->checkDuitInput($debit[$k]);
			$xcredit 	= $this->main->checkDuitInput($credit[$k]);

			$totalCredit += $xcredit;
			$totalDebit += $xdebit;
		}

		if($totalCredit != $totalDebit):
			$data['inputerror'][] 	= '';
			$data['error_string'][] = '';
			$data['message'] 		= $this->lang->line('lb_debit_credit_not_same');
			$data['status'] 		= FALSE;
		endif;

		if($data['status'] === FALSE)
        {
            header('Content-Type: application/json');
            echo json_encode($data,JSON_PRETTY_PRINT);  
            exit();
        }
	}

	public function export_example(){
		$list = $this->api->coa_select("active",4);
		$file_name = "SampleConversionBalance".date("Ymd_His").".xls";
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
        foreach(range('A','G') as $columnID):
	        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
	    endforeach;
	    $objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValue('A1', 'ID')
			            ->setCellValue('B1', 'Code')
			            ->setCellValue('C1', 'Name')
			            ->setCellValue('D1', 'Level')
			            ->setCellValue('E1', 'Parent COA')
			            ->setCellValue('F1', 'Debit')
			            ->setCellValue('G1', 'Credit');
		foreach ($list as $a):                
	      	$no              	= $i++; 
	      	$urut            	= $ii++;
	      	$objPHPExcel->setActiveSheetIndex(0)
	                  ->setCellValue('A'.$urut, $a->ID)
	                  ->setCellValue('B'.$urut, $a->Code)
	                  ->setCellValue('C'.$urut, $a->Name)
	                  ->setCellValue('D'.$urut, $a->Level)
	                  ->setCellValue('E'.$urut, $a->parentName)
	                  ->setCellValue('F'.$urut, 0)
	                  ->setCellValue('G'.$urut, 0);
	    endforeach;

	    // Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Template Conversion Balance');
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

	public function export(){
		$list = $this->api->coa_select("active",4,"active");
		$file_name = "ConversionBalance.xls";
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
        foreach(range('A','G') as $columnID):
	        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
	    endforeach;
	    $objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValue('A1', 'ID')
			            ->setCellValue('B1', 'Code')
			            ->setCellValue('C1', 'Name')
			            ->setCellValue('D1', 'Level')
			            ->setCellValue('E1', 'Parent COA')
			            ->setCellValue('F1', 'Debit')
			            ->setCellValue('G1', 'Credit');
		foreach ($list as $a):                
	      	$no              	= $i++; 
	      	$urut            	= $ii++;
	      	$objPHPExcel->setActiveSheetIndex(0)
	                  ->setCellValue('A'.$urut, $a->ID)
	                  ->setCellValue('B'.$urut, $a->Code)
	                  ->setCellValue('C'.$urut, $a->Name)
	                  ->setCellValue('D'.$urut, $a->Level)
	                  ->setCellValue('E'.$urut, $a->parentName)
	                  ->setCellValue('F'.$urut, (float) $a->debit)
	                  ->setCellValue('G'.$urut, (float) $a->credit);
	    endforeach;

	    // Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Conversion Balance');
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

	public function import(){

		$fileName                 = $this->input->post('file', TRUE);
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
			$list_coa 		= $this->api->coa_select("active",4,"active");
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
			$arrID 		= array();

			$status 	= true;
			$message 	= "Success";
			
			for ($row = 2; $row <= $highestRow; $row++){
				$rowData 		= $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
				if(count($rowData[0])<7):
					break;
				endif;
				$ID       		= $rowData[0][0];
				$Code 			= $rowData[0][1];
				$Name 			= $rowData[0][2];
				$Level 			= $rowData[0][3];
				$Parent 		= $rowData[0][4];
				$Debit 			= $rowData[0][5];
				$Credit 		= $rowData[0][6];

				$d = array(
					"ID"			=> $ID,
					"Code"			=> $Code,
					"Name"			=> $Name,
					"Level"			=> $Level,
					"parentName"	=> $Parent,
					"debit"			=> $Debit,
					"credit"		=> $Credit,
				);

				$d2 = array(
					"ID"			=> $ID,
					"Code"			=> $Code,
				);

				array_push($list, $d);
				array_push($arrID, $d2);
			}

			$tempArr = array_unique(array_column($arrID, 'ID'));
			$arrID = array_intersect_key($arrID, $tempArr);
			$tempArr = array_unique(array_column($arrID, 'Code'));
			$arrID = array_intersect_key($arrID, $tempArr);

			if(count($list_coa) != count($list) || count($list_coa) != count($arrID)):
				$status 	= false;
				$message 	= "Jumlah data tidak sama";
			endif;

			if (!unlink($inputFileName)):

			endif;

			$output = array(
				"status" 	=> $status,
				"hak_akses"	=> $this->session->hak_akses,
				"message"	=> $message,
				"list"		=> $list,
			);
		endif;
		$this->main->echoJson($output);
	}
}