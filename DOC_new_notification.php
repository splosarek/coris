<?php 

require_once('include/include_ayax.php'); 
include('include/email_util.php');
include('include/pdf_utils.php');

$case_id = getValue('case_id');
$sprawa	= getValue('case_id');

$case = new CorisCase($case_id);
$tow_id = $case->client_id;

$warta_lista = array (606,607,608,609,610,611,612,613,614,615,616,617,618,619,620,621,622,623,624,625,626,627,628,630,652) ;	

$warta = in_array($tow_id,$warta_lista) ? 1 : 0 ;

?>
<HTML>
<HEAD>
<META HTTP-EQUIV="content-type" CONTENT="TEXT/HTML; CHARSET=iso-8859-2">
<TITLE><?= AS_FORMSF_TITLE ?></TITLE>


<script language="javascript" src="Scripts/javascript.js"></script>
<script type="text/javascript" language="javascript" src="Scripts/mootools-core.js"></script>
<script type="text/javascript" language="javascript" src="Scripts/mootools-more.js"></script>
<script type="text/javascript" src="Scripts/Fx.ProgressBar.js"></script>
<script type="text/javascript" src="Scripts/Swiff.Uploader.js"></script>
<script type="text/javascript" src="Scripts/FancyUpload4.Attach.js"></script>
 
</HEAD>
<BODY bgcolor="#dfdfdf">

<FORM METHOD="POST" ACTION="DOC_new_notification_send.php?case_id=<?php echo $sprawa; ?>" target="_blank">
<input type="hidden" name="client_id" value="<?php echo $tow_id; ?>"> 
<input type="hidden" name="warta" value="<?php echo $warta; ?>"> 
<BR>
<TABLE bgcolor=#eeeeee ALIGN="CENTER" cellspacing=0 cellpadding=3 style="border: #000011 1px solid" SIZE=80%>
	<TR border=0>
		<TD WIDTH=650 align="left">
			<font size=2 face="verdana">
			<BR>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= AS_CASADD_KOSZTY ?>: <?php 
			
			if ($warta)	{	?>
			<BR>&nbsp;Coris: <input type="text" name="kosztyC" size=15 maxlength=15>
			<BR>Warta:	<input type="text" name="kosztyW" size=15 maxlength=15></TD>			
			<?php 
			}else{
			?>
				&nbsp;<input type="text" name="koszty" size=20 maxlength=20>
			<?php 
			}
			?>
		</TD>
	</TR>
	<TR>
		<TD WIDTH=650>
		<font size=1 face="verdana">
			<CENTER>			
			<HR ALIGN="CENTER" WIDTH=82%>	
			<?= AS_CASADD_TXTDODUWAGI ?>
			
			<BR></CENTER>
			<font size=3 face="arial narrow">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= COMMENTS ?>
			<BR>
			<CENTER>
			<TEXTAREA NAME=txtUwagi COLS=81 ROWS=<?php echo ( $warta ?  10 : 15 );	?> style="font-family: verdana"></TEXTAREA><BR>
			</CENTER>
			<BR>


			<CENTER><input type="submit" value="<?= AS_CASADD_PODGLWYDR ?>" style="color: blue; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 15pt; width: 120px; background: yellow" title="<?= AS_CASADD_PODGLWYDR ?>">
			  <br>

<?php
		$query = "SELECT case_id, client_id,number,year,type_id, name,fax1,coris_assistance_cases.paxname, coris_assistance_cases.paxsurname,email,coris_contrahents.country_id FROM coris_assistance_cases, coris_contrahents WHERE case_id = '$sprawa' AND client_id = contrahent_id";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		$do = $row['name'];
		$fax_do = str_replace('/',' ',$row['fax1']);
		$contrahent_id = $row['client_id'];
		$email = $row['email'];
		$paxname = $row['paxsurname'].' '.$row['paxname'];
		$email_temat = '';
		
		$case_no = $row['number'] . '/' . substr($row['year'], 2) . '/' . $row['type_id'] . '/' . $row['client_id'];
		
		if ($row['country_id'] == 'PL')
			$email_temat = 'NOWA SPRAWA: '.$row['paxsurname'].', '.$row['paxname'].', nasz nr: '.$case_no;
		else 
			$email_temat = 'NEW CASE: '.$row['paxsurname'].', '.$row['paxname'].', Our Ref.: '.$case_no;			

?>
<input type="hidden" name="contrahent_id" value="<?php echo $contrahent_id; ?>"> 
<input type="hidden" name="case_id" value="<?php echo $sprawa; ?>"> 
<input type="hidden" name="paxname" value="<?php echo $paxname; ?>"> 
<input type="hidden" name="lang" value="<?php echo $row['country_id']; ?>">

			  <table width="550"  border="0" cellpadding="5" cellspacing="0">
                <tr>
                  <td colspan="3" align="left"><b><?= SAVE_ONLY ?></b><input type="checkbox" name="save_only" value="1" <?php echo $contrahent_id==7592 ? 'checked' : ''; ?>></td>
                
                </tr>
                <tr>
                  <td width="20%" align="right"><?= AS_CASADD_FAXDO ?>: </td>
                  <td width="80%" ><input type="text" name="contrahent_name" size=60 maxlength=100 readonly="readonly" value="<?php echo $do; ?>"></td>
                  <td width="80%" rowspan="2" align="center" ><input name="send_fax" type="submit" style="color: blue; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 15pt; width: 120px; background: yellow" title="<?= AS_CASADD_WYSLFAX ?>" value="<?= AS_CASADD_WYSLFAX ?>"></td>
                </tr>
                <tr>
                  <td align="right"><?= AS_CASADD_FAXNR ?>: </td>
                  <td><input type="text" name="faxto" size=20 maxlength=20 value="<?php echo poprawNumer($fax_do); ?>"></td>
                </tr>
                <tr>
                  <td colspan="3" align="right" height="10"><HR ALIGN="CENTER" WIDTH=92%></td>
                </tr>
                <tr>
                  <td align="right"><?= EMAIL ?>: </td>
                  <td><input name="email_to" type="text" id="email_to" value="<?php echo $email; ?>" size=40 maxlength="240"></td>
                  <td rowspan="2" align="center"><input name="send_email" type="submit" style="color: blue; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 15pt; width: 120px; background: yellow" title="<?= AS_CASADD_WYSLFAX ?>" value="<?= AS_CASADD_WYSLEMAIL ?>"></td>
                </tr>   
                <tr>
                  <td align="right"><?= AS_FORMSF_TEMAT ?>: </td>
                  <td><input name="email_temat" type="text" id="email_temat" value="<?= $email_temat ?>" size=60 maxlength=200></td>
                </tr>
              </table>
			  <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</CENTER>
			<INPUT TYPE="HIDDEN" VALUE="<?php echo $sprawa; ?>" NAME="Sprawa">
		</TD>
	</TR>
</TABLE>
</FORM>
</BODY>
</HTML>