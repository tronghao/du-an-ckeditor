<?php
class ControllerEnhancementEaVqmodManager extends Controller {
	private $error = array();

	public function __construct($registry) {
		parent::__construct($registry);

		// Paths and Files
		$this->base_dir = substr_replace(DIR_SYSTEM, '/', -8);
		$this->vqmod_dir = substr_replace(DIR_SYSTEM, '/vqmod/', -8);
		$this->vqmod_script_dir = substr_replace(DIR_SYSTEM, '/vqmod/xml/', -8);
		$this->vqcache_dir = substr_replace(DIR_SYSTEM, '/vqmod/vqcache/', -8);
		$this->vqcache_files = substr_replace(DIR_SYSTEM, '/vqmod/vqcache/vq*', -8);
		$this->vqmod_logs_dir = substr_replace(DIR_SYSTEM, '/vqmod/logs/', -8);
		$this->vqmod_logs = substr_replace(DIR_SYSTEM, '/vqmod/logs/*.log', -8);
		$this->vqmod_modcache = substr_replace(DIR_SYSTEM, '/vqmod/mods.cache', -8);
		$this->vqmod_opencart_script = substr_replace(DIR_SYSTEM, '/vqmod/xml/vqmod_opencart.xml', -8);
		$this->vqmod_path_replaces = substr_replace(DIR_SYSTEM, '/vqmod/pathReplaces.php', -8);
		$this->oc_logs_dir = substr_replace(DIR_SYSTEM, '/system/storage/logs/', -8);
		$this->vqmod_backup_dir = substr_replace(DIR_SYSTEM, '/vqmod/xml/backups/', -8);

		clearstatcache();
	}

	public function index() {
		$language_data = $this->load->language('enhancement/ea_vqmod_manager');
		foreach($language_data as $key=>$value){
			$data[$key] = $value;
		}
		
		$this->document->addStyle('view/template/enhancement/js/bootstrap/css/bootstrap-table.min.css');
		$this->document->addScript('view/template/enhancement/js/bootstrap/js/bootstrap-table.min.js');

		$this->document->addScript('view/template/enhancement/js/bootstrap/bootstrap-filestyle.min.js');
		
		$this->document->addStyle('view/template/enhancement/js/bootstrap/css/bootstrap-editable.css');
		$this->document->addScript('view/template/enhancement/js/bootstrap/js/bootstrap-editable.min.js');		
		
		$this->document->addStyle('view/template/enhancement/js/toastr/toastr.min.css');	
		$this->document->addScript('view/template/enhancement/js/toastr/toastr.min.js');
		
		$this->document->addScript('view/template/enhancement/js/jquery/combodate.js');
		
		$this->document->addStyle('view/template/enhancement/css/enhanced_vqmod.css');
		
		$this->document->setTitle($this->language->get('heading_title_vqmods'));

		$this->load->model('enhancement/ea_vqmod_manager');
		$this->load->model('setting/setting');
			
		$query = $this->db->query("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "ea_vqmod_manager'");
		if (!$query->rows) {
			$this->model_enhancement_ea_vqmod_manager->installVqmodTable();
		}

		if(!file_exists($this->vqmod_backup_dir)) {
			mkdir($this->vqmod_backup_dir, 0755, true);
		}
		
		$this->getList();
	}

	public function getList() {
		$data = array();
		// Language
		$language_data = $this->load->language('enhancement/ea_vqmod_manager');
		foreach($language_data as $key=>$value){
			$data[$key] = $value;
		}	

		// Check that VQMod is properly installed in store
		if ($this->vqmod_installation_check()) {
			$data['vqmod_is_installed'] = true;
		} else {
			$data['vqmod_is_installed'] = false;
		}

		// VQMod installation errors
		if (isset($this->session->data['vqmod_installation_error'])) {
			$data['vqmod_installation_error'] = $this->session->data['vqmod_installation_error'];

			unset($this->session->data['vqmod_installation_error']);
		} else {
			$data['vqmod_installation_error'] = '';
		}

		// Warning
		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} else {
			$data['error_warning'] = '';
		}

		// Success
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		// Breadcrumbs
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_bcrumb_vqmods'),
			'href' => $this->url->link('enhancement/ea_vqmod_manager', 'token=' . $this->session->data['token'], true)
		);

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		// Action Buttons
		$data['action'] = $this->url->link('enhancement/ea_vqmod_manager', 'token=' . $this->session->data['token'], true);
		$data['clear_log'] = $this->url->link('enhancement/ea_vqmod_manager/clear_log', 'token=' . $this->session->data['token'], true);
		$data['clear_vqcache'] = $this->url->link('enhancement/ea_vqmod_manager/clear_vqcache', 'token=' . $this->session->data['token'], true);
		$data['download_log'] = $this->url->link('enhancement/ea_vqmod_manager/download_log', 'token=' . $this->session->data['token'], true);
		$data['download_scripts'] = $this->url->link('enhancement/ea_vqmod_manager/download_vqmod_scripts', 'token=' . $this->session->data['token'], true);
		$data['download_vqcache'] = $this->url->link('enhancement/ea_vqmod_manager/download_vqcache', 'token=' . $this->session->data['token'], true);
		$data['upload_vqmod'] = $this->url->link('enhancement/ea_vqmod_manager/vqmod_upload', 'token=' . $this->session->data['token'], true);			
		
