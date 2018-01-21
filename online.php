<?php
  //Êîëè÷åñòâî ìèíóò, â òå÷åíèè êîòîðûõ ïîëüçîâàòåëè ñ÷èòàþòñÿ íàõîäÿùèìèñÿ "îí-ëàéí"
  $time=5;
  
  //Îáëàñòü äàííûõ, ñëåäóþùàÿ ñòðî÷êà ÎÁßÇÀÒÅËÜÍÎ äîëæíà áûòü øåñòîé â ýòîì ôàéëå
$online='a:1:{i:0;s:43:"95acd2b64543208416fb973d7460f1d1|1131440177";}';
  
  $online=unserialize($online);    


//Óñòàíîâêà êîäèðîâêè windows-1251
header('Content-Type: text/html; charset=windows-1251');  

    //Îêîí÷àíèÿ "÷èñëèòåëüíûõ"
    $users = array('ïîëüçîâàòåëåé', 'ïîëüçîâàòåëü', 'ïîëüçîâàòåëÿ');
  
  //Àíòèêåøèðîâàíèå
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
  header('Cache-Control: no-store, no-cache, must-revalidate'); 
  header('Cache-Control: post-check=0, pre-check=0', FALSE); 
  header('Pragma: no-cache');
  
  //Îïðåäåëåíèå âíåøíåãî è âíóòðåííåãî ip-àäðåñîâ
  $ip[0]=$_SERVER['REMOTE_ADDR'];
  if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) 
    $ip[1]=$_SERVER['HTTP_X_FORWARDED_FOR'];  
  else
    $ip[1]='';
  
  //Ïîëó÷àåì äàííûå áðîóçåðà
  $browser=$_SERVER['HTTP_USER_AGENT'];
  
  //Ïîëó÷àåì õåø èç äàííûõ î ïîëüçîâàòåëå
  $user=md5($ip[0].$ip[1].$browser); 
  
  //Ïðèäåëûâàåì ê õåøó âðåìÿ ïîñëåäíåé àêòèâíîñòè
  $str=$user."|".time();  
  
  
  
  //Â öèêëå "óáèðàåì" âñåõ óñòàðåâøèõ ïîëüçîâàòåëåé
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
  
  
  
  //Âûâîäèì êîëè÷åñòâî
  $count=count($online);
  echo 'document.write("'.$count.'");'."\n";


  //Âûâîäèì ñëîâî "ïîëüçîâàòåë.." ñ íóæíûì îêîí÷àíèåì
  if(isset($_GET['usr']))
  {    
    $index = $count % 100;
    if ($index >=11 && $index <= 14) 
        $index = 0;
    else 
        $index = ($index %= 10) < 5 ? ($index > 2 ? 2 : $index): 0;
    
    echo 'document.write(" '.$users[$index].'");'."\n";        
    }
  
  //×èòàåì ýòîò ôàéë è îáíîâëÿåì øåñòóþ ñòðî÷êó
  $file=file("online.php");
  $file[5]='$online=\''.serialize($online).'\''.";\n";
  
  //Ïðîâåðÿåì íà äîñòóïíîñòü äëÿ çàïèñè è, â ñëó÷àå óñïåõà, çàïèñûâàåì îáíîâëåííûå äàííûå.
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
