<?php 
/**
 * 채용공고
 * 
 */
if ( ! defined('__BASE_PATH')) exit('No direct script access allowed');

include_once __MODULE_PATH."/core/CoreObject.php";

class Recruitment extends CoreObject
{
	// DB Connection Object
	var $db;
	// 환경 설정 배열
	var $setting;
	// 게시판 키
	var $board_sid;
	// FileUpload Object
	var $uploader;
	// 페이지 이미지
	var $pageImages;
	// 검색어
	var $keywords;
	// 총게시물 수
	var $TABLE = "cy_apply";
	var $total_count;
	var $list_count = 10;
	var $page_count	= 10;
	var $curPage		= 1;
	var $view_url		= "recruitmentView.php";
	var $list_url		= "list.php";

	function Recruitment( $board_sid )
	{
		// DB Object 생성
		$this->db = $this->module( "core/DB" );
		$this->board_sid = (int)$board_sid;
		if ( $this->board_sid == 0 ) errorMsg("존재하지 않는 게시판입니다!","NONE");

		// 게시판 환경설정 정보 로드
		$this->_setting( $this->board_sid );
		// 게시판 언어셋 정보 로드 <ex: korean-euc-kr.php>
		$this->language( strtolower( $this->setting['board_lang']."-".__CHAR_SET ) );
	} 

	/**
	 * 게시판 설정 정보 load
	*/
	function _setting()
	{
		// 게시판 설정 정보 table 자료 fetch
		$query = "select * from board_config where board_sid = '".$this->board_sid."' and delete_state = 'N' ";
		$rs = $this->db->query( $query );
		if ( $this->db->num_rows( $rs ) > 0 )
			$this->setting = $this->db->fetch( $rs );
		else
			errorMsg("Not exist Board!","NONE");

		//---------------------------------------------------------------------------------------------------------//
		// [15.09.14] 카테고리 추가
		$category = array();
		$query = "select category_sid, category_name from board_category where board_sid = '".$this->board_sid."' order by category_order";
		if ( $rs = $this->db->query( $query ) ) 
		{
			while ( $row = $this->db->fetch( $rs ) )
				$category[ $row['category_sid'] ] = $row['category_name'];
		}
		if ( count( $category ) > 0 )
			$this->setting['board_category'] = $category;
		//---------------------------------------------------------------------------------------------------------//
		
		// skin directory/property.php 의 설정 변수를 $setting 변수로 저장
		include_once ( __BOARD_PATH."/skin/".$this->setting['skin_type']."/property.php" );
		foreach( $property as $key => $value )	$this->setting[$key] = $value;
	}

	/**
	 * 기능별 사용가능 레벨 정보
	 * param - $type : list_level / view_level / write_level / reply_level / comment_level
	 * return - array( 1, 2, 3, 4, 5 )  <사용가능 회원 레벨키 배열>
	*/
	function _avail_level( $type )
	{
		if ( $type != "" ) return explode( ",", $this->setting[ $type ."_level" ] );
	}

	/**
	 * 해당 액션 사용가능 여부
	 * param - $action<String> : 요청 액션
	 * return - true | false<Boolean> : 사용가능 회원 레벨배열에 사용자 세션레벨 포함여부
	 */
	function isAvail( $action )
	{
		if ( !in_array( $action, array( "list", "view", "write", "reply", "comment", "apply" ) ) ) 
			return false;
		return in_array( $_SESSION['LOGIN_LEVEL'], $this->_avail_level($action) );
	}

	/**
	 * 공통 검색 조건 SQL문
	 */
	function getSearchQuery() 
	{
		$searchQuery1 = "";

		if ( trim( $_GET['skey'] ) != "" && trim( $_GET['sval'] ) != "" )
		{
			$tmpKeys = explode( " ", trim( $_GET['sval'] ) );
			
			$searchQuery1 = " and ( ";
			for ( $i = 0; $i < count( $tmpKeys ); $i++ )
			{
				if ( $i > 0 ) $searchQuery1 .= " or ";

				$searchQuery1 .= " b.".clean( trim( $_GET['skey'] ) ) ." like '%".clean( trim( $tmpKeys[$i] ) )."%' ";

				$this->keywords[] = clean( trim( $tmpKeys[$i] ) );
			}
			$searchQuery1 .= " ) ";
		}
		// [15.09.14] 카테고리 선택시
		if ( trim( $_GET['category_code1'] ) != "" )
			$searchQuery1 .= " and b.category_code1 = '".clean( $_GET['category_code1'], "HTML" ) ."' ";
		// [16.02.25] 내가 쓴 게시물만 출력 여보
		if ( !is_admin() && $this->setting['ismy_article'] == "Y" ) 
			$searchQuery1 .= " and b.user_sid = '".clean( $_SESSION['LOGIN_SID'], "HTML" ) ."' ";

		return $searchQuery1;
	}

