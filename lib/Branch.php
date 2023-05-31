<?php


class Branch{

	static function getFaxNumber($branch){
		//$fax_nr = array(1 => '+48 22 864 55 23', 2 => '+49 89 43 607 565' , 3 => '+43 1 979 0181');
		$fax_nr = array(1 => '+48 22 864 55 23', 2 => '+49 89 37416645' , 3 => '+43 1 979 0181');
		return $fax_nr[$branch];
	}

	static function getPhoneNumber($branch){
		//$tel_nr = array(1 => '+48 22 864 55 22', 2 => '+49 89 43 607 560' , 3 => '+43 1 979 0180');
		$tel_nr = array(1 => '+48 22 864 55 22', 2 => '+49 89 37416643' , 3 => '+43 1 979 0180');
		return $tel_nr[$branch];
	}

	static function getEmailAddres($branch){
		$emails = array(1 => 'assistance@pl.april.com', 2 => 'assistance-germany@pl.april.com' , 3 => 'assistance-austria@pl.april.com');
		return $emails[$branch];
	}


	static function getFaxFooter($branch){
		if ($branch==1){
			return '<div align="center" style="margin: auto; width: 700px; text-align: left; margin-top:0px;border: #FFF solid 1px;">
	<table class="footerDE" border="0" width="700">
	<tr><td width="165"><img src="graphics/april_logo_assistance.gif" /></td>
	<td  style="margin-left:10px;font-size:8pt;color:#5A5445; font-family: Arial;">
			ul. Sienna 73, 00-833 Warszawa, Polska, Tel.: '.self::getPhoneNumber($branch).', Fax: '.self::getFaxNumber($branch).'
			<br/><strong>'.self::getEmailAddres($branch).', www.april-polska.pl</strong>
	</td>
	</tr></table>
		<div style="clear:both"></div>
		<div style="font-size:6pt;color:#8D8876;font-family: Arial;">
		NIP 1132626599, REGON 140578578
		<br/>kapita³ zak³adowy spó³ki -  350 000 PLN, KRS 0000262086, S±d Rejonowy dla m.st. Warszawy, XII Wydzia³ Gospodarczy Krajowego Rejestru S±dowego
		</div>
		<div style="clear:both"></div>
</div>';
		}

		if ($branch==2){
			return '<div align="center" style="margin: auto; width: 700px; text-align: left; margin-top:0px;border: #FFF solid 1px;">
	<table class="footerDE" border="0" width="700">
	<tr><td width="165"><img src="graphics/april_logo_assistance.gif" /></td>
	<td  style="margin-left:10px;font-size:8pt;color:#5A5445; font-family: Arial;">
			73, Sienna Strasse, 00-833 Warschau, Polen, Tel.: '.self::getPhoneNumber($branch).', Fax: '.self::getFaxNumber($branch).'
			<br/><strong>'.self::getEmailAddres($branch).', www.april-polska.pl</strong>
	</td>
	</tr></table>
		<div style="clear:both"></div>
		<div style="font-size:6pt;color:#8D8876;font-family: Arial;">
		Steueridentifikationsnr. 1132626599, Gewerbeanmeldungsnr. 140578578<br/> Gründungskapital der Gesellschaft -  350 000 PLN
Polnisches Handelsregister 0000262086, Amtsgericht für die Hauptstadt in Warschau, XII Wirtschaftsabteilung des polnischen Handelsregisters
		</div>
		<div style="clear:both"></div>

</div>';
		}

		if ($branch==3){
			return '<div align="center" style="margin: auto; width: 700px; text-align: left; margin-top:0px;border: #FFF solid 1px;">
	<table class="footerDE" border="0" width="700">
	<tr><td width="165"><img src="graphics/april_logo_assistance.gif" /></td>
	<td  style="margin-left:10px;font-size:8pt;color:#5A5445; font-family: Arial;">
			73, Sienna Strasse, 00-833 Warschau, Polen, Tel.: '.self::getPhoneNumber($branch).', Fax: '.self::getFaxNumber($branch).'
			<br/><strong>'.self::getEmailAddres($branch).', www.april-polska.pl</strong>
	</td>
	</tr></table>
		<div style="clear:both"></div>
		<div style="font-size:6pt;color:#8D8876;font-family: Arial;">
		Steueridentifikationsnr. 1132626599, Gewerbeanmeldungsnr. 140578578<br/> Gründungskapital der Gesellschaft -  350 000 PLN
Polnisches Handelsregister 0000262086, Amtsgericht für die Hauptstadt in Warschau, XII Wirtschaftsabteilung des polnischen Handelsregisters
		</div>
		<div style="clear:both"></div>

</div>';
		}
	}


