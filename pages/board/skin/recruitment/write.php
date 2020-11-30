<?php
$emails = @explode( "@", $article->user_email );

//근무지역
$tmp_field8 = array('1' => '부산',
				'2' => '인천',
				'3' => '대전',
				'4' => '용인');

?>
<script type="text/javascript" src="<?php echo __SYSTEM_URL?>/js/util.js"></script>
<script src="/application/js/jquery.inputmask.js" type="text/javascript"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
<script type="text/javascript" src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
<script type="text/javascript">
$(function() {
	makeCalendar( "tmp_field5" );
	makeCalendar( "tmp_field6" );

	$("#tmp_field5").inputmask("9999-99-99");
	$("#tmp_field6").inputmask("9999-99-99");

	if($("#mode").val() == "MOD"){
		var data = "<?php echo $article->tmp_field7; ?>";
		if(data != ""){
			var jbSplit = data.split(',');
			for(var i=0;i<jbSplit.length;i++){
				$("input[name='tmp_field7[]'][value='"+jbSplit[i]+"']").prop("checked",true);
			}
		}
		
	}
});
function checkform()
{
	var frm = document.mainform;

	if ( $.trim( frm.category_code1.value ) == "" )
	{
		alert( "분류를 선택하세요!" );
		frm.category_code1.focus();
		return;
	}else if ( $.trim( frm.data_title.value ) == "" )
	{
		alert( "제목을 입력하세요!" );
		frm.data_title.focus();
		return;
	}
	else if ( $.trim( frm.user_nick.value ) == "" )
	{
		alert( "작성자를 입력하세요!" );
		frm.user_nick.focus();
		return;
	}
	else if ( $.trim( frm.data_title.value ) == "" )
	{
		alert( "공고명을 입력하세요!" );
		frm.data_title.focus();
		return;
	}
	else if ( $.trim( frm.tmp_field5.value ) == "" )
	{
		alert( "접수기간을 입력하세요!" );
		frm.tmp_field5.focus();
		return;
	}
	else if ( $.trim( frm.tmp_field6.value ) == "" )
	{
		alert( "접수기간을 입력하세요!" );
		frm.tmp_field6.focus();
		return;
	}
	else if ( $.trim( frm.tmp_field8.value ) == "" )
	{
		alert( "근무지역을 입력하세요!" );
		frm.tmp_field8.focus();
		return;
	}
	if ( $.trim( frm.tmp_field1.value ) == "" )
	{
		alert( "자기소개서 질문문구를 입력하세요!" );
		frm.tmp_field1.focus();
		return;
	}
	if ( $.trim( frm.tmp_field2.value ) == "" )
	{
		alert( "자기소개서 질문문구를 입력하세요!" );
		frm.tmp_field2.focus();
		return;
	}
	if ( $.trim( frm.tmp_field3.value ) == "" )
	{
		alert( "자기소개서 질문문구를 입력하세요!" );
		frm.tmp_field3.focus();
		return;
	}

<?php // 에디터 사용시
if ( $setting->isuse_editor == "Y" ) {
?>
		// editor's function
		saveContent();
<?php } else { ?>
	if ( $.trim( frm.data_content.value ) == "" )
	{
		alert( "내용을 입력하세요!" );
		frm.data_content.focus();
		return;
	}
	else
	{
		if ( confirm("등록하시겠습니까?" ) )
			document.mainform.submit();
	}
<?php } ?>
}

