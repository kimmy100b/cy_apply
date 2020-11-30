<?php
include $_SERVER['DOCUMENT_ROOT']."/application/default.php";

// REFERER CHECK
//CheckRequest( "R" );
Check_Page_Use_Admin(__USER_ADMIN_NORMAL);

$skin = array();
$skin[] = "<tr>
				<td>[CHECK][NUM]</td>
				<td>[LINK][FIELD][/LINK]</td>
				<td>[LINK][GUBUN][/LINK]</td>
				<td>[LINK][NAME]([NAME_EN])[/LINK]</td>
				<td>[LINK][PHONE][/LINK]</td>
				<td>[LINK][MAIL][/LINK]</td>
				<td>[LINK][REG_DATE][/LINK]</td>
				<td>[ADOPT]</td>
				<td>[CHG_ADOPT]</td>
			</tr>";
$skin[] = "<tr><td colspan='12' bgcolor='#FFFFFF'>지원자가 없습니다.</td></tr>";

include_once __BASE_PATH."/function/util_func.php";
include __MODULE_PATH."/recruitment/RecruitmentAdmin.php";

$userObj	= new RecruitmentAdmin();
$listArr	= $userObj->makeList( $skin );
$listHtml	= $listArr[0];
$listPage	= $listArr[1];

$_menu_grp1 = 4;
$_menu_grp2 = 1;
include $admin_page_path."/include/header.boot.html";
?>
<script src="/application/js/jquery.inputmask.js" type="text/javascript"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
<script type="text/javascript" src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
<script type="text/javascript">
$(function() {
	makeCalendar( "sdate" );
	makeCalendar( "edate" );

	$("#sdate").inputmask("9999-99-99");
	$("#edate").inputmask("9999-99-99");
});

function add()
{
	document.location = "recruitmentWrite.php";
}

function checkForm()
{	
	frm = document.searchForm;
	frm.submit();
}

function showLayer( layerName )
{
	// 열려있는 div 닫기
	$("div:visible").hide();
	leftPos = 0;
	obj = document.getElementById( layerName );
	obj.style.top  = event.y + document.body.scrollTop - 5 + "px";
	obj.style.left = event.x - leftPos + "px";
	obj.style.display = "";
}

function loginUser(userSid)
{
	if ( confirm( "회원으로 로그인시 관리자는 자동 로그아웃 됩니다!\r\n이 회원으로 로그인 하시겠습니까?" ) )
	{
		var frm = document.userform;
		//var userLogin = window.open( "", "userLogin", "" );
		//frm.target = "userLogin";
		frm.action = "/adm_menu/include/login_user_copy.php";
		frm.userSid.value = userSid;
		frm.submit();
	}
}


$(function(){
	$(".mailSend").click( function(){		
		$("input[name='check[]']:checked").attr( "checked", false );
		$(this).parent().parent().first("td").find("input").attr("checked","true");
		mail();
	});
	$(".chgSelect").click( function(){
		$("input[name='check[]']:checked").attr( "checked", false );
		$(this).parent().parent().first("td").find("input").attr("checked","true");
	});	
});

var checked = false;
function checkAll()
{
	checked = !checked;
	$("input[name='check[]']").prop( "checked", checked );
}

//리스트 데이터 저장
function apply_list() {
    var frm = document.mainform;
    frm.mode.value = "LIST_ADD";

    var selectedCheck = new Array();
    $('.inputchk:checked').each(function() {
        var sid = $(this).val();
        selectedCheck.push(sid);
    });

    if (selectedCheck.length < 1) {
    alert("최소 1개 이상의 항목을 선택하셔야합니다.");
        return false;
    }
    frm.submit();
}

//합격문자발송
function send_msg() {
    var frm = document.mainform;
    frm.mode.value = "SEND_MSG";

    var selectedCheck = new Array();
    $('.inputchk:checked').each(function() {
        var sid = $(this).val();
        selectedCheck.push(sid);
    });

    if (selectedCheck.length < 1) {
    alert("최소 1개 이상의 항목을 선택하셔야합니다.");
        return false;
    }
    frm.submit();
}

//엑셀저장
function excel()
{
	document.searchForm.method = "post";
	document.searchForm.action = "recruitmentListExcel.php";
	document.searchForm.submit();

	document.searchForm.method = "get";
	document.searchForm.action = "<?php echo $_SERVER["PHP_SELF"] ?>";
}

</script>

	<!-- Main content -->
    <section class="content">
      <div class="container-fluid">  
		<div class="row">   
			<div class="col-12">
				  
				<div class="card card-success">
					<div class="card-header">
					  <h3 class="card-title">입사지원자 검색</h3>
					</div>
					<!-- /.card-header -->
					<div class="card-body">
