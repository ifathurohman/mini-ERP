<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_opname extends CI_Controller {
	var $title = 'Stock Opname';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_stock_opname",'stock_opname');
		$this->load->library(array('PHPExcel','IOFactory'));
		$this->main->cek_session();
	}
	public function index()
	{
		$url 						= $this->uri->segment(1); 
		$id_url 					= $this->main->id_menu($url);
		$read 						= $this->main->read($id_url);
		$inventory 					= $this->main->check_parameter_module("inventory", "stock");
		if($read == 0 or $inventory->view == 0){ redirect(); }
		$stock_opname_tambah 				= $this->main->menu_tambah($id_url);
		if($stock_opname_tambah > 0 and $inventory->add >0):
            $tambah = $this->main->general_button('add',$this->lang->line('lb_add_new').' '.$this->title);
		else: 
			$tambah = ""; 
		endif;
		$this->main->delete_temp_sn("stock_opname");
		#ini untuk session halaman aturan user privileges;
		$data['url'] 			= $url;
		$data['url_modul']		= $url;
		$data['modul']			= 'stock_opname';
		$data['title']  		= $this->title;
		$data['tambah'] 		= $tambah;
		$data['modal'] 			= 'stock_opname/modal';
		$data['page'] 			= 'stock_opname/list';
		$this->load->view('index',$data);
	}
	//---------------------------------------------------------------------------------------
	public function ajax_list()
	{
		$page 	= "stock_opname";
		$url 	= $this->uri->segment(1); 
		$id_url = $this->main->id_menu($url);
		$list 	= $this->stock_opname->get_datatables($page);
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		$inventory = $this->main->check_parameter_module("inventory", "stock");
		foreach ($list as $a) {
			$ubab 	= "";
			$hapus 	= "";
			$stock_opname_ubah 	= $this->main->menu_ubah($id_url);
			$stock_opname_hapus 	= $this->main->menu_hapus($id_url);
			if($stock_opname_ubah > 0 and $inventory->add >0):
           	$ubah = '<a href="javascript:void(0)" type="button" class="btn btn-success" title="Edit" onclick="view('."'".$a->correctionno."'".')"><i class="icon fa-search" aria-hidden="true"></i></a>';
			endif;
			if($stock_opname_hapus > 0):
           	$hapus = '<a href="javascript:void(0)" type="button" class="btn btn-danger" title="Hapus" onclick="hapus('."'".$a->correctionno."'".')"><i class="icon fa-remove" aria-hidden="true"></i></a>';
			endif;
			$print 	= '<a href="javascript:void(0)" type="button" class="btn btn-info" title="Hapus" onclick="print('."'".$a->correctionno."'".')"><i class="icon fa-print" aria-hidden="true"></i></a>'; 

			$button  = '<div class="btn-group btn-group-xs" aria-label="Basic example" role="group">';
            // $button .= $print;
            $button .= $ubah;
            // $button .= $hapus;
            $button .= '</div>';

            $code = '<a href="javascript:;" type="button" title="View" onclick="view('."'".$a->correctionno."'".')">'.$a->correctionno.'</a>';
            if($a->BranchID):
	        	$branch 		= $this->main->button_action("general_onclick","redirect_post('store-device-management','".$a->BranchID."')",$a->branchName);
	        else:
	        	$branch 		= "";
	        endif;

			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $code;
			$row[] 	= $branch;
			$row[] 	= $a->date;

			// $row[] 	= $button;		
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->stock_opname->count_all($page),
			"recordsFiltered" => $this->stock_opname->count_filtered($page),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$a = $this->stock_opname->get_by_id($id,"stock_opname");
		$detail = $this->stock_opname->get_list_detail($id);
		$data = array(
			"correctionno" 	=> $a->correctionno,
			"branchName"	=> $a->branchName,
			"date" 			=> $a->date,
			"hakakses" 		=> $this->session->hak_akses,
			"list_detail" 	=> $detail,
		);
		header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
	}

	public function simpan($page = "")
	{
		$this->_validate();

		$BranchID 		= $this->input->post('BranchID');
		$BranchID 		= explode("-", $BranchID); $BranchID = $BranchID[0];
		$CompanyID 			= $this->session->CompanyID;
		$date 				= $this->input->post('date');
		$productid 			= $this->input->post('productid');
		$product_code 		= $this->input->post('product_code');
		$product_price 		= $this->input->post('product_price');
		$product_stock 		= $this->input->post('product_stock');
		$product_qty 		= $this->input->post('product_qty');
		$product_remark 	= $this->input->post('product_remark');

		// serial post
		$rowid	 		= $this->input->post('rowid');
		$dt_serial 	  	= $this->input->post("dt_serial");
		$dt_serialkey 	= $this->input->post("dt_serialkey");
		$dt_serialauto 	= $this->input->post("dt_serialauto");
		$dt_product_type= $this->input->post('dt_product_type');
		$dt_serial    = json_decode($dt_serial);
		$dt_serialkey = json_decode($dt_serialkey);
		$dt_serialauto 	= json_decode($dt_serialauto);
		$product_type 	= json_decode($dt_product_type);

		$correctionno = $this->main->correction_code_generate("stock_opname");
		$data = array(
			'CorrectionNo' 	=> $correctionno,
			'CompanyID'		=> $CompanyID,
			'Date' 			=> $date,
			'Type'			=> 2,
		);
		if($BranchID):
			$data['BranchID'] = $BranchID;
		endif;
		$insert = $this->stock_opname->save($data);

		foreach ($productid as $key => $v) {
			$xproductid 	= $productid[$key];
			$qty_length 	= strlen($product_stock[$key]);
			$correctiondet 	= $this->main->correctiondet_code_generate("stock_opname");

			if($qty_length > 0 && $productid[$key]):
				$xqty 	= $this->main->checkDuitInput($product_stock[$key]);
				$xqty2 	= $this->main->checkDuitInput($product_qty[$key]);
				$qty_correction = $xqty - $xqty2;
				$xprice = $this->main->checkDuitInput($product_price[$key]);

				$average = $this->main->average($productid[$key],$qty_correction,$xprice,$BranchID);

				$data = array(
					'CorrectionDet'	=> $correctiondet,
					'CorrectionNo' 	=> $correctionno,
					'CompanyID'		=> $this->session->CompanyID,
					'ProductID' 	=> $productid[$key],
					'Qty' 			=> $xqty2,
					'CorrectionQty' => $xqty,
					'Remark'		=> $product_remark[$key],
					'Price'			=> $this->main->checkDuitInput($product_price[$key]),
					'PriceBefore'	=> $average,
					'User_Add' 		=> $this->session->nama,
					'Date_Add' 		=> date("Y-m-d H:i:s")
				);
				$this->db->insert("PS_Correction_Detail",$data);
				$ID = $this->db->insert_id();
				$this->main->branch_stock($BranchID,$productid[$key],$xqty);

				// simpan serial number
				$xtype = $product_type[$key];
				if($xtype == 2):
					$arrkey = array_keys($dt_serialkey,$rowid[$key]);
					$arrTempSN = array();
					foreach ($arrkey as $key2 => $value_key) {
						$no = $key2 + 1;
						if($xqty>=$no):
							$sn = $dt_serial[$value_key];
							
							// insert ke table PS_Correction_Detail_SN
							$data_serial = array(
								"CompanyID"			=> $CompanyID,
								"Status"			=> 1,
								"Qty"				=> 1,
								"ProductID"			=> $productid[$key],
								"CorrectionNo"		=> $correctionno,
								"CorrectionDetID" 	=> $ID,
								"User_Add"			=> $this->session->NAMA,
								"Date_Add"			=> date("Y-m-d H:i:s"),
								"SN"				=> $sn,
							);
							$this->db->insert("PS_Correction_Detail_SN", $data_serial);
							array_push($arrTempSN, $sn);
						endif;
					}
					if(count($arrTempSN)>0):
						$sn_data = $this->main->product_serial("array",$arrTempSN,$productid[$key],"",$BranchID);
						foreach ($arrTempSN as $k2 => $v2) {
							$sn = array_search($v2, array_column($sn_data, "serialno"));
	            			$sn_length = strlen($sn);
	            			// insert data sn yang baru
	            			if($sn_length<=0):
	            				$data_serial = array(
	            					"ProductID"	=> $productid[$key],
	            					"CompanyID"	=> $CompanyID,
	            					"SerialNo"	=> $v2,
	            					"Date"		=> date("Y-m-d"),
	            					"Qty"		=> 1,
	            					"Status"	=> 1,
	            					"User_Add"	=> $this->session->NAMA,
									"Date_Add"	=> date("Y-m-d H:i:s"),
									"BranchID"	=> $BranchID,
	            				);
	            				$this->db->insert("PS_Product_Serial", $data_serial);
	            			endif;
						}
						// update status active
						$data_serial = array(
							"Status" 	=> 1,
							"User_Ch"	=> $this->session->NAMA,
							"Date_Ch"	=> date("Y-m-d H:i:s"),
						);
	    				$this->db->where("BranchID", $BranchID);
						$this->db->where("CompanyID", $CompanyID);
						$this->db->where("ProductID", $productid[$key]);
						$this->db->where_in("SerialNo", $arrTempSN);
						$this->db->update("PS_Product_Serial", $data_serial);

						// update status non active
						$data_serial = array(
							"Status" 	=> 0,
							"User_Ch"	=> $this->session->NAMA,
							"Date_Ch"	=> date("Y-m-d H:i:s"),
						);
	    				$this->db->where("BranchID", $BranchID);
						$this->db->where("CompanyID", $CompanyID);
						$this->db->where("ProductID", $productid[$key]);
						$this->db->where_not_in("SerialNo", $arrTempSN);
						$this->db->update("PS_Product_Serial", $data_serial);
					elseif($product_qty[$key] == 0):
						$data_serial = array(
							"Status" 	=> 0,
							"User_Ch"	=> $this->session->NAMA,
							"Date_Ch"	=> date("Y-m-d H:i:s"),
						);
						$this->db->where("BranchID", $BranchID);
						$this->db->where("CompanyID", $CompanyID);
						$this->db->where("ProductID", $productid[$key]);
						$this->db->update("PS_Product_Serial", $data_serial);
					endif;
				endif;
			endif;
		}
		$this->main->delete_temp_sn("stock_opname");

		// generate journal
		$this->db->query("CALL run_generate_jurnal('UPDATE', '$correctionno', 'stock_opname', '$CompanyID')");

		echo json_encode(array("status" => TRUE,"pesan" => "yoi", "code" => $correctionno));
	}
	public function correctionstock($productid,$qty,$AveragePrice)
	{
		$data = array(
			"Qty" 			=> $qty,
			"AveragePrice" 	=> $AveragePrice,
			"User_Ch" 		=> $this->session->nama,
			"Date_Ch" 		=> date("Y-m-d H:i:s")
		);
		$this->db->where("ProductID",$productid);
		$this->db->update("ps_product",$data);
	}
	public function ajax_delete($id)
	{
		$this->stock_opname->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}
	private function _validate($page = "")
	{
		$this->main->validate_modlue_add("inventory","inventory");
		$data 					= array();
		$data['error_string'] 	= array();
		$data['inputerror'] 	= array();
		$data['status'] 		= TRUE;

		// $data['inputerror'][] 	= "";
		// $data['error_string'][] = '';
		// $data['list'][] 		= '';
		// $data['tab'][] 			= '';
		// $data['ara'] 			= $this->input->post();
		// $data['status'] 		= FALSE;
		// $data['message']		= "Maintenance Stock Opname";

		// $this->main->echoJson($data);
		// exit();

		$CompanyID 		= $this->session->CompanyID;
		$BranchID 		= $this->input->post('BranchID');
		$BranchID 		= explode("-", $BranchID); $BranchID = $BranchID[0];
		$productid 		= $this->input->post('productid');
		$rowid	 		= $this->input->post('rowid');
		$product_stock 	= $this->input->post('product_stock');

		// serial post
		$dt_serial 	  	= $this->input->post("dt_serial");
		$dt_serialkey 	= $this->input->post("dt_serialkey");
		$dt_serialauto 	= $this->input->post("dt_serialauto");
		$dt_product_type= $this->input->post('dt_product_type');
		$dt_serial    = json_decode($dt_serial);
		$dt_serialkey = json_decode($dt_serialkey);
		$dt_serialauto 	= json_decode($dt_serialauto);
		$product_type 	= json_decode($dt_product_type);

		$status 	= FALSE;
		$message 	= '';
		foreach ($productid as $key => $value) {
			$ProductID = $productid[$key];
			if($ProductID):
				$status = TRUE;
			endif;
		}

		if(!$status):
			$data['status'] 		= FALSE;
			$data['message']		= $this->lang->line('lb_select_product_empty');
		endif;

		$status_qty 	= true;
		$lb_message 	= '';
		foreach ($productid as $key => $v) {
			$ProductID 	= $productid[$key];
			if($ProductID):
				$cek = $this->db->count_all("ps_product where CompanyID = '$CompanyID' and ProductID = '$ProductID'");
				if($cek<=0):
					$data['inputerror'][] 	= $rowid[$key];
					$data['error_string'][] = $rowid[$key];
					$data['list'][] 		= 'list';
					$data['tab'][] 			= '';
					$data['message'] 		= $this->lang->line('lb_product_not_found');
					$data['status'] 		= FALSE;
					$status_product 		= false;
				else:
					$qty_len = strlen($product_stock[$key]);
					if($qty_len<=0):
						$data['inputerror'][] 	= $rowid[$key];
						$data['error_string'][] = $this->lang->line('lb_product_qty_empty');
						$data['list'][] 		= 'list';
						$data['tab'][] 			= '';
						$status_qty 	= false;
						$lb_message 	= $this->lang->line('lb_product_qty_empty');
					else:
						$xtype 	= $product_type[$key];
						$xqty 	= $this->main->checkDuitInput($product_stock[$key]);
						$temp_data  = $this->api->temp_serial("stock_opname","",$rowid[$key],$productid[$key],"class");
						if($xtype == 2):
							$arrkey = array_keys($dt_serialkey,$rowid[$key]);
							if($xqty>count($arrkey)):
								$data['inputerror'][] 	= $rowid[$key];
								$data['error_string'][] = $this->lang->line('lb_serial_empty');
								$data['list'][] 		= 'list';
								$status_qty 			= false;
								$lb_message 			= $this->lang->line('lb_serial_empty');
							else:
								$status_sn 	= true;
								$message_sn = '';
								foreach ($arrkey as $key2 => $value_key) {
									$no = $key2 + 1;
									if($xqty>=$no):

										if($dt_serial[$value_key] == ''):
											$status_sn = false;
											$message_sn = $this->lang->line('lb_serial_empty');
										else:
											$temp_sn = array_search($dt_serial[$value_key], array_column($temp_data, 'SN'));
	            							$temp_sn_length = strlen($temp_sn);

	            							if($temp_sn_length<=0):
	            								$status_sn = false;
												$message_sn = $this->lang->line('lb_serial_not_found');
	            							endif;
										endif;
									endif;
								}
								if(!$status_sn):
									$data['inputerror'][] 	= $rowid[$key];
									$data['error_string'][] = $message_sn;
									$data['list'][] 		= 'list';
									$lb_message 			= $message_sn;
									$status_qty 			= false;
								endif;
							endif;
						endif;
					endif;
				endif;
			endif;
		}

		if(!$status_qty):
			$data['status'] 	= FALSE;
			$data['message'] 	= $lb_message;
			echo json_encode($data);
			exit();
		endif;

		$ck_Branch = $this->db->count_all("Branch where CompanyID = '$CompanyID' and BranchID = '$BranchID' and Active = '1'");
		if($ck_Branch<=0):
			$data['inputerror'][] 	= "BranchID";
			$data['error_string'][] = '';
			$data['list'][] 		= '';
			$data['status'] 		= FALSE;
			$data['message'] 		= $this->lang->line('lb_store_not_found');
		endif;

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	
	public function get_detail($id){
		$d = $this->stock_opname->get_list_detail($id,"detail");

		$data = array(
			"list"		=> $d,
			"serial" 	=> $this->main->product_serial("add_serial_mutasi",$d->productid),
			"hak_akses"	=> $this->session->hak_akses,
		);

		$this->main->echoJson($data);
	}

	public function export($page=""){
		$list = $this->main->product_branch();
		$file_name = "Stock_opname_".date("Ymd_His").".xls";
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
		if($page != "template"):
			foreach(range('A','H') as $columnID):
		        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		    endforeach;
			$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A1', $this->lang->line('lb_store'))
	            ->setCellValue('B1', $this->lang->line('lb_product_code'))
	            ->setCellValue('C1', $this->lang->line('lb_product_name'))
	            ->setCellValue('D1', $this->lang->line('lb_average_price'))
	            ->setCellValue('E1', $this->lang->line('lb_stock_opname_qty'))
	            ->setCellValue('F1', $this->lang->line('lb_stock_qty'))
	            ->setCellValue('G1', $this->lang->line('lb_unit'))
	            ->setCellValue('H1', $this->lang->line('lb_remark'));

			foreach ($list as $a):                
		      	$no              	= $i++; 
		      	$urut            	= $ii++;
		      	$objPHPExcel->setActiveSheetIndex(0)
		                  ->setCellValue('A'.$urut, $a->branchName)
		                  ->setCellValue('B'.$urut, $a->product_code)
		                  ->setCellValue('C'.$urut, $a->product_name)
		                  ->setCellValue('D'.$urut, $a->average_price)
		                  ->setCellValue('E'.$urut, 0)
		                  ->setCellValue('F'.$urut, $a->qty)
		                  ->setCellValue('G'.$urut, $a->unit_name)
		                  ->setCellValue('H'.$urut, '');
		    endforeach;
		else:
			foreach(range('A','H') as $columnID):
		        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		    endforeach;

			$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A1', $this->lang->line('lb_product_code'))
	            ->setCellValue('B1', $this->lang->line('lb_product_name'))
	            ->setCellValue('C1', $this->lang->line('price'))
	            ->setCellValue('D1', $this->lang->line('lb_stock_opname_qty'))
	            ->setCellValue('E1', $this->lang->line('lb_stock_qty'))
	            ->setCellValue('F1', $this->lang->line('lb_unit'))
	            ->setCellValue('G1', $this->lang->line('lb_remark'))
	            ->setCellValue('H1', $this->lang->line('lb_sn'));
		endif;

	    // Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Template Stock opname');
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

	public function import2(){
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
			$list 		= array();

			$status 		=	 true;
			$message 			= $this->lang->line('lb_success');
			$arr_product_code 	= array();
			$arrData 			= array();

			for ($row = 2; $row <= $highestRow; $row++){
				$rowData 		= $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);

				$product_code 			= $rowData[0][0];

				 
				if($cek<=0):
					$status 	= false;
					$message 	= "Product Code not found, Please check your data.";
					break;
				endif;

				array_push($arr_product_code, $product_code);
			}

			if($status):
				$list 	= $this->main->product("array_code", $arr_product_code);
				for ($row = 2; $row <= $highestRow; $row++){
					$rowData 		= $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
					$average_price 	= 0;
					$stock 			= 0;
					$status_average = TRUE;

					$product_code 	= $rowData[0][0];
					if($rowData[0][2]): $average_price = $rowData[0][2]; endif;
					if($rowData[0][3]): $stock = $rowData[0][3]; endif;

					$key = array_search($product_code, array_column($list, 'product_code'));

					if($key>=0):
						$correction_stock 	= $stock - $list[$key]->qty;
						if($average_price != $list[$key]->average_price): $status_average = FALSE; endif;

						$data = array(
							"productid"					=> $list[$key]->productid,
							"product_code"				=> $list[$key]->product_code,
							"product_name"				=> $list[$key]->product_name,
							"product_average_program" 	=> $list[$key]->average_price,
							"product_average"			=> $average_price,
							"product_qty"				=> $list[$key]->qty,
							"product_stock_opname"		=> $stock,
							"correction_stock"			=> $correction_stock,
							"status_average"			=> $status_average,
							"unitid"					=> $list[$key]->unitid,
							"unit_name"					=> $list[$key]->unit_name,
							"product_type"				=> $list[$key]->product_type,
						);
						array_push($arrData, $data);
					endif;
				}
			endif;

			$output = array(
				"status" 	=> $status,
				"message"	=> $message,
				"hak_akses"	=> $this->session->hak_akses,
				"list"		=> $arrData,
			);

			if (!unlink($inputFileName)):

			endif;

		endif;

		$this->main->echoJson($output);
	}

	public function import(){
		// $this->import2();
		$this->main->validate_modlue_add("inventory","inventory");
		$this->import_stock_opname();
	}

	private function import_stock_opname(){
		$fileName                 = $this->input->post('file', TRUE);
		$CompanyID 				  = $this->session->CompanyID;
		$userid 				  = $this->session->id_user;
		$BranchID 				  = $this->input->post('BranchID2');
		$BranchID 				  = explode("-", $BranchID);

		$ck_Branch = $this->db->count_all("Branch where BranchID = '$BranchID[0]' and CompanyID = '$CompanyID' and Active = 1");
		if($ck_Branch<=0):
			$output = array(
				"status" 	=> false,
				"message"	=> $this->lang->line('lb_store_not_found'),
				"hak_akses"	=> $this->session->hak_akses,
			);
			$this->main->echoJson($output);
			exit();
		else:
			$branchName = $BranchID[1];
			$BranchID 	= $BranchID[0];
		endif;

		$folder 				  = $this->main->create_folder_general("stock_opname_temp");
		$config['upload_path']    = './'.$folder; 
		$config['file_name']      = $fileName;
		$config['allowed_types']  = 'xls|xlsx|csv|ods|ots';
		$config['max_size']       = 10000;
		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('file')):
			$output = array(
				"status" 	=> false,
				"message"	=> $this->lang->line('lb_excell_empty'),
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
			$message   = $this->lang->line('lb_success');
			$arrData   = array();
			$arrHeader = array();
			$rowData   = $sheet->rangeToArray('A' . 1 . ':' . $highestColumn . 1,NULL,TRUE,FALSE);
			if($rowData): $arrHeader = $rowData; endif;
			$arr_product_code 	= array();
			$total_data = 0;

			for ($row = 2; $row <= $highestRow; $row++){
				$total_data += 1;
				$rowData 		= $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
				if(count($rowData[0]) == 8):
					$Code 	 = $this->main->checkInputData($rowData[0][0]);
					$ck_code = $this->db->count_all("ps_product where CompanyID = '$CompanyID' and Code = '$Code' and Position = '0'");
					if($ck_code>0):
						if(!in_array($Code, $arr_product_code)):
							array_push($arr_product_code, $Code);
						endif;
					endif;
				else:
					$status  = false;
					$message = $this->lang->line('lb_column_not_match').".";
				endif;
			}

			if($status):
				if(count($arr_product_code)>0):
					$list 	= $this->main->product_branch("array_code", $arr_product_code,$BranchID);
				else:
					$list 	= array();
				endif;

				$temp_product_code = '';
				for ($row = 2; $row <= $highestRow; $row++){
					$status_product = true;
					$status_data 	= "update";
					$rowData 		= $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
					$Code 			= $this->main->checkInputData($rowData[0][0]);
					$Name 			= $this->main->checkInputData($rowData[0][1]);
					$Average 		= $this->main->checkDuitInput($rowData[0][2]);
					$StockOpname 	= $this->main->checkDuitInput($rowData[0][3]);
					$StockQty 		= $this->main->checkDuitInput($rowData[0][4]);
					$Unit 			= $this->main->checkInputData($rowData[0][5]);
					$Remark 		= $this->main->checkInputData($rowData[0][6]);
					$SerialNumber 	= $this->main->checkInputData($rowData[0][7]);
					$Message 		= '';

					$key = array_search($Code, array_column($list, 'product_code'));
					if(strlen($key)>0):
						if($list[$key]->import_data == 2):
							$status_product = false;
							$Message .= "- ".$this->lang->line('lb_product_duplicate2')."<br>";
						endif;
						if($list[$key]->product_name != $Name):
							$status_product = false;
							$Message .= "- ".$this->lang->line('lb_product_name_not_change')."<br>";
						endif;
						if($list[$key]->Uom != $Unit):
							$status_product = false;
							$Message .= "- ".$this->lang->line('lb_unit_not_change1')."<br>";
						endif;
						if($list[$key]->ProductType != 'item' and $SerialNumber):
							$status_product = false;
							$Message .= "- ".$this->lang->line('lb_service_not_serial')."<br>";
						elseif($list[$key]->ProductType == 'item' and $list[$key]->Type != 2 and $SerialNumber):
							$status_product = false;
							$Message .= "- ".$this->lang->line('lb_serial_not_active')."<br>";
						elseif($list[$key]->ProductType == 'item' and $list[$key]->Type == 2 and !$SerialNumber):
							$status_product = false;
							$Message .= "- ".$this->lang->line('lb_serial_empty')."<br>";
						elseif($list[$key]->Type == 2):
							$temp_serial_number = json_decode($list[$key]->SN);
							if(in_array($SerialNumber, $temp_serial_number)):
								$status_product = false;
								$Message .= "- ".$this->lang->line('lb_serial_duplicate')."<br>";
							elseif($list[$key]->import_data != 2):
								array_push($temp_serial_number, $SerialNumber);
								$list[$key]->SN = json_encode($temp_serial_number);
							endif;
						endif;

						if($list[$key]->ProductType == 'item' and $list[$key]->Type == 2 and $StockOpname > 1 ):
							$status_product = false;
							$Message .= "- ".$this->lang->line('lb_serial_qty')."<br>";
						endif;
						
						if($StockQty != $list[$key]->qty):
							$status_product = false;
							$Message .= "- ".$this->lang->line('lb_stock_qty_not_change1')."<br>";
						endif;

						if($list[$key]->Type != 2):
							$list[$key]->import_data = 2;
						else:
							try {
								$row2 = $row + 1;
							  	$temp_rowdata = $sheet->rangeToArray('A' . $row2 . ':' . $highestColumn . $row2,NULL,TRUE,FALSE);
							  	if(count($temp_rowdata)>0):
							  		$Code2 		  = $this->main->checkInputData($temp_rowdata[0][0]);
							  	else:
							  		$Code2 		  = '';
							  	endif;
							} catch (Exception $e) {
							  	$Code2 		  = '';
							}
							if($Code != $Code2):
								$list[$key]->import_data = 2;
							endif;
						endif;
					else:
						$status_product = false;
						$Message .= "- ".$this->lang->line('lb_product_code_not_found')." <br>";
					endif;

					if(!is_numeric($Average)):
						$status_product = false;
						$Message 		.= "- ".$this->lang->line('lb_average_number')." <br>";
					endif;
					if(!is_numeric($StockOpname)):
						$status_product = false;
						$Message 		.= "- ".$this->lang->line('lb_stock_opname_qty_num')." <br>";
					endif;
					if(!is_numeric($StockQty)):
						$status_product = false;
						$Message 		.= "- ".$this->lang->line('lb_stock_qty_num')." <br>";
					endif;

					$h = array(
				    	"status"		=> $status_product,
				    	"status_data"	=> $status_data,
				    	"Code"  		=> $Code,
				    	"Name"			=> $Name,
				    	"Average"		=> $this->main->currency($Average),
				    	"StockOpname"	=> $this->main->qty($StockOpname),
				    	"StockQty"		=> $this->main->qty($StockQty),
				    	"Unit"			=> $Unit,
				    	"Remark"		=> $Remark,
				    	"SerialNumber"	=> $SerialNumber,
				    	"Message" 		=> $Message,
				    );

				    array_push($arrData, $h);
				}
			endif;


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
				"branchName" 	=> $branchName,
				"BranchID" 		=> $BranchID,
			);
		endif;
		$this->main->echoJson($output);
	}

	public function save_import(){
		$CompanyID 	= $this->session->CompanyID;
		$filename 	= $this->input->post("filename");
		$BranchID 	= $this->input->post('BranchID');

		$ck_Branch = $this->db->count_all("Branch where BranchID = '$BranchID' and CompanyID = '$CompanyID' and Active = 1");
		if($ck_Branch<=0):
			$output = array(
				"status" 	=> false,
				"message"	=> $this->lang->line('lb_store_not_found'),
				"hak_akses"	=> $this->session->hak_akses,
			);
			$this->main->echoJson($output);
			exit();
		endif;

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
			$message   = $this->lang->line('lb_success');

			$arr_product_code 	= array();

			for ($row = 2; $row <= $highestRow; $row++){
				$rowData 		= $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
				if(count($rowData[0]) == 8):
					$Code 	 = $this->main->checkInputData($rowData[0][0]);
					$ck_code = $this->db->count_all("ps_product where CompanyID = '$CompanyID' and Code = '$Code' and Position = '0'");
					if($ck_code>0):
						if(!in_array($Code, $arr_product_code)):
							array_push($arr_product_code, $Code);
						endif;
					endif;
				else:
					$status  = false;
					$message = $this->lang->line('lb_column_not_match').".";
				endif;
			}

			if($status):
				$arrData = array();
				$correctionno = $this->main->correction_code_generate("stock_opname");
				if(count($arr_product_code)>0):
					$list 	= $this->main->product_branch("array_code", $arr_product_code,$BranchID);
				else:
					$list 	= array();
				endif;
				for ($row = 2; $row <= $highestRow; $row++){
					$status_product = true;
					$qty_total 		= 0;
					$sn_total 		= '[]';
					$rowData 		= $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
					$Code 			= $this->main->checkInputData($rowData[0][0]);
					$Name 			= $this->main->checkInputData($rowData[0][1]);
					$Average 		= $this->main->checkDuitInput($rowData[0][2]);
					$StockOpname 	= $this->main->checkDuitInput($rowData[0][3]);
					$StockQty 		= $this->main->checkDuitInput($rowData[0][4]);
					$Unit 			= $this->main->checkInputData($rowData[0][5]);
					$Remark 		= $this->main->checkInputData($rowData[0][6]);
					$SerialNumber 	= $this->main->checkInputData($rowData[0][7]);

					$key = array_search($Code, array_column($list, 'product_code'));
					if(!is_numeric($Average)):
						$status_product = false;
					endif;
					if(!is_numeric($StockOpname)):
						$status_product = false;
					endif;
					if(!is_numeric($StockQty)):
						$status_product = false;
					endif;

					if(strlen($key)>0):
						if($list[$key]->product_name != $Name):
							$status_product = false;
						endif;
						if($list[$key]->Uom != $Unit):
							$status_product = false;
						endif;

						if($list[$key]->ProductType != 'item' and $SerialNumber):
							$status_product = false;
						elseif($list[$key]->ProductType == 'item' and $list[$key]->Type != 2 and $SerialNumber):
							$status_product = false;
						elseif($list[$key]->ProductType == 'item' and $list[$key]->Type == 2 and !$SerialNumber):
							$status_product = false;
						elseif($list[$key]->Type == 2):
							$temp_serial_number = json_decode($list[$key]->SN);
							if(in_array($SerialNumber, $temp_serial_number)):
								$status_product = false;
							elseif($list[$key]->import_data != 2):
								array_push($temp_serial_number, $SerialNumber);
								$list[$key]->SN = json_encode($temp_serial_number);
							endif;
						endif;

						if($list[$key]->ProductType == 'item' and $list[$key]->Type == 2 and $StockOpname > 1 ):
							$status_product = false;
						endif;

						if($StockQty != $list[$key]->qty):
							$status_product = false;
						endif;

						if($list[$key]->Type != 2):
							$list[$key]->import_data = 2;
						else:
							try {
								$row2 = $row + 1;
							  	$temp_rowdata = $sheet->rangeToArray('A' . $row2 . ':' . $highestColumn . $row2,NULL,TRUE,FALSE);
							  	if(count($temp_rowdata)>0):
							  		$Code2 		  = $this->main->checkInputData($temp_rowdata[0][0]);
							  	else:
							  		$Code2 		  = '';
							  	endif;
							} catch (Exception $e) {
							  	$Code2 		  = '';
							}
							if($Code != $Code2):
								$list[$key]->import_data = 2;
							endif;
						endif;

						if($status_product):
							$list[$key]->temp_qty += $StockOpname;
						endif;
						$qty_total = $list[$key]->temp_qty;
						$sn_total  = $list[$key]->SN;

						if($list[$key]->import_data != 2):
							$status_product = false;
						endif;
					else:
						$status_product = false;
					endif;

					if($status_product):
						$qty_correction = $qty_total - $StockQty;
						$average = $this->main->average($list[$key]->productid,$qty_correction,$Average,$BranchID);

						$data = array(
							'CorrectionNo' 	=> $correctionno,
							'CompanyID'		=> $CompanyID,
							'ProductID' 	=> $list[$key]->productid,
							'Qty' 			=> $StockQty,
							'CorrectionQty' => $qty_total,
							'Remark'		=> $Remark,
							'Price'			=> $Average,
							'PriceBefore'	=> $average,
							'User_Add' 		=> $this->session->nama,
							'Date_Add' 		=> date("Y-m-d H:i:s"),
							"SN"			=> $sn_total,
							"Type"			=> $list[$key]->Type,
						);

						array_push($arrData, $data);
					endif;
				}

				if(count($arrData)>0):
					$data = array(
						'CorrectionNo' 	=> $correctionno,
						'CompanyID'		=> $CompanyID,
						'Date' 			=> date("Y-m-d"),
						'Type'			=> 2,
						'BranchID'		=> $BranchID,
					);
					$insert = $this->stock_opname->save($data);
					$arrData = json_encode($arrData);
					$arrData = json_decode($arrData);
					foreach ($arrData as $k => $v) {
						$correctiondet 		= $this->main->correctiondet_code_generate("stock_opname");
						$v->CorrectionDet 	= $correctiondet;
						$SN 	= $v->SN;
						$Type 	= $v->Type;
						unset($v->SN);
						unset($v->Type);
						$this->db->insert("PS_Correction_Detail",$v);
						$DetID = $this->db->insert_id();
						$this->main->branch_stock($BranchID,$v->ProductID,$v->CorrectionQty,$v->Price);
						if($Type == 2):
							$this->stock_opname->check_serial_number($SN,$BranchID,$v,$DetID);
						endif;
					}
				endif;
			endif;

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

	public function serial_number_list(){
		$mt = $this->input->post("mt");
		$dt = $this->input->post("dt");

		$list = $this->stock_opname->serial_number_list($mt,$dt);

		$output = array(
			"status"	=> true,
			"list"		=> $list,
		);

		$this->main->echoJson($output);
	}
}
