<?php
  //���������� �����, � ������� ������� ������������ ��������� ������������ "��-����"
  $time=5;
  
  //������� ������, ��������� ������� ����������� ������ ���� ������ � ���� �����
$online='a:1:{i:0;s:43:"95acd2b64543208416fb973d7460f1d1|1131440177";}';
  
  $online=unserialize($online);    


//��������� ��������� windows-1251
header('Content-Type: text/html; charset=windows-1251');  

    //��������� "������������"
    $users = array('�������������', '������������', '������������');
  
  //���������������
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
  header('Cache-Control: no-store, no-cache, must-revalidate'); 
  header('Cache-Control: post-check=0, pre-check=0', FALSE); 
  header('Pragma: no-cache');
  
  //����������� �������� � ����������� ip-�������
  $ip[0]=$_SERVER['REMOTE_ADDR'];
  if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) 
    $ip[1]=$_SERVER['HTTP_X_FORWARDED_FOR'];  
  else
    $ip[1]='';
  
  //�������� ������ ��������
  $browser=$_SERVER['HTTP_USER_AGENT'];
  
  //�������� ��� �� ������ � ������������
  $user=md5($ip[0].$ip[1].$browser); 
  
  //����������� � ���� ����� ��������� ����������
  $str=$user."|".time();  
  
  
  
  //� ����� "�������" ���� ���������� �������������
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
  
  
  
  //������� ����������
  $count=count($online);
  echo 'document.write("'.$count.'");'."\n";


  //������� ����� "�����������.." � ������ ����������
  if(isset($_GET['usr']))
  {    
    $index = $count % 100;
    if ($index >=11 && $index <= 14) 
        $index = 0;
    else 
        $index = ($index %= 10) < 5 ? ($index > 2 ? 2 : $index): 0;
    
    echo 'document.write(" '.$users[$index].'");'."\n";        
    }
  
  //������ ���� ���� � ��������� ������ �������
  $file=file("online.php");
  $file[5]='$online=\''.serialize($online).'\''.";\n";
  
  //��������� �� ����������� ��� ������ �, � ������ ������, ���������� ����������� ������.
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