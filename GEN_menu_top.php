<?php include('include/include.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
</head>
    <body topmargin="0">
		<!--<body onload="Login()" bgcolor="#6699cc" topmargin="0">-->
        <script language="JavaScript"><!--
            function showhide() {
                if (top.group.cols == "0,*")
                    top.group.cols = "145,*";
                else
                    top.group.cols = "0,*";
            }

        	function LogoutN(){
        		top.document.location="index.php?logout=1";
           	}
            //-->
        </script>
        <SCRIPT LANGUAGE="VBScript">
        <!--
        Function Dclient1_OnCallReceive(s)

            Dim sTrimmed
            sTrimmed = Trim(s)

            Dim url
            url = "AS_main_callreceive.php?phone="& sTrimmed

            'If Cint(Len(sTrimmed)) > 3 Then
                set wcover = window.open (url, "", "left="+ (screen.availWidth - 650) / 2 +",top="+ ((screen.availHeight - screen.availHeight * 0.05) - 500) / 2 +", height = 500, width = 650,toolbar=no, maximize=no, resizeable=no, status=no")
            'End If

        End Function

        Function Dclient1_OnCall(s)
            ' Obcinamy Energis
            Set re = New RegExp
            With re
            .Pattern = "^01066"
            .IgnoreCase = True
            .Global = True
            End With

            Dim sTrimmed
            sTrimmed = Trim(re.Replace(s, ""))

            Dim url
            url = "AS_main_call.php?phone="& sTrimmed
            'If Cint(Len(sTrimmed)) > 3 Then
                set wcover = window.open (url, "", "left="+ (screen.availWidth - 650) / 2 +",top="+ ((screen.availHeight - screen.availHeight * 0.05) - 300) / 2 +", height = 300, width = 650,toolbar=no, maximize=no, resizeable=no, status=no")
            'End If
        End Function

        Function Dclient1_OnStatusChange()
            'MsgBox(Cstr(Dclient1.Status))
            If Dclient1.Status = 2 Then ' Zalogowany
                btnSwitch.disabled=FALSE
                btnLogOff.disabled=FALSE
                msgShow.style.backgroundColor="green"
                msgShow.value="<?= GEN_MENU_ZALOGOWANY ?>"
                btnSwitch.style.backgroundColor="orange"
                btnSwitch.value="<?= GEN_MENU_PRZERWA ?>"
            ElseIf Dclient1.Status = 1 Then ' Przerwa albo wylogowany
                btnSwitch.disabled=FALSE
                btnLogOff.disabled=FALSE
                msgShow.style.backgroundColor="orange"
                msgShow.value="<?= GEN_MENU_PRZERWA ?>"
                btnSwitch.style.backgroundColor="green"
                btnSwitch.value="<?= GEN_MENU_POWROT ?>"
            ElseIf Dclient1.Status = 0 Then
                btnSwitch.disabled=TRUE
                btnLogOff.disabled=TRUE
                msgShow.style.backgroundColor="red"
                msgShow.value="<?= GEN_MENU_WYLOGOWANY ?>"
            End If
        End Function
        Function BreakSwitch()
            If Dclient1.Status = 2 Then
                Dclient1.StartBreak()
            ElseIf Dclient1.Status = 1 Then
                Dclient1.EndBreak()
            End If
        End Function
        Function Logout()
           // If Dclient1.Status = 2 Then
            //    Dclient1.Logout()
                top.document.location.href="index.php?logout=1"
            //End If
        End Function
        Function Login()
            'Dclient1.Login("<?php //echo $_SESSION["ext"] ?>")
            'Dclient1.Login("302")
        End Function
        -->
        </SCRIPT>
		<!--
        <OBJECT id="Dclient1" CODEBASE="control/Client.cab#Version=1,0,0,0" TYPE="application/x-oleobject" classid="clsid:27475B40-5012-44E2-9880-FF11F9E6348A">
	        <PARAM NAME="_Version" VALUE="65536">
	        <PARAM NAME="_ExtentX" VALUE="2646">
	        <PARAM NAME="_ExtentY" VALUE="1323">
	        <PARAM NAME="_StockProps" VALUE="0">
        </OBJECT>
		//-->
		<script>
		</script>
		<style>
			body {
				background: #6699cc;
			}
		</style>
    	<table border="0" width="100%" cellspacing="0" cellpadding="0">
    	    <tr valign="middle">
    	    	<td valign="bottom">
                	<a href="javascript:void(0);" onclick="showhide()" style="text-decoration: none" title="Menu podręczne"><font color="#ced9e2" style="font-size: 12pt" face="Webdings">1</font></a>
		        </td>
                <td valign="bottom" align="right">
                  <!--   <input id="msgShow" type="text" value="<?= GEN_MENU_WYLOGOWANY ?>" style="background: Red; text-align: center; width: 100px;" disabled>&nbsp;&nbsp;
                    <input id="btnSwitch" type="button" value="<?= GEN_MENU_PRZERWA ?>" style="background: Orange; color: #ffffff; width: 100px;" disabled onclick="BreakSwitch()">&nbsp;&nbsp; -->
                    <input id="btnLogOff" type="button" value="<?= GEN_MENU_WYLOGUJ ?>" style="background: Red; color: #ffffff; width: 100px;" onclick="LogoutN()">&nbsp;&nbsp;
                </td>
    		    <td align="right" valign="bottom" width="100">
    		        <img src="graphics/logo_april_pl2_small.gif" height="40" border="0">
    		    </td>
            </tr>
    	</table>
    </body>
</html>