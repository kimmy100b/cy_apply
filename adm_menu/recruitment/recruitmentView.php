<?php
include $_SERVER['DOCUMENT_ROOT']."/application/default.php";
include_once __BASE_PATH."/function/util_func.php";

// REFERER CHECK
CheckRequest( "R" );
Check_Page_Use_Admin(__USER_ADMIN_NORMAL);

$mode = "VIEW";
$sid = trim( $_GET[applySid] );

//if ( $sid == "" ) errorMsg( "잘못된 요청입니다!", "BACK" );

include __MODULE_PATH."/recruitment/RecruitmentAdmin.php";
$object	= new RecruitmentAdmin();
$data = $object->getData($sid, $mode);

$gubun = array('1' => '정규직', '2' => '기간제 계약직');
$state = array('Y' => '해당', 'N' => '미해당');
$office = array($data['office1'],$data['office2'],$data['office3'],$data['office4'],$data['office5']);
$division = array($data['division1'],$data['division2'],$data['division3'],$data['division4'],$data['division5']);
$rank = array($data['rank1'],$data['rank2'],$data['rank3'],$data['rank4'],$data['rank5']);
$task = array($data['task1'],$data['task2'],$data['task3'],$data['task4'],$data['task5']);
$office_stdt= array($data['office1_stdt'],$data['office2_stdt'],$data['office3_stdt'],$data['office4_stdt'],$data['office5_stdt']);
$office_endt= array($data['office1_endt'],$data['office2_endt'],$data['office3_endt'],$data['office4_endt'],$data['office5_endt']);
$etc_text = array($data['etc_text1'],$data['etc_text2'],$data['etc_text3'],$data['etc_text4'],$data['etc_text5']);

$_menu_grp1 = 4;
$_menu_grp2 = 1;
include $admin_page_path."/include/header.boot.html";
?>

<link href="/css/sub_shop.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
<script type="text/javascript" src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
<?php
// 다음 우편번호 라이브러리 #post, #address, #address1
include(__MAP_PATH.'/pages/tool/daum_post.php');
?>
<script type="text/javascript">
function checkPass()
{
	return CheckPass( document.mainform.userPassword );
}

function checkForm()
{
	var frm = document.mainform;

	if ( $("input[name='loginState']:checked").length == 0 )
	{
		alert( "허용여부를 선택하세요!" );
		return false;
	}
	else if ( $("input[name='userLevel']:checked").length == 0 )
	{
		alert( "회원레벨을 선택하세요!" );
		return false;
	}
	else if ( trim( frm.userPassword.value ) != "" && !checkPass() )
	{
		return false;
	}

	if ( confirm("회원정보를 수정하시겠습니까?" ) )
	{
		frm.action = "memberProcess.php";
		frm.submit();
	}
	else return false;
}

function delApply()
{
	if ( confirm( "사용자를 삭제하시겠습니까?" ) )
	{
		var frm = document.mainform;
		frm.mode.value = "DEL";
		frm.action = "recruitmentProcess.php";
		frm.reUrl.value = "recruitmentList.php";
		frm.submit();
	}
}

// 회원정보 출력
function printMember()
{
	window.open( "memberView_print.php?sid=<?php echo $sid ?>", "memberWin", "width=700,height=600,scrollbars=1" );	
}
</script>

		<!-- Main content -->
    <section class="content">
      <div class="container-fluid">       
        <div class="col-12">
					
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">지원자 정보</h3>
            </div>


            <!-- /.card-header -->
            <div class="card-body">

