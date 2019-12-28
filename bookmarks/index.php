<?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle(false);

$assets = Bitrix\Main\Page\Asset::getInstance();
$assets->addCss('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css');

?>
<?$APPLICATION->IncludeComponent(
	"custom:bookmarks", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"HL_ID" => "4",
		"ELEMENT_COUNT" => "5"
	),
	false
);?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>