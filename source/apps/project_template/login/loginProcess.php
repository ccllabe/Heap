]<?php
        //session 參考網址：https://m.xp.cn/b.php/102162.html
        // https://medium.com/%E9%BA%A5%E5%85%8B%E7%9A%84%E5%8D%8A%E8%B7%AF%E5%87%BA%E5%AE%B6%E7%AD%86%E8%A8%98/%E7%AD%86%E8%A8%98-http-cookie-%E5%92%8C-session-%E4%BD%BF%E7%94%A8-19bc740e49b5
        error_reporting(0);


        //檢查有無課程
        if(!is_dir("../".$_POST[course] )){
            //echo "<script> document.location.href='login.php? msg=1 '; </script>";
            echo "<script>alert('無此課程'); document.location.href='login.php'; </script>";
            exit;
        }
         // 如果帳號和密碼正確的話，寫入Session變數，並視情況重導到相關的頁面
        if( !empty($_POST[uname]) && !empty($_POST[upass]) ){

             //利用帳號第一位字母判斷老師或學生
            $identity = preg_split('//', $_POST[uname] , -1, PREG_SPLIT_NO_EMPTY);
            if($identity[0]=="B"){
                $json_string = file_get_contents("../".$_POST[course]."/Students/student.json");// 從檔案中讀取資料到PHP變數
                $data = json_decode($json_string,true);// 把JSON字串轉成PHP陣列
                $authority="student";
            }else if($identity[0]=="A"){
                $json_string = file_get_contents("teacher.json");
                $data = json_decode($json_string,true);
                $authority="teacher";

            }


            $i=0;
            //是否存在使用者資訊
            while($i<count($data)){

                if($_POST[uname] == $data[$i]['ID'] && $_POST[upass] == $data[$i]['Passwd']){


                    //session_save_path(); path:/var/lib/php/sessions
                    exec('sudo ls /var/lib/php/sessions', $output, $return_var);
                    //藉由檔名:sess_學號，切割尋找是否之前有登入且沒消除session
                    for( $i=0 ; $i <count($output) ; $i++ ) {
                        $j=substr( $output[$i] , 0 , 13 );
                        if($j=="sess_".$_POST[uname]){
                            //刪除session
                            shell_exec('sudo rm /var/lib/php/sessions/'.$output[$i]);
                            //echo "<script>alert('您已在其他瀏覽器登入，無法重複登入!!');  </script>";

                        }
                    }

                    //設定session_id為帳號
                    session_id($_POST[uname].rand(0,1000000000000000));
                    // 啟動 Session
                    session_start();
                    // 寫入 Session 變數值
                    $_SESSION['course'] = $_POST[course];
                    $_SESSION['id'] = $_POST[uname];
                    $_SESSION['authority'] =$authority;
                    // 重導到相關頁面
                    header("Location: ../index.php");
                    exit;

                }
                $i++;
            }
            //帳號或密碼入錯誤
            //echo "<script> document.location.href='login.php? msg=error';</script>";
            echo "<script>alert('使用者名稱或密碼錯誤'); document.location.href='login.php'; </script>";
            exit;



        }else if( isset($_POST[uname]) && isset($_POST[upass])){
            //未輸入帳號密碼
            //echo "<script> document.location.href='login.php? msg=empty';</script>";
            echo "<script>alert('請輸入使用者名稱密碼'); document.location.href='login.php'; </script>";
            exit;

        }

?>
