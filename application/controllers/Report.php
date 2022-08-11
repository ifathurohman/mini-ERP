<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->main->cek_session();
        $this->load->model("M_report","report");
        $this->load->library("PHPExcel");
        $this->load->library('dompdf_gen'); 
    }
    #good receipt 2018-01-29
    public function index($page = "")
    {   
        $url        = $this->uri->segment(1);
        $id_url     = $this->main->id_menu($url);
        $menu       = $this->main->menu_detail($id_url);
        $this->main->countParentID();
        $Report     = "none";
        $title      = $menu->nama_menu;
        $StartDate  =  date('Y-m-01');
        $EndDate    =  date('Y-m-d');
        if($this->input->post("StartDate")):
            $Report     = $this->input->post("Report");
            $StartDate  = $this->input->post("StartDate");
            $EndDate    = $this->input->post("EndDate");
        endif;
        $v = $this->input->get("v");
        $data["title"]  = $title;
        $data["page"]   = "report/index";
        if($v == "v2"):
            $data["page"]   = "report/indexv2";
        endif;
        $data["Report"]     = $Report;
        $data["StartDate"]  = $StartDate;
        $data["EndDate"]    = $EndDate;
        $data['url']        = $url;
        $this->load->view("index",$data);        
    }
    public function table($page){

        $data[""] = "";

        $nama_laporan   = "";
        $datacompany    = $this->main->company("api");
        $company_name   = $datacompany->nama;
        $this->session->set_userdata("report_page",$page);
        $list = '';
        if($page == "good_receipt"):
            $nama_laporan   = "Good Receipt";
            $table          = "AP_GoodReceipt_Det";
            $list           = $this->report->get_datatables($table);
        elseif($page == "purchase"):
            $nama_laporan   = "Purchase Order";
            $table          = "PS_Purchase";
            $list           = $this->report->get_datatables($table);
        elseif($page == "mutation"):
            $nama_laporan   = "Mutation";
            $table          = "PS_Mutation_Detail";
            $list           = $this->report->get_datatables($table); 
        elseif($page == "selling"):
            $nama_laporan   = "Selling";
            $table          = "PS_Sell_Detail";
            $list           = $this->report->get_datatables($table);   
        elseif($page == "account_receive"):
            $nama_laporan   = "Account Receive";
            $table          = "AC_CorrectionPR_Det";
            $list           = $this->report->get_datatables($table);
        elseif($page == "return"):
            $nama_laporan   = "Return";
            $table          = "AP_Retur";
            $list           = $this->report->get_datatables($table);
        elseif($page == "payment"):
            $nama_laporan   = "Payment";
            $table          = "PS_Payment_Detail";
            $list           = $this->report->get_datatables($table);
        elseif($page == "payment_payable"):
            $nama_laporan   = "Payment Payable";
            $table          = "PS_Payment_Detail";
            $list           = $this->report->get_datatables($table);
        elseif($page == "serial_number"):
            $nama_laporan   = "Serial Number";
            $table          = "PS_Product_Serial";
            $list           = $this->report->get_datatables($table); 
        elseif($page == "correction_stock"):
            $nama_laporan   = "Correction Stock";
            $table          = "PS_Correction";
            $list           = $this->report->get_datatables($table); 
        elseif($page == "stock"):
            $nama_laporan   = "Stock";
            $table          = "";
            $list           = $this->report->stock_report();
         elseif($page == "stock1"):
            $nama_laporan   = "Stock1";
            $table          = "";
            $list           = $this->report->stock_report1();
        elseif($page == "distributor_selling"):
            $nama_laporan   = "Sales Distributor";
            $table          = "PS_Sell";
            $list           = $this->report->get_datatables($table);    
         elseif($page == "outstanding_delivery"):
            $nama_laporan   = "Outstanding Delivery";
            $table          = "PS_Sell_Detail";
            $list           = $this->report->get_datatables($table);
        elseif($page == "return_selling"):
            $nama_laporan   = "Return Selling";
            $table          = "AP_Retur";
            $list           = $this->report->get_datatables($table);
        elseif($page == "return_distributor"):
            $nama_laporan   = "Return Distributor";
            $table          = "AP_Retur";
            $list           = $this->report->get_datatables($table);
        elseif($page == "invoice_customer"):
            $nama_laporan   = "Invoice Customer";
            $table          = "PS_Invoice";
            $list           = $this->report->get_datatables($table);    
        elseif($page == "invoice_vendor"):
            $nama_laporan   = "Invoice Payable";
            $table          = "PS_Invoice";
            $list           = $this->report->get_datatables($table);       
        elseif($page == "sales_book"):
            $nama_laporan   = "Sales Book";
            $table          = "PS_Sell";
            $list           = $this->report->get_datatables($table);
        elseif($page == "purchase_book"):
            $nama_laporan   = "Purchase Book";
            $table          = "PS_Purchase";
            $list           = $this->report->get_datatables($table);
        elseif($page == "voucher"):
            $nama_laporan   = "Voucher";
            $table          = "Voucher";
            $list           = $this->report->get_datatables($table);
        elseif($page == "age_off_debt"):
            $nama_laporan   = "Age Off Debt";
            $list           = "";
        elseif($page == "age_off_credit"):
            $nama_laporan   = "Age Off Credit";
        elseif($page == "correction_ap"):
            $nama_laporan   = "Correction Payable";
            $table          = "AC_BalancePayable";
            $list           = $this->report->get_datatables($table);
        elseif($page == "correction_ar"):
            $nama_laporan   = "Correction Payable";
            $table          = "AC_BalancePayable";
            $list           = $this->report->get_datatables($table);
        elseif($page == "debtors_account"):
            $nama_laporan   = "Debtors Account";
            $table          = "PS_Invoice_Detail";
            $list           = $this->report->select_debtors_account();    
        elseif($page == "creditors_account"):
            $nama_laporan   = "Creditors Account";
            $table          = "PS_Invoice_Detail";
            $list           = $this->report->select_creditors_account();                      
        elseif($page == "saldo_receivable"):
            $nama_laporan   = "Saldo Receivable";
            $table          = "PS_Payment";
            $list           = $this->report->select_saldo_receivable();
         elseif($page == "saldo_ap"):
            $nama_laporan   = "Saldo Payable";
            $table          = "PS_Payment";
            $list           = $this->report->select_saldo_ap();
        elseif($page == "cash"):
            $nama_laporan   = "Cash";
            $table          = "AC_KasBank";
            $list           = $this->report->get_datatables($table);
        elseif($page == "bank"):
            $nama_laporan   = "Bank";
            $table          = "AC_KasBank";
            $list           = $this->report->get_datatables($table);
        elseif($page == "jurnal"):
            $nama_laporan   = "Jurnal";
            $list           = $this->report->select_jurnal();
        elseif($page == "balance_sheet"):
            $nama_laporan   = "Balance Sheet";
            $list           = "";
         #ini untuk salespro
        elseif($page == "sales_visiting"):
            $nama_laporan   = "Routing Employee";
            $table          = "sales_visiting";
            $list           = $this->report->get_datatables($table);   
        elseif($page == "sales_visiting_time"):
            $nama_laporan   = "Employee Visiting Time";
            $table          = "sales_visiting_time";
            $list           = $this->report->get_datatables($table);
            $data["page"]   = $page;   
        elseif($page == "sales_visiting_remark"):
            $page           = "sales_visiting_time";
            $nama_laporan   = "Remark And Note Transaction";
            $table          = "sales_visiting_time";
            $list           = $this->report->get_datatables($table);
            $data["page"]   = "sales_visiting_remark";   
        endif;
        if($this->session->app == "pipesys"):
            $view_table     = "report/table_".$page;
        elseif($this->session->app == "salespro"):
            $view_table     = "report/salespro/table_".$page;
        endif;
        $data['group']          = $this->input->post("group");
        $data['i']              = 1;
        $data['no']             = 1;
        $data['list']           = $list;
        $data["company_name"]   = $company_name;
        $data["title"]          = $nama_laporan;
        $data["nama_laporan"]   = $nama_laporan;
        $data['cetak']          = $this->input->get("cetak");
        $this->load->view($view_table,$data);
    }
    public function cetak($page){
        $nama_laporan   = "";
        $datacompany    = $this->main->company("api");
        $company_name   = $datacompany->nama;
        $company_imgbin = $datacompany->img_bin;
        $company_imgurl = $datacompany->img_url;
        $logo           = $company_imgurl;
        $this->session->set_userdata("report_page",$page);
        $list = '';

        if($page == "good_receipt"):
            $nama_laporan   = $this->lang->line('lb_goodrc');
            $table          = "AP_GoodReceipt_Det";
            $list           = $this->report->get_datatables($table);
        elseif($page == "purchase"):
            $nama_laporan   = $this->lang->line('lb_purchase');
            $table          = "PS_Purchase";
            $list           = $this->report->get_datatables($table);
        elseif($page == "mutation"):
            $nama_laporan   = $this->lang->line('lb_stock_mutation');
            $table          = "PS_Mutation_Detail";
            $list           = $this->report->get_datatables($table); 
        elseif($page == "selling"):
            $nama_laporan   = $this->lang->line('lb_sales_store');
            $table          = "PS_Sell_Detail";
            $list           = $this->report->get_datatables($table);   
        elseif($page == "account_receive"):
            $nama_laporan   = "Account Receive";
            $table          = "AC_CorrectionPR_Det";
            $list           = $this->report->get_datatables($table);
        elseif($page == "return"):
            $nama_laporan   = $this->lang->line('lb_returnap');
            $table          = "AP_Retur";
            $list           = $this->report->get_datatables($table);
        elseif($page == "payment"):
            $nama_laporan   = $this->lang->line('lb_paymentar');
            $table          = "PS_Payment_Detail";
            $list           = $this->report->get_datatables($table); 
        elseif($page == "payment_payable"):
            $nama_laporan   = $this->lang->line('lb_paymentap');
            $table          = "PS_Payment_Detail";
            $list           = $this->report->get_datatables($table);
        elseif($page == "serial_number"):
            $nama_laporan   = $this->lang->line('lb_sn');
            $table          = "PS_Product_Serial";
            $list           = $this->report->serial_number_report($table);
        elseif($page == "correction_stock"):
            $nama_laporan   = $this->lang->line('lb_stock_correction');
            $table          = "PS_Correction";
            $list           = $this->report->get_datatables($table);   
        elseif($page == "stock"):
            $nama_laporan   = "Stock";
            $table          = "";
            $list           = $this->report->stock_report();
        elseif($page == "stock1"):
            $nama_laporan   = $this->lang->line('lb_stock_report');
            $table          = "";
            $list           = $this->report->stock_report1();
        elseif($page == "distributor_selling"):
            $nama_laporan   = $this->lang->line('lb_sales_ho'); 
            $table          = "PS_Sell";
            $list           = $this->report->get_datatables($table);
        elseif($page == "outstanding_delivery"):
            $nama_laporan   = $this->lang->line('lb_outstanding_delivery');
            $table          = "PS_Sell";
            $list           = $this->report->get_datatables($table);
        elseif($page == "sales_book"):
            $nama_laporan   = $this->lang->line('lb_sales_book');
            $table          = "PS_Sell";
            $list           = $this->report->get_datatables($table);
        elseif($page == "purchase_book"):
            $nama_laporan   = $this->lang->line('lb_purchase_book');
            $table          = "PS_Purchase";
            $list           = $this->report->get_datatables($table);
        elseif($page == "voucher"):
            $nama_laporan   = $this->lang->line('lb_voucher');
            $table          = "Voucher";
            $list           = $this->report->get_datatables($table);
        elseif($page == "return_selling"):
            $nama_laporan   = $this->lang->line('lb_returnar');
            $table          = "AP_Retur";
            $list           = $this->report->get_datatables($table);
        elseif($page == "return_distributor"):
            $nama_laporan   = "Return Distributor";
            $table          = "AP_Retur";
            $list           = $this->report->get_datatables($table);  
        elseif($page == "invoice_customer"):
            $nama_laporan   = "$this->lang->line('lb_invoicear')";
            $table          = "PS_Invoice";
            $list           = $this->report->get_datatables($table);    
	    elseif($page == "invoice_vendor"):
	        $nama_laporan   = $this->lang->line('lb_invoiceap');
	        $table          = "PS_Invoice";
	        $list           = $this->report->get_datatables($table);     
        elseif($page == "debtors_account"):
            $nama_laporan   = $this->lang->line('lb_ar_card');
            $table          = "PS_Invoice_Detail";
            $list           = $this->report->select_debtors_account();
        elseif($page == "creditors_account"):
            $nama_laporan   = $this->lang->line('lb_ap_card');
            $table          = "PS_Invoice_Detail";
            $list           = $this->report->select_creditors_account();  
        elseif($page == "saldo_receivable"):
            $nama_laporan   = $this->lang->line('lb_ar_saldo');
            $table          = "PS_Payment";
            $list           = $this->report->select_saldo_receivable();    
        elseif($page == "saldo_ap"):
            $nama_laporan   = $this->lang->line('lb_ap_saldo');
            $table          = "PS_Payment";
            $list           = $this->report->select_saldo_ap();            
        elseif($page == "age_off_debt"):
            $nama_laporan   = $this->lang->line('lb_ar_age');
        elseif($page == "age_off_credit"):
            $nama_laporan   = $this->lang->line('lb_ap_age');
        elseif($page == "correction_ap"):
            $nama_laporan   = $this->lang->line('lb_correctionap');
            $table          = "AC_BalancePayable";
            $list           = $this->report->get_datatables($table);
        elseif($page == "correction_ar"):
            $nama_laporan   = $this->lang->line('lb_correctionar');
            $table          = "AC_BalancePayable";
            $list           = $this->report->get_datatables($table);
        elseif($page == "cash"):
            $nama_laporan   = $this->lang->line('lb_cash');
            $table          = "AC_KasBank";
            $list           = $this->report->get_datatables($table);
        elseif($page == "bank"):
            $nama_laporan   = $this->lang->line('lb_bank');
            $table          = "AC_KasBank";
            $list           = $this->report->get_datatables($table);
        elseif($page == "jurnal"):
            $nama_laporan   = $this->lang->line('lb_journal');
            $list           = $this->report->select_jurnal();
        elseif($page == "balance_sheet"):
            $nama_laporan   = $this->lang->line('lb_balance_sheet');
            $list           = "";
        elseif($page == "loss_and_profit"):
            $nama_laporan   = $this->lang->line('lb_loss_profit');
        elseif($page == "stock_opname"):
            $nama_laporan   = $this->lang->line('lb_stock_opname');
        elseif($page == "stock_receipt"):
            $nama_laporan   = $this->lang->line('lb_stock_receipt');
        elseif($page == "stock_issue"):
            $nama_laporan   = $this->lang->line('lb_stock_issue');
        elseif($page == "ledger"):
            $nama_laporan   = $this->lang->line('lb_ledger');
        #ini untuk salespro
        elseif($page == "sales_visiting"):
            $nama_laporan   = "Routing Employee";
            $table          = "sales_visiting";
            $list           = $this->report->get_datatables($table);   
        elseif($page == "sales_visiting_time"):
            $nama_laporan   = "Employee Visiting Time";
            $table          = "sales_visiting_time";
            $list           = $this->report->get_datatables($table);
            $data["page"]   = $page;   
        elseif($page == "sales_visiting_remark"):
            $page           = "sales_visiting_time";
            $nama_laporan   = "Remark And Note Transaction";
            $table          = "sales_visiting_time";
            $list           = $this->report->get_datatables($table);
            $data["page"]   = "sales_visiting_remark";   
        endif;
        if($this->session->app == "pipesys"):
            $view_table     = "report/table_".$page;
        elseif($this->session->app == "salespro"):
            $view_table     = "report/salespro/table_".$page;
        endif;
        $nama_laporan           = $this->input->get("name");
        $data['group']          = $this->input->post("group");
        $data['i']              = 1;
        $data['no']             = 1;
        $data['list']           = $list;
        $data["company_name"]   = $company_name;
        $data["title"]          = $nama_laporan;
        $data["nama_laporan"]   = $nama_laporan;
        $data["logo"]           = $logo;
        $data["table"]          = $view_table;
        $data['cetak']          = $this->input->get("cetak");
        $data['start_date']     = $this->input->post('start_date');
        $data['end_date']       = $this->input->post('end_date');
        $this->load->view("report/index_cetak",$data);

        if($this->input->get("cetak") == "pdf"):
            $this->load->library('dompdf_gen');
            $html = $this->output->get_output();   
            if($page == "stock" || $page == "serial_number" || $page == "account_receive"):

            else:
                $this->dompdf->set_paper('legal', 'landscape');
            endif;
            $this->dompdf->load_html($html);
            $this->dompdf->render();
            $this->dompdf->stream($nama_laporan."_".date("Ymd_His").".pdf",array('Attachment'=>0));
        endif;
    }
    #good receipt 2018-01-29
    public function good_receipt()
    {
        $data["title"]  = "Good Receipt";
        $data["page"]   = "report/good_receipt/index";
        $this->load->view("index",$data);
    } 
    #good receipt 2018-01-29
    public function good_receipt_list()
    {

        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        #-----------------------------------------------
        $table  = "AP_GoodReceipt_Det";
        $list   = $this->report->get_datatables($table);
        $total1 = 0;
        $total2 = 0;
        $total3 = 0;
        $total4 = 0;
        $total5 = 0;
        $total6 = 0;
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;
        if($group == "all"):
            foreach ($list as $a):
            // $subtotal = $a->subtotal + $a->deliverycost;

                $sub_total  = ($a->price * $a->qty);
                $discount   = $sub_total*($a->discount/100);
                $sub_total  = $sub_total - $discount;
                $tax        = $a->tax;
                $total      = $sub_total;
                $ppn        = 0;
                if($tax == 1):
                    $tax    = 10;
                    $ppn    = $sub_total * (10/100);
                    $total  = $sub_total + $ppn;
                endif;

                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->date));
                $row[]  = $a->receiveno;
                $row[]  = $a->transactioncode;
                $row[]  = $a->receivename;
                $row[]  = $a->branchName;
                $row[]  = $a->product_code;
                $row[]  = $a->product_name;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $a->unit_name;
                $row[]  = (float) $a->conversion;
                $row[]  = $this->main->currency($a->price);
                $row[]  = $this->main->currency($discount);
                $row[]  = $this->main->currency($ppn);
                // $row[]  = $this->main->currency($a->deliverycost);
                $row[]  = $this->main->currency($total);
                $data[] = $row;

                $total1 += $a->price;
                $total2 += $discount;
                $total3 += $ppn;
                $total4 += $a->deliverycost;
                $total5 += $total;
                $total6 += $a->qty;
            endforeach;
        elseif($group == "date"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->date));
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $this->main->currency($a->subtotal);
                $data[] = $row;

                $total5 += $a->subtotal;
                $total6 += $a->qty;
            endforeach;
        elseif($group == "gr_code"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->date));
                $row[]  = $a->receiveno;
                $row[]  = $a->transactioncode;
                $row[]  = $a->receivename;
                $row[]  = $a->branchName;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $this->main->currency($a->sub_total);
                $row[]  = $this->main->currency($a->total_discount);
                $row[]  = $this->main->currency($a->TotalPPN);
                $row[]  = $this->main->currency($a->DeliveryCost);
                $row[]  = $this->main->currency($a->payment);
                $data[] = $row;

                $total1 += $a->sub_total;
                $total2 += $a->total_discount;
                $total3 += $a->TotalPPN;
                $total4 += $a->DeliveryCost;
                $total5 += $a->payment;
                $total6 += $a->qty;
            endforeach;
        elseif($group == "purchase_code"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->date));
                $row[]  = $a->PurchaseNo;
                $row[]  = $a->receiveno;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $this->main->currency($a->subtotal);
                $data[] = $row;

                $total5 += $a->subtotal;
                $total6 += $a->qty;
            endforeach;
        elseif($group == "receipt_name"):
            $list = $this->report->good_receipt_manage($list,"VendorID","receivename");
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->receivename;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $this->main->currency($a->subtotal);
                $data[] = $row;

                $total5 += $a->subtotal;
                $total6 += $a->qty;
            endforeach;
        elseif($group == "product_name"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->product_code;
                $row[]  = $a->product_name;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $a->unit_name;
                $row[]  = (float) $a->Conversion;
                $data[] = $row;

                $total6 += $a->qty;
            endforeach;
        elseif($group == "store"):
            $list = $this->report->good_receipt_manage($list,"BranchID","branchName");
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->branchName;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $this->main->currency($a->subtotal);
                $data[] = $row;

                $total5 += $a->subtotal;
                $total6 += $a->qty;
            endforeach;
        endif;
        $output = array(
            "draw"            => $this->input->post("draw"),//$_POST['draw'],
            "recordsTotal"    => $this->report->count_all($table),
            "recordsFiltered" => $this->report->count_filtered($table),
            "data"            => $data,
            "total1"          => $this->main->currency($total1),
            "total2"          => $this->main->currency($total2),
            "total3"          => $this->main->currency($total3),
            "total4"          => $this->main->currency($total4),
            "total5"          => $this->main->currency($total5),
            "total6"          => $this->main->qty($total6),
        );
        // header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }
    #good receipt 2018-01-29
    public function good_receipt_print()
    {
        $data["title"]  = "Good Receipt";
        $this->load->view("report/good_receipt/print",$data);
    } 
    
    #purchase 2019-04-08
    public function purchase_list(){
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;

        $table   = "PS_Purchase";
        $list    = $this->report->get_datatables($table);
        $total1  = 0;
        $total2  = 0;
        $total3  = 0;
        $total4  = 0;
        $total5  = 0;
        $total6  = 0;
        $total7  = 0;
        $total8  = 0;
        $total9  = 0;
        $total10 = 0;
        $total11 = 0;

        if($group == "all"):
            foreach ($list as $a) {
                $sub_total  = ($a->price * $a->qty) - $a->discount_value;
                $tax        = $a->tax;
                $total      = $sub_total;
                $ppn        = 0;
                if($tax == 1):
                    $tax    = 10;
                    $ppn    = $sub_total * (10/100);
                    $total  = $sub_total + $ppn;
                endif;

                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->PurchaseNo;
                $row[]  = $a->Date;
                $row[]  = $a->vendor_name;
                $row[]  = $a->branchName;
                $row[]  = $a->product_code;
                $row[]  = $a->product_name;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $a->unit_name;
                $row[]  = (float) $a->conversion;
                $row[]  = $this->main->currency($a->price);
                $row[]  = (float) $a->discount;
                $row[]  = $this->main->currency($a->discount_value);
                $row[]  = $this->main->currency($sub_total);
                $row[]  = $tax.' %';
                $row[]  = $this->main->currency($ppn);
                $row[]  = $this->main->currency($total);
                $row[]  = $a->sales_name;
                $row[]  = $a->remark;

                $data[] = $row;

                $total8  += $a->qty;
                $total9  += '';
                $total10 += '';
                $total1  += $a->price;
                $total2  += '';
                $total3  += $a->discount_value;
                $total4  += $sub_total;
                $total5  += '';
                $total6  += $ppn;
                $total7  += $total;
            }
        elseif($group == "gr_purchase"):
            foreach ($list as $a) {
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->PurchaseNo;
                $row[]  = $a->Date;
                $row[]  = $a->vendor_name;
                $row[]  = $a->branchName;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $this->main->currency($a->subtotal);
                $row[]  = $this->main->currency($a->discount);
                $row[]  = $this->main->currency($a->TotalPPN);
                $row[]  = $this->main->currency($a->DeliveryCost);
                $row[]  = $this->main->currency($a->payment);
                $row[]  = $a->sales_name;
                $row[]  = $a->remark;

                $data[] = $row;

                $total8 += $a->qty;
                $total1 += $a->subtotal;
                $total3 += $a->discount;
                $total4 += $a->TotalPPN;
                $total6 += $a->DeliveryCost;
                $total7 += $a->payment;
            }
        elseif($group == "product_name"):
            foreach ($list as $a) {
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->product_code;
                $row[]  = $a->product_name;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $a->unit_name;
                $row[]  = (float) $a->conversion;

                $data[] = $row;
                $total8 += $a->qty;
            }
        elseif($group == "vendor"):
            foreach ($list as $a) {
                $no++;
                $row    = array();
                $row[]  = $i++;
              	$row[]  = $a->vendor_name;
                $row[]  = $a->totalpurchase;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $this->main->currency($a->price);
                $row[]  = $this->main->currency($a->total);         
         
                $data[] = $row;

                $total11 += $a->totalpurchase;
                $total8 += $a->qty;
                $total3 += $a->price;   
                $total4 += $a->total;
            }
        elseif($group == "store"):
            foreach ($list as $a) {
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->branchName;
                $row[]  = $a->totalpurchase;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $this->main->currency($a->price);
                $row[]  = $this->main->currency($a->total);         
         
                $data[] = $row;

                $total11 += $a->totalpurchase;
                $total8 += $a->qty;
                $total3 += $a->price;   
                $total4 += $a->total;
            }
        endif;


        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => $this->report->count_all($table),
            "recordsFiltered" => $this->report->count_filtered($table),
            "data"            => $data,
            "total1"          => $this->main->currency($total1),
            "total2"          => '',
            "total3"          => $this->main->currency($total3),
            "total4"          => $this->main->currency($total4),
            "total5"          => '',
            "total6"          => $this->main->currency($total6),
            "total7"          => $this->main->currency($total7),
            "total8"          => $this->main->qty($total8),
            "total9"          => '',
            "total10"         => '',
            "total11"         => $total11,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);
    }

    public function correction_stock_list(){
        $report = $this->input->post("report");
        $group  = $this->input->post("group");

        $table  = "PS_Correction";
        $list   = $this->report->get_datatables($table);
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;

        $totalQty   = 0;
        $totalQty2  = 0;
        $totalTransaction = 0;
        if($group == "all"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->CorrectionNo;
                $row[]  = date("Y-m-d",strtotime($a->Date));
                $row[]  = $a->branchName;
                $row[]  = $a->product_code;
                $row[]  = $a->product_name;
                $row[]  = $this->main->qty($a->Qty);
                $row[]  = $this->main->qty($a->CorrectionQty);

                $data[] = $row;

                $totalQty += $a->Qty;
                $totalQty2 += $a->CorrectionQty;
            endforeach;
        elseif($group == "transaction"):
            foreach ($list as $a) {
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->CorrectionNo;
                $row[]  = date("Y-m-d",strtotime($a->Date));
                $row[]  = $a->branchName;
                $row[]  = $this->main->qty($a->Qty);
                $row[]  = $this->main->qty($a->CorrectionQty);

                $data[] = $row;

                $totalQty += $a->Qty;
                $totalQty2 += $a->CorrectionQty;
            }
        elseif($group == "store"):
            foreach ($list as $a) {
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->branchName;
                $row[]  = $a->totalTransaction;
                $row[]  = $this->main->qty($a->Qty);
                $row[]  = $this->main->qty($a->CorrectionQty);

                $data[] = $row;

                $totalQty += $a->Qty;
                $totalQty2 += $a->CorrectionQty;
            }
        endif;

        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => $this->report->count_all($table),
            "recordsFiltered" => $this->report->count_filtered($table),
            "data"            => $data,
            "totalQty"        => $this->main->qty($totalQty),
            "totalQty2"       => $this->main->qty($totalQty2),
            "totalTransaction"=> $totalTransaction,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT); 
    }

    #mutation 2018-01-29
    public function mutation_list()
    {

        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        #-----------------------------------------------
        $table  = "PS_Mutation_Detail";
        $list   = $this->report->get_datatables($table);
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;

        if($group == "all"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->date));
                $row[]  = $a->mutationno;
                $row[]  = $a->mutationfrom;
                $row[]  = $a->mutationto;
                $row[]  = $a->product_code;
                $row[]  = $a->product_name;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $a->unit_name;
                $row[]  = $a->conversion;
                $row[]  = $this->main->currency($a->price);
                $row[]  = $this->main->currency($a->subtotal);
                $data[] = $row;
            endforeach;
        elseif($group == "date"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->date));
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $this->main->currency($a->price);
                $row[]  = $this->main->currency($a->subtotal);
                $data[] = $row;
            endforeach;
        elseif($group == "mutation_code"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->mutationno;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $this->main->currency($a->price);
                $row[]  = $this->main->currency($a->subtotal);
                $data[] = $row;
            endforeach;
        elseif($group == "mutation_from" || $group == "mutation_to"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->mutationfrom;
                $row[]  = $a->mutationto;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $this->main->currency($a->price);
                $row[]  = $this->main->currency($a->subtotal);
                $data[] = $row;
            endforeach;
        endif;
        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => $this->report->count_all($table),
            "recordsFiltered" => $this->report->count_filtered($table),
            "data"            => $data,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }
    #payment 2018-01-29
    public function payment_list()
    {

        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        #-----------------------------------------------
        $table  = "PS_Payment_Detail";
        $list   = $this->report->get_datatables($table);
        $total1 = 0;
        $total2 = 0;
        $total3 = 0;
        $total4 = 0;
        $total5 = 0;
        $total6 = 0;
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;

        if($group == "all"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->date));
                $row[]  = $a->paymentno;
                $row[]  = $a->vendorName;
                $row[]  = date("Y-m-d",strtotime($a->transactionDate));
                $row[]  = $a->sellno;
                $row[]  = $a->store_name;
                $row[]  = $this->main->currency($a->grandtotal);
                $row[]  = $this->main->currency($a->unpaid);
                $row[]  = $this->main->currency($a->total_payment);
                $data[] = $row;

                $total1 += $a->grandtotal;
                $total2 += $a->unpaid;
                $total6 += $a->total_payment;                

            endforeach;
        elseif($group == "date"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->date));
                $row[]  = $this->main->currency($a->grandtotal);
                $row[]  = $this->main->currency($a->giro);
                $row[]  = $this->main->currency($a->credit);
                $row[]  = $this->main->currency($a->cash);
                $row[]  = $this->main->currency($a->addcost);
                $row[]  = $this->main->currency($a->total_payment);
                $data[] = $row;

                $total1 += $a->grandtotal;
                $total2 += $a->giro;
                $total3 += $a->credit;
                $total4 += $a->cash;
                $total5 += $a->addcost;
                $total6 += $a->total_payment;

            endforeach;
        elseif($group == "payment_code"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->Date));
                $row[]  = $a->paymentno;
                $row[]  = $this->main->currency($a->grandtotal);
                $row[]  = $this->main->currency($a->giro);
                $row[]  = $this->main->currency($a->credit);
                $row[]  = $this->main->currency($a->cash);
                $row[]  = $this->main->currency($a->addcost);
                $row[]  = $this->main->currency($a->total_payment);
                $data[] = $row;

                $total1 += $a->grandtotal;
                $total2 += $a->giro;
                $total3 += $a->credit;
                $total4 += $a->cash;
                $total5 += $a->addcost;
                $total6 += $a->total_payment;

            endforeach;
        elseif($group == "store_name"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->store_name;
                $row[]  = $this->main->currency($a->grandtotal);
                $row[]  = $this->main->currency($a->giro);
                $row[]  = $this->main->currency($a->credit);
                $row[]  = $this->main->currency($a->cash);
                $row[]  = $this->main->currency($a->addcost);
                $row[]  = $this->main->currency($a->total_payment);
                $data[] = $row;

                $total1 += $a->grandtotal;
                $total2 += $a->giro;
                $total3 += $a->credit;
                $total4 += $a->cash;
                $total5 += $a->addcost;
                $total6 += $a->total_payment;

            endforeach;
        elseif($group == "sales_code"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->sellno;
                $row[]  = $this->main->currency($a->grandtotal);
                $row[]  = $this->main->currency($a->giro);
                $row[]  = $this->main->currency($a->credit);
                $row[]  = $this->main->currency($a->cash);
                $row[]  = $this->main->currency($a->addcost);
                $row[]  = $this->main->currency($a->total_payment);
                $data[] = $row;

                $total1 += $a->grandtotal;
                $total2 += $a->giro;
                $total3 += $a->credit;
                $total4 += $a->cash;
                $total5 += $a->addcost;
                $total6 += $a->total_payment;

            endforeach;
        endif;
        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => $this->report->count_all($table),
            "recordsFiltered" => $this->report->count_filtered($table),
            "data"            => $data,
            "total1"          => $this->main->currency($total1),
            "total2"          => $this->main->currency($total2),
            "total3"          => $this->main->currency($total3),
            "total4"          => $this->main->currency($total4),
            "total5"          => $this->main->currency($total5),
            "total6"          => $this->main->currency($total6),
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }

    public function payment_payable_list()
    {

        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        #-----------------------------------------------
        $table  = "PS_Payment_Detail";
        $list   = $this->report->get_datatables($table);
        $total1 = 0;
        $total2 = 0;
        $total3 = 0;
        $total4 = 0;
        $total5 = 0;
        $total6 = 0;
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;

        if($group == "all"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->date));
                $row[]  = $a->paymentno;
                $row[]  = $a->vendorName;
                $row[]  = $a->transactionDate;
                $row[]  = $a->transactionCode;
                $row[]  = $this->main->currency($a->grandtotal);
                $row[]  = $this->main->currency($a->unpaid);
                $row[]  = $this->main->currency($a->total_payment);
                $data[] = $row;

                $total1 += $a->grandtotal;
                $total2 += $a->unpaid;
                $total3 += $a->total_payment;

            endforeach;
        elseif($group == "payment_code"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->Date;
                $row[]  = $a->paymentno;
                $row[]  = $a->vendorName;
                $row[]  = $this->main->currency($a->grandtotal);
                $row[]  = $this->main->currency($a->giro);
                $row[]  = $this->main->currency($a->credit);
                $row[]  = $this->main->currency($a->cash);
                $row[]  = $this->main->currency($a->addcost);
                $row[]  = $this->main->currency($a->total_payment);
                $data[] = $row;

                $total1 += $a->grandtotal;
                $total2 += $a->giro;
                $total3 += $a->credit;
                $total4 += $a->cash;
                $total5 += $a->addcost;
                $total6 += $a->total_payment;

            endforeach;
        endif;
        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => $this->report->count_all($table),
            "recordsFiltered" => $this->report->count_filtered($table),
            "data"            => $data,
            "total1"         => $this->main->currency($total1),
            "total2"          => $this->main->currency($total2),
            "total3"          => $this->main->currency($total3),
            "total4"          => $this->main->currency($total4),
            "total5"          => $this->main->currency($total5),
            "total6"          => $this->main->currency($total6),
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }

    #return 2018-01-29
    public function return_list()
    {

        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        #-----------------------------------------------
        $table  = "AP_Retur";
        $list   = $this->report->get_datatables($table);
        $total1 = 0;
        $total2 = 0;
        $total3 = 0;
        $total4 = 0;
        $total5 = 0;
        $total6 = 0;
        $total7 = 0;
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;

        if($group == "all"):
            foreach ($list as $a):
                $discount   = $this->main->PersenttoRp($a->subtotal,$a->discount);
                $sub_total  = $a->subtotal - $discount;
                if($a->Tax == 1):
                  $tax        = $this->main->PersenttoRp($sub_total,10);
                else:
                  $tax        = 0;
                endif;

                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->returno;
                $row[]  = date("Y-m-d",strtotime($a->date));
                $row[]  = $a->receiveno;
                $row[]  = $a->vendorname;
                $row[]  = $a->branchName;
                $row[]  = $a->product_code;
                $row[]  = $a->product_name;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $a->unit_name;
                $row[]  = (float) $a->conversion;
                $row[]  = $this->main->currency($a->price);
                $row[]  = $this->main->currency($a->subtotal);
                $row[]  = (float) $a->discount.'%';
                $row[]  = $this->main->currency($tax);
                $row[]  = $this->main->currency($a->total);
                $data[] = $row;

                $total6 += $a->qty;
                $total1 += $a->price;
                $total2 += $a->subtotal;
                $total3 += '';
                $total4 += $tax;
                $total5 += $a->total;

            endforeach;
        elseif($group == "date"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->date));
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $this->main->currency($a->subtotal);
                $data[] = $row;
                $total2 += $a->subtotal;
            endforeach;
        elseif($group == "return_code"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->returno;
                $row[]  = $a->date;
                $row[]  = $a->receiveno;
                $row[]  = $a->vendorname;
                $row[]  = $a->branchName;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $this->main->currency($a->subtotal);
                $row[]  = $this->main->currency($a->discount);
                $row[]  = $this->main->currency($a->TotalPPN);
                $row[]  = $this->main->currency($a->total);
                $data[] = $row;
                
                $total2 += $a->subtotal;
                $total3 += $a->discount;
                $total4 += $a->TotalPPN;
                $total5 += $a->total;
                $total6 += $a->qty;
            endforeach;
        elseif($group == "vendor_name"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->vendorname;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $this->main->currency($a->total);
                $row[]  = $a->total_return;
                $data[] = $row;

                $total6 += $a->qty;
                $total5 += $a->total;
                $total7 += $a->total_return;
            endforeach;
        elseif($group == "product_name"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->product_code;
                $row[]  = $a->product_name;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $a->unit_name;
                $row[]  = (float) $a->conversion;
                $data[] = $row;
                $total6 += $a->qty;
            endforeach;
        elseif($group == "store"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->branchName;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $this->main->currency($a->total);
                $row[]  = $a->total_return;
                $data[] = $row;

                $total6 += $a->qty;
                $total5 += $a->total;
                $total7 += $a->total_return;
            endforeach;
        endif;
        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => $this->report->count_all($table),
            "recordsFiltered" => $this->report->count_filtered($table),
            "data"            => $data,
            "total1"          => $this->main->currency($total1),
            "total2"          => $this->main->currency($total2),
            "total3"          => $this->main->currency($total3),
            "total4"          => $this->main->currency($total4),
            "total5"          => $this->main->currency($total5),
            "total6"          => $this->main->qty($total6),
            "total7"          => $total7,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }
    #Account Receive 2018-01-29
    public function account_receive_list()
    {

        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        #-----------------------------------------------
        $table  = "AC_CorrectionPR_Det";
        $list   = $this->report->get_datatables($table);
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;

        if($group == "all"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->date));
                $row[]  = $a->store_name;
                $row[]  = $a->arcode;
                $row[]  = $this->main->currency($a->total);
                $data[] = $row;
            endforeach;
        elseif($group == "date"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->date));
                $row[]  = $this->main->currency($a->total);
                $data[] = $row;
            endforeach;
        elseif($group == "store_name"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->store_name;
                $row[]  = $this->main->currency($a->total);
                $data[] = $row;
            endforeach;
        elseif($group == "ar_code"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->arcode;
                $row[]  = $this->main->currency($a->total);
                $data[] = $row;
            endforeach;
        endif;
        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => $this->report->count_all($table),
            "recordsFiltered" => $this->report->count_filtered($table),
            "data"            => $data,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }
    #Selling 2018-01-29
    public function selling_list()
    {

        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        #-----------------------------------------------
        $table  = "PS_Sell_Detail";
        $list   = $this->report->get_datatables($table);
        $total1 = 0;
        $total2 = 0;
        $total3 = 0;
        $total4 = 0;
        $total5 = 0;
        $total6 = 0;
        $total7 = 0;
        $total8 = 0;
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;

        if($group == "all"):
            foreach ($list as $a):

                $discount   = $this->main->PersenttoRp($a->subtotal,$a->discount);
                $sub_total  = $a->subtotal - $discount;
                if($a->Tax == 1):
                  $tax        = $this->main->PersenttoRp($sub_total,10);
                else:
                  $tax        = 0;
                endif;

                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->date));
                $row[]  = $a->sellno;
                $row[]  = $a->store_name;
                $row[]  = $a->product_code;
                $row[]  = $a->product_name;
                // $row[]  = "";
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $a->unit_name;
                $row[]  = (float) $a->conversion;
                $row[]  = $this->main->currency($a->price);
                $row[]  = $this->main->currency($a->subtotal);
                $row[]  = $this->main->currency($discount);
                $row[]  = $this->main->currency($tax);
                $row[]  = $this->main->currency($a->payment);
                $data[] = $row;

                $total1 += $a->price;
                $total2 += $a->subtotal;
                $total3 += '';
                $total4 += $tax;
                $total5 += $a->payment;
                $total6 += $a->qty;
                $total7 += $a->unit_name;
                $total8 += $a->conversion;
            endforeach;
        elseif($group == "date"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->date));
                $row[]  = "";
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $this->main->currency($a->subtotal);
                $data[] = $row;
                $total6 += $a->qty;
                $total2 += $a->subtotal;
        
            endforeach;
        elseif($group == "store_name"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->store_name;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $this->main->currency($a->payment);
                $data[] = $row;
                $total6 += $a->qty;
                $total5 += $a->payment;
            endforeach;
        elseif($group == "product_name"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->product_code;
                $row[]  = $a->product_name;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $a->unit_name;
                $row[]  = $a->conversion;
                $data[] = $row;
                $total6 += $a->qty;
            endforeach;
        endif;
        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => $this->report->count_all($table),
            "recordsFiltered" => $this->report->count_filtered($table),
            "data"            => $data,
            "total1"          => $this->main->currency($total1),
            "total2"          => $this->main->currency($total2),
            "total3"          => $this->main->currency($total3),
            "total4"          => $this->main->currency($total4),
            "total5"          => $this->main->currency($total5),
            "total6"          => $total6,
            "total7"          => '',
            "total8"          => '',
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }
    public function distributor_selling_list()
    {

        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        #-----------------------------------------------
        $table  = "PS_Sell";
        $list   = $this->report->get_datatables($table);
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;

        $totalQty      = 0;
        $totalPrice    = 0;
        $totalDiscount = 0;
        $totalSubTotal = 0;
        $totalTax      = 0;
        $totalDelivery = 0;
        $totalPayment  = 0;
        $totalTransaction = 0;

        if($group == "all"):
            foreach ($list as $a):
                $ppn        = 0;
                $total_net  = 0;
                if($a->tax == 1):
                    $ppn = $this->main->PersenttoRp($a->TotalPrice, $a->ppn);
                    $total_net = $a->TotalPrice + $ppn;
                endif;

                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->sellno;
                $row[]  = date("Y-m-d",strtotime($a->date));
                $row[]  = $a->customerName;
                $row[]  = $a->branchName;
                $row[]  = $a->DeliveryCity;
                $row[]  = $a->product_code;
                $row[]  = $a->product_name;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $a->unit_name;
                $row[]  = (float) $a->conversion;
                $row[]  = $this->main->currency($a->price);
                $row[]  = (float) $a->diskon;
                $row[]  = $this->main->currency($a->diskonValue);
                $row[]  = $this->main->currency($a->TotalPrice);
                $row[]  = $this->main->label_tax($a->tax);
                $row[]  = $this->main->currency($ppn);
                $row[]  = $this->main->currency($total_net);
                $row[]  = $a->salesName;
                $row[]  = $a->remark;
                $data[] = $row;

                $totalQty       += $a->qty;
                $totalPrice     += $a->price;
                $totalDiscount  += $a->diskonValue;
                $totalSubTotal  += $a->TotalPrice;
                $totalTax       += $ppn;
                $totalPayment   += $total_net;
            endforeach;
        elseif($group == "selling"):
            foreach ($list as $a):
                $no++;

                $ppn        = 0;
                $total_net  = 0;
                if($a->tax == 1):
                    $ppn = $this->main->PersenttoRp($a->TotalPrice, $a->ppn);
                    $total_net = $a->TotalPrice - $ppn;
                endif;

                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->sellno;
                $row[]  = date("Y-m-d",strtotime($a->date));
                $row[]  = $a->customerName;
                $row[]  = $a->branchName;
                $row[]  = $a->DeliveryCity;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $this->main->currency($a->subtotal);
                $row[]  = $this->main->currency($a->totaldiscount);
                $row[]  = $this->main->currency($a->totalppn);
                $row[]  = $this->main->currency($a->deliverycost);
                $row[]  = $this->main->currency($a->payment);
                $row[]  = $a->salesName;
                $row[]  = $a->remark;
                $data[] = $row;
                
                $totalQty       += $a->qty;
                $totalSubTotal  += $a->subtotal;
                $totalDiscount  += $a->totaldiscount;
                $totalTax       += $a->totalppn;
                $totalDelivery  += $a->deliverycost;
                $totalPayment   += $a->payment;
            endforeach;
        elseif($group == "product_name"):
            foreach ($list as $a):
                 $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->product_code;
                $row[]  = $a->product_name;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $a->unit_name;
                $row[]  = (float) $a->conversion;
                $data[] = $row;

                $totalQty += $a->qty;
            endforeach;
        elseif($group == "vendor"):
            foreach ($list as $a) {
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->vendor_name;
                $row[]  = $a->totalTransaction;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $this->main->currency($a->price);
                $row[]  = $this->main->currency($a->payment);         
                $data[] = $row;

                $totalQty       += $a->qty;
                $totalPrice     += $a->price;
                $totalPayment   += $a->payment;
                $totalTransaction += $a->totalTransaction;
            }
        elseif($group == "store"):
            foreach ($list as $a) {
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->branchName;
                $row[]  = $a->totalTransaction;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $this->main->currency($a->price);
                $row[]  = $this->main->currency($a->payment);         
                $data[] = $row;

                $totalQty       += $a->qty;
                $totalPrice     += $a->price;
                $totalPayment   += $a->payment;
                $totalTransaction += $a->totalTransaction;
            }
        endif;

        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => $this->report->count_all($table),
            "recordsFiltered" => $this->report->count_filtered($table),
            "data"            => $data,
            "totalQty"        => $this->main->qty($totalQty),
            "totalPrice"      => $this->main->currency($totalPrice),
            "totalDiscount"   => $this->main->currency($totalDiscount),
            "totalSubTotal"   => $this->main->currency($totalSubTotal),
            "totalTax"        => $this->main->currency($totalTax),
            "totalPayment"    => $this->main->currency($totalPayment),
            "totalDelivery"   => $this->main->currency($totalDelivery),
            "totalTransaction" => $totalTransaction,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }

    public function outstanding_delivery_list()
    {

        $report = $this->input->post("report");
        $group  = $this->input->post("group");	
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        #-----------------------------------------------
        $table  = "PS_Sell";
        $list   = $this->report->get_datatables($table);
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;

        $totalQty           = 0;
        $totalQtyDelivery   = 0;
        $totalQtyResidue    = 0;
        if($group == "all"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->SellNo;
                $row[]  = date("Y-m-d",strtotime($a->Date));
                $row[]  = $a->vendorName;
                $row[]  = $a->branchName;
                $row[]  = $a->productCode;
                $row[]  = $a->productName;
                $row[]  = $this->main->qty($a->Qty);
                $row[]  = $this->main->qty($a->DeliveryQty);
                $row[]  = $this->main->qty($a->qtyResidue);
                $data[] = $row;

                $totalQty           += $a->Qty;
                $totalQtyDelivery   += $a->DeliveryQty;
                $totalQtyResidue    += $a->qtyResidue;
            endforeach;
        elseif($group == "transaction"):
            foreach ($list as $a):
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->SellNo;
                $row[]  = date("Y-m-d",strtotime($a->Date));
                $row[]  = $a->vendorName;
                $row[]  = $a->branchName;
                $row[]  = $this->main->qty($a->Qty);
                $row[]  = $this->main->qty($a->DeliveryQty);
                $row[]  = $this->main->qty($a->qtyResidue);
                $data[] = $row;

                $totalQty           += $a->Qty;
                $totalQtyDelivery   += $a->DeliveryQty;
                $totalQtyResidue    += $a->qtyResidue;

            endforeach;
        elseif($group == "product_name"):
            foreach ($list as $a):
                 $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->product_code;
                $row[]  = $a->product_name;
                $row[]  = $this->main->qty($a->Qty);
                $row[]  = $this->main->qty($a->DeliveryQty);
                $row[]  = $this->main->qty($a->qtyResidue);
                $row[]  = $a->unit_name;
                $row[]  = (float) $a->conversion;
                $data[] = $row;

                $totalQty           += $a->Qty;
                $totalQtyDelivery   += $a->DeliveryQty;
                $totalQtyResidue    += $a->qtyResidue;
            endforeach;
        elseif($group == "store"):
            foreach ($list as $a):
                 $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->branchName;
                $row[]  = $this->main->qty($a->Qty);
                $row[]  = $this->main->qty($a->DeliveryQty);
                $row[]  = $this->main->qty($a->qtyResidue);
                $data[] = $row;

                $totalQty           += $a->Qty;
                $totalQtyDelivery   += $a->DeliveryQty;
                $totalQtyResidue    += $a->qtyResidue;
            endforeach;
        endif;
        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => $this->report->count_all($table),
            "recordsFiltered" => $this->report->count_filtered($table),
            "data"            => $data,
            "totalQty"        => $this->main->qty($totalQty),
            "totalQtyDelivery" => $this->main->qty($totalQtyDelivery),
            "totalQtyResidue"  => $this->main->qty($totalQtyResidue),
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }

    public function return_selling_list()
    {

        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        #-----------------------------------------------
        $table  = "AP_Retur";
        $list   = $this->report->get_datatables($table);
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;

        $totalQty      = 0;
        $totalPrice    = 0;
        $totalDiscount = 0;
        $totalSubTotal = 0;
        $totalTax      = 0;
        $totalPayment  = 0;
        if($group == "all"):
            foreach ($list as $a):
                $no++;

                $sub_total  = $a->qty * $a->price;
                $sub_total  = $sub_total - $a->discount;

                if($a->tax == 1):
                  $tax        = $this->main->PersenttoRp($sub_total,10);
                else:
                  $tax        = 0;
                endif;

                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->date));
                $row[]  = $a->returno;
                $row[]  = date("Y-m-d",strtotime($a->sellDate));
                $row[]  = $a->branch;
                $row[]  = $a->SellNo;
                $row[]  = $a->vendorname;
                $row[]  = $a->product_code;
                $row[]  = $a->product_name;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $a->unit_name;
                $row[]  = $a->conversion;
                $row[]  = $this->main->currency($a->price);
                $row[]  = $this->main->currency($a->discount);
                $row[]  = $this->main->currency($tax);
                $row[]  = $this->main->currency($a->total);
                
                $row[]  = $a->remark;
                $data[] = $row;

                $totalQty       += $a->qty;
                $totalPrice     += $a->price;
                $totalDiscount  += $a->discount;
                $totalTax       += $tax;
                $totalPayment   += $a->total;

            endforeach;
        elseif($group == "selling"):
            foreach ($list as $a):
                $no++;

                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->date));
                $row[]  = $a->returno;
                $row[]  = date("Y-m-d",strtotime($a->sellDate));
                $row[]  = $a->SellNo;
                $row[]  = $a->vendorname;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $this->main->currency($a->total);
                $row[]  = $a->remark;
                $row[]  = $a->sales_name;
                $data[] = $row;

                $totalQty       += $a->qty;
                $totalPayment   += $a->total;
            endforeach;
        endif;

        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => $this->report->count_all($table),
            "recordsFiltered" => $this->report->count_filtered($table),
            "data"            => $data,
            "totalQty"        => $this->main->qty($totalQty),
            "totalPrice"      => $this->main->currency($totalPrice),
            "totalDiscount"   => $this->main->currency($totalDiscount),
            "totalSubTotal"   => $this->main->currency($totalSubTotal),
            "totalTax"        => $this->main->currency($totalTax),
            "totalPayment"    => $this->main->currency($totalPayment),
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }

    public function invoice_customer_list()
    {

        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        #-----------------------------------------------
        $table  = "PS_Invoice";
        $list   = $this->report->get_datatables($table);
        $total1 = 0;
        $total2 = 0;
        $total3 = 0;
        $total4 = 0;
        $total5 = 0;
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;

        if($group == "all"):
            foreach ($list as $a):
                $no++;

                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->Date));
                $row[]  = $a->InvoiceNo;
                $row[]  = $a->transactionCode;
                $row[]  = date("Y-m-d",strtotime($a->transactionDate));
                $row[]  = $a->Name;
                $row[]  = $this->main->currency($a->Subtotal);
                $row[]  = $this->main->currency($a->Discount);
                $row[]  = $this->main->currency($a->PPN);
                $row[]  = $this->main->currency($a->DeliveryCost);
                $row[]  = $this->main->currency($a->Total);
                $row[]  = $a->Remark;
                $data[] = $row;

                $total1 += $a->Subtotal;
                $total2 += $a->Discount;
                $total3 += $a->PPN;
                $total4 += $a->DeliveryCost;
                $total5 += $a->Total;

            endforeach;
        elseif($group == "transaction"):
            foreach ($list as $a):
                $no++;

                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->Date));
                $row[]  = $a->InvoiceNo;
                $row[]  = $a->Name;
                $row[]  = $this->main->currency($a->Subtotal);
                $row[]  = $this->main->currency($a->Discount);
                $row[]  = $this->main->currency($a->PPN);
                $row[]  = $this->main->currency($a->DeliveryCost);
                $row[]  = $this->main->currency($a->Total);
                $row[]  = $a->Remark;
                $data[] = $row;

                $total1 += $a->Subtotal;
                $total2 += $a->Discount;
                $total3 += $a->PPN;
                $total4 += $a->DeliveryCost;
                $total5 += $a->Total;

            endforeach;
        endif;
        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => $this->report->count_all($table),
            "recordsFiltered" => $this->report->count_filtered($table),
            "data"            => $data,
            "total1"          => $this->main->currency($total1),
            "total2"          => $this->main->currency($total2),
            "total3"          => $this->main->currency($total3),
            "total4"          => $this->main->currency($total4),
            "total5"          => $this->main->currency($total5),
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }

    public function invoice_vendor_list()
    {

        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        #-----------------------------------------------
        $table  = "PS_Invoice";
        $list   = $this->report->get_datatables($table);
        $total1 = 0;
        $total2 = 0;
        $total3 = 0;
        $total4 = 0;
        $total5 = 0;
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;

        if($group == "all"):
            foreach ($list as $a) {
                $no++;

                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->Date));
                $row[]  = $a->InvoiceNo;
                $row[]  = $a->transactionCode;
                $row[]  = date("Y-m-d",strtotime($a->transactionDate));
                $row[]  = $a->Name;
                $row[]  = $this->main->currency($a->Subtotal);
                $row[]  = $this->main->currency($a->Discount);
                $row[]  = $this->main->currency($a->PPN);
                $row[]  = $this->main->currency($a->DeliveryCost);
                $row[]  = $this->main->currency($a->Total);
                // $row[]  = $a->Remark;
                $data[] = $row;

                $total1 += $a->Subtotal;
                $total2 += $a->Discount;
                $total3 += $a->PPN;
                $total4 += $a->DeliveryCost;
                $total5 += $a->Total;

            }

        elseif($group == "transaction"):
        
            foreach ($list as $a) {
                $no++;

                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->Date));
                $row[]  = $a->InvoiceNo;
                $row[]  = $a->Name;
                $row[]  = $this->main->currency($a->Subtotal);
                $row[]  = $this->main->currency($a->Discount);
                $row[]  = $this->main->currency($a->PPN);
                $row[]  = $this->main->currency($a->DeliveryCost);
                $row[]  = $this->main->currency($a->Total);
                // $row[]  = $a->Remark;
                $data[] = $row;

                $total1 += $a->Subtotal;
                $total2 += $a->Discount;
                $total3 += $a->PPN;
                $total4 += $a->DeliveryCost;
                $total5 += $a->Total;

            }

        endif;
        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => $this->report->count_all($table),
            "recordsFiltered" => $this->report->count_filtered($table),
            "data"            => $data,
            "total1"          => $this->main->currency($total1),
            "total2"          => $this->main->currency($total2),
            "total3"          => $this->main->currency($total3),
            "total4"          => $this->main->currency($total4),
            "total5"          => $this->main->currency($total5),
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }

     public function return_distributor_list()
    {

        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        #-----------------------------------------------
        $table  = "AP_Retur";
        $list   = $this->report->get_datatables($table);
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;

        if($group == "all"):
            foreach ($list as $a):
                $no++;

                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->date));
                $row[]  = $a->returno;
                $row[]  = $a->sellDate;
                $row[]  = $a->branch;
                $row[]  = $a->SellNo;
                $row[]  = $a->vendorname;
                $row[]  = $a->product_code;
                $row[]  = $a->product_name;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $a->unit_name;
                $row[]  = $a->conversion;
                $row[]  = $a->remark;
                $data[] = $row;
            endforeach;
        elseif($group == "distributor"):
            foreach ($list as $a):
                $no++;

                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->date));
                $row[]  = $a->returno;
                $row[]  = $a->sellDate;
                $row[]  = $a->branch;
                $row[]  = $a->SellNo;
                $row[]  = $a->vendorname;
                $row[]  = $this->main->qty($a->qty);
                $row[]  = $this->main->currency($a->total_qty);
                $row[]  = $a->remark;
                $row[]  = $a->sales_name;
                $data[] = $row;
            endforeach;
        endif;
        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => $this->report->count_all($table),
            "recordsFiltered" => $this->report->count_filtered($table),
            "data"            => $data,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }

    public function correction_ap_list()
    {

        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        #-----------------------------------------------
        $table  = "AC_BalancePayable";
        $list   = $this->report->get_datatables($table);
        $total1 = 0;
        $total2 = 0;
        $total3 = 0;
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;
        // $Total1 = 0;
        if($group == "all"):
            foreach ($list as $a):

                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->Code;
                $row[]  = $a->vendorName;
                $row[]  = date("Y-m-d",strtotime($a->Date));
                $row[]  = $this->main->currency($a->totalpayment);
                $row[]  = $this->main->currency($a->totalcorrection);
                $row[]  = $this->main->currency($a->total);
                $data[] = $row;

                $total1 += $a->totalpayment;
                $total2 += $a->totalcorrection;
                $total3 += $a->total;
        
            endforeach;
        elseif($group == "transaction"):

            foreach ($list as $a):

                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->Code;
                $row[]  = $a->vendorName;
                $row[]  = date("Y-m-d",strtotime($a->Date));
                $row[]  = $this->main->currency($a->totalpayment);
                $row[]  = $this->main->currency($a->TotalCorrection);
                $row[]  = $this->main->currency($a->total);
                $data[] = $row;

                $total1 += $a->totalpayment;
                $total2 += $a->TotalCorrection;
                $total3 += $a->total;

            endforeach;
        elseif($group == "vendor"):

            foreach ($list as $a):
              
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->vendorName;
                $row[]  = $this->main->currency($a->TotalCorrection);
                $data[] = $row;


                $total2 += $a->TotalCorrection;

            endforeach;
        endif;
        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => $this->report->count_all($table),
            "recordsFiltered" => $this->report->count_filtered($table),
            "data"            => $data,
            "total1"          => $this->main->currency($total1),
            "total2"          => $this->main->currency($total2),
            "total3"          => $this->main->currency($total3),
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }

    public function correction_ar_list()
    {

        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        #-----------------------------------------------
        $table  = "AC_BalancePayable";
        $list   = $this->report->get_datatables($table);
        $total1 = 0;
        $total2 = 0;
        $total3 = 0;
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;
        // $Total1 = 0;
        if($group == "all"):
            foreach ($list as $a):

                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->Code;
                $row[]  = $a->vendorName;
                $row[]  = date("Y-m-d",strtotime($a->Date));
                $row[]  = $this->main->currency($a->totalpayment);
                $row[]  = $this->main->currency($a->totalcorrection);
                $row[]  = $this->main->currency($a->total);
                $data[] = $row;

                $total1 += $a->totalpayment;
                $total2 += $a->totalcorrection;
                $total3 += $a->total;

            endforeach;
        elseif($group == "transaction"):

            foreach ($list as $a):

                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->Code;
                $row[]  = $a->vendorName;
                $row[]  = date("Y-m-d",strtotime($a->Date));
                $row[]  = $this->main->currency($a->totalpayment);
                $row[]  = $this->main->currency($a->TotalCorrection);
                $row[]  = $this->main->currency($a->total);
                $data[] = $row;

                $total1 += $a->totalpayment;
                $total2 += $a->TotalCorrection;
                $total3 += $a->total;

            endforeach;
        elseif($group == "vendor"):

            foreach ($list as $a):
              
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->vendorName;
                $row[]  = $this->main->currency($a->TotalCorrection);
                $data[] = $row;


                $total2 += $a->TotalCorrection;
                
            endforeach;
        endif;
        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => $this->report->count_all($table),
            "recordsFiltered" => $this->report->count_filtered($table),
            "data"            => $data,
            "total1"          => $this->main->currency($total1),
            "total2"          => $this->main->currency($total2),
            "total3"          => $this->main->currency($total3),
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }

    public function sales_book_list()
    {

        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        #-----------------------------------------------
        $table  = "PS_Sell";
        $list   = $this->report->get_datatables($table);
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;
        $total1 = 0;
        $total2 = 0;
        $total3 = 0;
        $total4 = 0;
        $total5 = 0;
        $total6 = 0;
        $total7 = 0;
      

            $grandtotal1  = 0;
            $total_Saldo  = 0;
            foreach ($list as $a):
                $total_Saldo  = $a->total - $a->grandtotal;
                	
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->date));
                $row[]  = $a->transactionCode;
                $row[]  = $a->customerName;
                $row[]  = $this->main->currency($a->subtotal);
                $row[]  = $this->main->currency($a->diskon);
                $row[]  = $this->main->label_tax($a->tax);
                $row[]  = $this->main->currency($a->deliverycost);
                $row[]  = $this->main->currency($a->total);
                $row[]  = $this->main->currency($a->grandtotal);
                $row[]  = $this->main->currency($total_Saldo);
                $data[] = $row;

                $total1 += $a->subtotal;
                $total2 += $a->diskon;
                $total3 += '';
                $total4 += $a->deliverycost;
                $total5 += $a->total;
                $total6 += $a->grandtotal;
                $total7 += $total_Saldo;
        
            endforeach;
        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => $this->report->count_all($table),
            "recordsFiltered" => $this->report->count_filtered($table),
            "data"            => $data,
            "total1"          => $this->main->currency($total1),
            "total2"          => $this->main->currency($total2),
            "total3"          => '',
            "total4"          => $this->main->currency($total4),
            "total5"          => $this->main->currency($total5),
            "total6"          => $this->main->currency($total6),
            "total7"          => $this->main->currency($total7),
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }

    public function purchase_book_list()
    {

        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        #-----------------------------------------------
        $table  = "PS_Purchase";
        $list   = $this->report->get_datatables($table);
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;
        $total1 = 0;
        $total2 = 0;
        $total3 = 0;
        $total4 = 0;
        $total5 = 0;
        $total6 = 0;
        $total7 = 0;

            $grandtotal1  = 0;
            $total_Saldo  = 0;
            foreach ($list as $a):
                $total_Saldo  = $a->total - $a->grandtotal;
                    
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->date));
                $row[]  = $a->transactionCode;
                $row[]  = $a->customerName;
                $row[]  = $this->main->currency($a->subtotal);
                $row[]  = $this->main->currency($a->diskon);
                $row[]  = (float) $a->tax;
                $row[]  = $this->main->currency($a->deliverycost);
                $row[]  = $this->main->currency($a->total);
                $row[]  = $this->main->currency($a->grandtotal);
                $row[]  = $this->main->currency($total_Saldo);
                $data[] = $row;

                $total1 += $a->subtotal;
                $total2 += $a->diskon;
                $total3 += '';
                $total4 += $a->deliverycost;
                $total5 += $a->total;
                $total6 += $a->grandtotal;
                $total7 += $total_Saldo;

            endforeach;

        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => $this->report->count_all($table),
            "recordsFiltered" => $this->report->count_filtered($table),
            "data"            => $data,
            "total1"          => $this->main->currency($total1),
            "total2"          => $this->main->currency($total2),
            "total3"          => '',
            "total4"          => $this->main->currency($total4),
            "total5"          => $this->main->currency($total5),
            "total6"          => $this->main->currency($total6),
            "total7"          => $this->main->currency($total7),
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }

    public function voucher_list()
    {

        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        #-----------------------------------------------
        $table  = "Voucher";
        $list   = $this->report->get_datatables($table);
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;
        $total1 = 0;
        $total2 = 0;
        $total3 = 0;
        $total4 = 0;
        $total5 = 0;
        $total6 = 0;
        $total7 = 0;

            foreach ($list as $a):
                $qty        = $a->parentQty;
                $QtyModule  = $a->Qty;
                if($a->Module == "android"):
                    $qty = $a->Qty;
                    $QtyModule = 0;
                endif;
                    
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->Date));
                $row[]  = $a->Code;
                $row[]  = $a->Type;
                $row[]  = number_format($qty,0);
                $row[]  = number_format($QtyModule,0);
                $row[]  = "IDR ".number_format($a->Price,0,".",",");
                $row[]  = "IDR ".number_format($a->PriceModule,0,".",",");
                $row[]  = "IDR ".number_format($a->TotalPrice,0,".",",");
                $row[]  = $a->nama;
                $data[] = $row;

                // $total1 += $a->subtotal;
                // $total2 += $a->diskon;
                // $total3 += '';
                // $total4 += $a->deliverycost;
                // $total5 += $a->total;
                // $total6 += $a->grandtotal;
                // $total7 += $total_Saldo;

            endforeach;

        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => $this->report->count_all($table),
            "recordsFiltered" => $this->report->count_filtered($table),
            "data"            => $data,
            // "total1"          => $this->main->currency($total1),
            // "total2"          => $this->main->currency($total2),
            // "total3"          => '',
            // "total4"          => $this->main->currency($total4),
            // "total5"          => $this->main->currency($total5),
            // "total6"          => $this->main->currency($total6),
            // "total7"          => $this->main->currency($total7),
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }

    public function saldo_receivable_list()
    {

        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        #-----------------------------------------------
        $table  = "PS_Payment";
        $list   = $this->report->select_saldo_receivable();
        $total1 = 0;
        $total2 = 0;
        $total3 = 0;
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;
        if($group == "all"):
            foreach ($list as $a):

                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->Supplier;
                $row[]  = $this->main->currency($a->Saldo);
                $data[] = $row;
                $total3 += $a->Saldo;
            endforeach;
        elseif($group == "transaction"):
            foreach ($list as $a):
                                                                                                                                                
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->Tanggal));
                $row[]  = $a->supplier;
                $row[]  = $a->notransaksi;
                $row[]  = $this->main->currency($a->Totalnota);
                $row[]  = $this->main->currency($a->Bayar);
                $row[]  = $this->main->currency($a->Saldo);
                $data[] = $row;
                $total1 += $a->Totalnota;
                $total2 += $a->Bayar;
                $total3 += $a->Saldo;
            endforeach;
        endif;
    
        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => '',
            "recordsFiltered" => '',
            "data"            => $data,
            "total1"          => $this->main->currency($total1),
            "total2"          => $this->main->currency($total2),
            "total3"          => $this->main->currency($total3),
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }

    public function saldo_ap_list()
    {

        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        #-----------------------------------------------
        $table  = "PS_Payment";
        $list   = $this->report->select_saldo_ap();
        $total1 = 0;
        $total2 = 0;
        $total3 = 0;
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;
        if($group == "all"):
            foreach ($list as $a):
                if($a->VendorID):
                    $no++;
                    $row    = array();
                    $row[]  = $i++;
                    $row[]  = $a->Supplier;
                    $row[]  = $this->main->currency($a->Saldo);
                    $data[] = $row;

                    $total3 += $a->Saldo;
                endif;
            endforeach;
        elseif($group == "transaction"):
            foreach ($list as $a):
                if($a->supplier):                                                                                                                                   
                    $no++;
                    $row    = array();
                    $row[]  = $i++;
                    $row[]  = date("Y-m-d",strtotime($a->Tanggal));
                    $row[]  = $a->supplier;
                    $row[]  = $a->notransaksi;
                    $row[]  = $this->main->currency($a->Totalnota);
                    $row[]  = $a->noInvoice;
                    $row[]  = $this->main->currency($a->Bayar);
                    $row[]  = $this->main->currency($a->Saldo);
                    $data[] = $row;

                    $total1 += $a->Totalnota;
                    $total2 += $a->Bayar;
                    $total3 += $a->Saldo;
                endif;

            endforeach;
        endif;
    
        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => '',
            "recordsFiltered" => '',
            "data"            => $data,
            "total1"          => $this->main->currency($total1),
            "total2"          => $this->main->currency($total2),
            "total3"          => $this->main->currency($total3),
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }

     public function debtors_account_list()
    {

        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        #-----------------------------------------------
        $table  = "PS_Invoice_Detail";
        $list   = $this->report->select_debtors_account();
        $total1 = 0;
        $total2 = 0;
        $total3 = 0;
        $total4 = 0;
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;
           	
            foreach ($list as $a):
                   
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->Tanggal));
                $row[]  = $a->nobukti;
                $row[]  = $a->nopengiriman;
                $row[]  = $a->Customer;
                $row[]  = $this->main->currency($a->Awal);
                $row[]  = $this->main->currency($a->Debit);
                $row[]  = $this->main->currency($a->Kredit);
                $row[]  = $this->main->currency($a->Saldo);
                $data[] = $row;

                $total1    += $a->Awal;
                $total2    += $a->Debit;
                $total3    += $a->Kredit;
                $total4    += $a->Saldo;

            endforeach;

        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => '',
            "recordsFiltered" => '',
            "data"            => $data,
            "total1"          => $this->main->currency($total1),
            "total2"          => $this->main->currency($total2),
            "total3"          => $this->main->currency($total3),
            "total4"          => $this->main->currency($total4),
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }

     public function creditors_account_list()
    {

        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        #-----------------------------------------------
        $table  = "PS_Invoice_Detail";
        $list   = $this->report->select_creditors_account();
        $total1 = 0;
        $total2 = 0;
        $total3 = 0;
        $total4 = 0;
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;
          
            foreach ($list as $a):
                   
                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->Tanggal));
                $row[]  = $a->nobukti;
                $row[]  = $a->nopengiriman;
                $row[]  = $a->Supplier;
                $row[]  = $this->main->currency($a->Awal);
                $row[]  = $this->main->currency($a->Debit);
                $row[]  = $this->main->currency($a->Kredit);
                $row[]  = $this->main->currency($a->Saldo);
                $data[] = $row;

                $total1 += $a->Awal;
                $total2 += $a->Debit;
                $total3 += $a->Kredit;
                $total4 += $a->Saldo;

            endforeach;

        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => '',
            "recordsFiltered" => '',
            "data"            => $data,
            "total1"          => $this->main->currency($total1),
            "total2"          => $this->main->currency($total2),
            "total3"          => $this->main->currency($total3),
            "total4"          => $this->main->currency($total4),
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }
    
    #Stock 2018-01-30
    public function stock_list1()
    {

        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        #-----------------------------------------------
        $table  = "ps_product";
        // $list   = $this->report->get_datatables($table);
        $list   = $this->report->stock_report1();
        $data   = array();
        $no     = $this->input->post('start');
        $i      = 1;

        if($group == "all"):
            foreach ($list as $a):

                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->Nama_Produk;
                $row[]  = $a->Kode;

                $row[]  = $this->main->qty($a->awal);
                $row[]  = $this->main->qty($a->masuk);
                $row[]  = $this->main->qty($a->keluar);
                $row[]  = $this->main->qty($a->akhir);
                // $row[]  = $a->unit_name;
                // $row[]  = $a->conversion;
                // $row[]  = $this->main->qty($a->min_qty);
                $data[] = $row;
            endforeach;
        elseif($group == "transaction"):
            foreach ($list as $a):

                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->no_bukti;
                // $row[]  = $a->transaksi;
                $row[]  = date("Y-m-d",strtotime($a->tanggal));
                $row[]  = $a->Nama_Produk;
                $row[]  = $a->Kode;

                $row[]  = $this->main->qty($a->awal);
                $row[]  = $this->main->qty($a->masuk);
                $row[]  = $this->main->qty($a->keluar);
                $row[]  = $this->main->qty($a->akhir);

                // $row[]  = $a->keterangan;
                // $row[]  = $a->unit_name;
                // $row[]  = $a->conversion;
                // $row[]  = $this->main->qty($a->min_qty);
                $data[] = $row;
            endforeach;
          endif;
        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => "",
            "recordsFiltered" => "",
            "data"            => $data,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }

    #Stock 2018-01-30
    public function stock_list()
    {

        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        #-----------------------------------------------
        $table  = "ps_product";
        // $list   = $this->report->get_datatables($table);
        $list   = $this->report->stock_report();
        $data   = array();
        $no     = $this->input->post('start');
        $i      = 1;
        foreach ($list as $a):
            $initial = $this->report->stock_initial($a->productid,$a->date,$a->conversion,$a->unitid);
            if($initial){
                $initial = $initial->qty;
            } else {
                $initial = 0;
            }
            $last = $initial + $a->qty;
            $no++;
            $row    = array();
            $row[]  = $i++;
            $row[]  = date("Y-m-d",strtotime($a->date));
            $row[]  = $a->product_name;
            $row[]  = $a->category_code;
            $row[]  = $a->category_name;
            $row[]  = $this->main->qty($initial);
            $row[]  = $this->main->qty($a->qty_in);
            $row[]  = $this->main->qty($a->qty_out);
            $row[]  = $this->main->qty($last);
            $row[]  = $a->unit_name;
            $row[]  = $a->conversion;
            $row[]  = $this->main->qty($a->min_qty);
            $data[] = $row;
        endforeach;
        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => "",
            "recordsFiltered" => "",
            "data"            => $data,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }
    #Selling 2018-02-03
    public function serial_number_list()
    {
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        #-----------------------------------------------
        $table  = "PS_Product_Serial";
        $list   = $this->report->serial_number_report();
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;
        foreach ($list as $a):
            $no++;
            $row    = array();
            $row[]  = $i++;
            if($group != "all"):
                $row[]  = date("Y-m-d",strtotime($a->date));
            endif;
            if($group == "mutation"):
                $row[] = '<td>'.$a->branchName_from.'</td>';
                $row[] = '<td>'.$a->branchName_to.'</td>';
            else:
                $row[] = '<td>'.$a->branchName.'</td>';
            endif;
            $row[]  = $a->product_code;
            $row[]  = $a->product_name;
            // $row[]  = $this->main->qty($a->qty);
            $row[]  = $a->type_serial;
            $row[]  = $a->serialnumber;
            $data[] = $row;
        endforeach;
            
        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => $this->report->count_all($table),
            "recordsFiltered" => $this->report->count_filtered($table),
            "data"            => $data,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }
    #2018-04-02
     public function sales_visiting_list()
    {
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        #-----------------------------------------------
        $table  = "sales_visiting";
        $list   = $this->report->get_datatables($table);
        // echo "<pre>"; print_r($list);
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;


        if($group == "all"):
            $origin    = "0";
            $total_km  = 0;
            $sales     = ""; 
            foreach ($list as $a):
                $onclick1 = "'routing_sales',".$a->ID;
                $onclick2 = "'sales_visit',".$a->ID;
                $action = '<div class="btn-group btn-group-xs" role="group">';
                $action .= '<a href="javascript:void(0)" class="btn btn-default btn-outline" role="menuitem" onclick="modal_visit('.$onclick1.')">view</a>';
                $action .= '</div>';
                
                if(empty($a->VendorID)):
                    $Latlng     = $a->CheckInLatlng;
                else:
                    $Lat        = $a->Lat;
                    $Lng        = $a->Lng;
                    $Latlng     = $Lat.",".$Lng;
                endif;

                if($sales != $a->BranchID):
                    $total_km = 0;
                    $origin   = "0";
                endif;
                $sales = $a->BranchID;

                $distance = $this->main->Distance($origin,$Latlng);
                $km     = $distance["km"];
                $value  = $distance["value"];
                $total_km    = $total_km+$value;

                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = $a->Code;
                $row[]  = date("Y-m-d",strtotime($a->Date));
                $row[]  = $a->SalesName;
                $row[]  = $a->customer;
                $row[]  = $this->main->convertSelisih($a->total_checkin);
                $row[]  = number_format(($value/1000), 1)." KM";;
                $row[]  = number_format(($total_km/1000), 1)." KM";

                if($this->session->ParentID>0):
                $row[]  = $a->nama;
                endif;
                
                // $row[]  = $action;
                $data[] = $row;
                $origin = $Latlng;
            endforeach;
        elseif($group == "date"):
            foreach ($list as $a):
                $onclick1 = "'routing_sales',".$a->ID.",'".$a->Date."'";
                $onclick2 = "'sales_visit',".$a->ID;
                $action = '<div class="btn-group btn-group-xs" role="group">';
                $action .= '<a href="javascript:void(0)" class="btn btn-default btn-outline" role="menuitem" onclick="modal_visit('.$onclick1.')">view</a>';
                $action .= '</div>';
                
                $km     = $this->main->Distance($a->ID,"TransactionRouteIDArray",$a->Date)["km"];

                $no++;
                $row    = array();
                $row[]  = $i++;
                $row[]  = date("Y-m-d",strtotime($a->Date));
                $row[]  = $a->SalesName;
                $row[]  = $a->total_visit;
                $row[]  = $km;

                if($this->session->ParentID>0):
                $row[]  = $a->nama;
                endif;

                $row[]  = $action;
                $data[] = $row;
            endforeach;
        endif;
        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => $this->report->count_all($table),
            "recordsFiltered" => $this->report->count_filtered($table),
            "data"            => $data,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }
    public function sales_visiting_time_list($page = "")
    {
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        #-----------------------------------------------
        $table  = "sales_visiting_time";
        $list   = $this->report->get_datatables($table);
        // echo "<pre>"; print_r($list);
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;
        $total_route        = 0;
        $total_route_miss   = 0;
        $total_planning     = 0;
        $total_not_planning = 0;
        foreach ($list as $a):
            $CheckIn    = "";
            $CheckOut   = "";
            $duration   = "";
            $action     = "";
            $onclick1   = "'sales_visit',".$a->ID;
            $action = '<div class="btn-group btn-group-xs" role="group">';
            $action .= '<a href="javascript:void(0)" class="btn btn-default btn-outline" role="menuitem" onclick="modal_visit('.$onclick1.')">view</a>';
            
            if(!$a->CustomerName):
                $action .= '<a href="javascript:void(0)" class="btn btn-primary" role="menuitem" onclick="save_customer('.$a->ID.')">Save as Customer</a>';
            endif;

            $action .= '</div>';

            if($a->CheckIn && $a->CheckOut):
                $duration = $this->main->selisih_waktu(date("Y-m-d H:i",strtotime($a->CheckIn)),date("Y-m-d H:i",strtotime($a->CheckOut)));
            endif;
            if($a->CheckIn):
                $CheckIn = date("H:i",strtotime($a->CheckIn));
            endif;
            if($a->CheckOut):
                $CheckOut = date("H:i",strtotime($a->CheckOut));
            endif;
            
            $total_route = $total_route+1;
            if($a->CustomerName):
                $total_planning = $total_planning+1;
            else:
                $total_not_planning = $total_not_planning+1;
            endif;
            if(!$a->CheckIn):
                $total_route_miss = $total_route_miss+1;
            endif;

            $no++;
            $row    = array();
            $row[]  = $i++;
            $row[]  = $a->Code;
            $row[]  = date("Y-m-d",strtotime($a->Date));
            $row[]  = $a->SalesName;
            $row[]  = $a->CustomerName;
            if($page == "time"):
                $row[]  = $a->CustomerAddress;
                $row[]  = $a->CheckInAddress;
                $row[]  = $a->CheckOutAddress;
                $row[]  = $CheckIn;
                $row[]  = $CheckOut;
                $row[]  = $duration;
                
                if($this->session->ParentID>0):
                $row[]  = $a->nama;
                endif;
                
                $row[]  = $action;
            elseif($page == "remark"):
                $row[]  = $a->Remark;
                $row[]  = $a->RemarkSales;

                if($this->session->ParentID>0):
                $row[]  = $a->nama;
                endif;

            endif;
            $data[] = $row;
        endforeach;
        $output = array(
            "draw"             => $this->input->post("draw"),
            "recordsTotal"     => $this->report->count_all($table),
            "recordsFiltered"  => $this->report->count_filtered($table),
            "data"             => $data,
            "total_route"      => $total_route,
            "total_route_miss" => $total_route_miss,
            "total_route_planning"      => $total_planning,
            "total_route_not_planning"  => $total_not_planning,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }
   


    #ini untuk excell
    #2018-02-05 iqbal
    public function good_receipt_excell(){
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        
        $nama_laporan   = $this->lang->line('lb_goodrc');
        $nama_laporan   = $this->input->get("name");
        $table          = "AP_GoodReceipt_Det";
        $list           = $this->report->get_datatables($table);   
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
        if($group == "all"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:O".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','O') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_date'))
                        ->setCellValue('C1', $this->lang->line('lb_goodrcno'))
                        ->setCellValue('D1', $this->lang->line('lb_transaction_no'))
                        ->setCellValue('E1', $this->lang->line('lb_vendor_name'))
                        ->setCellValue('F1', $this->lang->line('lb_store'))
                        ->setCellValue('G1', $this->lang->line('lb_product_code'))
                        ->setCellValue('H1', $this->lang->line('lb_product_name'))
                        ->setCellValue('I1', $this->lang->line('lb_qty'))
                        ->setCellValue('J1', $this->lang->line('lb_unit'))
                        ->setCellValue('K1', $this->lang->line('lb_conversion'))
                        ->setCellValue('L1', $this->lang->line('price'))
                        ->setCellValue('M1', $this->lang->line('lb_discount'))
                        ->setCellValue('N1', $this->lang->line('lb_tax'))
                        ->setCellValue('O1', $this->lang->line('lb_sub_total'));
            $total_qty      = 0;
            $total_price    = 0;
            $total_discount = 0;
            $total_tax      = 0;
            $total_delivery = 0;
            $subtotal       = 0;
            foreach ($list as $a) {
                $no     = $i++; 
                $urut   = $ii++;

                $sub_total  = ($a->price * $a->qty);
                $discount   = $sub_total*($a->discount/100);
                $sub_total  = $sub_total - $discount;
                $tax        = $a->tax;
                $total      = $sub_total;
                $ppn        = 0;
                if($tax == 1):
                    $tax    = 10;
                    $ppn    = $sub_total * (10/100);
                    $total  = $sub_total + $ppn;
                endif;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->date)
                            ->setCellValue('C'.$urut, $a->transactioncode)
                            ->setCellValue('D'.$urut, $a->receiveno)
                            ->setCellValue('E'.$urut, $a->receivename)
                            ->setCellValue('F'.$urut, $a->branchName)
                            ->setCellValue('G'.$urut, $a->product_code)
                            ->setCellValue('H'.$urut, $a->product_name)
                            ->setCellValue('I'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('J'.$urut, $a->unit_name)
                            ->setCellValue('K'.$urut, $a->conversion)
                            ->setCellValue('L'.$urut, $this->main->currency($a->price))
                            ->setCellValue('M'.$urut, $this->main->currency($discount))
                            ->setCellValue('N'.$urut, $this->main->currency($ppn))
                            ->setCellValue('O'.$urut, $this->main->currency($a->subtotal));

                $total_qty      += $a->qty;
                $total_price    += $a->price;
                $total_discount += $discount;
                $total_tax      += $ppn;
                $total_delivery += $a->deliverycost;
                $subtotal       += $a->subtotal;
            }
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':H'.$urut)
                            ->setCellValue('I'.$urut, $this->main->qty($total_qty))
                            ->setCellValue('L'.$urut, $this->main->currency($total_price))
                            ->setCellValue('M'.$urut, $this->main->currency($total_discount))
                            ->setCellValue('N'.$urut, $this->main->currency($total_tax))
                            ->setCellValue('O'.$urut, $this->main->currency($subtotal));
        elseif($group == "date"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:D".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','D') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_date'))
                        ->setCellValue('C1', $this->lang->line('lb_qty'))
                        ->setCellValue('D1', $this->lang->line('lb_sub_total'));
            $total_qty      = 0;
            $subtotal       = 0;
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->date)
                            ->setCellValue('C'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('D'.$urut, $this->main->currency($a->subtotal));
                $total_qty    += $a->qty;
                $subtotal     += $a->subtotal;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':B'.$urut)
                            ->setCellValue('C'.$urut, $this->main->qty($total_qty))
                            ->setCellValue('D'.$urut, $this->main->currency($subtotal));
        elseif($group == "gr_code"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:L".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','L') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_date'))
                        ->setCellValue('C1', $this->lang->line('lb_goodrcno'))
                        ->setCellValue('D1', $this->lang->line('lb_transaction_no'))
                        ->setCellValue('E1', $this->lang->line('lb_vendor_name'))
                        ->setCellValue('F1', $this->lang->line('lb_store'))
                        ->setCellValue('G1', $this->lang->line('lb_qty_total'))
                        ->setCellValue('H1', $this->lang->line('lb_sub_total'))
                        ->setCellValue('I1', $this->lang->line('lb_discount_total'))
                        ->setCellValue('J1', $this->lang->line('lb_tax'))
                        ->setCellValue('K1', $this->lang->line('lb_delivery_cost'))
                        ->setCellValue('L1', $this->lang->line('lb_total')); 
            $total_qty      = 0;
            $subtotal       = 0;   
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->date)
                            ->setCellValue('C'.$urut, $a->receiveno)
                            ->setCellValue('D'.$urut, $a->transactioncode)
                            ->setCellValue('E'.$urut, $a->receivename)
                            ->setCellValue('F'.$urut, $a->branchName)
                            ->setCellValue('G'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('H'.$urut, $this->main->currency($a->sub_total))
                            ->setCellValue('I'.$urut, $this->main->currency($a->total_discount))
                            ->setCellValue('J'.$urut, $this->main->currency($a->TotalPPN))
                            ->setCellValue('K'.$urut, $this->main->currency($a->DeliveryCost))
                            ->setCellValue('L'.$urut, $this->main->currency($a->payment));

                $total_qty      += $a->qty;
                $total_price    += $a->sub_total;
                $total_discount += $a->total_discount;
                $total_tax      += $a->TotalPPN;
                $total_delivery += $a->DeliveryCost;
                $subtotal       += $a->payment;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':F'.$urut)
                            ->setCellValue('G'.$urut, $this->main->qty($total_qty))
                            ->setCellValue('H'.$urut, $this->main->currency($total_price))
                            ->setCellValue('I'.$urut, $this->main->currency($total_discount))
                            ->setCellValue('J'.$urut, $this->main->currency($total_tax))
                            ->setCellValue('K'.$urut, $this->main->currency($total_delivery))
                            ->setCellValue('L'.$urut, $this->main->currency($subtotal));
        elseif($group == "purchase_code"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:F".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','F') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_date'))
                        ->setCellValue('C1', $this->lang->line('lb_purchaseno'))
                        ->setCellValue('D1', $this->lang->line('lb_goodrcno'))
                        ->setCellValue('E1', $this->lang->line('lb_qty'))
                        ->setCellValue('F1', $this->lang->line('lb_total'));
            $total_qty      = 0;
            $subtotal       = 0;   
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->date)
                            ->setCellValue('C'.$urut, $a->PurchaseNo)
                            ->setCellValue('D'.$urut, $a->receiveno)
                            ->setCellValue('E'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('F'.$urut, $this->main->currency($a->subtotal));
                $total_qty    += $a->qty;
                $subtotal     += $a->subtotal;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':D'.$urut)
                            ->setCellValue('E'.$urut, $this->main->qty($total_qty))
                            ->setCellValue('F'.$urut, $this->main->currency($subtotal));
        elseif($group == "receipt_name"):
            $list = $this->report->good_receipt_manage($list,"VendorID","receivename");
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:D".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','D') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_receipt_name'))
                        ->setCellValue('C1', $this->lang->line('lb_qty'))
                        ->setCellValue('D1', $this->lang->line('lb_sub_total'));
            $total_qty      = 0;
            $subtotal       = 0;   
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->receivename)
                            ->setCellValue('C'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('D'.$urut, $this->main->currency($a->subtotal));
                $total_qty    += $a->qty;
                $subtotal     += $a->subtotal;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':B'.$urut)
                            ->setCellValue('C'.$urut, $this->main->qty($total_qty))
                            ->setCellValue('D'.$urut, $this->main->currency($subtotal));
        elseif($group == "product_name"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:F".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','F') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_product_code'))
                        ->setCellValue('C1', $this->lang->line('lb_product_name'))
                        ->setCellValue('D1', $this->lang->line('lb_qty'))
                        ->setCellValue('E1', $this->lang->line('lb_unit'))
                        ->setCellValue('F1', $this->lang->line('lb_conversion'));
            $total_qty      = 0;
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->product_code)
                            ->setCellValue('C'.$urut, $a->product_name)
                            ->setCellValue('D'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('E'.$urut, $a->unit_name)
                            ->setCellValue('F'.$urut, $a->Conversion);
                $total_qty    += $a->qty;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':C'.$urut)
                            ->setCellValue('D'.$urut, $this->main->qty($total_qty));
        elseif($group == "store"):
            $list = $this->report->good_receipt_manage($list,"BranchID","branchName");
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:D".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','D') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_store'))
                        ->setCellValue('C1', $this->lang->line('lb_qty'))
                        ->setCellValue('D1', $this->lang->line('lb_sub_total'));
            $total_qty      = 0;
            $subtotal       = 0;   
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->branchName)
                            ->setCellValue('C'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('D'.$urut, $this->main->currency($a->subtotal));
                $total_qty    += $a->qty;
                $subtotal     += $a->subtotal;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':B'.$urut)
                            ->setCellValue('C'.$urut, $this->main->qty($total_qty))
                            ->setCellValue('D'.$urut, $this->main->currency($subtotal));
        endif;
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

    public function purchase_excell(){
        $report = $this->input->post("report");
        $group  = $this->input->post("group");

        $nama_laporan   = $this->lang->line('lb_purchase');
        $nama_laporan   = $this->input->get("name");
        $table          = "PS_Purchase";
        $list           = $this->report->get_datatables($table);

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
        if($group == "all"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:S".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','S') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:S1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_purchaseno'))
                        ->setCellValue('C1', $this->lang->line('lb_date'))
                        ->setCellValue('D1', $this->lang->line('lb_vendor_name'))
                        ->setCellValue('E1', $this->lang->line('lb_store'))
                        ->setCellValue('F1', $this->lang->line('lb_product_code'))
                        ->setCellValue('G1', $this->lang->line('lb_product_name'))
                        ->setCellValue('H1', $this->lang->line('lb_qty'))
                        ->setCellValue('I1', $this->lang->line('lb_unit'))
                        ->setCellValue('J1', $this->lang->line('lb_conversion'))
                        ->setCellValue('K1', $this->lang->line('price'))
                        ->setCellValue('L1', $this->lang->line('lb_discount').' (%)')
                        ->setCellValue('M1', $this->lang->line('lb_discount').' ')
                        ->setCellValue('N1', $this->lang->line('lb_total_bruto'))
                        ->setCellValue('O1', $this->lang->line('lb_tax').' %')
                        ->setCellValue('P1', $this->lang->line('lb_tax').'')
                        ->setCellValue('Q1', $this->lang->line('lb_total_netto'))
                        ->setCellValue('R1', $this->lang->line('lb_sales_name'))
                        ->setCellValue('S1', $this->lang->line('lb_remark'));

            $total_qty      = 0;
            $total_price    = 0;
            $total_discount = 0;
            $total_bruto    = 0;
            $total_tax      = 0;
            $total_netto    = 0;
            foreach ($list as $a):
                $sub_total  = ($a->price * $a->qty) - $a->discount_value;
                $tax        = $a->tax;
                $total      = $sub_total;
                $ppn        = 0;
                if($tax == 1):
                    $tax    = 10;
                    $ppn    = $sub_total * (10/100);
                    $total  = $sub_total + $ppn;
                endif;

                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->PurchaseNo)
                            ->setCellValue('C'.$urut, $a->Date)
                            ->setCellValue('D'.$urut, $a->vendor_name)
                            ->setCellValue('E'.$urut, $a->branchName)
                            ->setCellValue('F'.$urut, $a->product_code)
                            ->setCellValue('G'.$urut, $a->product_name)
                            ->setCellValue('H'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('I'.$urut, $a->unit_name)
                            ->setCellValue('J'.$urut, $a->conversion)
                            ->setCellValue('K'.$urut, $this->main->currency($a->price))
                            ->setCellValue('L'.$urut, $a->discount)
                            ->setCellValue('M'.$urut, $this->main->currency($a->discount_value))
                            ->setCellValue('N'.$urut, $this->main->currency($sub_total))
                            ->setCellValue('O'.$urut, $tax." %")
                            ->setCellValue('P'.$urut, $this->main->currency($ppn))
                            ->setCellValue('Q'.$urut, $this->main->currency($total))
                            ->setCellValue('R'.$urut, $a->sales_name)
                            ->setCellValue('S'.$urut, $a->remark);

                $total_qty      += $a->qty;
                $total_price    += $a->price;
                $total_discount += $a->discount_value;
                $total_bruto    += $sub_total;
                $total_tax      += $ppn;
                $total_netto    += $total;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':G'.$urut)
                                ->setCellValue('H'.$urut, $this->main->qty($total_qty))
                                ->setCellValue('K'.$urut, $this->main->currency($total_price))
                                ->setCellValue('M'.$urut, $this->main->currency($total_discount))
                                ->setCellValue('N'.$urut, $this->main->currency($total_bruto))
                                ->setCellValue('O'.$urut, $this->main->currency($total_tax))
                                ->setCellValue('P'.$urut, $this->main->currency($total_netto));
        elseif($group == "gr_purchase"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:M".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','M') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_purchaseno'))
                        ->setCellValue('C1', $this->lang->line('lb_date'))
                        ->setCellValue('D1', $this->lang->line('lb_vendor_name'))
                        ->setCellValue('E1', $this->lang->line('lb_store'))
                        ->setCellValue('F1', $this->lang->line('lb_qty_total'))
                        ->setCellValue('G1', $this->lang->line('lb_sub_total'))
                        ->setCellValue('H1', $this->lang->line('lb_discount_total'))
                        ->setCellValue('I1', $this->lang->line('lb_tax'))
                        ->setCellValue('J1', $this->lang->line('lb_delivery_cost'))
                        ->setCellValue('K1', $this->lang->line('lb_total'))
                        ->setCellValue('L1', $this->lang->line('lb_sales_name'))
                        ->setCellValue('M1', $this->lang->line('lb_remark'));
            $total_qty      = 0;
            $total_price    = 0;
            $total_discount = 0;
            $total_tax      = 0;
            $total_delivery = 0;
            $total_netto    = 0;
            foreach ($list as $a) {
                $no     = $i++; 
                $urut   = $ii++;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->PurchaseNo)
                            ->setCellValue('C'.$urut, $a->Date)
                            ->setCellValue('D'.$urut, $a->vendor_name)
                            ->setCellValue('E'.$urut, $a->branchName)
                            ->setCellValue('F'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('G'.$urut, $this->main->currency($a->subtotal))
                            ->setCellValue('H'.$urut, $this->main->currency($a->discount))
                            ->setCellValue('I'.$urut, $this->main->currency($a->TotalPPN))
                            ->setCellValue('J'.$urut, $this->main->currency($a->DeliveryCost))
                            ->setCellValue('K'.$urut, $this->main->currency($a->payment))
                            ->setCellValue('L'.$urut, $a->sales_name)
                            ->setCellValue('M'.$urut, $a->remark);

                $total_qty      += $a->qty;
                $total_price    += $a->subtotal;
                $total_discount += $a->discount;
                $total_tax      += $a->TotalPPN;
                $total_delivery += $a->DeliveryCost;
                $total_netto    += $a->payment;
            }
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':E'.$urut)
                                ->setCellValue('F'.$urut, $this->main->qty($total_qty))
                                ->setCellValue('G'.$urut, $this->main->currency($total_price))
                                ->setCellValue('H'.$urut, $this->main->currency($total_discount))
                                ->setCellValue('I'.$urut, $this->main->currency($total_tax))
                                ->setCellValue('J'.$urut, $this->main->currency($total_delivery))
                                ->setCellValue('K'.$urut, $this->main->currency($total_netto));
        elseif($group == "product_name"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:F".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','F') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_product_code'))
                        ->setCellValue('C1', $this->lang->line('lb_product_name'))
                        ->setCellValue('D1', $this->lang->line('lb_qty'))
                        ->setCellValue('E1', $this->lang->line('lb_unit'))
                        ->setCellValue('F1', $this->lang->line('lb_conversion'));

            $total_qty      = 0;
            foreach ($list as $a) {
                $no     = $i++; 
                $urut   = $ii++;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->product_code)
                            ->setCellValue('C'.$urut, $a->product_name)
                            ->setCellValue('D'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('E'.$urut, $a->unit_name)
                            ->setCellValue('F'.$urut, $a->conversion);
                $total_qty      += $a->qty;
            }
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':C'.$urut)
                                ->setCellValue('D'.$urut, $this->main->qty($total_qty));
        elseif($group == "vendor"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:F".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','F') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_vendor_name'))
                        ->setCellValue('C1', $this->lang->line('lb_purchase_total'))
                        ->setCellValue('D1', $this->lang->line('lb_qty_total'))
                        ->setCellValue('E1', $this->lang->line('lb_price_total'))
                        ->setCellValue('F1', $this->lang->line('lb_total_netto'));

            $total_qty      = 0;
            $total_purchase = 0;
            $total_price    = 0;
            $total_netto    = 0;
            foreach ($list as $a) {
                $no     = $i++; 
                $urut   = $ii++;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->vendor_name)
                            ->setCellValue('C'.$urut, $a->totalpurchase)
                            ->setCellValue('D'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('E'.$urut, $this->main->currency($a->price))
                            ->setCellValue('F'.$urut, $this->main->currency($a->total));

                $total_qty      += $a->qty;
                $total_purchase += $a->totalpurchase;
                $total_price    += $a->price;
                $total_netto    += $a->total;
            }

            $urut += 1;
            $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':B'.$urut)
                            ->setCellValue('C'.$urut, $total_purchase)
                            ->setCellValue('D'.$urut, $this->main->qty($total_qty))
                            ->setCellValue('E'.$urut, $this->main->currency($total_price))
                            ->setCellValue('F'.$urut, $this->main->currency($total_netto));
        elseif($group == "store"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:F".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','F') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_store'))
                        ->setCellValue('C1', $this->lang->line('lb_purchase_total'))
                        ->setCellValue('D1', $this->lang->line('lb_qty_total'))
                        ->setCellValue('E1', $this->lang->line('lb_price_total'))
                        ->setCellValue('F1', $this->lang->line('lb_total_netto'));

            $total_qty      = 0;
            $total_purchase = 0;
            $total_price    = 0;
            $total_netto    = 0;
            foreach ($list as $a) {
                $no     = $i++; 
                $urut   = $ii++;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->branchName)
                            ->setCellValue('C'.$urut, $a->totalpurchase)
                            ->setCellValue('D'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('E'.$urut, $this->main->currency($a->price))
                            ->setCellValue('F'.$urut, $this->main->currency($a->total));

                $total_qty      += $a->qty;
                $total_purchase += $a->totalpurchase;
                $total_price    += $a->price;
                $total_netto    += $a->total;
            }

            $urut += 1;
            $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':B'.$urut)
                            ->setCellValue('C'.$urut, $total_purchase)
                            ->setCellValue('D'.$urut, $this->main->qty($total_qty))
                            ->setCellValue('E'.$urut, $this->main->currency($total_price))
                            ->setCellValue('F'.$urut, $this->main->currency($total_netto));
        endif;

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

    public function correction_stock_excell(){
        $report = $this->input->post("report");
        $group  = $this->input->post("group");

        $nama_laporan   = $this->lang->line('lb_stock_correction');
        $nama_laporan   = $this->input->get("name");
        $table          = "PS_Correction";
        $list           = $this->report->get_datatables($table);
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
        $urut   = 1;
        $totalQty = 0;
        $totalQty2= 0;
        $totalTransaction = 0;
        if($group == "all"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:H".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','H') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_transaction_no'))
                        ->setCellValue('C1', $this->lang->line('lb_date'))
                        ->setCellValue('D1', $this->lang->line('lb_store'))
                        ->setCellValue('E1', $this->lang->line('lb_product_code'))
                        ->setCellValue('F1', $this->lang->line('lb_product_name'))
                        ->setCellValue('G1', $this->lang->line('lb_qty'))
                        ->setCellValue('H1', $this->lang->line('lb_qty_real1'));
            foreach ($list as $a) {
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->CorrectionNo)
                            ->setCellValue('C'.$urut, $a->Date)
                            ->setCellValue('D'.$urut, $a->branchName)
                            ->setCellValue('E'.$urut, $a->product_code)
                            ->setCellValue('F'.$urut, $a->product_name)
                            ->setCellValue('G'.$urut, $this->main->qty($a->Qty))
                            ->setCellValue('H'.$urut, $this->main->qty($a->CorrectionQty));

                $totalQty += $a->Qty;
                $totalQty2 += $a->CorrectionQty;
            }
            $urut += 1;
            $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':F'.$urut)
                            ->setCellValue('G'.$urut, $this->main->qty($totalQty))
                            ->setCellValue('H'.$urut, $this->main->qty($totalQty2));
        elseif($group == "transaction"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:F".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','F') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_transaction_no'))
                        ->setCellValue('C1', $this->lang->line('lb_date'))
                        ->setCellValue('D1', $this->lang->line('lb_store'))
                        ->setCellValue('E1', $this->lang->line('lb_qty_total'))
                        ->setCellValue('F1', $this->lang->line('lb_qty_total_real'));
            foreach ($list as $a) {
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->CorrectionNo)
                            ->setCellValue('C'.$urut, $a->Date)
                            ->setCellValue('D'.$urut, $a->branchName)
                            ->setCellValue('E'.$urut, $this->main->qty($a->Qty))
                            ->setCellValue('F'.$urut, $this->main->qty($a->CorrectionQty));

                $totalQty += $a->Qty;
                $totalQty2 += $a->CorrectionQty;
            }
            $urut += 1;
            $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':D'.$urut)
                            ->setCellValue('E'.$urut, $this->main->qty($totalQty))
                            ->setCellValue('F'.$urut, $this->main->qty($totalQty2));
        elseif($group == "store"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:E".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','E') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_store'))
                        ->setCellValue('C1', $this->lang->line('lb_total_transaction'))
                        ->setCellValue('D1', $this->lang->line('lb_qty_total'))
                        ->setCellValue('E1', $this->lang->line('lb_qty_total_real'));

            foreach ($list as $a) {
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->branchName)
                            ->setCellValue('C'.$urut, $a->totalTransaction)
                            ->setCellValue('D'.$urut, $this->main->qty($a->Qty))
                            ->setCellValue('E'.$urut, $this->main->qty($a->CorrectionQty));

                $totalQty += $a->Qty;
                $totalQty2 += $a->CorrectionQty;
                $totalTransaction += $a->totalTransaction;
            }
            $urut += 1;
            $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':B'.$urut)
                            ->setCellValue('C'.$urut, $totalTransaction)
                            ->setCellValue('D'.$urut, $this->main->qty($totalQty))
                            ->setCellValue('E'.$urut, $this->main->qty($totalQty2));
        endif;
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

    public function mutation_excell()
    {
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        $nama_laporan   = $this->lang->line('lb_stock_mutation');
        $nama_laporan   = $this->input->get("name");
        $table          = "PS_Mutation_Detail";
        $list           = $this->report->get_datatables($table);
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
        $urut   = 1;
        if($group == "all"):
            $jumlah_kolom = count($list)+1;
            $objPHPExcel->getActiveSheet()->getStyle("A1:L".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','L') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_date'))
                        ->setCellValue('C1', $this->lang->line('lb_mutation_no'))
                        ->setCellValue('D1', $this->lang->line('lb_from'))
                        ->setCellValue('E1', $this->lang->line('lb_to'))
                        ->setCellValue('F1', $this->lang->line('lb_product_code'))
                        ->setCellValue('G1', $this->lang->line('lb_product_name'))
                        ->setCellValue('H1', $this->lang->line('lb_qty'))
                        ->setCellValue('I1', $this->lang->line('lb_unit'))
                        ->setCellValue('J1', $this->lang->line('lb_conversion'))
                        ->setCellValue('K1', $this->lang->line('price'))
                        ->setCellValue('L1', $this->lang->line('lb_sub_total'));
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->date)
                            ->setCellValue('C'.$urut, $a->mutationno)
                            ->setCellValue('D'.$urut, $a->mutationfrom)
                            ->setCellValue('E'.$urut, $a->mutationto)
                            ->setCellValue('F'.$urut, $a->product_code)
                            ->setCellValue('G'.$urut, $a->product_name)
                            ->setCellValue('H'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('I'.$urut, $a->unit_name)
                            ->setCellValue('J'.$urut, $a->conversion)
                            ->setCellValue('K'.$urut, $this->main->currency($a->price))
                            ->setCellValue('L'.$urut, $this->main->currency($a->subtotal));
            endforeach;
        elseif($group == "date"):
            $jumlah_kolom = count($list)+1;
            $objPHPExcel->getActiveSheet()->getStyle("A1:D".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','D') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_date'))
                        ->setCellValue('C1', $this->lang->line('lb_qty'))
                        ->setCellValue('D1', $this->lang->line('lb_sub_total'));
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->date)
                            ->setCellValue('C'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('D'.$urut, $this->main->currency($a->subtotal));
            endforeach;
        elseif($group == "mutation_code"):
            $jumlah_kolom = count($list)+1;
            $objPHPExcel->getActiveSheet()->getStyle("A1:D".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','D') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_mutation_no'))
                        ->setCellValue('C1', $this->lang->line('lb_qty'))
                        ->setCellValue('D1', $this->lang->line('lb_sub_total'));
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->mutationno)
                            ->setCellValue('C'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('D'.$urut, $this->main->currency($a->subtotal));
            endforeach;
        elseif($group == "mutation_from" || $group == "mutation_to"):
            $jumlah_kolom = count($list)+1;
            $objPHPExcel->getActiveSheet()->getStyle("A1:E".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','E') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_from'))
                        ->setCellValue('C1', $this->lang->line('lb_to'))
                        ->setCellValue('D1', $this->lang->line('lb_qty'))
                        ->setCellValue('E1', $this->lang->line('lb_sub_total'));
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->mutationfrom)
                            ->setCellValue('C'.$urut, $a->mutationto)
                            ->setCellValue('D'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('E'.$urut, $this->main->currency($a->subtotal));
            endforeach;
        endif;
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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
    
    public function selling_excell()
    {
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        $nama_laporan   = $this->lang->line('lb_sales_store');
        $nama_laporan   = $this->input->get("name");
        $table          = "PS_Sell_Detail";
        $list           = $this->report->get_datatables($table);   
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
        $urut   = 1;
        if($group == "all"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:O".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','O') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_date'))
                        ->setCellValue('C1', $this->lang->line('lb_transaction_no'))
                        ->setCellValue('D1', $this->lang->line('lb_store_name'))
                        ->setCellValue('E1', $this->lang->line('lb_product_code'))
                        ->setCellValue('F1', $this->lang->line('lb_product_name'))
                        ->setCellValue('G1', 'Status')
                        ->setCellValue('H1', $this->lang->line('lb_qty'))
                        ->setCellValue('I1', $this->lang->line('lb_unit'))
                        ->setCellValue('J1', $this->lang->line('lb_conversion'))
                        ->setCellValue('K1', $this->lang->line('price'))
                        ->setCellValue('L1', $this->lang->line('lb_sub_total'))
                        ->setCellValue('M1', $this->lang->line('lb_discount'))
                        ->setCellValue('N1', $this->lang->line('lb_tax'))
                        ->setCellValue('O1', $this->lang->line('lb_total'));

            $total_qty      = 0;
            $total_price    = 0;
            $subtotal       = 0;
            $total_discount = 0;
            $total_tax      = 0;
            $total          = 0;
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++;

                $discount   = $this->main->PersenttoRp($a->subtotal,$a->discount);
                $sub_total  = $a->subtotal - $discount;
                if($a->Tax == 1):
                  $tax        = $this->main->PersenttoRp($sub_total,10);
                else:
                  $tax        = 0;
                endif;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, date("Y-m-d",strtotime($a->date)))
                            ->setCellValue('C'.$urut, $a->sellno)
                            ->setCellValue('D'.$urut, $a->store_name)
                            ->setCellValue('E'.$urut, $a->product_name)
                            ->setCellValue('F'.$urut, $a->product_code)
                            ->setCellValue('G'.$urut, "")
                            ->setCellValue('H'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('I'.$urut, $a->unit_name)
                            ->setCellValue('J'.$urut, $a->conversion)
                            ->setCellValue('K'.$urut, $this->main->currency($a->price))
                            ->setCellValue('L'.$urut, $this->main->currency($a->subtotal))
                            ->setCellValue('M'.$urut, $this->main->currency($discount))
                            ->setCellValue('N'.$urut, $this->main->currency($tax))
                            ->setCellValue('O'.$urut, $this->main->currency($a->payment));
            
            $total_qty      += $a->qty;
            $total_price    += $a->price;
            $subtotal       += $a->subtotal;
            $total_discount += $discount;
            $total_tax      += $tax;
            $total          += $a->payment;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':G'.$urut)
                            ->setCellValue('H'.$urut, $this->main->qty($total_qty))
                            ->setCellValue('K'.$urut, $this->main->currency($total_price))
                            ->setCellValue('L'.$urut, $this->main->currency($subtotal))
                            ->setCellValue('M'.$urut, $this->main->currency($total_discount))
                            ->setCellValue('N'.$urut, $this->main->currency($total_tax))
                            ->setCellValue('O'.$urut, $this->main->currency($total));
        elseif($group == "date"):
            $jumlah_kolom = count($list)+1;
            $objPHPExcel->getActiveSheet()->getStyle("A1:E".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','E') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_date'))
                        ->setCellValue('C1', 'Status')
                        ->setCellValue('D1', $this->lang->line('lb_qty'))
                        ->setCellValue('E1', $this->lang->line('lb_sub_total'));
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, date("Y-m-d",strtotime($a->date)))
                            ->setCellValue('c'.$urut, "")
                            ->setCellValue('D'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('E'.$urut, $this->main->currency($a->subtotal));
            endforeach;
        elseif($group == "store_name"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:D".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','D') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_store_name'))
                        ->setCellValue('C1', $this->lang->line('lb_qty'))
                        ->setCellValue('D1', $this->lang->line('lb_sub_total'));

            $total_qty      = 0;
            $total          = 0;
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->store_name)
                            ->setCellValue('C'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('D'.$urut, $this->main->currency($a->payment));
             
            $total_qty      += $a->qty;
            $total          += $a->payment;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':B'.$urut)
                            ->setCellValue('C'.$urut, $this->main->qty($total_qty))
                            ->setCellValue('D'.$urut, $this->main->currency($total));
        elseif($group == "product_name"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:F".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','F') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_product_code'))
                        ->setCellValue('C1', $this->lang->line('lb_product_name'))
                        ->setCellValue('D1', $this->lang->line('lb_qty'))
                        ->setCellValue('E1', $this->lang->line('lb_unit'))
                        ->setCellValue('F1', $this->lang->line('lb_conversion'));

            $total_qty      = 0;
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->product_name)
                            ->setCellValue('C'.$urut, $a->product_code)
                            ->setCellValue('D'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('E'.$urut, $a->unit_name)
                            ->setCellValue('F'.$urut, $a->conversion);
            
            $total_qty      += $a->qty;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':C'.$urut)
                            ->setCellValue('D'.$urut, $this->main->qty($total_qty));
        endif;
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

    public function distributor_selling_excell()
    {
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        $nama_laporan   = $this->lang->line('lb_sales_ho');
        $nama_laporan   = $this->input->get("name");
        $table          = "PS_Sell";
        $list           = $this->report->get_datatables($table);   
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
        $urut   = 1;
        if($group == "all"):

            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:T".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','T') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:T1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_transaction_no'))
                        ->setCellValue('C1', $this->lang->line('lb_date'))
                        ->setCellValue('D1', $this->lang->line('lb_customer_name'))
                        ->setCellValue('E1', $this->lang->line('lb_store'))
                        ->setCellValue('F1', $this->lang->line('lb_city'))
                        ->setCellValue('G1', $this->lang->line('lb_product_code'))
                        ->setCellValue('H1', $this->lang->line('lb_name'))
                        ->setCellValue('I1', $this->lang->line('lb_qty'))
                        ->setCellValue('J1', $this->lang->line('lb_unit'))
                        ->setCellValue('K1', $this->lang->line('lb_conversion'))
                        ->setCellValue('L1', $this->lang->line('price'))
                        ->setCellValue('M1', $this->lang->line('lb_discount').' (%)')
                        ->setCellValue('N1', $this->lang->line('lb_discount').' ')
                        ->setCellValue('O1', $this->lang->line('lb_total_bruto'))
                        ->setCellValue('P1', $this->lang->line('lb_tax').' (%)')
                        ->setCellValue('Q1', $this->lang->line('lb_tax').'')
                        ->setCellValue('R1', $this->lang->line('lb_total_netto'))
                        ->setCellValue('S1', $this->lang->line('lb_sales_name'))
                        ->setCellValue('T1', $this->lang->line('lb_remark'));
            $total_qty      = 0;
            $total_price    = 0;
            $total_discount = 0;
            $total_bruto    = 0;
            $total_tax      = 0;
            $total_netto    = 0;
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++;

                $ppn        = 0;
                $total_net  = 0;
                if($a->tax == 1):
                    $ppn = $this->main->PersenttoRp($a->TotalPrice, $a->ppn);
                    $total_net = $a->TotalPrice + $ppn;
                endif; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, date("Y-m-d",strtotime($a->date)))
                            ->setCellValue('C'.$urut, $a->sellno)
                            ->setCellValue('D'.$urut, $a->customerName)
                            ->setCellValue('E'.$urut, $a->branchName)
                            ->setCellValue('F'.$urut, $a->DeliveryCity)
                            ->setCellValue('G'.$urut, $a->product_code)
                            ->setCellValue('H'.$urut, $a->product_name)
                            ->setCellValue('I'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('J'.$urut, $a->unit_name)
                            ->setCellValue('K'.$urut, $a->conversion)
                            ->setCellValue('L'.$urut, $this->main->currency($a->price))
                            ->setCellValue('M'.$urut, $a->diskon)
                            ->setCellValue('N'.$urut, $this->main->currency($a->diskonValue))
                            ->setCellValue('O'.$urut, $this->main->currency($a->TotalPrice))
                            ->setCellValue('P'.$urut, $this->main->label_tax($a->tax))
                            ->setCellValue('Q'.$urut, $this->main->currency($ppn))
                            ->setCellValue('R'.$urut, $this->main->currency($total_net))
                            ->setCellValue('S'.$urut, $a->salesName)
                            ->setCellValue('T'.$urut, $a->remark);
                $total_qty      += $a->qty;
                $total_price    += $a->price;
                $total_discount += $a->diskonValue;
                $total_bruto    += $a->TotalPrice;
                $total_tax      += $ppn;
                $total_netto    += $total_net;
            endforeach;
            $urut += 1;
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':H'.$urut)
                        ->setCellValue('I'.$urut, $this->main->qty($total_qty))
                        ->setCellValue('L'.$urut, $this->main->currency($total_price))
                        ->setCellValue('N'.$urut, $this->main->currency($total_discount))
                        ->setCellValue('O'.$urut, $this->main->currency($total_bruto))
                        ->setCellValue('Q'.$urut, $this->main->currency($total_tax))
                        ->setCellValue('R'.$urut, $this->main->currency($total_netto));
        elseif($group == "selling"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:N".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','N') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_transaction_no'))
                        ->setCellValue('C1', $this->lang->line('lb_date'))
                        ->setCellValue('D1', $this->lang->line('lb_customer_name'))
                        ->setCellValue('E1', $this->lang->line('lb_store'))
                        ->setCellValue('F1', $this->lang->line('lb_city'))
                        ->setCellValue('G1', $this->lang->line('lb_qty'))
                        ->setCellValue('H1', $this->lang->line('lb_sub_total'))
                        ->setCellValue('I1', $this->lang->line('lb_discount_total'))
                        ->setCellValue('J1', $this->lang->line('lb_tax'))
                        ->setCellValue('K1', $this->lang->line('lb_delivery_cost'))
                        ->setCellValue('L1', $this->lang->line('lb_total_netto'))
                        ->setCellValue('M1', $this->lang->line('lb_sales_name'))
                        ->setCellValue('N1', $this->lang->line('lb_remark'));
            
            $total_qty      = 0;
            $total_netto    = 0;
            $totalDiscount  = 0;
            $totalSubTotal  = 0;
            $totalTax       = 0;
            $totalDelivery  = 0;
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++;

                $ppn        = 0;
                $total_net  = 0;
                if($a->tax == 1):
                    $ppn = $this->main->PersenttoRp($a->TotalPrice, $a->ppn);
                    $total_net = $a->TotalPrice - $ppn;
                endif;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->sellno)
                            ->setCellValue('C'.$urut, date("Y-m-d",strtotime($a->date)))
                            ->setCellValue('D'.$urut, $a->customerName)
                            ->setCellValue('E'.$urut, $a->customerName)
                            ->setCellValue('F'.$urut, $a->DeliveryCity)
                            ->setCellValue('G'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('H'.$urut, $this->main->currency($a->subtotal))
                            ->setCellValue('I'.$urut, $this->main->currency($a->totaldiscount))
                            ->setCellValue('J'.$urut, $this->main->currency($a->totalppn))
                            ->setCellValue('K'.$urut, $this->main->currency($a->deliverycost))
                            ->setCellValue('L'.$urut, $this->main->currency($a->payment))
                            ->setCellValue('M'.$urut, $a->salesName)
                            ->setCellValue('N'.$urut, $a->remark);
            
            $total_qty      += $a->qty;
            $totalSubTotal  += $a->subtotal;
            $totalDiscount  += $a->totaldiscount;
            $totalTax       += $a->totalppn;
            $totalDelivery  += $a->deliverycost;
            $total_netto    += $a->payment;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':F'.$urut)
                            ->setCellValue('G'.$urut, $this->main->qty($total_qty))
                            ->setCellValue('H'.$urut, $this->main->currency($totalSubTotal))
                            ->setCellValue('I'.$urut, $this->main->currency($totalDiscount))
                            ->setCellValue('J'.$urut, $this->main->currency($totalTax))
                            ->setCellValue('K'.$urut, $this->main->currency($totalDelivery))
                            ->setCellValue('L'.$urut, $this->main->currency($total_netto));
        elseif($group == "product_name"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:F".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','F') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_product_code'))
                        ->setCellValue('C1', $this->lang->line('lb_name'))
                        ->setCellValue('D1', $this->lang->line('lb_qty'))
                        ->setCellValue('E1', $this->lang->line('lb_unit'))
                        ->setCellValue('F1', $this->lang->line('lb_conversion'));
            $total_qty      = 0;
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->product_code)
                            ->setCellValue('C'.$urut, $a->product_name)
                            ->setCellValue('D'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('E'.$urut, $a->unit_name)
                            ->setCellValue('F'.$urut, $a->conversion);
            $total_qty      += $a->qty;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':C'.$urut)
                            ->setCellValue('D'.$urut, $this->main->qty($total_qty));
        elseif($group == "vendor"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:F".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','F') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_customer_name'))
                        ->setCellValue('C1', $this->lang->line('lb_selling_total'))
                        ->setCellValue('D1', $this->lang->line('lb_qty_total'))
                        ->setCellValue('E1', $this->lang->line('lb_price_total'))
                        ->setCellValue('F1', $this->lang->line('lb_total_netto'));
            
            $total_selling  = 0;
            $total_qty      = 0;
            $total_price    = 0;
            $total_netto    = 0;
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->vendor_name)
                            ->setCellValue('C'.$urut, $a->totalTransaction)
                            ->setCellValue('D'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('E'.$urut, $this->main->currency($a->price))
                            ->setCellValue('F'.$urut, $this->main->currency($a->payment));
            $total_selling  += $a->totalTransaction;
            $total_qty      += $a->qty;
            $total_price    += $a->price;
            $total_netto    += $a->payment;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':B'.$urut)
                            ->setCellValue('C'.$urut, $total_selling)
                            ->setCellValue('D'.$urut, $this->main->qty($total_qty))
                            ->setCellValue('E'.$urut, $this->main->currency($total_price))
                            ->setCellValue('F'.$urut, $this->main->currency($total_netto));
        elseif($group == "store"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:F".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','F') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_store'))
                        ->setCellValue('C1', $this->lang->line('lb_selling_total'))
                        ->setCellValue('D1', $this->lang->line('lb_qty_total'))
                        ->setCellValue('E1', $this->lang->line('lb_price_total'))
                        ->setCellValue('F1', $this->lang->line('lb_total_netto'));
            
            $total_selling  = 0;
            $total_qty      = 0;
            $total_price    = 0;
            $total_netto    = 0;
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->branchName)
                            ->setCellValue('C'.$urut, $a->totalTransaction)
                            ->setCellValue('D'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('E'.$urut, $this->main->currency($a->price))
                            ->setCellValue('F'.$urut, $this->main->currency($a->payment));
            $total_selling  += $a->totalTransaction;
            $total_qty      += $a->qty;
            $total_price    += $a->price;
            $total_netto    += $a->payment;
            endforeach;
            $urut += 1;
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':B'.$urut)
                        ->setCellValue('C'.$urut, $total_selling)
                        ->setCellValue('D'.$urut, $this->main->qty($total_qty))
                        ->setCellValue('E'.$urut, $this->main->currency($total_price))
                        ->setCellValue('F'.$urut, $this->main->currency($total_netto));
        endif;
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

     public function correction_ar_excell()
    {
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        $nama_laporan   = $this->lang->line('lb_correctionar');
        $nama_laporan   = $this->input->get("name");
        $table          = "AC_BalancePayable";
        $list           = $this->report->get_datatables($table);   
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
        $urut   = 1;
        if($group == "all"):

            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:G".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','G') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:R1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_correction_no'))
                        ->setCellValue('C1', $this->lang->line('lb_vendor_name'))
                        ->setCellValue('D1', $this->lang->line('lb_date'))
                        ->setCellValue('E1', $this->lang->line('lb_total_payment'))
                        ->setCellValue('F1', $this->lang->line('lb_correction_total'))
                        ->setCellValue('G1', $this->lang->line('lb_total'));
            $total_payment      = 0;
            $total_correction   = 0;
            $total              = 0;
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++;
        
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->Code)
                            ->setCellValue('C'.$urut, $a->vendorName)
                            ->setCellValue('D'.$urut, date("Y-m-d",strtotime($a->Date)))
                            ->setCellValue('E'.$urut, $this->main->currency($a->totalpayment))
                            ->setCellValue('F'.$urut, $this->main->currency($a->totalcorrection))
                            ->setCellValue('G'.$urut, $this->main->currency($a->total));
            $total_payment      += $a->totalpayment;
            $total_correction   += $a->totalcorrection;
            $total              += $a->total;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':D'.$urut)
                            ->setCellValue('E'.$urut, $this->main->currency($total_payment))
                            ->setCellValue('F'.$urut, $this->main->currency($total_correction))
                            ->setCellValue('G'.$urut, $this->main->currency($total));
        elseif($group == "transaction"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:G".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','G') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_correction_no'))
                        ->setCellValue('C1', $this->lang->line('lb_vendor_name'))
                        ->setCellValue('D1', $this->lang->line('lb_date'))
                        ->setCellValue('E1', $this->lang->line('lb_total_payment'))
                        ->setCellValue('F1', $this->lang->line('lb_correction_total'))
                        ->setCellValue('G1', $this->lang->line('lb_total'));
            $total_payment      = 0;
            $total_correction   = 0;
            $total              = 0;
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++;
        
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->Code)
                            ->setCellValue('C'.$urut, $a->vendorName)
                            ->setCellValue('D'.$urut, date("Y-m-d",strtotime($a->Date)))
                            ->setCellValue('E'.$urut, $this->main->currency($a->totalpayment))
                            ->setCellValue('F'.$urut, $this->main->currency($a->TotalCorrection))
                            ->setCellValue('G'.$urut, $this->main->currency($a->total));
            $total_payment      += $a->totalpayment;
            $total_correction   += $a->TotalCorrection;
            $total              += $a->total;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':D'.$urut)
                            ->setCellValue('E'.$urut, $this->main->currency($total_payment))
                            ->setCellValue('F'.$urut, $this->main->currency($total_correction))
                            ->setCellValue('G'.$urut, $this->main->currency($total));
         elseif($group == "vendor"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:C".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','C') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_vendor_name'))
                        ->setCellValue('C1', $this->lang->line('lb_correction_total'));
            $total_correction   = 0;
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++;
               
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->vendorName)
                            ->setCellValue('C'.$urut, $this->main->currency($a->TotalCorrection));
            $total_correction   += $a->TotalCorrection;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':B'.$urut)
                            ->setCellValue('C'.$urut, $this->main->currency($total_correction));
        endif;
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

     public function correction_ap_excell()
    {
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        $nama_laporan   = $this->lang->line('lb_correctionap');
        $nama_laporan   = $this->input->get("name");
        $table          = "AC_BalancePayable";
        $list           = $this->report->get_datatables($table);   
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
        $urut   = 1;
        if($group == "all"):

            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:G".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','G') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:R1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_correction_no'))
                        ->setCellValue('C1', $this->lang->line('lb_vendor_name'))
                        ->setCellValue('D1', $this->lang->line('lb_date'))
                        ->setCellValue('E1', $this->lang->line('lb_total_payment'))
                        ->setCellValue('F1', $this->lang->line('lb_correction_total'))
                        ->setCellValue('G1', $this->lang->line('lb_total'));
            $total_payment   = 0;
            $total_corection = 0;
            $total           = 0;
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++;
        
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->Code)
                            ->setCellValue('C'.$urut, $a->vendorName)
                            ->setCellValue('D'.$urut, date("Y-m-d",strtotime($a->Date)))
                            ->setCellValue('E'.$urut, $this->main->currency($a->totalpayment))
                            ->setCellValue('F'.$urut, $this->main->currency($a->totalcorrection))
                            ->setCellValue('G'.$urut, $this->main->currency($a->total));
            $total_payment   += $a->totalpayment;
            $total_corection += $a->totalcorrection;
            $total           += $a->total;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':D'.$urut)
                            ->setCellValue('E'.$urut, $this->main->currency($total_payment))
                            ->setCellValue('F'.$urut, $this->main->currency($total_corection))
                            ->setCellValue('G'.$urut, $this->main->currency($total));
        elseif($group == "transaction"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:G".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','G') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_correction_no'))
                        ->setCellValue('C1', $this->lang->line('lb_vendor_name'))
                        ->setCellValue('D1', $this->lang->line('lb_date'))
                        ->setCellValue('E1', $this->lang->line('lb_total_payment'))
                        ->setCellValue('F1', $this->lang->line('lb_correction_total'))
                        ->setCellValue('G1', $this->lang->line('lb_total'));
            $total_payment   = 0;
            $total_corection = 0;
            $total           = 0;
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++;
        
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->Code)
                            ->setCellValue('C'.$urut, $a->vendorName)
                            ->setCellValue('D'.$urut, date("Y-m-d",strtotime($a->Date)))
                            ->setCellValue('E'.$urut, $this->main->currency($a->totalpayment))
                            ->setCellValue('F'.$urut, $this->main->currency($a->TotalCorrection))
                            ->setCellValue('G'.$urut, $this->main->currency($a->total));
            $total_payment   += $a->totalpayment;
            $total_corection += $a->TotalCorrection;
            $total           += $a->total;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':D'.$urut)
                            ->setCellValue('E'.$urut, $this->main->currency($total_payment))
                            ->setCellValue('F'.$urut, $this->main->currency($total_corection))
                            ->setCellValue('G'.$urut, $this->main->currency($total));
         elseif($group == "vendor"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:C".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','C') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_vendor_name'))
                        ->setCellValue('C1', $this->lang->line('lb_correction_total'));
            $total_corection = 0;
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++;
               
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->vendorName)
                            ->setCellValue('C'.$urut, $this->main->currency($a->TotalCorrection));
            $total_corection += $a->TotalCorrection;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':B'.$urut)
                            ->setCellValue('C'.$urut, $this->main->currency($total_corection));
        endif;
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

     public function saldo_receivable_excell()
    {
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        $nama_laporan   = $this->lang->line('lb_ar_saldo');
        $nama_laporan   = $this->input->get("name");
        $table          = "PS_Payment";
        $list           = $this->report->select_saldo_receivable();   
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
        $urut   = 1;
        if($group == "all"):

            $jumlah_kolom = count($list)+1;
            $objPHPExcel->getActiveSheet()->getStyle("A1:C".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','C') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_customer_name'))
                        ->setCellValue('C1', $this->lang->line('lb_saldo'));
            $total_saldo  = 0;
            foreach ($list as $a):
                if(trim($a->Supplier) != "Total:"):
                    $no             = $i++; 
                    $urut           = $ii++;

                    $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A'.$urut, $no) #no
                                ->setCellValue('B'.$urut, $a->Supplier)
                                ->setCellValue('C'.$urut, $this->main->currency($a->Saldo));
                $total_saldo += $a->Saldo;
                endif;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':B'.$urut)
                            ->setCellValue('C'.$urut, $this->main->currency($total_saldo));
        elseif($group == "transaction"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:G".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','G') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_date'))
                        ->setCellValue('C1', $this->lang->line('lb_customer_name'))
                        ->setCellValue('D1', $this->lang->line('lb_transaction_no'))
                        ->setCellValue('E1', $this->lang->line('lb_total_nota'))
                        ->setCellValue('F1', $this->lang->line('lb_paid'))
                        ->setCellValue('G1', $this->lang->line('lb_saldo'));
            $grandtotal   = 0;
            $total_nota   = 0;
            $total_paid   = 0;
            $total_saldo  = 0;
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, date("Y-m-d",strtotime($a->Tanggal)))
                            ->setCellValue('C'.$urut, $a->supplier)
                            ->setCellValue('D'.$urut, $a->notransaksi)
                            ->setCellValue('E'.$urut, $this->main->currency($a->Totalnota))
                            ->setCellValue('F'.$urut, $this->main->currency($a->Bayar))
                            ->setCellValue('G'.$urut, $this->main->currency($a->Saldo))
                           ;
            $total_nota   += $a->Totalnota;
            $total_paid   += $a->Bayar;
            $total_saldo  += $a->Saldo;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':B'.$urut)
                            ->setCellValue('E'.$urut, $this->main->currency($total_nota))
                            ->setCellValue('F'.$urut, $this->main->currency($total_paid))
                            ->setCellValue('G'.$urut, $this->main->currency($total_saldo));
        endif;
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

     public function saldo_ap_excell()
    {
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        $nama_laporan   = $this->lang->line('lb_ap_saldo');
        $nama_laporan   = $this->input->get("name");
        $table          = "PS_Payment";
        $list           = $this->report->select_saldo_ap();   
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
        $urut   = 1;
        if($group == "all"):

            $jumlah_kolom = count($list)+1;
            $objPHPExcel->getActiveSheet()->getStyle("A1:C".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','C') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_vendor_name'))
                        ->setCellValue('C1', $this->lang->line('lb_saldo'));
            $total_saldo   = 0;
            foreach ($list as $a):
                if($a->VendorID):
                    $no             = $i++; 
                    $urut           = $ii++;
                    
                    $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A'.$urut, $no) #no
                                ->setCellValue('B'.$urut, $a->Supplier)
                                ->setCellValue('C'.$urut, $this->main->currency($a->Saldo));
                    $total_saldo   += $a->Saldo;
                endif;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':B'.$urut)
                            ->setCellValue('C'.$urut, $this->main->currency($total_saldo)); 
        elseif($group == "transaction"):
            
            $jumlah_kolom = count($list)+1;
            $objPHPExcel->getActiveSheet()->getStyle("A1:H".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','H') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_date'))
                        ->setCellValue('C1', $this->lang->line('lb_vendor_name'))
                        ->setCellValue('D1', $this->lang->line('lb_transaction_no'))
                        ->setCellValue('E1', $this->lang->line('lb_total_nota'))
                        ->setCellValue('F1', $this->lang->line('lb_invoiceno'))
                        ->setCellValue('G1', $this->lang->line('lb_paid'))
                        ->setCellValue('H1', $this->lang->line('lb_saldo'));
            $total_nota     = 0;
            $total_paid     = 0;
            $total_saldo    = 0;
            foreach ($list as $a):
                if($a->supplier):
                    $no     = $i++; 
                    $urut   = $ii++;
                    $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A'.$urut, $no) #no
                                ->setCellValue('B'.$urut, date("Y-m-d",strtotime($a->Tanggal)))
                                ->setCellValue('C'.$urut, $a->supplier)
                                ->setCellValue('D'.$urut, $a->notransaksi)
                                ->setCellValue('E'.$urut, $this->main->currency($a->Totalnota))
                                ->setCellValue('F'.$urut, $a->noInvoice)
                                ->setCellValue('G'.$urut, $this->main->currency($a->Bayar))
                                ->setCellValue('H'.$urut, $this->main->currency($a->Saldo))
                               ;
                    $total_nota     += $a->Totalnota;
                    $total_paid     += $a->Bayar;
                    $total_saldo    += $a->Saldo;
                endif;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':D'.$urut)
                            ->setCellValue('E'.$urut, $this->main->currency($total_nota))
                            ->setCellValue('G'.$urut, $this->main->currency($total_paid))
                            ->setCellValue('H'.$urut, $this->main->currency($total_saldo));
        endif;
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

    public function sales_book_excell()
    {
        $report = $this->input->post("report");
        $nama_laporan   = $this->lang->line('lb_sales_book');
        $nama_laporan   = $this->input->get("name");
        $table          = "PS_Sell";
        $list           = $this->report->get_datatables($table);   
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
        $urut   = 1;

            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:K".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','K') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_date'))
                        ->setCellValue('C1', $this->lang->line('lb_sellingno'))
                        ->setCellValue('D1', $this->lang->line('lb_customer_name'))
                        ->setCellValue('E1', $this->lang->line('lb_sub_total'))
                        ->setCellValue('F1', $this->lang->line('lb_discount'))
                        ->setCellValue('G1', $this->lang->line('lb_tax'))
                        ->setCellValue('H1', $this->lang->line('lb_delivery_cost'))
                        ->setCellValue('I1', $this->lang->line('lb_total_sales'))
                        ->setCellValue('J1', $this->lang->line('lb_total_paid'))
                        ->setCellValue('K1', $this->lang->line('lb_saldo'));
            $grandtotal1  = 0;
            $total_Saldo  = 0;

            $subtotal       = 0;
            $discount       = 0;
            $deliverycost   = 0;
            $total_sales    = 0;
            $total_paid     = 0;
            $total_saldo    = 0;
            foreach ($list as $a):
                $total_Saldo   = $a->total - $a->grandtotal;
                $no            = $i++; 
                $urut          = $ii++;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, date("Y-m-d",strtotime($a->date)))
                            ->setCellValue('C'.$urut, $a->transactionCode)
                            ->setCellValue('D'.$urut, $a->customerName)
                            ->setCellValue('E'.$urut, $this->main->currency($a->subtotal))
                            ->setCellValue('F'.$urut, $a->diskon)
                            ->setCellValue('G'.$urut, $this->main->label_tax($a->tax))
                            ->setCellValue('H'.$urut, $this->main->currency($a->deliverycost))
                            ->setCellValue('I'.$urut, $this->main->currency($a->total))
                            ->setCellValue('J'.$urut, $this->main->currency($a->grandtotal))
                            ->setCellValue('K'.$urut, $this->main->currency($total_Saldo))
                            ;

            $subtotal       += $a->subtotal;
            $discount       += $a->diskon;
            $deliverycost   += $a->deliverycost;
            $total_sales    += $a->total;
            $total_paid     += $a->grandtotal;
            $total_saldo    += $total_Saldo;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':D'.$urut)
                            ->setCellValue('E'.$urut, $this->main->currency($subtotal))
                            ->setCellValue('F'.$urut, $discount)
                            ->setCellValue('H'.$urut, $this->main->currency($deliverycost))
                            ->setCellValue('I'.$urut, $this->main->currency($total_sales))
                            ->setCellValue('J'.$urut, $this->main->currency($total_paid))
                            ->setCellValue('K'.$urut, $this->main->currency($total_saldo));
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

    public function purchase_book_excell()
    {
        $report = $this->input->post("report");
        $nama_laporan   = $this->lang->line('lb_purchase_book');
        $nama_laporan   = $this->input->get("name");
        $table          = "PS_Purchase";
        $list           = $this->report->get_datatables($table);   
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
        $urut   = 1;

            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:K".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','K') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_date'))
                        ->setCellValue('C1', $this->lang->line('lb_purchaseno'))
                        ->setCellValue('D1', $this->lang->line('lb_vendor_name'))
                        ->setCellValue('E1', $this->lang->line('lb_sub_total'))
                        ->setCellValue('F1', $this->lang->line('lb_discount'))
                        ->setCellValue('G1', $this->lang->line('lb_tax').' (%)')
                        ->setCellValue('H1', $this->lang->line('lb_delivery_cost'))
                        ->setCellValue('I1', $this->lang->line('lb_purchase_total'))
                        ->setCellValue('J1', $this->lang->line('lb_total_paid'))
                        ->setCellValue('K1', $this->lang->line('lb_saldo'));
            // $grandtotal1  = 0;
            $total_Saldo  = 0;

            $subtotal       = 0;
            $discount       = 0;
            $deliverycost   = 0;
            $totalsales     = 0;
            $totalpaid      = 0;
            $saldo          = 0;
            foreach ($list as $a):
                $total_Saldo  = $a->total - $a->grandtotal;

                $no            = $i++; 
                $urut          = $ii++;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, date("Y-m-d",strtotime($a->date)))
                            ->setCellValue('C'.$urut, $a->transactionCode)
                            ->setCellValue('D'.$urut, $a->customerName)
                            ->setCellValue('E'.$urut, $this->main->currency($a->subtotal))
                            ->setCellValue('F'.$urut, $this->main->currency($a->diskon))
                            ->setCellValue('G'.$urut, $a->tax)
                            ->setCellValue('H'.$urut, $this->main->currency($a->deliverycost))
                            ->setCellValue('I'.$urut, $this->main->currency($a->total))
                            ->setCellValue('J'.$urut, $this->main->currency($a->grandtotal))
                            ->setCellValue('K'.$urut, $this->main->currency($total_Saldo))
                            ;
            $subtotal       += $a->subtotal;
            $discount       += $a->diskon;
            $deliverycost   += $a->deliverycost;
            $totalsales     += $a->total;
            $totalpaid      += $a->grandtotal;
            $saldo          += $total_Saldo;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':D'.$urut)
                            ->setCellValue('E'.$urut, $this->main->currency($subtotal))
                            ->setCellValue('F'.$urut, $this->main->currency($discount))
                            ->setCellValue('H'.$urut, $this->main->currency($deliverycost))
                            ->setCellValue('I'.$urut, $this->main->currency($totalsales))
                            ->setCellValue('J'.$urut, $this->main->currency($totalpaid))
                            ->setCellValue('K'.$urut, $this->main->currency($saldo));
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

    public function voucher_excell()
    {
        $report = $this->input->post("report");
        $nama_laporan   = $this->lang->line('lb_voucher');
        $nama_laporan   = $this->input->get("name");
        $table          = "Voucher";
        $list           = $this->report->get_datatables($table);   
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
            $objPHPExcel->getActiveSheet()->getStyle("A1:J".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','J') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_date'))
                        ->setCellValue('C1', $this->lang->line('lb_transaction_no'))
                        ->setCellValue('D1', $this->lang->line('lb_package'))
                        ->setCellValue('E1', $this->lang->line('lb_additional_qty'))
                        ->setCellValue('F1', $this->lang->line('lb_module_qty'))
                        ->setCellValue('G1', $this->lang->line('lb_voucher_user_amount'))
                        ->setCellValue('H1', $this->lang->line('lb_voucher_module_amount'))
                        ->setCellValue('I1', $this->lang->line('lb_total_amount'))
                        ->setCellValue('J1', $this->lang->line('lb_name'));

            foreach ($list as $a):
                $qty        = $a->parentQty;
                $QtyModule  = $a->Qty;
                if($a->Module == "android"):
                    $qty = $a->Qty;
                    $QtyModule = 0;
                endif;

                $no            = $i++; 
                $urut          = $ii++;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, date("Y-m-d",strtotime($a->Date)))
                            ->setCellValue('C'.$urut, $a->Code)
                            ->setCellValue('D'.$urut, $a->Type)
                            ->setCellValue('E'.$urut, number_format($qty,0))
                            ->setCellValue('F'.$urut, number_format($QtyModule,0))
                            ->setCellValue('G'.$urut, "IDR ".number_format($a->Price,0,".",","))
                            ->setCellValue('H'.$urut, "IDR ".number_format($a->PriceModule,0,".",","))
                            ->setCellValue('I'.$urut, "IDR ".number_format($a->TotalPrice,0,".",","))
                            ->setCellValue('J'.$urut, $a->nama)
                            ;
            endforeach;
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

    public function debtors_account_excell()
    {
        $report = $this->input->post("report");
        $nama_laporan   = $this->lang->line('lb_ar_card');
        $nama_laporan   = $this->input->get("name");
        $table          = "PS_Invoice_Detail";
        $list           = $this->report->select_debtors_account();   
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
        $urut   = 1;
        $jumlah_kolom = count($list)+2;
        $objPHPExcel->getActiveSheet()->getStyle("A1:I".$jumlah_kolom)->applyFromArray($border_style);
        foreach(range('A','I') as $columnID):
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        endforeach;
        $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', $this->lang->line('lb_no'))
                    ->setCellValue('B1', $this->lang->line('lb_date'))
                    ->setCellValue('C1', $this->lang->line('lb_transaction_no'))
                    ->setCellValue('D1', $this->lang->line('lb_customer_name'))
                    ->setCellValue('E1', $this->lang->line('lb_initial_balance'))
                    ->setCellValue('F1', $this->lang->line('lb_debit'))
                    ->setCellValue('G1', $this->lang->line('lb_credit'))
                    ->setCellValue('H1', $this->lang->line('lb_delivery_cost'))
                    ->setCellValue('I1', $this->lang->line('lb_final_balance'));
        $initial_balance = 0;
        $total_balance   = 0;
        $initial_balance = 0;
        $debit           = 0;
        $credit          = 0;
        $final_balance   = 0;
        foreach ($list as $a):
            $no         = $i++; 
            $urut       = $ii++;

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$urut, $no) #no
                        ->setCellValue('B'.$urut, date("Y-m-d",strtotime($a->Tanggal)))
                        ->setCellValue('C'.$urut, $a->nobukti)
                        ->setCellValue('D'.$urut, $a->nopengiriman)
                        ->setCellValue('E'.$urut, $a->Customer)
                        ->setCellValue('F'.$urut, $this->main->currency($a->Awal))
                        ->setCellValue('G'.$urut, $this->main->currency($a->Debit))
                        ->setCellValue('H'.$urut, $this->main->currency($a->Kredit))
                        ->setCellValue('I'.$urut, $this->main->currency($a->Saldo))
                        ;
        $initial_balance += $a->Awal;
        $debit           += $a->Debit;
        $credit          += $a->Kredit;
        $final_balance   += $a->Saldo;
        endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':B'.$urut)
                            ->setCellValue('F'.$urut, $this->main->currency($initial_balance))
                            ->setCellValue('G'.$urut, $this->main->currency($debit))
                            ->setCellValue('H'.$urut, $this->main->currency($credit))
                            ->setCellValue('I'.$urut, $this->main->currency($final_balance));

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

    public function creditors_account_excell()
    {
        $report = $this->input->post("report");
        $nama_laporan   = $this->lang->line('lb_ap_card');
        $nama_laporan   = $this->input->get("name");
        $table          = "PS_Invoice_Detail";
        $list           = $this->report->select_creditors_account();   
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
        $urut   = 1;

        $jumlah_kolom = count($list)+2;
        $objPHPExcel->getActiveSheet()->getStyle("A1:I".$jumlah_kolom)->applyFromArray($border_style);
        foreach(range('A','I') as $columnID):
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        endforeach;
        $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', $this->lang->line('lb_no'))
                    ->setCellValue('B1', $this->lang->line('lb_date'))
                    ->setCellValue('C1', $this->lang->line('lb_transaction_no'))
                    ->setCellValue('D1', $this->lang->line('lb_vendor_name'))
                    ->setCellValue('E1', $this->lang->line('lb_initial_balance'))
                    ->setCellValue('F1', $this->lang->line('lb_debit'))
                    ->setCellValue('G1', $this->lang->line('lb_credit'))
                    ->setCellValue('H1', $this->lang->line('lb_delivery_cost'))
                    ->setCellValue('I1', $this->lang->line('lb_final_balance'));
        $total_awal     = 0;
        $total_debit    = 0;
        $total_kredit   = 0;
        $total_saldo    = 0;
        foreach ($list as $a):
            $no         = $i++; 
            $urut       = $ii++;

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$urut, $no) #no
                        ->setCellValue('B'.$urut, date("Y-m-d",strtotime($a->Tanggal)))
                        ->setCellValue('C'.$urut, $a->nobukti)
                        ->setCellValue('D'.$urut, $a->nopengiriman)
                        ->setCellValue('F'.$urut, $a->Supplier)
                        ->setCellValue('E'.$urut, $this->main->currency($a->Awal))
                        ->setCellValue('G'.$urut, $this->main->currency($a->Debit))
                        ->setCellValue('H'.$urut, $this->main->currency($a->Kredit))
                        ->setCellValue('I'.$urut, $this->main->currency($a->Saldo))
                        ;
        $total_awal     += $a->Awal;
        $total_debit    += $a->Debit;
        $total_kredit   += $a->Kredit;
        $total_saldo    += $a->Saldo;
        endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':D'.$urut)
                            ->setCellValue('E'.$urut, $this->main->currency($total_awal))
                            ->setCellValue('G'.$urut, $this->main->currency($total_debit))
                            ->setCellValue('H'.$urut, $this->main->currency($total_kredit))
                            ->setCellValue('I'.$urut, $this->main->currency($total_saldo));

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

    public function outstanding_delivery_excell()
    {
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        $nama_laporan   = "Distributor Outstanding";
        $nama_laporan   = $this->input->get("name");
        $table          = "PS_Sell";
        $list           = $this->report->get_datatables($table);   
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
        $urut   = 1;

        $totalQty           = 0;
        $totalQtyDelivery   = 0;
        $totalQtyResidue    = 0;
        if($group == "all"):

            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:J".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','J') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_sellingno'))
                        ->setCellValue('C1', $this->lang->line('lb_date'))
                        ->setCellValue('D1', $this->lang->line('lb_customer_name'))
                        ->setCellValue('E1', $this->lang->line('lb_store'))
                        ->setCellValue('F1', $this->lang->line('lb_product_code'))
                        ->setCellValue('G1', $this->lang->line('lb_product_name'))
                        ->setCellValue('H1', $this->lang->line('lb_qty_order'))
                        ->setCellValue('I1', $this->lang->line('lb_deliveryqty'))
                        ->setCellValue('J1', $this->lang->line('lb_qty_residue'));
            foreach ($list as $a):
                $no             = $i++; 
                $urut           = $ii++;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->SellNo)
                            ->setCellValue('C'.$urut, date("Y-m-d",strtotime($a->Date)))
                            ->setCellValue('D'.$urut, $a->vendorName)
                            ->setCellValue('E'.$urut, $a->branchName)
                            ->setCellValue('F'.$urut, $a->productCode)
                            ->setCellValue('G'.$urut, $a->productName)
                            ->setCellValue('H'.$urut, $this->main->qty($a->Qty))
                            ->setCellValue('I'.$urut, $this->main->qty($a->DeliveryQty))
                            ->setCellValue('J'.$urut, $this->main->qty($a->qtyResidue))
                            ;
                $totalQty           += $a->Qty;
                $totalQtyDelivery   += $a->DeliveryQty;
                $totalQtyResidue    += $a->qtyResidue;
            endforeach;
            $urut += 1;
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':G'.$urut)
                        ->setCellValue('H'.$urut, $this->main->qty($totalQty))
                        ->setCellValue('I'.$urut, $this->main->qty($totalQtyDelivery))
                        ->setCellValue('J'.$urut, $this->main->qty($totalQtyResidue));
        elseif($group == "transaction"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:H".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','H') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_sellingno'))
                        ->setCellValue('C1', $this->lang->line('lb_date'))
                        ->setCellValue('D1', $this->lang->line('lb_customer_name'))
                        ->setCellValue('E1', $this->lang->line('lb_store'))
                        ->setCellValue('F1', $this->lang->line('lb_qty_order'))
                        ->setCellValue('G1', $this->lang->line('lb_deliveryqty'))
                        ->setCellValue('H1', $this->lang->line('lb_qty_residue'));
            foreach ($list as $a):
                $no             = $i++; 
                $urut           = $ii++;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->SellNo)
                            ->setCellValue('C'.$urut, date("Y-m-d",strtotime($a->Date)))
                            ->setCellValue('D'.$urut, $a->vendorName)
                            ->setCellValue('E'.$urut, $a->branchName)
                            ->setCellValue('F'.$urut, $this->main->qty($a->Qty))
                            ->setCellValue('G'.$urut, $this->main->qty($a->DeliveryQty))
                            ->setCellValue('H'.$urut, $this->main->qty($a->qtyResidue));

                $totalQty           += $a->Qty;
                $totalQtyDelivery   += $a->DeliveryQty;
                $totalQtyResidue    += $a->qtyResidue;
            endforeach;
            $urut += 1;
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':E'.$urut)
                        ->setCellValue('F'.$urut, $this->main->qty($totalQty))
                        ->setCellValue('G'.$urut, $this->main->qty($totalQtyDelivery))
                        ->setCellValue('H'.$urut, $this->main->qty($totalQtyResidue));
        elseif($group == "product_name"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:H".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','H') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_product_code'))
                        ->setCellValue('C1', $this->lang->line('lb_product_name'))
                        ->setCellValue('D1', $this->lang->line('lb_qty'))
                        ->setCellValue('E1', $this->lang->line('lb_deliveryqty'))
                        ->setCellValue('F1', $this->lang->line('lb_qty_residue'))
                        ->setCellValue('G1', $this->lang->line('lb_unit'))
                        ->setCellValue('H1', $this->lang->line('lb_conversion'));
            foreach ($list as $a):
                $no             = $i++; 
                $urut           = $ii++;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->product_code)
                            ->setCellValue('C'.$urut, $a->product_name)
                            ->setCellValue('D'.$urut, $this->main->qty($a->Qty))
                            ->setCellValue('E'.$urut, $this->main->qty($a->DeliveryQty))
                            ->setCellValue('F'.$urut, $this->main->qty($a->qtyResidue))
                            ->setCellValue('G'.$urut, $a->unit_name)
                            ->setCellValue('H'.$urut, $a->conversion);

                $totalQty           += $a->Qty;
                $totalQtyDelivery   += $a->DeliveryQty;
                $totalQtyResidue    += $a->qtyResidue;
            endforeach;
            $urut += 1;
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':C'.$urut)
                        ->setCellValue('D'.$urut, $this->main->qty($totalQty))
                        ->setCellValue('E'.$urut, $this->main->qty($totalQtyDelivery))
                        ->setCellValue('F'.$urut, $this->main->qty($totalQtyResidue));
        elseif($group == "store"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:E".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','E') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_store'))
                        ->setCellValue('C1', $this->lang->line('lb_qty'))
                        ->setCellValue('D1', $this->lang->line('lb_deliveryqty'))
                        ->setCellValue('E1', $this->lang->line('lb_qty_residue'));
            foreach ($list as $a):
                $no             = $i++; 
                $urut           = $ii++;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->branchName)
                            ->setCellValue('C'.$urut, $this->main->qty($a->Qty))
                            ->setCellValue('D'.$urut, $this->main->qty($a->DeliveryQty))
                            ->setCellValue('E'.$urut, $this->main->qty($a->qtyResidue));

                $totalQty           += $a->Qty;
                $totalQtyDelivery   += $a->DeliveryQty;
                $totalQtyResidue    += $a->qtyResidue;
            endforeach;
            $urut += 1;
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':B'.$urut)
                        ->setCellValue('C'.$urut, $this->main->qty($totalQty))
                        ->setCellValue('D'.$urut, $this->main->qty($totalQtyDelivery))
                        ->setCellValue('E'.$urut, $this->main->qty($totalQtyResidue));
        endif;
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

    public function return_selling_excell()
    {
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        $nama_laporan   = "Return Selling";
        $nama_laporan   = $this->input->get("name");
        $table          = "AP_Retur";
        $list           = $this->report->get_datatables($table);   
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
        $urut   = 1;
        if($group == "all"):

            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:Q".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','Q') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_return_date'))
                        ->setCellValue('C1', $this->lang->line('lb_return_no'))
                        ->setCellValue('D1', $this->lang->line('lb_date'))
                        ->setCellValue('E1', $this->lang->line('lb_store_name'))
                        ->setCellValue('F1', $this->lang->line('lb_transaction_no'))
                        ->setCellValue('G1', $this->lang->line('lb_customer_name'))
                        ->setCellValue('H1', $this->lang->line('lb_product_code'))
                        ->setCellValue('I1', $this->lang->line('lb_product_name'))
                        ->setCellValue('J1', $this->lang->line('lb_qty'))
                        ->setCellValue('K1', $this->lang->line('lb_unit'))
                        ->setCellValue('L1', $this->lang->line('lb_conversion'))
                        ->setCellValue('M1', $this->lang->line('price'))
                        ->setCellValue('N1', $this->lang->line('lb_discount'))
                        ->setCellValue('O1', $this->lang->line('lb_tax'))
                        ->setCellValue('P1', $this->lang->line('lb_total'))
                        ->setCellValue('Q1', $this->lang->line('lb_remark'));
            $total_qty      = 0;
            $total_price    = 0;
            $total_discount = 0;
            $total_tax      = 0;
            $total          = 0;
            foreach ($list as $a):
                $no             = $i++; 
                $urut           = $ii++;
                $sub_total  = $a->qty * $a->price;
                $sub_total  = $sub_total - $a->discount;
                if($a->tax == 1):
                  $tax        = $this->main->PersenttoRp($sub_total,10);
                else:
                  $tax        = 0;
                endif;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, date("Y-m-d",strtotime($a->date)))
                            ->setCellValue('C'.$urut, $a->returno)
                            ->setCellValue('D'.$urut, date("Y-m-d",strtotime($a->sellDate)))
                            ->setCellValue('E'.$urut, $a->branch)
                            ->setCellValue('F'.$urut, $a->SellNo)
                            ->setCellValue('G'.$urut, $a->vendorname)
                            ->setCellValue('H'.$urut, $a->product_code)
                            ->setCellValue('I'.$urut, $a->product_name)
                            ->setCellValue('J'.$urut, $a->qty)
                            ->setCellValue('K'.$urut, $a->unit_name)
                            ->setCellValue('L'.$urut, $a->conversion)
                            ->setCellValue('M'.$urut, $this->main->currency($a->price))
                            ->setCellValue('N'.$urut, $this->main->currency($a->discount))
                            ->setCellValue('O'.$urut, $this->main->currency($tax))
                            ->setCellValue('P'.$urut, $this->main->currency($a->total))
                            ->setCellValue('Q'.$urut, $a->remark);
            $total_qty      += $a->qty;
            $total_price    += $a->price;
            $total_discount += $a->discount;
            $total_tax      += $tax;
            $total          += $a->total;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':I'.$urut)
                            ->setCellValue('J'.$urut, $total_qty)
                            ->setCellValue('M'.$urut, $this->main->currency($total_price))
                            ->setCellValue('N'.$urut, $this->main->currency($total_discount))
                            ->setCellValue('O'.$urut, $this->main->currency($total_tax))
                            ->setCellValue('P'.$urut, $this->main->currency($total));
        elseif($group == "selling"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:J".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','J') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_return_date'))
                        ->setCellValue('C1', $this->lang->line('lb_return_no'))
                        ->setCellValue('D1', $this->lang->line('lb_date'))
                        ->setCellValue('E1', $this->lang->line('lb_transaction_no'))
                        ->setCellValue('F1', $this->lang->line('lb_customer_name'))
                        ->setCellValue('G1', $this->lang->line('lb_return_qty_total'))
                        ->setCellValue('H1', $this->lang->line('lb_return_total'))
                        ->setCellValue('I1', $this->lang->line('lb_remark'))
                        ->setCellValue('J1', $this->lang->line('lb_sales_name'));

            $total_qty      = 0;
            $total_return   = 0;
            foreach ($list as $a):
            $no             = $i++; 
            $urut           = $ii++;

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$urut, $no) #no
                        ->setCellValue('B'.$urut, date("Y-m-d",strtotime($a->date)))
                        ->setCellValue('C'.$urut, $a->returno)
                        ->setCellValue('D'.$urut, date("Y-m-d",strtotime($a->sellDate)))
                        ->setCellValue('E'.$urut, $a->SellNo)
                        ->setCellValue('F'.$urut, $a->vendorname)
                        ->setCellValue('G'.$urut, $a->qty)
                        ->setCellValue('H'.$urut, $this->main->currency($a->total))
                        ->setCellValue('I'.$urut, $a->remark)
                        ->setCellValue('J'.$urut, $a->sales_name)
                        ;
            $total_qty      += $a->qty;
            $total_return   += $a->total;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':F'.$urut)
                            ->setCellValue('G'.$urut, $total_qty)
                            ->setCellValue('H'.$urut, $this->main->currency($total_return));
        endif;
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

    public function return_distributor_excell()
    {
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        $nama_laporan   = "Return Selling";
        $nama_laporan   = $this->input->get("name");
        $table          = "AP_Retur";
        $list           = $this->report->get_datatables($table);   
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
        if($group == "all"):

            $jumlah_kolom = count($list)+1;
            $objPHPExcel->getActiveSheet()->getStyle("A1:L".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','L') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'No')
                        ->setCellValue('B1', 'Date Return')
                        ->setCellValue('C1', 'Return No')
                        ->setCellValue('D1', 'Date Selling')
                        ->setCellValue('E1', 'Selling No')
                        ->setCellValue('F1', 'Customer Name')
                        ->setCellValue('G1', 'Product No')
                        ->setCellValue('H1', 'Product Name')
                        ->setCellValue('I1', 'Qty')
                        ->setCellValue('J1', 'Unit')
                        ->setCellValue('K1', 'Conversion')
                        ->setCellValue('L1', 'Remark');
            foreach ($list as $a):
                $no             = $i++; 
                $urut           = $ii++;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, date("Y-m-d",strtotime($a->date)))
                            ->setCellValue('C'.$urut, $a->returno)
                            ->setCellValue('D'.$urut, date("Y-m-d",strtotime($a->sellDate)))
                            ->setCellValue('E'.$urut, $a->SellNo)
                            ->setCellValue('F'.$urut, $a->vendorname)
                            ->setCellValue('G'.$urut, $a->product_code)
                            ->setCellValue('H'.$urut, $a->product_name)
                            ->setCellValue('I'.$urut, $a->qty)
                            ->setCellValue('J'.$urut, $a->unit_name)
                            ->setCellValue('K'.$urut, $a->conversion)
                            ->setCellValue('L'.$urut, $a->remark)
                            ;
            endforeach;
        elseif($group == "distributor"):
            $jumlah_kolom = count($list)+1;
            $objPHPExcel->getActiveSheet()->getStyle("A1:J".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','J') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'No')
                        ->setCellValue('B1', 'Date Return')
                        ->setCellValue('C1', 'Return No')
                        ->setCellValue('D1', 'Date Selling')
                        ->setCellValue('E1', 'Selling No')
                        ->setCellValue('F1', 'Customer Name')
                        ->setCellValue('G1', 'Total Qty Return')
                        ->setCellValue('H1', 'Total Return')
                        ->setCellValue('I1', 'Remark')
                        ->setCellValue('J1', 'Sales Name');
            foreach ($list as $a):
            $no             = $i++; 
            $urut           = $ii++;

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$urut, $no) #no
                        ->setCellValue('B'.$urut, date("Y-m-d",strtotime($a->date)))
                        ->setCellValue('C'.$urut, $a->returno)
                        ->setCellValue('D'.$urut, date("Y-m-d",strtotime($a->sellDate)))
                        ->setCellValue('E'.$urut, $a->SellNo)
                        ->setCellValue('F'.$urut, $a->vendorname)
                        ->setCellValue('G'.$urut, $a->qty)
                        ->setCellValue('H'.$urut, $a->total_qty)
                        ->setCellValue('I'.$urut, $a->remark)
                        ->setCellValue('J'.$urut, $a->sales_name)
                        ;
            endforeach;
        endif;
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

    public function invoice_customer_excell()
    {
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        $nama_laporan   = "Invoice Customer";
        $nama_laporan   = $this->input->get("name");
        $table          = "PS_Invoice";
        $list           = $this->report->get_datatables($table);   
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
        $urut   = 1;
        if($group == "all"):

            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:K".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','K') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_date'))
                        ->setCellValue('C1', $this->lang->line('lb_transaction_date'))
                        ->setCellValue('D1', $this->lang->line('lb_transaction_no'))
                        ->setCellValue('E1', $this->lang->line('lb_customer_name'))
                        ->setCellValue('F1', $this->lang->line('lb_sub_total'))
                        ->setCellValue('G1', $this->lang->line('lb_discount_total'))
                        ->setCellValue('H1', $this->lang->line('lb_tax'))
                        ->setCellValue('I1', $this->lang->line('lb_delivery_cost'))
                        ->setCellValue('J1', $this->lang->line('lb_total'))
                        ->setCellValue('k1', $this->lang->line('lb_remark'));
            $subtotal       = 0;
            $total_discount = 0;
            $total_tax      = 0;
            $total_delivery = 0;
            $total          = 0;
            foreach ($list as $a):
                $no             = $i++; 
                $urut           = $ii++;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, date("Y-m-d",strtotime($a->Date)))
                            ->setCellValue('D'.$urut, $a->transactionCode)
                            ->setCellValue('C'.$urut, date("Y-m-d",strtotime($a->transactionDate)))
                            ->setCellValue('E'.$urut, $a->Name)
                            ->setCellValue('F'.$urut, $this->main->currency($a->Subtotal))
                            ->setCellValue('G'.$urut, $this->main->currency($a->Discount))
                            ->setCellValue('H'.$urut, $this->main->currency($a->PPN))
                            ->setCellValue('I'.$urut, $this->main->currency($a->DeliveryCost))
                            ->setCellValue('J'.$urut, $this->main->currency($a->Total))
                            ->setCellValue('K'.$urut, $a->Remark)
                            ;
            $subtotal       += $a->Subtotal;
            $total_discount += $a->Discount;
            $total_tax      += $a->PPN;
            $total_delivery += $a->DeliveryCost;
            $total          += $a->Total;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':E'.$urut)
                            ->setCellValue('F'.$urut, $this->main->currency($subtotal))
                            ->setCellValue('G'.$urut, $this->main->currency($total_discount))
                            ->setCellValue('H'.$urut, $this->main->currency($total_tax))
                            ->setCellValue('I'.$urut, $this->main->currency($total_delivery))
                            ->setCellValue('J'.$urut, $this->main->currency($total));
        elseif($group == "transaction"):
             $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:I".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','I') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_date'))
                        ->setCellValue('C1', $this->lang->line('lb_customer_name'))
                        ->setCellValue('D1', $this->lang->line('lb_sub_total'))
                        ->setCellValue('E1', $this->lang->line('lb_discount_total'))
                        ->setCellValue('F1', $this->lang->line('lb_tax'))
                        ->setCellValue('G1', $this->lang->line('lb_delivery_cost'))
                        ->setCellValue('H1', $this->lang->line('lb_total'))
                        ->setCellValue('I1', $this->lang->line('lb_remark'));
            $subtotal       = 0;
            $total_discount = 0;
            $total_tax      = 0;
            $total_delivery = 0;
            $total          = 0;
            foreach ($list as $a):
                $no             = $i++; 
                $urut           = $ii++;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, date("Y-m-d",strtotime($a->Date)))
                            ->setCellValue('C'.$urut, $a->Name)
                            ->setCellValue('D'.$urut, $this->main->currency($a->Subtotal))
                            ->setCellValue('E'.$urut, $this->main->currency($a->Discount))
                            ->setCellValue('F'.$urut, $this->main->currency($a->PPN))
                            ->setCellValue('G'.$urut, $this->main->currency($a->DeliveryCost))
                            ->setCellValue('H'.$urut, $this->main->currency($a->Total))
                            ->setCellValue('I'.$urut, $a->Remark)
                            ;
            $subtotal       += $a->Subtotal;
            $total_discount += $a->Discount;
            $total_tax      += $a->PPN;
            $total_delivery += $a->DeliveryCost;
            $total          += $a->Total;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':C'.$urut)
                            ->setCellValue('D'.$urut, $this->main->currency($subtotal))
                            ->setCellValue('E'.$urut, $this->main->currency($total_discount))
                            ->setCellValue('F'.$urut, $this->main->currency($total_tax))
                            ->setCellValue('G'.$urut, $this->main->currency($total_delivery))
                            ->setCellValue('H'.$urut, $this->main->currency($total));
        endif;
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

    public function invoice_vendor_excell()
    {
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        $nama_laporan   = "Invoice Payable";
        $nama_laporan   = $this->input->get("name");
        $table          = "PS_Invoice";
        $list           = $this->report->get_datatables($table);   
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
        $urut   = 1;
        if($group == "transaction"):

            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:J".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','J') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_date'))
                        ->setCellValue('C1', $this->lang->line('lb_transaction_no'))
                        ->setCellValue('D1', $this->lang->line('lb_vendor_name'))
                        ->setCellValue('E1', $this->lang->line('lb_sub_total'))
                        ->setCellValue('F1', $this->lang->line('lb_discount_total'))
                        ->setCellValue('G1', $this->lang->line('lb_tax'))
                        ->setCellValue('H1', $this->lang->line('lb_delivery_cost'))
                        ->setCellValue('I1', $this->lang->line('lb_total'))
                        ->setCellValue('J1', $this->lang->line('lb_remark'));
            $subtotal       = 0;
            $total_discount = 0;
            $total_tax      = 0;
            $total_delivery = 0;
            $total          = 0;
            foreach ($list as $a):
                $no             = $i++; 
                $urut           = $ii++;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, date("Y-m-d",strtotime($a->Date)))
                            ->setCellValue('C'.$urut, $a->InvoiceNo)
                            ->setCellValue('D'.$urut, $a->Name)
                            ->setCellValue('E'.$urut, $a->Subtotal)
                            ->setCellValue('F'.$urut, $a->Discount)
                            ->setCellValue('G'.$urut, $a->PPN)
                            ->setCellValue('H'.$urut, $a->DeliveryCost)
                            ->setCellValue('I'.$urut, $a->Total)
                            ->setCellValue('J'.$urut, $a->Remark)
                            ;
            $subtotal       += $a->Subtotal;
            $total_discount += $a->Discount;
            $total_tax      += $a->PPN;
            $total_delivery += $a->DeliveryCost;
            $total          += $a->Total;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':D'.$urut)
                            ->setCellValue('E'.$urut, $subtotal)
                            ->setCellValue('F'.$urut, $total_discount)
                            ->setCellValue('G'.$urut, $total_tax)
                            ->setCellValue('H'.$urut, $total_delivery)
                            ->setCellValue('I'.$urut, $total);
        elseif($group == "all"):
             $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:K".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','K') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_invoiceno'))
                        ->setCellValue('C1', $this->lang->line('lb_transaction_no'))
                        ->setCellValue('D1', $this->lang->line('lb_transaction_date'))
                        ->setCellValue('E1', $this->lang->line('lb_vendor_name'))
                        ->setCellValue('F1', $this->lang->line('lb_sub_total'))
                        ->setCellValue('G1', $this->lang->line('lb_discount_total'))
                        ->setCellValue('H1', $this->lang->line('lb_tax'))
                        ->setCellValue('I1', $this->lang->line('lb_delivery_cost'))
                        ->setCellValue('J1', $this->lang->line('lb_total'))
                        ->setCellValue('K1', $this->lang->line('lb_remark'));
            $subtotal       = 0;
            $total_discount = 0;
            $total_tax      = 0;
            $total_delivery = 0;
            $total          = 0;
            foreach ($list as $a):
                $no             = $i++; 
                $urut           = $ii++;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->InvoiceNo)
                            ->setCellValue('C'.$urut, date("Y-m-d",strtotime($a->transactionCode)))
                            ->setCellValue('D'.$urut, $a->transactionDate)
                            ->setCellValue('E'.$urut, $a->Name)
                            ->setCellValue('F'.$urut, $a->Subtotal)
                            ->setCellValue('G'.$urut, $a->Discount)
                            ->setCellValue('H'.$urut, $a->PPN)
                            ->setCellValue('I'.$urut, $a->DeliveryCost)
                            ->setCellValue('J'.$urut, $a->Total)
                            ->setCellValue('K'.$urut, $a->Remark)
                            ;
            $subtotal       += $a->Subtotal;
            $total_discount += $a->Discount;
            $total_tax      += $a->PPN;
            $total_delivery += $a->DeliveryCost;
            $total          += $a->Total;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':E'.$urut)
                            ->setCellValue('F'.$urut, $subtotal)
                            ->setCellValue('G'.$urut, $total_discount)
                            ->setCellValue('H'.$urut, $total_tax)
                            ->setCellValue('I'.$urut, $total_delivery)
                            ->setCellValue('J'.$urut, $total);
        endif;
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

    public function account_receive_excell()
    {
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        $nama_laporan   = "Account Receive";
        $nama_laporan   = $this->input->get("name");
        $table          = "AC_CorrectionPR_Det";
        $list           = $this->report->get_datatables($table);
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
        if($group == "all"):
            $jumlah_kolom = count($list)+1;
            $objPHPExcel->getActiveSheet()->getStyle("A1:E".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','E') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'No')
                        ->setCellValue('B1', 'Date')
                        ->setCellValue('C1', 'Store Name')
                        ->setCellValue('D1', 'AR No')
                        ->setCellValue('E1', 'Total AR Invoice');
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, date("Y-m-d",strtotime($a->date)))
                            ->setCellValue('C'.$urut, $a->store_name)
                            ->setCellValue('D'.$urut, $a->arcode)
                            ->setCellValue('E'.$urut, $this->main->currency($a->total));
            endforeach;
        elseif($group == "date"):
            $jumlah_kolom = count($list)+1;
            $objPHPExcel->getActiveSheet()->getStyle("A1:C".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','C') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'No')
                        ->setCellValue('B1', 'Date')
                        ->setCellValue('C1', 'Total AR Invoice');
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, date("Y-m-d",strtotime($a->date)))
                            ->setCellValue('C'.$urut, $this->main->currency($a->total));
            endforeach;
        elseif($group == "store_name"):
            $jumlah_kolom = count($list)+1;
            $objPHPExcel->getActiveSheet()->getStyle("A1:C".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','C') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'No')
                        ->setCellValue('B1', 'Store Name')
                        ->setCellValue('C1', 'Total AR Invoice');
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->store_name)
                            ->setCellValue('C'.$urut, $this->main->currency($a->total));
            endforeach;
        elseif($group == "ar_code"):
            $jumlah_kolom = count($list)+1;
            $objPHPExcel->getActiveSheet()->getStyle("A1:C".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','C') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'No')
                        ->setCellValue('B1', 'AR No')
                        ->setCellValue('C1', 'Total AR Invoice');
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->arcode)
                            ->setCellValue('C'.$urut, $this->main->currency($a->total));
            endforeach;
        endif;
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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
    
    public function return_excell()
    {
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        $nama_laporan   = "Return";
        $nama_laporan   = $this->input->get("name");
        $table          = "AP_Retur";
        $list           = $this->report->get_datatables($table); 
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
        $urut   = 1;
        if($group == "all"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:P".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','P') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:P1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_transaction_no'))
                        ->setCellValue('C1', $this->lang->line('lb_date'))
                        ->setCellValue('D1', $this->lang->line('lb_goodrcno'))
                        ->setCellValue('E1', $this->lang->line('lb_vendor_name'))
                        ->setCellValue('F1', $this->lang->line('lb_store'))
                        ->setCellValue('G1', $this->lang->line('lb_product_code'))
                        ->setCellValue('H1', $this->lang->line('lb_product_name'))
                        ->setCellValue('I1', $this->lang->line('lb_qty'))
                        ->setCellValue('J1', $this->lang->line('lb_unit'))
                        ->setCellValue('K1', $this->lang->line('lb_conversion'))
                        ->setCellValue('L1', $this->lang->line('price'))
                        ->setCellValue('M1', $this->lang->line('lb_sub_total'))
                        ->setCellValue('N1', $this->lang->line('lb_discount'))
                        ->setCellValue('O1', $this->lang->line('lb_tax'))
                        ->setCellValue('P1', $this->lang->line('lb_total'));

            $total_qty      = 0;
            $total_price    = 0;
            $total_subtotal = 0;
            $total_tax      = 0;
            $total          = 0;
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++;

                $discount   = $this->main->PersenttoRp($a->subtotal,$a->discount);
                $sub_total  = $a->subtotal - $discount;
                if($a->Tax == 1):
                  $tax        = $this->main->PersenttoRp($sub_total,10);
                else:
                  $tax        = 0;
                endif;
                
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->returno)
                            ->setCellValue('C'.$urut, date("Y-m-d",strtotime($a->date)))
                            ->setCellValue('D'.$urut, $a->receiveno)
                            ->setCellValue('E'.$urut, $a->vendorname)
                            ->setCellValue('F'.$urut, $a->branchName)
                            ->setCellValue('G'.$urut, $a->product_code)
                            ->setCellValue('H'.$urut, $a->product_name)
                            ->setCellValue('I'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('J'.$urut, $a->unit_name)
                            ->setCellValue('K'.$urut, $a->conversion)
                            ->setCellValue('L'.$urut, $this->main->currency($a->price))
                            ->setCellValue('M'.$urut, $this->main->currency($a->subtotal))
                            ->setCellValue('N'.$urut, $a->discount." %")
                            ->setCellValue('O'.$urut, $this->main->currency($tax))
                            ->setCellValue('P'.$urut, $this->main->currency($a->total));

            $total_qty      += $a->qty;
            $total_price    += $a->price;
            $total_subtotal += $a->subtotal;
            $total_tax      += $tax;
            $total          += $a->total;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':H'.$urut)
                            ->setCellValue('I'.$urut, $this->main->qty($total_qty))
                            ->setCellValue('L'.$urut, $this->main->currency($total_price))
                            ->setCellValue('M'.$urut, $this->main->currency($total_subtotal))
                            ->setCellValue('O'.$urut, $this->main->currency($total_tax))
                            ->setCellValue('P'.$urut, $this->main->currency($total));
        elseif($group == "date"):
            $jumlah_kolom = count($list)+1;
            $objPHPExcel->getActiveSheet()->getStyle("A1:D".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','D') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_date'))
                        ->setCellValue('C1', $this->lang->line('lb_qty'))
                        ->setCellValue('D1', $this->lang->line('lb_sub_total'));
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, date("Y-m-d",strtotime($a->date)))
                            ->setCellValue('C'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('D'.$urut, $this->main->currency($a->subtotal));
            endforeach;
        elseif($group == "return_code"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:K".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','K') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_transaction_no'))
                        ->setCellValue('C1', $this->lang->line('lb_transaction_date'))
                        ->setCellValue('D1', $this->lang->line('lb_goodrcno'))
                        ->setCellValue('E1', $this->lang->line('lb_vendor_name'))
                        ->setCellValue('F1', $this->lang->line('lb_store'))
                        ->setCellValue('G1', $this->lang->line('lb_qty_total'))
                        ->setCellValue('H1', $this->lang->line('lb_sub_total'))
                        ->setCellValue('I1', $this->lang->line('lb_discount_total'))
                        ->setCellValue('J1', $this->lang->line('lb_tax'))
                        ->setCellValue('K1', $this->lang->line('lb_total'));

            $total              = 0;
            $total_qty          = 0;
            $total_price        = 0;
            $total_discount     = 0;
            $total_tax          = 0;
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->returno)
                            ->setCellValue('C'.$urut, $a->date)
                            ->setCellValue('D'.$urut, $a->receiveno)
                            ->setCellValue('E'.$urut, $a->vendorname)
                            ->setCellValue('F'.$urut, $a->branchName)
                            ->setCellValue('G'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('H'.$urut, $this->main->currency($a->subtotal))
                            ->setCellValue('I'.$urut, $this->main->currency($a->discount))
                            ->setCellValue('J'.$urut, $this->main->currency($a->TotalPPN))
                            ->setCellValue('K'.$urut, $this->main->currency($a->total));

            $total          += $a->total;
            $total_price    += $a->subtotal;
            $total_discount += $a->discount;
            $total_tax      += $a->TotalPPN;
            $total_qty      += $a->qty;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':F'.$urut)
                            ->setCellValue('G'.$urut, $this->main->qty($total_qty))
                            ->setCellValue('H'.$urut, $this->main->currency($total_price))
                            ->setCellValue('I'.$urut, $this->main->currency($total_discount))
                            ->setCellValue('J'.$urut, $this->main->currency($total_tax))
                            ->setCellValue('K'.$urut, $this->main->currency($total));
        elseif($group == "vendor_name"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:E".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','E') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_vendor_name'))
                        ->setCellValue('C1', $this->lang->line('lb_qty'))
                        ->setCellValue('D1', $this->lang->line('lb_total'))
                        ->setCellValue('E1', $this->lang->line('lb_return_purchase_total'));

            $total_qty      = 0;
            $total          = 0;
            $total_rp       = 0;
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->vendorname)
                            ->setCellValue('C'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('D'.$urut, $this->main->currency($a->total))
                            ->setCellValue('E'.$urut, $a->total_return);

            $total_qty     += $a->qty;
            $total         += $a->total;
            $total_rp      += $a->total_return;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':B'.$urut)
                            ->setCellValue('C'.$urut, $this->main->qty($total_qty))
                            ->setCellValue('D'.$urut, $this->main->currency($total))
                            ->setCellValue('E'.$urut, $total_rp);
        elseif($group == "product_name"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:F".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','F') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_product_code'))
                        ->setCellValue('C1', $this->lang->line('lb_product_name'))
                        ->setCellValue('D1', $this->lang->line('lb_qty'))
                        ->setCellValue('E1', $this->lang->line('lb_unit'))
                        ->setCellValue('F1', $this->lang->line('lb_conversion'));

            $total_qty      = 0;
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->product_code)
                            ->setCellValue('C'.$urut, $a->product_name)
                            ->setCellValue('D'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('E'.$urut, $a->unit_name)
                            ->setCellValue('F'.$urut, $a->conversion);

                $total_qty     += $a->qty;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':C'.$urut)
                            ->setCellValue('D'.$urut, $this->main->qty($total_qty));
        elseif($group == "store"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:E".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','E') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_store'))
                        ->setCellValue('C1', $this->lang->line('lb_qty'))
                        ->setCellValue('D1', $this->lang->line('lb_total'))
                        ->setCellValue('E1', $this->lang->line('lb_return_purchase_total'));

            $total_qty      = 0;
            $total          = 0;
            $total_rp       = 0;
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->branchName)
                            ->setCellValue('C'.$urut, $this->main->qty($a->qty))
                            ->setCellValue('D'.$urut, $this->main->currency($a->total))
                            ->setCellValue('E'.$urut, $a->total_return);

            $total_qty     += $a->qty;
            $total         += $a->total;
            $total_rp      += $a->total_return;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':B'.$urut)
                            ->setCellValue('C'.$urut, $this->main->qty($total_qty))
                            ->setCellValue('D'.$urut, $this->main->currency($total))
                            ->setCellValue('E'.$urut, $total_rp);
        endif;
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

    public function payment_payable_excell(){
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        $nama_laporan   = "Payment Payable";
        $nama_laporan   = $this->input->get("name");
        $table          = "PS_Payment_Detail";
        $list           = $this->report->get_datatables($table); 
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
        $urut   = 1;
        if($group == "all"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:I".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','I') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_date'))
                        ->setCellValue('C1', $this->lang->line('lb_paymentno'))
                        ->setCellValue('D1', $this->lang->line('lb_vendor_name'))
                        ->setCellValue('E1', $this->lang->line('lb_transaction_date'))
                        ->setCellValue('F1', $this->lang->line('lb_transaction_no'))
                        ->setCellValue('G1', $this->lang->line('lb_total_nota'))
                        ->setCellValue('H1', $this->lang->line('lb_total_unpaid'))
                        ->setCellValue('I1', $this->lang->line('lb_total_payment'));
            $total_nota     = 0;
            $total_unpaid   = 0;
            $total_payment  = 0;
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, date("Y-m-d",strtotime($a->Date)))
                            ->setCellValue('C'.$urut, $a->paymentno)
                            ->setCellValue('D'.$urut, $a->vendorName)
                            ->setCellValue('E'.$urut, $a->transactionDate)
                            ->setCellValue('F'.$urut, $a->transactionCode)
                            ->setCellValue('G'.$urut, $this->main->currency($a->grandtotal))
                            ->setCellValue('H'.$urut, $this->main->currency($a->unpaid))
                            ->setCellValue('I'.$urut, $this->main->currency($a->total_payment));
            $total_nota     += $a->grandtotal;
            $total_unpaid   += $a->unpaid;
            $total_payment  += $a->total_payment;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':F'.$urut)
                            ->setCellValue('G'.$urut, $this->main->currency($total_nota))
                            ->setCellValue('H'.$urut, $this->main->currency($total_unpaid))
                            ->setCellValue('I'.$urut, $this->main->currency($total_payment));
        elseif($group == "payment_code"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:J".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','J') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_date'))
                        ->setCellValue('C1', $this->lang->line('lb_paymentno'))
                        ->setCellValue('D1', $this->lang->line('lb_vendor_name'))
                        ->setCellValue('E1', $this->lang->line('lb_total_nota'))
                        ->setCellValue('F1', $this->lang->line('lb_giro_total'))
                        ->setCellValue('G1', $this->lang->line('lb_debit_credit_total'))
                        ->setCellValue('H1', $this->lang->line('lb_cash_total'))
                        ->setCellValue('I1', $this->lang->line('lb_pay_additional'))
                        ->setCellValue('J1', $this->lang->line('lb_total_payment'));
            $total_nota     = 0;
            $total_giro     = 0;
            $total_kredit   = 0;
            $total_cash     = 0;
            $total_aditional= 0;
            $total_payment  = 0;
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, date("Y-m-d",strtotime($a->Date)))
                            ->setCellValue('C'.$urut, $a->paymentno)
                            ->setCellValue('D'.$urut, $a->vendorName)
                            ->setCellValue('E'.$urut, $this->main->currency($a->grandtotal))
                            ->setCellValue('F'.$urut, $this->main->currency($a->giro))
                            ->setCellValue('G'.$urut, $this->main->currency($a->credit))
                            ->setCellValue('H'.$urut, $this->main->currency($a->cash))
                            ->setCellValue('I'.$urut, $this->main->currency($a->addcost))
                            ->setCellValue('J'.$urut, $this->main->currency($a->total_payment));
            $total_nota     += $a->grandtotal;
            $total_giro     += $a->giro;
            $total_kredit   += $a->credit;
            $total_cash     += $a->cash;
            $total_aditional+= $a->addcost;
            $total_payment  += $a->total_payment;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':D'.$urut)
                            ->setCellValue('E'.$urut, $this->main->currency($total_nota))
                            ->setCellValue('F'.$urut, $this->main->currency($total_giro))
                            ->setCellValue('G'.$urut, $this->main->currency($total_kredit))
                            ->setCellValue('H'.$urut, $this->main->currency($total_cash))
                            ->setCellValue('I'.$urut, $this->main->currency($total_aditional))
                            ->setCellValue('J'.$urut, $this->main->currency($total_payment));
        endif;

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

    public function payment_excell()
    {
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        $nama_laporan   = "Payment";
        $nama_laporan   = $this->input->get("name");
        $table          = "PS_Payment_Detail";
        $list           = $this->report->get_datatables($table); 
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
        $urut   = 1;
        if($group == "all"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:J".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','J') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_date'))
                        ->setCellValue('C1', $this->lang->line('lb_transaction_no'))
                        ->setCellValue('D1', $this->lang->line('lb_customer_name'))
                        ->setCellValue('E1', $this->lang->line('lb_transaction_date'))
                        ->setCellValue('F1', $this->lang->line('lb_transaction_no'))
                        ->setCellValue('G1', $this->lang->line('lb_store_name'))
                        ->setCellValue('H1', $this->lang->line('lb_total_netto'))
                        ->setCellValue('I1', $this->lang->line('lb_total_unpaid'))
                        ->setCellValue('J1', $this->lang->line('lb_total_paid'));
            $total_net      =0;
            $total_unpaid   =0;
            $total_paid     =0;
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, date("Y-m-d",strtotime($a->date)))
                            ->setCellValue('C'.$urut, $a->paymentno)
                            ->setCellValue('D'.$urut, $a->vendorName)
                            ->setCellValue('E'.$urut, date("Y-m-d",strtotime($a->transactionDate)))
                            ->setCellValue('F'.$urut, $a->sellno)
                            ->setCellValue('G'.$urut, $a->store_name)
                            ->setCellValue('H'.$urut, $this->main->currency($a->grandtotal))
                            ->setCellValue('I'.$urut, $this->main->currency($a->unpaid))
                            ->setCellValue('J'.$urut, $this->main->currency($a->total_payment));
            $total_net      +=$a->grandtotal;
            $total_unpaid   +=$a->unpaid;
            $total_paid     +=$a->total_payment;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':G'.$urut)
                            ->setCellValue('H'.$urut, $this->main->currency($total_net))
                            ->setCellValue('I'.$urut, $this->main->currency($total_unpaid))
                            ->setCellValue('J'.$urut, $this->main->currency($total_paid));
        elseif($group == "date"):
            $jumlah_kolom = count($list)+1;
            $objPHPExcel->getActiveSheet()->getStyle("A1:H".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','H') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_date'))
                        ->setCellValue('C1', $this->lang->line('lb_grand_total_sales'))
                        ->setCellValue('D1', $this->lang->line('lb_giro_total'))
                        ->setCellValue('E1', $this->lang->line('lb_debit_credit_total'))
                        ->setCellValue('F1', $this->lang->line('lb_cash_total'))
                        ->setCellValue('G1', $this->lang->line('lb_pay_additional'))
                        ->setCellValue('H1', $this->lang->line('lb_total_payment'));
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, date("Y-m-d",strtotime($a->date)))
                            ->setCellValue('C'.$urut, $this->main->currency($a->grandtotal))
                            ->setCellValue('D'.$urut, $this->main->currency($a->giro))
                            ->setCellValue('E'.$urut, $this->main->currency($a->credit))
                            ->setCellValue('F'.$urut, $this->main->currency($a->cash))
                            ->setCellValue('G'.$urut, $this->main->currency($a->addcost))
                            ->setCellValue('H'.$urut, $this->main->currency($a->total_payment));
            endforeach;
        elseif($group == "payment_code"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:I".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','I') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_date'))
                        ->setCellValue('C1', $this->lang->line('lb_transaction_no'))
                        ->setCellValue('D1', $this->lang->line('lb_grand_total_sales'))
                        ->setCellValue('E1', $this->lang->line('lb_giro_total'))
                        ->setCellValue('F1', $this->lang->line('lb_debit_credit_total'))
                        ->setCellValue('G1', $this->lang->line('lb_cash_total'))
                        ->setCellValue('H1', $this->lang->line('lb_pay_additional'))
                        ->setCellValue('I1', $this->lang->line('lb_total_payment'));
            $total_net      =0;
            $total_giro     =0;
            $total_kredit   =0;
            $total_cash     =0;
            $total_aditional=0;
            $total_payment  =0;
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, date("Y-m-d",strtotime($a->Date)))
                            ->setCellValue('C'.$urut, $a->paymentno)
                            ->setCellValue('D'.$urut, $this->main->currency($a->grandtotal))
                            ->setCellValue('E'.$urut, $this->main->currency($a->giro))
                            ->setCellValue('F'.$urut, $this->main->currency($a->credit))
                            ->setCellValue('G'.$urut, $this->main->currency($a->cash))
                            ->setCellValue('H'.$urut, $this->main->currency($a->addcost))
                            ->setCellValue('I'.$urut, $this->main->currency($a->total_payment));
            $total_net      +=$a->grandtotal;
            $total_giro     +=$a->giro;
            $total_kredit   +=$a->credit;
            $total_cash     +=$a->cash;
            $total_aditional+=$a->addcost;
            $total_payment  +=$a->total_payment;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':C'.$urut)
                            ->setCellValue('D'.$urut, $this->main->currency($total_net))
                            ->setCellValue('E'.$urut, $this->main->currency($total_giro))
                            ->setCellValue('F'.$urut, $this->main->currency($total_kredit))
                            ->setCellValue('G'.$urut, $this->main->currency($total_cash))
                            ->setCellValue('H'.$urut, $this->main->currency($total_aditional))
                            ->setCellValue('I'.$urut, $this->main->currency($total_payment));
        elseif($group == "store_name"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:H".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','H') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_store_name'))
                        ->setCellValue('C1', $this->lang->line('lb_grand_total_sales'))
                        ->setCellValue('D1', $this->lang->line('lb_giro_total'))
                        ->setCellValue('E1', $this->lang->line('lb_debit_credit_total'))
                        ->setCellValue('F1', $this->lang->line('lb_cash_total'))
                        ->setCellValue('G1', $this->lang->line('lb_pay_additional'))
                        ->setCellValue('H1', $this->lang->line('lb_total_payment'));
            $total_sales    =0;
            $total_giro     =0;
            $total_kredit   =0;
            $total_cash     =0;
            $total_aditional=0;
            $total_payment  =0;
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->store_name)
                            ->setCellValue('C'.$urut, $this->main->currency($a->grandtotal))
                            ->setCellValue('D'.$urut, $this->main->currency($a->giro))
                            ->setCellValue('E'.$urut, $this->main->currency($a->credit))
                            ->setCellValue('F'.$urut, $this->main->currency($a->cash))
                            ->setCellValue('G'.$urut, $this->main->currency($a->addcost))
                            ->setCellValue('H'.$urut, $this->main->currency($a->total_payment));
            $total_sales    +=$a->grandtotal;
            $total_giro     +=$a->giro;
            $total_kredit   +=$a->credit;
            $total_cash     +=$a->cash;
            $total_aditional+=$a->addcost;
            $total_payment  +=$a->total_payment;
            endforeach;
                $urut += 2;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':B'.$urut)
                            ->setCellValue('C'.$urut, $this->main->currency($total_sales))
                            ->setCellValue('D'.$urut, $this->main->currency($total_giro))
                            ->setCellValue('E'.$urut, $this->main->currency($total_kredit))
                            ->setCellValue('F'.$urut, $this->main->currency($total_cash))
                            ->setCellValue('G'.$urut, $this->main->currency($total_aditional))
                            ->setCellValue('H'.$urut, $this->main->currency($total_payment));
        elseif($group == "sales_code"):
            $jumlah_kolom = count($list)+1;
            $objPHPExcel->getActiveSheet()->getStyle("A1:H".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','H') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_sellingno'))
                        ->setCellValue('C1', $this->lang->line('lb_grand_total_sales'))
                        ->setCellValue('D1', $this->lang->line('lb_giro_total'))
                        ->setCellValue('E1', $this->lang->line('lb_debit_credit_total'))
                        ->setCellValue('F1', $this->lang->line('lb_cash_total'))
                        ->setCellValue('G1', $this->lang->line('lb_pay_additional'))
                        ->setCellValue('H1', $this->lang->line('lb_total_payment'));
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->sellno)
                            ->setCellValue('C'.$urut, $this->main->currency($a->grandtotal))
                            ->setCellValue('D'.$urut, $this->main->currency($a->giro))
                            ->setCellValue('E'.$urut, $this->main->currency($a->credit))
                            ->setCellValue('F'.$urut, $this->main->currency($a->cash))
                            ->setCellValue('G'.$urut, $this->main->currency($a->addcost))
                            ->setCellValue('H'.$urut, $this->main->currency($a->total_payment));
            endforeach;
        endif;
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

    public function age_off_debt_excell()
    {
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        $nama_laporan   = "Age Off Debt";
        $nama_laporan   = $this->input->get("name");
        $list           = $this->report->select_age_off_debt();   
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
        $urut   = 1;
        if($group == "all"):

            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:C".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','C') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_customer_name'))
                        ->setCellValue('C1', $this->lang->line('lb_saldo'));
            $total_Saldo1  = 0;
            $total_Saldo   = 0;
            $totalnota     = 0;
            $total         = 0;
            $saldo_total   = 0;
            foreach ($list as $a):

                $totalnota      = $a->totalnota;
                $total          = floatval($a->total);
                $total_Saldo    = $total - $totalnota;
                $total_Saldo1   += floatval($total_Saldo);

                $no             = $i++; 
                $urut           = $ii++;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no)
                            ->setCellValue('B'.$urut, $a->vendorName)
                            ->setCellValue('C'.$urut, $this->main->currency($a->Unpaid));
            $saldo_total   += $a->Unpaid;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':B'.$urut)
                            ->setCellValue('C'.$urut, $this->main->currency($saldo_total));

        elseif($group == "transaction"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:K".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','K') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_customer_name'))
                        ->setCellValue('C1', $this->lang->line('lb_transaction_no'))
                        ->setCellValue('D1', $this->lang->line('lb_transaction_date'))
                        ->setCellValue('E1', $this->lang->line('lb_term').' ('.$this->lang->line('lb_days').')')
                        ->setCellValue('F1', $this->lang->line('lb_due_date'))
                        ->setCellValue('G1', $this->lang->line('lb_total_nota'))
                        ->setCellValue('H1', '0-30 '.$this->lang->line('lb_days'))
                        ->setCellValue('I1', '31-60 '.$this->lang->line('lb_days'))
                        ->setCellValue('J1', '60-90 '.$this->lang->line('lb_days'))
                        ->setCellValue('K1', '>90 '.$this->lang->line('lb_days'));
            $top        = 0;
            $total_nota = 0;
            $days30     = 0;
            $days31     = 0;
            $days60     = 0;
            $days90     = 0;
            foreach ($list as $a):

                $no     = $i++; 
                $urut   = $ii++;

                $max30  = 0;
                $max60  = 0;
                $max90  = 0;
                $max    = 0;

                $due_date = date("Y-m-d", strtotime($a->transactionDate." +".$a->Term." Days"));
                $selisih = 0;
                if($due_date<date("Y-m-d")):
                    $selisih  = $this->main->selisih_hari($due_date,date("Y-m-d"));
                endif;

                if($selisih<=30):
                    $max30  = $a->Unpaid;
                elseif($selisih<=60):
                    $max60  = $a->Unpaid;
                elseif($selisih<=90):
                    $max90  = $a->Unpaid;
                else:
                    $max    = $a->Unpaid;
                endif;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->vendorName)
                            ->setCellValue('C'.$urut, $a->transactionCode)
                            ->setCellValue('D'.$urut, date("Y-m-d",strtotime($a->transactionDate)))
                            ->setCellValue('E'.$urut, (float) $a->Term)                   
                            ->setCellValue('F'.$urut, date("Y-m-d", strtotime($a->transactionDate." +".$a->Term." Days")))
                            ->setCellValue('G'.$urut, $this->main->currency($a->Unpaid))
                            ->setCellValue('H'.$urut, $this->main->currency($max30))
                            ->setCellValue('I'.$urut, $this->main->currency($max60))
                            ->setCellValue('J'.$urut, $this->main->currency($max90))
                            ->setCellValue('K'.$urut, $this->main->currency($max));
            $top        += $a->Term;
            $total_nota += $a->Unpaid;
            $days30     += $max30;
            $days31     += $max60;
            $days60     += $max90;
            $days90     += $max;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':D'.$urut)
                            ->setCellValue('E'.$urut, (float) $top)                   
                            ->setCellValue('G'.$urut, $this->main->currency($total_nota))
                            ->setCellValue('H'.$urut, $this->main->currency($days30))
                            ->setCellValue('I'.$urut, $this->main->currency($days31))
                            ->setCellValue('J'.$urut, $this->main->currency($days60))
                            ->setCellValue('K'.$urut, $this->main->currency($days90));
        endif;
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

    public function age_off_credit_excell()
    {
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        $nama_laporan   = "Age Off Credit";  

        $list           = $this->report->select_age_off_credit();   
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
        $urut   = 1;
        if($group == "all"):

            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:C".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','C') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_vendor_name'))
                        ->setCellValue('C1', $this->lang->line('lb_saldo'));
            $total_Saldo1  = 0;
            $total_Saldo   = 0;
            $totalnota     = 0;
            $total         = 0;
            $total_unpaid  = 0;
            foreach ($list as $a):

                // $totalnota      = $a->totalnota;
                // $total          = floatval($a->total);
                // $total_Saldo    = $total - $totalnota;
                // $total_Saldo1   += floatval($total_Saldo);

                $no             = $i++; 
                $urut           = $ii++;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no)
                            ->setCellValue('B'.$urut, $a->vendorName)
                            ->setCellValue('C'.$urut, $this->main->currency($a->Unpaid));
            $total_unpaid  += $a->Unpaid;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':B'.$urut)
                            ->setCellValue('C'.$urut, $this->main->currency($total_unpaid));

        elseif($group == "transaction"):
            $jumlah_kolom = count($list)+2;
            $objPHPExcel->getActiveSheet()->getStyle("A1:K".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','K') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_vendor_name'))
                        ->setCellValue('C1', $this->lang->line('lb_transaction_no'))
                        ->setCellValue('D1', $this->lang->line('lb_transaction_date'))
                        ->setCellValue('E1', $this->lang->line('lb_term').'('.$this->lang->line('lb_days').')')
                        ->setCellValue('F1', $this->lang->line('lb_due_date'))
                        ->setCellValue('G1', $this->lang->line('lb_total_nota'))
                        ->setCellValue('H1', '0-30 '.$this->lang->line('lb_days'))
                        ->setCellValue('I1', '31-60 '.$this->lang->line('lb_days'))
                        ->setCellValue('J1', '60-90 '.$this->lang->line('lb_days'))
                        ->setCellValue('K1', '>90 '.$this->lang->line('lb_days'));
            $total_top  = 0;
            $total_nota = 0;
            $days1      = 0;
            $days2      = 0;
            $days3      = 0;
            $days4      = 0;
            foreach ($list as $a):

                $no     = $i++; 
                $urut   = $ii++;

                $max30  = 0;
                $max60  = 0;
                $max90  = 0;
                $max    = 0;

                $due_date = date("Y-m-d", strtotime($a->transactionDate." +".$a->Term." Days"));
                $selisih = 0;
                if($due_date<date("Y-m-d")):
                    $selisih  = $this->main->selisih_hari($due_date,date("Y-m-d"));
                endif;

                if($selisih<=30):
                    $max30  = $a->Unpaid;
                elseif($selisih<=60):
                    $max60  = $a->Unpaid;
                elseif($selisih<=90):
                    $max90  = $a->Unpaid;
                else:
                    $max    = $a->Unpaid;
                endif;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->vendorName)
                            ->setCellValue('C'.$urut, $a->transactionCode)
                            ->setCellValue('D'.$urut, date("Y-m-d",strtotime($a->transactionDate)))
                            ->setCellValue('E'.$urut, (float) $a->Term)                   
                            ->setCellValue('F'.$urut, date("Y-m-d", strtotime($a->transactionDate." +".$a->Term." Days")))
                            ->setCellValue('G'.$urut, $this->main->currency($a->Unpaid))
                            ->setCellValue('H'.$urut, $this->main->currency($max30))
                            ->setCellValue('I'.$urut, $this->main->currency($max60))
                            ->setCellValue('J'.$urut, $this->main->currency($max90))
                            ->setCellValue('K'.$urut, $this->main->currency($max));
            $total_top  += $a->Term;
            $total_nota += $a->Unpaid;
            $days1      += $max30;
            $days2      += $max60;
            $days3      += $max90;
            $days4      += $max;
            endforeach;
                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':D'.$urut)
                            ->setCellValue('E'.$urut, (float) $total_top)                   
                            ->setCellValue('G'.$urut, $this->main->currency($total_nota))
                            ->setCellValue('H'.$urut, $this->main->currency($days1))
                            ->setCellValue('I'.$urut, $this->main->currency($days2))
                            ->setCellValue('J'.$urut, $this->main->currency($days3))
                            ->setCellValue('K'.$urut, $this->main->currency($days4));
        endif;
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

    #2018-02-07
    public function stock_excell()
    {
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        $nama_laporan   = "Stock";
        $list           = $this->report->stock_report();
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
        $objPHPExcel->getActiveSheet()->getStyle("A1:L".$jumlah_kolom)->applyFromArray($border_style);
        foreach(range('A','L') as $columnID):
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        endforeach;
        $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'No')
                    ->setCellValue('B1', 'Date')
                    ->setCellValue('C1', 'Product Name')
                    ->setCellValue('D1', 'Category No')
                    ->setCellValue('E1', 'Category Name')
                    ->setCellValue('F1', 'Initial')
                    ->setCellValue('G1', 'In')
                    ->setCellValue('H1', 'Out')
                    ->setCellValue('I1', 'Last')
                    ->setCellValue('J1', 'Unit')
                    ->setCellValue('K1', 'Conv.')
                    ->setCellValue('L1', 'Min. Qty');
        foreach ($list as $a):
            $initial = $this->report->stock_initial($a->productid,$a->date,$a->conversion,$a->unitid);
            if($initial){
                $initial = $initial->qty;
            } else {
                $initial = 0;
            }
            $last = $initial + $a->qty;

            $no     = $i++; 
            $urut   = $ii++; 
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$urut, $no) #no
                        ->setCellValue('B'.$urut, date("Y-m-d",strtotime($a->date)))
                        ->setCellValue('C'.$urut, $a->product_name)
                        ->setCellValue('D'.$urut, $a->category_code)
                        ->setCellValue('E'.$urut, $a->category_name)
                        ->setCellValue('F'.$urut, $this->main->qty($initial))
                        ->setCellValue('G'.$urut, $this->main->qty($a->qty_in))
                        ->setCellValue('H'.$urut, $this->main->qty($a->qty_out))
                        ->setCellValue('I'.$urut, $this->main->qty($last))
                        ->setCellValue('J'.$urut, $a->unit_name)
                        ->setCellValue('K'.$urut, $a->conversion)
                        ->setCellValue('L'.$urut, $this->main->qty($a->min_qty));
        endforeach;
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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
    #2018-02-07
    public function serial_number_excell()
    {
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        $nama_laporan   = "Serial Number";
        $nama_laporan   = $this->input->get("name");
        $list   = $this->report->serial_number_report();
        

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
        $urut   = 1;
        $jumlah_kolom = count($list)+1;

        if($group == "all"):
            $objPHPExcel->getActiveSheet()->getStyle("A1:F".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','F') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;

            $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_store'))
                        ->setCellValue('C1', $this->lang->line('lb_product_code'))
                        ->setCellValue('D1', $this->lang->line('lb_product_name'))
                        ->setCellValue('E1', $this->lang->line('lb_type'))
                        ->setCellValue('F1', $this->lang->line('lb_sn'));

            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->branchName)
                            ->setCellValue('C'.$urut, $a->product_code)
                            ->setCellValue('D'.$urut, $a->product_name)
                            ->setCellValue('E'.$urut, $a->type_serial)
                            ->setCellValue('F'.$urut, $a->serialnumber);
            endforeach;

        else:
            $objPHPExcel->getActiveSheet()->getStyle("A1:G".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','G') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;

            $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_date'))
                        ->setCellValue('C1', $this->lang->line('lb_store'))
                        ->setCellValue('D1', $this->lang->line('lb_product_code'))
                        ->setCellValue('E1', $this->lang->line('lb_product_name'))
                        ->setCellValue('F1', $this->lang->line('lb_type'))
                        ->setCellValue('G1', $this->lang->line('lb_sn'));

            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, date("Y-m-d",strtotime($a->date)))
                            ->setCellValue('C'.$urut, $a->branchName)
                            ->setCellValue('D'.$urut, $a->product_code)
                            ->setCellValue('E'.$urut, $a->product_name)
                            ->setCellValue('F'.$urut, $a->type_serial)
                            ->setCellValue('G'.$urut, $a->serialnumber);
            endforeach;
        endif;
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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
    #2018-04-02 ini untuk salespro
    #-----------------------------------------------------------------------------------------------------
    public function sales_visiting_excell()
    {
        $report         = $this->input->post("report");
        $group          = $this->input->post("group");
        $nama_laporan   = "Routing Employee";
        $list           = $this->report->get_datatables("sales_visiting");
        $objPHPExcel    = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("SALESPRO APPLICATION MANAGEMENT")
                    ->setLastModifiedBy("SALESPRO APPLICATION MANAGEMENT")
                    ->setTitle("Office 2003 XLS Test Document")
                    ->setSubject("Office 2003 XLS Test Document")
                    ->setDescription("Dokumen ini dari aplikasi PIPESYS")
                    ->setKeywords("office 2003 openxml php")
                    ->setCategory("Template Excel Driver");
        $border_style   = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
        $i              = 1;
        $ii             = 2;
        if($group == "date"):
            $jumlah_kolom   = count($list)+1;
            $akhir_kolom    = "E";
            $company_kolom  = "";
            if($this->session->ParentID>0):
                $akhir_kolom    = "F";
                $company_kolom  = "Company";
            endif;

            foreach(range('A',$akhir_kolom) as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle("A1:".$akhir_kolom.$jumlah_kolom)->applyFromArray($border_style);
            $objPHPExcel->getActiveSheet()->getStyle('A1:'.$akhir_kolom.'1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'No')
                        ->setCellValue('B1', 'Date')
                        ->setCellValue('C1', 'Sales')
                        ->setCellValue('D1', 'Total Visit')
                        ->setCellValue('E1', 'KM')
                        ->setCellValue('F1', $company_kolom);

            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++;

                if($this->session->ParentID>0):
                    $company_kolom = $a->nama;
                endif;

                $km     = $this->main->Distance($a->ID,"TransactionRouteIDArray",$a->Date)["km"];
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, date("Y-m-d",strtotime($a->Date)))
                            ->setCellValue('C'.$urut, $a->SalesName)
                            ->setCellValue('D'.$urut, $a->total_visit)
                            ->setCellValue('E'.$urut, $km)
                            ->setCellValue('F'.$urut, $company_kolom);
            endforeach;
        else:
            $jumlah_kolom   = count($list)+1;
            $akhir_kolom    = "H";
            $company_kolom  = "";
            if($this->session->ParentID>0):
                $akhir_kolom    = "I";
                $company_kolom  = "Company";
            endif;

            foreach(range('A',$akhir_kolom) as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            $objPHPExcel->getActiveSheet()->getStyle("A1:".$akhir_kolom.$jumlah_kolom)->applyFromArray($border_style);
            $objPHPExcel->getActiveSheet()->getStyle('A1:'.$akhir_kolom.'1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'No')
                        ->setCellValue('B1', 'Transaction No')
                        ->setCellValue('C1', 'Date')
                        ->setCellValue('D1', 'Sales')
                        ->setCellValue('E1', 'Customer')
                        ->setCellValue('F1', 'CheckIn')
                        ->setCellValue('G1', 'KM')
                        ->setCellValue('H1', 'Total KM')
                        ->setCellValue('I1', $company_kolom);

            $origin    = "0";
            $total_km  = 0;
            foreach ($list as $a):
                if(empty($a->VendorID)):
                    $Latlng     = $a->CheckInLatlng;
                else:
                    $Lat        = $a->Lat;
                    $Lng        = $a->Lng;
                    $Latlng     = $Lat.",".$Lng;
                endif;

                $distance = $this->main->Distance($origin,$Latlng);
                $km     = $distance["km"];
                $value  = $distance["value"];
                $total_km    = $total_km+$value;


                $no     = $i++; 
                $urut   = $ii++;

                if($this->session->ParentID>0):
                    $company_kolom = $a->nama;
                endif;



                $CheckIn = $this->main->convertSelisih($a->total_checkin);
                $km      = $this->main->Distance($a->ID, "TransactionRouteID")["km"];
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->Code)
                            ->setCellValue('C'.$urut, date("Y-m-d",strtotime($a->Date)))
                            ->setCellValue('D'.$urut, $a->SalesName)
                            ->setCellValue('E'.$urut, $a->customer)
                            ->setCellValue('F'.$urut, $CheckIn)
                            ->setCellValue('G'.$urut, $km)
                            ->setCellValue('H'.$urut, number_format(($total_km/1000), 1)." KM")
                            ->setCellValue('I'.$urut, $company_kolom);

                $origin = $Latlng;
            endforeach;
        endif;

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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
    public function sales_visiting_time_excell()
    {
        $report         = $this->input->post("report");
        $group          = $this->input->post("group");
        $nama_laporan   = "Employee Visiting Time";
        $list           = $this->report->get_datatables("sales_visiting_time");
        $objPHPExcel    = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("SALESPRO APPLICATION MANAGEMENT")
                    ->setLastModifiedBy("SALESPRO APPLICATION MANAGEMENT")
                    ->setTitle("Office 2003 XLS Test Document")
                    ->setSubject("Office 2003 XLS Test Document")
                    ->setDescription("Dokumen ini dari aplikasi PIPESYS")
                    ->setKeywords("office 2003 openxml php")
                    ->setCategory("Template Excel Driver");
        $border_style   = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
        $i              = 1;
        $ii             = 2;
        $jumlah_kolom   = count($list)+1;
        $akhir_kolom    = "K";
        $company_kolom  = "";
        if($this->session->ParentID>0):
            $akhir_kolom    = "L";
            $company_kolom  = "Company";
        endif;

        foreach(range('A','Z') as $columnID):
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        endforeach;
        $objPHPExcel->getActiveSheet()->getStyle('A1:Z1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("A1:".$akhir_kolom.$jumlah_kolom)->applyFromArray($border_style);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'No')
                    ->setCellValue('B1', 'Transaction')
                    ->setCellValue('C1', 'Date')
                    ->setCellValue('D1', 'Sales')
                    ->setCellValue('E1', 'Customer')
                    ->setCellValue('F1', 'Customer Address')
                    ->setCellValue('G1', 'Check In Address')
                    ->setCellValue('H1', 'Check Out Address')
                    ->setCellValue('I1', 'Check In Time')
                    ->setCellValue('J1', 'Check Out Time')
                    ->setCellValue('K1', 'Duration')
                    ->setCellValue('L1', $company_kolom);

        $total_route        = 0;
        $total_route_miss   = 0;
        $total_planning     = 0;
        $total_not_planning = 0;
        
        foreach ($list as $a):
            
            $CheckIn    = "";
            $CheckOut   = "";
            $duration   = "";
            if($a->CheckIn && $a->CheckOut):
                $duration = $this->main->selisih_jam(date("H:i:s",strtotime($a->CheckIn)),date("H:i:s",strtotime($a->CheckOut)));
            endif;
            if($a->CheckIn):
                $CheckIn = date("H:i",strtotime($a->CheckIn));
            endif;
            if($a->CheckOut):
                $CheckOut = date("H:i",strtotime($a->CheckOut));
            endif;

            $total_route = $total_route+1;
            if($a->CustomerName):
                $total_planning = $total_planning+1;
            else:
                $total_not_planning = $total_not_planning+1;
            endif;
            if(!$a->CheckIn):
                $total_route_miss = $total_route_miss+1;
            endif;

            if($this->session->ParentID>0):
                $company_kolom = $a->nama;
            endif;

            $no     = $i++; 
            $urut   = $ii++; 
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$urut, $no) #no
                        ->setCellValue('B'.$urut, $a->Code)
                        ->setCellValue('C'.$urut, date("Y-m-d",strtotime($a->Date)))
                        ->setCellValue('D'.$urut, $a->SalesName)
                        ->setCellValue('E'.$urut, $a->CustomerName)
                        ->setCellValue('F'.$urut, $a->CustomerAddress)
                        ->setCellValue('G'.$urut, $a->CheckInAddress)
                        ->setCellValue('H'.$urut, $a->CheckOutAddress)
                        ->setCellValue('I'.$urut, $CheckIn)
                        ->setCellValue('J'.$urut, $CheckOut)
                        ->setCellValue('K'.$urut, $duration)
                        ->setCellValue('L'.$urut, $company_kolom);
        endforeach;

        $jumlah_kolom = $jumlah_kolom+2;
        $objPHPExcel->getActiveSheet()->getStyle("A".$jumlah_kolom.":C".($jumlah_kolom+3))->applyFromArray($border_style);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue("A".($jumlah_kolom), 'Total Route')
                    ->setCellValue("B".($jumlah_kolom), ':')
                    ->setCellValue("C".($jumlah_kolom), $total_route)
                    ->setCellValue("A".($jumlah_kolom+1), 'Total Planned Route')
                    ->setCellValue("B".($jumlah_kolom+1), ':')
                    ->setCellValue("C".($jumlah_kolom+1), $total_planning)
                    ->setCellValue("A".($jumlah_kolom+2), 'Total Missed Route')
                    ->setCellValue("B".($jumlah_kolom+2), ':')
                    ->setCellValue("C".($jumlah_kolom+2), $total_route_miss)
                    ->setCellValue("A".($jumlah_kolom+3), 'Total Unplanned Route')
                    ->setCellValue("B".($jumlah_kolom+3), ':')
                    ->setCellValue("C".($jumlah_kolom+3), $total_not_planning);


        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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
    public function sales_visiting_remark_excell()
    {
        $report         = $this->input->post("report");
        $group          = $this->input->post("group");
        $nama_laporan   = "Remark And Note Transaction";
        $list           = $this->report->get_datatables("sales_visiting_time");
        $objPHPExcel    = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("SALESPRO APPLICATION MANAGEMENT")
                    ->setLastModifiedBy("SALESPRO APPLICATION MANAGEMENT")
                    ->setTitle("Office 2003 XLS Test Document")
                    ->setSubject("Office 2003 XLS Test Document")
                    ->setDescription("Dokumen ini dari aplikasi PIPESYS")
                    ->setKeywords("office 2003 openxml php")
                    ->setCategory("Template Excel Driver");
        $border_style   = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
        $i              = 1;
        $ii             = 2;
        $jumlah_kolom   = count($list)+1;
        $akhir_kolom    = "G";
        $company_kolom  = "";
        if($this->session->ParentID>0):
            $akhir_kolom    = "H";
            $company_kolom  = "Company";
        endif;

        foreach(range('A','H') as $columnID):
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        endforeach;
        $objPHPExcel->getActiveSheet()->getStyle("A1:".$akhir_kolom.$jumlah_kolom)->applyFromArray($border_style);
        $objPHPExcel->getActiveSheet()->getStyle('A1:'.$akhir_kolom.'1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'No')
                    ->setCellValue('B1', 'Transaction')
                    ->setCellValue('C1', 'Date')
                    ->setCellValue('D1', 'Sales')
                    ->setCellValue('E1', 'Customer')
                    ->setCellValue('F1', 'Remark')
                    ->setCellValue('G1', 'Remark Sales')
                    ->setCellValue('H1', $company_kolom);

        $total_route        = 0;
        $total_route_miss   = 0;
        $total_planning     = 0;
        $total_not_planning = 0;

        foreach ($list as $a):
            if($this->session->ParentID>0):
                $company_kolom = $a->nama;
            endif;

            $no     = $i++; 
            $urut   = $ii++; 
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$urut, $no) #no
                        ->setCellValue('B'.$urut, $a->Code)
                        ->setCellValue('C'.$urut, date("Y-m-d",strtotime($a->Date)))
                        ->setCellValue('D'.$urut, $a->SalesName)
                        ->setCellValue('E'.$urut, $a->CustomerName)
                        ->setCellValue('F'.$urut, $a->Remark)
                        ->setCellValue('G'.$urut, $a->RemarkSales)
                        ->setCellValue('H'.$urut, $company_kolom);

            $total_route = $total_route+1;
            if(!$a->CheckIn):
                $total_route_miss = $total_route_miss+1;
            endif;
            if($a->CustomerName):
                $total_planning = $total_planning+1;
            else:
                $total_not_planning = $total_not_planning+1;
            endif;
        endforeach;

        $jumlah_kolom = $jumlah_kolom+3;
        $objPHPExcel->getActiveSheet()->getStyle("A".$jumlah_kolom.":C".($jumlah_kolom+3))->applyFromArray($border_style);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue("A".($jumlah_kolom), 'Total Route')
                    ->setCellValue("B".($jumlah_kolom), ':')
                    ->setCellValue("C".($jumlah_kolom), $total_route)
                    ->setCellValue("A".($jumlah_kolom+1), 'Total Planned Route')
                    ->setCellValue("B".($jumlah_kolom+1), ':')
                    ->setCellValue("C".($jumlah_kolom+1), $total_planning)
                    ->setCellValue("A".($jumlah_kolom+2), 'Total Missed Route')
                    ->setCellValue("B".($jumlah_kolom+2), ':')
                    ->setCellValue("C".($jumlah_kolom+2), $total_route_miss)
                    ->setCellValue("A".($jumlah_kolom+3), 'Total Unplanned Route')
                    ->setCellValue("B".($jumlah_kolom+3), ':')
                    ->setCellValue("C".($jumlah_kolom+3), $total_not_planning);

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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


    function modal_visit($id,$page = "",$date = "")
    {
        $detail             = "";
        $duration           = "";
        $CheckIn            = "";
        $CheckOut           = "";
        $CustomerImage      = "";
        $list_data                = array();
        $list_data_real           = array();
        $origin                   = array();
        $destination              = array();
        $list_data_ex             = array();
        $TransactionRouteIDArray  = array();
       

        if($page == "routing_sales"):
            $filter             = "TransactionRouteID";
            if($date):
                $filter          = "TransactionRouteIDArray";
                $IDA = $this->report->get_transaction_route_id($id,$date);
                foreach($IDA as $ida):
                    array_push($TransactionRouteIDArray, $ida->TransactionRouteID);
                endforeach;
                $id = $TransactionRouteIDArray;
            endif;
            $list_data      = $this->report->get_transaction_route_detail($filter,$id);
            foreach ($list_data as $a):
                $Name       = $a->Name;
                $Address    = $a->Address;
                $Lat        = $a->Lat;
                $Lng        = $a->Lng;
                
                if(empty($a->VendorID)):
                    $Name       = "unknown";
                    $Address    = $a->CheckInAddress;
                    $Lat        = explode(",", $a->CheckInLatlng)[0];
                    $Lng        = explode(",", $a->CheckInLatlng)[1];
                endif;
                $item = array(
                    "TransactionRouteDetailID"  => $a->TransactionRouteDetailID,
                    "VendorID"                  => $a->VendorID,
                    "CheckIn"                   => $a->CheckIn,
                    "Name"                      => $Name,
                    "Address"                   => $Address,
                    "Lat"                       => $Lat,
                    "Lng"                       => $Lng
                );
                array_push($list_data_real, $item);
            endforeach;

            $count_list         = count($list_data);
            $destinationnum     = $count_list - 1;
            if($count_list >= 3):
                $list_data_ex1       = $this->report->get_transaction_route_detail($filter,$id,array($list_data[0]->TransactionRouteDetailID,$list_data[$destinationnum]->TransactionRouteDetailID));
                foreach ($list_data_ex1 as $a) {
                    $Name       = $a->Name;
                    $Address    = $a->Address;
                    $Lat        = $a->Lat;
                    $Lng        = $a->Lng;
                    
                    if(empty($a->VendorID)):
                        $Name       = "unknown";
                        $Address    = $a->CheckInAddress;
                        $Lat        = explode(",", $a->CheckInLatlng)[0];
                        $Lng        = explode(",", $a->CheckInLatlng)[1];
                    endif;
                    $item = array(
                        "TransactionRouteDetailID"  => $a->TransactionRouteDetailID,
                        "VendorID"                  => $a->VendorID,
                        "CheckIn"                   => $a->CheckIn,
                        "Name"                      => $Name,
                        "Address"                   => $Address,
                        "Lat"                       => $Lat,
                        "Lng"                       => $Lng
                    );
                    array_push($list_data_ex, $item);
                }
            endif;
            if($count_list > 0):
                $origin         = $list_data_real[0];
            endif;
            if($count_list > 1):
                $destination    = $list_data_real[$destinationnum];
            endif;
        elseif($page == "sales_visit"):
            $b =  $this->report->get_transaction_detail($id);
            if($b->CheckIn && $b->CheckOut):
                $duration = $this->main->selisih_waktu(date("Y-m-d H:i",strtotime($b->CheckIn)),date("Y-m-d H:i",strtotime($b->CheckOut)));
            endif;

            if($b->CheckIn):
                $CheckIn = date("d F Y H:i",strtotime($b->CheckIn));
            endif;
            if($b->CheckOut):
                $CheckOut = date("d F Y H:i",strtotime($b->CheckOut));
            endif;
            if($b->CustomerImage):
                $CustomerImage = base_url($b->CustomerImage);
            else:
                $CustomerImage = base_url("img/noimage.png");
            endif;


            $detail = array(
                "ID"                => $b->ID,
                "Code"              => $b->Code,
                "Date"              => date("d F Y",strtotime($b->Date)),
                "SalesName"         => $b->SalesName,
                "CustomerID"        => $b->CustomerID,
                "CustomerName"      => $b->CustomerName,
                "CustomerLat"       => $b->CustomerLat,
                "CustomerLng"       => $b->CustomerLng,
                "CustomerImage"     => $CustomerImage,
                "Radius"            => $b->Radius,
                "CustomerAddress"   => $b->CustomerAddress,
                "CheckIn"           => $CheckIn,
                "CheckOut"          => $CheckOut,
                "Duration"          => $duration,
                "CheckInAddress"    => $b->CheckInAddress,
                "CheckOutAddress"   => $b->CheckOutAddress,
                "Remark"            => $b->Remark,
                "RemarkSales"       => $b->RemarkSales,
                "CheckInLatlng"     => $b->CheckInLatlng,
                "CheckOutLatlng"    => $b->CheckOutLatlng,

            );
        endif;

        $output = array(
            "A"                  => $id,
            "detail"             => $detail,
            "origin"             => $origin,
            "destination"        => $destination,
            "list_data_real"     => $list_data_real, 
            "list_data"          => $list_data_ex, 
            "hakakses"           => $this->session->hak_akses,
            "page"               => $page,
            "status"             => true,
            "message"            => "success"
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }

    //20180522 MW
    //save as customer
    public function get_transaction_detail($id){
        $list = $this->report->get_transaction_detail($id);
        $data = array(
            "address"   => $list->CheckInAddress,
            "lat"       => $this->main->getLat($list->CheckInLatlng),
            "lng"       => $this->main->getLng($list->CheckInLatlng),
            "companyID" => $list->CompanyID,
            );
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);   
    }

    public function save_customer(){
        $this->validate();
        $ID         = $this->input->post("ID");
        $CompanyID  = $this->input->post("companyID");
        $name       = $this->input->post("name2");
        $phone      = $this->input->post("phone2");
        $email      = $this->input->post("email2");
        $basecamp   = $this->input->post("basecamp2");
        $address    = $this->input->post("address2");
        $radius     = $this->input->post("radius2");
        $lat        = $this->input->post("lat2");
        $lng        = $this->input->post("lng2");
        
        if($this->input->post("type2") == "new_customer"):
            $vendorcode = $this->main->vendor_code_generate();
            if($basecamp == "yes"):
                $basecamp = 1;
            else:
                $basecamp = 0;
            endif;
            $data = array(
                    'code'          => $vendorcode,
                    'App'           => $this->session->app,
                    'CompanyID'     => $CompanyID,
                    'title'         => "MR",
                    'position'      => 2,
                    'name'          => $name,
                    'email'         => $email,
                    'phone'         => $phone,
                    'Address'       => $address,
                    'lat'           => $lat,
                    'lng'           => $lng,
                    'radius'        => $radius,
                    'active'        => 1,
                    'basecamp'      => $basecamp,
                    'User_Add'      => $this->session->userdata("NAMA"),
                    'Date_Add'       => date("Y-m-d H:i:s"),
                );

            $this->db->insert("PS_Vendor", $data);
            $insert =  $this->db->insert_id();
        else:
            $insert = $this->input->post("customer");
        endif;
        

        $this->db->where("TransactionRouteDetailID", $ID);
        $this->db->update("SP_TransactionRouteDetail", array("VendorID" => $insert));

        $res["status"] = TRUE;
        header('Content-Type: application/json');
        echo json_encode($res,JSON_PRETTY_PRINT);
    }

    private function validate(){
        $data                   = array();
        $data['error_string']   = array();
        $data['inputerror']     = array();
        $data['status']         = TRUE;
        if($this->input->post("type2") == "new_customer"){
            if($this->input->post('name2') == '')
            {
                $data['inputerror'][]   = 'name2';
                $data['error_string'][] = 'Partner name cannot be null ';
                $data['status']         = FALSE;
            }

            if($this->input->post('address2') == '')
            {
                $data['inputerror'][]   = 'address2';
                $data['error_string'][] = 'Address cannot be null';
                $data['status']         = FALSE;
            }
        }else{
            if($this->input->post('customer') == 'none')
            {
                $data['inputerror'][]   = 'customer';
                $data['error_string'][] = 'Select Customer';
                $data['status']         = FALSE;
            }
        }

        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }

    //2018-06-08 MW
    #report employee visiting hour
    public function sales_visiting_hour(){
        $this->main->countParentID();
        $StartDate  =  date('Y-m');
        $EndDate    =  date('Y-m');

        $this->main->cek_session();
        #ini untuk session halaman aturan user privileges;
        $data['title']      = 'Report Employee Visiting Hour';
        $data['page']       = 'report/salespro/sales_visiting_hour';
        $data['modul']      = 'sales_visiting_hour';
        $data["sales"]      = $this->main->branch("","",1);
        $data["StartDate"]  = $StartDate;
        $data["EndDate"]    = $EndDate;
        $this->load->view('index',$data);
    }

    public function get_sales_visiting_hour(){
        $this->_validate();
        $data = $this->main->dashboard("sales_visiting_hour");
        $data["status"] = TRUE;

        echo json_encode($data);
    }

    private function _validate(){
        $data                   = array();
        $data['error_string']   = array();
        $data['inputerror']     = array();
        $data['status']         = TRUE;
        
        if($this->input->post('company') == 'all')
        {
            $data['inputerror'][]   = 'company';
            $data['error_string'][] = 'Select Company';
            $data['status']         = FALSE;
        }

        if($this->input->post('Sales') == 'all')
        {
            $data['inputerror'][]   = 'Sales';
            $data['error_string'][] = 'Select Employee';
            $data['status']         = FALSE;
        }
        
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }   
    }

    public function get_jurnal(){
        $list = $this->report->example_get_jurnal();
        $this->main->echoJson($list);
    }

    #akunting
    public function cash_list(){
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;

        $table  = "AC_KasBank";
        $list   = $this->report->get_datatables($table);
        foreach ($list as $a) {
            $no++;
            $row    = array();
            $row[]  = $i++;
            $row[]  = $a->Date;
            $row[]  = $a->KasBankNo;
            $row[]  = $a->coaCode;
            $row[]  = $a->coaName;
            $row[]  = $this->main->currency($a->Debit);
            $row[]  = $this->main->currency($a->Credit);
            $row[]  = $a->Remark;
            $data[] = $row;
        }

        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => $this->report->count_all($table),
            "recordsFiltered" => $this->report->count_filtered($table),
            "data"            => $data,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);
    }

    public function cash_excell(){
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        
        $nama_laporan   = "Cash";
        $nama_laporan   = $this->input->get("name");
        $table          = "AC_KasBank";
        $list           = $this->report->get_datatables($table);   
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
        $objPHPExcel->getActiveSheet()->getStyle("A1:H".$jumlah_kolom)->applyFromArray($border_style);
        foreach(range('A','H') as $columnID):
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        endforeach;
        $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', $this->lang->line('lb_no'))
                    ->setCellValue('B1', $this->lang->line('lb_date'))
                    ->setCellValue('C1', $this->lang->line('lb_total_amount'))
                    ->setCellValue('D1', $this->lang->line('lb_coa_code'))
                    ->setCellValue('E1', $this->lang->line('lb_coa_name'))
                    ->setCellValue('F1', $this->lang->line('lb_total_debit'))
                    ->setCellValue('G1', $this->lang->line('lb_total_credit'))
                    ->setCellValue('H1', $this->lang->line('lb_remark'));
        foreach ($list as $a):
            $no     = $i++; 
            $urut   = $ii++; 
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$urut, $no) #no
                        ->setCellValue('B'.$urut, $a->Date)
                        ->setCellValue('C'.$urut, $a->KasBankNo)
                        ->setCellValue('D'.$urut, $a->coaCode)
                        ->setCellValue('E'.$urut, $a->coaName)
                        ->setCellValue('F'.$urut, $this->main->currency($a->Debit))
                        ->setCellValue('G'.$urut, $this->main->currency($a->Credit))
                        ->setCellValue('H'.$urut, $a->Remark);
        endforeach;
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

    public function bank_list(){
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;

        $table  = "AC_KasBank";
        $list   = $this->report->get_datatables($table);
        foreach ($list as $a) {
            $no++;
            $row    = array();
            $row[]  = $i++;
            $row[]  = $a->Date;
            $row[]  = $a->KasBankNo;
            $row[]  = $a->coaCode;
            $row[]  = $a->coaName;
            $row[]  = $this->main->currency($a->Debit);
            $row[]  = $this->main->currency($a->Credit);
            $row[]  = $a->Remark;
            $data[] = $row;
        }

        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => $this->report->count_all($table),
            "recordsFiltered" => $this->report->count_filtered($table),
            "data"            => $data,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);
    }

    public function bank_excell(){
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        
        $nama_laporan   = "Cash";
        $nama_laporan   = $this->input->get("name");
        $table          = "AC_KasBank";
        $list           = $this->report->get_datatables($table);   
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
        $objPHPExcel->getActiveSheet()->getStyle("A1:H".$jumlah_kolom)->applyFromArray($border_style);
        foreach(range('A','H') as $columnID):
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        endforeach;
        $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', $this->lang->line('lb_no'))
                    ->setCellValue('B1', $this->lang->line('lb_date'))
                    ->setCellValue('C1', $this->lang->line('lb_total_amount'))
                    ->setCellValue('D1', $this->lang->line('lb_coa_code'))
                    ->setCellValue('E1', $this->lang->line('lb_coa_name'))
                    ->setCellValue('F1', $this->lang->line('lb_total_debit'))
                    ->setCellValue('G1', $this->lang->line('lb_total_credit'))
                    ->setCellValue('H1', $this->lang->line('lb_remark'));
        foreach ($list as $a):
            $no     = $i++; 
            $urut   = $ii++; 
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$urut, $no) #no
                        ->setCellValue('B'.$urut, $a->Date)
                        ->setCellValue('C'.$urut, $a->KasBankNo)
                        ->setCellValue('D'.$urut, $a->coaCode)
                        ->setCellValue('E'.$urut, $a->coaName)
                        ->setCellValue('F'.$urut, $this->main->currency($a->Debit))
                        ->setCellValue('G'.$urut, $this->main->currency($a->Credit))
                        ->setCellValue('H'.$urut, $a->Remark);
        endforeach;
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

    public function jurnal_list(){
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        $data   = array();
        $no     = $this->input->post("start");
        $i      = 1;
        
        $list = $this->report->select_jurnal();
        $totalDebit     = 0;
        $totalCredit    = 0;
        foreach ($list as $a) {
            $no++;
            $row    = array();
            $row[]  = $i++;
            $row[]  = $a->Date;
            $row[]  = $a->KasBankNo;
            $row[]  = $this->main->kasbank_type($a->type);
            $row[]  = $a->Remarks;
            $row[]  = $a->Code;
            $row[]  = $a->Name;
            $row[]  = $this->main->currency($a->Debit);
            $row[]  = $this->main->currency($a->Credit);
            $data[] = $row;

            $totalDebit     += $a->Debit;
            $totalCredit    += $a->Credit;
        }

        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => count($list),
            "recordsFiltered" => count($list),
            "data"            => $data,
            "totalDebit"      => $this->main->currency($totalDebit),
            "totalCredit"     => $this->main->currency($totalCredit),
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);
    }

    public function jurnal_excell(){
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        
        $nama_laporan   = "Jurnal";
        $nama_laporan   = $this->input->get("name");
        $list           = $this->report->select_jurnal();
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
        $urut   = 1;
        $totalDebit  = 0;
        $totalCredit = 0;
        $jumlah_kolom = count($list)+2;
        
        $objPHPExcel->getActiveSheet()->getStyle("A1:I".$jumlah_kolom)->applyFromArray($border_style);
        foreach(range('A','I') as $columnID):
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        endforeach;
        $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', $this->lang->line('lb_no'))
                    ->setCellValue('B1', $this->lang->line('lb_date'))
                    ->setCellValue('C1', $this->lang->line('lb_transaction_no'))
                    ->setCellValue('D1', $this->lang->line('lb_transaction_name'))
                    ->setCellValue('E1', $this->lang->line('lb_remark'))
                    ->setCellValue('F1', $this->lang->line('lb_bank_acountno'))
                    ->setCellValue('G1', $this->lang->line('lb_bank_acount'))
                    ->setCellValue('H1', $this->lang->line('lb_debit'))
                    ->setCellValue('I1', $this->lang->line('lb_credit'));
        foreach ($list as $a):
            $no     = $i++; 
            $urut   = $ii++; 
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$urut, $no) #no
                        ->setCellValue('B'.$urut, $a->Date)
                        ->setCellValue('C'.$urut, $a->KasBankNo)
                        ->setCellValue('D'.$urut, $this->main->kasbank_type($a->type))
                        ->setCellValue('E'.$urut, $a->Remarks)
                        ->setCellValue('F'.$urut, $a->Code)
                        ->setCellValue('G'.$urut, $a->Name)
                        ->setCellValue('H'.$urut, $this->main->currency($a->Debit))
                        ->setCellValue('I'.$urut, $this->main->currency($a->Credit));

            $totalDebit += $a->Debit;
            $totalCredit += $a->Credit;
        endforeach;

        $urut += 1;

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':G'.$urut)
            ->setCellValue('H'.$urut, $this->main->currency($totalDebit))
            ->setCellValue('I'.$urut, $this->main->currency($totalCredit));

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

    public function balance_sheet_excell(){
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        
        $nama_laporan   = "Balance Sheet";
        $nama_laporan   = $this->input->get("name");
        $list           = $this->report->select_balance_sheet();
        $objPHPExcel    = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("PIPESYS APPLICATION MANAGEMENT")
                    ->setLastModifiedBy("PIPESYS APPLICATION MANAGEMENT")
                    ->setTitle("Office 2003 XLS Test Document")
                    ->setSubject("Office 2003 XLS Test Document")
                    ->setDescription("Dokumen ini dari aplikasi PIPESYS")
                    ->setKeywords("office 2003 openxml php")
                    ->setCategory("Template Excel Driver");
        $border_style = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
        foreach(range('A','Z') as $columnID):
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        endforeach;
        $i      = 1;
        $ii     = 2;
        $urut   = 0;
        $totalDebit  = 0;
        $totalCredit = 0;
        $jumlah_kolom = count($list)+2;

        $list_induk = array();
        foreach ($list  as $a):
            if(!in_array($a->ID, $list_induk, true)):
                array_push($list_induk, $a->ID);
            endif;
        endforeach;
        foreach($list_induk as $key => $a):
            $IndukName      = "";
            $tr             = "";
            $list_induk2    = array();
            $list_induk3    = array();

            $grand_total_debit  = 0;
            foreach($list as $b):
                if($a == $b->ID):
                    $IndukName = $b->COA2;
                    if(!in_array($b->COA, $list_induk2, true)):
                        array_push($list_induk2, $b->COA);
                    endif;
                endif;
            endforeach;

            // foreach ($list_induk2 as $b) {
            //     foreach ($list as $c) {
            //         if($c->COA == $b):
            //             if(!in_array($c->I, $list_induk3, true)):
            //                 array_push($list_induk3, $c->I);
            //             endif;
            //         endif;
            //     }
            // }

            $urut   = $ii++;
            if($key != 0):
                $urut   = $ii++;
            endif;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$urut, $IndukName)->mergeCells('A'.$urut.':B'.$urut);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$urut.':B'.$urut)->getFont()->setBold(true);
            
            foreach ($list_induk2 as $b):
                $list_induk3    = array();
                foreach ($list as $c) {
                    if($c->COA == $b):
                        if(!in_array($c->I, $list_induk3, true)):
                            array_push($list_induk3, $c->I);
                        endif;
                    endif;
                }
                
                $IndukName2     = $b;
                $total_debit    = 0;
                
                $urut   = $ii++;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$urut, $IndukName2)->mergeCells('A'.$urut.':B'.$urut);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$urut.':B'.$urut)->getFont()->setBold(true);

                foreach($list_induk3 as $d):
                    $total_level3 = 0;

                    $urut   = $ii++;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$urut, $d)->mergeCells('A'.$urut.':B'.$urut);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$urut.':B'.$urut)->getFont()->setBold(true);
                    
                    foreach ($list as $e) {
                        if($e->I == $d):
                            $cost = $e->Debit - $e->Credit;
                            $total_level3 += $cost;
                            $urut   = $ii++;

                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$urut, $e->II )->setCellValue('B'.$urut, $this->main->currency2($cost));
                        endif;
                    }

                    $urut   = $ii++;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$urut, 'Total '.$d)->setCellValue('B'.$urut, $this->main->currency2($total_level3));
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$urut.':B'.$urut)->getFont()->setBold(true);

                    $total_debit += $total_level3;
                endforeach;

                $urut   = $ii++;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$urut, 'Total '.$IndukName2)->setCellValue('B'.$urut, $this->main->currency2($total_debit));
                $objPHPExcel->getActiveSheet()->getStyle('A'.$urut.':B'.$urut)->getFont()->setBold(true);

                $grand_total_debit += $total_debit;
            endforeach;

            $urut   = $ii++;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$urut, 'Total '.$IndukName)->setCellValue('B'.$urut, $this->main->currency2($grand_total_debit));
            $objPHPExcel->getActiveSheet()->getStyle('A'.$urut.':B'.$urut)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("A1:B".$urut)->applyFromArray($border_style);
        endforeach;

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

    public function loss_and_profit_excell(){
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        
        $nama_laporan   = "Loss and Profit";
        $nama_laporan   = $this->input->get("name");
        $list           = $this->report->select_loss_and_profit();
        $objPHPExcel    = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("PIPESYS APPLICATION MANAGEMENT")
                    ->setLastModifiedBy("PIPESYS APPLICATION MANAGEMENT")
                    ->setTitle("Office 2003 XLS Test Document")
                    ->setSubject("Office 2003 XLS Test Document")
                    ->setDescription("Dokumen ini dari aplikasi PIPESYS")
                    ->setKeywords("office 2003 openxml php")
                    ->setCategory("Template Excel Driver");
        $border_style = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
        foreach(range('A','Z') as $columnID):
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        endforeach;
        $i      = 1;
        $ii     = 2;
        $urut   = 0;
        $totalDebit  = 0;
        $totalCredit = 0;
        $jumlah_kolom = count($list)+2;

        $list_induk = array();
        foreach ($list  as $a):
            if(!in_array($a->GreatGreatGrandParent, $list_induk, true)):
                array_push($list_induk, $a->GreatGreatGrandParent);
            endif;
        endforeach;

        foreach($list_induk as $key => $a):
            $IndukName      = $a;
            $Remark         = "";
            $tr             = "";
            $list_induk2    = array();
            $list_induk3    = array();

            $grand_total_debit  = 0;
            foreach($list as $b):
                if($a == $b->GreatGreatGrandParent):
                    $Remark = $b->Keterangan;
                    if(!in_array($b->GreatGrandParent, $list_induk2, true)):
                        array_push($list_induk2, $b->GreatGrandParent);
                    endif;
                endif;
            endforeach;

            // foreach ($list_induk2 as $b) {
            //     foreach ($list as $c) {
            //         if($c->GreatGrandParent == $b):
            //             if(!in_array($c->GrandParent, $list_induk3, true)):
            //                 array_push($list_induk3, $c->GrandParent);
            //             endif;
            //         endif;
            //     }
            // }

            $urut   = $ii++;
            if($key != 0):
                $urut   = $ii++;
            endif;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$urut, $Remark.". ".$IndukName)->mergeCells('A'.$urut.':B'.$urut);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$urut.':B'.$urut)->getFont()->setBold(true);
            
            foreach ($list_induk2 as $b):
                $list_induk3    = array();
                foreach ($list as $c) {
                    if($c->GreatGrandParent == $b):
                        if(!in_array($c->GrandParent, $list_induk3, true)):
                            array_push($list_induk3, $c->GrandParent);
                        endif;
                    endif;
                }

                $IndukName2     = $b;
                $total_debit    = 0;
                
                $urut   = $ii++;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$urut, $IndukName2)->mergeCells('A'.$urut.':B'.$urut);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$urut.':B'.$urut)->getFont()->setBold(true);

                foreach($list_induk3 as $d):
                    $total_level3 = 0;

                    $urut   = $ii++;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$urut, $d)->mergeCells('A'.$urut.':B'.$urut);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$urut.':B'.$urut)->getFont()->setBold(true);
                    
                    foreach ($list as $e) {
                        if($e->GrandParent == $d):
                            $cost = $e->Total;
                            $total_level3 += $cost;
                            $urut   = $ii++;

                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$urut, $e->ParentCode." ".$e->ParentName )->setCellValue('B'.$urut, $this->main->currency2($cost));
                        endif;
                    }

                    $urut   = $ii++;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$urut, 'Total '.$d)->setCellValue('B'.$urut, $this->main->currency2($total_level3));
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$urut.':B'.$urut)->getFont()->setBold(true);

                    $total_debit += $total_level3;
                endforeach;

                $urut   = $ii++;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$urut, 'Total '.$IndukName2)->setCellValue('B'.$urut, $this->main->currency2($total_debit));
                $objPHPExcel->getActiveSheet()->getStyle('A'.$urut.':B'.$urut)->getFont()->setBold(true);

                $grand_total_debit += $total_debit;
            endforeach;

            $urut   = $ii++;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$urut, 'Total '.$IndukName)->setCellValue('B'.$urut, $this->main->currency2($grand_total_debit));
            $objPHPExcel->getActiveSheet()->getStyle('A'.$urut.':B'.$urut)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("A1:B".$urut)->applyFromArray($border_style);
        endforeach;

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

    public function ledger_excell(){
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        
        $nama_laporan   = "Ledger";
        $nama_laporan   = $this->input->get("name");
        $list           = $this->report->select_ledger();
        $objPHPExcel    = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("PIPESYS APPLICATION MANAGEMENT")
                    ->setLastModifiedBy("PIPESYS APPLICATION MANAGEMENT")
                    ->setTitle("Office 2003 XLS Test Document")
                    ->setSubject("Office 2003 XLS Test Document")
                    ->setDescription("Dokumen ini dari aplikasi PIPESYS")
                    ->setKeywords("office 2003 openxml php")
                    ->setCategory("Template Excel Driver");
        $border_style = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
        foreach(range('A','Z') as $columnID):
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        endforeach;
        $i      = 1;
        $ii     = 2;
        $iii    = 0;
        $urut   = 0;
        $totalDebit  = 0;
        $totalCredit = 0;
        $jumlah_kolom = count($list)+2;

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', $this->lang->line('lb_date'))
                    ->setCellValue('B1', $this->lang->line('lb_bank_acount'))
                    ->setCellValue('C1', $this->lang->line('lb_bank_acountno'))
                    ->setCellValue('D1', $this->lang->line('lb_remark'))
                    ->setCellValue('E1', $this->lang->line('lb_transaction'))
                    ->setCellValue('F1', $this->lang->line('lb_debit'))
                    ->setCellValue('G1', $this->lang->line('lb_credit'))
                    ->setCellValue('H1', $this->lang->line('lb_saldo'));
        if(!empty($list)):
            $list_induk = array();
            foreach ($list  as $a):
                if(!in_array($a->ParentCode, $list_induk, true)):
                   array_push($list_induk, $a->ParentCode);
                endif;
            endforeach;
            $grand_total_debit  = 0;
            $grand_total_credit = 0;
            $grand_total_saldo  = 0;
            $urut               = 1;
            foreach($list_induk as $a):
                $urut           += 1;
                $induk_name     = "";
                $induk_code     = "";
                $total_debit    = 0;
                $total_credit   = 0;
                $total_saldo    = 0;

                foreach($list as $b):
                    if($a == $b->ParentCode):
                        $induk_name     = $b->parentName;
                        $induk_code     = $b->ParentCode;
                    endif;
                endforeach;

                $total_saldo        = $total_debit - $total_credit;

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$urut, '('.$induk_code.') - '.$induk_name)->mergeCells('A'.$urut.':H'.$urut);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$urut.':I'.$urut)->getFont()->setBold(true);

                $urutx  = 0;
                foreach($list as $b):
                    if($a == $b->ParentCode):
                        $urut           += 1;
                        $total_debit    += $b->Debit;
                        $total_credit   += $b->Kredit;
                        $total_saldo    = $b->Saldo;
                        $Transaksi      = explode(".", $b->Transaksi);
                        if(count($Transaksi)>1):
                            $Transaksi = $Transaksi[1];
                        else:
                            $Transaksi = $Transaksi[0];
                        endif;
                        $objPHPExcel->setActiveSheetIndex(0)
                              ->setCellValue('A'.$urut, date("d/m/Y",strtotime($b->Tanggal)))
                              ->setCellValue('B'.$urut, $b->AcctName)
                              ->setCellValue('C'.$urut, $b->AcctCode)
                              ->setCellValue('D'.$urut, $b->keterangan)
                              ->setCellValue('E'.$urut, $Transaksi)
                              ->setCellValue('F'.$urut, $this->main->currency($b->Debit,"excell"))
                              ->setCellValue('G'.$urut, $this->main->currency($b->Kredit,"excell"))
                              ->setCellValue('H'.$urut, $this->main->currency($b->Saldo,"excell"));
                    endif;
                endforeach;
                $total_saldo        = $total_debit - $total_credit;
                $grand_total_debit += $total_debit;
                $grand_total_credit += $total_credit;

                $urut += 1;
                $objPHPExcel->setActiveSheetIndex(0)
                              ->setCellValue('A'.$urut, '('.$induk_name.')')->mergeCells('A'.$urut.':E'.$urut)
                              ->setCellValue('F'.$urut, $this->main->currency($total_debit,"excell"))
                              ->setCellValue('G'.$urut, $this->main->currency($total_credit,"excell"))
                              ->setCellValue('H'.$urut, $this->main->currency($total_saldo,"excell"));
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$urut)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
                $objPHPExcel->getActiveSheet()->getStyle('A'.$urut.':H'.$urut)->getFont()->setBold(true);
            endforeach;

            $grand_total_saldo = $grand_total_debit - $grand_total_credit;
            $urut += 1;

            $objPHPExcel->setActiveSheetIndex(0)
                              ->setCellValue('A'.$urut, $this->lang->line('lb_grand_total'))->mergeCells('A'.$urut.':E'.$urut)
                              ->setCellValue('F'.$urut, $this->main->currency($grand_total_debit,"excell"))
                              ->setCellValue('G'.$urut, $this->main->currency($grand_total_credit,"excell"))
                              ->setCellValue('H'.$urut, $this->main->currency($grand_total_saldo,"excell"));
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$urut)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            $objPHPExcel->getActiveSheet()->getStyle('A'.$urut.':I'.$urut)->getFont()->setBold(true);
        endif;

        $col_footer             = $urut + 3; 
        #ini tanda untuk tanggal export
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$col_footer, $footer)->mergeCells('A'.$col_footer.':H'.$col_footer);

        $jumlah_kolom = $urut;
        $objPHPExcel->getActiveSheet()->getStyle("A1:H".$jumlah_kolom)->applyFromArray($border_style);
        $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);

        #$objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        ob_end_clean();
        $objWriter->save('php://output');
        exit;
    }
    #end akunting

    public function stock1_excell(){
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        $ProductID  = $this->input->post("product");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;

        $nama_laporan   = "Stock Report";
        $nama_laporan   = $this->input->get("name");
        $list           = $this->report->stock_report1(); 
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
        $urut   = 0;
        $jumlah_kolom = count($list)+1;
        
        if($group == "transaction" && $ProductID != "all" && $ProductID):
            $BranchID = array();
            foreach ($list as $a):
                if(!in_array($a->BranchID,$BranchID)): array_push($BranchID,$a->BranchID); endif;
            endforeach;

            foreach ($BranchID as $v) {
                $tr         = '';
                $td         = '';
                $no         = 0;
                $awal       = 0;
                $totalIn    = 0;
                $totalOut   = 0;
                $totalLast  = 0;
                $totalAwal  = 0;
                $branchName = '';

                $urut_store     = $urut += 1;
                $urut_header    = $urut += 1;

                foreach(range('A','J') as $columnID):
                    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
                endforeach;

                foreach ($list as $a) {
                    if($v == $a->BranchID):
                        $td = 1;
                        $branchName = $a->branchName;
                        if($no == 0):
                            $awal       = $a->awal2;
                            $totalAwal  = $awal;
                        endif;

                        $akhir      = $awal + $a->masuk - $a->keluar;
                        $tanggal    = '';
                        if($a->tanggal):
                            $tanggal = date("Y-m-d",strtotime($a->tanggal));
                        endif;

                        $urut += 1;
                        $no += 1;
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no)
                            ->setCellValue('B'.$urut, $a->no_bukti)
                            ->setCellValue('C'.$urut, $this->main->label_report_stock($a->jenis))
                            ->setCellValue('D'.$urut, $tanggal)
                            ->setCellValue('E'.$urut, $a->Nama_Produk)
                            ->setCellValue('F'.$urut, $a->Kode)
                            ->setCellValue('G'.$urut, $this->main->qty($awal))
                            ->setCellValue('H'.$urut, $this->main->qty($a->masuk))
                            ->setCellValue('I'.$urut, $this->main->qty($a->keluar))
                            ->setCellValue('J'.$urut, $this->main->qty($akhir));

                        $awal       = $akhir;
                        $totalIn    += $a->masuk;
                        $totalOut   += $a->keluar;
                        $totalLast  = $akhir;
                    endif;
                }

                if($td):
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$urut_store.':J'.$urut_store)->getFont()->setBold(true);
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$urut_store, 'Store Name : '.$branchName)->mergeCells('A'.$urut_store.':J'.$urut_store);

                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$urut_header, $this->lang->line('lb_no'))
                        ->setCellValue('B'.$urut_header, $this->lang->line('lb_transaction_no'))
                        ->setCellValue('C'.$urut_header, $this->lang->line('lb_transaction'))
                        ->setCellValue('D'.$urut_header, $this->lang->line('lb_date'))
                        ->setCellValue('E'.$urut_header, $this->lang->line('lb_product_code'))
                        ->setCellValue('F'.$urut_header, $this->lang->line('lb_product_name'))
                        ->setCellValue('G'.$urut_header, $this->lang->line('lb_initial'))
                        ->setCellValue('H'.$urut_header, $this->lang->line('lb_in'))
                        ->setCellValue('I'.$urut_header, $this->lang->line('lb_out'))
                        ->setCellValue('J'.$urut_header, $this->lang->line('lb_last'));

                    $urut += 1;
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$urut.':J'.$urut)->getFont()->setBold(true);
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':F'.$urut)
                        ->setCellValue('G'.$urut, $this->main->qty($totalAwal))
                        ->setCellValue('H'.$urut, $this->main->qty($totalIn))
                        ->setCellValue('I'.$urut, $this->main->qty($totalOut))
                        ->setCellValue('J'.$urut, $this->main->qty($totalLast));
                endif;
            }
            $objPHPExcel->getActiveSheet()->getStyle("A1:J".$urut)->applyFromArray($border_style);
        elseif($group == "transaction"):
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', $this->lang->line('lb_data_not_found'));
        elseif($group == "all"):
            $td = '';
            $no = 0;
            $totalIn    = 0;
            $totalOut   = 0;
            $totalLast  = 0;
            $totalAwal  = 0;
            $urut_header = $urut += 1;
            foreach(range('A','G') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;
            foreach ($list as $a) {
                $urut   += 1;
                $no     += 1;

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$urut, $no)
                    ->setCellValue('B'.$urut, $a->Kode)
                    ->setCellValue('C'.$urut, $a->Nama_Produk)
                    ->setCellValue('D'.$urut, $this->main->qty($a->awal))
                    ->setCellValue('E'.$urut, $this->main->qty($a->masuk))
                    ->setCellValue('F'.$urut, $this->main->qty($a->keluar))
                    ->setCellValue('G'.$urut, $this->main->qty($a->akhir));

                $totalAwal  += $a->awal;
                $totalIn    += $a->masuk;
                $totalOut   += $a->keluar;
                $totalLast  += $a->akhir;
            }

            $objPHPExcel->getActiveSheet()->getStyle('A'.$urut_header.':G'.$urut_header)->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$urut_header, $this->lang->line('lb_no'))
                ->setCellValue('B'.$urut_header, $this->lang->line('lb_product_code'))
                ->setCellValue('C'.$urut_header, $this->lang->line('lb_product_name'))
                ->setCellValue('D'.$urut_header, $this->lang->line('lb_initial'))
                ->setCellValue('E'.$urut_header, $this->lang->line('lb_in'))
                ->setCellValue('F'.$urut_header, $this->lang->line('lb_out'))
                ->setCellValue('G'.$urut_header, $this->lang->line('lb_last'));

            $urut += 1;
            $objPHPExcel->getActiveSheet()->getStyle('A'.$urut.':G'.$urut)->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':C'.$urut)
                ->setCellValue('D'.$urut, $this->main->qty($totalAwal))
                ->setCellValue('E'.$urut, $this->main->qty($totalIn))
                ->setCellValue('F'.$urut, $this->main->qty($totalOut))
                ->setCellValue('G'.$urut, $this->main->qty($totalLast));


            $objPHPExcel->getActiveSheet()->getStyle("A1:G".$urut)->applyFromArray($border_style);
        elseif($group == "store"):
            $BranchID = array();
            foreach ($list as $a):
                if(!in_array($a->BranchID,$BranchID)): array_push($BranchID,$a->BranchID); endif;
            endforeach;

            foreach ($BranchID as $v) {
                $tr         = '';
                $td         = '';
                $no         = 0;
                $awal       = 0;
                $totalIn    = 0;
                $totalOut   = 0;
                $totalLast  = 0;
                $totalAwal  = 0;
                $branchName = '';
                $urut_store     = $urut += 1;
                $urut_header    = $urut += 1;

                foreach(range('A','G') as $columnID):
                    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
                endforeach;
                foreach ($list as $a) {
                    if($v == $a->BranchID):
                        $branchName = $a->branchName;

                        $urut += 1;
                        $no += 1;
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no)
                            ->setCellValue('B'.$urut, $a->Nama_Produk)
                            ->setCellValue('C'.$urut, $a->Kode)
                            ->setCellValue('D'.$urut, $this->main->qty($a->awal))
                            ->setCellValue('E'.$urut, $this->main->qty($a->masuk))
                            ->setCellValue('F'.$urut, $this->main->qty($a->keluar))
                            ->setCellValue('G'.$urut, $this->main->qty($a->akhir));

                        $totalAwal  += $a->awal;
                        $totalIn    += $a->masuk;
                        $totalOut   += $a->keluar;
                        $totalLast  += $a->akhir;
                    endif;
                }

                $objPHPExcel->getActiveSheet()->getStyle('A'.$urut_store.':J'.$urut_store)->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$urut_store, 'Store Name : '.$branchName)->mergeCells('A'.$urut_store.':G'.$urut_store);

                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$urut_header, $this->lang->line('lb_no'))
                        ->setCellValue('B'.$urut_header, $this->lang->line('lb_product_code'))
                        ->setCellValue('C'.$urut_header, $this->lang->line('lb_product_name'))
                        ->setCellValue('D'.$urut_header, $this->lang->line('lb_initial'))
                        ->setCellValue('E'.$urut_header, $this->lang->line('lb_in'))
                        ->setCellValue('F'.$urut_header, $this->lang->line('lb_out'))
                        ->setCellValue('G'.$urut_header, $this->lang->line('lb_last'));

                $urut += 1;
                $objPHPExcel->getActiveSheet()->getStyle('A'.$urut.':G'.$urut)->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':C'.$urut)
                    ->setCellValue('D'.$urut, $this->main->qty($totalAwal))
                    ->setCellValue('E'.$urut, $this->main->qty($totalIn))
                    ->setCellValue('F'.$urut, $this->main->qty($totalOut))
                    ->setCellValue('G'.$urut, $this->main->qty($totalLast));

            }
            $objPHPExcel->getActiveSheet()->getStyle("A1:G".$urut)->applyFromArray($border_style);
        endif;

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

    public function stock_opname_excell(){
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        $nama_laporan   = $this->lang->line('lb_stock_opname');
        $nama_laporan   = $this->input->get("name");
        $list   = $this->report->select_stock_opname();

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
        $urut   = 1;
        $jumlah_kolom = count($list)+1;

        $totalPriceBefore   = 0;
        $totalPrice         = 0;
        $totalCorrectionQty = 0;
        $totalQty           = 0;
        $totalCorrectionStock = 0;
        if($group == "all"):
            $objPHPExcel->getActiveSheet()->getStyle("A1:M".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','M') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;

            $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_transaction_no'))
                        ->setCellValue('C1', $this->lang->line('lb_date'))
                        ->setCellValue('D1', $this->lang->line('lb_store'))
                        ->setCellValue('E1', $this->lang->line('lb_product_name'))
                        ->setCellValue('F1', $this->lang->line('lb_product_code'))
                        ->setCellValue('G1', $this->lang->line('lb_average_program'))
                        ->setCellValue('H1', $this->lang->line('lb_average_price'))
                        ->setCellValue('I1', $this->lang->line('lb_stock_opname_qty'))
                        ->setCellValue('J1', $this->lang->line('lb_stock_qty'))
                        ->setCellValue('K1', $this->lang->line('lb_correction_qty'))
                        ->setCellValue('L1', $this->lang->line('lb_unit'))
                        ->setCellValue('M1', $this->lang->line('lb_remark'));

            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->CorrectionNo)
                            ->setCellValue('C'.$urut, $a->Date)
                            ->setCellValue('D'.$urut, $a->branchName)
                            ->setCellValue('E'.$urut, $a->product_code)
                            ->setCellValue('F'.$urut, $a->product_name)
                            ->setCellValue('G'.$urut, $this->main->currency($a->PriceBefore))
                            ->setCellValue('H'.$urut, $this->main->currency($a->Price))
                            ->setCellValue('I'.$urut, $this->main->qty($a->CorrectionQty))
                            ->setCellValue('J'.$urut, $this->main->qty($a->Qty))
                            ->setCellValue('K'.$urut, $this->main->qty($a->correction_stock))
                            ->setCellValue('L'.$urut, $a->unit_name)
                            ->setCellValue('M'.$urut, $a->Remark);

            endforeach;
        elseif($group == "transaction"):
            $objPHPExcel->getActiveSheet()->getStyle("A1:G".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','G') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;

            $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_transaction_no'))
                        ->setCellValue('C1', $this->lang->line('lb_date'))
                        ->setCellValue('D1', $this->lang->line('lb_store'))
                        ->setCellValue('E1', $this->lang->line('lb_stock_opname_qty_total'))
                        ->setCellValue('F1', $this->lang->line('lb_stock_qty_total'))
                        ->setCellValue('G1', $this->lang->line('lb_correction_qty_total'));

            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->CorrectionNo)
                            ->setCellValue('C'.$urut, $a->Date)
                            ->setCellValue('D'.$urut, $a->branchName)
                            ->setCellValue('E'.$urut, $this->main->qty($a->CorrectionQty))
                            ->setCellValue('F'.$urut, $this->main->qty($a->Qty))
                            ->setCellValue('G'.$urut, $this->main->qty($a->correction_stock));

            endforeach;
        elseif($group == "store"):
            $objPHPExcel->getActiveSheet()->getStyle("A1:F".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','F') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;

            $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_store'))
                        ->setCellValue('C1', $this->lang->line('lb_total_transaction'))
                        ->setCellValue('D1', $this->lang->line('lb_stock_opname_qty_total'))
                        ->setCellValue('E1', $this->lang->line('lb_stock_qty_total'))
                        ->setCellValue('F1', $this->lang->line('lb_correction_qty_total'));
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->branchName)
                            ->setCellValue('C'.$urut, $a->totalTransaction)
                            ->setCellValue('D'.$urut, $this->main->qty($a->CorrectionQty))
                            ->setCellValue('E'.$urut, $this->main->qty($a->Qty))
                            ->setCellValue('F'.$urut, $this->main->qty($a->correction_stock));

            endforeach;
        endif;

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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

    public function stock_receipt_excell(){
        $nama_laporan   = $this->lang->line('lb_stock_receipt');
        $nama_laporan   = $this->input->get("name");
        $this->stock_receipt_issue($nama_laporan);
    }

    public function stock_issue_excell(){
        $nama_laporan   = $this->lang->line('lb_stock_issue');
        $nama_laporan   = $this->input->get("name");
        $this->stock_receipt_issue($nama_laporan,"issue");
    }

    private function stock_receipt_issue($nama_laporan,$page=""){
        $report = $this->input->post("report");
        $group  = $this->input->post("group");
        if($this->input->get("group")):
            $group = $this->input->get("group");
        endif;
        $list   = $this->report->select_stock_receipt($page);

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
        $urut   = 1;
        $jumlah_kolom = count($list)+2;

        $totalQty   = 0;
        $totalPrice = 0;
        $totalSubTotal      = 0;
        $totalTransaction   = 0;

        if($group == "all"):
            $objPHPExcel->getActiveSheet()->getStyle("A1:K".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','K') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;

            $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_transaction_no'))
                        ->setCellValue('C1', $this->lang->line('lb_date'))
                        ->setCellValue('D1', $this->lang->line('lb_store'))
                        ->setCellValue('E1', $this->lang->line('lb_product_name'))
                        ->setCellValue('F1', $this->lang->line('lb_product_code'))
                        ->setCellValue('G1', $this->lang->line('lb_qty'))
                        ->setCellValue('H1', $this->lang->line('lb_unit'))
                        ->setCellValue('I1', $this->lang->line('lb_conversion'))
                        ->setCellValue('J1', $this->lang->line('price'))
                        ->setCellValue('K1', $this->lang->line('lb_sub_total'));

            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 

                $total_qty  = (float) $a->total_qty;
                $subtotal   = $total_qty * (float) $a->Price;

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->CorrectionNo)
                            ->setCellValue('C'.$urut, $a->Date)
                            ->setCellValue('D'.$urut, $a->branchName)
                            ->setCellValue('E'.$urut, $a->product_code)
                            ->setCellValue('F'.$urut, $a->product_name)
                            ->setCellValue('G'.$urut, $this->main->qty($a->Qty))
                            ->setCellValue('H'.$urut, $a->unit_name)
                            ->setCellValue('I'.$urut, (float) $a->Conversion)
                            ->setCellValue('J'.$urut, $this->main->currency($a->Price))
                            ->setCellValue('K'.$urut, $this->main->currency($subtotal));

                $totalQty       += $a->Qty;
                $totalPrice     += $a->Price;
                $totalSubTotal  += $subtotal;
            endforeach;
            $urut += 1;
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':F'.$urut)
                        ->setCellValue('G'.$urut, $this->main->qty($totalQty))
                        ->setCellValue('J'.$urut, $this->main->currency($totalPrice))
                        ->setCellValue('K'.$urut, $this->main->currency($totalSubTotal));
        elseif($group == "transaction"):
            $objPHPExcel->getActiveSheet()->getStyle("A1:F".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','F') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;

            $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_transaction_no'))
                        ->setCellValue('C1', $this->lang->line('lb_date'))
                        ->setCellValue('D1', $this->lang->line('lb_store'))
                        ->setCellValue('E1', $this->lang->line('lb_qty_total'))
                        ->setCellValue('F1', $this->lang->line('lb_sub_total'));

            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->CorrectionNo)
                            ->setCellValue('C'.$urut, $a->Date)
                            ->setCellValue('D'.$urut, $a->branchName)
                            ->setCellValue('E'.$urut, $this->main->qty($a->Qty))
                            ->setCellValue('F'.$urut, $this->main->currency($a->Price));

                $totalQty       += $a->Qty;
                $totalPrice     += $a->Price;
            endforeach;
            $urut += 1;
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':D'.$urut)
                        ->setCellValue('E'.$urut, $this->main->qty($totalQty))
                        ->setCellValue('F'.$urut, $this->main->currency($totalPrice));
        elseif($group == "store"):
            $objPHPExcel->getActiveSheet()->getStyle("A1:E".$jumlah_kolom)->applyFromArray($border_style);
            foreach(range('A','E') as $columnID):
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            endforeach;

            $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $this->lang->line('lb_no'))
                        ->setCellValue('B1', $this->lang->line('lb_store'))
                        ->setCellValue('C1', $this->lang->line('lb_total_transaction'))
                        ->setCellValue('D1', $this->lang->line('lb_qty_total'))
                        ->setCellValue('E1', $this->lang->line('lb_sub_total'));
            foreach ($list as $a):
                $no     = $i++; 
                $urut   = $ii++; 

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$urut, $no) #no
                            ->setCellValue('B'.$urut, $a->branchName)
                            ->setCellValue('C'.$urut, $a->totalTransaction)
                            ->setCellValue('D'.$urut, $this->main->qty($a->Qty))
                            ->setCellValue('E'.$urut, $this->main->currency($a->Price));

                $totalQty       += $a->Qty;
                $totalPrice     += $a->Price;
                $totalTransaction += $a->totalTransaction;
            endforeach;
            $urut += 1;
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$urut, $this->lang->line('lb_total'))->mergeCells('A'.$urut.':B'.$urut)
                        ->setCellValue('C'.$urut, $totalTransaction)
                        ->setCellValue('D'.$urut, $this->main->qty($totalQty))
                        ->setCellValue('E'.$urut, $this->main->currency($totalPrice));
        endif;

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nama_laporan);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nama_laporan."_".date("Ymd_His").'.xls"');
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
}