<?php
/**
 * Enter description here...
 *
 */
class search_modSearch extends Efy_DB_Connection {	
//Lap trinh lay du lieu	
	public function _searchGetAll($userId, $assetId, $componentId, $textsearch, $piPage, $piNumberRecordPerPage, $permission = 0){
		$sql = "Exec qlts_Search_GetAll ";
		$sql = $sql . "'"  . $userId . "'";
		$sql = $sql . ",'" . $assetId . "'";
		$sql = $sql . ",'" . $componentId . "'";
		$sql = $sql . ",'" . $textsearch . "'";
		$sql = $sql . ","  . $piPage;
		$sql = $sql . ","  . $piNumberRecordPerPage;
		$sql = $sql . ","  . $permission;
		//echo $sql; 
		try{
			$arrAsset = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrAsset;
	}
	public function _assetComponetGetAll($assetid){
		$arrResult = null;
		$sql = "Exec [qlts_Search_Component_GetAll] ";
		$sql .= "'" . $assetid . "'";
		//echo $sql;
		try{
			$arrResult = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
	public function _assetUserGetAll($assetid){
		$arrResult = null;
		$sql = "Exec [qlts_Search_User_GetAll] ";
		$sql .= "'" . $assetid . "'";
		//echo '<br>' . $sql . '<br>';
		try{
			$arrResult = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
}