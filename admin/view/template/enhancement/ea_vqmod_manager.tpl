<?php echo $header; ?>
<?php echo $column_left; ?>

<!--content --> 
<div id="content">

<!-- page header -->   
    <div class="page-header">
      <div class="container-fluid">
      	<div class="pull-right" id="hd-right">
        	<?php if (!$ziparchive) { ?><div class="alert alert-danger" style="border-left: 5px solid #F56B6B; border-radius:0; text-align:left; margin-bottom:0"><i class="fa fa fa-exclamation-circle fa-lg"></i>&nbsp;&nbsp;<?php echo $error_ziparchive; ?></div><?php } ?> 
            <?php if ($ziparchive) { ?>
                <a data-toggle="tooltip" title="<?php echo $button_backup; ?>" href="<?php echo $download_scripts; ?>" class="btn btn-primary"><i class="fa fa-download"></i></a>&nbsp;
            <?php }  ?> 
            <a href="<?php echo $clear_vqcache; ?>" class="btn btn-warning" data-toggle="tooltip" title="<?php echo $button_clear; ?>" onclick="showOverlay('<?php echo $text_clearing; ?>');"><i class="fa fa-paint-brush"></i></a>          
        </div>
        <h1><i class="fa fa-wrench fa-lg"></i>&nbsp;&nbsp;<?php echo $heading_title_vqmods; ?></h1>
        <ul class="breadcrumb">
          <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
    </div>
<!-- page header --> 

<!-- container-fluid -->
    <div class="container-fluid" id="page-load"> 
        <div class="panel panel-default">
            <div class="panel-body">
<!-- Content Loader -->
 				<div id="content_loader" rel="index.php?route=enhancement/ea_vqmod_manager&token=<?php echo $token; ?>">
