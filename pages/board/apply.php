<?php
// Last modified Jan 21 2013
// - 메일 발송시 각 게시판별 메일 수신 주소 설정
include_once $_SERVER['DOCUMENT_ROOT']."/application/default.php";
include $_SERVER['DOCUMENT_ROOT']."/application/module/board/Board.php";
include_once $_SERVER['DOCUMENT_ROOT']."/application/function/util_func.php";

// 일반 보기에서 링크시에는 GET, 비밀번호 입력으로 넘어 올때는 POST 구분
if ( $_SERVER['REQUEST_METHOD'] == "POST" )	$_PARAM = $_POST;
else															$_PARAM = $_GET;

$board_sid	= (int)$_PARAM['board_sid'];
$data_sid		= clean($_PARAM['data_sid']);
$data_depth = 1;

// 메뉴정보 조회
include_once __BOARD_PATH."/menu_info.php";

$mode			= ( $_PARAM['mode'] == "" ) ? "ADD" : $_PARAM['mode'];

// 게시판 모듈
$board = new Board( $board_sid );

// 게시판 설정 정보
$setting = $board->getSetting();

// [16.04.05] SSL 사용여부
if ( $setting->isuse_ssl == "Y" ) 
{
	// 보안 인증서 체크
	if ( $_SERVER["SERVER_PORT"] != __SSL_PORT ) 
	{
		$moveUrl = "https://".$_SERVER['SERVER_NAME'].":".__SSL_PORT.$_SERVER['REQUEST_URI'];
		echo  "<script type='text/javascript'>document.location.replace('$moveUrl');</script>";
		exit;
	}
}

/* 신규 등록 모드 */
if ( $mode == "ADD" )
{

	$article = $board->getData($data_sid, $mode, $_PARAM['user_pw']);
	$data_title		= $article->data_title;
	$data_content	= $article->data_content;
	$user_email		= $article->user_email;

	$readOnly = "";
	if ( trim( $_SESSION['LOGIN_NAME'] ) != "" )
	{
		$user_nick = trim( $_SESSION['LOGIN_NAME'] );
		$readOnly = "readonly";
	}

	$user_email = $_SESSION['LOGIN_EMAIL'];

	// 답변 등록시
	if ( (double)$data_sid > 0 ) 	
	{
		// 게시물 정보 + 본인 글 여부 체크
		$article2 = $board->getData($data_sid, $mode, $_PARAM['user_pw']);
		$data_depth = (int)$article2->data_depth + 1;
		$data_title		= "[답글]".$article2->data_title;
		$data_content	= "--------------------------------------원본 글---------------------------------<br />".
									$article2->data_content.
									"------------------------------------------------------------------------------<br />";
		$org_user_name = $article2->user_nick;
		$org_user_email = $article2->user_email;
	}
		
	$button_text = "등록";

	// 신규 글 등록시 에러로 인해 되돌아 온 경우 작성 내용 보존하기
	// 첨부한 파일 까지 보존하려면 다음 두개 파라미터로 이미지 정보 가져오기 [attach_image], [attach_file] 
	if ( $_POST['postback'] == "Y" )
	{
		//$article = array( "data_title"=>$_POST['data_title'], "data_content"=>$_POST['data_content'], "user_nick"=>$_POST['user_nick'] );
		//$article = (object)$article;
		$data_depth	= (int)$_POST['data_depth'];
		$data_title		= $_POST['data_title'];
		$data_content = $_POST['data_content'];
		$user_nick		= $_POST['user_nick'];
		$user_email		= $_POST['user_email'];
	}
	// 카테고리
	$cate_code1 = $_GET['category_code1'];
}
/* 수정 모드 */
else if ( $mode == "MOD" )
{
	// 게시물 정보 + 본인 글 여부 체크
	$article = $board->getData($data_sid, $mode, $_PARAM['user_pw']);
	$data_title		= $article->data_title;
	$data_content	= $article->data_content;
	$user_email		= $article->user_email;

	// 글 수정시 에러로 인해 되돌아 온 경우 작성 내용 보존하기
	// 첨부한 파일 까지 보존하려면 다음 두개 파라미터로 이미지 정보 가져오기 [attach_image], [attach_file] 
	if ( $_POST['postback'] == "Y" )
	{
		$data_title		= $_POST['data_title'];
		$data_content	= $_POST['data_content'];
		$user_email		= $_POST['user_email'];
	}

	$user_nick = $article->user_nick;
	// 작성자가 null 일 경우 작성자 수정 가능
	$readOnly = "readonly";
	if ( trim( $user_nick ) == "" ) $readOnly = "";
	
	// 첨부 이미지/파일 정보
	$attImages	= $article->attach_image;
	$attFiles		= $article->attach_file;
	// [190121] 대표이미지도 파일첨부란에 표시
	$attImagesRep		= $article->attach_image_rep;
	$attSize		= $article->attach_fileSize;
	// 첨부된 파일 수
	$previous_files_count = count( $attImages ) + count( $attFiles );
	// 첨부된 파일 용량( KB )
	$up_files_size = $attSize;

	$button_text = "수정";
	// 카테고리
	$cate_code1 = $article->category_code1;
}

// sub title
$subTitle = " > " . $setting->board_title;

/*
// 모바일 체크
if ( is_mobile( "PC" ) )
{
	$setting->editor_size = $setting->m_image_width;
	include __BOARD_PATH."/include/".$setting->m_skin_header;
	include __BOARD_PATH."/skin/".		$setting->skin_type."/m.". $setting->write_url;
	include __BOARD_PATH."/include/".$setting->m_skin_footer;
}
else
{*/
	//include __BOARD_PATH."/include/".$setting->skin_header;

	//-------------------------------------------------------------------------------------------------//
	// 헤더정보(레이아웃)
	if ( $layout_info['header_type'] == "INC" ) 
		require __MAP_PATH."/". str_replace( "../", "/", $layout_info['header_content'] );
	else
		echo $layout_info['header_content'];
	//-------------------------------------------------------------------------------------------------//

	include __BOARD_PATH."/skin/".		$setting->skin_type."/". $setting->apply_url;

	//-------------------------------------------------------------------------------------------------//
	// 푸터정보(레이아웃)
	if ( $layout_info['footer_type'] == "INC" ) 
		require __MAP_PATH."/". str_replace( "../", "/", $layout_info['footer_content'] );
	else
		echo $layout_info['footer_content'];
	//-------------------------------------------------------------------------------------------------//

	//include __BOARD_PATH."/include/".$setting->skin_footer;
//}
?>
