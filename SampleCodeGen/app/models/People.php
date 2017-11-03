<?php
	/**
	* =========================================================
	* CLASSNAME:        people
	* DATE CREATED:  	25-10-2017
	* FOR TABLE:  		people
	* AUTHOR:			Hezecom Technology Solutions LTD.
	* CONTACT:			http://hezecom.com <info@hezecom.com>
	* =========================================================
	*/
	
	namespace App\Model;
	use App\Lib\HDB;
	
	class People{
		
	private $hts;
    private $table = 'people';
    
	function __construct()
    {
        $this->hts = HDB::hus();
    }
	
	/**
	 * SELECT FOR DATATABLE
     * @param $columns = list of table fields to display
     * @param $editlink = link to edit page
     * @return array
     */
    public function SelectTableData($columns, $editlink)
    {
        $fieldsvars = implode(', ', $columns);
        $sqldata = "SELECT $fieldsvars FROM $this->table";
        $sqlcount = "SELECT COUNT(*) AS ntotal FROM $this->table";
        return $this->hts->HTSdataTable($sqldata,$sqlcount,$columns,$editlink,"people");
    }
	
	/*
     * SELECT MULTIPLE RECORD WITH LIMIT
     * @param $limit
     * @return array|bool|mixed
     */
    public function SelectAll($limit = 10000)
    {
        return $this->hts->HTSselect("$this->table ORDER BY id DESC LIMIT $limit");
    }
    
    /*
     * SELECT ONE RECORD
     * @param $id
     * @return array|bool|mixed
     */
    public function SelectOne($id)
    {
        $bind = array(":id" =>$id);
        return $this->hts->HTSselect("$this->table WHERE id=:id",$bind,1);
    }
	
	/*
	* DELETE RECORD
	* @param $id
	* @return array|bool|mixed
	*/
	public function Delete($id)
	{
	$bind = array(":id" =>$id);
	return $this->hts->Hdelete("$this->table","id=:id",$bind);
	
	}
	
	/*
	* SELECT MULTIPLE FILES
	* @param $id
    * @return array|bool|mixed
	*/
	public function MultiDelete($ids)
	{
	return $this->hts->Hcustom('DELETE FROM $this->table WHERE id IN ('.$ids.')');
	}
	
	
	/*
	* INSERT
	*/
	public function Insert($name,$gender,$date_registered,$others)
	{
	$newupload = new HTSUploadControl;
	$uploadname=$newupload->ImageUplaodResize('picture',THUMB_IMAGE_WIDTH,BIG_IMAGE_WIDTH,UPLOAD_PATH,THUMB_PATH,90);
	if($uploadname===false){
	$values = array(array( 'name'=>$name,'gender'=>$gender,'date_registered'=>$date_registered,'others'=>$others ));
	}else{
	$values = array(array( 'picture'=>$uploadname,'name'=>$name,'gender'=>$gender,'date_registered'=>$date_registered,'others'=>$others ));
	}
	$this->hts->Hinsert($this->table, $values);
	}
	
	/*
	* UPDATE
	*/
	public function Update($name,$gender,$date_registered,$others,$id)
	{
	$newupload = new HTSUploadControl;
	$uploadname=$newupload->ImageUplaodResize('picture',THUMB_IMAGE_WIDTH,BIG_IMAGE_WIDTH,UPLOAD_PATH,THUMB_PATH,90);
	$where = array('id' => $id);
	if($uploadname===false){
	$values = array('name'=>$name,'gender'=>$gender,'date_registered'=>$date_registered,'others'=>$others);
	}else{
	$values = array('picture'=>$uploadname, 'name'=>$name,'gender'=>$gender,'date_registered'=>$date_registered,'others'=>$others);
	}
	$this->hts->HTSupdate($this->table,$values,$where);
	
	}
	
	
	} /*end class*/
	
	