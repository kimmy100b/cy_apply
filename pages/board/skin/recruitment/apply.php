<?php
$emails = @explode( "@", $article->user_email );

$area = array('1' => '부산',
				'2' => '인천',
				'3' => '대전',
				'4' => '용인');

require_once __MODULE_PATH."/recruitment/Mobile_Detect.php";
$detect = new Mobile_Detect;

if ( $detect->isMobile() ) { 
	//die("모바일은 지원하지 않습니다.");
	//exit("모바일은 지원하지 않습니다.");
	errorMsg('모바일은 지원하지 않습니다.','BACK');
}

?>
<script type="text/javascript" src="<?php echo __SYSTEM_URL?>/js/util.js"></script>
<script src="/application/js/jquery.inputmask.js" type="text/javascript"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
<script type="text/javascript" src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
<script type="text/javascript">
$(function() {
	//달력
	makeCalendar( "office1_stdt" );
	makeCalendar( "office1_endt" );

	$("#office1_stdt").inputmask("9999-99-99");
	$("#office1_endt").inputmask("9999-99-99");

	makeCalendar( "office2_stdt" );
	makeCalendar( "office2_endt" );

	$("#office2_stdt").inputmask("9999-99-99");
	$("#office2_endt").inputmask("9999-99-99");

	makeCalendar( "office3_stdt" );
	makeCalendar( "office3_endt" );

	$("#office3_stdt").inputmask("9999-99-99");
	$("#office3_endt").inputmask("9999-99-99");

	makeCalendar( "office4_stdt" );
	makeCalendar( "office4_endt" );

	$("#office4_stdt").inputmask("9999-99-99");
	$("#office4_endt").inputmask("9999-99-99");

	makeCalendar( "office5_stdt" );
	makeCalendar( "office5_endt" );

	$("#office5_stdt").inputmask("9999-99-99");
	$("#office5_endt").inputmask("9999-99-99");

	//자격증
	var data = "<?php echo $article->license; ?>";
	if(data != ""){
		var jbSplit = data.split(',');
		for(var i=0;i<jbSplit.length;i++){
			$("input[name='license[]'][value='"+jbSplit[i]+"']").prop("checked",true);
		}
	}

});

