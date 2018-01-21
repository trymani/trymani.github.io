<?php
  //Количество минут, в течении которых пользователи считаются находящимися "он-лайн"
  $time=5;
  
  //Область данных, следующая строчка ОБЯЗАТЕЛЬНО должна быть шестой в этом файле
$online='a:1:{i:0;s:43:"95acd2b64543208416fb973d7460f1d1|1131440177";}';
  
  $online=unserialize($online);    


//Установка кодировки windows-1251
header('Content-Type: text/html; charset=windows-1251');  

    //Окончания "числительных"
    $users = array('пользователей', 'пользователь', 'пользователя');
  
  //Антикеширование
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
  header('Cache-Control: no-store, no-cache, must-revalidate'); 
  header('Cache-Control: post-check=0, pre-check=0', FALSE); 
  header('Pragma: no-cache');
  
  //Определение внешнего и внутреннего ip-адресов
  $ip[0]=$_SERVER['REMOTE_ADDR'];
  if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) 
    $ip[1]=$_SERVER['HTTP_X_FORWARDED_FOR'];  
  else
    $ip[1]='';
  
  //Получаем данные броузера
  $browser=$_SERVER['HTTP_USER_AGENT'];
  
  //Получаем хеш из данных о пользователе
  $user=md5($ip[0].$ip[1].$browser); 
  
  //Приделываем к хешу время последней активности
  $str=$user."|".time();  
  
  
  
  //В цикле "убираем" всех устаревших пользователей
  for($i=0;$i<count($online);$i++)
  {
      $tmp=explode("|",$online[$i]);
      $online2[$i]=$tmp[0];
      
      $t=@$tmp[1];      
      if($t<time()-$time*60)
      {
        unset($online[$i]);
      }
      else
      {
          if($online2[$i]!=$user)
          {        
            $online3[]=$online[$i];
          }
      }     
  }
  
  $online=@$online3;  
  $online[]=$str;           
  
  
  
  //Выводим количество
  $count=count($online);
  echo 'document.write("'.$count.'");'."\n";


  //Выводим слово "пользовател.." с нужным окончанием
  if(isset($_GET['usr']))
  {    
    $index = $count % 100;
    if ($index >=11 && $index <= 14) 
        $index = 0;
    else 
        $index = ($index %= 10) < 5 ? ($index > 2 ? 2 : $index): 0;
    
    echo 'document.write(" '.$users[$index].'");'."\n";        
    }
  
  //Читаем этот файл и обновляем шестую строчку
  $file=file("online.php");
  $file[5]='$online=\''.serialize($online).'\''.";\n";
  
  //Проверяем на доступность для записи и, в случае успеха, записываем обновленные данные.
  if(is_writable("online.php"))
  {
      $f=fopen("online.php","w");
      flock($f,LOCK_EX);
      fwrite($f,join("",$file));
      flock($f,LOCK_UN);
      fclose($f);
  }
  else
  {
    echo 'document.write("Check file permissions!");'."\n";    
  }
   
       
?>