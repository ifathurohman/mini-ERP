<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_unit extends CI_Model {

	var $table 	= 'ps_unit';
	var $column = array('unitid','name','conversion','type','remark'); //set column field database for order and search
	var $order 	= array('unitid' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('PHPExcel','IOFactory'));
	}

	private function _get_datatables_query()
	{
		$url = $this->uri->segment(1);
		$this->db->select("
			unitid,
			name,
			conversion,
			LCASE(ps_unit.type) as type,
			remark,
			active
			");
		$this->db->where("CompanyID",$this->session->CompanyID);
		$this->db->order_by("Active","DESC");
		$this->db->from($this->table);
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
		if($this->input->post("Active") != "none"):
            $Active = $this->input->post("Active");
            $this->db->where("ps_unit.Active", $Active);
        endif;
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$url = $this->uri->segment(1);
		$this->db->where("CompanyID",$this->session->CompanyID);
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->select("
				UnitID as unitid, 
				CompanyID as CompanyID, 
				Active as active");
		$this->db->from($this->table);
		$this->db->where("CompanyID",$this->session->CompanyID);
		$this->db->where('unitid',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function save($data)
	{
		$this->db->set("user_add",$this->session->userdata("NAMA"));
		$this->db->set("date_add",date("Y-m-d H:i:s"));
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->set("user_ch",$this->session->userdata("NAMA"));
		$this->db->set("date_ch",date("Y-m-d H:i:s"));
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where('unitid', $id);
		$this->db->delete($this->table);
	}
	public function export_data(){
		$this->db->where("CompanyID",$this->session->CompanyID);
		$this->db->where("Active", 1);
		$query = $this->db->get("ps_unit");
		return $query->result();
	}
	public function export()
	{
		$file_name 			= "SampleUnit".date("Ymd_His").".xls";
		$data["NAMA"]       = $this->session->userdata("NAMA");
		$data["DATETIME"]   = date("Y-m-d H:i:s");
		$lap_data     		= $this->export_data();
		$jumlah_data  		= count($lap_data); 
		$jumlah_kolom 		= $jumlah_data + 1;
		$objPHPExcel 		= new PHPExcel();
		// Set document properties
		$objPHPExcel->getProperties()->setCreator("PIPESYS")
		            ->setLastModifiedBy("PIPESYS")
		            ->setTitle("Office 2003 XLS Test Document")
		            ->setSubject("Office 2003 XLS Test Document")
		            ->setDescription("PIPESYS")
		            ->setKeywords("office 2003 openxml php")
		            ->setCategory("PIPESYS");
		$border_style = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
		// $objPHPExcel->getActiveSheet()->getStyle("A1:D".$jumlah_kolom)->applyFromArray($border_style);
		foreach(range('A','Z') as $columnID):
	        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
	    endforeach;
		#ini untuk kolom pertama      
		$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A1', 'Unit Name')
		            ->setCellValue('B1', 'Conversion')
		            ->setCellValue('C1', 'Type')
		            ->setCellValue('D1', 'Remark');
		// Miscellaneous glyphs, UTF-8
		$i    = 1;
		$ii   = 2; 
		#ini untuk kolom data dalam looping
		if(!empty($lap_data)):
		    foreach ($lap_data as $l):                
		      $no              	= $i++; 
		      $urut            	= $ii++;
		      $objPHPExcel->setActiveSheetIndex(0)
		                  ->setCellValue('A'.$urut, $l->Name)
		                  ->setCellValue('B'.$urut, $l->Conversion)
		                  ->setCellValue('C'.$urut, $l->Type)
		                  ->setCellValue('D'.$urut, $l->Remark);
		    endforeach;
		else:
			$objPHPExcel->setActiveSheetIndex(0)
		                  ->setCellValue('A2', 'KG')
		                  ->setCellValue('B2', '1.0')
		                  ->setCellValue('C2', 'berat')
		                  ->setCellValue('D2', '');
		    $objPHPExcel->setActiveSheetIndex(0)
		                  ->setCellValue('A3', 'Liter')
		                  ->setCellValue('B3', '1.0')
		                  ->setCellValue('C3', 'volume')
		                  ->setCellValue('D3', '');
		    $objPHPExcel->setActiveSheetIndex(0)
		                  ->setCellValue('A4', 'Meter')
		                  ->setCellValue('B4', '1.0')
		                  ->setCellValue('C4', 'panjang')
		                  ->setCellValue('D4', '');
		endif;
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Template Import Category');
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
	public function import()
	{
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
		elseif (!$this->upload->do_upload('file')):
			$output = array(
				"status" 	=> true,
				"message"	=> "import data Success",
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

			$status 	= true;
			$message 	= "Success";

			for ($row = 2; $row <= $highestRow; $row++){  
			    $rowData 		= $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);

			    $arrType 		= array('berat','panjang','volume');
			    $name       	= $rowData[0][0];
			    $conversion		= $rowData[0][1];     
			    $type			= $rowData[0][2];     
			    $remark			= $rowData[0][3];     

			    $data     = array(
			    	'CompanyID'		=> $this->session->companyid,
			    	'Name' 			=> $name,
					'Conversion'	=> $conversion,
					'Type' 			=> $type,
					'Remark' 		=> $remark,
					'Active'		=> 1,
			    );

			    if(!$name):
					$status 	= false;
					$message 	= "Name cann't be null";
					break;
				elseif($name>1):
					$status 	= false;
					$message 	= "Sorry name already exist";
					break;
				elseif(!in_array($type,$arrType)):
					$status 	= false;
					$message 	= "Use only Type berat, panjang or volume";
					break;
				endif;

				$cek_unit 	= $this->db->count_all("ps_unit where Name = '$name' and CompanyID = '$CompanyID'");
				$UnitID 	= 0;
				if($cek_unit<=0): // ini kondisi untuk insert baru
					$data_unit 	= array(
						'CompanyID'	=> $this->session->CompanyID,
						'parentid' 	=> 0,
						'position' 	=> 0,
						'active' 	=> 1,
						'position' 	=> 0,
						'name' 		=> $name,
						'conversion'=> $conversion,
						'type'		=> ucwords($type),
					);
					$this->db->set("user_add",$this->session->userdata("NAMA"));
					$this->db->set("date_add",date("Y-m-d H:i:s"));
					$this->db->insert("ps_unit", $data_unit);
					$UnitID =  $this->db->insert_id();
				else:
					$data["user_ch"] = $this->session->nama;
					$data["date_ch"] = date("Y-m-d H:i:s");
					$this->db->where("CompanyID",$this->session->companyid);
					$this->db->where("Name",$name);
					$this->db->update("ps_unit",$data);
				endif;
			}
			$media['file_path'];
		  	// delete_files($media['file_path']);   
		  	$files = glob($media['file_path'].'*');
			foreach($files as $file){
			   if(is_file($file)){
			       unlink($file);
			   }
			}
		  	echo json_encode(array("status" => TRUE));
		endif;  
	}
	public function cek_unit($name)
	{
		$this->db->select("UnitID");
		$this->db->where("Name",$name);
		$this->db->where("CompanyID",$this->session->CompanyID);
		$query = $this->db->get("ps_unit");
		return $query->num_rows();
	}
}
