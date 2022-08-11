<!DOCTYPE html>
<html>
<head>
	<title>Email</title>
	<!-- <link rel="stylesheet" type="text/css" href="<?= base_url("aset/css/email.css"); ?>"> -->
	<style type="text/css">
		body {
			font-family:monospace;
			background:#fafbfc;
		}
		.content {
	       padding: 0px 0px; 
	       min-height: 0px; 
	    }
		.div-email {
	      background: #fff;
	      /*margin: 0px;*/
	      /*border: 1px solid #cdcdcd;*/
	    }
		.header {
			padding:10px 20px;
			border-bottom:1px solid #cdcdcd;
		/* 	background:#2196F3; */
		/* 	color:#fff; */
		}
		.header .title{
			font-weight:bold;
			font-size:25px;
		}
		.content{
			padding:10px 20px;
			/*min-height:200px;*/
		}
		.footer {
			padding:15px;
			text-align:center;
			border-top:1px solid #cdcdcd;
			background: #eaeaea;
			font-size:11px
		}
		.footer .title{
			display:block;
		}
		.footer .alamat{
			display:block;
		}
		.btn {
			text-align: center;
		    display: block;
		    border-radius: 3px;
		    color: #fff;
		    background: #1b9ce2;
		    text-decoration: none;
		    font-family: Roboto,Helvetica Neue,Helvetica,Arial,sans-serif;
		    font-size: 14px;
		    border-top: 12px solid #1b9ce2;
		    border-right: 30px solid #1b9ce2;
		    border-bottom: 12px solid #1b9ce2;
		    border-left: 30px solid #1b9ce2;
		    text-transform: uppercase;
		    width: 50%;
		}
		.table {
			width: 100%;
		    border-collapse: collapse;
		    width: 100%;
		    margin-bottom: 15px;
		}

		.table th, .table td {
		    text-align: left;
		    padding: 8px;
		}

		.table tr:nth-child(even){background-color: #eaeaea}

		.table th {
		    background-color: #4CAF50;
		    color: white;
		}
		.bg-gray {
			background: #eaeaea;
			padding:10px;
			border-radius: 3px;
		}
		.red_txt {
			color: red !important;
		}
		.blue_txt {
			color: #2196f3 !important;
		}
		.div-email .content .table {
			border:1px solid #eaeaea;
			margin-top: 10px;
		}
		.div-kode {
			background: #eaeaea;
		    padding: 10px;
		    text-align: center;
		    line-height: 40px;
		    margin: 15px 0px;
		    border-radius: 5px;
		}
		.div-kode .text {
			font-size: 35px;
		}
		.div-kode .text-title {
			font-size: 20px;
		}
		.div-saldo {
		    padding: 10px;
		    line-height: 40px;
		    margin: 15px 0px;
		    border-radius: 5px;
		    border: outset;
		}
		.div-saldo .text {
			font-size: 30px;
		}
		.div-saldo .text-title {
			font-size: 20px;
		}
		.table-saldo th, .table-saldo td {
		    text-align: center;
		}

		.table-saldo tr:nth-child(even){background-color: #eaeaea}

		.table-saldo th {
		    background-color: #b6b7b6;
		    color: #000000;
		}
		a{
			color: blue;
			text-decoration: none;
		}
		.img-logo{
			max-height: 60px;
			max-width: 100%;
		}
	</style>
</head>
<body>
<div class="div-email">
	<table class="es-header" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top;"> 
         <tbody><tr style="border-collapse:collapse;"> 
          <td align="center" style="padding:0;Margin:0;"> 
           <table class="es-header-body" width="600" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#72d3fe73;"> 
             <tbody><tr style="border-collapse:collapse;"> 
              <td align="left" style="padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px;"> 
               <!--[if mso]><table width="560" cellpadding="0" cellspacing="0"><tr><td width="178" valign="top"><![endif]--> 
               <table class="es-left" cellspacing="0" cellpadding="0" align="left" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left;"> 
                 <tbody><tr style="border-collapse:collapse;"> 
                  <td class="es-m-p0r es-m-p20b" width="178" valign="top" align="center" style="padding:0;Margin:0;"> 
                   <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;"> 
                     <tbody><tr style="border-collapse:collapse;"> 
                      <td class="es-m-txt-c" align="left" style="padding:0;Margin:0;"><img src="<?= base_url('img/logo.png') ?>" width="250" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;"></td> 
                     </tr> 
                   </tbody></table></td> 
                 </tr> 
               </tbody></table> 
               <!--[if mso]></td><td width="20"></td><td width="362" valign="top"><![endif]--> 
               <table cellspacing="0" cellpadding="0" align="right" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;"> 
                 <tbody><tr style="border-collapse:collapse;"> 
                  <td width="362" align="left" style="padding:0;Margin:0;"> 
                   <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;margin-top: -60px;"> 
                     <tbody><tr style="border-collapse:collapse;"> 
                      <td class="es-m-txt-c" align="right" style="padding:0;Margin:0;padding-top:15px;padding-bottom:20px;"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, &#39;helvetica neue&#39;, helvetica, sans-serif;line-height:21px;color:#064156;">Webinar date:</p><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, &#39;helvetica neue&#39;, helvetica, sans-serif;line-height:21px;color:#064156;">January 25, 2018 | 7.00 PM EST</p></td> 
                     </tr> 
                   </tbody></table></td> 
                 </tr> 
               </tbody></table> 
               <!--[if mso]></td></tr></table><![endif]--></td> 
             </tr> 
             <tr style="border-collapse:collapse;"> 
              <td align="left" style="padding:0;Margin:0;"> 
               <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;"> 
                 <tbody><tr style="border-collapse:collapse;"> 
                  <td width="600" valign="top" align="center" style="padding:0;Margin:0;"> 
                   <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;"> 
                     <tbody><tr style="border-collapse:collapse;"> 
                      <td align="center" style="padding:0;Margin:0;"><img class="adapt-img" src="<?= base_url('aset/images/email/47051523540803179.png');?>" alt="" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;" width="600"></td> 
                     </tr> 
                   </tbody></table></td> 
                 </tr> 
               </tbody></table></td> 
             </tr> 
           </tbody></table></td> 
         </tr> 
    </tbody></table> 
	<div class="content">
		<!-- <b>Dear <?= $nama; ?>,</b> -->
		<?php $this->load->view($page); ?>
	</div>
	<table class="es-footer" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:white;background-repeat:repeat;background-position:center top;"> 
         <tbody><tr style="border-collapse:collapse;"> 
          <td align="center" style="padding:0;Margin:0;"> 
           <table class="es-footer-body" width="600" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;"> 
             <tbody><tr style="border-collapse:collapse;"> 
              <td align="left" style="padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px;"> 
               <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;"> 
                 <tbody><tr style="border-collapse:collapse;"> 
                  <td width="560" valign="top" align="center" style="padding:0;Margin:0;"> 
                   <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;"> 
                     <tbody><tr style="border-collapse:collapse;"> 
                      <td align="center" style="padding:0;Margin:0;"><img src="<?= base_url('img/logo.png') ?>" title="Pipesys logo" width="178" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;"></td> 
                     </tr> 
                     <tr style="border-collapse:collapse;"> 
                      <td align="center" style="padding:0;Margin:0;padding-top:10px;padding-left:10px;padding-right:10px;"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, &#39;helvetica neue&#39;, helvetica, sans-serif;line-height:21px;color:#333333;"><a href="https://rcelectronic.co.id" target="_blank"  style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, &#39;helvetica neue&#39;, helvetica, sans-serif;font-size:14px;text-decoration:none;color:#34265F;">Copyright &copy; since 2019 - RC Electronic, Cv<strong></a></strong>.</p></td> 
                     </tr> 
                     <tr style="border-collapse:collapse;"> 
                      <td align="center" style="Margin:0;padding-top:5px;padding-bottom:5px;padding-left:20px;padding-right:20px;"> 
                       <table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;"> 
                         <tbody><tr style="border-collapse:collapse;"> 
                          <td style="padding:0;Margin:0px 0px 0px 0px;border-bottom:1px solid #CCCCCC;background:none;height:1px;width:100%;margin:0px;"></td> 
                         </tr> 
                       </tbody></table></td> 
                     </tr> 
                     <tr style="border-collapse:collapse;"> 
                      <td align="center" style="padding:10px;Margin:0;"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:12px;font-family:arial, &#39;helvetica neue&#39;, helvetica, sans-serif;line-height:18px;color:#333333;"><a href="https://goo.gl/maps/mB46EPs6Dz2tuzqc9" target="_blank"  style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, &#39;helvetica neue&#39;, helvetica, sans-serif;font-size:14px;text-decoration:none;color:#34265F;">Jl. Indrayasa No.158 - 160, Cibaduyut, Kec. Bojongloa Kidul, Kota Bandung, Jawa Barat 40236</a></p></td> 
                     </tr> 
                   </tbody></table></td> 
                 </tr> 
               </tbody></table></td> 
             </tr> 
           </tbody></table></td> 
         </tr> 
       </tbody></table> 
</div>
</body>
</html>