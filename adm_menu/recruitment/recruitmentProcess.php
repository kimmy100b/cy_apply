<?
include $_SERVER['DOCUMENT_ROOT']."/application/default.php";

// REFERER CHECK
CheckRequest( "A" );
// 권한 체크
Check_Page_Use_Admin(__USER_ADMIN_NORMAL);

include_once __BASE_PATH."/function/util_func.php";
include_once __MODULE_PATH."/recruitment/RecruitmentAdmin.php";

$object	= new RecruitmentAdmin();

if ( $_POST["mode"] == "LIST_ADD") {
	$result = $object->list_update();
	$msg = "저장했습니다";
	$act = "/adm_menu/recruitemt/recruitmentList.php";
}else if ( $_POST["mode"] == "DEL" ) {
	$result = $object->delApply( $_POST["applySid"] );	
	$msg = "삭제했습니다";
}else if ( $_POST["mode"] == "SEND_MSG" ) {
	$result = $object->send_msg();	
	$msg = "문자를 보냈습니다.";
}
/*if ( $_POST["mode"] == "ADD" ) {
	$result = $object->addUser();
	$msg = "등록했습니다";
} else if ( $_POST["mode"] == "MOD" ) {
	$result = $object->updateApply( $_POST["applySid"] );
	$msg = "수정했습니다";
} */

if ( !$result ) $msg = "요청을 처리하지 못했습니다!";

?>
<script type="text/javascript">
	alert( "<?= $msg?>" );
	document.location.replace( "recruitmentList.php" );
</script>