function checkform()
{
	var frm = document.mainform;

	if ( $.trim( frm.name.value ) == "" )
	{
		alert( "성명(한글)을 입력하세요!" );
		frm.name.focus();
		return;
	}
	else if ( $.trim( frm.name_en.value ) == "" )
	{
		alert( "성명(영문)을 입력하세요!" );
		frm.name_en.focus();
		return;
	}
	else if ( $.trim( frm.address.value ) == "" )
	{
		alert( "주소를 입력하세요!" );
		frm.address.focus();
		return;
	}
	else if ( $.trim( frm.phone1.value ) == "" )
	{
		alert( "휴대폰 번호를 입력하세요!" );
		frm.phone1.focus();
		return;
	}
	else if ( $.trim( frm.phone2.value ) == "" )
	{
		alert( "휴대폰 번호를 입력하세요!" );
		frm.phone2.focus();
		return;
	}
	else if ( $.trim( frm.phone3.value ) == "" )
	{
		alert( "휴대폰 번호를 입력하세요!" );
		frm.phone3.focus();
		return;
	}
	else if ( $.trim( frm.mail.value ) == "" )
	{
		alert( "이메일을 입력하세요!" );
		frm.mail.focus();
		return;
	}
	//경력사항
	if($.trim( frm.office1.value ) != ""||$.trim( frm.division1.value ) != ""||$.trim( frm.rank1.value ) != ""||$.trim( frm.task1.value ) != ""
	||$.trim( frm.office1_stdt.value ) != ""||$.trim( frm.office1_endt.value ) != ""||$.trim( frm.etc_text1.value ) != ""){
		if($.trim( frm.office1.value ) == ""){
			alert( "근무처를 입력하세요!" );
			frm.office1.focus();
			return;
		}else if($.trim( frm.division1.value ) == ""){
			alert( "근무부서를 입력하세요!" );
			frm.division1.focus();
			return;
		}else if($.trim( frm.rank1.value ) == ""){
			alert( "직위를 입력하세요!" );
			frm.rank1.focus();
			return;
		}else if($.trim( frm.task1.value ) == ""){
			alert( "담당업무를 입력하세요!" );
			frm.task1.focus();
			return;
		}else if($.trim( frm.office1_stdt.value ) == ""){
			alert( "근무기간을 입력하세요!" );
			frm.office1_stdt.focus();
			return;
		}else if($.trim( frm.office1_endt.value ) == ""){
			alert( "근무기간을 입력하세요!" );
			frm.office1_endt.focus();
			return;
		}else if($.trim( frm.etc_text1.value ) == ""){
			alert( "퇴사사유를 입력하세요!" );
			frm.etc_text1.focus();
			return;
		}
	} else if($.trim( frm.office2.value ) != ""||$.trim( frm.division2.value ) != ""||$.trim( frm.rank2.value ) != ""||$.trim( frm.task2.value ) != ""
	||$.trim( frm.office2_stdt.value ) != ""||$.trim( frm.office2_endt.value ) != ""||$.trim( frm.etc_text2.value ) != ""){
		if($.trim( frm.office2.value ) == ""){
			alert( "근무처를 입력하세요!" );
			frm.office2.focus();
			return;
		}else if($.trim( frm.division2.value ) == ""){
			alert( "근무부서를 입력하세요!" );
			frm.division2.focus();
			return;
		}else if($.trim( frm.rank2.value ) == ""){
			alert( "직위를 입력하세요!" );
			frm.rank2.focus();
			return;
		}else if($.trim( frm.task2.value ) == ""){
			alert( "담당업무를 입력하세요!" );
			frm.task2.focus();
			return;
		}else if($.trim( frm.office2_stdt.value ) == ""){
			alert( "근무기간을 입력하세요!" );
			frm.office2_stdt.focus();
			return;
		}else if($.trim( frm.office2_endt.value ) == ""){
			alert( "근무기간을 입력하세요!" );
			frm.office2_endt.focus();
			return;
		}else if($.trim( frm.etc_text2.value ) == ""){
			alert( "퇴사사유를 입력하세요!" );
			frm.etc_text2.focus();
			return;
		}
	}else if($.trim( frm.office3.value ) != ""||$.trim( frm.division3.value ) != ""||$.trim( frm.rank3.value ) != ""||$.trim( frm.task3.value ) != ""
	||$.trim( frm.office3_stdt.value ) != ""||$.trim( frm.office3_endt.value ) != ""||$.trim( frm.etc_text3.value ) != ""){
		if($.trim( frm.office3.value ) == ""){
			alert( "근무처를 입력하세요!" );
			frm.office3.focus();
			return;
		}else if($.trim( frm.division3.value ) == ""){
			alert( "근무부서를 입력하세요!" );
			frm.division3.focus();
			return;
		}else if($.trim( frm.rank3.value ) == ""){
			alert( "직위를 입력하세요!" );
			frm.rank3.focus();
			return;
		}else if($.trim( frm.task3.value ) == ""){
			alert( "담당업무를 입력하세요!" );
			frm.task3.focus();
			return;
		}else if($.trim( frm.office3_stdt.value ) == ""){
			alert( "근무기간을 입력하세요!" );
			frm.office3_stdt.focus();
			return;
		}else if($.trim( frm.office3_endt.value ) == ""){
			alert( "근무기간을 입력하세요!" );
			frm.office3_endt.focus();
			return;
		}else if($.trim( frm.etc_text3.value ) == ""){
			alert( "퇴사사유를 입력하세요!" );
			frm.etc_text3.focus();
			return;
		}
	}else if($.trim( frm.office4.value ) != ""||$.trim( frm.division4.value ) != ""||$.trim( frm.rank4.value ) != ""||$.trim( frm.task4.value ) != ""
	||$.trim( frm.office4_stdt.value ) != ""||$.trim( frm.office4_endt.value ) != ""||$.trim( frm.etc_text4.value ) != ""){
		if($.trim( frm.office4.value ) == ""){
			alert( "근무처를 입력하세요!" );
			frm.office4.focus();
			return;
		}else if($.trim( frm.division4.value ) == ""){
			alert( "근무부서를 입력하세요!" );
			frm.division4.focus();
			return;
		}else if($.trim( frm.rank4.value ) == ""){
			alert( "직위를 입력하세요!" );
			frm.rank4.focus();
			return;
		}else if($.trim( frm.task4.value ) == ""){
			alert( "담당업무를 입력하세요!" );
			frm.task4.focus();
			return;
		}else if($.trim( frm.office4_stdt.value ) == ""){
			alert( "근무기간을 입력하세요!" );
			frm.office4_stdt.focus();
			return;
		}else if($.trim( frm.office4_endt.value ) == ""){
			alert( "근무기간을 입력하세요!" );
			frm.office4_endt.focus();
			return;
		}else if($.trim( frm.etc_text4.value ) == ""){
			alert( "퇴사사유를 입력하세요!" );
			frm.etc_text4.focus();
			return;
		}
	}else if($.trim( frm.office5.value ) != ""||$.trim( frm.division5.value ) != ""||$.trim( frm.rank5.value ) != ""||$.trim( frm.task5.value ) != ""
	||$.trim( frm.office5_stdt.value ) != ""||$.trim( frm.office5_endt.value ) != ""||$.trim( frm.etc_text5.value ) != ""){
		if($.trim( frm.office5.value ) == ""){
			alert( "근무처를 입력하세요!" );
			frm.office5.focus();
			return;
		}else if($.trim( frm.division5.value ) == ""){
			alert( "근무부서를 입력하세요!" );
			frm.division5.focus();
			return;
		}else if($.trim( frm.rank5.value ) == ""){
			alert( "직위를 입력하세요!" );
			frm.rank5.focus();
			return;
		}else if($.trim( frm.task5.value ) == ""){
			alert( "담당업무를 입력하세요!" );
			frm.task5.focus();
			return;
		}else if($.trim( frm.office5_stdt.value ) == ""){
			alert( "근무기간을 입력하세요!" );
			frm.office5_stdt.focus();
			return;
		}else if($.trim( frm.office5_endt.value ) == ""){
			alert( "근무기간을 입력하세요!" );
			frm.office5_endt.focus();
			return;
		}else if($.trim( frm.etc_text5.value ) == ""){
			alert( "퇴사사유를 입력하세요!" );
			frm.etc_text5.focus();
			return;
		}
	}
	if ( $.trim( frm.introduction1.value ) == "" )
	{
		alert( "자기소개서를 입력하세요!" );
		frm.introduction1.focus();
		return;
	}
	else if ( $.trim( frm.introduction2.value ) == "" )
	{
		alert( "자기소개서를 입력하세요!" );
		frm.introduction2.focus();
		return;
	}
	else if ( $.trim( frm.introduction3.value ) == "" )
	{
		alert( "자기소개서를 입력하세요!" );
		frm.introduction3.focus();
		return;
	}
	else if(!$("input:radio[id='agree_y']").is(":checked")){
		alert("개인정보에 관한 전문은 개인정보처리방침에 동의해주세요.");
		return false;
	}else{
		if ( confirm("등록하시겠습니까?" ) )
			document.mainform.submit();
	}
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
<form name="mainform" id="mainform" method="post" action="recruitmentProcess.php" enctype="multipart/form-data">
	<input type="hidden" name="menu_code" value="<?php echo $menu_code?>" />
	<input type="hidden" name="board_sid" value="<?php echo $board_sid?>" />
	<input type="hidden" name="data_sid" value="<?php echo $data_sid?>" />
	<input type="hidden" name="mode" value="<?php echo $mode?>" />
	<input type="hidden" name="reg_date" value="<?php echo $article->register_date ?>" />
	<input type="hidden" name="data_title" value="<?php echo $article->data_title ?>" />
	<input type="hidden" name="category_code1" value="<?php echo $article->category_code1 ?>" />
	<input type="hidden" name="tmp_field4" value="<?php echo $article->tmp_field4 ?>" />
	<input type="hidden" name="tmp_field8" value="<?php echo $article->tmp_field8 ?>" />
	<input type="hidden" name="data_depth" value="<?php echo $data_depth?>" />
	<input type="hidden" name="attach_image" id="attach_image" value="" />
	<input type="hidden" name="attach_file" id="attach_file" value="" />
	<input type="hidden" name="previous_files_count" id="previous_files_count" value="<?php echo $previous_files_count?>" />
	<input type="hidden" name="attach_files_size" id="attach_files_size" value="<?php echo $up_files_size?>" />
	<input type="hidden" name="org_user_email" value="<?php echo $org_user_email?>" />
	<input type="hidden" name="org_user_name" value="<?php echo $org_user_name?>" />


	<div class="container board-write top-line">
		
<?php // 카테고리 
if ( count( $setting->board_category ) > 0 ) { ?>
		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require">분류</label>
			<div class="col-lg-10 align-self-center">
				<?php
					if($article->category_code1 == "6"){
						echo "경영지원부";
					}else if($article->category_code1 == "7"){
						echo "고객센터";
					}else if($article->category_code1 == "8"){
						echo "보안업무팀";
					}else if($article->category_code1 == "9"){
						echo "시설관리팀";
					}else if($article->category_code1 == "10"){
						echo "환경미화팀";
					}
					
				?>
			</div>
		</div>
<?php } ?>

		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require">공고명</label>
			<div class="col-lg-10">
				<?php echo $article->data_title ?>
			</div>
		</div>	

		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require">근무지역</label>
			<div class="col-lg-10 align-self-center">
				<?php
					if($article->tmp_field8 == "1"){
						echo "부산";
					}else if($article->tmp_field8 == "2"){
						echo "인천";
					}else if($article->tmp_field8 == "3"){
						echo "대전";
					}else if($article->tmp_field8 == "4"){
						echo "용인";
					}					
				?>
			</div>
		</div>

		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require">채용구분</label>
			<div class="col-lg-10 align-self-center">
				<input type="radio" name="tmp_field4" value="<?php echo $article->tmp_field4 ?>" id="tmp_field4_1" disabled <?php if($article->tmp_field4 == "" || $article->tmp_field4 == "1") { ?> checked <?php }?>> <label for="tmp_field4_1" class="mgb0 mgr10">정규직</label>
				<input type="radio" name="tmp_field4" value="<?php echo $article->tmp_field4 ?>" id="tmp_field4_2" disabled <?php if($article->tmp_field4 == "2") { ?> checked <?php }?>> <label for="tmp_field4_2" class="mgb0 mgr10">대체직</label>
			</div>
		</div>		

		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require">성명(한글)</label>
			<div class="col-lg-10">
				<input type="text" name="name" value="<?php echo $name?>"  maxlength="15" class="form-control" placeholder="한글로 성명을 입력하세요!" title="지원자 입력" />
			</div>
		</div>

		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require">성명(영문)</label>
			<div class="col-lg-10">
				<input type="text" name="name_en" value="<?php echo $name_en?>"  maxlength="15" class="form-control" placeholder="영문으로 성명을 입력하세요!" title="지원자 입력" />
			</div>
		</div>

		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require">주소</label>
			<div class="col-lg-10">
				<input type="text" name="address" value="<?php echo $address?>" class="form-control" placeholder="주소를 입력하세요!" title="주소 입력" />
			</div>
		</div>

		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right">전화번호</label>
			<div class="col-lg-10">
				<input type="text" class="form-control tel" name="tel1" value="<?php echo $tel1?>"  maxlength="3" class="form-control" placeholder="" title="전화번호 입력" />
				-<input type="text" class="form-control tel" name="tel2" value="<?php echo $tel2?>"  maxlength="4" class="form-control" placeholder="" title="전화번호 입력" />
				-<input type="text" class="form-control tel" name="tel3" value="<?php echo $tel3?>"  maxlength="4" class="form-control" placeholder="" title="전화번호 입력" />
			</div>
		</div>

		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require">휴대폰 번호</label>
			<div class="col-lg-10">
			<p style="color:red">본인이 실사용중인 휴대전화 번호</p>
				<input type="text" class="form-control tel" name="phone1" value="<?php echo $phone1?>"  maxlength="15" class="form-control" placeholder="" title="휴대폰 번호 입력" />
				-<input type="text" class="form-control tel" name="phone2" value="<?php echo $phone2?>"  maxlength="15" class="form-control" placeholder="" title="휴대폰 번호 입력" />
				-<input type="text" class="form-control tel" name="phone3" value="<?php echo $phone3?>"  maxlength="15" class="form-control" placeholder="" title="휴대폰 번호 입력" />
			</div>
		</div>

		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require">Email</label>
			<div class="col-lg-10">
				<input type="mail" name="mail" value="<?php echo $mail?>"  maxlength="50" class="form-control" placeholder="이메일을 입력하세요!" title="이메일 입력" />
			</div>
		</div>

		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require">취업지원대상자 여부</label>
			<div class="col-lg-10">
				<input type="radio" name="support" value="Y" id="support_y"> <label for="support_y" class="mgb0 mgr10">예</label>
				<input type="radio" name="support" value="N" id="support_n" checked> <label for="support_n" class="mgb0 mgr10">아니요</label>
				<p style="color:red">국가보훈대상자, 국가유공자 등 예우 및 지원에 관한 법률에 해당하는 자 </p>
			</div>
		</div>

		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require">장애인 여부</label>
			<div class="col-lg-10">
				<input type="radio" name="disabled" value="Y" id="disabled_y"> <label for="disabled_y" class="mgb0 mgr10">예</label>
				<input type="radio" name="disabled" value="N" id="disabled_n" checked> <label for="disabled_n" class="mgb0 mgr10">아니요</label>
			</div>
		</div>

		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require">[국민기초생활보장법]상 수급자 여부</label>
			<div class="col-lg-10">
				<input type="radio" name="base" value="Y" id="base_y"> <label for="base_y" class="mgb0 mgr10">예</label>
				<input type="radio" name="base" value="N" id="base_n" checked> <label for="base_n" class="mgb0 mgr10">아니요</label>
			</div>
		</div>

		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require">[국민기초생활보장법]상 차상위계층 여부</label>
			<div class="col-lg-10">
				<input type="radio" name="caup" value="Y" id="caup_y"> <label for="caup_y" class="mgb0 mgr10">예</label>
				<input type="radio" name="caup" value="N" id="caup_n" checked> <label for="caup_n" class="mgb0 mgr10">아니요</label>
			</div>
		</div>

		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require">자격 및 면허</label>
		</div>

		<div class="row">
		<?php
			$license = $article->tmp_field7;

			if($license != ""){
				$data = split(",",$license);
				$cnt = count($data);

				for($i=0;$i<$cnt;$i++){ ?>
					<label class="col-form-label col-lg-2 text-lg-right"><?php echo $data[$i]; ?></label>
					<div class="col-lg-10">
						<input type="radio" name="license[<?php echo $i?>]" value="<?php echo $data[$i]?>" id="license[<?php echo $i?>]_y"> <label for="license[<?php echo $i?>]_y" class="mgb0 mgr10">유</label>
						<input type="radio" name="license[<?php echo $i?>]" value="" id="license[<?php echo $i?>]_n" checked> <label for="license[<?php echo $i?>]_n" class="mgb0 mgr10">무</label>
					</div>
			<?php
				}
			}else{
				echo "필요한 자격증이 없습니다.";
			}
			?>
			
		
		</div>

		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right">경력사항</label>
		</div>
		<table class="table table-striped">
			<thead>
				<tr>
					<th scope="col">근무처</th>
					<th scope="col">근무부서</th>
					<th scope="col">직위</th>
					<th scope="col">담당업무</th>
					<th scope="col">근무기간</th>
					<th scope="col">퇴사사유</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><input type="text" name="office1" value="<?php echo $office1?>"  maxlength="15" class="form-control" placeholder="근무처를 입력" title="근무처 입력" style="width:100%; border: 0;"></td>
					<td><input type="text" name="division1" value="<?php echo $division1?>"  maxlength="15" class="form-control" placeholder="근무부서를 입력" title="근무부서 입력" style="width:100%; border: 0;"></td>
					<td><input type="text" name="rank1" value="<?php echo $rank1?>"  maxlength="15" class="form-control" placeholder="직위를 입력" title="직위 입력" style="width:100%; border: 0;"></td>
					<td><input type="text" name="task1" value="<?php echo $task1?>"  maxlength="15" class="form-control" placeholder="담당업무를 입력" title="담당업무 입력" style="width:100%; border: 0;"></td>
					<td>
						<input id="office1_stdt" class="inputbox" type="text" value="<?php echo $office1_stdt?>" size="10" name="office1_stdt" />
						<a id="icon_sdate" href="#this" onclick="$('#office1_stdt').focus()"><img src="/adm_menu/images/icon_calendar.gif"></a>
						~
						<input id="office1_endt" class="inputbox" type="text" value="<?php echo $office1_endt?>" size="10" name="office1_endt" />
						<a id="icon_sdate" href="#this" onclick="$('#office1_endt').focus()"><img src="/adm_menu/images/icon_calendar.gif"></a>
					</td>
					<td><input type="text" name="etc_text1" value="<?php echo $etc_text1?>"  maxlength="15" class="form-control" placeholder="퇴사사유를 입력" title="퇴사사유 입력" style="width:100%; border: 0;"></td>
				</tr>
				<tr>
					<td><input type="text" name="office2" value="<?php echo $office2?>"  maxlength="15" class="form-control" placeholder="근무처를 입력" title="근무처 입력" style="width:100%; border: 0;"></td>
					<td><input type="text" name="division2" value="<?php echo $division2?>"  maxlength="15" class="form-control" placeholder="근무부서를 입력" title="근무부서 입력" style="width:100%; border: 0;"></td>
					<td><input type="text" name="rank2" value="<?php echo $rank2?>"  maxlength="15" class="form-control" placeholder="직위를 입력" title="직위 입력" style="width:100%; border: 0;"></td>
					<td><input type="text" name="task2" value="<?php echo $task2?>"  maxlength="15" class="form-control" placeholder="담당업무를 입력" title="담당업무 입력" style="width:100%; border: 0;"></td>
					<td>
						<input id="office2_stdt" class="inputbox" type="text" value="<?php echo $office2_stdt?>" size="10" name="office2_stdt" />
						<a id="icon_sdate" href="#this" onclick="$('#office2_stdt').focus()"><img src="/adm_menu/images/icon_calendar.gif"></a>
						~
						<input id="office2_endt" class="inputbox" type="text" value="<?php echo $office2_endt?>" size="10" name="office2_endt" />
						<a id="icon_sdate" href="#this" onclick="$('#office2_endt').focus()"><img src="/adm_menu/images/icon_calendar.gif"></a>
					</td>
					<td><input type="text" name="etc_text2" value="<?php echo $etc_text2?>"  maxlength="15" class="form-control" placeholder="퇴사사유를 입력" title="퇴사사유 입력" style="width:100%; border: 0;"></td>
				</tr>
				<tr>
					<td><input type="text" name="office3" value="<?php echo $office3?>"  maxlength="15" class="form-control" placeholder="근무처를 입력" title="근무처 입력" style="width:100%; border: 0;"></td>
					<td><input type="text" name="division3" value="<?php echo $division3?>"  maxlength="15" class="form-control" placeholder="근무부서를 입력" title="근무부서 입력" style="width:100%; border: 0;"></td>
					<td><input type="text" name="rank3" value="<?php echo $rank3?>"  maxlength="15" class="form-control" placeholder="직위를 입력" title="직위 입력" style="width:100%; border: 0;"></td>
					<td><input type="text" name="task3" value="<?php echo $task3?>"  maxlength="15" class="form-control" placeholder="담당업무를 입력" title="담당업무 입력" style="width:100%; border: 0;"></td>
					<td>
						<input id="office3_stdt" class="inputbox" type="text" value="<?php echo $office3_stdt?>" size="10" name="office3_stdt" />
						<a id="icon_sdate" href="#this" onclick="$('#office3_stdt').focus()"><img src="/adm_menu/images/icon_calendar.gif"></a>
						~
						<input id="office3_endt" class="inputbox" type="text" value="<?php echo $office3_endt?>" size="10" name="office3_endt" />
						<a id="icon_sdate" href="#this" onclick="$('#office3_endt').focus()"><img src="/adm_menu/images/icon_calendar.gif"></a>
					</td>
					<td><input type="text" name="etc_text3" value="<?php echo $etc_text1?>"  maxlength="15" class="form-control" placeholder="퇴사사유를 입력" title="퇴사사유 입력" style="width:100%; border: 0;"></td>
				</tr>
				<tr>
					<td><input type="text" name="office4" value="<?php echo $office4?>"  maxlength="15" class="form-control" placeholder="근무처를 입력" title="근무처 입력" style="width:100%; border: 0;"></td>
					<td><input type="text" name="division4" value="<?php echo $division4?>"  maxlength="15" class="form-control" placeholder="근무부서를 입력" title="근무부서 입력" style="width:100%; border: 0;"></td>
					<td><input type="text" name="rank4" value="<?php echo $rank4?>"  maxlength="15" class="form-control" placeholder="직위를 입력" title="직위 입력" style="width:100%; border: 0;"></td>
					<td><input type="text" name="task4" value="<?php echo $task4?>"  maxlength="15" class="form-control" placeholder="담당업무를 입력" title="담당업무 입력" style="width:100%; border: 0;"></td>
					<td>
						<input id="office4_stdt" class="inputbox" type="text" value="<?php echo $office4_stdt?>" size="10" name="office4_stdt" />
						<a id="icon_sdate" href="#this" onclick="$('#office4_stdt').focus()"><img src="/adm_menu/images/icon_calendar.gif"></a>
						~
						<input id="office4_endt" class="inputbox" type="text" value="<?php echo $office4_stdt?>" size="10" name="office4_endt" />
						<a id="icon_sdate" href="#this" onclick="$('#office4_endt').focus()"><img src="/adm_menu/images/icon_calendar.gif"></a>
					</td>
					<td><input type="text" name="etc_text4" value="<?php echo $etc_text4?>"  maxlength="15" class="form-control" placeholder="퇴사사유를 입력" title="퇴사사유 입력" style="width:100%; border: 0;"></td>
				</tr>
				<tr>
					<td><input type="text" name="office5" value="<?php echo $office5?>"  maxlength="15" class="form-control" placeholder="근무처를 입력" title="근무처 입력" style="width:100%; border: 0;"></td>
					<td><input type="text" name="division5" value="<?php echo $division5?>"  maxlength="15" class="form-control" placeholder="근무부서를 입력" title="근무부서 입력" style="width:100%; border: 0;"></td>
					<td><input type="text" name="rank5" value="<?php echo $rank5?>"  maxlength="15" class="form-control" placeholder="직위를 입력" title="직위 입력" style="width:100%; border: 0;"></td>
					<td><input type="text" name="task5" value="<?php echo $task5?>"  maxlength="15" class="form-control" placeholder="담당업무를 입력" title="담당업무 입력" style="width:100%; border: 0;"></td>
					<td>
						<input id="office5_stdt" class="inputbox" type="text" value="<?php echo $office5_stdt?>" size="10" name="office5_stdt" />
						<a id="icon_sdate" href="#this" onclick="$('#office5_stdt').focus()"><img src="/adm_menu/images/icon_calendar.gif"></a>
						~
						<input id="office5_endt" class="inputbox" type="text" value="<?php echo $office5_endt?>" size="10" name="office5_endt" />
						<a id="icon_sdate" href="#this" onclick="$('#office5_endt').focus()"><img src="/adm_menu/images/icon_calendar.gif"></a>
					</td>
					<td><input type="text" name="etc_text5" value="<?php echo $etc_text5?>"  maxlength="15" class="form-control" placeholder="퇴사사유를 입력" title="퇴사사유 입력" style="width:100%; border: 0;"></td>
				</tr>
			</tbody>
		</table>

		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right">자기소개서</label>
			<p>(간접적으로 학교명, 가족관계 등이 드러나지 않도록 유의)</p>
		</div>	
			
		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require"><?php echo $article->tmp_field1 ?></label>
			<div class="col-lg-10">
				<textarea id="introduction1" name="introduction1"  class="form-control" rows="8"></textarea>
			</div>
		</div>	
	
		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require"><?php echo $article->tmp_field2 ?></label>
			<div class="col-lg-10">
				<textarea id="introduction2" name="introduction2"  class="form-control" rows="8"></textarea>
			</div>
		</div>
		
		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require"><?php echo $article->tmp_field3 ?></label>
			<div class="col-lg-10">
				<textarea id="introduction3" name="introduction3"  class="form-control" rows="8"></textarea>
			</div>
		</div>
		
		<div class="row">
			<label class="col-form-label col-lg-2 text-lg-right require">서약사항</label>
		</div>
			<!-- 약관동의 -->
			<div class="agree_ch">
				<div class="agreement_box">
					<textarea readonly style="padding: 3em; height:200px;"><?php include __BOARD_PATH."/include/applyAgree.txt"; ?></textarea>
				</div>
				<div class="agreemet_txt">
					<p>위와 같이 지원서를 제출하며 기재내용에 허위사실이 있을 경우 합격․채용 취소에 대해 이의를 제기하기 않겠습니다.<br> 위 사항에 동의하십니까?</p>
					<div class="agree_check mgt1">
							<span><input type="radio" name="agree" value="Y" id="agree_y"> <label for="agree_y" class="mgb0 mgr10">동의함</label></span>
							<span><input type="radio" name="agree" value="N" id="agree_n" checked> <label for="agree_n" class="mgb0 mgr10">동의하지 않음</label></span>
					</div>
				</div>
			</div>
			<!-- //약관동의 -->
	</div>
</form>


<div class="text-center mgt5">
	<a href="#this" onClick="checkform();" class="btn btn-lg btn-board-01" role="button">확인</a>
	<a href="<?php echo $setting->list_url . $board->queryString()?>" class="btn btn-lg btn-board-02" role="button">취소</a>
</div>