	/** 
	@ 게시물 등록
	@ 트랜잭션 : InnoDb Row Level Lock
						게시물 고유번호를 구하기 위해 해당되는 게시판의 항목들(row)에 대해 update lock 생성
	**/
	function _add()
	{
		// 연속 글 등록 방지
		$this->CheckDupWrite();

		// 금지어 체크
		$this->filter();
		
		$content_org = $_POST['data_content'];
		if($this->setting['isuse_xxs'] == "Y")
			$content_org_option = xss_filter(addslashes($content_org), "");
		else
			$content_org_option = xss_filter(addslashes($content_org), "DEL");

		// post sql injection 
		$_POST = clean( $_POST );

		/* 트랜잭션 시작 */
		//$this->db->startTrans();

		$name = strip_tags($_POST['name']);
		
		$data_sid = $_POST['data_sid'];
		
		//채용번호
		$date = explode("-", date('Y-m-d', strtotime($_POST['reg_date'])));
		$year = substr($date[0],2,2);
		$month = $date[1];
		
		$department = $_POST['category_code1']-5;
		
		$gubun = $_POST['tmp_field4'];
		
		$s_query = "select COUNT(sid) + 1 as cnt from {$this->TABLE} where data_sid = '".$_POST['data_sid']."'";
		
		if($rs = $this->db->query( $s_query )){
			while ( $row = $this->db->fetch( $rs ) )
			{
				$max_cnt = $row['cnt'];
			}
		}
		else
		errorMsg("Not exist Board!","NONE");
		
		$cnt = str_pad($max_cnt,3,"0",STR_PAD_LEFT);
		
		$cynum = $department.$_POST['tmp_field8'].$gubun.$year.$month.$cnt;
		
		//전화번호
		if($_POST['tel1']!=""||$_POST['tel1']!=""||$_POST['tel1']!="")
		{
			$tel = $_POST['tel1']."-".$_POST['tel2']."-".$_POST['tel3'];
		}
		
		$phone = $_POST['phone1']."-".$_POST['phone2']."-".$_POST['phone3'];
				
		//중복 채용 공고 검사
		$this->chkDouble($data_sid, $name, $phone);

		//자격증
		$license = "";
		$j = 0;

		for($i=0;$i<count($_POST['license']);$i++){
			if($_POST['license'][$i]!=""){
				if($j==0){
					$license.= $_POST['license'][$i];
					$j++;
				}else{
					$license.= ",".$_POST['license'][$i];
					$j++;
				}
			}
		}

		//직무경력
		// $same_office_year = $_POST['same_office_date_year'];
		// $same_office_month = $_POST['same_office_date_month']; 

		// if($same_office_year==""){
		// 	$same_office_year = 0;
		// }
		// if($same_office_month==""){
		// 	$same_office_month = 0;
		// }
		// $same_office_date = $same_office_year."년 ".$same_office_month."개월";
		
		//삽입
		//data_content		=	'".xss_filter( $_POST['data_content'] )."',
		$query = "insert into {$this->TABLE} set
				data_sid			= 	'".$_POST['data_sid']."',
				cynum				=   '".$cynum."', 
				field 				=   '".$_POST['category_code1']."',
				gubun				=   '".$gubun."',
				name_kr			    = 	'".$name."',
				name_en				=	'".strip_tags($_POST['name_en'])."',
				address			    = 	'".strip_tags($_POST['address'])."',
				tel				    =	'".$tel."',
				phone				=	'".$phone."',
				mail				=	'".strip_tags($_POST['mail'])."',
				support_yn			=	'".strip_tags($_POST['support'])."',
				disabled_yn			=	'".strip_tags($_POST['disabled'])."',
				base_yn				=	'".strip_tags($_POST['base'])."',
				caup_yn				=	'".strip_tags($_POST['caup'])."',
				license				=	'".$license."',
				office1				=	'".strip_tags($_POST['office1'])."',
				office2				=	'".strip_tags($_POST['office2'])."',
				office3				=	'".strip_tags($_POST['office3'])."',
				office4				=	'".strip_tags($_POST['office4'])."',
				office5				=	'".strip_tags($_POST['office5'])."',
				division1			=	'".strip_tags($_POST['division1'])."',
				division2			=	'".strip_tags($_POST['division2'])."',
				division3			=	'".strip_tags($_POST['division3'])."',
				division4			=	'".strip_tags($_POST['division4'])."',
				division5			=	'".strip_tags($_POST['division5'])."',
				rank1				=	'".strip_tags($_POST['rank1'])."',
				rank2				=	'".strip_tags($_POST['rank2'])."',
				rank3				=	'".strip_tags($_POST['rank3'])."',
				rank4				=	'".strip_tags($_POST['rank4'])."',
				rank5				=	'".strip_tags($_POST['rank5'])."',
				task1				=	'".strip_tags($_POST['task1'])."',
				task2				=	'".strip_tags($_POST['task2'])."',
				task3				=	'".strip_tags($_POST['task3'])."',
				task4				=	'".strip_tags($_POST['task4'])."',
				task5				=	'".strip_tags($_POST['task5'])."',
				office1_stdt		=	'".strip_tags($_POST['office1_stdt'])."',
				office2_stdt		=	'".strip_tags($_POST['office2_stdt'])."',
				office3_stdt		=	'".strip_tags($_POST['office3_stdt'])."',
				office4_stdt		=	'".strip_tags($_POST['office4_stdt'])."',
				office5_stdt		=	'".strip_tags($_POST['office5_stdt'])."',
				office1_endt		=	'".strip_tags($_POST['office1_endt'])."',
				office2_endt		=	'".strip_tags($_POST['office2_endt'])."',
				office3_endt		=	'".strip_tags($_POST['office3_endt'])."',
				office4_endt		=	'".strip_tags($_POST['office4_endt'])."',
				office5_endt		=	'".strip_tags($_POST['office5_endt'])."',
				etc_text1			=	'".strip_tags($_POST['etc_text1'])."',
				etc_text2			=	'".strip_tags($_POST['etc_text2'])."',
				etc_text3			=	'".strip_tags($_POST['etc_text3'])."',
				etc_text4			=	'".strip_tags($_POST['etc_text4'])."',
				etc_text5			=	'".strip_tags($_POST['etc_text5'])."',
				introduction1		=	'".strip_tags($_POST['introduction1'])."',
				introduction2		=	'".strip_tags($_POST['introduction2'])."',
				introduction3		=	'".strip_tags($_POST['introduction3'])."',
				adoption_yn 		=   'C',
				reg_date		    =	now() ";
			
		$result = $this->db->query( $query );
		//$insertKey = $this->db->insert_id();
		
		//문자발송
		$smsp = $_POST['phone1'].$_POST['phone2'].$_POST['phone3'];
		$msg = "안녕하십니까?\n㈜기보메이트 인사담당자입니다.\n당사 채용에 지원하여 주셔서 감사합니다.\n귀하의 수험번호는 $cynum 입니다.\n입사지원 제출이 완료되었으며, 향후 전형 결과는 당사 홈페이지를 통해서 확인하실 수 있습니다.\n채용결과 등록 후 SMS로 안내드릴 예정입니다.\n감사합니다.";

		$this->sendSms1004($name, $smsp, $msg, "MMS");
	
		if ( $result )	
		{

			if ( is_mobile( "PC" ) || $this->setting['isuse_editor'] == "N" )
				$this->getUploader()->DoUploadNormal( $this->board_sid, "files", $insertKey, true );
			return "SUCCESS";
		}
		else 
		{
			return "FAIL";
		}
	}

