<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Партнерский кабинет");

CUtil::InitJSCore(array('jquery', 'window', 'popup', 'ajax', 'date'));
$APPLICATION->AddHeadScript('/partners/local/func.js');

$rsUser = CUser::GetByID($USER->GetID());
$UserID = $USER->GetID();
//echo "--".$USER->GetID()."++";
//$arUser = $rsUser->Fetch();
//echo $arUser["LOGIN"]);

if(!isset($UserID)) {
	echo "<font size='20'><b>Доступ запрещен</b></font >";
}

if(CModule::IncludeModule('iblock') AND isset($UserID)) {
    $arSortPartner= Array("NAME"=>"ASC");
    $arSelectPartner = Array("ID","NAME", "PROPERTY_OPERATOR");
    $arFilterPartner = Array("IBLOCK_ID" => 22, "PROPERTY_OPERATOR" => $USER->GetID());
 
    $resPartner =  CIBlockElement::GetList($arSortPartner, $arFilterPartner, false, false, $arSelectPartner);

	if (intval($resPartner->SelectedRowsCount() == 0)){ // Если  пользователь не является оператором ни у одного партнера
		echo "<font size='20'><b>Доступ запрещен</b></font >";
	} elseif (intval($resPartner->SelectedRowsCount() == 1)) { // Если  пользователь является оператором у одного партнера
		$obPartner = $resPartner->GetNextElement();
		$arFieldsPartner = $obPartner->GetFields();

		$IDPartner = $arFieldsPartner['ID'];
		if(CModule::IncludeModule('iblock')) {
            $arSortProduct= Array("NAME"=>"ASC");
            $arSelectProduct = Array("ID","NAME", "PROPERTY_PARTNER","ACTIVE");
            $arFilterProduct = Array("IBLOCK_ID" => 23, "PROPERTY_PARTNER" => $IDPartner); //'ACTIVE' => 'Y'
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
			

		}
    } else { // Если  пользователь является оператором у нескольких партнеров

		$resPartner ->NavStart(10);
		echo "<font size=3'>".$resPartner ->NavPrint("Партнеры")."</font>";
		
        while($resPartner ->NavNext(true, "f_")):
			echo "<br><font size=6'><a href='./product.php?idpar=".$f_ID."'><b>".$f_NAME."</b></a></font><br>";
        endwhile;

	}
}
?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>