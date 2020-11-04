<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-vqmod-edit" class="btn btn-primary" onclick="showOverlay('<?php echo $text_saving; ?>');"><i class="fa fa-save"></i><span class="hidden-xs">&nbsp;&nbsp;<?php echo $button_save_xml; ?></span></button>
        <a href="<?php echo $return; ?>" class="btn btn-default"><i class="fa fa-reply"></i><span class="hidden-xs">&nbsp;&nbsp;<?php echo $button_close_xml; ?></span></a>  
      </div>
      <h1><i class="fa fa-wrench"></i> <?php echo $heading_title_vqmods; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  
  <div class="container-fluid" id="page-load"> 
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit_vqmod; ?></h3>
      </div>

      <div class="panel-body clearfix">
      	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-vqmod-edit" class="form-horizontal">
			<div class="col-sm-12">
 				<div class="form-group required" style="padding-top:0px;">
                  <label class="control-label" for="vqmod_file"><?php echo $column_xml; ?></label>
                  <?php if (isset($error_xml)) { ?><div class="text-danger"><strong><?php echo $error_xml; ?></strong></div><?php } ?>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group" style="padding-top:0px;">
                  <textarea name="vqmod_file" id="inputcode" class="form-control"><?php echo htmlentities($vqmod_xml, ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>                
        	</div>
      	</form>

        <div class="loading-overlay hidden" id="loading-overlay">
            <div id="vqloader"></div>  
            <div id="ea-msg"></div>                           
        </div> 

      </div>
    </div>
   
  </div>
</div>

<style type="text/css">
#inputcode + .CodeMirror {
	height: 550px;
}
#toast-container > div {
	width: 550px!important;
	opacity: 0.9;
	-ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=90);
	filter: alpha(opacity=90);
}
.toast-message {
	font-size:16px;
	font-weight:bold;
}

.loading-overlay-container {position: relative;}
.loading-overlay {position: absolute;left: 0;top: 0;width: 100%;height: 100%;background: rgba(0, 0, 0, 0.4)!important;font-size: 4em;text-align: center;z-index: 20;}

#vqloader {
	-moz-border-bottom-colors: none;
	-moz-border-left-colors: none;
	-moz-border-right-colors: none;
	-moz-border-top-colors: none;
	animation: 2s linear infinite spin;
	border-color: #990000 #C9C9C9;
	border-image: none;
	border-radius: 50%;
	border-style: solid;
	border-width: 16px;
	height: 120px;
	left: 50%;
	margin: 0 0 0 -60px;
	position: fixed;
	top: 35%;
	width: 120px;
	z-index: 1;
	opacity:0.7;
}
#ea-msg {
	height: 60px;
	left: 50%;
	margin: 30px 0 0 -160px;
	position: fixed;
	top: 35%;
	width: 320px;
	z-index: 1;	
	border-top:2px solid #eee;
	border-bottom:2px solid #eee;
	padding:0;
}
#ea-msg div {
	margin-top:-10px;
	color: #eee;
	text-shadow: 1px 1px 1px rgba(150, 150, 150, 1);
	opacity:0.8;
}
@keyframes spin {
to{transform:rotateZ(720deg)}
}
@-o-keyframes spin {
to{transform:rotateZ(720deg)}
}
@-moz-keyframes spin {
to{transform:rotateZ(720deg)}
}
@-webkit-keyframes spin {
to{transform:rotateZ(720deg)}
}
</style>

<script type="text/javascript"><!--
function showOverlay(msg) {
	$('#ea-msg').html('');
	html = '<div>'+ msg +'</div>';
	$('#ea-msg').prepend(html);
	$('#loading-overlay').removeClass('hidden');
}

function hideOverlay() {
	$('#loading-overlay').addClass('hidden');
}

var editor = CodeMirror.fromTextArea(document.getElementById("inputcode"), {
	mode: "application/xml",
	theme: "xq-dark",
	styleActiveLine: true,
	lineNumbers: true,
	lineWrapping: true,
	integer: 2,
	indentWithTabs: true,
	indentUnit: 2,
	autofocus: true,
	alignCDATA: true,
	viewportMargin: 10,
	autoCloseTags: true
});
//--></script>

<script type="text/javascript"><!--
function showToast(type, css, msg) {
	toastr.options.extendedTimeOut = 0; //1000;
	toastr.options.timeOut = 8000;
	toastr.options.hideDuration = 250;
	toastr.options.showDuration = 500;
	toastr.options.showMethod = 'slideDown';
	toastr.options.hideMethod = 'slideUp';
	toastr.options.closeMethod = 'slideUp';	
	toastr.options.closeButton = true,
	toastr.options.preventDuplicates = true,
	toastr.options.positionClass = css;
	toastr[type](msg);
}
//--></script>

<?php if ($error_warning) { ?>
<script type="text/javascript"><!--
showToast('error', 'toast-top-center', '<?php echo $error_warning; ?>');
//--></script>
<?php } ?>
<?php if ($success) { ?>
<script type="text/javascript"><!--
showToast('success', 'toast-top-center', '<?php echo $success; ?>');
//--></script>
<?php } ?>

<?php echo $footer; ?>