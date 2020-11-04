<div class="modal-dialog" style="max-height:650px;">
  <div class="modal-content">

	<div class="modal-header">
        <div class="pull-right">
            <button type="button" class="btn btn-primary frame-save" id="button-save-close"><?php echo $button_save_close; ?></button>&nbsp;
            <button type="button" class="btn btn-danger" data-dismiss="modal" id="button-save-cancel"><?php echo $button_cancel_return; ?></button>
        </div>
        <h2 class="modal-title"><i class="fa fa-comment"></i> <?php echo $text_edit_comment_modal; ?></span>
	</div>

	<div class="modal-body">
         
          <div class="container-fluid"> 
            <div class="panel panel-default">
        
              <div class="panel-body clearfix">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-vqmod-comment" class="form-horizontal">
                    
                    <i class="fa fa-info-circle fa-lg"></i>&nbsp;&nbsp<?php echo $text_comment_info; ?>
                    <hr style="margin-bottom:5px" />
                    
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group col-sm-12">
                              <textarea name="vqmod_comment" id="inputcomment" class="form-control" rows="15"><?php echo htmlentities($vqmod_comment, ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>                
                        </div>
                    </div>
                    
                    <hr style="margin-top:5px" />
                    
                    <i class="fa fa-user fa-lg"></i>&nbsp;&nbsp;<strong><?php echo $text_developer_details; ?></strong>
                    
                    <hr style="margin-bottom:5px" />
                    
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="control-label col-sm-2" for="vqmod_email" style="margin-top:10px;"><?php echo $text_email_info; ?>:</label>
                            <div class="form-group col-sm-8">
                                <div class="input-group">
                                    <input type="email" name="vqmod_email" class="form-control" value="<?php echo $vqmod_email; ?>" />
                                    <div class="input-group-addon"><i class="fa fa-envelope"></i></div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <?php if ($vqmod_email != '') { ?>
                                        &nbsp;&nbsp;<a href="mailto:<?php echo $vqmod_email; ?>" class="btn btn-default" data-toggle="tooltip" title="<?php echo $text_external_email; ?>"><i class="fa fa-external-link-square fa-lg"></i></a>
                                    <?php } ?>
                                </div> 
                            </div>                
                        </div>                
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="control-label col-sm-2" for="vqmod_link" style="margin-top:10px;"><?php echo $text_link_info; ?>:</label>
                            <div class="form-group col-sm-8">
                                <div class="input-group">
                                    <input type="text" name="vqmod_link" class="form-control" value="<?php echo $vqmod_link; ?>" />
                                    <div class="input-group-addon"><i class="fa fa-link"></i></div>
                                </div>                        
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <?php if ($vqmod_link != '') { ?>
                                        &nbsp;&nbsp;<a href="<?php echo $vqmod_link; ?>" class="btn btn-default" data-toggle="tooltip" title="<?php echo $text_external_link; ?>" target="_blank"><i class="fa fa-external-link-square fa-lg"></i></a>
                                    <?php } ?>
                                </div>
                            </div>                 
                        </div>
                    </div>
                </form>
              </div>
            </div>
        
            <div class="loading-overlay hidden" id="loading-overlay">
                <div id="vqloader"></div>  
                <div id="ea-msg"></div>                           
            </div> 
           
          </div>

    </div> 
   
  </div>
</div>


<style type="text/css">
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
.form-horizontal .control-label {
    text-align: left;
}
#modal-vqmod-comment .panel-default {
	box-shadow: 0 0 0 #ccc;
	border: 1px solid #ccc;
}
#modal-vqmod-comment .container-fluid {
    padding-left: 0px;
    padding-right: 0px;
}
#content {
    padding-bottom: 0px;
}
#modal-vqmod-comment .panel {
    margin-bottom: 0px;
}
</style>

<script type="text/javascript"><!--
$('#modal-vqmod-comment .frame-save').on('click', function() {	
	$('[data-toggle=\'tooltip\']').tooltip('hide');
	var ajaxUrl = $("#form-vqmod-comment").attr("action");	
	$.ajax({
		type: "POST",
		url: ajaxUrl, 
		data: $('#form-vqmod-comment').serialize(),
		beforeSend: function() {
			$('#button-save-close i').replaceWith('<i class="fa fa-cog fa-spin"></i>');
			$('#button-save-close').prop('disabled', true);
			$('#button-save-continue').prop('disabled', true);
			$('#button-save-cancel').prop('disabled', true);			
		},
		complete: function() {
			$('#button-save-close i').replaceWith('<i class="fa fa-save"></i>');
			$('#button-save-close').prop('disabled', false);
			$('#button-save-continue').prop('disabled', false);
			$('#button-save-cancel').prop('disabled', false);
		},
		success: function(response) {
			window.parent.showToast('success', 'toast-top-center', '<?php echo $success_saved_comment; ?>');			
			$('#modal-vqmod-comment').modal('hide');
		},	
	});
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