	/*
	 * 문자 메시지 전송
	 *   
	 */
	function sendSms1004($name, $number, $msg, $type = "LMS")
	{
		// 메세지
		$tran_msg	 = $msg;
		$tran_msg2	 = urlencode( iconv( "utf-8", "euc-kr", $tran_msg ) );
		// 제목(LMS일 경우)
		$subject = "";
		if ( trim( $_POST['subject'] ) != "" ) $subject = urlencode( iconv( "utf-8", "euc-kr", $_POST['subject'] ) );

		$_POST = clean( $_POST );
		
		// 보내는이 전화
		$tran_callback = "0517106102";
		if ( $tran_callback == "" ) errorMsg( "보내는 사람의 전화번호가 없습니다!" );
		
		// 예약
		$reserve = "";
		// 예약 시간
		$reserve_time="";
		// 사용자 정의(발송후 리턴페이지로 값 그대로 리턴)
		$etc1="";
		$etc2="";
		// 전송 성공/실패
		$successCnt = 0;
		$failCnt = 0;

		// 문자천사 주소 및 계정
		$host = "www.munja1004.co.kr"; //도메인 주소
		$id = "kibomate"; // 회원 아이디 입력해 주세요.
		$pass = "**sms7692"; // 비밀번호 입력해 주세요.

		// 수신자 번호들( 이름1@@번호1,이름2@@번호2,,,,, )
		$receiverArr[] = $name."@@".$number;		
		
		//$receiverArr = explode( ",", $_POST['receiver'] );

		if ( count($receiverArr) == 0 ) errorMsg("수신자가 없습니다!" );
		// 수신자 핸드폰 결과 String
		$phone = "";
		for( $i = 0; $i < count( $receiverArr ); $i++ )
		{
			$tmpreceiverArr = explode( "@@", $receiverArr[$i] );			
			$phone = $tmpreceiverArr[1];
						
			//-----------------------------------------------------------------------------------//
			// SMS 발송
			//-----------------------------------------------------------------------------------//
			$param = "remote_id=".$id;
			$param .= "&remote_pass=".$pass;
			$param .= "&remote_reserve=".$reserve;
			$param .= "&remote_reservetime=".$reserve_time;
			$param .= "&remote_phone=".$phone;
			$param .= "&remote_callback=".$tran_callback;
			$param .= "&remote_msg=".$tran_msg2;
			$param .= "&remote_etc1=".$etc1;
			$param .= "&remote_etc2=".$etc2;

			// LMS(2000) or MMS
			if ( $type == "MMS" ) 
			{
				$param .= "&remote_subject=".$subject;
				$path = "/Remote/RemoteMms.html";
			}
			else 
				$path = "/Remote/RemoteSms.html";
			
			$fp = @fsockopen($host,80,$errno,$errstr,30); 
			$return = "";

			if (!$fp) {
				die($_err.$errstr.$errno);
			} else { 
				fputs($fp, "POST ".$path." HTTP/1.1\r\n");
				fputs($fp, "Host: ".$host."\r\n");
				fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
				fputs($fp, "Content-length: ".strlen($param)."\r\n");
				fputs($fp, "Connection: close\r\n\r\n");
				fputs($fp, $param."\r\n\r\n");

				while(!feof($fp)) $return .= fgets($fp,4096);
			} 

			fclose ($fp); 
			$_temp_array = explode("\r\n\r\n", $return);
			$_temp_array2 = explode("\r\n", $_temp_array[1]);

			//-----------------------------------------------------------------------------------//

			// 테스트용 결과
			//$_temp_array2[1] = "code=0000|msg=success|nums=".count( $receiverArr )."|cols=100";
			/* CODE
			0000 전송성공			0001 접속에러	0002 인증에러			0003 잔여콜수 없음	0004 메시지 형식에러		0005 콜백번호 에러
			0006 수신번호 개수에러					0007 예약시간 에러	0008 잔여콜수 부족	0009 전송실패
			0010 MMS NO IMG (서버에 이미지파일 없음)					0011 MMS ERROR TRANSFER (이미지 전송에러)
			*/
			// code | msg | nums(전송갯수) | etc1~6 | cols(남은잔여콜수) | gcode
			$return_string = iconv( "euc-kr", "utf-8", $_temp_array2[1] );
			//echo $return_string;

			$returnArr = explode( "|", $return_string );
			$resultArr = array();
			for ( $j = 0; $j < count($returnArr ); $j++ )
			{
				$tmpArr = explode( "=", $returnArr[$j] );
				$resultArr[ $tmpArr[0] ] = $tmpArr[1];
			}
			$code		= $resultArr['code'];
			$msg		= $resultArr['msg'];
			$nums	= $resultArr['nums'];
			$cols		= $resultArr['cols'];
			
			$status = "";
			$returnMsg = "";
			
			// 발송 성공시
			if ( $code == "0000" )
			{
				$status = "1";
				$successCnt++;
			}
			else
			{
				$status = "0";
				$failCnt++;
				$returnMsg .= "[". $phone . " 전송실패] ". $msg;
			}
		}

		// 발송내역 성공/실패 여부 저장
		return array( $returnMsg, $successCnt, $failCnt );
	}

