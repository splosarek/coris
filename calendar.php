<?php 
session_start();
$lang = $_SESSION['GUI_language'];
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
    </head>
    <body bgcolor="#dfdfdf">
        <style>
            a, a:active, a:visited {
                color="#6699cc";
                text-decoration: none;
            }
            body {
                color="#000000";
                font-family: verdana;
                margin-top: 0.3cm;
                margin-bottom: 0.1cm;
                margin-left: 0.1cm;
                margin-right: 0.1cm;
            }
            td {
                font-size: 9pt;
            }
        </style>
        <script language="JavaScript1.2">
            <!--
            function Calendar(Month,Year) {

                var output = '<TABLE BGCOLOR="#d0d0d0" BORDER=0>';
                output += '<TR><TD ALIGN=CENTER WIDTH="15%"><A HREF="javascript:changeMonthL(' + Month + ',' + Year + ')"><font  size="2" color="#eeeeee"> &lt;&lt; </font></A></TD>';
                    output += '<TD ALIGN=CENTER WIDTH="70%"><FONT SIZE="1" COLOR="#6699cc"><b>' + names[Month] + ' ' + Year + '</b></FONT></TD>';
                output += '<TD ALIGN=CENTER WIDTH="15%"><A HREF="javascript:changeMonthG(' + Month + ',' + Year + ')"><font size="2" color="#eeeeee"> &gt;&gt; </font></A><\/TD></TR>';
                output += '<TR><TD ALIGN=CENTER COLSPAN=3>';

                firstDay = new Date(Year,Month,1);
                startDay = firstDay.getDay();

                if (((Year % 4 == 0) && (Year % 100 != 0)) || (Year % 400 == 0))
                     days[1] = 29;
                else
                     days[1] = 28;

                output += '<TABLE CALLSPACING=0 CELLPADDING=0 BORDER=0 BGCOLOR="#6699cc"><TR bgcolor="ffffff">';

                for (i=0; i<5; i++)
                    output += '<TD WIDTH=30 ALIGN=CENTER VALIGN=MIDDLE><FONT COLOR="#6699cc"><B>' + dow[i] +'<\/B><\/FONT><\/TD>';
                for (i=5; i<7; i++)
                    output += '<TD WIDTH=30 ALIGN=CENTER VALIGN=MIDDLE><FONT COLOR="red"><B>' + dow[i] +'<\/B><\/FONT><\/TD>';

                output += '<\/TR><TR ALIGN=CENTER VALIGN=MIDDLE>';

                var column = 0;
                var lastMonth = Month - 1;
                if (lastMonth == -1) lastMonth = 11;

                for (i=1; i<startDay; i++, column++)
                    output += '<TD WIDTH=30 HEIGHT=20><FONT COLOR="#666666">' + (days[lastMonth]-startDay+i+1) + '<\/FONT><\/TD>';

                for (i=1; i<=days[Month]; i++, column++) {

                    var day;
                    if (i < 10) {
                        day = '0' + i;
                    } else {
                        day = i;
                    }

                    var month = Month + 1;
                    if (month < 10) {
                        month = '0' + month;
                    }
                        
                    if (i == new Date().getDate() && Month == new Date().getMonth() && Year == new Date().getYear())	{
                        output += '<TD WIDTH=30 HEIGHT=20 bgColor="#336699" STYLE="CURSOR: default" ONMOUSEOVER="this.bgColor=\'#dddddd\'" ONMOUSEOUT="this.bgColor=\'#336699\'" ONCLICK="opener.document.getElementById(\'<?= $_GET['name'] ?>_d\').value=\'' + day + '\'; opener.document.getElementById(\'<?= $_GET['name'] ?>_m\').value=\'' + month + '\'; opener.document.getElementById(\'<?= $_GET['name'] ?>_y\').value=\'' + Year + '\'; window.close();">'
                     } else {  
                         output += '<TD WIDTH=30 HEIGHT=20 bgColor="#6699cc" STYLE="CURSOR: default" ONMOUSEOVER="this.bgColor=\'#dddddd\'" ONMOUSEOUT="this.bgColor=\'\'" ONCLICK="opener.document.getElementById(\'<?= $_GET['name'] ?>_d\').value=\'' + day + '\'; opener.document.getElementById(\'<?= $_GET['name'] ?>_m\').value=\'' + month + '\'; opener.document.getElementById(\'<?= $_GET['name'] ?>_y\').value=\'' + Year + '\'; window.close();">'
                     }

                  if ((column >= 0) && (column < 5)) {
                      output += '<FONT COLOR="#ffffff">' + i + '<\/FONT>' + '<\/TD>';
                  }
                  if (column == 5) {
                      output += '<FONT COLOR="#99ccee">' + i + '<\/FONT>' + '<\/TD>';
                  }
                    if (column == 6) {
                        output += '<FONT COLOR="#99ccee">' + i + '<\/FONT>' + '<\/TD>';
                        output += '<\/TR><TR ALIGN=CENTER VALIGN=MIDDLE>';
                        column = -1;
                    }
                }

                if (column > 0) {
                    for (i=1; column<7; i++, column++)
                        output +=  '<TD WIDTH=30 HEIGHT=20><FONT COLOR="#666666">' + i + '<\/FONT><\/TD>';
                }

                output += '<\/TR><\/TABLE><\/TD><\/TR><\/TABLE>';

                return output;
            }

            function changeMonthL(m,y) {
                if(m == 0){
                    opener.month = 11;
                    opener.year = y - 1;
                    }
                else {
                    opener.month = m - 1;
                    opener.year = y;
                    }
                location.href = 'calendar.php?name=<?= $_GET['name'] ?>';
            }

            function changeMonthG(m,y) {
                if(m == 11){
                    opener.month = 0;
                    opener.year = y + 1;
                    }
                else {
                    opener.month = m + 1;
                    opener.year = y;
                    }
                location.href = 'calendar.php?name=<?= $_GET['name'] ?>';
            }

            function makeArray0() {
                for (i = 0; i<makeArray0.arguments.length; i++)
                    this[i] = makeArray0.arguments[i];
            }

            var days      = new makeArray0(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        <?php if ($lang=='pl'){ ?>    
            var names   = new makeArray0('Styczeń','Luty','Marzec','Kwiecień','Maj','Czerwiec','Lipiec','Sierpień','Wrzesień','Październik','Listopad','Grudzień');            
            var dow       = new makeArray0('Pn','Wt','Śr','Cz','Pt','Sb','Nd');
        <?php }else{ ?>    
        		var names   = new makeArray0('January','February','March','April','May','June','July','August','September','October','November','December');            
        		var dow       = new makeArray0('So','Tu','We','Th','Fr','Sa','Su');
        <?php } ?>
            //-->
        </script>
        <center>
            <script language="JavaScript">
                <!--
                document.write(Calendar(opener.month,opener.year));
                //-->
            </script>
        </center>
    </body>
</html>