/* editor의 함수에서 호출 */
function filterString( contentString )
{
	var frm = document.mainform;
	var findKey = "<?php echo  str_replace( "\r\n", "", $setting->board_ban_content ) ?>";

	if ( $.trim( findKey ) != "" )
	{
		findKeys = findKey.split( "," );
		loopCount = findKeys.length;
		// 특수문자 제거<한,영,공백만 허용>
		var expr = /[^(가-힣ㄱ-ㅎㅏ-ㅣa-zA-Z0-9)|^(\s*)|(\s*$)]/gi;
		titleVal = frm.data_title.value;
		titleVal = titleVal.replace( expr, "" );
		contentString = contentString.replace( expr, "" );
		//content = $( "#data_content" ).text();

		for ( i = 0; i < loopCount; i++ )
		{
			if ( titleVal.indexOf(findKeys[i]) > -1) {
				alert(findKeys[i] + "은(는) 금지어입니다. 등록 할 수 없습니다.");
				return false;
			}
			if ( contentString.indexOf(findKeys[i]) > -1) {
				alert(findKeys[i] + "은(는) 금지어입니다. 등록 할 수 없습니다.");
				return false;
			}
		}
	}

	return true;
}
</script>

<link rel="stylesheet" type="text/css" href="/pages/board/css/board.css" />
<form name="mainform" id="mainform" method="post" action="process.php" enctype="multipart/form-data">
	<input type="hidden" name="menu_code" value="<?php echo $menu_code?>" />
	<input type="hidden" name="board_sid" value="<?php echo $board_sid?>" />
	<input type="hidden" name="data_sid" value="<?php echo $data_sid?>" />
	<input type="hidden" id="mode" name="mode" value="<?php echo $mode?>" />
	<input type="hidden" name="data_depth" value="<?php echo $data_depth?>" />
	<input type="hidden" name="attach_image" id="attach_image" value="" />
	<input type="hidden" name="attach_file" id="attach_file" value="" />
	<input type="hidden" name="previous_files_count" id="previous_files_count" value="<?php echo $previous_files_count?>" />
	<input type="hidden" name="attach_files_size" id="attach_files_size" value="<?php echo $up_files_size?>" />

	<input type="hidden" name="org_user_email" value="<?php echo $org_user_email?>" />
	<input type="hidden" name="org_user_name" value="<?php echo $org_user_name?>" />


	<div class="container board-write top-line">
		
<?php // 공지 게시 구분
if ( $setting->isuse_notice == "Y" && is_board_admin( $setting->user_sid ) ) { ?>
		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right">게시구분</label>
			<div class="col-lg-10 align-self-center">
				<input type="radio" name="data_notice_fl" value="X" <?php if ( $article->data_notice_fl == "X" || $article->data_notice_fl == "" ) echo "checked"?>  id="data_notice_fl01"><label for="data_notice_fl01">일반 게시물</label>
				<input type="radio" name="data_notice_fl" value="N" <?php if ( $article->data_notice_fl == "N" ) echo "checked"?> id="data_notice_fl02">  <label for="data_notice_fl02">공지 게시물</label>
			</div>
		</div>

<?php } else echo "<input type='hidden' name='data_notice_fl' value='X' />"; ?>
<?php if ( $setting->isuse_secret == "Y" ) { ?>
		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right">공개여부</label>
			<div class="col-lg-10 align-self-center">
				<input type="radio" name="data_secret_fl" value="N" id="data_secret_fl_y" <?php if ( $article->data_secret_fl != "Y" ) echo "checked"; ?> /><label for="data_secret_fl_y">공개</label>
				<input type="radio" name="data_secret_fl" value="Y" id="data_secret_fl_n" <?php if ( $article->data_secret_fl == "Y" ) echo "checked"; ?>/><label for="data_secret_fl_n">비공개</label>
			</div>
		</div>

<?php } else echo "<input type=\"hidden\" name=\"data_secret_fl\" value=\"N\" />"; ?>
<?php // 카테고리 
if ( count( $setting->board_category ) > 0 ) { ?>
		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require">분류</label>
			<div class="col-lg-10 align-self-center">
				<?php echo MakeRadio( "category_code1", $setting->board_category, $cate_code1, 3 );?>
			</div>
		</div>
<?php } ?>
<?php
if ( ( $mode == "ADD" && !is_logged() ) || ( $mode == "MOD" && !is_board_admin( $setting->user_sid ) && $board->isGuestArticle( $article->user_sid, $article->user_id ) ) ) { ?>
		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require">비밀번호</label>
			<div class="col-lg-10">
				<input type="password" name="user_pw" maxlength="8" value="" class="form-control" placeholder="비밀번호를 입력하세요!" title="비빌번호 입력" />
			</div>
		</div>

<?php
		// 스팸 방지키는 신규등록시만 사용
		if ( $mode == "ADD" ) {
		// spam 방지키
		$spamKeyArr = array( rand(0,9), rand(0,9), rand(0,9), rand(0,9) );
		$spamKey = AES_Encode( __AES_KEY, $spamKeyArr[0].$spamKeyArr[1].$spamKeyArr[2].$spamKeyArr[3] );
		$mixedSpamKey = str_repeat( rand(0,9), rand(1,2) ) . 
						"<span class=\"text-danger\">". $spamKeyArr[0] ."</span>". 
						str_repeat( rand(0,9), rand(1,2) ) . 
						"<span class=\"text-danger\">". $spamKeyArr[1] ."</span>". 
						str_repeat( rand(0,9), rand(1,2) ) . 
						"<span class=\"text-danger\">". $spamKeyArr[2] ."</span>". 
						str_repeat( rand(0,9), rand(1,2) ) . 
						"<span class=\"text-danger\">". $spamKeyArr[3] ."</span>". 
						str_repeat( rand(0,9), rand(1,2) );
?>
		<input type="hidden" name="spamEncode" value="<?php echo $spamKey?>" />
		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require">스팸방지코드</label>
			<div class="col-lg-10">
				<div class="mb-3"><?php echo $mixedSpamKey; ?></div>
				<input type="text" name="spam_key" value="" maxlength="20" class="form-control" placeholder="스팸방지코드 입력" title="스팸방지코드 입력" />
				<p class="mb-0 mt-2">위의 숫자 중 <span class="text-danger">붉은색 숫자</span>만 차례대로 입력하세요.</p>
			</div>
		</div>
<?php	
	}
}
?>
		
		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require">작성자</label>
			<div class="col-lg-10">
				<input type="text" name="user_nick" value="<?php echo $user_nick?>" <?php echo $readOnly?> maxlength="15" class="form-control" placeholder="작성자를 입력하세요!" title="작성자 입력" />
			</div>
		</div>

