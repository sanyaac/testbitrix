<?
header("Content-Type: text/html; charset=windows-1251");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");

if(isset($_POST["ID"]) AND is_numeric($_POST["ID"])){
	$ID = $_POST["ID"];
	$result = "";
	
	
	$arSortProduct= Array("NAME"=>"ASC");
    $arSelectProduct = Array("ID","ACTIVE");
    $arFilterProduct = Array("IBLOCK_ID" => 23, "ID" => $ID);
 
    $resProduct =  CIBlockElement::GetList($arSortProduct, $arFilterProduct, false, false, $arSelectProduct);
	$obProduct = $resProduct->GetNextElement();
	$arFieldsProduct = $obProduct->GetFields();
	$ACTIVE = $arFieldsProduct['ACTIVE'];

	if ($ACTIVE == "Y"){
		$ACTIVE = "N";
		$result = "Активировать";
	} else {
		$ACTIVE = "Y";
		$result = "Деактивировать";
	}
	
	$obEl = new CIBlockElement();
    $boolResult = $obEl->Update($ID,array('ACTIVE' => $ACTIVE));
	
	echo $result;
}
?>