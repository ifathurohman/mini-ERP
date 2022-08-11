<!DOCTYPE html>
<html>
<head>
    <title><?= $title; ?></title>
    <!-- <link rel="stylesheet" type="text/css" href="/aset/report.css"> -->
    <?php $this->load->view("report/report_css"); ?>
</head>
<body>
  <div class="page-receipt">
    <div class="header">
    <?php $this->load->view("report/header"); ?>
    </div>
    <div class="content">
      <?php $this->load->view($table); ?>
    </div>
    <div>
    <?php $this->load->view("report/footer"); ?>
    </div>
  </div>
</body>
</html>