<form name="searchForm" action="recruitmentList.php" method="get" >
							<table class="table table-bordered">
								<colgroup>
									<col class="col_sm">
									<col class="col_md">
									<col class="col_sm">
									<col class="">
								</colgroup>
								<tbody>
									 <tr>
										<th>지원분야</th>
										<td>
                                            <select name="category_code1">
												<option value="">::선택::</option>
												<option value="6"	<?php if ( $_GET['category_code1'] == "6" ) echo "selected"; ?>>보안업무직</option>
                                                <option value="7"	<?php if ( $_GET['category_code1'] == "7" ) echo "selected"; ?>>고객센터</option>		
												<option value="8"	<?php if ( $_GET['category_code1'] == "8" ) echo "selected"; ?>>환경미화</option>
											</select>
										</td>

										<th>지원자성명</th>
										<td>
											<input type="text" name="searchName" class="inputbox" value="<?php echo  $_GET['searchName'] ?>" size="30" />
										</td>
									 </tr>
									 <tr>
										<th>지원날짜</th>
										<td>
											<span class="smbr"><input id="sdate" class="inputbox" type="text" value="<?php echo $_GET['sDate']?>" size="12" name="sDate" />
											<a id="icon_sdate" href="#this" onclick="$('#sdate').focus()"><img src="/adm_menu/images/icon_calendar.gif"></a></span>
											~
											<span class="smbr"><input id="edate" class="inputbox" type="text" value="<?php echo $_GET['eDate']?>" size="12" name="eDate" />
											<a id="icon_sdate" href="#this" onclick="$('#edate').focus()"><img src="/adm_menu/images/icon_calendar.gif"></a></span>
										</td>
										<th>합격여부</th>
										<td>
                                            <select name="adoption">
												<option value="">::선택::</option>
												<option value="C" <?php if ( $_GET['adoption'] == "C" ) echo "selected"; ?>>지원완료</option>
                                                <option value="B"	<?php if ( $_GET['adoption'] == "B" ) echo "selected"; ?>>서류전형합격</option>		
                                                <option value="A"	<?php if ( $_GET['adoption'] == "A" ) echo "selected"; ?>>면접전형합격</option>		
                                                <option value="P"	<?php if ( $_GET['adoption'] == "P" ) echo "selected"; ?>>최종합격</option>		
												<option value="N"	<?php if ( $_GET['adoption'] == "N" ) echo "selected"; ?>>불합격</option>
											</select>
										</td>
									</tr>
								</tbody>
							</table>
</form>
						</div>
						<div class="card-footer tac">
							<a href="#this" onclick="checkForm();" class="btn btn-primary">검색</a>
						</div>
					</div>


					<div class="card card-primary">
					<div class="card-header">
					  <h3 class="card-title">입사지원자 목록</h3>
										<!-- 테이블 상단 영역 검색 -->
										<div class="card-tools">
						  <div class="input-group input-group-sm">
												<?php echo  number_format($userObj->curPage) ?> / <?php echo  number_format($userObj->totalPages) ?> pages
						  </div>
						</div>
										<!-- 테이블 상단 영역 검색 끝 -->
					</div>
					<!-- /.card-header -->
					<div class="card-body">
						<div class="over_table ">
							<table class="table table-bordered table-hover ov_size700">
									<colgroup>
										<col width="">
										<col width="">
										<col width="">
										<col width="">
										<col width="">
										<col width="">
										<col width="">
										<col width="">
									</colgroup>
									<tbody>
										<tr class="tac">
											<th><input type="checkbox" name="checkall" onClick="checkAll()"> 번호</th>
											<th>지원분야</th>
											<th>지원형태</th>
											<th>성명(영문)</th>
											<th>휴대폰번호</th>
											<th>Email</th>
											<th>지원날짜</th>
											<th>합격여부</th>
											<th>상태변경(합격)</th>
										</tr>
                                        <form name="mainform" action="recruitmentProcess.php" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="mode" value="" />
										    <?php echo $listHtml; ?>
                                        </form>
									</tbody>
								</table>
							</div>

							<div class="btn-paging">
								<?php echo $listPage; ?>
							</div>
              
						</div>
            <!-- /.card-body -->

						<div class="card-footer tac">
							<a href="#this" onClick="apply_list()" class="btn btn-primary">저장</a>
							<a href="#this" onClick="send_msg()" class="btn btn-primary">문자발송</a>
							<a href="#this" onclick="excel();" class="btn btn-danger">엑셀저장</a>
						</div>

				</div>
				<!-- /.card -->
			</div>
			<!-- /.col -->
		  </div>
		  <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->


<div id='ShowMsg' style="position:absolute;display:none;top:0;left:0;z-index:0;backgroundcolor:'#3300cc'">
	<div id='UserHistory' style="width:490px;bgcolor:'#3300cc'"></div>
</div>


<form name="writeform" action="" method="POST">
<input type="hidden" name="menuSid" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="menuLevel" value="">
</form>

<form name="userform" action="" method="POST">
<input type="hidden" name="userSid" value="" />
</form>

<!--footer -->
<?php include($admin_page_path."/include/footer.boot.html");?>
<!--//footer -->