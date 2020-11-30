<?php 
/*
 * Board framework
 *
 * Author : cocktail
 * 
 * 관리자 채용관리 관리
 *
 */
if ( ! defined('__BASE_PATH')) exit('No direct script access allowed');

include_once __MODULE_PATH."/recruitment/Recruitment.php";

class RecruitmentAdmin extends Recruitment {	

	function RecruitmentAdmin() {
		$this->TABLE	= "cy_apply";
		// DB Object 생성
		$this->db = $this->module( "core/DB" );
	}

	function searchCon() {
		$search_val = "";

		if($_GET['search_field']){
			$search_val .= "and field = '".$_GET['search_field']."'";
		}
		if($_GET['sDate']){
			$search_val .= "and reg_date >= '".$_GET['sDate']." 00:00:00'";
		}
		if($_GET['eDate']){
			$search_val .= "and reg_date <= '".$_GET['eDate']." 23:59:59'";
		}
		if($_GET['searchName']){
			$search_val .= "and name_kr like '%".$_GET['searchName']."%'";
		}
		if($_GET['adoption']){
			$search_val .= "and adoption_yn like '%".$_GET['adoption']."%'";
		}
		if($_GET['cynum']){
			$search_val .= "and cynum = '".$_GET['cynum']."'";
		}
		if($_GET['mail']){
			$search_val .= "and mail = '".$_GET['mail']."'";
		}

		return $search_val;
	}

	function makeList( $skin ) 
	{	

		//검색관련 쿼리 만들기

		$pageNum = 	( trim( $_GET["page_num"] ) != "" ) ? intval( $_GET["page_num"] )  : intval( $_POST["page_num"] );
		if ( $pageNum == 0 ) $pageNum = 1;
		$startNum = ( $pageNum - 1 ) * $this->list_count;

		$html_result	= "";
		$count			= 0;
		
		//-------------------------------------------------------------------------------------------------------------------------------------------------------		
		// Paging Start
		//-------------------------------------------------------------------------------------------------------------------------------------------------------
		// paging query
		$pageQuery	= "select count(*) as totCount from ". $this->TABLE." where del_yn = 'N' ".$this->searchCon();
		$pageRow = $this->db->fetch( $pageQuery );

		// 총 게시물 수 
		$totalCount		= $pageRow['totCount'];
		$pageList		= $this->paging( $pageNum, $totalCount, $this->list_count, $this->page_count, "page_num", "Q", "", $this->pageImages );
		//-------------------------------------------------------------------------------------------------------------------------------------------------------		
		// Paging End
		//-------------------------------------------------------------------------------------------------------------------------------------------------------

		// list
		$query		= "select * from ". $this->TABLE ."  where del_yn = 'N' ".$this->searchCon()." order by sid desc limit ".$startNum.", " . $this->list_count;

		if ( $result = $this->db->query( $query ) )
		{
			while($row = $this->db->fetch($result))
			{	
				$tmpRow = $skin[0];
				// 목록 번호
				$list_num = $totalCount - $startNum - $count;

				$html_result .= $this->_makeList( $tmpRow, $row, $list_num, $count );

				$state_html = "<select name=\"state_".$row['sid']."\">
									<option value=\"C\">지원완료</option>
									<option value=\"B\">서류전형합격</option>		
									<option value=\"A\">면접전형합격</option>		
									<option value=\"P\">최종합격</option>		
									<option value=\"N\">불합격</option>
								</select>";

				$count++;
			}
	
		}

		if ( $html_result == "" )
		{
			$html_result = $skin[0];
		}
		
		return array( $html_result, $pageList );
	}

	function searchResult($skin){
		//검색관련 쿼리 만들기
		$html_result	= "";
		$count			= 0;
	
		// list
		$query		= "select a.*, b.register_date from ". $this->TABLE ." as a, board_data as b  where a.del_yn = 'N' and a.data_sid = b.data_sid ".$this->searchCon()." order by sid desc";

		if ( $result = $this->db->query( $query ) )
		{
			if($row = $this->db->fetch($result))
			{	
				$tmpRow = $skin[0];
				// 목록 번호
				$list_num = $totalCount - $startNum - $count;

				
				switch($row['adoption_yn'])
				{
					case 'A':
						$tmpRow = $skin[3];
					break;
					case 'B':
						$tmpRow = $skin[2];
					break;
					case 'C':
						$tmpRow = $skin[1];
					break;
					case 'P':
						$tmpRow = $skin[3];
					break;
					case 'N':
						$tmpRow = $skin[4];
					break;
				}

				$html_result .= $this->_makeList( $tmpRow, $row, $list_num, $count );

				$state_html = "<select name=\"state_".$row['sid']."\">
									<option value=\"C\">지원완료</option>
									<option value=\"B\">서류전형합격</option>		
									<option value=\"A\">면접전형합격</option>		
									<option value=\"P\">최종합격</option>		
									<option value=\"N\">불합격</option>
								</select>";
			}	
		}


		if ( $html_result == "" )
		{
			$html_result = $skin[0];
		}
				
		//return array( $html_result, $pageList );
		return $html_result;
	}
	