		$data['text_saving'] = $this->language->get('text_saving');
		$data['text_generating'] = $this->language->get('text_generating');
		$data['text_removeing'] = $this->language->get('text_removeing');
		$data['text_restoreing'] = $this->language->get('text_restoreing');
		$data['text_clearing'] = $this->language->get('text_clearing');
		$data['text_uploading'] = $this->language->get('text_uploading');

		$data['vqlog'] = '';
		
		// Check ZipArchive for use with downloads
		if (class_exists('ZipArchive')) {
			$data['ziparchive'] = true;
		} else {
			$data['ziparchive'] = false;
		}

		// Detect scripts
		$vqmod_scripts = $this->list_vqmod_scripts();

		$data['vqmods'] = array();

		if (!empty($vqmod_scripts)) {
			foreach ($vqmod_scripts as $vqmod_script) {
				$extension = pathinfo($vqmod_script, PATHINFO_EXTENSION);

				if ($extension == 'xml_') {
					$file = basename($vqmod_script, '.xml_');
				} else {
					$file = basename($vqmod_script, '.xml');
				}

				$action = array();

				if ($extension == 'xml_') {
					$action[] = array(
						'text' => $this->language->get('text_install'),
						'href' => $this->url->link('enhancement/ea_vqmod_manager/vqmod_install', 'token=' . $this->session->data['token'] . '&vqmod=' . $file, true)
					);
				} else {
					$action[] = array(
						'text' => $this->language->get('text_uninstall'),
						'href' => $this->url->link('enhancement/ea_vqmod_manager/vqmod_uninstall', 'token=' . $this->session->data['token'] . '&vqmod=' . $file, true)
					);
				}

				libxml_use_internal_errors(true);
				$xml = simplexml_load_file($vqmod_script);

				if (libxml_get_errors()) {
					$invalid_xml = sprintf($this->language->get('highlight-error'), $this->language->get('error_invalid_xml'));
					libxml_clear_errors();
				} else {
					$invalid_xml = '';
				}
				
				$date_added_result = $this->model_enhancement_ea_vqmod_manager->getDateAdded(basename($vqmod_script, ''));
				
				if (!$date_added_result) {
					$date_added = 'N/A';
				} else {
					$date_added = date($this->language->get('datetime_format'), strtotime($date_added_result['date_added']));
				}
				
				$date_modified_result = $this->model_enhancement_ea_vqmod_manager->getDateModified(basename($vqmod_script, ''));
				
				if (!$date_modified_result) {
					$date_modified = 'N/A';
				} else {
					$date_modified = date($this->language->get('datetime_format'), strtotime($date_modified_result['date_modified']));
				}				
				
				$comment_result = $this->model_enhancement_ea_vqmod_manager->getComment(basename($vqmod_script, ''));
				
				if (!$comment_result) {
					$xml_comment = '';
				} else {
					$xml_comment = $comment_result['comment'];
				}

				$data['vqmods'][] = array(
					'file_name'   	=> basename($vqmod_script, ''),
					'file_size'   	=> $this->formatbytes($vqmod_script, "KB"),
					'file_ext'    	=> $extension,
					'id'          	=> isset($xml->id) ? $xml->id : $this->language->get('text_unavailable'),
					'version'     	=> isset($xml->version) ? $xml->version : $this->language->get('text_unavailable'),
					'vqmver'      	=> isset($xml->vqmver) ? $xml->vqmver : $this->language->get('text_unavailable'),
					'author'      	=> isset($xml->author) ? $xml->author : $this->language->get('text_unavailable'),
					'status'     	=> $extension == 'xml_' ? sprintf($this->language->get('text_disabled2')) : $this->language->get('text_enabled2'),
					'installed'   	=> $extension == 'xml_' ? sprintf($this->language->get('text_disabled')) : $this->language->get('text_enabled'),
					'comment'   	=> $xml_comment,
					'edit_comment'  => $this->url->link('enhancement/ea_vqmod_manager/edit_comment', 'token=' . $this->session->data['token'] . '&vqmod=' . basename($vqmod_script), true),
					'edit'    	  	=> $this->url->link('enhancement/ea_vqmod_manager/edit_vqmod_script', 'token=' . $this->session->data['token'] . '&vqmod=' . basename($vqmod_script), true),
					'download'    	=> $this->url->link('enhancement/ea_vqmod_manager/download_vqmod_script', 'token=' . $this->session->data['token'] . '&vqmod=' . basename($vqmod_script), true),
					'delete'      	=> $this->url->link('enhancement/ea_vqmod_manager/vqmod_delete', 'token=' . $this->session->data['token'] . '&vqmod=' . basename($vqmod_script), true),
					'action'      	=> $action,
					'invalid_xml' 	=> $invalid_xml,
					'date_added'  	=> $date_added,
					'date_modified' => $date_modified,
				);
			}
		}
		
