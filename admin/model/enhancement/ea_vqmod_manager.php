<?php
class ModelEnhancementEaVqmodManager extends Model {
	public function installVqmodTable() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "ea_vqmod_manager`");
		$this->db->query("CREATE TABLE `" . DB_PREFIX . "ea_vqmod_manager` (`id` int(11) NOT NULL AUTO_INCREMENT, `xml_name` varchar(255) COLLATE utf8_general_ci NOT NULL DEFAULT '', `date_added` datetime NOT NULL, `date_modified` datetime NOT NULL, `date_backup` datetime NOT NULL, `comment` text COLLATE utf8_general_ci NOT NULL, `email` varchar(96) COLLATE utf8_general_ci NOT NULL, `link` varchar(255) COLLATE utf8_general_ci NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");				
		return true;
	}
	
	public function addVqmod($xml_name) {
		$name = str_replace("xml_", "xml", $xml_name);
		$this->db->query("INSERT INTO `" . DB_PREFIX . "ea_vqmod_manager` SET `xml_name` = '" . $this->db->escape($name) . "', `date_added` = NOW(), `date_modified` = '1970-01-01 01:00:00', `date_backup` = '1970-01-01 01:00:00'");		
	}
	
	public function updateVqmod($xml_name) {
		$this->db->query("UPDATE `" . DB_PREFIX . "ea_vqmod_manager` SET `date_modified` = NOW() WHERE `xml_name` = '" . $this->db->escape($xml_name) . "'");
	}
	
	public function updateDate($xml_name,$date_added) {
		$this->db->query("UPDATE `" . DB_PREFIX . "ea_vqmod_manager` SET `date_added` = '" . $this->db->escape($date_added) . "' WHERE `xml_name` = '" . $this->db->escape($xml_name) . "'");
	}
	
	public function updateDateBackup($xml_name,$date_backup) {
		$this->db->query("UPDATE `" . DB_PREFIX . "ea_vqmod_manager` SET `date_backup` = '" . $this->db->escape($date_backup) . "' WHERE `xml_name` = '" . $this->db->escape($xml_name) . "'");
	}
	
	public function updateComment($xml_name,$comment) {
		$this->db->query("UPDATE `" . DB_PREFIX . "ea_vqmod_manager` SET `comment` = '" . $this->db->escape($comment) . "' WHERE `xml_name` = '" . $this->db->escape($xml_name) . "'");
	}
	
	public function updateEmail($xml_name,$email) {
		$this->db->query("UPDATE `" . DB_PREFIX . "ea_vqmod_manager` SET `email` = '" . $this->db->escape($email) . "' WHERE `xml_name` = '" . $this->db->escape($xml_name) . "'");
	}
	
	public function updateLink($xml_name,$link) {
		$this->db->query("UPDATE `" . DB_PREFIX . "ea_vqmod_manager` SET `link` = '" . $this->db->escape($link) . "' WHERE `xml_name` = '" . $this->db->escape($xml_name) . "'");
	}	
	
	public function deleteVqmod($xml_name) {
		$name = str_replace("xml_", "xml", $xml_name);
		$this->db->query("DELETE FROM `" . DB_PREFIX . "ea_vqmod_manager` WHERE `xml_name` = '" . $this->db->escape($name) . "'");
	}
	
	public function getDateAdded($xml_name) {
		$name = str_replace("xml_", "xml", $xml_name);
		$query = $this->db->query("SELECT `date_added` FROM `" . DB_PREFIX . "ea_vqmod_manager` WHERE `xml_name` = '" . $this->db->escape($name) . "'");
		return $query->row;
	}
	
	public function getDateModified($xml_name) {
		$name = str_replace("xml_", "xml", $xml_name);
		$query = $this->db->query("SELECT `date_modified` FROM `" . DB_PREFIX . "ea_vqmod_manager` WHERE `xml_name` = '" . $this->db->escape($name) . "'");
		return $query->row;
	}
	
	public function getDateBackup($xml_name) {
		$name = str_replace("xml_", "xml", $xml_name);
		$query = $this->db->query("SELECT `date_backup` FROM `" . DB_PREFIX . "ea_vqmod_manager` WHERE `xml_name` = '" . $this->db->escape($name) . "'");
		return $query->row;
	}
	
	public function getComment($xml_name) {
		$name = str_replace("xml_", "xml", $xml_name);
		$query = $this->db->query("SELECT `comment` FROM `" . DB_PREFIX . "ea_vqmod_manager` WHERE `xml_name` = '" . $this->db->escape($name) . "'");
		return $query->row;
	}
	
	public function getEmail($xml_name) {
		$name = str_replace("xml_", "xml", $xml_name);
		$query = $this->db->query("SELECT `email` FROM `" . DB_PREFIX . "ea_vqmod_manager` WHERE `xml_name` = '" . $this->db->escape($name) . "'");
		return $query->row;
	}
	
	public function getLink($xml_name) {
		$name = str_replace("xml_", "xml", $xml_name);
		$query = $this->db->query("SELECT `link` FROM `" . DB_PREFIX . "ea_vqmod_manager` WHERE `xml_name` = '" . $this->db->escape($name) . "'");
		return $query->row;
	}
}