<?php // 메일 발송 사용시 필수
if ( $setting->isuse_mail == "Y" ) { ?>
		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right">이메일</label>
			<div class="col-lg-10 form-row">
				<div class="col-lg-4 mb-2 mb-lg-0">
					<input type="hidden" name="user_email" value="<?php echo $article->user_email?>" />
					<input type="text" name="user_email1" value="<?php echo $emails[0]  ?>" class="form-control" placeholder="이메일 아이디" title="이메일 아이디 입력" />
				</div>
				<div class="input-group col-6 col-lg-4">
					  <span class="input-group-addon" id="basic-addon1">@</span>
					  <input type="text" name="user_email2" value="" class="form-control" placeholder="이메일 도메인" title="이메일 도메인 입력" />
				 </div>
				 <div class="col-6 col-lg-4">
					<select class="email" name="email3" onChange="document.mainform.user_email2.value = this.value;">
						<option value="">직접입력</option>
						<?php echo getEmails( $emails[1] ) ?>
					</select>
				</div>
			</div>
		</div>
<?php } ?>
		
		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require">공고명</label>
			<div class="col-lg-10">
				<input type="text" name="data_title" id="data_title" value="<?php echo $data_title ?>" class="form-control" placeholder="공고명을 입력 하세요!" title="공고명 입력" />
			</div>
		</div>	
		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require">접수기간</label>
			<div class="col-lg-10">
				<input id="tmp_field5" class="inputbox" type="text" value="<?php echo $article->tmp_field5?>" size="12" name="tmp_field5" />
				<a id="icon_sdate" href="#this" onclick="$('#tmp_field5').focus()"><img src="/adm_menu/images/icon_calendar.gif"></a>
				~
				<input id="tmp_field6" class="inputbox" type="text" value="<?php echo $article->tmp_field6?>" size="12" name="tmp_field6" />
				<a id="icon_sdate" href="#this" onclick="$('#tmp_field6').focus()"><img src="/adm_menu/images/icon_calendar.gif"></a>
			</div>
		</div>	

		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require">근무지역</label>
			<div class="col-lg-10 align-self-center">
				<?php echo MakeRadio( "tmp_field8", $tmp_field8, $article->tmp_field8, 3 ); ?>
			</div>
		</div>

		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require">채용구분</label>
			<div class="col-lg-10 align-self-center">
				<input type="radio" name="tmp_field4" value="1" id="tmp_field4_1" <?php if($article->tmp_field4 == "" || $article->tmp_field4 == "1") { ?> checked <?php }?>> <label for="tmp_field4_1" class="mgb0 mgr10">정규직</label>
				<input type="radio" name="tmp_field4" value="2" id="tmp_field4_2" <?php if($article->tmp_field4 == "2") { ?> checked <?php }?>> <label for="tmp_field4_2" class="mgb0 mgr10">대체직</label>
			</div>
		</div>

		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require">자격증선택</label>
			<div class="col-lg-10">
				<input type="checkbox" name="tmp_field7[]" value="공인회계사" id="tmp_field7_1"> <label for="tmp_field7_1" class="mgb0 mgr10">공인회계사</label>
				<input type="checkbox" name="tmp_field7[]" value="공인변호사" id="tmp_field7_2"> <label for="tmp_field7_2" class="mgb0 mgr10">공인변호사</label>
				<input type="checkbox" name="tmp_field7[]" value="공인노무사" id="tmp_field7_3"> <label for="tmp_field7_3" class="mgb0 mgr10">공인노무사</label>
				<input type="checkbox" name="tmp_field7[]" value="법무사" id="tmp_field7_4"> <label for="tmp_field7_4" class="mgb0 mgr10">법무사</label>
				<input type="checkbox" name="tmp_field7[]" value="세무사" id="tmp_field7_5"> <label for="tmp_field7_5" class="mgb0 mgr10">세무사</label>
				</br>
				<input type="checkbox" name="tmp_field7[]" value="전산회계" id="tmp_field7_6"> <label for="tmp_field7_6" class="mgb0 mgr10">전산회계</label>
				<input type="checkbox" name="tmp_field7[]" value="전산세무" id="tmp_field7_7"> <label for="tmp_field7_7" class="mgb0 mgr10">전산세무</label>
				<input type="checkbox" name="tmp_field7[]" value="세무회계" id="tmp_field7_8"> <label for="tmp_field7_8" class="mgb0 mgr10">세무회계</label>
				<input type="checkbox" name="tmp_field7[]" value="경비지도사" id="tmp_field7_9"> <label for="tmp_field7_9" class="mgb0 mgr10">경비지도사</label>
				<input type="checkbox" name="tmp_field7[]" value="워드프로세서(1급)" id="tmp_field7_10"> <label for="tmp_field7_10" class="mgb0 mgr10">워드프로세서(1급)</label>
				</br>
				<input type="checkbox" name="tmp_field7[]" value="컴퓨터활용능력(1급또는2급)" id="tmp_field7_16"> <label for="tmp_field7_16" class="mgb0 mgr10">컴퓨터활용능력(1급또는2급)</label>
				<input type="checkbox" name="tmp_field7[]" value="콜센터전문상담사" id="tmp_field7_12"> <label for="tmp_field7_12" class="mgb0 mgr10">콜센터전문상담사</label>
				<input type="checkbox" name="tmp_field7[]" value="텔레마케팅관리사" id="tmp_field7_13"> <label for="tmp_field7_13" class="mgb0 mgr10">텔레마케팅관리사</label>
				<input type="checkbox" name="tmp_field7[]" value="CS리더스(관리사)" id="tmp_field7_14"> <label for="tmp_field7_14" class="mgb0 mgr10">CS리더스(관리사)</label>
				<input type="checkbox" name="tmp_field7[]" value="소비자전문상담사(1급또는2급)" id="tmp_field7_15"> <label for="tmp_field7_15" class="mgb0 mgr10">소비자전문상담사(1급또는2급)</label>
				</br>
				<input type="checkbox" name="tmp_field7[]" value="가스사용시설안전관리자" id="tmp_field7_21"> <label for="tmp_field7_21" class="mgb0 mgr10">가스사용시설안전관리자</label>
				<input type="checkbox" name="tmp_field7[]" value="전기기사" id="tmp_field7_17"> <label for="tmp_field7_17" class="mgb0 mgr10">전기기사</label>
				<input type="checkbox" name="tmp_field7[]" value="전기산업기사" id="tmp_field7_18"> <label for="tmp_field7_18" class="mgb0 mgr10">전기산업기사</label>
				<input type="checkbox" name="tmp_field7[]" value="공조냉동기계기능사" id="tmp_field7_19"> <label for="tmp_field7_19" class="mgb0 mgr10">공조냉동기계기능사</label>
				<input type="checkbox" name="tmp_field7[]" value="에너지관리기능사" id="tmp_field7_20"> <label for="tmp_field7_20" class="mgb0 mgr10">에너지관리기능사</label>
			</div>
		</div>	

		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require">자기소개서 질문문구1</label>
			<div class="col-lg-10">
				<input type="text" name="tmp_field1" value="<?php echo $article->tmp_field1 ?>" class="form-control" placeholder="자기소개서 질문문구1을 입력 하세요!" title="자기소개서 질문문구1 입력" />
			</div>
		</div>	

		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require">자기소개서 질문문구2</label>
			<div class="col-lg-10">
				<input type="text" name="tmp_field2" value="<?php echo $article->tmp_field2 ?>" class="form-control" placeholder="자기소개서 질문문구2을 입력 하세요!" title="자기소개서 질문문구2 입력" />
			</div>
		</div>	

		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require">자기소개서 질문문구3</label>
			<div class="col-lg-10">
				<input type="text" name="tmp_field3" value="<?php echo $article->tmp_field3 ?>" class="form-control" placeholder="자기소개서 질문문구3을 입력 하세요!" title="자기소개서 질문문구3 입력" />
			</div>
		</div>	
		
