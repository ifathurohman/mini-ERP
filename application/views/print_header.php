<?php $status = $this->input->get("cetak"); ?>
<?php if($status == "xprint"): ?>
<script type="text/javascript">
window.print();
</script>
<?php endif; ?>
<table style="width:100%;margin:0px;">
    <tr>
      <td align="center" style="text-align:-webkit-center;font-size: 12px">
        <center>
        <?php if($logo): ?>
        <div>
        <img src="<?= $logo; ?>" height="50px">
        </div>
        <?php endif; ?>
        <span style="font-weight:bold;"><?= $company_name; ?></span><br>
        <span><?= $company->address ?> </span><br>
        <span><?= $company->city ?></span>
        </center>
      </td>
    </tr>
  </table>
  <hr style="border-top: 1px dashed #ccc;height: 1px;"  align="bottom">
</div>