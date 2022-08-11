<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attachment extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_attachment",'attachment');
		$this->main->cek_session();
	}

	public function index($ID){
		$CompanyID  = $this->session->CompanyID;
		$ID2 		 = $ID;
		$attachment  = $this->main->label_attachment();
		$format 	 = $attachment["format"];

		#ini untuk session halaman aturan user privileges;
		$arr_type = array('selling','purchase');
		if(in_array($attachment["Type"], $arr_type)):
			$ID = str_replace("-", "/", $ID);
		endif;
		$label_from  = $this->label_from($ID);
		$data["ID"] 			= $ID;
		$data["ID2"] 			= $ID2;
		$data["Type"] 			= $attachment["Type"];
		$data["format"]			= $format;
		$data["from"]			= $label_from[0];
		$data['title']  		= $label_from[1];
		$data['page']			= 'attachment/list';
		$this->load->view('index',$data);
	}
	private function label_from($ID=""){
		$CompanyID 		= $this->session->CompanyID;
		$label = $this->input->get("type");
		$title = '';
		if($label == "selling"):
			$label 		= '<a href="'.site_url()."selling".'">Selling</a>';
			$title 		= "Attachment Selling Transaction No ".$ID;
		elseif($label == "product"):
			$label 		= '<a href="'.site_url()."product".'">Product</a>';
			$title 		= "Attachment product Product No ".$ID;
		else:
			$label = '<a href="#">Master</a>';
			$title = 'Attachment';
		endif;
		$data = array($label,$title);
		return $data;
	}

	public function save(){
		$ID 	= $this->input->post("ID");
		$Type 	= $this->input->post("Type");

		if($Type == "selling"):
			$config['allowed_types'] 	= '*';
		elseif($Type == "product"):
			$config['allowed_types'] 	= '*';
		else:
			$config['allowed_types'] 	= 'gif|jpg|png|jpeg|bmp|PNG|JPG'; //type yang dapat diakses bisa anda sesuaikan
		endif;
		$config['upload_path'] 		= './img/attachment'; //path folder 
		$config['max_size'] 		= '9999'; //maksimum besar file 2M 
		$config['max_width'] 		= '9999'; //lebar maksimum 1288 px 
		$config['max_height'] 		= '9999'; //tinggi maksimu 768 px 

		$files = $_FILES;
		$data_res = array();
	    for($i=0; $i< count($_FILES['photo']['name']); $i++)
	    {           
	        $_FILES['userfile']['name']= $files['photo']['name'][$i];
	        $_FILES['userfile']['type']= $files['photo']['type'][$i];
	        $_FILES['userfile']['tmp_name']= $files['photo']['tmp_name'][$i];
	        $_FILES['userfile']['error']= $files['photo']['error'][$i];
	        $_FILES['userfile']['size']= $files['photo']['size'][$i];

	        $nmfile 					= "pipesys_".time();
	        $config['file_name'] 		= $nmfile; //nama yang terupload nantinya 
	        $this->upload->initialize($config);
	        $upload =  $this->upload->do_upload();
	        $resizeImage = '';
	    	$gbr 	= $this->upload->data();
	    	if($files['photo']['size'][$i]>2000000):
	        	$info = getimagesize($_FILES["photo"]["tmp_name"][$i]);
	        	$resizeImage = $this->main->resizeImage2($gbr['file_name'],'./img/attachment/',$info);
	        endif;
			$querycek 	= " and ID = '$ID'";
			
			$cek 		= $this->db->count_all("PS_Attachment where Type='$Type' $querycek ");
			$CompanyID 	= $this->session->CompanyID;
			
			if($cek == 0):
				$cek = 1;
			else:
				$cek = 0;
			endif;
			$data = array(
				'CompanyID'			=> $this->session->CompanyID,
				"ID" 				=> $ID,
				"Cek" 				=> 1,
				"Type"				=> $Type,
				"UserAdd" 			=> $this->session->NAMA,
				"DateAdd" 			=> date("Y-m-d H:i:s"),
			);
			if($upload): 		
				$this->attachment->update_attachment_cek($Type,$ID);
				$image 				= "img/attachment/".$gbr['file_name'];
				$data['Image']		= $image;
				$this->db->insert("PS_Attachment",$data);
				$AttachmentID = $this->db->insert_id();
				
				$fileType 	= $this->main->type_file($image);
				$url 		= $image;
				$url_file 	= site_url($image);
				if($fileType != "image"):
				 	$url = $this->main->icon_file($fileType);
			   	endif;

				$res = array(
					"status" 		=> TRUE,
					"pesan" 		=> "Saving data success",
					"AttachmentID" 	=> $AttachmentID,
					'ID'			=> $ID,
					"url_photo" 	=> site_url($url),
					"url_file" 		=> $url_file,
					"caption" 		=> "",
					"cek"			=> 1,
				);
				array_push($data_res, $res);
			else:
				$res = array(
					"status" => FALSE,
					"pesan"  => "Error upload file",
					"error"  => $this->upload->display_errors()
					);
				array_push($data_res, $res);
			endif;
	    }

		$res = array(
			'CompanyID'	=> $this->session->CompanyID,
			'status'	=> true,
			'ID' 		=> $ID,
			'Type' 		=> $Type,
		);

		$this->main->echoJson($data_res);
	}

	public function list($Type,$ID){
		$arr_type = array('selling','purchase');
		if(in_array($Type, $arr_type)):
			$ID = str_replace("-", "/", $ID);
		endif;
		$list = $this->attachment->get_by_id($ID,$Type);
		$data  = array();
		foreach($list as $a):
			$fileType		 = $this->main->type_file($a->Image);
			$url 		= $a->Image;
			$url_file 	= $a->Image;
			if($fileType != "image"):
			 	$url = $this->main->icon_file($fileType);
		   	endif;

			$b["AttachmentID"]	= $a->AttachmentID;
			$b["ID"]			= $a->ID;
			$b["url_photo"] 	= site_url($url);
			$b["url_file"] 		= site_url($url_file);
			$b["cek"] 			= $a->Cek;
			$b["caption"] 		= "";
			array_push($data, $b);
		endforeach;
		$this->main->echoJson($data);
	}

	public function update($id){
		$CompanyID 		= $this->session->CompanyID;
		$Type 	 		= $this->input->post("Type");
		$cek 	 		= $this->input->post("cek");
		$caption 		= $this->input->post("caption");
		$ID 	 		= $this->input->post("ID");
		$AttachmentID 	= $this->input->post("AttachmentID");
		
		if($cek == "ada"):
			$cek = 1;
			$this->attachment->update_attachment_cek($Type,$ID);
		else:
			$cek = 0;
		endif;
		$this->db->set("Cek",$cek);	
		$this->db->where("AttachmentID",$AttachmentID);
		if($Type == "selling"):
			$this->db->where("ID",$ID);
		elseif($Type == "product"):
			$this->db->where("ID",$ID);
		endif;
		$this->db->update("PS_Attachment");
		echo json_encode(array("status" => TRUE));
	}
	public function update_attachment_cek($Type,$ID)
	{
		$this->db->set("Cek",0);
		$this->db->where("Type",$Type);
		$this->db->where("ID",$ID);
		$query = $this->db->update("PS_Attachment");
		if($query): $a = 1; else: $a = 0; endif;
		return $a; 
	}

	public function delete($AttachmentID){
		$CompanyID = $this->session->CompanyID;
		$cek = $this->db->count_all("PS_Attachment where CompanyID = '$CompanyID' and AttachmentID = '$AttachmentID'");
		if($cek>0):
			$this->attachment->delete_file($AttachmentID);
			$this->db->where("AttachmentID",$AttachmentID);
			$this->db->delete("PS_Attachment");
			echo json_encode(array("status" => TRUE));
		else:
			echo json_encode(array("status" => FALSE));
		endif;
	}

	public function save_new_attachment(){
		$CompanyID 	= $this->session->CompanyID;
		$ID 	 	= $this->input->post("ID");
		$type 	 	= $this->input->post("type");
		$file 	 	= $this->input->post("file");
		$filename 	= $this->input->post("filename");
		$size 		= $this->input->post("size");
		$key 		= $this->input->post("key");

		$folder 		= 'img/attachment/';
		$data['status'] = false;
		$data['message']= 'File not found';
		$data['hakakses'] = $this->session->hak_akses;

		if(count($file)>0):
			foreach ($file as $k => $v) {
				$name 	= explode(".", $filename[$k]);
				$nmfile = "pipesys_".time().$k.'.'.$name[1];
				$frame  = str_replace('[removed]', "", $file[$k]);
				$file_d = substr($frame, strpos($frame,"base64,"));
	            $decoded=base64_decode($file_d);
	            if(file_put_contents($folder.$nmfile,$decoded)):
					$querycek 	= " and ID = '$ID'";
					$cek 		= $this->db->count_all("PS_Attachment where CompanyID = '$CompanyID' and Type='$type' $querycek ");
	            	if($cek>0):
	            		$cek = 0;
	            	else:
	            		$cek = 1;
	            	endif;
	            	$data_atach = array(
	            		"CompanyID"	=> $CompanyID,
	            		"ID"		=> $ID,
	            		"Type"		=> $type,
	            		"Cek"		=> $cek,
	            		"Image"		=> $folder.$nmfile,
	            		"Name"		=> $filename[$k],
	            		"UserAdd" 	=> $this->session->NAMA,
						"DateAdd" 	=> date("Y-m-d H:i:s"),
	            	);
	            	$this->db->insert("PS_Attachment",$data_atach);
	            	$id = $this->db->insert_id();

	            	$data['status'] 	= true;
	            	$data['message'] 	= 'Success upload file';
	            	$data['url'][]		= site_url($folder.$nmfile);
	            	$data['key'][] 		= $key[$k];
	            	$data['filename'][]	= $filename[$k];
	            	$data['size'][]		= filesize($folder.$nmfile);
	            	$data['ID'][]		= $id;
	            else:
	            	$data['status'] 	= false;
	            	$data['message'] 	= 'Upload failed';
	            	$data['key'][] 		= $key[$k];
	            endif;	
			}
		endif ;

		$this->main->echoJson($data);
	}
}