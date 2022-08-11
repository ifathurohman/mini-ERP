<table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
<?php
	$ProductID = $this->input->post('product');
	$group;
    if($group == "transaction" && $ProductID != "all" && $ProductID):
        $BranchID = array();
        foreach ($list as $a):
            if(!in_array($a->BranchID,$BranchID)): array_push($BranchID,$a->BranchID); endif;
        endforeach;

        foreach ($BranchID as $v) {
            $tr 		= '';
            $td 		= '';
            $no 		= 0;
            $awal 		= 0;
            $totalIn  	= 0;
			$totalOut 	= 0;
			$totalLast 	= 0;
			$totalAwal 	= 0;
            $branchName = '';
            foreach ($list as $a) {
                if($v == $a->BranchID):
                    $branchName = $a->branchName;
                    if($no == 0):
                        $awal 		= $a->awal2;
                        $totalAwal 	= $awal;
                    endif;

                    $akhir 		= $awal + $a->masuk - $a->keluar;
                    $tanggal 	= '';
                    if($a->tanggal):
                    	$tanggal = date("Y-m-d",strtotime($a->tanggal));
                    endif;

                    $no += 1;
                    $td .= '<tr>';
                    $td .= "<td>".$no."</td>";
                    $td .= "<td>".$a->no_bukti."</td>";
                    $td .= "<td>".$this->main->label_report_stock($a->jenis)."</td>";
                    $td .= "<td>".$tanggal."</td>";
                    $td .= "<td>".$a->Nama_Produk."</td>";
                    $td .= "<td>".$a->Kode."</td>";
                    $td .= "<td>".$this->main->qty($awal)."</td>";
                    $td .= "<td>".$this->main->qty($a->masuk)."</td>";
                    $td .= "<td>".$this->main->qty($a->keluar)."</td>";
                    $td .= "<td>".$this->main->qty($akhir)."</td>";
                    $td .= "</tr>";

                    $awal 		= $akhir;
                    $totalIn 	+= $a->masuk;
                    $totalOut 	+= $a->keluar;
                    $totalLast 	= $akhir;
                endif;
            }

            if($td):
                $tr .= '<tr>';
                $tr .= '<td colspan="10" style="font-weight:bold;background: #ededed;">Store Name : '.$branchName.'</td>';
                $tr .= '</tr>';

                $tr.= '<tr>';
                $tr.= '<th>'.$this->lang->line('lb_no').'</th>';
                $tr.= '<th>'.$this->lang->line('lb_transaction_no').'</th>';
                $tr.= '<th>'.$this->lang->line('lb_transaction').'</th>';
                $tr.= '<th>'.$this->lang->line('lb_date').'</th>';
                $tr.= '<th>'.$this->lang->line('lb_product_code').'</th>';
                $tr.= '<th>'.$this->lang->line('lb_product_name').'</th>';
                $tr.= '<th>'.$this->lang->line('lb_initial').'</th>';
                $tr.= '<th>'.$this->lang->line('lb_in').'</th>';
                $tr.= '<th>'.$this->lang->line('lb_out').'</th>';
                $tr.= '<th>'.$this->lang->line('lb_last').'</th>';
                $tr.= '</tr>';

                $tfoot = '<tr style="background: #bababa;color: black;">';
                $tfoot .= '<th colspan="6">'.$this->lang->line('lb_total').'</th>';
                $tfoot .= '<th>'.$this->main->qty($totalAwal).'</th>';
                $tfoot .= '<th>'.$this->main->qty($totalIn).'</th>';
                $tfoot .= '<th>'.$this->main->qty($totalOut).'</th>';
                $tfoot .= '<th>'.$this->main->qty($totalLast).'</th>';
                $tfoot .= '</tr>';

                echo $tr;
                echo $td;
                echo $tfoot;
            endif;
        }
    elseif($group == "transaction"):
    	$tr = '<tr>';
    	$tr .= '<th style="color: #d90000;text-align: center;">Please Select Product Name</th>';
    	$tr .= '</tr>';
    	echo $tr;
    elseif($group == "all"):
    	
    	$td = '';
    	$no = 0;
    	$totalIn  	= 0;
		$totalOut 	= 0;
		$totalLast 	= 0;
		$totalAwal 	= 0;
    	foreach ($list as $a) {
    		$no += 1;
            $td .= '<tr>';
            $td .= "<td>".$no."</td>";
            $td .= "<td>".$a->Kode."</td>";
            $td .= "<td>".$a->Nama_Produk."</td>";
            $td .= "<td>".$this->main->qty($a->awal)."</td>";
            $td .= "<td>".$this->main->qty($a->masuk)."</td>";
            $td .= "<td>".$this->main->qty($a->keluar)."</td>";
            $td .= "<td>".$this->main->qty($a->akhir)."</td>";
            $td .= "</tr>";

            $totalAwal 	+= $a->awal;
            $totalIn 	+= $a->masuk;
            $totalOut 	+= $a->keluar;
            $totalLast 	+= $a->akhir;
    	}

    	$tr = '<tr>';
        $tr.= '<th>'.$this->lang->line('lb_no').'</th>';
        $tr.= '<th>'.$this->lang->line('lb_product_code').'</th>';
        $tr.= '<th>'.$this->lang->line('lb_product_name').'</th>';
        $tr.= '<th>'.$this->lang->line('lb_initial').'</th>';
        $tr.= '<th>'.$this->lang->line('lb_in').'</th>';
        $tr.= '<th>'.$this->lang->line('lb_out').'</th>';
        $tr.= '<th>'.$this->lang->line('lb_last').'</th>';
        $tr.= '</tr>';

        $tfoot = '<tr>';
        $tfoot .= '<th colspan="3">'.$this->lang->line('lb_total').'</th>';
        $tfoot .= '<th>'.$this->main->qty($totalAwal).'</th>';
        $tfoot .= '<th>'.$this->main->qty($totalIn).'</th>';
        $tfoot .= '<th>'.$this->main->qty($totalOut).'</th>';
        $tfoot .= '<th>'.$this->main->qty($totalLast).'</th>';
        $tfoot .= '</tr>';

        echo $tr;
        echo $td;
        echo $tfoot;
    elseif($group == "store"):
    	$BranchID = array();
        foreach ($list as $a):
            if(!in_array($a->BranchID,$BranchID)): array_push($BranchID,$a->BranchID); endif;
        endforeach;

        foreach ($BranchID as $v) {
	        $tr 		= '';
	        $td 		= '';
	        $no 		= 0;
	        $awal 		= 0;
	        $totalIn  	= 0;
			$totalOut 	= 0;
			$totalLast 	= 0;
			$totalAwal 	= 0;
	        $branchName = '';
	        foreach ($list as $a) {
	            if($v == $a->BranchID):
	                $branchName = $a->branchName;

	                $no += 1;
		            $td .= '<tr>';
		            $td .= "<td>".$no."</td>";
		            $td .= "<td>".$a->Kode."</td>";
		            $td .= "<td>".$a->Nama_Produk."</td>";
		            $td .= "<td>".$this->main->qty($a->awal)."</td>";
		            $td .= "<td>".$this->main->qty($a->masuk)."</td>";
		            $td .= "<td>".$this->main->qty($a->keluar)."</td>";
		            $td .= "<td>".$this->main->qty($a->akhir)."</td>";
		            $td .= "</tr>";

		            $totalAwal 	+= $a->awal;
	                $totalIn 	+= $a->masuk;
	                $totalOut 	+= $a->keluar;
	                $totalLast 	+= $a->akhir;
	            endif;
        	}

        	$tr .= '<tr>';
            $tr .= '<td colspan="7" style="font-weight:bold;background: #ededed;">Store Name : '.$branchName.'</td>';
            $tr .= '</tr>';

        	$tr .= '<tr>';
	        $tr.= '<th>'.$this->lang->line('lb_no').'</th>';
	        $tr.= '<th>'.$this->lang->line('lb_product_code').'</th>';
	        $tr.= '<th>'.$this->lang->line('lb_product_name').'</th>';
	        $tr.= '<th>'.$this->lang->line('lb_initial').'</th>';
	        $tr.= '<th>'.$this->lang->line('lb_in').'</th>';
	        $tr.= '<th>'.$this->lang->line('lb_out').'</th>';
	        $tr.= '<th>'.$this->lang->line('lb_last').'</th>';
	        $tr.= '</tr>';

	        $tfoot = '<tr style="background: #bababa;color: black;">';
            $tfoot .= '<th colspan="3">'.$this->lang->line('lb_total').'</th>';
            $tfoot .= '<th>'.$this->main->qty($totalAwal).'</th>';
            $tfoot .= '<th>'.$this->main->qty($totalIn).'</th>';
            $tfoot .= '<th>'.$this->main->qty($totalOut).'</th>';
            $tfoot .= '<th>'.$this->main->qty($totalLast).'</th>';
            $tfoot .= '</tr>';

	        echo $tr;
	        echo $td;
	        echo $tfoot;
        }
    endif;
?>
</table>