	/*
	 * 이전 다음 게시물
	 * data_order 의 이전 앞
	 * [2015-01-19] org_user_sid 필드 추가
	 */
	function getPrevNext( $data_sid, $data_order )
	{
		$result = "";
		
		// 다음글
		$skin = $this->setting['list_html_row_next'];
		$query = "select  data_sid, board_sid, data_no, data_order, data_depth, user_sid, user_id, user_nick, user_email, user_homepage, data_title,
									delete_state, data_notice_fl, data_secret_fl, comment_count, view_count, register_date, user_pw, org_user_sid 
						from board_data b
						where board_sid = '".$this->board_sid."' and data_order < ".clean($data_order)." and delete_state = 'N' " . $this->getSearchQuery() ."
						order by data_order desc limit 1 ";
		$row = $this->db->fetch( $query );
		$temp = $this->_makeList( $skin, $row );
		if ( $temp == "" ) $temp = $this->setting['list_html_row_next_not'];
		$result = $temp;

		// 이전글
		$skin = $this->setting['list_html_row_prev'];
		$query = "select  data_sid, board_sid, data_no, data_order, data_depth, user_sid, user_id, user_nick, user_email, user_homepage, data_title,
									delete_state, data_notice_fl, data_secret_fl, comment_count, view_count, register_date, user_pw, org_user_sid 
						from board_data b
						where board_sid = '".$this->board_sid."' and data_order > ".clean($data_order)." and delete_state = 'N' " . $this->getSearchQuery() ." limit 1 ";
		$row = $this->db->fetch( $query );
		$temp = $this->_makeList( $skin, $row );
		if ( $temp == "" ) $temp = $this->setting['list_html_row_prev_not'];
		$result .= $temp;

		return $result;
	}

