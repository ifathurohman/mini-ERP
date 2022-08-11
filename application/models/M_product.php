<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_product extends CI_Model {

	var $table 				= 'ps_product';
	var $column_category 	= array('ps_product.productid','ps_product.code','ps_product.name','ps_product.position','pp2.name');
	var $column_product 	= array(
		'ps_product.productid',
		'ps_product.productid',
		'ps_product.code',
		'ps_product.name',
		'category.name',
		'ps_product.minimumstock',
		'(select sum(Qty) from PS_Product_Branch where CompanyID = ps_product.CompanyID and ProductID = ps_product.ProductID)',
		'ps_product.uom',
		'ps_product.sellingprice',
		'ps_product.ProductType',
		'ps_product.SalesType',

		);
	var $order 				= array('productid' => 'desc'); // default order 
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
       	// $this->load->library(array('PHPExcel','IOFactory'));

	}
	public function generate_product_code($id_category)
	{
		$this->db->select("ps_product.code");
		$this->db->where("ps_product.code",$id_category);
        $this->db->limit(1);
		$query 	= $this->db->get("ps_product");
		if($query->num_rows() > 0):
			$a 		= $query->row();
			$code 	= $a->code;
		else:
			$code 	= "NON";
		endif;		
		$code;
	    return $this->auto_productcode("ps_product","code",4,$code."",$code);
	}
	public function auto_productcode($tabel, $kolom, $lebar=0, $awalan,$code) {
        $this->db->select("$kolom");
        $this->db->where("CompanyID",$this->session->companyid);
        $this->db->where("ParentCode",$code);
        $this->db->where("position",0);
        $this->db->like("code",$code,"left");
        $this->db->order_by("code,productid", "DESC");
        $this->db->limit(1);
        $this->db->from($tabel);
        $query 		= $this->db->get();
        $result 	= $query->result_array();
        $total_rec 	= $query->num_rows();
        if ($total_rec == 0) {
        $nomor 		= 1;
        } else {
        $nomor 		= intval(substr($result[0][$kolom],strlen($awalan))) + 1;
        }
        if($lebar > 0) {
            $angka 	= $awalan.str_pad($nomor,$lebar,"0",STR_PAD_LEFT);
        } else {
            $angka 	= $awalan.$nomor;
        }
        return $angka;
    }
	private function _get_datatables_query($page="")
	{
		$column = $this->column_category;
		$url 	= $this->uri->segment(1);
		if($page == "category"):
			$column = $this->column_category;
			$this->db->select("
				ps_product.productid as productid,
				ps_product.code as category_code,
				ps_product.name as category_name,
				ps_product.position as level, 
				pp2.name as parent_name,
				ps_product.Active as active,
				ps_product.parentcode,
				pp2.code,
				
			");
			$this->db->join("ps_product as pp2","ps_product.parentcode = pp2.code","left");
			$this->db->where("ps_product.companyid",$this->session->companyid);
			$this->db->where("ifnull(pp2.companyid,ps_product.companyid)",$this->session->companyid);
			$this->db->where("ps_product.position !=",0);
		elseif($page == "product"):
			$column = $this->column_product;
			$this->db->select("
				ps_product.productid as productid,
				ps_product.code as product_code,
				ps_product.name as product_name,
				(select sum(Qty) from PS_Product_Branch where CompanyID = ps_product.CompanyID and ProductID = ps_product.ProductID) 	as qty,
				ps_product.minimumstock as min_qty,
				ifnull(ps_product.PurchasePrice,0) as PurchasePrice,
				ifnull(ps_product.sellingprice,0)  as sellingprice,
				ifnull(ps_product.AveragePrice,0)  as averageprice,
				ps_product.Active 		 as active,
				ps_product.Status 		 as status,
				ps_product.ProductType 	 as type_product,
				ps_product.SalesType 	 as type_sales,
				ps_product.TypeCode 	 as typecode,
				category.name 	 		 as category_name,
				LCASE(category.position) as level,
				ifnull(unit.Uom,'') 	 as unit_name,
				ifnull(unit.Conversion,'')	 as conversion,
				(select Image from PS_Attachment where CompanyID = ps_product.CompanyID and ID = ps_product.ProductID and Type = 'product' and Cek = '1' limit 1) as Image,
			");
			// UCASE(unit.uom)			 as unit_name,

			$this->db->join("ps_product as category","ps_product.parentcode = category.code","left");
			$this->db->join("ps_product_unit as unit","ps_product.ProductID = unit.ProductID and ps_product.Uom = unit.Uom","left");
			// $this->db->join("ps_unit as unit", "ps_product.UnitID = unit.UnitID", "left");
			$this->db->where("ps_product.companyid",$this->session->companyid);
			$this->db->where("category.companyid",$this->session->companyid);
			$this->db->where("ps_product.position",0);
			$this->db->where("ps_product.Status",0);
			// $data_session = array("Status" => 0);
   //          $this->session->set_userdata($data_session);

		endif;
		$this->db->order_by("ps_product.Active","DESC");
		$this->db->from($this->table);
		$i = 0;
        // $column = $this->column;
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
            $this->db->where("ps_product.Active", $Active);
        endif;
		if($page == 'product'):
	        if($this->input->post("ProductType") != "none"):
	            $ProductType = $this->input->post("ProductType");
	            $this->db->where("ps_product.ProductType", $ProductType);
	        endif;
	        if($this->input->post("SalesType") != "none"):
	            $SalesType = $this->input->post("SalesType");
	            $this->db->where("ps_product.SalesType", $SalesType);
	        endif;	
		endif;
		if(isset($_POST['order'])){
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order)){
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
	function get_datatables($page = "")
	{
		$this->_get_datatables_query($page);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}
	function count_filtered($page="")
	{
		$this->_get_datatables_query($page);
		if($page == "product"):
			$this->db->where("ps_product.position",0);
		elseif($page == "category"):
			$this->db->where("ps_product.position !=",0);
		endif;
		$this->db->where("ps_product.CompanyID",$this->session->CompanyID);
		$query = $this->db->get();
		return $query->num_rows();
	}
	public function count_all($page="")
	{
		$url = $this->uri->segment(1);
		if($page == "product"):
			$this->db->join("ps_product as category","ps_product.parentcode = category.code");
			$this->db->where("ps_product.position",0);
		elseif($page == "category"):
			$this->db->join("ps_product as pp2","ps_product.parentcode = pp2.code","left");
			$this->db->where("ps_product.position !=",0);
		endif;
		$this->db->where("ps_product.CompanyID",$this->session->companyid);
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
	public function get_by_id($id,$page="")
	{
		if($page == "category"):
			$this->db->select("
				productid as categoryid, 
				code as category_code, 
				name as category_name, 
				position as level, 
				parentcode as parent_category,
				active 	as active,
				parentcode as parent_code");
		elseif($page == "product"):
			$this->db->select("
				ps_product.productid as productid,
				ps_product.code as product_code,
				ps_product.type as type,
				ps_product.name as product_name,
				ps_product.ParentCode as parent_code,
				ps_product.minimumstock as min_qty,
				ps_product.unitid,
				ps_product.sellingprice as selling_price,
				ps_product.SNFormat as serial_format,
				ps_product.SNAuto 	as serial_auto,
				ps_product.TypeCode as typecode,
				ps_product.ProductType,
				ps_product.SalesType,
				ps_product.Active,
				ps_product.CostMethod,
				ifnull(unit.Uom,'') as unit,
				ifnull(unit.Conversion,'') 	as conversion,

			");
		endif;
		$this->db->from($this->table);
		// $this->db->join("ps_product as category","ps_product.parentcode = category.code and ps_product.CompanyID = category.CompanyID","left");
		if($page == "product"):
			$this->db->join("ps_product_unit as unit","ps_product.ProductID = unit.ProductID and ps_product.Uom = unit.Uom","left");
			// $this->db->join("ps_unit as unit","ps_product.UnitID = unit.UnitID","left");
		endif;
		$this->db->where('ps_product.productid',$id);
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

	public function save_uom($data)
	{
		$this->db->set("User_Add",$this->session->userdata("NAMA"));
		$this->db->set("Date_Add",date("Y-m-d H:i:s"));
		$this->db->insert("ps_product_unit", $data);
		return $this->db->insert_id();
	}
	public function update_uom($where, $data)
	{
		$this->db->set("User_Ch",$this->session->userdata("NAMA"));
		$this->db->set("Date_Ch",date("Y-m-d H:i:s"));
		$this->db->update("ps_product_unit", $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where('productid', $id);
		$this->db->delete($this->table);
	}
	public function export_data($page = "",$page2="")
	{
		if($page == "category"):
			$column = $this->column_category;
			$this->db->select("
				ps_product.productid as productid,
				ps_product.code as category_code,
				ps_product.name as category_name,
				ps_product.position as level, 
				ps_product.parentcode as parent_code,
				pp2.name as parent_name,
			");
			$this->db->join("ps_product as pp2","ps_product.parentid = pp2.productid","left");
			$this->db->where("ps_product.companyid",$this->session->companyid);
			$this->db->where("ps_product.userid",$this->session->id_user);
			$this->db->where("ps_product.position !=",0);
			$this->db->where("ps_product.Active", 1);
		elseif($page == "product"):
			$column = $this->column_product;
			$this->db->select("
				ps_product.productid as productid,
				ps_product.code as product_code,
				ps_product.parentcode as category_code,
				ps_product.name as product_name,
				ps_product.minimumstock as min_qty,
				ps_product.sellingprice as sellingprice,
				(case
					when ps_product.ProductType = 'item' 	then 'Yes'
					when ps_product.ProductType = 'service' then 'No'
					else 'No'
				end)	 			as type_product,
				(case
					when ps_product.SalesType = 'sell' 	then 'Yes'
					when ps_product.SalesType = 'nonsell' then 'No'
					else 'No'
				end)	 			as type_sales,
				(case
					when ps_product.Type = '2' 	then 'Yes'
					else 'No'
				end)	 			as serial_type,
				category.name 		as category_name,
				category.position 	as level,
				ifnull(unit.Uom,'') as unit_name,
				ifnull(unit.Conversion,'') 	as conversion,
				ps_product.CostMethod,
				ps_product.PurchasePrice,
			");
			$this->db->where("ps_product.companyid",$this->session->companyid);
			// if($this->session->hak_akses != "super_admin"):
			// 	$this->db->where("ps_product.companyid",$this->session->companyid);
			// endif;
			$this->db->where("ps_product.position",0);
			$this->db->where("ps_product.Active", 1);
			$this->db->where("ps_product.Status", 0);
			$this->db->join("ps_product as category","ps_product.parentcode = category.code and ps_product.CompanyID = category.CompanyID","left");
			$this->db->join("ps_product_unit as unit","ps_product.ProductID = unit.ProductID and ps_product.Uom = unit.Uom","left");
			// $this->db->join("ps_unit as unit","ps_product.UnitID = unit.UnitID","left");
			$this->db->order_by("ps_product.code","ASC");
		endif;
		$this->db->from($this->table);
		$query = $this->db->get();
		return $query->result();
	}
	
	public function export($page = "",$page2="")
	{
		$file_name = "SampleCategory".date("Ymd_His").".xls";
		if($page == "product"): 
			$file_name = "SampelProduct".date("Ymd_His").".xls";
		endif;
		$data["NAMA"]       = $this->session->userdata("NAMA");
		$data["DATETIME"]   = date("Y-m-d H:i:s");
		$lap_data     		= $this->export_data($page);
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
		$range = '';
		if($page == "category"):
			$range = 'D';
		elseif($page == "product" && $this->session->CostMethod == "average"):
			$range = 'J';
		elseif($page == "product"):
			$range = 'K';
		endif;
		
		foreach(range('A',$range) as $columnID):
	        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
	    endforeach;
		#ini untuk kolom pertama      
		if($page == "category"):
			$objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValue('A1', 'Category Code')
			            ->setCellValue('B1', 'Name')
			            ->setCellValue('C1', 'Level')
			            ->setCellValue('D1', 'Parent Code');
		elseif($page == "product"):
			if($this->session->CostMethod == "average"):
				$objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValue('A1', 'Product Code')
			            ->setCellValue('B1', 'Category Code')
			            ->setCellValue('C1', 'Category Name')
			            ->setCellValue('D1', 'Product Name')
			            ->setCellValue('E1', 'Min. Qty')
			            ->setCellValue('F1', 'Unit')
			            ->setCellValue('G1', 'Selling Price')
			            ->setCellValue('H1', 'Inventory')
			            ->setCellValue('I1', 'Selling')
			            ->setCellValue('J1', 'Serial');
			else:
				$objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValue('A1', 'Product Code')
			            ->setCellValue('B1', 'Category Code')
			            ->setCellValue('C1', 'Category Name')
			            ->setCellValue('D1', 'Product Name')
			            ->setCellValue('E1', 'Min. Qty')
			            ->setCellValue('F1', 'Unit')
			            ->setCellValue('G1', 'Selling Price')
			            ->setCellValue('H1', 'Purchase Price')
			            ->setCellValue('I1', 'Inventory')
			            ->setCellValue('J1', 'Selling')
			            ->setCellValue('K1', 'Serial');
			endif;
		endif;
		// Miscellaneous glyphs, UTF-8
		$i    = 1;
		$ii   = 2; 
		#ini untuk kolom data dalam looping
		if(!empty($lap_data) && $page2 != "template"):
			if($page == "category"):
			    foreach ($lap_data as $l):                
			      $no              	= $i++; 
			      $urut            	= $ii++;
			      $category_code   	= $l->category_code; 
			      $category_name   	= $l->category_name; 
			      $level   		   	= $l->level; 
			      $parent_code 		= $l->parent_code; 
			      $parent_name 		= $l->parent_name; 
			      $objPHPExcel->setActiveSheetIndex(0)
			                  ->setCellValue('A'.$urut, $category_code)
			                  ->setCellValue('B'.$urut, $category_name)
			                  ->setCellValue('C'.$urut, $level)
			                  ->setCellValue('D'.$urut, $parent_code);
			    endforeach;
			elseif($page == "product"):
				foreach ($lap_data as $l):   
		        $no              	= $i++; 
		        $urut            	= $ii++;
		        $product_code   	= $l->product_code; 
		        $category_code   	= $l->category_code; 
		        $category_name   	= $l->category_name;
		        $level   		   	= $l->level;  
		        $product_name   	= $l->product_name; 
		        $min_qty   		    = $l->min_qty; 
		        $unit_name   		= $l->unit_name; 
		        $conversion 		= $l->conversion;
		        $sellingprice 	    = $l->sellingprice;
		        $type_product 	    = $l->type_product;
		        $type_sales 	    = $l->type_sales;
		        $CostMethod 		= $l->CostMethod;
		        $PurchasePrice 		= $l->PurchasePrice;
		        $serial_type 		= $l->serial_type;

		        if($this->session->CostMethod == "average"):
		        	$objPHPExcel->setActiveSheetIndex(0)
			                  ->setCellValue('A'.$urut, $product_code)
			                  ->setCellValue('B'.$urut, $category_code)
			                  ->setCellValue('C'.$urut, $category_name)
			                  ->setCellValue('D'.$urut, $product_name)
			                  ->setCellValue('E'.$urut, $min_qty)
			                  ->setCellValue('F'.$urut, $unit_name)
			                  ->setCellValue('G'.$urut, $sellingprice)
			                  ->setCellValue('H'.$urut, $type_product)
			                  ->setCellValue('I'.$urut, $type_sales)
			                  ->setCellValue('J'.$urut, $serial_type);
		        else:
		        	$objPHPExcel->setActiveSheetIndex(0)
			                  ->setCellValue('A'.$urut, $product_code)
			                  ->setCellValue('B'.$urut, $category_code)
			                  ->setCellValue('C'.$urut, $category_name)
			                  ->setCellValue('D'.$urut, $product_name)
			                  ->setCellValue('E'.$urut, $min_qty)
			                  ->setCellValue('F'.$urut, $unit_name)
			                  ->setCellValue('G'.$urut, $sellingprice)
			                  ->setCellValue('H'.$urut, $PurchasePrice)
			                  ->setCellValue('I'.$urut, $type_product)
			                  ->setCellValue('J'.$urut, $type_sales)
			                  ->setCellValue('K'.$urut, $serial_type);
		        endif;

		        

			    // foreach(range(0,10000) as $columnID):
	    				

			   //  endforeach;
	            endforeach;
			endif;
		else:

		endif;
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Template Import');
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

	public function cek_product($page = "",$code,$name)
	{
		$this->db->select("productid");
		$this->db->where("code",$code);
		$this->db->where("name",$name);
		if($this->session->hak_akses != "super_admin"):
			$this->db->where("ps_product.companyid",$this->session->companyid);
		endif;
		if($page == "category"):
			$this->db->where("ps_product.position !=",0);
		elseif($page == "product"):
			$this->db->where("ps_product.position",0);
		endif;
		$this->db->where("ps_product.code",$code);
		$this->db->where("ps_product.userid",$this->session->id_user);
		$query = $this->db->get("ps_product");
		return $query->num_rows();
	}
	public function cek_unit($name)
	{
		$this->db->select("ProductID");
		$this->db->where("uom",$name);
		$this->db->where("CompanyID",$this->session->CompanyID);
		$query = $this->db->get("ps_product_unit");
		return $query->num_rows();
	}

	public function import($page= ""){
		$fileName                 = $this->input->post('file', TRUE);
		$CompanyID 				  = $this->session->CompanyID;
		$userid 				  = $this->session->id_user;
		$folder 				  = $this->main->create_folder_product();
		$config['upload_path']    = './'.$folder; 
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
			// $list 		= array();

			$status 	= true;
			$message 	= "Success";

			for ($row = 2; $row <= $highestRow; $row++){
				$rowData 		= $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
				if($page == "category"):
				    $code       	= $rowData[0][0];
				    $name			= $rowData[0][1];     
				    $level			= $rowData[0][2];     
				    $parentcode		= $rowData[0][3];

					$category_active = $this->db->count_all("ps_product where code = '$code' and CompanyID = '$CompanyID' and position !='0' and active = '0'");
					if(!$code): 
						$status 	= false;
						$message 	= "Code cann't be null";
						break;
					elseif($code && $category_active>0): 
						$status 	= false;
						$message 	= "Category name has been inactive";
						break;
					elseif(!is_numeric($level)):
						$status 	= false;
						$message 	= "Input level With Number Format";
						break;
					elseif(!$name):
						$status 	= false;
						$message 	= "Name cann't be null";
						break;
					elseif($level>5):
						$status 	= false;
						$message 	= "Level Category max 5";
						break;
					elseif($level<=0 or !$level):
						$status 	= false;
						$message 	= "Level Category min 1";
						break;
					elseif($level == 1 && $parentcode):
						$status 	= false;
						$message 	= "Level 1 don't need Parent Code";
						break;
					elseif($level != 1 && !$parentcode):
						$status 	= false;
						$level2 	= $level - 1;
						$message 	= "Level ".$level." need Parent Code level ".$level2;
						break;
					elseif(strlen($code)>10):
						$status 	= false;
						$message 	= "Code max length 10 character";
						break;
					endif;
				elseif($page == "product"):
					$arrType 		  = array('berat','panjang','volume');
					$arrType_product  = array('item','service');
					$arrType_sales 	  = array('sell','nonsell');

					$code       	= $rowData[0][0];
				    $category_code	= $rowData[0][1];
				    $name			= $rowData[0][2];
				    $min_qty		= $rowData[0][3];
				    $unit			= $rowData[0][4];
				   	$sellingprice	= $rowData[0][5];
				   	$type_product 	= $rowData[0][6];
				    $type_sales		= $rowData[0][7];

				    if($type_product == 'Yes'):
			            $type_product = "item";
			        elseif($type_product == 'No'):
			            $type_product = "service";
			        endif;

			        if($type_sales == 'Yes'):
			            $type_sales = "sell";
			        elseif($type_sales == 'No'):
			            $type_sales = "nonsell";
			        endif;

			        $cek_category 	= $this->db->count_all("ps_product where CompanyID = '$CompanyID' and Code = '$category_code' and Position !=0");
			        $ck_code 		= $this->db->count_all("ps_product where CompanyID = '$CompanyID' and Code = '$code' and Position = '0'");
					
					if(!$name):
						$status 	= false;
						$message 	= "Name cann't be null";
						break;
					elseif(!$unit && $type_product == "item"):
						$status 	= false;
						$message 	= "If inventory type yes unit cann't be null ";
						break;
					elseif(!is_numeric($sellingprice)):
						$status 	= false;
						$message 	= "Input Price With Number Format ";
						break;
					elseif(!in_array($type_product,$arrType_product)):
						$status 	= false;
						$message 	= "Use only Inventory Type Yes and No";
						break;
					elseif($cek_category<=0):
						$status 	= false;
						$message 	= "Category Code '".$category_code."' not found, Please input Category Code in master Category";
						break;
					elseif(!in_array($type_sales,$arrType_sales)):
						$status 	= false;
						$message 	= "Use only Selling Type Yes and No";
						break;
					elseif($type_sales == "nonsell" && $sellingprice>0):
						$status 	= false;
						$message 	= "Should fild Selling Price is Zero, because Selling Type is "." No with Product Name is ".$name;
						break;
					elseif($ck_code>0):
						$ck_product_type = $this->db->count_all("ps_product where CompanyID = '$CompanyID' and Code = '$code' and Position = '0' and ProductType = '$type_product'");
						$ck_sales_type 	 = $this->db->count_all("ps_product where CompanyID = '$CompanyID' and Code = '$code' and Position = '0' and SalesType = '$type_sales'");
						if($ck_product_type<=0): // cek jika update product type tidak boleh brubah
							$status 	 = false;
							$message 	 = "Update cannot change Inventory type";
							break;
						elseif($ck_sales_type<=0): // cek jika update sales type tidak boleh brubah
							$status 	 = false;
							$message 	 = "Update cannot change Selling type";
							break;
						endif;
					endif;
				endif;
				
			}

			$arrData = array();
			if($status):
				for ($row = 2; $row <= $highestRow; $row++){
					$rowData 		= $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);

				if($page == "category"):
				    $code       	= $rowData[0][0];
				    $name			= $rowData[0][1];     
				    $level			= $rowData[0][2];     
				    $parentcode		= $rowData[0][3];     

				    $data     = array(
				    	'UserID'		=> $this->session->id_user,
						'CompanyID'		=> $this->session->companyid,
				    	'Code'			=> substr($code,0,10),
				       	'Name' 			=> $name,
						'Position' 		=> $level,
						'ParentCode' 	=> $parentcode,
				    );

				    $cek = $this->db->count_all("ps_product where CompanyID = '$CompanyID' and Position != 0 and Code = '$code'");
				    if($cek>0):
				    	$data["user_ch"] = $this->session->nama;
						$data["date_ch"] = date("Y-m-d H:i:s");
						$this->db->where("Code", $code);
						$this->db->where("CompanyID", $CompanyID);
						$this->db->update("ps_product", $data);
				    else:
				    	$data["User_Add"] = $this->session->nama;
						$data["User_Ch"] = date("Y-m-d H:i:s");
						$this->db->insert("ps_product",$data);
				    endif;

				elseif($page == "product"):
					// Product Code	Category Code	Category Name	Level	Product Name	Min. Qty	Unit	Konv.	Type	Selling Price
					$code       	= $rowData[0][0];
				    $category_code	= $rowData[0][1];
				    $name			= $rowData[0][2];
				    $min_qty		= $rowData[0][3];
				    $unit			= $rowData[0][4];
				    $sellingprice	= $rowData[0][5];
				    $type_product	= $rowData[0][6];
				    $type_sales		= $rowData[0][7];

				    // pengecekan untuk category
					// $cek_category = $this->db->count_all("ps_product where Code = '$category_code' and CompanyID = '$CompanyID' and position != 0 ");
					// if($cek_category<=0): // ini kondisi untuk insert baru
					// 	$parent_code = 0;
					// 	if($level == 1):
					// 		$parent_code = 0;
					// 	endif;
					// 	$data_category = array(
					// 		"Code"			=> $category_code,
					// 		'CompanyID'		=> $CompanyID,
					// 		'UserID'		=> $this->session->id_user,
					// 		'Name'			=> $category_name,
					// 		'Position' 		=> $level,
					// 		'ParentCode' 	=> $parent_code,
					// 	);
					// 	$this->save($data_category);
					// endif;

					if($type_product == 'Yes'):
			            $type_product = "item";
			        elseif($type_product == 'No'):
			            $type_product = "service";
			        endif;

			        if($type_sales == 'Yes'):
			            $type_sales = "sell";
			        elseif($type_sales == 'No'):
			            $type_sales = "nonsell";
			        endif;

			        $UnitID = 0;
			        if($type_product == "item"):
			        	$cek_unit = $this->db->count_all("ps_unit where CompanyID = '$CompanyID' and Name = '$unit'");
				        if($cek_unit>0):
				        	$UnitID = $this->main->get_one_column("ps_unit", "UnitID", array("CompanyID" => $CompanyID, "Name" => $unit))->UnitID;
				        else:
				        	$data_unit = array(
				        		"Name"			=> $unit,
				        		"CompanyID" 	=> $CompanyID,
				        		"Conversion"	=> 1,
				        		"active"		=> 1,
				        	);
				        	$data_unit["user_add"] = $this->session->nama;
							$data_unit["date_add"] = date("Y-m-d H:i:s");
							$this->db->insert("ps_unit",$data_unit);
							$UnitID = $this->db->insert_id();
				        endif;
			        endif;

				    $data     = array(
				    	'UserID'		=> $this->session->id_user,
						'CompanyID'		=> $this->session->companyid,
				    	'Code'			=> $code,
						'ParentCode' 	=> $category_code,
				       	'Name' 			=> $name,
						'Position' 		=> 0,
						'minimumstock'  => $min_qty,
						'sellingprice'  => $sellingprice,
						'uom'			=> $unit,
						'Active'		=> 1,
						'TypeCode'		=> 1,
						'ProductType'	=> $type_product,
						'SalesType'		=> $type_sales,
						"UnitID"		=> $UnitID,
				    );

				    $cek_data = $this->db->count_all("ps_product where CompanyID = '$CompanyID' and Code = '$code' and Position = '0'");
					if($cek_data > 0):
						$data["user_ch"] = $this->session->nama;
						$data["date_ch"] = date("Y-m-d H:i:s");
						$this->db->where("CompanyID",$this->session->CompanyID);
						// $this->db->where("userid",$this->session->id_user);
						$this->db->where("Code",$code);
						// $this->db->where("Name",$name);
						$this->db->update("ps_product",$data);
					else:
						if($page == "product"):
							if(!$data["Code"]):
								$code 				= $this->generate_product_code($category_code);
								$data["Code"] 		= $code;// auto generate
								$data["TypeCode"] 	= 0;
							endif;
						endif;
						$data["user_add"] = $this->session->nama;
						$data["date_add"] = date("Y-m-d H:i:s");
						$this->db->insert("ps_product",$data);
						$productid = $this->db->insert_id();
						if($page == "product"):
							$this->copy_branch($productid);
						endif;
					endif;

					if($type_product == "item"):
						$ProductID = $this->main->get_one_column("ps_product", "ProductID",array("CompanyID" => $CompanyID, "Code" => $code, "Position" => 0))->ProductID;
						$this->check_oum($ProductID,$unit,"update");
					endif;

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

	private function check_oum($ProductID,$Uom,$method=""){
		$CompanyID = $this->session->CompanyID;
		$data = array(
			"CompanyID"		=> $this->session->CompanyID,
			"ProductID"		=> $ProductID,
			"Uom"			=> $Uom,
			"Uom2"			=> $Uom,
			"Conversion"	=> 1,
			"Active"		=> 1,
		);
		$cek = $this->db->count_all("ps_product_unit where CompanyID = '$CompanyID' and ProductID = '$ProductID'");
		if($method == "update" && $cek>0):
			#catatan : jika kedepannya akan ada 1 product banyak unit mohon tambahkan ke where oum2 sebelumnya pada kondisi update
			$where  = array(
				"ProductID"	=> $ProductID,
				"CompanyID"	=> $this->session->CompanyID,
			);
			$this->update_uom($where,$data);
		else:
			$this->save_uom($data);
		endif;
	}
	
	public function get_unit($search)
	{
		$this->db->select("UnitID as unitid");
		$this->db->where("CompanyID",$this->session->CompanyID);
		$this->db->where("Name",$search);
		$query = $this->db->get("ps_unit");
		return $query->row();
	}

	public function copy_branch($ProductID)
	{
		$this->db->select("BranchID,Code,CompanyID");
		$this->db->where("CompanyID",$this->session->CompanyID);
		$query = $this->db->get("Branch");
		$data = $query->result();
		foreach($data as $a):
			$data = array(
				"ProductID" 	=> $ProductID,
				"CompanyID" 	=> $a->CompanyID,
				"BranchID" 		=> $a->BranchID,
				"Qty" 			=> 0,
			);
			$this->db->set("User_Add",$this->session->userdata("NAMA"));
			$this->db->set("Date_Add",date("Y-m-d H:i:s"));
			$this->db->insert("PS_Product_Branch",$data);
		endforeach;
	}

	public function label_product_type($type_product,$page="",$id=""){
        if($type_product == 'item'):
            $label  = '<hijau class="dtype'.$id.'" data-status="1">Yes</hijau>';
            if($page == "cetak"):
                $label  = 'item';
            endif;
        else:
            $label  = '<biru class="dtype'.$id.'" data-status="2">No</biru>';
            if($page == "cetak"):
                $label  = 'service';
            endif;
        endif;

        return $label;
    }
    public function label_sales_type($type_sales,$page="",$id=""){
        if($type_sales == 'sell'):
            $label  = '<hijau class="dtype'.$id.'" data-status="1">Yes</hijau>';
            if($page == "cetak"):
                $label  = 'sell';
            endif;
        else:
            $label  = '<biru class="dtype'.$id.'" data-status="2">No</biru>';
            if($page == "cetak"):
                $label  = 'nonsell';
            endif;
        endif;

        return $label;
    }

    public function save_unit($data)
    {
        $this->db->insert("ps_product_unit", $data);
        return $this->db->insert_id();  
    }
}