	static function getEmailFooter($branch,$target=0){
		 if ($branch==1){

             if ($target == 13) {
                 return '
APRIL Assistance - Partner Hanse Merkur              
<br><a href="mailto:hansemerkur@pl.april.com">hansemerkur@pl.april.com</a>
<br>Tel: +48 22 864 55 22
<br>Tel: +48 22 481 05 40
<br>Fax: +48 22 864 55 23
<p><img src="graphics/logo_april_pl2.jpg"><br>

<br>HanseMerkur Reiseversicherung AG
<br>c/o APRIL Polska Sp.z o.o.
<br>Ul. Sienna 73
<br>00-833 Warszawa
';
             }else {
                 return '<a style="color: black; text-decoration: none" href="mailto:' . self::getEmailAddres($branch) . '">' . self::getEmailAddres($branch) . '</a> <br>
<p><img src="graphics/logo_april_pl2.jpg"><br>
<hr style="width: 30px" align="left"><br>
<img src="graphics/april-stopka.jpg" height=20><br>
<p>&nbsp;</p>
<P class=small>ul. Sienna 73 <BR>00-833 Warszawa <br>Polska <br>
Tel.: ' . self::getPhoneNumber($branch) . ' - Fax: ' . self::getFaxNumber($branch) . '<br>
<br>
<HR style="WIDTH: 30px" align=left>
<p class=small><A style="color: black; text-decoration: none"
href="http://www.april-polska.pl/">http://www.april-polska.pl/</A> </p>
<p class=mentionlegale><BR>NIP 1132626599, REGON 140578578, kapita³ zak³adowy
spó³ki -  350 000 z³ <BR>KRS 0000262086, S±d Rejonowy dla m.st. Warszawy, XII
Wydzia³ Gospodarczy Krajowego Rejestru S±dowego</p>';
             }
		 }

		 if ($branch==2){
		     if ($target == 12){
                 return '
April on behalf of Best Doctors                 
<br><a href="mailto:bestdoctors@april.com">bestdoctors@april.com</a>
<br>APRIL Assistance
<br>Erna-Scheffler-Straße 1a
<br>51103 Köln, Germany';
             }else if ($target == 9){
                 return 'Ihr APRIL Assistance Team - Partner von Barclaycard 
                 <br>
                 <br><a  href="mailto:Barclaycard-Reiseversicherung@april.com">Barclaycard-Reiseversicherung@april.com</a> 
                 <br>Tel.:  +49 322 21 09 34 67
                <br>Fax:  +49 322 21 09 34 68
<p><img src="graphics/logo_april_pl2.jpg"><br>
<p>&nbsp;</p>
<P><b>APRIL Assistance</b>
<br>Barclaycard Reiseversicherung
<br>c/o Deutz Cubus, 6. Etage
<br>Erna-Scheffler-Straße 1a
<br>51103 Köln
<br>Tel.:  +49 322 21 09 34 67
<br>Fax:  +49 322 21 09 34 68
<br>
';

             }else {
                 return '<a style="color: black; text-decoration: none" href="mailto:' . self::getEmailAddres($branch) . '">' . self::getEmailAddres($branch) . '</a> <br>
<p><img src="graphics/logo_april_pl2.jpg"><br>
<hr style="width: 30px" align="left"><br>
<img src="graphics/april-stopka.jpg" height=20><br>
<p>&nbsp;</p>
<P class=small>73, Sienna Strasse<BR>
00-833 Warschau<BR> Polen <br>
Tel.:' . self::getPhoneNumber($branch) . ' - Fax: ' . self::getFaxNumber($branch) . '<br>
<br>
<HR style="WIDTH: 30px" align=left>
<p class=small><A style="color: black; text-decoration: none"
href="http://www.april-polska.pl/">http://www.april-polska.pl/</A> </p>
<p class=mentionlegale><BR>Steueridentifikationsnr. 1132626599, Gewerbeanmeldungsnr. 140578578<br> Gründungskapital der Gesellschaft -  350 000 PLN
Polnisches Handelsregister 0000262086, Amtsgericht für die Hauptstadt in Warschau, XII Wirtschaftsabteilung des polnischen Handelsregisters</p>';
             }
		 }

		 if ($branch==3){
		 	return '<a style="color: black; text-decoration: none" href="mailto:'.self::getEmailAddres($branch).'">'.self::getEmailAddres($branch).'</a> <br>
<p><img src="graphics/logo_april_pl2.jpg"><br>
<hr style="width: 30px" align="left"><br>
<img src="graphics/april-stopka.jpg" height=20><br>
<p>&nbsp;</p>
<P class=small>73, Sienna Strasse<BR>
00-833 Warschau<BR> Polen <br>
Tel.: '.self::getPhoneNumber($branch).' - Fax: '.self::getFaxNumber($branch).'<br>
<br>
<HR style="WIDTH: 30px" align=left>
<p class=small><A style="color: black; text-decoration: none"
href="http://www.april-polska.pl/">http://www.april-polska.pl/</A> </p>
<p class=mentionlegale><BR>Steueridentifikationsnr. 1132626599, Gewerbeanmeldungsnr. 140578578<br> Gründungskapital der Gesellschaft -  350 000 PLN
Polnisches Handelsregister 0000262086, Amtsgericht für die Hauptstadt in Warschau, XII Wirtschaftsabteilung des polnischen Handelsregisters</p>';
		 }

	}
}
?>