	/* 
	 * 필수 입력 항목 체크
	 * param : $reqires<array>	- 필수 입력항목 필드 name
	 *				$url<string>		- 에러시 이동 url
	 */
	function requireFields( $requires, $url )
	{
		$_PARAM = $_GET;
		$method	= "get";
		if ( $_SERVER['REQUEST_METHOD'] == "POST" )
		{
			$_PARAM = $_POST;
			$method	= "post";
		}

		for ( $i = 0; $i < count( $requires ); $i++ )
		{
			if ( trim( $_PARAM[$requires[$i]] ) == "" ) 
			{
				if ( $method == "get" )
				{
					errorMsg( $this->config['lang_require_'.$requires[$i]], "BACK" );
					exit;
				}
				else if ( $method == "post" )
				{
					postBack( $this->config['lang_require_'.$requires[$i]], $url );
					exit;
				}
			}
		}
	}

	/*
	 * 모드별 버튼 링크 출력
	 * - 작성 화면은 스킨에서..
	 */
	function getButtons( $mode, $isMine )
	{
		$result = "";
		
		// 목록 버튼
		if ( $mode == "LIST" )
		{
			// 게시물 작성 권한자 일 경우 쓰기 버튼
			if ( strpos( $this->setting['write_level'], $_SESSION['LOGIN_LEVEL'] ) !== false ) 
			{
				$url		= $this->setting['write_url'] . $this->queryString();
				$result .= str_replace( "[LINK]", $url, $this->setting['write_btn'] );
			}
			// 게시판 관리자 일 경우 다중 삭제 버튼
			if ( is_board_admin( $this->setting['user_sid'] ) ) 
			{
				$url		= "deleteCheck();";
				$result .= str_replace( "[LINK]", $url, $this->setting['delete_btn'] );
			}
		}

		// 글 보기 화면 버튼
		else if ( $mode == "VIEW" )
		{
			$result = array();

			// 목록 버튼
			$url		= $this->setting['list_url'] . $this->queryString();
			$result['LIST'] = str_replace( "[LINK]", $url, $this->setting['list_btn'] );

			// 지원하기 버튼
			$apply_url = $this->setting['apply_url'] . $this->queryString( getParam('data_sid') );
			$temp = str_replace( "[LINK]", $apply_url, $this->setting['apply_btn'] );
			$result['APPLY'] = $temp;

			// 본인 글
			if ( $isMine ) 
			{
				$mod_url = "modifyArticle()";
				$del_url	= "deleteArticle()";

				// 수정 버튼
				$temp = str_replace( "[LINK]", $mod_url, $this->setting['modify_btn'] );
				// 삭제 버튼
				$temp .= str_replace( "[LINK]", $del_url, $this->setting['delete_btn'] );
				$result['MOD'] = $temp;
			}

			if ( $this->isAvail( "reply" ) )
			{
				$reply_url = $this->setting['write_url'] . $this->queryString( getParam('data_sid') );
				// 답글 버튼
				$temp = str_replace( "[LINK]", $reply_url, $this->setting['reply_btn'] );
				$result['REPLY'] = $temp;
			}

		}

		return $result;
	}



