<?php include('include/include.php'); 
include('include/pdf_utils.php');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
<body>
<TABLE ALIGN="CENTER" BORDER=0 SIZE="90%">
<tr>
<td>
<?

			$sprawa = $_GET['case_id'];
?>

<FORM METHOD="POST" ACTION="AS_cases_add_med_form_notification.php?case_id=<?php echo $sprawa; ?>" target="_blank">
<BR>
<TABLE bgcolor=#eeeeee ALIGN="CENTER" cellspacing=0 cellpadding=3 style="border: #000011 1px solid" SIZE=80%>
	<TR border=0>
		<TD WIDTH=650 align="left">
			<font size=2 face="verdana">
			<BR>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= AS_CASADD_KOSZTY ?>: &nbsp;<input type="text" name="koszty" size=20 maxlength=20>
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
			<TEXTAREA NAME=txtUwagi COLS=81 ROWS=15 style="font-family: verdana"></TEXTAREA><BR>
			</CENTER>
			<BR>


			<CENTER><input type="submit" value="<?= AS_CASADD_PODGLWYDR ?>" style="color: blue; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 15pt; width: 120px; background: yellow" title="<?= AS_CASADD_PODGLWYDR ?>">
			  <br>

<?
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
                  <td colspan="3" align="left"><b>Zapisz tylko w dokumentach</b><input type="checkbox" name="save_only" value="1" <?php echo $contrahent_id==7592 ? 'checked' : ''; ?>></td>
                
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
                  <td><input name="email_to" type="text" id="email_to" value="<?php echo $email; ?>" size=40 maxlength=50></td>
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
			<INPUT TYPE="HIDDEN" VALUE="<? echo $sprawa; ?>" NAME="Sprawa">
		</TD>
	</TR>
</TABLE>
</FORM>
</BODY>
</HTML>