		// Detect backup scripts
		$backup_scripts = $this->list_backup_scripts();

		$data['backups'] = array();

		if (!empty($backup_scripts)) {
			foreach ($backup_scripts as $backup_script) {
				$fileinfo = pathinfo(basename($backup_script));
				$filename = $fileinfo['filename'];
				$filename = substr($filename, 0, -20);

				$date_backup_result = $this->model_enhancement_ea_vqmod_manager->getDateBackup(basename($backup_script, ''));
				
				$file = basename($backup_script);
				$fname = $fileinfo['filename'];
				
				if (!$date_backup_result) {
					$filedate = substr($fname, -20);
					$filedate = str_replace('_','/',$filedate);
					$filedate = substr($filedate, 1);
					$filedate = str_replace('-',':',$filedate);
					$filedate = str_replace('@',' ',$filedate);
				} else {
					$filedate = date($this->language->get('datetime_format'), strtotime($date_backup_result['date_backup']));
				}

				$action = array();

				libxml_use_internal_errors(true);
				$xml = simplexml_load_file($backup_script);

				if (libxml_get_errors()) {
					$invalid_xml = sprintf($this->language->get('highlight-error'), $this->language->get('error_invalid_xml'));
					libxml_clear_errors();
				} else {
					$invalid_xml = '';
				}

				$data['backups'][] = array(
					'file_name'   => $filename.'.xml',
					'file_size'   => $this->formatbytes($backup_script, "KB"),
					'id'          => isset($xml->id) ? $xml->id : $this->language->get('text_unavailable'),
					'version'     => isset($xml->version) ? $xml->version : $this->language->get('text_unavailable'),
					'restore'     => $this->url->link('enhancement/ea_vqmod_manager/backup_restore', 'token=' . $this->session->data['token'] . '&vqmod=' . basename($backup_script), true),
					'delete'      => $this->url->link('enhancement/ea_vqmod_manager/backup_delete', 'token=' . $this->session->data['token'] . '&vqmod=' . basename($backup_script), true),
					'invalid_xml' => $invalid_xml,
					'date_added'  => $filedate
				);
			}
		}

		// VQCache files
		$data['vqcache'] = array();

		if (is_dir($this->vqcache_dir)) {
			$data['vqcache'] = array_diff(scandir($this->vqcache_dir), array('.', '..'));
		}

		// VQMod Error Log
		if (is_dir($this->vqmod_logs_dir) && is_readable($this->vqmod_logs_dir)) {
			// VQMod 2.2.0 and later logs
			$vqmod_logs = glob($this->vqmod_logs);
			$vqmod_logs_size = 0;

			if (!empty($vqmod_logs)) {
				foreach ($vqmod_logs as $vqmod_log) {
					$vqmod_logs_size += filesize($vqmod_log);
				}

				// Error if log files are larger than 6MB combined
				if ($vqmod_logs_size > 6291456) {
					$data['error_warning'] = sprintf($this->language->get('error_log_size'), round(($vqmod_logs_size / 1048576), 2));
					$data['vqlog'] = sprintf($this->language->get('error_log_size'), round(($vqmod_logs_size / 1048576), 2));
				} else {
					foreach ($vqmod_logs as $vqmod_log) {
						$data['vqlog'] .= str_pad(basename($vqmod_log), 70, '*', STR_PAD_BOTH) . "\n";
						$data['vqlog'] .= file_get_contents($vqmod_log, FILE_USE_INCLUDE_PATH, null);
					}
				}
			}
		} elseif (is_file($this->vqmod_log) && filesize($this->vqmod_log) > 0) {
			$data['vqlog'] = file_get_contents($this->vqmod_log, FILE_USE_INCLUDE_PATH, null);
		}

		// VQMod Path
		if (is_dir($this->vqmod_dir)) {
			$data['vqmod_path'] = $this->vqmod_dir;
		} else {
			$data['vqmod_path'] = '';
		}

		// VQMod class variables
		$vqmod_vars = get_class_vars('VQMod');

		$data['vqmod_vars'] = array();

		if ($vqmod_vars) {
			foreach ($vqmod_vars as $setting => $value) {
				if ($setting == 'protectedFilelist' && is_file($this->base_dir . $value)) {
					$protected_files = file_get_contents($this->base_dir . $value);

					if (!empty($protected_files)) {
						$protected_files = preg_replace('~\r?\n~', "\n", $protected_files);

						$paths = explode("\n", $protected_files);

						$data['vqmod_vars'][] = array(
							'setting' => $this->language->get('setting_protected_files'),
							'value'   => implode('<br />', $paths)
						);
					}
				}
			}
		}

