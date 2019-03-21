<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script type="text/javascript">
	// Import Employees
	function import_employees() {
		$('#submit').attr('disabled', 'disabled');
		_H.Loading( true );
		var values = {
			employees: $('#employees').val()
		};
		$.post(_BASE_URL + 'employees/import/save', values, function(response) {
			var res = _H.StrToObject( response );
			_H.Notify(res.status, _H.Message(res.message));
			$('#employees').val('');
			$('#submit').removeAttr('disabled');
			_H.Loading( false );
		});
	}
</script>
<section class="content-header">
   <h1><i class="fa fa-upload text-green"></i> <?=ucwords(strtolower($title));?></h1>
 </section>
 <section class="content">
 	<div class="callout callout-info">
		<button type="button" onclick="removeCallout()" class="close">×</button>
   	<h4>Petunjuk Singkat</h4>
		<ol>
			<li>Buka Aplikasi Microsoft Excel untuk pengguna Windows atau Libre Office Calc untuk pengguna Linux</li>
			<li>Isikan data dengan urutan <strong>[NIK] [NAMA LENGKAP] [JENIS KELAMIN] [ALAMAT JALAN] [TEMPAT LAHIR] [TANGGAL LAHIR]</strong></li>
			<li>Copy data yang sudah diketik tersebut tanpa judul kolom <strong>(Point 2)</strong> kemudian paste didalam form textarea dibawah.</li>
			<li>Kolom <strong>JENIS KELAMIN</strong> diisi huruf <strong>"L"</strong> jika Laki-laki dan <strong>"P"</strong> jika Perempuan.</li>
			<li>Kolom <strong>TANGGAL LAHIR</strong> diisi dengan format <strong>"YYYY-MM-DD"</strong>. Contoh :  <strong>1991-03-15</strong></li>
		</ol>
	</div>
 	<div class="panel panel-default">
		<div class="panel-body">			
			<form role="form">
				<div class="box-body">
					<div class="form-group">
               	<textarea autofocus id="employees" name="employees" class="form-control" rows="16" placeholder="Paste here..."></textarea>
            	</div>
				</div>
				<div class="box-footer">
               <button type="submit" onclick="import_employees(); return false;" class="btn btn-primary"><i class="fa fa-upload"></i> IMPORT</button>
            </div>
         </form>
		</div>
	</div>
 </section>