	/**
	 * 목록 html 생성
	 */
	function _makeList( $temp, $row, $list_num, $count )
	{
		global $__CONF;

		if ( !is_array( $row ) || count( $row ) == 0 || is_null( $row ) ) return "";

		// keyArr과 valArr의 값들이 인덱스가 일치해야 함
		/*$keyArr = array("[CHECK]","[NUM]","[SID]","[DATA_SID}","[NAME]", "[NAME_EN]","[ADDRESS]","[TEL]","[PHONE]","[MAIL]","[SUPPORT]","[DISABLED]","[BASE]","[CAUP]","[LICENSE]","[OFFICE1]","[OFFICE2]","[OFFICE3]","[OFFICE4]","[OFFICE5]"
		,"[DIVISION1]","[DIVISION2]","[DIVISION3]","[DIVISION4]","[DIVISION5]","[RANK1]","[RANK2]","[RANK3]","[RANK4]","[RANK5]","[TASK1]","[TASK2]","[TASK3]","[TASK4]","[TASK5]","[OFFICE1_STDT]","[OFFICE2_STDT]","[OFFICE3_STDT]","[OFFICE4_STDT]","[OFFICE5_STDT]"
		,"[OFFICE1_ENDT]","[OFFICE2_ENDT]","[OFFICE3_ENDT]","[OFFICE4_ENDT]","[OFFICE5_ENDT]","[ETC_TEXT1]","[ETC_TEXT2]","[ETC_TEXT3]","[ETC_TEXT4]","[ETC_TEXT5]"
		,"[SAME_OFFICE_DATE]","[INTRODUCTION1]","[INTRODUCTION2]","[INTRODUCTION3]","[ADOPTION]","[REG_DATE]","[LINK]","[/LINK]","[FIELD]", "[GUBUN]");*/
		$keyArr = array("[CHECK]","[NUM]","[NAME]","[NAME_EN]","[PHONE]","[MAIL]","[REG_DATE]","[LINK]","[/LINK]","[FIELD]","[GUBUN]","[ADOPT]","[CHG_ADOPT]","[YEAR]");
		$valArr = array();
		
		$row = array_map( "stripslashes", $row );

		$valArr[] = "<input type='checkbox' class='inputchk' name='check[]' value='".$row['sid']."' />";

		$valArr[] = "{$list_num}";
		
		$valArr[] = $row['name_kr'];

		$valArr[] = $row['name_en'];
		
		$valArr[] = $row['phone'];
		
		$valArr[] = $row['mail'];
		
		$valArr[] =  date('Y-m-d', strtotime($row['reg_date']));

		$linkUrl = "<a href=\"recruitmentView.php?applySid=".$row['sid']."\">";
		$valArr[] = $linkUrl;

		$valArr[] = "</a>";

		switch($row['field']){
			case 6:
				$field_state = "경영지원부";
			break;
			case 7:
				$field_state = "고객센터";
			break;
			case 8:
				$field_state = "보안업무팀";
			break;
			case 9:
				$field_state = "시설관리팀";
			break;
			case 10:
				$field_state = "환경미화팀";
			break;
			default:
				$field_state="기타";
		break;
		}
		$valArr[] = $field_state;


		if($row['gubun']==1){
			$gubun_state = "정규직";
		} else if($row['gubun']==2){
			$gubun_state = "대체직";
		}
		$valArr[] = $gubun_state;

		if($row['adoption_yn']=="C"){
			$adopt_state = "지원완료";
		}else if($row['adoption_yn']=="B"){
			$adopt_state = "서류전형합격";
		} else if($row['adoption_yn']=="A"){
			$adopt_state = "면접전형합격";
		} else if($row['adoption_yn']=="P"){
			$adopt_state = "최종합격";
		}else if($row['adoption_yn']=="N"){
			$adopt_state = "불합격";
		}

		$valArr[] = $adopt_state;

		$adopt_html = "<select name=\"state_".$row['sid']."\">
									<option value=\"".$row['adoption_yn']."\">".$adopt_state."</option>
									<option value=\"C\">지원완료</option>
									<option value=\"B\">서류전형합격</option>
									<option value=\"A\">면접전형합격</option>
									<option value=\"P\">최종합격</option>
									<option value=\"N\">불합격</option>
								</select>";

		$valArr[] = $adopt_html; 

		//채용공고등록일
		$cy_date = explode('-',date('Y-m-d', strtotime($row['register_date'])));
		$valArr[] = $cy_date[0];

		$temp = str_replace( $keyArr, $valArr, $temp );

		return $temp;
	}

