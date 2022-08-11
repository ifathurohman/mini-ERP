<?php
	$list = $this->report->select_ledger();
	// echo '<pre>';
	// print_r($list);
	// echo '</pre>';
?>
<table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
  <thead>
    <tr>
        <th width="50"><?= $this->lang->line('lb_date') ?></th>
        <th><?= $this->lang->line('lb_bank_acount') ?></th>
        <th><?= $this->lang->line('lb_bank_acountno') ?></th>
        <th><?= $this->lang->line('lb_remark') ?></th>
        <th><?= $this->lang->line('lb_transaction') ?></th>
        <th style="width: 10%"><?= $this->lang->line('lb_debit') ?></th>
        <th style="width: 10%"><?= $this->lang->line('lb_credit') ?></th>
        <th style="width: 10%"><?= $this->lang->line('lb_saldo') ?></th>
    </tr>
  </thead>
<tbody>
<?php
	$no = 1;
    $list_induk = array();
    foreach ($list  as $a):
        if(!in_array($a->ParentCode, $list_induk, true)):
            array_push($list_induk, $a->ParentCode);
        endif;
    endforeach;
    $grand_total_debit  = 0;
    $grand_total_credit = 0;
    $grand_total_saldo  = 0;
    foreach($list_induk as $a):
        // $induk  = $this->report->ambil_induk_coa($a);
        $induk_name 	= "";
        $induk_code 	= "";
        $tr_detail      = "";
        $total_debit    = 0;
        $total_credit   = 0;
        $total_saldo    = 0;

        foreach($list as $b):
            if($a == $b->ParentCode):
            	$induk_name 	= $b->parentName;
            	$induk_code 	= $b->ParentCode;
            	$Transaksi 		= explode(".", $b->Transaksi);
                if(count($Transaksi)>1):
                    $Transaksi = $Transaksi[1];
                else:
                    $Transaksi = $Transaksi[0];
                endif;
                $tr = "<tr>";
                $tr .= "<td>".date("d/m/Y",strtotime($b->Tanggal))."</td>";
                $tr .= "<td>".$b->AcctName."</td>";
                $tr .= "<td>".$b->AcctCode."</td>";
                $tr .= "<td>".$b->keterangan."</td>";
                $tr .= "<td>".$Transaksi."</td>";
                $tr .= "<td>".$this->main->currency($b->Debit)."</td>";
                $tr .= "<td>".$this->main->currency($b->Kredit)."</td>";
                $tr .= "<td>".$this->main->currency($b->Saldo)."</td>";
                $tr .= "</tr>";

                $total_debit    += $b->Debit;
                $total_credit   += $b->Kredit;
                $total_saldo    = $b->Saldo;
                $tr_detail      .= $tr;
            endif;
        endforeach;

        $total_saldo        = $total_debit - $total_credit;
        $grand_total_debit += $total_debit;
        $grand_total_credit += $total_credit;


        $tr = '<tr>';
        $tr .= '<td colspan="8"><strong>('.$a.') - '.$induk_name.'</strong></td>';
        $tr .= '</tr>';
        $tr .= $tr_detail;
        $tr .= '<tr>';
        $tr .= '<td colspan="5" style="text-align:center"><strong>('.$induk_name.')</strong></td>';
        $tr .= '<td style="font-weight:bold;">'.$this->main->currency($total_debit).'</td>';
        $tr .= '<td style="font-weight:bold;">'.$this->main->currency($total_credit).'</td>';
        $tr .= '<td style="font-weight:bold;">'.$this->main->currency($total_saldo).'</td>';
        $tr .= '</tr>';
        echo $tr;

    endforeach;

    $grand_total_saldo = $grand_total_debit - $grand_total_credit;
    $tr = '<tr>';
    $tr .= '<td colspan="5" style="text-align:center"><strong>'.$this->lang->line('lb_grand_total').'</strong></td>';
    $tr .= '<td style="font-weight:bold;">'.$this->main->currency($grand_total_debit).'</td>';
    $tr .= '<td style="font-weight:bold;">'.$this->main->currency($grand_total_credit).'</td>';
    $tr .= '<td style="font-weight:bold;">'.$this->main->currency($grand_total_saldo).'</td>';
    $tr .= '</tr>';
    echo $tr;
?>
</tbody>
</table>