		// Path Replacements - VQMod 2.3.0
		if (is_file($this->vqmod_path_replaces)) {
			if (!in_array('pathReplaces.php', get_included_files())) {
				include_once($this->vqmod_path_replaces);
			}

			if (!empty($replaces)) {
				$replacement_values = array();

				foreach ($replaces as $key => $value) {
					$replacement_values[] = $value[0] . $this->language->get('text_separator') . $value[1];
				}

				$data['vqmod_vars'][] = array(
					'setting' => $this->language->get('setting_path_replaces'),
					'value'   => implode('<br />', $replacement_values)
				);
			}
		}
		
		$data['token'] = $this->session->data['token'];
		
		$data['theme_enhancements'] = $this->config->get('ea_tools_theme_enhancements');
        
		// Template
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('enhancement/ea_vqmod_manager', $data));		
	}

	protected function getForm($vqmod_script) {		
		$language_data = $this->load->language('enhancement/ea_vqmod_manager');
		foreach($language_data as $key=>$value){
			$data[$key] = $value;
		}
		
		$this->document->addStyle('view/template/enhancement/js/codemirror/lib/codemirror.css');
		$this->document->addStyle('view/template/enhancement/js/codemirror/theme/xq-dark.css');
		$this->document->addScript('view/template/enhancement/js/codemirror/lib/codemirror.js');
		$this->document->addScript('view/template/enhancement/js/codemirror/mode/xml/xml.js');
		$this->document->addScript('view/template/enhancement/js/codemirror/addon/selection/active-line.js');
		
		$this->document->addStyle('view/template/enhancement/js/toastr/toastr.min.css');	
		$this->document->addScript('view/template/enhancement/js/toastr/toastr.min.js');
		
		$this->document->setTitle($this->language->get('heading_title_vqmods'));
		
		if (isset($this->request->post['vqmod_file'])) {
			file_put_contents($this->vqmod_script_dir.$vqmod_script, html_entity_decode($this->request->post['vqmod_file']));
			$vqmod_source_code = $this->request->post['vqmod_file'];
			$this->load->model('enhancement/ea_vqmod_manager');
			$this->model_enhancement_ea_vqmod_manager->updateVqmod($vqmod_script);
			$this->session->data['success'] = $this->language->get('success_modified');
		} else {
			$this->backup_vqmod_script($vqmod_script, date('Y-m-d h:i:s'));
			$vqmod_source_code = '';
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['xml'])) {
			$data['error_xml'] = $this->error['xml'];
		} else {
			$data['error_xml'] = '';
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_bcrumb_edit'),
			'href' => html_entity_decode($this->url->link('enhancement/ea_vqmod_manager', 'token=' . $this->session->data['token'] . '&vqmod=' . $vqmod_script, true))
		);

		$data['action'] = html_entity_decode($this->url->link('enhancement/ea_vqmod_manager/edit_vqmod_script', 'token=' . $this->session->data['token'] . '&vqmod=' . $vqmod_script, true));
		
		$data['return'] = $this->url->link('enhancement/ea_vqmod_manager', 'token=' . $this->session->data['token'], true);
		
		$data['vqmod_xml'] = file_get_contents($this->vqmod_script_dir.$vqmod_script);		
		
		$data['token'] = $this->session->data['token'];
		$data['text_edit_vqmod'] = sprintf($this->language->get('text_edit_vqmod'), $vqmod_script);
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('enhancement/ea_vqmod_manager_form', $data));
	}

	protected function getFormComment($vqmod_script) {		
		$language_data = $this->load->language('enhancement/ea_vqmod_manager');
		foreach($language_data as $key=>$value){
			$data[$key] = $value;
		}
		
		$this->document->setTitle($this->language->get('heading_title_vqmods'));
		
		$this->load->model('enhancement/ea_vqmod_manager');
		
		if (isset($this->request->post['vqmod_comment'])) {
			$this->model_enhancement_ea_vqmod_manager->updateComment($vqmod_script,$this->request->post['vqmod_comment']);
			if (isset($this->request->post['vqmod_email'])) {
				$this->model_enhancement_ea_vqmod_manager->updateEmail($vqmod_script,$this->request->post['vqmod_email']);
			}
			if (isset($this->request->post['vqmod_link'])) {
				$this->model_enhancement_ea_vqmod_manager->updateLink($vqmod_script,$this->request->post['vqmod_link']);
			}
			$this->session->data['success'] = $this->language->get('success_saved_comment');
			$this->response->redirect($this->url->link('enhancement/ea_vqmod_manager', 'token=' . $this->session->data['token'], true));
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['action'] = html_entity_decode($this->url->link('enhancement/ea_vqmod_manager/edit_comment', 'token=' . $this->session->data['token'] . '&vqmod=' . $vqmod_script, true));
		
		$this->load->model('enhancement/ea_vqmod_manager');
		$comment_result = $this->model_enhancement_ea_vqmod_manager->getComment($vqmod_script);
		$email_result = $this->model_enhancement_ea_vqmod_manager->getEmail($vqmod_script);
		$link_result = $this->model_enhancement_ea_vqmod_manager->getLink($vqmod_script);
		
		$data['vqmod_comment'] = $comment_result['comment'];
		$data['vqmod_email'] = $email_result['email'];	
		$data['vqmod_link'] = $link_result['link'];	
		
		$data['token'] = $this->session->data['token'];
		$data['text_edit_comment'] = sprintf($this->language->get('text_edit_comment'), $vqmod_script);
		
		$this->response->setOutput($this->load->view('enhancement/ea_vqmod_manager_form_comment', $data));
	}

	public function formatbytes($file, $type) {
		switch($type){
      		case "KB":
         		$filesize = filesize($file) * .0009765625; // bytes to KB
      			break;
      		case "MB":
         		$filesize = (filesize($file) * .0009765625) * .0009765625; // bytes to MB
      			break;
		}
   
		if($filesize <= 0) {
      		return $filesize = 'unknown file size';
		} else {
			return round($filesize, 2);
		}
	}

	public function vqmod_install() {
		if ($this->userPermission()) {
			$vqmod_script = $this->request->get['vqmod'];

			if (is_file($this->vqmod_script_dir . $vqmod_script . '.xml_')) {
				rename($this->vqmod_script_dir . $vqmod_script . '.xml_', $this->vqmod_script_dir . $vqmod_script . '.xml');
				
				$this->load->model('enhancement/ea_vqmod_manager');
				$this->model_enhancement_ea_vqmod_manager->updateVqmod($vqmod_script.'.xml');

				$this->clear_vqcache(true);
				$this->session->data['success'] = $this->language->get('success_install');
			} else {
				$this->session->data['error'] = $this->language->get('error_install');

			}
		}

		$this->response->redirect($this->url->link('enhancement/ea_vqmod_manager', 'token=' . $this->session->data['token'], true));
	}

	public function vqmod_uninstall() {
		if ($this->userPermission()) {
			$vqmod_script = $this->request->get['vqmod'];

			if (is_file($this->vqmod_script_dir . $vqmod_script . '.xml')) {
				rename($this->vqmod_script_dir . $vqmod_script . '.xml', $this->vqmod_script_dir . $vqmod_script . '.xml_');

				$this->clear_vqcache(true);

				$this->session->data['success'] = $this->language->get('success_uninstall');
			} else {
				$this->session->data['error'] = $this->language->get('error_uninstall');
			}
		}

		$this->response->redirect($this->url->link('enhancement/ea_vqmod_manager', 'token=' . $this->session->data['token'], true));
	}

	public function vqmod_upload() {
		$language_data = $this->load->language('enhancement/ea_vqmod_manager');
		foreach($language_data as $key=>$value){
			$data[$key] = $value;
		}

		if ($this->userPermission()) {
			$file = $this->request->files['vqmod_file']['tmp_name'];
			$file_name = $this->request->files['vqmod_file']['name'];

			if ($this->request->files['vqmod_file']['error'] > 0) {
				switch($this->request->files['vqmod_file']['error']) {
					case 1:
						$this->session->data['error'] = $this->language->get('error_ini_max_file_size');
						break;
					case 2:
						$this->session->data['error'] = $this->language->get('error_form_max_file_size');
						break;
					case 3:
						$this->session->data['error'] = $this->language->get('error_partial_upload');
						break;
					case 4:
						$this->session->data['error'] = $this->language->get('error_no_upload');
						break;
					case 6:
						$this->session->data['error'] = $this->language->get('error_no_temp_dir');
						break;
					case 7:
						$this->session->data['error'] = $this->language->get('error_write_fail');
						break;
					case 8:
						$this->session->data['error'] = $this->language->get('error_php_conflict');
						break;
					default:
						$this->session->data['error'] = $this->language->get('error_unknown');
				}

			} else {
				if ($this->request->files['vqmod_file']['type'] != 'text/xml') {
					$this->session->data['error'] = $this->language->get('error_filetype');
				} else {
					libxml_use_internal_errors(true);
					simplexml_load_file($file);

					if (libxml_get_errors()) {
						$this->session->data['error'] = $this->language->get('error_invalid_xml');
						libxml_clear_errors();
					} elseif (move_uploaded_file($file, $this->vqmod_script_dir . $file_name) === false) {
						$this->session->data['error'] = $this->language->get('error_move');
					} else {
						$this->clear_vqcache(true);
						
						$this->load->model('enhancement/ea_vqmod_manager');
						$this->model_enhancement_ea_vqmod_manager->addVqmod($file_name);
						$this->session->data['success'] = $this->language->get('success_upload');
					}
				}
			}
		}

		$this->response->redirect($this->url->link('enhancement/ea_vqmod_manager', 'token=' . $this->session->data['token'], true));
	}

	public function vqmod_delete() {
		if ($this->userPermission()) {
			$vqmod_script = $this->request->get['vqmod'];

			if (is_file($this->vqmod_script_dir . $vqmod_script)) {
				if (unlink($this->vqmod_script_dir . $vqmod_script)) {
					$this->clear_vqcache(true);
					
					$this->load->model('enhancement/ea_vqmod_manager');
					$this->model_enhancement_ea_vqmod_manager->deleteVqmod($vqmod_script);
					$this->session->data['success'] = $this->language->get('success_delete');
				} else {
					$this->session->data['error'] = $this->language->get('error_delete');
				}
			}
		}

		$this->response->redirect($this->url->link('enhancement/ea_vqmod_manager', 'token=' . $this->session->data['token'], true));
	}

	public function clear_vqcache($return = false) {
		if ($this->userPermission()) {
			$files = glob($this->vqcache_files);

			if ($files) {
				foreach ($files as $file) {
					if (is_file($file)) {
						unlink($file);
					}
				}
			}

			if (is_file($this->vqmod_modcache)) {
				unlink($this->vqmod_modcache);
			}

			if ($return) {
				return;
			}

			$this->session->data['success'] = $this->language->get('success_clear_vqcache');
		}

		$this->response->redirect($this->url->link('enhancement/ea_vqmod_manager', 'token=' . $this->session->data['token'], true));
	}

	public function clear_log() {
		if ($this->userPermission()) {
			if (is_dir($this->vqmod_logs_dir)) {
				$files = glob($this->vqmod_logs);

				if ($files) {
					foreach ($files as $file) {
						unlink($file);
					}
				}
			}
			
			$this->session->data['success'] = $this->language->get('success_clear_log');
		}

		$this->response->redirect($this->url->link('enhancement/ea_vqmod_manager', 'token=' . $this->session->data['token'], true));
	}

	private function list_vqmod_scripts() {
		$vqmod_scripts = array();

		if ($this->userPermission('access')) {
			$enabled_vqmod_scripts = glob($this->vqmod_script_dir . '*.xml');
			$disabled_vqmod_scripts = glob($this->vqmod_script_dir . '*.xml_');
			
			$this->load->model('enhancement/ea_vqmod_manager');

			if (!empty($enabled_vqmod_scripts)) {			
				foreach ($enabled_vqmod_scripts as $vqmod_script) {
					$date_added_result = $this->model_enhancement_ea_vqmod_manager->getDateAdded(basename($vqmod_script, ''));
					if (!$date_added_result) {
						$this->model_enhancement_ea_vqmod_manager->addVqmod(basename($vqmod_script, ''));
					}
				}
				$vqmod_scripts = array_merge($vqmod_scripts, $enabled_vqmod_scripts);
			}

			if (!empty($disabled_vqmod_scripts)) {
				foreach ($disabled_vqmod_scripts as $vqmod_script) {
					$date_added_result = $this->model_enhancement_ea_vqmod_manager->getDateAdded(basename($vqmod_script, ''));
					if (!$date_added_result) {
						$this->model_enhancement_ea_vqmod_manager->addVqmod(basename($vqmod_script, ''));
					}
				}
				$vqmod_scripts = array_merge($vqmod_scripts, $disabled_vqmod_scripts);
			}
		}

		return $vqmod_scripts;
	}

	public function edit_comment() {
		if ($this->userPermission()) {
			$vqmod_script = $this->request->get['vqmod'];
			$this->getFormComment($vqmod_script);	
		} else {
			$this->response->redirect($this->url->link('enhancement/ea_vqmod_manager', 'token=' . $this->session->data['token'], true));
		}
	}

	public function edit_vqmod_script() {
		if ($this->userPermission()) {
			$vqmod_script = $this->request->get['vqmod'];
			$this->getForm($vqmod_script);	
		} else {
			$this->response->redirect($this->url->link('enhancement/ea_vqmod_manager', 'token=' . $this->session->data['token'], true));
		}
	}

	private function list_backup_scripts() {
		$backup_scripts = array();

		if ($this->userPermission('access')) {
			$backuped_vqmod_scripts = glob($this->vqmod_backup_dir . '*.xml');
			$backup_scripts = array_merge($backup_scripts, $backuped_vqmod_scripts);
		}


		return $backup_scripts;
	}

	public function backup_vqmod_script($vqmod_script,$date_backup) {
		$target = $this->vqmod_script_dir.$vqmod_script;
		$info = pathinfo(basename($target));
		$filename = $info['filename'];		
		$filedate = date('d_m_Y'.'@'.'h-i-s', strtotime($date_backup));
		$destination = $this->vqmod_backup_dir.$filename.'_'.$filedate.'.xml';
		$this->load->model('enhancement/ea_vqmod_manager');
		$this->model_enhancement_ea_vqmod_manager->updateDateBackup($filename.'.xml', $date_backup);
		copy($target,$destination);
	}

	public function backup_delete() {
		if ($this->userPermission()) {
			$backup_script = $this->request->get['vqmod'];

			if (is_file($this->vqmod_backup_dir . $backup_script)) {
				if (unlink($this->vqmod_backup_dir . $backup_script)) {
					$this->clear_vqcache(true);
					$this->session->data['success'] = $this->language->get('success_delete');
				} else {
					$this->session->data['error'] = $this->language->get('error_delete');
				}
			}
		}

		$this->response->redirect($this->url->link('enhancement/ea_vqmod_manager', 'token=' . $this->session->data['token'], true));
	}	
	
	public function backup_delete_multi() {
		$language_data = $this->load->language('enhancement/ea_vqmod_manager');
		foreach($language_data as $key=>$value){
			$data[$key] = $value;
		}

		$this->load->model('enhancement/ea_vqmod_manager');

		if (isset($this->request->post['selected']) && $this->userPermission()) {
			foreach ($this->request->post['selected'] as $xml_name) {
				$bkdate = $this->model_enhancement_ea_vqmod_manager->getDateBackup($xml_name);

				if (is_file($this->vqmod_backup_dir . $xml_name.date('d_m_Y'.'@'.'h-i-s', strtotime($bkdate['date_backup'])).'.xml')) {
					unlink($this->vqmod_backup_dir . $xml_name.date('d_m_Y'.'@'.'h-i-s', strtotime($bkdate['date_backup'])).'.xml');
				}
			}

			$this->session->data['success'] = $this->language->get('success_backups_delete');
		}

		$this->getList();
	}

	public function backup_restore() {
		if ($this->userPermission()) {
			$backup_script = $this->request->get['vqmod'];
			$filename = substr($backup_script, 0, -24);
			
			if (is_file($this->vqmod_script_dir . $filename.'.xml')) {
				if (unlink($this->vqmod_script_dir . $filename.'.xml')) {					
					copy($this->vqmod_backup_dir . $backup_script, $this->vqmod_script_dir . $filename.'.xml');
					$this->clear_vqcache(true);
					$this->session->data['success'] = $this->language->get('success_xml_restored');
				} else {
					$this->session->data['error'] = $this->language->get('error_restore');
				}
			} elseif (is_file($this->vqmod_script_dir . $filename.'.xml_')) {
				if (unlink($this->vqmod_script_dir . $filename.'.xml_')) {
					copy($this->vqmod_backup_dir . $backup_script, $this->vqmod_script_dir . $filename.'.xml');
					$this->clear_vqcache(true);
					$this->session->data['success'] = $this->language->get('success_xml_restored');
				} else {
					$this->session->data['error'] = $this->language->get('error_restore');
				}
			}
		}

		$this->response->redirect($this->url->link('enhancement/ea_vqmod_manager', 'token=' . $this->session->data['token'], true));		
	}

	public function edit_date() {
		$language_data = $this->load->language('enhancement/ea_vqmod_manager');
		foreach($language_data as $key=>$value){
			$data[$key] = $value;
		}
		
		$json = array();

		$this->load->model('enhancement/ea_vqmod_manager');
		
		if (isset($_POST['value']) && $this->userPermission()) {
			$this->model_enhancement_ea_vqmod_manager->updateDate($_POST['name'], $_POST['value']);
			$json['success'] = $this->language->get('success_date_changed');
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
    }

	public function download_vqmod_script() {
		if ($this->userPermission()) {
			$target = $this->vqmod_script_dir.$this->request->get['vqmod'];
			$info = pathinfo( basename($target) );
			$filename = $info['filename'];
			$this->script_zip_send($target, $filename);
		} else {
			$this->response->redirect($this->url->link('enhancement/ea_vqmod_manager', 'token=' . $this->session->data['token'], true));
		}
	}

	public function download_vqmod_scripts() {
		if ($this->userPermission()) {
			$targets = $this->list_vqmod_scripts();

			$this->zip_send($targets, 'vqmod_scripts_backup');
		} else {
			$this->response->redirect($this->url->link('enhancement/ea_vqmod_manager', 'token=' . $this->session->data['token'], true));
		}
	}

	public function download_vqcache() {
		if ($this->userPermission()) {
			$targets = glob($this->vqcache_files);

			if (is_file($this->vqmod_modcache)) {
				$targets[] = $this->vqmod_modcache;
			}

			$this->zip_send($targets, 'vqcache_dump');
		} else {
			$this->response->redirect($this->url->link('enhancement/ea_vqmod_manager', 'token=' . $this->session->data['token'], true));
		}
	}

	private function script_zip_send($target, $filename = 'download') {
		$temp = tempnam('tmp', 'zip');

		$zip = new ZipArchive();
		$zip->open($temp, ZipArchive::OVERWRITE);

		if (is_file($target)) {
			$zip->addFile($target, basename($target));
		}

		$zip->close();

		header('Pragma: public');
		header('Expires: 0');
		header('Content-Description: File Transfer');
		header('Content-Type: application/zip');
		header('Content-Disposition: attachment; filename=' . $filename . '.zip');
		header("Content-Length: " . filesize($temp));
		header('Content-Transfer-Encoding: binary');
		readfile($temp);
		unlink($temp);
	}

	private function zip_send($targets, $filename = 'download') {
		$temp = tempnam('tmp', 'zip');

		$zip = new ZipArchive();
		$zip->open($temp, ZipArchive::OVERWRITE);

		foreach ($targets as $target) {
			if (is_file($target)) {
				$zip->addFile($target, basename($target));
			}
		}

		$zip->close();

		header('Pragma: public');
		header('Expires: 0');
		header('Content-Description: File Transfer');
		header('Content-Type: application/zip');
		header('Content-Disposition: attachment; filename=' . $filename . '_' . date('d-m-Y_H-i') . '.zip');		
		header("Content-Length: " . filesize($temp));
		header('Content-Transfer-Encoding: binary');
		readfile($temp);
		unlink($temp);
	}

	private function vqmod_installation_check() {
		// Check SimpleXML for VQMod Manager use
		if (!function_exists('simplexml_load_file')) {
			$this->session->data['vqmod_installation_error'] = $this->language->get('error_simplexml');
			return false;
		}

		// Check if /vqmod directory exists
		if (!is_dir($this->vqmod_dir)) {
			$this->session->data['vqmod_installation_error'] = $this->language->get('error_vqmod_dir');
			return false;
		}

		// Check if vqmod.php exists
		if (!is_file($this->vqmod_dir . 'vqmod.php')) {
			$this->session->data['vqmod_installation_error'] = $this->language->get('error_vqmod_core');
			return false;
		}

		// Check if /vqmod/xml directory exists
		if (!is_dir($this->vqmod_script_dir)) {
			$this->session->data['vqmod_installation_error'] = $this->language->get('error_vqmod_script_dir');
			return false;
		}

		// Check if /vqmod/vqcache directory exists
		if (!is_dir($this->vqcache_dir)) {
			$this->session->data['vqmod_installation_error'] = $this->language->get('error_vqcache_dir');
			return false;
		}

		// Check that vqmod_opencart.xml exists
		if (!is_file($this->vqmod_opencart_script)) {
			if (is_file($this->vqmod_opencart_script . '_')) {
				$this->session->data['error'] = $this->language->get('error_opencart_xml_disabled');
			} else {
				$this->session->data['vqmod_installation_error'] = $this->language->get('error_opencart_xml');
				return false;
			}
		}

		// Check that OpenCart 2.x is being used
		if (!defined('VERSION') || version_compare(VERSION, '2.0.0.0', '<')) {
			$this->session->data['vqmod_installation_error'] = $this->language->get('error_opencart_version');
			return false;
		}

		// Check if VQMod class is added to OpenCart
		if (!class_exists('VQMod')) {
			if (is_file($this->vqmod_dir . 'install/index.php') && is_file($this->vqmod_dir . 'install/ugrsr.class.php')) {
				$this->session->data['vqmod_installation_error'] = sprintf($this->language->get('error_vqmod_install_link'), HTTP_CATALOG . 'vqmod/install');
			} else {
				$this->session->data['vqmod_installation_error'] = $this->language->get('error_vqmod_opencart_integration');
			}
			return false;
		}

		// Check VQMod Error Log Writing
		if ((is_dir($this->vqmod_logs_dir) && !is_writable($this->vqmod_logs_dir)) || (!is_writable($this->vqmod_dir))) {
			$this->session->data['vqmod_installation_error'] = $this->language->get('error_error_log_write');
			return false;
		}

		// Check VQMod Script Writing
		if (!is_writable($this->vqmod_script_dir)) {
			$this->session->data['vqmod_installation_error'] = $this->language->get('error_vqmod_script_write');
			return false;
		}

		// Check VQCache Writing
		if (!is_writable($this->vqcache_dir)) {
			$this->session->data['vqmod_installation_error'] = $this->language->get('error_vqcache_write');
			return false;
		}

		// vqcache files         
		$vqcache_files = array(
			'vq2-system_startup.php'
		);
               
		foreach ($vqcache_files as $vqcache_file) {
			// Only return false if vqmod_opencart.xml_ isn't present (in case the user has disabled it) so they aren't locked out of VQMM
			if (!is_file($this->vqcache_dir . $vqcache_file) && !is_file($this->vqmod_opencart_script . '_')) {
				$this->session->data['vqmod_installation_error'] = $this->language->get('error_vqcache_files_missing');
				return false;
			}
		}

		return true;
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'enhancement/ea_vqmod_manager')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['vqmod_comment']) {
			$this->error['comment'] = $this->language->get('error_comment');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	private function userPermission($permission = 'modify') {
		$this->load->language('enhancement/ea_vqmod_manager');

		if (!$this->user->hasPermission($permission, 'enhancement/ea_vqmod_manager')) {
			$this->session->data['error'] = $this->language->get('error_permission');
			return false;
		} else {
			return true;
		}
	}
}
?>
