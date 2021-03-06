<?php

/**
 * CreditNotesItems
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ShineISP
 * 
 * @author     Shine Software <info@shineisp.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class CreditNotesItems extends BaseCreditNotesItems
{
	/**
     * Get a doctrine record by ID
     * 
     * 
     * @param $id
     * @return Doctrine Record
     */
    public static function find_by_creditnoteid($id) {
        return Doctrine::getTable ( 'CreditNotesItems' )->findOneBy ( 'creditnote_id', $id );
    }
    

    /**
     * Get all the credit notes
     * 
     * @return ArrayObject
     */
    public static function getDetails($creditnoteid) {
        $records = Doctrine_Query::create ()->from ( 'CreditNotesItems cn' )
      									  ->where('cn.creditnote_id = ?', $creditnoteid)
      									  ->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );

		return $records;
    }

    /**
     * Get credit note by credit note Identifier
     * 
     * @return ArrayObject
     */
    public static function get_all($creditnoteitemid) {
        $records = Doctrine_Query::create ()->from ( 'CreditNotesItems cni' )
        								  ->leftJoin('cni.CreditNotes cn ')
      									  ->where('cni.creditnoteitem_id = ?', $creditnoteitemid)
      									  ->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );

		return !empty($records[0]) ? $records[0] : array();
    }
    
    /**
     * Delete an credit note item using its ID
     * 
     * 
     * @param $id
     * @return boolean
     */
    public static function DeleteByID($id) {
    	if(is_numeric($id)){
	        return Doctrine_Query::create ()->delete ()->from ( 'CreditNotesItems' )->where ( 'creditnoteitem_id = ?', $id )->execute ();
    	}
        return false;
    }    
}