<form name="mainform" action="" method="post">
<input type="hidden" name="mode" value="<?php echo $mode ?>" />
<input type="hidden" name="applySid" value="<?php echo $sid ?>" />
<input type="hidden" name="prevUserLevel" value="<?php echo $userInfo['userLevel'] ?>" />
<input type="hidden" name="prevLoginState" value="<?php echo $userInfo['loginState'] ?>" />
<input type="hidden" name="prevSsn" value="<?php echo $userInfo['coSsn'] ?>" />
<input type="hidden" name="reUrl" value="memberView.php" />

							<table class="table ft table-bordered">
								<colgroup>
									<col width="15%">
									<col width="">
								</colgroup>
								<tbody>	
									<tr>
										<th><b>수험번호</b></th>
										<td><?php echo $data['cynum'];?></td>
									</tr>		
									<tr>
										<th><b>분류</b></th>
										<td>
											<?php
												switch($data['field']){
													case 6:
														echo "경영자원부";
													break;
													case 7: 
														echo "고객센터";
													break;
													case 8: 
														echo "보안업무팀";
													break;
													case 9: 
														echo "시설관리팀";
													break;
													case 10: 
														echo "환경미화팀";
													break;
												}		
											?>
										</td>
									</tr>
									<tr>
										<th><b>공고명</b></th>
										<td><?php echo $data['data_title'];?></td>
									</tr>
									<tr>
										<th><b>근무지역</b></th>
										<td>
											<input type="radio" name="tmp_field8" value="<?php echo $data['tmp_field8'] ?>" id="tmp_field8_1" disabled <?php if($data['tmp_field8']== "" || $data['tmp_field8']== "1") { ?> checked <?php }?>> <label for="tmp_field8_1" class="mgb0 mgr10">부산</label>
											<input type="radio" name="tmp_field8" value="<?php echo $data['tmp_field8'] ?>" id="tmp_field8_2" disabled <?php if($data['tmp_field8']== "2") { ?> checked <?php }?>> <label for="tmp_field8_2" class="mgb0 mgr10">인천</label>
											<input type="radio" name="tmp_field8" value="<?php echo $data['tmp_field8'] ?>" id="tmp_field8_2" disabled <?php if($data['tmp_field8']== "3") { ?> checked <?php }?>> <label for="tmp_field8_2" class="mgb0 mgr10">대전</label>
											<input type="radio" name="tmp_field8" value="<?php echo $data['tmp_field8'] ?>" id="tmp_field8_2" disabled <?php if($data['tmp_field8']== "4") { ?> checked <?php }?>> <label for="tmp_field8_2" class="mgb0 mgr10">용인</label>
										</td>
									</tr>
									<tr>
										<th><b>채용구분</b></th>
										<td>
											<input type="radio" name="tmp_field4" value="<?php echo $data['gubun'] ?>" id="tmp_field4_1" disabled <?php if($data['gubun']== "" || $data['gubun']== "1") { ?> checked <?php }?>> <label for="tmp_field4_1" class="mgb0 mgr10">정규직</label>
											<input type="radio" name="tmp_field4" value="<?php echo $data['gubun'] ?>" id="tmp_field4_2" disabled <?php if($data['gubun']== "2") { ?> checked <?php }?>> <label for="tmp_field4_2" class="mgb0 mgr10">기간제 계약직</label>
										</td>
									</tr>
									<tr>
										<th><b>성명(영문)</b></th>
										<td><?php echo $data['name_kr']."(".$data['name_en'].")";?></td>
									</tr>				   
									<tr>
										<th><b>주소</b></th>
										<td><?php echo $data['address'];?></td>
									</tr>
									<tr>
										<th>전화번호</th>
										<td><?php echo $data['tel'];?></td>
									</tr>
									<tr>
										<th><b>휴대폰</b></th>
										<td><?php echo $data['phone'];?></td>
									</tr>
									<tr>
										<th>Email</th>
										<td><?php echo $data['mail'];?></td>
									</tr>
									<tr>
										<th><b>취업지원대상자 여부</b></th>
										<td>
											<select name="support_yn" disabled>
												<?php echo MakeOptions($state, $data['support_yn'])?>
											</select>
										</td>
									</tr>
									<tr>
										<th><b>장애인 여부</b></th>
										<td>
											<select name="disabled_yn" disabled>
											<?php echo MakeOptions($state, $data['disabled_yn'])?>
											</select>
										</td>
									</tr>
									<tr>
										<th><b>[국민기초생활보장법]상 수급자 여부</b></th>
										<td>
											<select name="base_yn" disabled>
											<?php echo MakeOptions($state, $data['base_yn'])?>
											</select>
										</td>
									</tr>
									<tr>
										<th><b>[국민기초생활보장법]상 차상위계층 여부</b></th>
										<td>
											<select name="caup_yn" disabled>
											<?php echo MakeOptions($state, $data['caup_yn'])?>
											</select>
										</td>
									</tr>
									<tr>
										<th><b>자격증</b></th>
										<td><?php echo $data['license'];?></td>										</td>
									</tr>
									<tr>
										<th><b>경력사항</b></th>
										<td>
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
													<?php
														for($i=0;$i<5;$i++){
															if($office[$i]!=""){
															?>
															<tr>
																<td><?php echo $office[$i];?></td>
																<td><?php echo $division[$i];?></td>
																<td><?php echo $rank[$i];?></td>
																<td><?php echo $task[$i];?></td>
																<td><?php echo $office_stdt[$i];?><br>~<?php echo $office_endt[$i];?></td>
																<td><?php echo $etc_text[$i];?></td>
															</tr>
													<?php	
															}
														}
													?>
												</tbody>
											</table>
										</td>
									</tr>
									<tr>
										<th><b>지원분야 관련 경력</b></th>
										<td><?php echo $data['same_office_date'];?></td>										
									</tr>
									<tr>
										<th><b><?php echo $data['tmp_field1'];?></b></th>
										<td><?php echo $data['introduction1'];?></td>										
									</tr>
									<tr>
										<th><b><?php echo $data['tmp_field2'];?></b></th>
										<td><?php echo $data['introduction2'];?></td>									
									</tr>
									<tr>
										<th><b><?php echo $data['tmp_field3'];?></b></th>
										<td><?php echo $data['introduction3'];?></td>										
									</tr>
									<tr>
										<th><b>원서접수일</b></th>
										<td><?php echo $data['reg_date'];?></td>										
									</tr>
									<tr>
										<th><b>서약동의일</b></th>
										<td><?php echo $data['reg_date'];?></td>										
									</tr>
								</tbody>
							</table>
</form>
						</div>
					</div>

					<div class="card card-secondary">
          

						<div class="card-footer tac">
							<a href="#this" onclick="delApply();" class="lpad02 btn btn-danger">삭제</a>
							<a href="recruitmentList.php" class="lpad02 btn btn-success">목록</a>	
						</div>

          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->

<!--footer -->
<?php include($admin_page_path."/include/footer.boot.html");?>
<!--//footer -->