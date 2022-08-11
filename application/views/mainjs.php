<script src="<?= base_url('aset/js/jquery-2.1.4.min.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('/aset/general_app.js?'.time()) ?>"></script>
<?php if($this->session->bahasa == "indonesia"): ?>
  <script src="<?= base_url('/aset/language_indo.js?'.time()) ?>"></script>
<?php else: ?>
  <script src="<?= base_url('/aset/language_english.js?'.time()) ?>"></script>
<?php endif; ?>
<script src="<?= base_url("aset/js/sweetalert.min.js".$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url("aset/js/jquery.lineProgressbar.js".$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('aset/js/page/main.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url("aset/plugin/jquery.maskMoney.js".$this->main->js_css_version()); ?>"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.js"></script>

<!-- <script src="<?= base_url("aset/js/maskMoneybaru.js".$this->main->js_css_version()); ?>"></script> -->

