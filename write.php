<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title> :: ARTINEER :: </title>

    <link rel="stylesheet" href="./style/init.css">
    <link rel="stylesheet" href="./style/write.css">
    <link rel="stylesheet" href="./style/header.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="./ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="./scripts/header.js"></script>
    <link rel="shortcut icon" href="./favicon.ico">
  </head>
  <body>
    <?php
    include ("./db_connect.php");
    $connect = dbconn();
    $member = member();

    $id = $_GET['id'];
    $no = $_GET['no'];

    $bbsname = $_GET['bbsname'];

    if($bbsname == 'notice') {
      if(!$member['id'])Error("로그인 후 이용해 주세요.");
      else if(!$member['level'] == 0)Error("관리자만 이용할 수 있습니다.");
    }
    else if($bbsname == 'minutes') {
      if(!$member['id'])Error("로그인 후 이용해 주세요.");
      else if(!$member['level'] == 0)Error("관리자만 이용할 수 있습니다.");
    }
    else if($bbsname == 'hello') {
      if(!$member['id'])Error("로그인 후 이용해 주세요.");
    }
    else {
      if($member['level'] == 2)Error("관리자로부터 가입 승인 후 이용해 주세요.");
      else if(!$member['id'])Error("로그인 후 이용해 주세요.");
    }
    ?>
    <div class="wrap">

      <div id="header">
        <div class="header_position">

          <!-- header fixed logo -->
          <div class="header_fixed_img">
            <a href="./index.php" target="_self"><img src="./image/logo.png"></a>
          </div>

          <!-- header fixed user (login, register) -->
          <div class="header_fixed_user">
              <?php if(!$member['id']) {?>
                <div class="member"><a style="cursor:pointer;">로그인하세요</a></div>
              <div class="member_bubble">
                <li class="member_sub"><a style="cursor:pointer;" id="login_toggle">로그인</a></li>
                <li>
                  <div class="login" hidden>
                    <form action="./login_post.php" name="login" method="post" id="form">
                      <input type="text" name="id" size="10" id="id" placeholder="아이디">
                      <input type="password" name="pw" size="10" id="pw" placeholder="비밀번호">
                      <input type="submit" value="로그인" id="login_btn" style="cursor:pointer;">
                    </form>
                  </div>
                  <div id="message"></div>
                </li>
                <li class="member_sub"><a href="./register.php">회원가입</a></li>
              </div>
              <div class="tail"></div>
                    <?php } else {?>
                    <div class="member"><a style="cursor:pointer;"><?php echo $member['name']."(".$member['year']."기)";?></a></div>
                    <div class="member_bubble">
                    <li class="member_sub"><a href="./logout.php">로그아웃</a></li>
                    <li class="member_sub"><a href="./modify.php?no=<?=$member['no']?>&id=<?=$member['id']?>">정보수정</a></li>
                  </div>
                  <div class="tail"></div>
                    <?php }?>
          </div>

          <!-- header logo (ARTINEER) -->
          <div class="header_logo">
            <h2>ARTINEER</h2>
          </div>

          <!-- header category -->
          <div class="header_category">
            <ul>
              <li class="fixed_menu" id="about">
                <a href="./about.php" target="_self">ABOUT</a>
              </li>
                <li class="fixed_menu" id="notice">
                  <a href="#">NOTICE</a> <!-- 공지사항, 회의록, 가입인사 -->
                </li>
              <li class="fixed_menu" id="activity">
                <a href="#">ACTIVITY</a> <!-- 프로젝트, 활동사진, 자료실 -->
              </li>
              <li class="fixed_menu" id="note">
                <a href="./note.php" target="_self">NOTE</a>
              </li>
            </ul>
            <div class="notice_sub">
              <li class="sub">
                <a href="./notice.php" target="_self">공지사항</a>
              </li>
              <li class="sub">
                <a href="./minutes.php" target="_self">회의록</a>
              </li>
              <li class="last_sub">
                <a href="./hello.php" target="_self">가입인사</a>
              </li>
            </div>
            <div class="activity_sub">
              <li class="sub">
                  <a href="./project.php" target="_self">프로젝트</a>
                </li>
                <li class="sub">
                  <a href="./gallery.php" target="_self">활동사진</a>
                </li>
                <li class="sub">
                  <a href="./exam.php" target="_self">시험족보</a>
                </li>
                <li class="last_sub">
                  <a href="./reference.php" target="_self">자료실</a>
                </li>
            </div>
            <div class="menu_pointer">
              <div class="none_pointer"></div>
              <div class="pointer"><span id="notice_pointer" hidden>▼</span></div>
              <div class="pointer"><span id="activity_pointer" hidden>▼</span></div>
              <div class="none_pointer"></div>
            </div>
          </div>

        </div>
      </div>

      <!-- container -->
      <div id="container">
        <div class="container_position">

          <!-- 글쓰기 게시판 -->
          <div class="write_table_position">
            <form name="write" action="write_post.php" method="post" enctype="multipart/form-data">
              <?php if($bbsname == 'notice') {?><input type="hidden" name="bbsname" value="notice"><?}?>
              <?php if($bbsname == 'reference') {?><input type="hidden" name="bbsname" value="reference"><?}?>
              <?php if($bbsname == 'minutes') {?><input type="hidden" name="bbsname" value="minutes"><?}?>
              <?php if($bbsname == 'hello') {?><input type="hidden" name="bbsname" value="hello"><?}?>
              <?php if($bbsname == 'project') {?><input type="hidden" name="bbsname" value="project"><?}?>
              <?php if($bbsname == 'gallery') {?><input type="hidden" name="bbsname" value="gallery"><?}?>
              <?php if($bbsname == 'exam') {?><input type="hidden" name="bbsname" value="exam"><?}?>
              <?php
              if($bbsname == 'notice') {
                $query = "select * from notice where no='$no'";
              } else if($bbsname == 'reference') {
                $query = "select * from reference where no='$no'";
              } else if($bbsname == 'minutes') {
                $query = "select * from minutes where no='$no'";
              } else if($bbsname == 'hello') {
                $query = "select * from hello where no='$no'";
              } else if($bbsname == 'project') {
                $query = "select * from project where no='$no'";
              } else if($bbsname == 'gallery') {
                $query = "select * from gallery where no='$no'";
              } else if($bbsname == 'exam') {
                $query = "select * from exam where no='$no'";
              }
              mysqli_query($connect, "set names utf8");
              $result = mysqli_query($connect, $query);
              $data = mysqli_fetch_array($result);
              ?>
              <table class="write_table" border="1">
                <tr class="table_title">
                  <input type="hidden" name="no" value="<?=$no?>">
                  <td colspan="2"><input class="table_title_input" type="text" name="subject" placeholder="제목"></td>
                </tr>
                <tr class="table_body">
                  <td colspan="2" align="center">
                    <textarea id="editor" name="story"></textarea>
                    <script>
                        CKEDITOR.replace("story",{width: '100%', height: '400px'});

                    </script>
                  </td>
                </tr>
                <tr class="table_file_upload">
                  <td colspan="2"><input class="table_file_upload_btn" multiple="multiple" type="file" name="file[]" placeholder="첨부파일"></td>
                </tr>
                <tr class="table_ok">
                  <td colspan="2" align="right"><button class="table_btn" type="button" style="cursor:pointer;" onclick="history.back(-1)">취소</button><input class="table_btn" type="submit" style="cursor:pointer;" value="작성" onclick="submitContents()"></td>
                </tr>
              </table>
            </form>
          </div>
        </div>
      </div>

      <div id="footer">
        <div class="footer_fixed">
          <h4>© 2018. ARTINEER</h4>
          <h4>Design. THIRTY</h4>
          <h4>Development. THIRTY</h4>
        </div>
      </div>
    </div>

  </body>
</html>
