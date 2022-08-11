<?php
	$list = $this->report->select_loss_and_profit();
	$no = 1;
	$list_induk = array();
	 foreach ($list  as $a):
        if(!in_array($a->GreatGreatGrandParent, $list_induk, true)):
            array_push($list_induk, $a->GreatGreatGrandParent);
        endif;
    endforeach;

    foreach($list_induk as $a):
    	$IndukName      = $a;
    	$Remark 		= "";
        $tr             = "";
        $list_induk2    = array();
        $grand_total_debit  = 0;

        foreach($list as $b):
        	if($a == $b->GreatGreatGrandParent):
        		$Remark = $b->Keterangan;
        		if(!in_array($b->GreatGrandParent, $list_induk2, true)):
                    array_push($list_induk2, $b->GreatGrandParent);
                endif;
        	endif;
        endforeach;

        foreach ($list_induk2 as $b):
            $list_induk3    = array();
            foreach ($list as $c):
                if($c->GreatGrandParent == $b):
                    if(!in_array($c->GrandParent, $list_induk3, true)):
                        array_push($list_induk3, $c->GrandParent);
                    endif;
                endif;
            endforeach;

        	$IndukName2     = $b;
        	$total_debit    = 0;
        	$tr .= '<tr><td colspan="2" style="font-weight:bold;padding-left:20px;background:#d0cdcd">'.$IndukName2.'</td></tr>';
        	foreach($list_induk3 as $d):
        		$total_level3 = 0;
        		$tr .= '<tr><td colspan="2" style="font-weight:bold;padding-left:30px;background:#eee">'.$d.'</td></tr>';
        		foreach ($list as $e):
        			if($e->GrandParent == $d):
        				$cost = $e->Total;
        				$total_level3 += $cost;
        				$tr .= '<tr>';
                        $tr .= '<td style="padding-left:40px;">'.$e->ParentCode." ".$e->ParentName.'</td>';
                        $tr .= '<td style="text-align:right;">'.$this->main->currency2($cost).'</td>';
                        $tr .= '</tr>';
        			endif;
        		endforeach;
        		$tr .= '<tr><td style="width:35%;font-weight:bold;padding-left:30px;">Total '.$d.'</td>';
            	$tr .= '<td style="font-weight:bold;text-align:right;">'.$this->main->currency2($total_level3).'</td></tr>';
            	$total_debit += $total_level3;
        	endforeach;
        	$tr .= '<tr><td style="width:35%;font-weight:bold;padding-left:20px;">Total '.$IndukName2.'</td>';
            $tr .= '<td style="font-weight:bold;text-align:right;">'.$this->main->currency2($total_debit).'</td></tr>';
            $grand_total_debit += $total_debit;
        endforeach;

        $table = '<table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%" >';
        $table .= '<tbody>';
        $table .= '<tr><td colspan="2" style="font-weight:bold;">'.$Remark.". ".$IndukName.'</td></tr>';
        $table .= $tr;
        $table .= '<tr><td style="width:35%;font-weight:bold;">Total '.$IndukName.'</td>';
        $table .= '<td style="font-weight:bold;text-align:right;">'.$this->main->currency2($grand_total_debit).'</td></tr>';
        $table .= '</tbody>';
        $table .= '</table>';
        echo $table;
    
    endforeach;

    // echo '<pre>';
    // echo print_r($list);
    // echo '</pre>';
?>