	/**
	@ 게시판 설정 정보 get
	**/
	function getSetting()
	{
		return (object)$this->setting;
	}

	/*
	 * QueryString
	 * data_sid = null [글쓰기시 기존에 선택되었던 data_sid가 남아 있을 경우 오류 발생. 글보기시만 세팅]
	 */
	function queryString($data_sid="")
	{
		$result = "";

		if ( $_SERVER['REQUEST_METHOD'] == "POST" )
			$valueQuery = array(		"menu_code"=>$_POST['menu_code'], 
											"board_sid"=>$this->board_sid, "data_sid"=>$data_sid, 												
											"page_num"=>$_POST['page_num'], "sval"=>$_POST['sval'], "skey"=>$_POST['skey'], "category_code1"=>$_POST['category_code1'] );
		else
		{
			$valueQuery = array();
			parse_str( $_SERVER['QUERY_STRING'], $valueQuery );
			$valueQuery['board_sid'] = $this->board_sid;
			$valueQuery['data_sid'] = $data_sid;
			$valueQuery['category_code1'] = $_GET['category_code1'];
		}

		return "?". setQueryStr( $this->setting['query_str'], $valueQuery );
	}

	/* 신규 자료시 new */
	function CheckNewAritcle( $date )
	{	
		//새글 이미지 
		$new_img = $this->setting['image_new_article'];

		if ( intval( strtotime( dateformat( $date, "-" ) ) + 86400 ) > intval( strtotime( date("Y-m-d H:i:s" ) ) ) )
			return $new_img;
	}

	/* 스팸게시글 단속 */
	function CheckDupWrite() 
	{
		$dTime = $this->setting['new_article_interval'];
		if ( $_SESSION['last_write_time'] != "" ) 
		{
			if ( time(0) - $_SESSION['last_write_time'] < $dTime ) 
				postBack( $dTime . $this->config['lang_error_add_time_limit'], "apply.php" );
		}
		$_SESSION["last_write_time"] = time(0);
	}

	/* 금지어 체크 */
	function filter() 
	{
		$banKeyword = $this->setting['board_ban_content'];
		if ( trim( $banKeyword ) != "" )
		{
			$filters = explode( ",", $banKeyword );

			$findKeyword = filterString( $filters, array( $_POST['data_title'], $_POST['data_content'] ) );
			if ( trim( $findKeyword ) != "" )	postBack( $this->config['lang_error_bad_title'] ."(금지어 : ".$findKeyword.")", "apply.php" );
		}
	}

	// 게시물 수 관리
	function setCompanyCount($value, $group_sid, $mentor_sid)
	{
		if ( intval($group_sid) > 0 )
		{
			// 기업 게시글 카운트 증감
			$query = "update company set co_article_count = co_article_count {$value} where co_sid = '".intval($group_sid)."' ";
			$this->db->query( $query );
		}

		if ( intval($mentor_sid) > 0 )
		{
			// 멘토 게시글 카운트 증감
			$query = "update mentor set article_count  = article_count {$value} where mentor_sid = '".intval($mentor_sid)."' ";
			$this->db->query( $query );
		}
	}

	function chkDouble($data_sid, $name, $phone){
		$query = "select data_sid, name_kr, phone from {$this->TABLE}";
		if ( $rs = $this->db->query( $query ) ) 
		{
			while ( $row = $this->db->fetch( $rs ) ){
				if($data_sid==$row['data_sid']&&$name==$row['name_kr']&&$phone==$row['phone']){
					errorMsg('중복 지원은 불가능합니다!','LOCATION','./list.php?board_sid='.$_POST['board_sid'].'&category_code1='.$_POST['category_code1'].'&menu_code='.$_POST['menu_code'].'');
				}
			}
		}

		return true;
	}
}
?>