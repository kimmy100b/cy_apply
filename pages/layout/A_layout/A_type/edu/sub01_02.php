<?php
	include $_SERVER['DOCUMENT_ROOT']."/application/default.php";
	include_once __BASE_PATH."/function/util_func.php";

	include_once __MODULE_PATH."/recruitment/RecruitmentAdmin.php";
	$obj = new RecruitmentAdmin;

	$skin = array();
	$skin[] = "<div class='rec_result'>검색한 결과가 없습니다.</div>";

	//지원완료
	$skin[] = "<div class='rec_result'><p class='p-img'><img src=\"".getParentFolder(dirname(__FILE__))."/images/sub/icon_docu.gif\" alt=\"\" /></p><span class='bo'>[NAME]</span>님 [YEAR]년 (주)기보메이트  [FIELD] [GUBUN] 채용에 지원해주셔서 대단히 감사합니다.<br>
	현재 서류 검토 중에 있으며 추후 일정은 유선으로 개별 연락 드리도록 하겠습니다.<br>
	감사합니다.</div>";

	//서류전형 합격
	$skin[] = "<div class='rec_result'><p class='p-img'><img src=\"".getParentFolder(dirname(__FILE__))."/images/sub/icon_pass.gif\" alt=\"\" /></p><span class='bo'>[NAME]</span>님 [YEAR]년 (주)기보메이트  [FIELD] [GUBUN] 서류전형에 <span class='bo_b'>합격</span>을 축하드립니다!<br>
	추후 일정은 유선으로 개별 연락 드리도록 하겠습니다.<br>
	감사합니다.</div>";
	
	//면접전형 합격
	$skin[] = "<div class='rec_result'><p class='p-img'><img src=\"".getParentFolder(dirname(__FILE__))."/images/sub/icon_pass.gif\" alt=\"\" /></p><span class='bo'>[NAME]</span>님 [YEAR]년 (주)기보메이트  [FIELD] [GUBUN] 면접 전형 <span class='bo_b'>합격</span>을 축하드립니다!<br>
	추후 일정은 유선으로 개별 연락 드리도록 하겠습니다. <br>
	감사합니다.</div>";
	
	//불합격
	$skin[] = "<div class='rec_result'><p class='p-img'><img src=\"".getParentFolder(dirname(__FILE__))."/images/sub/icon_docu.gif\" alt=\"\" /></p><span class='bo'>[NAME]</span>님 [YEAR]년 (주)기보메이트  [FIELD] [GUBUN] 채용에 지원해주셔서 대단히 감사합니다.
	안타깝게도 불합격 소식을 전해드리게 되었습니다.<br>
	귀하의 뛰어난 자질과 소양에도 불구하고 제한된 선발인원과 여러 제약요인으로 인해 귀하를 우리 ㈜기보메이트의 직원으로 맞이하지 못하게 된 점을 매우 안타깝게 생각합니다.<br>
	귀하의 앞날에 무궁한 발전과 행운이 함께 하시길 진심으로 기원합니다. <br>
	다시 한 번 감사드립니다.</div>";
				
	if($_GET['cynum']!=""&&$_GET['mail']!=""){
		$listArr	= $obj->searchResult( $skin );
		$listHtml	= $listArr;
	}else{
		$listHtml	= $skin[0];
	}

?>
<script>

function checkForm()
{	
	var frm = document.searchForm;

	if ( $.trim( frm.cynum.value ) == "" )
	{
		alert( "수험번호를 입력하세요!" );
		frm.cynum.focus();
		return;
	}

	if ( $.trim( frm.mail.value ) == "" )
	{
		alert( "이메일을 입력하세요!" );
		frm.mail.focus();
		return;
	}

	frm.submit();
}

</script>

<link rel="stylesheet" type="text/css" href="/pages/board/css/board.css" />

<form name="mainform" action="<?php echo $setting->list_url?>" method="get">
</form>

<form name="searchForm" action="#this" method="GET" >
	<input type="hidden" name="menu_code" value="<?php echo $menu_code?>" />

<!-- 제공인력 교육신청 > 신청내역 조회 list  -->
<div id="peo_edu">
	<div class="peo_edus_con mgt3">			
		<div class="container">	
			<!-- 검색 -->
			<div class="filter-wrap">
				<div class="row_other"><!-- row_other -->
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"><!-- row_block -->
						<div class="blo_table">
							<div class="filter-label">
								수험번호
							</div>
							<div class="filter-list">
								<input type="text" class="form-control" name="cynum" id="cynum" value="<?php echo $_GET['cynum']?>">
							</div>
						</div>
					</div><!-- //row_block -->

					<div class="col-lg-6 col-md-6  col-sm-12 col-xs-12"><!-- row_block -->
						<div class="blo_table">
							<div class="filter-label">
								E-mail
							</div>
							<div class="filter-list">
								<input type="text" class="form-control" name="mail" id="mail" value="<?php echo $_GET['mail']?>">
							</div>
						</div>
					</div><!-- //row_block -->
			
					<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
						<span class="search_bt"><a href="#this" onclick="checkForm();">검색</a></span>
					</div>
				</div><!-- //row_other -->
			 </div>
			<!-- //검색 -->

			<div class="mgt2"><!-- 리스트 -->
			<?php echo $listHtml; ?>
		
			</div><!-- //리스트 -->
			<!-- 페이징  -->
			<div class="tac">
				<?php echo $listPage; ?>
			</div>
		</div><!-- container -->
	</div>
</div>
</form>