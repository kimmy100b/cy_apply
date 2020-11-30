<?
include $_SERVER['DOCUMENT_ROOT']."/application/default.php";
include_once __BASE_PATH."/function/util_func.php";

// REFERER CHECK
CheckRequest( "R" );
// 권한 체크
Check_Page_Use_Admin(__USER_ADMIN_NORMAL);

$skin = array();
$skin[] = "<tr bgcolor='#ffffff'>
				  <td align=center>[NUM]</td>
				  <td align=center>[FIELD]</td>
				  <td align=center>[GUBUN]</td>
				  <td align=center>[CYNUM]</td>
				  <td align=center>[NAME]([NAME_EN])</td>
				  <td align=center>[PHONE]</td>
				  <td align=center>[MAIL]</td>
				  <td align=center>[REGDATE]</td>
				  <td align=center>[ADOPTION]</td>
			   </tr>";

$skin[] = "<tr><td colspan='8' bgcolor='#FFFFFF'>등록된 입사지원자가 없습니다.</td></tr>";

include __MODULE_PATH."/recruitment/RecruitmentAdmin.php";
$userObj	= new RecruitmentAdmin();

$listHtml	= $userObj->makeListExcel( $skin );

$filename = iconv( "UTF-8", "EUC-KR", "입사지원서 ".Date("Y-m-d H") );
header( "Content-type: application/vnd.ms-excel;charset=utf-8" ); 
header( "Content-Disposition: attachment; filename={$filename}.xls" ); 
header( "Content-Description: Gamza Excel Data" );
?>
<html>
<head>
<title>입사지원서</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>

<table width="100%" border="1" cellspacing="1" cellpadding="1" bgcolor="#cccccc" class="list02">
	<tr height=25 bgcolor=#EEEEEE align="center">
    <th align="center">번호</th>
    <th align="center">지원분야</th>
	<th align="center">지원형태</th>
	<th align="center">수험번호</th>
	<th align="center">성명(영문)</th>
	<th align="center">휴대폰번호</th>
	<th align="center">Email</th>
	<th align="center">지원날짜</th>
	<th align="center">합격여부</th>
	</tr>
	<? echo $listHtml; ?>
</table>
</body>
</html>