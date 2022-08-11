<?= $list->Content; ?>
<script type="text/javascript">
  arrData = <?= json_encode($data_action) ?>;
  set_button_action(arrData)
</script>