	//리스트 상태변경
	function list_update( ){
		for($i=0;$i<count($_POST['check']);$i++){
			$mester_sid = $_POST['check'][$i];
			$state_key = "state_".$mester_sid;
			$state = $_POST[$state_key];
			
			$updateQuery = "UPDATE {$this->TABLE} set	
							adoption_yn = '".$state."'
							WHERE sid = '".$mester_sid."'";
			
			$this->db->query($updateQuery);

		}
		return true;
	}

	//합격문자발송
	function send_msg( ){
		for($i=0;$i<count($_POST['check']);$i++){
			$mester_sid = $_POST['check'][$i];
			$state_key = "state_".$mester_sid;
			$state = $_POST[$state_key];
		
			$query = "SELECT cynum, name_kr, phone, adoption_yn
						FROM cy_apply
						WHERE sid = '".$mester_sid."'";
			
			$result = $this->db->query( $query );
			while($row = $this->db->fetch($result))
			{
				$name = $row['name_kr'];
				$smsp = str_replace('-','',$row['phone']);
				$adopt = $row['adoption_yn'];
				$cynum = $row['cynum'];

				switch($adopt){
					case 'C' : //지원완료
						$msg = "안녕하십니까?\n㈜기보메이트 인사담당자입니다.\n당사 채용에 지원하여 주셔서 감사합니다.\n귀하의 수험번호는 $cynum 입니다.\n입사지원 제출이 완료되었으며, 향후 전형 결과는 당사 홈페이지를 통해서 확인하실 수 있습니다.\n채용결과 등록 후 SMS로 안내드릴 예정입니다.\n감사합니다.";
						$this->sendSms1004($name, $smsp, $msg, "MMS");
					break;
					case 'B': //서류전형 합격자 발표
						$msg = "안녕하십니까?\n㈜기보메이트 인사담당자입니다.\n\n당사에 지원하여 주셔서 감사드립니다.\n서류전형 합격자 발표가 공지 되었습니다.\n홈페이지 내 채용결과조회에서 합격여부를 확인 바랍니다.\n채용 결과조회 페이지 URL : http://www.kibomate.co.kr/?menu_code=252/";
						$this->sendSms1004($name, $smsp, $msg, "MMS");
					break;
					case 'A': //면접전형 합격자 발표
						$msg = "안녕하십니까?\n㈜기보메이트 인사담당자입니다.\n\n당사에 지원하여 주셔서 감사드립니다.\n면접전형 합격자 발표가 공지 되었습니다.\n홈페이지 내 채용결과조회에서 합격여부를 확인 바랍니다.\n채용 결과조회 페이지 URL : http://www.kibomate.co.kr/?menu_code=252/";
						$this->sendSms1004($name, $smsp, $msg, "MMS");
					break;
					case 'P': //최종 합격자 발표
						$msg = "안녕하십니까?\n㈜기보메이트 인사담당자입니다.\n\n당사에 지원하여 주셔서 감사드립니다.\n최종 합격자 발표가 공지 되었습니다.\n홈페이지 내 채용결과조회에서 합격여부를 확인 바랍니다.\n채용 결과조회 페이지 URL : http://www.kibomate.co.kr/?menu_code=252/";
						$this->sendSms1004($name, $smsp, $msg, "MMS");
					break;
					default:
						continue;
				break;
				}
			}
		}

		return true;
	}

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

			//LMS (2000) or MMS
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
			// echo $return_string;

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

