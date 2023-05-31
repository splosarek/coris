<?php include('include/include.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<link rel="stylesheet" type="text/css" href="Styles/general.css">
</head>
<body>
        <style>
            body {
				background: #6699CC;
                margin-top: 0.1cm;
                margin-bottom: 0.1cm;
                margin-left: 0.1cm;
                margin-right: 0.1cm;
            }
            td {
                font-size: 7pt;
            }
        </style>
        <script language="JavaScript">
        <!--
            function RemoveInfo() {
                var values = "";
                checkedForm = top.info.form1;
					 if (checkedForm) {
						 if (checkedForm.elements.length == 1 && checkedForm.info.checked) {
							  values = checkedForm.info.value;
						 } else if (checkedForm.elements.length > 1) {
							  for (var i = 0; i < checkedForm.info.length; i++)
									if (checkedForm.info[i].checked)
										 values += checkedForm.info[i].value + ","; 
						 }
					 }
                if (values == "") {
                    alert("<?= GEN_FR_BRAKZAZNINF ?>");
                    return;
                } else {
                    if (!confirm("<?= GEN_FR_CZYNAPCHUS ?>"))
                        return;
                    top.info.document.location = "GEN_info_frame.php?info_id="+ values;
                }
            }
            function AddInfo() {
                window.open('GEN_info_menu_add.php?', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=400,height=135,left='+ (screen.availWidth - 400) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.05) - 135) / 2);
            }
        //-->
        </script>
        <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr>
                <td width="80%">
                    &nbsp;<font color="#ffffff"><?= GEN_FR_INFOR ?></font>
                </td>
                <td width="20%" align="right" valign="top">
<?
if ($_SESSION['user_id'] == 1 || $_SESSION['user_id'] == 2 || $_SESSION['user_id'] == 3 || $_SESSION['user_id'] == 4 || $_SESSION['user_id'] == 31 || $_SESSION['user_id'] == 18 || $_SESSION['user_id'] == 26 || $_SESSION['user_id'] == 76) {
?>
                    <input type="button" value="+" style="color: #dddddd; background: #6699cc; font-size: 9pt; font-weight: bold; line-height: 7pt; height: 15px; width: 30px" onclick="AddInfo();">&nbsp;<input type="button" value="-" style="color: #dddddd; background: #6699cc; font-size: 9pt; font-weight: bold; line-height: 7pt; height: 15px; width: 30px" onclick="RemoveInfo();"><br>
<?
} else {
    echo "&nbsp;";
}
?>
                </td>
            </tr>
        </table>
    </body>
</html>

