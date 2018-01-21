<?
global $password,$action,$num,$fdatas,$scrname,$npass;
require ("ip.inc");
if ($password != $pw)
{
$ip=getenv('REMOTE_ADDR');
$ipf=fopen($fip,"a+");
$yes=0;
$stk=0;
$datas=date("m.d.Y h:m:s");
$fcont=file($fip);
$count=count($fcont);
for ($kkk=0;$kkk<$count;$kkk++)
{
$cont[$kkk]=explode("::",$fcont[$kkk]);
}
if ($odip==0)
{
for ($stk;$stk<$count;$stk++)
{
	if ($cont[$stk][1]==$ip."\n") {$yes=1;}
}
}
if ($yes==0) {fwrite($ipf,$datas."::".$ip."\n");}
fclose($ipf);
}
if ($action=="admin")
{
?>
<html>
<head>
<title>Sh@ttl Soft: Посетители</title>
<link rel=STYLESHEET type="text/css" href="../style.css">
</head>
<body>
<div align=center>
<table border=0>
<form action=<?=$me?>>
<tr><td>Введите пароль:</td><td><input type=password name=password size=20></td><td><input type=submit value=OK></td>
</form>
</table>
<br><br>
Данный скрипт написан компанией <a href=http://shattlsoft.h10.ru>Sh@ttl Soft</a><br>
</div>
</body>
</html>
<?
exit;
}
if ($password==$pw)
{
        if ($action=="clear")
	{
        $idf=fopen($fip,"w");
        fclose($idf);
	header ("Location: $me?password=$password");
	exit;
	}
	if ($action=="del")
	{
       	$idf=fopen($fip,"r+");
	$fcon=file($fip);
        $fcon=array_reverse($fcon);
	$coun=count($fcon);
        fclose	($idf);
       	$idf=fopen($fip,"w");
        for($i=0;$i<$coun;$i++)
	   {
	      if($i!=$num){fwrite($idf,$fcon[$i]);}
	   }
	fclose	($idf);
	header ("Location: $me?password=$password");
	exit;
        }
	if ($action=="change")
	{
		$fch=fopen("ip.inc","w");
		fwrite($fch,"<?\n");
		fwrite($fch,"\$fip=\"$fdatas\";\n");
		fwrite($fch,"\$me=\"$scrname\";\n");
		fwrite($fch,"\$pw=\"$npass\";\n");
		$ooip=1;
		if (isset($osip)) {
		$ooip=0;
		}
		fwrite($fch,"\$odip=$ooip;\n");
		fwrite($fch,"?>");
		fclose($fch);
		header ("Location: $me?password=$npass");
		exit;
	}
	if ($action=="settings")
	{
	?>
<html>
<head>
<title>Sh@ttl Soft: Посетители - Настройка</title>
<link rel=STYLESHEET type="text/css" href="../style.css">
</head>
<body>
<div align=center>
<a href=<?=$me?>?password=<?=$password?>>Админ-центр</a><br><br>
<form action=<?=$me?>>
<input type=hidden name=password value=<?=$password?>>
<input type=hidden name=action value=change>
<table>
<tr>
<td>Имя файла с данными скрипта:</td>
<td><input name=fdatas size=20 value=<?=$fip?>></td>
</tr><tr>
<td>Имя файла скрипта:</td>
<td><input name=scrname size=20 value=<?=$me?>></td>
</tr><tr>
<td>Пароль:</td>
<td><input name=npass size=20 value=<?=$pw?>></td>
</tr>
<tr>
<td><input type=checkbox name=osip <? if ($odip==0) {echo("checked");} ?>>Не записывать повторно IP адреса</td>
</tr>
<tr><td align=center>
<input type=submit value="Сохранить настройки">
</td></tr>
</table>
<br><br>
Данный скрипт написан компанией <a href=http://shattlsoft.h10.ru>Sh@ttl Soft</a><br>
</div>
</body>
</html>
	<?
        exit;
	}
?>
<html>
<head>
<title>Sh@ttl Soft: Посетители</title>
<link rel=STYLESHEET type="text/css" href="../style.css">
</head>
<body>
<div align=center>
<a href=<?=$me?>?password=<?=$password?>&action=settings>Настройка</a><br><br>
<?
	$idf=fopen($fip,"r+");
	$fcon=file($fip);
        $fcon=array_reverse($fcon);
	$coun=count($fcon);
        for ($kdkk=0;$kdkk<$coun;$kdkk++)
	{
	$cont[$kdkk]=explode("::",$fcon[$kdkk]);
	}
        ECHO ("Всего посещений: $coun<br>");
	if ($coun>0){
	echo ("IP пользователей, заходивших на ваш сайт:<br><br>\n");
	$stz=0;
	echo ("<table border=1 cellpadding=0 cellspacing=0>\n<tr><td align=center>№</td><td align=center>IP</td><td align=center>Дата</td><td align=center>Время</td><td align=center>&nbsp;<a href=$me?password=$password&action=clear>Очистить</a>&nbsp;</td></tr>\n");
	for ($stz;$stz<$coun;$stz++)
	{
	echo("<tr>");
		$sth=$coun-$stz;
		$ecip=$cont[$stz][1];
		$ecda=explode(" ",$cont[$stz][0]);
		$ecdat=$ecda[0];
		$ectime=$ecda[1];
	 echo ("<td align=center>$sth.</td><td align=center>&nbsp;$ecip&nbsp;</td><td align=center>&nbsp;$ecdat&nbsp;</td><td align=center>&nbsp;$ectime&nbsp;</td><td align=center>&nbsp;<a href=$me?password=$password&action=del&num=$stz>Удалить</a>&nbsp;</td>\n");
	echo("</tr>");
	}
	echo ("</table>");
	}
	fclose($idf);
?>
<br><br>
Данный скрипт написан компанией <a href=http://shattlsoft.h10.ru>Sh@ttl Soft</a><br>
</div>
</body>
</html>
<?
}
?>
