
<div class="content">
	<p>
		<b>Yang Terhormat <?= $nama; ?>,</b>
		<br/>
		<?php if($modul == "register"): echo "Terimakasih telah bergabung bersama kami."; endif; ?>
		
		<br/>
		<div class="div-kode">
			<div><span class="text-title">Ini kode verifikasi anda : </span></div>
			<div>
				<span class="text"><?= $VerificationNumber; ?></span>
			</div>
		</div>
		<span class="red_txt">gunakan kode verifikasi sebelum <?= $this->main->tanggal("d M Y H:i",$VerificationNumberExpire); ?></span>
		<br/>
		atau silakan <a href="<?= $url; ?>" target="_blank">Klik Disini</a> untuk verifikasi akun anda.
	</p>
</div>
<hr/>
<div class="content">
	<p>
		<b>Dear <?= $nama; ?>,</b>
		<br/>
		<?php if($modul == "register"): echo "Thanks for joining us."; endif; ?>
		<br/>
		<div class="div-kode">
			<div><span class="text-title">This is your verification number :</span></div>
			<div>
				<span class="text"><?= $VerificationNumber; ?></span>
			</div>
		</div>
		<span class="red_txt">use verification number before <?= date("d F Y H:i",strtotime($VerificationNumberExpire)); ?></span>
		<br/>
		or please <a href="<?= $url; ?>" target="_blank">Click Here</a> for verification your account.
	</p>
</div>