<!-- Content Loader -->

                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-vqmod">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#tab-scripts"><i class="fa fa fa-code fa-lg"></i>&nbsp;&nbsp;<?php echo $tab_scripts; ?></a></li>
                        <?php if ($backups) { ?><li><a data-toggle="tab" href="#tab-backups"><i class="fa fa fa-reply fa-lg"></i>&nbsp;&nbsp;<?php echo $tab_backups; ?></a></li><?php } ?>
                        <li><a data-toggle="tab" href="#tab-settings"><i class="fa fa fa-folder-open fa-lg"></i>&nbsp;&nbsp;<?php echo $tab_settings; ?></a></li>
                        <li<?php if ($vqlog) { ?> class="highlight-error"<?php } ?>><a data-toggle="tab" href="#tab-error"><i class="fa fa fa-exclamation-circle fa-lg"></i>&nbsp;&nbsp;<?php echo $tab_error_log; ?></a></li>                    
                    </ul>
                    <div class="tab-content">
                        <?php if ($vqmod_is_installed == true) { ?>
                        
                            <div id="tab-scripts" class="tab-pane active">
                                <div class="row" style="margin-bottom:10px;">
                                	<label class="control-label col-xs-12 col-sm-3 text-right" for="input-vqmod_file" style="margin-top:8px;"><?php echo $entry_upload; ?></label>
                                    <div class="col-xs-12 col-sm-6">
                                    	<input type="file" name="vqmod_file" class="filestyle" data-placeholder="<?php echo $text_no_file; ?>" data-buttonText="<?php echo $button_choose_file; ?>" accept=".xml" />
                                    </div>
                                	<div class="col-xs-12 col-sm-1 text-right">
                                    	<button type="submit" class="btn btn-primary" onclick="showOverlay('<?php echo $text_uploading; ?>'); $('#form-vqmod').attr('action', '<?php echo $upload_vqmod; ?>'); $('#form-vqmod').submit();" id="upbtn"><i class="fa fa-upload"></i> <?php echo $text_upload; ?></button>
                                    </div>
                                	<div class="hidden-xs col-sm-2">
                                          
                                    </div>
                                </div>
                                
                                <div class="table-responsive">    
                                    <table data-toggle="table" data-show-toggle="true" data-show-columns="true" id="xmlTable">
                                        <thead>
                                            <tr>
                                                <th class="text-left eaback" data-field="name" data-sortable="true" data-switchable="false"><?php echo $column_id; ?></th>
                                                <th class="text-left eaback column-size" data-field="size" data-sortable="true"><?php echo $column_size; ?></th>
                                                <th class="text-left eaback column-version" data-field="version"><?php echo $column_version; ?></th>
                                                <th class="text-left eaback column-author" data-field="author"><?php echo $column_author; ?></th>
                                                <th class="text-left eaback column-added" data-field="added" data-sortable="true"><?php echo $column_date_added; ?></th>
                                                <th class="text-left eaback column-modified" data-field="modified" data-sortable="true"><?php echo $column_date_modified; ?></th>
                                                <th class="text-center eaback" data-field="status" data-sortable="true" data-switchable="false"><?php echo $column_status; ?></th>
                                                <th class="text-center eaback" data-field="action" data-switchable="false"><?php echo $column_action; ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($vqmods) { ?>
                                                <?php $pk = 1 ?>
                                                <?php foreach ($vqmods as $vqmod) { ?>
                                                <tr>
                                                    <td class="text-left">
                                                    <strong><?php echo $vqmod['file_name']; ?></strong><?php if ($vqmod['comment'] != '') { ?>&nbsp;&nbsp;<span data-toggle="tooltip" title="<?php echo $text_has_comment; ?>" style="cursor:pointer"><i class="fa fa-comment fa-lg text-success"></i></span><?php } ?>
                                                    <br />
                                                    <div class="vqdescription"><?php echo $vqmod['id']; ?><br /><?php echo $vqmod['invalid_xml']; ?></div>
                                                    </td>
                                                    <td class="text-left column-size"><?php echo $vqmod['file_size']; ?></td>
                                                    <td class="text-left column-version"><?php echo $vqmod['version']; ?></td>
                                                    <td class="text-left column-author"><?php echo $vqmod['author']; ?></td>
                                                    <td class="text-left column-added">
                                                    	<a href="#" data-name="<?php echo $vqmod['file_name']; ?>" data-type="combodate" data-pk="<?php echo $pk++; ?>" title="<?php echo $text_change_date_added; ?>" class="cadd"><?php echo $vqmod['date_added']; ?></a>
                                                    </td>
                                                    <td class="text-left column-modified">
                                                    	<?php if ($vqmod['date_modified'] != '01/01/1970 01:00:00') { ?><?php echo $vqmod['date_modified']; ?><?php } ?>
                                                    </td>
                                                        <td class="text-center">
                                                        <?php if($vqmod['file_name'] == 'vqmod_opencart.xml' || $vqmod['invalid_xml'] != '') { ?>
                                                            <button type="button" class="btn btn-success btn-sm xsbtn" disabled><i class="fa fa-power-off fa-but-on fa-lg"></i></button>
                                                        <?php } else { ?>
                                                            <?php foreach ($vqmod['action'] as $action) { ?>
                                                                <?php if($vqmod['installed'] == 'Enabled') { ?>
                                                                    <a href="<?php echo $action['href']; ?>" class="btn btn-success btn-sm xsbtn" data-toggle="tooltip" title="<?php echo $action['text']; ?>" onclick="showOverlay('<?php echo $text_saving; ?>');"><i class="fa fa fa-power-off fa-but-on"></i></a>
                                                                <?php } else { ?>
                                                                    <a href="<?php echo $action['href']; ?>" class="btn btn-danger btn-sm xsbtn" data-toggle="tooltip" title="<?php echo $action['text']; ?>" onclick="showOverlay('<?php echo $text_saving; ?>');"><i class="fa fa fa-power-off fa-but-off"></i></a>
                                                                <?php } ?>
                                                            <?php } ?>                                           
                                                        <?php } ?>
                                                    </td>
                                                    <td class="vqnws text-center">
                                                        <?php if($vqmod['file_name'] == 'vqmod_opencart.xml') { ?>
                                                            <button type="button" class="btn btn-primary btn-sm" disabled><i class="fa fa-pencil fa-lg"></i></button>
                                                            <button type="button" class="btn btn-warning btn-sm" disabled><i class="fa fa-download fa-lg"></i></button>
                                                            <button type="button" class="btn btn-info btn-sm" disabled><i class="fa fa-comment fa-lg"></i></button>
                                                            <button type="button" class="btn btn-danger btn-sm" disabled><i class="fa fa-trash-o fa-lg"></i></button>
                                                        <?php } else { ?>
                                                           <a href="<?php echo $vqmod['edit'] ?>" class="btn btn-primary btn-sm" data-toggle="tooltip" title="<?php echo $button_edit_xml; ?>" onclick="showOverlay('<?php echo $text_ea_loading; ?>');"><i class="fa fa-pencil fa-lg"></i></a>                                                       
                                                            <?php if ($ziparchive) { ?>
                                                                <a href="<?php echo $vqmod['download'] ?>" class="btn btn-warning btn-sm" data-toggle="tooltip" title="<?php echo $button_download_xml; ?>"><i class="fa fa-download fa-lg"></i></a>
                                                            <?php } ?>
                                                            <?php if ($vqmod['comment'] == '') { ?>
                                                            	<a href="<?php echo $vqmod['edit_comment'] ?>" class="btn btn-info btn-sm" data-toggle="tooltip" title="<?php echo $button_add_comment; ?>" id="com_<?php echo $pk++; ?>" data-vqfile="<?php echo $vqmod['file_name']; ?>"><i class="fa fa-comment fa-lg"></i></a>
                                                            <?php } else { ?>
                                                            	<a href="<?php echo $vqmod['edit_comment'] ?>" class="btn btn-success btn-sm" data-toggle="tooltip" title="<?php echo $button_edit_comment; ?>" id="com_<?php echo $pk++; ?>" data-vqfile="<?php echo $vqmod['file_name']; ?>"><i class="fa fa-comment fa-lg"></i></a>
                                                            <?php } ?> 
                                                            <a href="<?php echo $vqmod['delete'] ?>" class="btn btn-danger btn-sm btndel" data-toggle="tooltip" title="<?php echo $button_delete_xml; ?>"><i class="fa fa-trash-o fa-lg"></i></a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            <?php } else { ?>
                                            <tr>
                                                <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>                           
                                </div>
                            </div>                        
    
                            <?php if ($backups) { ?>
                            <div id="tab-backups" class="tab-pane">
                                <p>
                                	<?php echo $text_restore_explain; ?>                                	
                                </p>
                                <div style="clear:both"></div>
                                <div class="table-responsive">
                                    <table data-show-toggle="true" data-toggle="table" id="backupTable">
                                        <thead>
                                            <tr>
                                                <th class="text-left eaback" data-field="name" data-sortable="true"><?php echo $column_id; ?></th>
                                                <th class="text-left eaback" data-field="size" data-sortable="true"><?php echo $column_size; ?></th>
                                                <th class="text-left eaback" data-field="version"><?php echo $column_version; ?></th>
                                                <th class="text-left eaback" data-sortable="true" data-field="backup"><?php echo $column_backup_date; ?></th>
                                                <th class="text-center eaback" data-field="action"><?php echo $column_action; ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php $pk = 1 ?>
                                            <?php foreach ($backups as $backup) { ?>
                                                <tr>
                                                    <td class="vqnws text-left"><strong><?php echo $backup['file_name']; ?></strong><br />
                                                    <div class="vqdescription"><?php echo $backup['id']; ?><br /><?php echo $backup['invalid_xml']; ?></div>                                           
                                                    </td>
                                                    <td class="text-left"><?php echo $backup['file_size']; ?></td>
                                                    <td class="text-left"><?php echo $backup['version']; ?></td>
                                                    <td class="text-left"><?php if ($backup['date_added'] != '01/01/1970 01:00:00') { ?><?php echo $backup['date_added']; ?><?php } ?></td>
                                                    <td class="vqnws text-center">
                                                        <a href="<?php echo $backup['restore'] ?>" class="btn btn-warning btn-sm btnres" data-toggle="tooltip" title="<?php echo $button_restore_xml; ?>"><i class="fa fa-repeat fa-lg"></i></a>
                                                        <a href="<?php echo $backup['delete'] ?>" class="btn btn-danger btn-sm btndel" data-toggle="tooltip" title="<?php echo $button_delete_xml; ?>"><i class="fa fa-trash-o fa-lg"></i></a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>                           
                                </div>
                            </div>
                            <?php } ?>                       
                            
                            <div id="tab-settings" class="tab-pane">
                                <div class="table-responsive"> 
                                    <table data-show-toggle="true" data-toggle="table" id="cacheTable">

                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                        </tr>
                                        </thead>
                                        
                                        <tbody>
                                        <tr>
                                            <td class="text-left"><?php echo $entry_vqcache; ?><br /><span class="vqhelp"><?php echo $text_vqcache_help; ?></span></td>
                                            <td class="text-left">
                                                <select multiple="multiple" size="15" id="vqcache" class="form-control">
                                                    <?php foreach ($vqcache as $vqcache_file) { ?>
                                                    <option><?php echo $vqcache_file; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <a href="<?php echo $clear_vqcache; ?>" class="btn btn-warning" data-toggle="tooltip" title="<?php echo $button_clear; ?>" onclick="showOverlay('<?php echo $text_clearing; ?>');"><i class="fa fa-paint-brush fa-lg">&nbsp;&nbsp;<?php echo $button_clear; ?></i></a>
                                                <?php if ($ziparchive) { ?>
                                                    <a href="<?php echo $download_vqcache; ?>" class="btn btn-primary" data-toggle="tooltip"  title="<?php echo $button_vqcache_dump; ?>"><i class="fa fa-download fa-lg">&nbsp;&nbsp;<?php echo $button_vqcache_dump; ?></i></a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-left"><?php echo $entry_vqmod_path; ?></td>
                                            <td class="text-left"><?php echo $vqmod_path; ?></td>
                                        </tr>
                                        <?php if ($vqmod_vars) { ?>
                                            <?php foreach ($vqmod_vars as $vqmod_var) { ?>
                                            <tr>
                                                <td class="text-left"><?php echo $vqmod_var['setting']; ?></td>
                                                <td class="text-left"><?php echo $vqmod_var['value']; ?></td>
                                            </tr>
                                            <?php } ?>
                                        <?php } ?>
                                        </tbody>
                                    </table>                            
                                </div>
                            </div>
     
                            <div id="tab-error" class="tab-pane">
                                 <div class="table-responsive">
                                     <table class="table table-bordered ea-table-striped ea-list">
                                        <tr>
                                            <td class="text-left"><textarea rows="20" cols="90" id="error-log"><?php echo $vqlog; ?></textarea>
                                                <div class="right"><?php if ($ziparchive) { ?><a href="<?php echo $download_log; ?>" class="btn btn-primary"><i class="fa fa-download fa-lg">&nbsp;&nbsp;<?php echo $button_download_log; ?></i></span></a><?php } ?> <a href="<?php echo $clear_log; ?>" class="btn btn-warning" onclick="showOverlay('<?php echo $text_clearing; ?>');"><i class="fa fa-paint-brush fa-lg">&nbsp;&nbsp;<?php echo $button_clear_logs; ?></i></span></a></div></td>
                                        </tr>
                                    </table>                             
                                 </div>
                            </div>
                        
                        <?php } else { ?>
                		<span><?php echo $vqmod_installation_error; ?></span>
                		<?php } ?>
            		</div>
				</form>
<!-- Content Loader -->
				</div>
 <!-- Content Loader -->       
                <div class="loading-overlay hidden" id="loading-overlay">
                    <div id="vqloader"></div>  
                	<div id="ea-msg"></div>                           
                </div> 
 
        	</div>
        </div>
	</div>
<!-- container-fluid -->

<style>
.eaback {
	background-color: #515151!important;
	color: #f5f5f5!important;
}
.card-view button.xsbtn, .card-view a.xsbtn {margin-bottom:10px!important;}
.fixed-table-toolbar {
	z-index:8;
	position:absolute;
	right:35px;
	margin-top:-45px;
}
#tab-backups .fixed-table-toolbar, #tab-backups .fixed-table-toolbar button,
#tab-settings .fixed-table-toolbar, #tab-settings .fixed-table-toolbar button {
	visibility:hidden!important;
	/*display:none!important;*/
}
#tab-settings table th, #tab-settings .th-inner {
	height:0px!important;
	padding:0!important;
	margin:0!important;
}
@media (max-width: 768px) {
	.fixed-table-toolbar {
		margin-top:-130px;
	}
	#upbtn {
		margin-top:10px;
	}
}
@media (max-width: 736px) {
	.fixed-table-toolbar {
		display:none;
	}
	#upbtn {
		margin-top:10px;
	}
}
</style>


<script src="view/template/enhancement/js/jquery/jquery.stickytableheaders.min.js"></script>
<?php if ($theme_enhancements == 1) { ?>    
<script type="text/javascript"><!--
$(function () {
    var offset = $('#header').height();
	if(($(window).width() > 767)) {
		$("table#xmlTable").stickyTableHeaders({fixedOffset: offset});
		$("table#backupTable").stickyTableHeaders({fixedOffset: offset});
	}
});
//--></script>
<?php } else { ?>
<script type="text/javascript"><!--
$(function () {
	if(($(window).width() > 767)) {
		$("table#xmlTable").stickyTableHeaders({fixedOffset: 0});
		$("table#backupTable").stickyTableHeaders({fixedOffset: 0});
	}
});
//--></script>
<?php } ?>

<script type="text/javascript"><!--
$(document).delegate('a[id^="com_"]', 'click', function(e) {
	e.preventDefault();	
	$('#modal-vqmod-comment').remove();
	var element = $(this);
	var vqmod = $(element).attr('data-vqfile');
	var ajaxUrl = 'index.php?route=enhancement/ea_vqmod_manager/edit_comment&token=<?php echo $token; ?>&vqmod=' + vqmod;
	$.ajax({
		url: ajaxUrl,
		dataType: 'html',
		success: function(html) {
			$('body').append('<div id="modal-vqmod-comment" class="modal modal-wide fade">' + html + '</div>');
			$('#modal-vqmod-comment').modal({backdrop: 'static',keyboard: false});
			$('#modal-vqmod-comment').modal('show');
		}
	});
});
//--></script>

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

var mtab = $(".nav-tabs").get(0);
$(mtab).addClass('ea-main-tabs');
$('.ea-main-tabs a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
  sessionStorage.setItem('lastVqmodTab', $(e.target).attr('href'));
});
var lastTab = sessionStorage.getItem('lastVqmodTab');
if (lastTab) {
	$('a[href="' + lastTab + '"]').tab('show');
}

$(document).ready(function () {
	// Confirm Delete
	$('a.btndel').click(function () {
		if ($(this).attr('href') != null && $(this).attr('href').indexOf('delete', 1) != -1) {
			if (!confirm('<?php echo $warning_vqmod_delete; ?>')) {
				return false;
			}
			showOverlay('<?php echo $text_removeing; ?>');
		}
	});
	// Confirm Restore
	$('a.btnres').click(function () {
		if ($(this).attr('href') != null && $(this).attr('href').indexOf('restore', 1) != -1) {
			if (!confirm('<?php echo $warning_vqmod_restore; ?>')) {
				return false;
			}
			showOverlay('<?php echo $text_restoreing; ?>');
		}
	});
});

$(document).ready(function() {
	if(($(window).width() < 768)) {
		$('button[name="toggle"]').trigger('click');
	}
	getColums();

    $('td.column-added a').editable({
        format: 'YYYY-MM-DD HH:mm:ss',    
        viewformat: 'DD/MM/YYYY HH:mm:ss',    
        template: 'DD / MM / YYYY HH : mm : ss',    
        combodate: {
            minYear: 2017,
            maxYear: 2999,
            minuteStep: 1
        },
		url: 'index.php?route=enhancement/ea_vqmod_manager/edit_date&token=<?php echo $token; ?>',
		placement: 'left',
		success: function(json) {
			if(json['success']) {
				showToast('success', 'toast-top-center', json['success']);
			}
		},			
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
    });
	
	$('.card-view a.cadd').editable({
        format: 'YYYY-MM-DD HH:mm:ss',     
        viewformat: 'DD/MM/YYYY HH:mm:ss',    
        template: 'DD<br />MM<br />YYYY<br />HH<br />mm<br />ss',    
        combodate: {
            minYear: 2017,
            maxYear: 2999,
            minuteStep: 1
        },
		url: 'index.php?route=enhancement/ea_vqmod_manager/edit_date&token=<?php echo $token; ?>',
		placement: 'bottom',
		success: function(json) {
			if(json['success']) {
				showToast('success', 'toast-top-center', json['success']);
			}
			if(json['error']) {
				showToast('error', 'toast-top-center', json['error']);
			}
		},			
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
    });
});

/*$.mockjax({
    url: '/post',
    responseTime: 200
});*/

function getColums() {
	$("input.column-toggler").each(function() {
		var column = 'column_'+$(this).attr("data-field");
		
		if (localStorage.getItem(column) == 0) {
			$(this).removeAttr("checked");
			$("td.column-" + $(this).attr("data-field")).hide(0);
			$("th.column-" + $(this).attr("data-field")).hide(0);
        } else {
			$(this).attr("checked", true);
			$("td.column-" + $(this).attr("data-field")).show(0);
			$("th.column-" + $(this).attr("data-field")).show(0);
        }
		
		$(this).on("click", function() {
			if ($(this).is(":checked") === true) {
				$("td.column-" + $(this).attr("data-field")).show(200);
				$("th.column-" + $(this).attr("data-field")).show(200);
				var value = 1;
        		localStorage.setItem(column,value);	
				getColums();
			} else {
				$(this).removeAttr("checked");
				$("td.column-" + $(this).attr("data-field")).hide(200);
				$("th.column-" + $(this).attr("data-field")).hide(200);
				var value = 0;
        		localStorage.setItem(column,value);
				getColums();
			}			
		});
	});
}

$('#tabs a:first').tab('show');
//--></script>

<?php if ($vqlog) { ?>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('a[href=\'#tab-error\']').trigger('click');
});
//--></script>
<?php } ?>

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

</div> 
<!--content --> 
<?php echo $footer; ?>