<?php

/**
 * Panels
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Panels extends BasePanels
{
	
	/**
	 * grid
	 * create the configuration of the grid
	 */	
	public static function grid($rowNum = 10) {
		
		$translator = Shineisp_Registry::getInstance ()->Zend_Translate;
		
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Name' ), 'field' => 'p.name', 'alias' => 'name', 'sortable' => true, 'searchable' => true, 'type' => 'string' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Active' ), 'field' => 'p.active', 'alias' => 'active', 'type' => 'index');
		
		$config ['datagrid'] ['fields'] = "panel_id, p.name as name, p.active as active";
		$config ['datagrid'] ['dqrecordset'] = Doctrine_Query::create ()->select ( $config ['datagrid'] ['fields'] )->from ( 'Panels p' )->leftJoin ( 'p.Isp i' );
		
		$config ['datagrid'] ['rownum'] = $rowNum;
		
		$config ['datagrid'] ['basepath'] = "/admin/panels/";
		$config ['datagrid'] ['index'] = "panel_id";
		$config ['datagrid'] ['rowlist'] = array ('10', '50', '100', '1000' );
		
		$config ['datagrid'] ['buttons'] ['edit'] ['label'] = $translator->translate ( 'Edit' );
		$config ['datagrid'] ['buttons'] ['edit'] ['cssicon'] = "edit";
		$config ['datagrid'] ['buttons'] ['edit'] ['action'] = "/admin/panels/edit/id/%d";
		
		$config ['datagrid'] ['buttons'] ['delete'] ['label'] = $translator->translate ( 'Delete' );
		$config ['datagrid'] ['buttons'] ['delete'] ['cssicon'] = "delete";
		$config ['datagrid'] ['buttons'] ['delete'] ['action'] = "/admin/panels/delete/id/%d";
		$config ['datagrid'] ['massactions'] = array ('massdelete'=>'Mass Delete', 'bulkexport'=>'Export' );
		return $config;
	}
	
	
	/**
	 * Get a record by ID
	 * @param $id
	 * @return Doctrine Record
	 */
	public static function find($id) {
		return Doctrine::getTable ( 'Panels' )->findOneBy ( 'panel_id', $id );
	}

	/**
	 * Get a record by isp_id
	 * @param $id
	 * @return Doctrine Record
	 */
	public static function findByIspId($isp_id) {
		$isp_id = intval($isp_id);
		return Doctrine::getTable ( 'Panels' )->findOneBy ( 'isp_id', $isp_id );
	}
	

	/**
	 * save all the custom parameters 
	 */
	public static function saveAll($parameters){
	
		if(!empty($parameters['panel_id']) && is_numeric($parameters['panel_id'])){
			$panel = Doctrine::getTable ( 'Panels' )->find($parameters['panel_id']);
		}else{
			$panel = new Panels();
		}
		
		$panel['name'] = $parameters['name'];
		$panel['isp_id'] = $parameters['isp_id'];
		$panel['active'] = ($parameters['active'] == 1) ? 1 : 0;
		$panel->save();
		
		return  $panel['panel_id'];
	}
	
	/**
	 * get all the custom parameter fields 
	 */
	public static function getParameters($panelId, $form){
		$parametersForm = new Zend_Form_SubForm ();
		
		// Get the list field
		$fields = Doctrine::getTable ( 'Panels' )->find($panelId, Doctrine_Core::HYDRATE_ARRAY );
		
		$parameters = !empty($fields['params']) ? json_decode($fields['params'], true) : array();  
		
		// Set the decorator
		$parametersForm->addElementPrefixPath('Shineisp_Decorator', 'Shineisp/Decorator/', 'decorator');
		
		foreach ($parameters as $field => $value) {
			
			// Create the custom field 
			$parametersForm->addElement('text', $field, array(
	            'filters'    => array('StringTrim'),
	            'label'      => $field,
	            'decorators' => array('Composite'),
	            'class'      => 'text-input large-input',
	            'value'		 => $value
	        ));
		}

		// Add the subform 
		$form->addSubForm ( $parametersForm, 'parameters' );
		
		return $form;
	}
	
	/**
	 * save all the custom parameter fields 
	 */
	public static function saveParameterValues(array $params, $panel_id){
		
		foreach ($params['parameters'] as $field => $value){
			$parameters[$field] = $value;
		}
		
		if(!empty($parameters)){
			$panel = Doctrine::getTable ( 'Panels' )->find($panel_id);
			$panel['params'] = json_encode($parameters);
			$panel->save();
		}
	}
	
	/**
	 * Get all data using the ID 
	 * @param $id
	 * @param $fields
	 * @return ArrayObject
	 */
	public static function getAllInfo($id, $fields = "*") {
		$record = Doctrine_Query::create ()->select ( $fields )->from ( 'Panels p' )
										->leftJoin ( 'p.Isp i' )
										->where ( "panel_id = ?", $id )
										->limit ( 1 )
										->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
										
		return !empty($record[0]) ? $record[0] : array();
		
	}	
		
	/**
	 * Get all data using the name 
	 * @param $panelName
	 * @param $fields
	 * @return ArrayObject
	 */
	public static function getAllInfoByName($panelName, $fields = "*") {
		$record = Doctrine_Query::create ()->select ( $fields )->from ( 'Panels p' )
										->leftJoin ( 'p.Isp i' )
										->where ( "name = ?", $panelName )
										->limit ( 1 )
										->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
										
		return !empty($record[0]) ? $record[0] : array();
		
	}	
		
		
	/**
	 * Get the config panel variables 
	 * @param string $panelvar
	 * @return ArrayObject
	 */
	public static function getActivePanel() {
		// Get the Active ISP Panel set at the Isp Profile page
		$strpanel = Isp::getPanel();
		if(!empty($strpanel)){
			$panel = Doctrine_Query::create ()->from ( 'Panels' )
											->where ( "name = ?", $strpanel )
											->limit ( 1 )
											->execute (array (), Doctrine::HYDRATE_ARRAY);
											
			return !empty($panel[0]) ? $panel[0] : array();
		}
		
		return false;
	}
	
	/**
	 * Get the config panel variables 
	 * @param string $panelvar
	 * @return ArrayObject
	 */
	public static function getConfig($panelvar) {
		$panel = Doctrine_Query::create ()->from ( 'Panels' )
											->where ( "name = ?", $panelvar )
											->limit ( 1 )
											->execute (array (), Doctrine::HYDRATE_ARRAY);
											
		return !empty($panel[0]) ? $panel[0] : array();
	}
	
	/**
	 * Create and Set as active the isp panels
	 * @param unknown_type $panelvar
	 */
	public static function setAsActive($panelvar, $ispId) {
		
		
		// Disable all the control panels
		Doctrine_Query::create ()->update ( 'Panels' )
					->set ( 'active', '?', 0 )
					->execute ();
		
		if(!empty($panelvar)){
			
			$isppanel = Doctrine_Query::create ()->from ( 'Panels' )
												->where ( "name = ?", $panelvar )
												->limit ( 1 )
												->execute (array (), Doctrine::HYDRATE_ARRAY);
	
			// ISP Panel module has not been created yet
			if(empty($isppanel)){
				$panel = new Panels();
				$path = PROJECT_PATH . "/library/Shineisp/Plugins/Panels/$panelvar";
				if(!empty($panelvar)){
					$config = simplexml_load_file ( $path . "/config.xml" );
					
					if($config){
						$conf = array();
						$attributes = $config->attributes();
		
						// create the params array
						foreach ($config->configuration->children() as $field => $value) {
							$conf[$field] = ( string ) $value;
						}
		
						// Save the configuration
						$panel['name'] = $attributes['name'];
						$panel['isp_id'] = $ispId;
						$panel['params'] = json_encode($conf);
						$panel['active'] = 0;
						$panel->save();
					}
				}
			}
			
			// Enable only the set isp panel in the ISP Profile		
			return Doctrine_Query::create ()->update ( 'Panels' )
					->set ( 'active', '?', 1 )
					->where ( 'name = ?', $panelvar )
					->execute ();
		}
	}
	
	/**
	 * Get the list of all the panels installed
	 */
	public static function getPanelInstalled() {
		$panels[] = "";
		$path = PROJECT_PATH . "/library/Shineisp/Plugins/Panels";
		
		$folderPanels = glob ( "$path/*", GLOB_ONLYDIR );
		
		foreach ( $folderPanels as $sSubDir ) {
			$confFile = $sSubDir . "/config.xml";
			if (file_exists ( $confFile )) {
				$config = simplexml_load_file ( $confFile );
				if (! empty ( $config->attributes ()->var )) {
					$var = ( string ) $config->attributes ()->var;
					$panelname = ( string ) $config->attributes ()->name;
					$panels[$var] = $panelname;
				}
			}
		}
		return $panels;
	}
	
	/**
	 * Get the list of the records ready for the select object
	 *
	 * @param boolean $emptyitem
	 * @return multitype:string unknown
	 */
	public static function getList($emptyitem=false) {
		$items = array ();
		if($emptyitem){
			$items[] = "";
		}
	
		$arrTypes = Doctrine::getTable ( 'Panels' )->findAll ();
		foreach ( $arrTypes->getData () as $c ) {
			$items [$c ['panel_id']] = $c ['name'];
		}
		return $items;
	}
	
	/**
	 * Get the list of the records ready for the select object
	 * This returns only installed panels
	 *
	 * @param boolean $emptyitem
	 * @return multitype:string unknown
	 */
	public static function getListInstalled($emptyitem=false) {
		$path = PROJECT_PATH . "/library/Shineisp/Plugins/Panels";
		$folderPanels = glob ( "$path/*", GLOB_ONLYDIR );
		$items = array ();

		if($emptyitem){
			$items[0] = "";
		}
	
		if ( empty($folderPanels) ) {
			return $items;
		}
	
		$arrTypes = Doctrine::getTable ( 'Panels' )->findAll ();
		foreach ( $arrTypes->getData () as $c ) {
			// Check if panel is installed (config file present)
			foreach ( $folderPanels as $panelDir ) {
				$confFile = $panelDir . "/config.xml";
				if (file_exists ( $confFile )) {
					$config = simplexml_load_file ( $confFile );
					if (! empty ( $config->attributes ()->var )) {
						$items [$c ['panel_id']] = $c ['name'];	
					}	
				}
			}
		}
		
		return $items;
	}
	


	/**
	 * Get a field by shineisp product attribute
	 * 
	 * 
	 * @param $attribute
	 * @return string
	 */
	public static function getXmlFieldbyAttribute($panel, $attribute) {
		$path = PROJECT_PATH . "/library/Shineisp/Plugins/Panels/$panel";
		if (file_exists ( $path . "/config.xml" )) {
			
			// Load the xml configuration file
			$config = simplexml_load_file ( $path . "/config.xml" );
			
			// For each field in matchfield xml node
			foreach ( $config->matchfields->hosting_attributes->children () as $node ) {
				$attrs = $node->attributes ();
				$panelAttribute = ( string ) $node;
				
				// Check if the attribute is empty and match if it is what we are looking for
				if (! empty ( $panelAttribute ) && $panelAttribute == $attribute && ! empty ( $node )) {
					$result ['field'] = $panelAttribute;
					$result ['label'] = ( string ) $attrs ['label'];
					$result ['default'] = ( string ) $attrs ['default'];
					$result ['type'] = ( string ) $attrs ['type'];
					return $result;
				}
			}
		}
		return false;
	}	

	/**
	 * Get all the items set in the config.xml
	 * for the activated ISP Panel
	 * 
	 * 
	 * @param $panel
	 * @see ProfileController
	 * @return array options
	 */
	public static function getOptionsXmlFields($panel) {
		$options = array();
		if(!empty($panel)){
			$options[] = "";
			$panelattrs = self::getXmlFields(Isp::getPanel());
			foreach ($panelattrs as $attribute) {
				$options[$attribute['field']] = $attribute['field']; 
			}
		}
		return $options;
	}
	
	/**
	 * Get all the matched fields by shineisp product attribute
	 * 
	 * 
	 * @param $panel
	 * @return string
	 */
	public static function getXmlFields($panel) {
		$i = 0;
		$path = PROJECT_PATH . "/library/Shineisp/Plugins/Panels/$panel";
		
		$result = array();
		if (file_exists ( $path . "/config.xml" )) {
		
			// Load the xml configuration file
			$config = simplexml_load_file ( $path . "/config.xml" );

			// For each field in matchfield xml node
			foreach ( $config->matchfields->hosting_attributes->children () as $node ) {
				$attrs = $node->attributes ();
				
				// Check if the attribute is empty and match if it is what we are looking for
				if ( ! empty ( $node )) {
					$result [$i]['field'] = ( string ) $node;
					$result [$i]['default'] = ( string ) $attrs ['default'];
					$result [$i]['type'] = ( string ) $attrs ['type'];
					$i++;
				}
			}
		}
		return $result;
	}

	/**
	 * Get just the panel var in the config.xml configuration file
	 * 
	 * 
	 * @param array $attributes --> ShineISP System Product Attributes
	 * @param array $internalAttr --> ShineISP System Product Attribute
	 * 
	 * @return mixed null or string
	 */
	public static function getVar($panel, array $attributes, $internalAttr) {
		// Loop of system product attributes
		foreach ( $attributes as $attribute => $value ) {

			// Get the saved system attribute
			$sysAttribute = ProductsAttributes::getAttributebyCode($attribute);
			
			if(!empty($sysAttribute[0]['system_var'])){
				$sysVariable = $sysAttribute[0]['system_var'];
				
				// Get the system product attribute
				$modAttribute = self::getXmlFieldbyAttribute ( $panel, $sysVariable );
				
				if(!empty($modAttribute ['field'])){
					return $modAttribute['field'];
				}
			}
		}
		return null;
	}
	
	
	
	
	/**
	 * Get all the customfields for this module
	 * 
	 * 
	 * @param $panel
	 * @return string
	 */
	public static function getXmlCustomFields($panel) {
		$i = 0;
		$path = PROJECT_PATH . "/library/Shineisp/Plugins/Panels/$panel";
		
		$result = array();
		if (file_exists ( $path . "/config.xml" )) {
		
			// Load the xml configuration file
			$config = simplexml_load_file ( $path . "/config.xml" );

			// For each field in matchfield xml node
			foreach ( $config->customfields->field as $node ) {
				$result[] = (string)$node;
			}
		}
		return $result;
	}
	
	
	
	
	
	
	
	
}