	/**
	 * 게시물 정보
	 */
	function getData( $sid, $mode, $type = "" )
	{
		if ( trim( $sid ) == "" ) {
			errorMsg( $this->config['lang_error_exist'], "BACK" );
		}

		$query = "select a.*, b.data_title, b.tmp_field1, b.tmp_field2, b.tmp_field3, b.tmp_field8
					from cy_apply a, board_data b
					where a.sid = '".$sid."' and a.data_sid = b.data_sid";
		
		// 글 정보 없을 경우 error
		if ( $this->db->num_rows( $query ) == 0 )
			errorMsg( $this->config['lang_error_exist'], "BACK" );

		// 게시물 정보 row
		$row = $this->db->fetch( $query );
		$row = array_map( "stripslashes", $row );

	
		// 모드별 가능 버튼 > 게시물 정보에 추가
		//$buttons = $this->getButtons( $mode, $isMine );
		//$row['buttons'] = $buttons;

		return $row;
	}

	// 삭제
	function delApply( $applySid ) 
	{
		$applySid = clean( $applySid );

		if ( trim( $applySid ) == "" ) {
			errorMsg( "필수정보가 없습니다!" );
			return;
		}

		// 정보 삭제
		$deleteQuery = "update {$this->TABLE} set del_yn = 'Y' where sid = '".$applySid."' ";

		if ( $this->db->query( $deleteQuery ) )	
		{
			return "SUCCESS";
		}
		else return false;
	}


	/**
	@ param : 
		skin	- array ( 출력 row [NAME]|[APP]|[DEVICE]|[COMPARE]|[SUBID], blank row )
	**/
	function makeListExcel( $skin ) 
	{
		global $__CONF;

		$html_result	= "";
		$count			= 1;

		// list
		$query = "select * from ". $this->TABLE ." where del_yn = 'N' ".$this->searchCon()." order by sid desc";

//echo $query;
		if ( $result = $this->db->query( $query ) )
		{
			while($row = $this->db->fetch($result))
			{
				$tmpRow = $skin[0];

				$tmpRow = str_replace( "[NUM]", $count, $tmpRow );				
				
				$field = $row['field'];
				switch($field){
					case 6: 
						$tmpRow = str_replace( "[FIELD]", "경영지원부", $tmpRow );
					break;
					case 7: 
						$tmpRow = str_replace( "[FIELD]", "고객센터", $tmpRow );
					break;
					case 8: 
						$tmpRow = str_replace( "[FIELD]", "보안업무팀", $tmpRow );
					break;
					case 9: 
						$tmpRow = str_replace( "[FIELD]", "시설관리팀", $tmpRow );
					break;
					case 10: 
						$tmpRow = str_replace( "[FIELD]", "환경미화팀", $tmpRow );
					break;
				}

				$gubun = $row['gubun'];
				switch($gubun){
					case 1: 
						$tmpRow = str_replace( "[GUBUN]", "정규직", $tmpRow );
					break;
					case 2: 
						$tmpRow = str_replace( "[GUBUN]", "대체직", $tmpRow );
					break;
				}

				$tmpRow = str_replace( "[CYNUM]", $row['cynum'], $tmpRow );
				$tmpRow = str_replace( "[NAME]", $row['name_kr'], $tmpRow );
				$tmpRow = str_replace( "[NAME_EN]",	$row['name_en'], $tmpRow );
				$tmpRow = str_replace( "[PHONE]", $row['phone'], $tmpRow );
				$tmpRow = str_replace( "[MAIL]", $row['mail'], $tmpRow );
				$tmpRow = str_replace( "[REGDATE]",date('Y-m-d', strtotime($row['reg_date'])),$tmpRow );

				$adoption = $row['adoption_yn'];
				switch($adoption){
					case 'P': 
						$tmpRow = str_replace( "[ADOPTION]", "최종합격", $tmpRow );
					break;
					case 'N': 
						$tmpRow = str_replace( "[ADOPTION]", "불합격", $tmpRow );
					break;
					case 'A': 
						$tmpRow = str_replace( "[ADOPTION]", "면접전형합격", $tmpRow );
					break;
					case 'B': 
						$tmpRow = str_replace( "[ADOPTION]", "서류전형합격", $tmpRow );
					break;
					case 'C': 
						$tmpRow = str_replace( "[ADOPTION]", "지원완료", $tmpRow );
					break;
				}


				//$tmpRow = str_replace( "[LASTVISIT]",	$row[lastVisit],		$tmpRow );
				$html_result .= $tmpRow;
				$count++;
			}
		}

		if ( $html_result == "" ) $html_result = $skin[1];

		return $html_result;
	}

}
?>
