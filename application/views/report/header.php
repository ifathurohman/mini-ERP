<?php $status = $this->input->get("cetak"); ?>
<?php if($status == "xprint"): ?>
<script type="text/javascript">
window.print();
</script>
<?php endif; ?>
<table style="width:100%;margin:0px;">
    <tr>
      <td align="center" style="text-align:-webkit-center;">
        <center>
        <?php if(is_file($logo)): ?>
        <div>
        <img src="<?= base_url($logo); ?>" height="80px">
        </div>
        <?php endif; ?>
        <span style="font-weight:bold;"><?= $company_name; ?></span>
        <br/>
        <!-- <span>JL. KEBONJATI NO. 27A, BANDUNG TELP. 022 - 421 9999 </span> -->
        </center>
      </td>
    </tr>
  </table>
   <hr style=" border: 0;height: 1px; background: #333;"  align="bottom">
   <div class="vPeriode" align="center" style="margin-bottom:10px;text-align:-webkit-center;margin-top:10px;">
   	<?= $nama_laporan; ?><br>
   	<span><?= "Period : " . $start_date." to ".$end_date; ?></span>
   	</div>
</div>