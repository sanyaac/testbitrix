<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Продукты");


CUtil::InitJSCore(array('jquery', 'window', 'popup', 'ajax', 'date'));
$APPLICATION->AddHeadScript('/partners/local/func.js');


// ID текущего пользователя
$UserID = $USER->GetID();


if(!isset($UserID)) {
	echo "<font size='20'><b>Доступ запрещен</b></font >";
}

//Проверем, что ID товара передан, ID - целое число и что пользователь залогинился
if (isset($_GET["idpro"]) AND is_numeric($_GET["idpro"])  AND isset($UserID)){
	
	if(CModule::IncludeModule('iblock')) {
        $arSortProduct= Array("NAME"=>"ASC");
        $arSelectProduct = Array("ID","NAME", "PROPERTY_PARTNER");
        $arFilterProduct = Array("IBLOCK_ID" => 23, "ID" => $_GET["idpro"]);
	    $nameButtonActive = "";
 
        $resProduct =  CIBlockElement::GetList($arSortProduct, $arFilterProduct, false, false, $arSelectProduct);
		$obProduct = $resProduct->GetNextElement();
		$arFieldsProduct = $obProduct->GetFields();
		$IDPartner = $arFieldsProduct['PROPERTY_PARTNER_VALUE'];
		$NameProduct = $arFieldsProduct['NAME'];

		$arSortPartner= Array("NAME"=>"ASC");
        $arSelectPartner = Array("ID","NAME", "PROPERTY_CONTENT", "PROPERTY_DELIVERY_CONDITION", "PROPERTY_OPERATOR");
        $arFilterPartner = Array("IBLOCK_ID" => 22, "ID" => $IDPartner);
 
        $resPartner =  CIBlockElement::GetList($arSortPartner, $arFilterPartner, false, false, $arSelectPartner);
		$obPartner = $resPartner->GetNextElement();
		$arFieldsPartner = $obPartner->GetFields();
		
		// Проверка на то, что пользователь является оператором партнера этого товара
		if ($UserID == $arFieldsPartner["PROPERTY_OPERATOR_VALUE"]){
		
		    //Вывод таблицы с информацие о товаре
		    echo "<table width='700' border='0' align='left' cellpadding='0' cellspacing='0'>
		            <tr><td><font size=4'>Наименование товара:</font>
				        <td width='480'><font size=4'>".$NameProduct."</font>
		            <tr><td><font size=4'>Наименование товара:</font>
				        <td><font size=4'>".$arFieldsPartner["NAME"]."</font>
		            <tr><td><font size=4'>Описание:</font>
				        <td><font size=4'>".$arFieldsPartner["PROPERTY_CONTENT_VALUE"]."</font>
		            <tr><td><font size=4'>Условия доставки:</font>
				        <td><font size=4'>".$arFieldsPartner["PROPERTY_DELIVERY_CONDITION_VALUE"]."</font>
			      </table><br>";
		} else {
			echo "<font size='20'><b>Доступ запрещен</b></font >";
		}
	}
}
//Проверем, что ID партнера передан, ID - целое число и что пользователь залогинился
if (isset($_GET["idpar"]) AND is_numeric($_GET["idpar"]) AND isset($UserID)){
	
	if(CModule::IncludeModule('iblock')) {
		
		$arSortPartner= Array("NAME"=>"ASC");
        $arSelectPartner = Array("ID","PROPERTY_OPERATOR");
        $arFilterPartner = Array("IBLOCK_ID" => 22, "ID" => $_GET["idpar"]);
		$resPartner =  CIBlockElement::GetList($arSortPartner, $arFilterPartner, false, false, $arSelectPartner);
		$obPartner = $resPartner->GetNextElement();
		$arFieldsPartner = $obPartner->GetFields();
		
		// Проверка на то, что пользователь является оператором партнера этого товара
		if ($UserID == $arFieldsPartner["PROPERTY_OPERATOR_VALUE"]){
            $arSortProduct= Array("NAME"=>"ASC");
            $arSelectProduct = Array("ID","NAME", "PROPERTY_PARTNER","ACTIVE");
            $arFilterProduct = Array("IBLOCK_ID" => 23, "PROPERTY_PARTNER" => $_GET["idpar"]);
	        $nameButtonActive = "";
 
            $resProduct =  CIBlockElement::GetList($arSortProduct, $arFilterProduct, false, false, $arSelectProduct);
			
	        $resProduct->NavStart(3);
			
			//Вывод таблицы с товарами
	        echo "<table width='500' border='0' align='left' cellpadding='0' cellspacing='0'>";
            while($resProduct->NavNext(true, "f_")):
			    if ($f_ACTIVE == 'Y'){
				    $nameButtonActive = "Деактивировать";
			    } else {
				    $nameButtonActive = "Активировать";
			    }
			    echo "<tr><td><font size=6'><a href='./product.php?idpro=".$f_ID."'><b>".$f_NAME."</b></a></font>
			              <td><input type='button' id='a_button".$f_ID."' style = 'width: 120' value='".$nameButtonActive."' onclick='sendCntAjax(".$f_ID.");'/></tr>";
            endwhile;
	        echo "<tr><td><font size=3'>".$resProduct->NavPrint("Товары")."</font></tr>";
            echo "</table>";

	    } else {
			echo "<font size='20'><b>Доступ запрещен</b></font >";
		}
	}
}

?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>