<?php // 에디터 사용시
if ( $setting->isuse_editor == "Y" ) {
?>
		<div class="row">
			<div class="col">
				<textarea id="data_content" name="data_content" style="display:none; width:100%"><?php echo stripslashes( $data_content )?></textarea>
			
				<?php include __BASE_PATH."/util/daumeditor-7.5.6/editor.php"; ?>
				<?php if ( trim( $data_content ) != "" ) { ?>
				<script type="text/javascript">loadContent();</script>
				<?php } ?>

			</div>
		</div>

<?php } else { ?>
		
		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require">내용</label>
			<div class="col-lg-10">
				<textarea id="data_content" name="data_content"  class="form-control" rows="8"><?php echo stripslashes( $article->data_content )?></textarea>
			</div>
		</div>

		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right">파일첨부</label>
			<div class="col-lg-10">
				<?php
				if ( $setting->isuse_list_img == "Y" ) 
					echo $board->getUploader()->imgUploader( $attImagesRep, "files", "IMAGE_REP", 1, 50, "", "목록용 이미지" );
				if ( $setting->img_upload_count > 0 ) 
					echo $board->getUploader()->imgUploader( $attImages, "files", "IMAGE", $setting->img_upload_count, 50, "", "이미지" );
				if ( $setting->file_upload_count > 0 ) 
					echo $board->getUploader()->imgUploader( $attFiles, "files", "FILE", $setting->file_upload_count, "", "", "파일" );
				?>
			</div>
		</div>	

<?php } ?>
			

	</div>
</form>


<div class="text-center mgt5">
	<a href="#this" onClick="checkform();" class="btn btn-lg btn-board-01" role="button">확인</a>
	<a href="<?php echo $setting->list_url . $board->queryString()?>" class="btn btn-lg btn-board-02" role="button">취소</a>
</div>
