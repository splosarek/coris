<?php
require_once('include/include_ayax.php');
require_once('include/pdf_utils.php');
$INTERCO = array(53, 54, 55, 59, 70, 71, 6050, 11358, 13850, 2231);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $attachments = array();
    $jezyk = getValue('jezyk');
    $expense_id = getValue('expense_id');
    $template_id = getValue('template_id');
    $send_type = getValue("send_type");
    $w_stopka = getValue('w_stopka');
    $case_id = getValue('case_id');
    $reclamation = getValue('reclamation') == 1 ? 1 : 0;

    $coris_case = new CorisCase($case_id);
    $branch = $coris_case->getBranchId();
    $target = 1;

    if ($branch == 2) { //niemcy
        if ($coris_case->getClient_id() == '17241') {
            $target = 9;
        } else if ($coris_case->getClient_id() == '18371') {
            $target = 12;
        } else if ($coris_case->getClient_id() == '17708') {
            $target = 10;
        } else if ($coris_case->getClient_id() == '18589') {
            $target = 13;
        } else {
            $target = 5;
        }
        $STOPKA_F = BRANCH::getFaxFooter(2, $target);
        $header = '<div align="center" style="margin-left:100px; width:770px; text-align: left;margin-bottom: 20px;"><img src="graphics/logo_april_pl.jpg"  width="148" height="120" /></div>';
        $margins = array('left' => 0, 'right' => 0, 'top' => 40, 'bottom' => 23);
    } else if ($branch == 3) { //austria
        $target = 6;
        $STOPKA_F = BRANCH::getFaxFooter(3);;
        $header = '<div align="center" style="margin-left:100px; width:770px; text-align: left;margin-bottom: 20px;"><img src="graphics/logo_april_pl.jpg"  width="148" height="120" /></div>';
        $margins = array('left' => 0, 'right' => 0, 'top' => 40, 'bottom' => 23);
    } else {
        if ($coris_case->getClient_id() == '18589') {
            $target = 13;
        }

        $STOPKA_FIRM_N = BRANCH::getFaxFooter(1, $target);
        $STOPKA_F = '<div  style="margin: auto; width: 700px; text-align: left; margin-top: 10px;border: #FFF solid 1px; ">' . $STOPKA_FIRM_N . '</div>';
        $header = '<div align="center" style="margin-left:100px; width:770px; text-align: left;margin-bottom: 20px;"><img src="graphics/logo_april_pl.jpg"  width="148" height="120" /></div>';
        $margins = array('left' => 0, 'right' => 0, 'top' => 40, 'bottom' => 15);
    }

    if ($template_id == '48') {
        save_taxi_in_cases($case_id);
    }

    if ($send_type == 'fax') {


        if (in_array($coris_case->getClient_id(), $INTERCO)) {
            $template_name = 'faxDE_INTERCO.html';

            $STOPKA_F = '<div style="width:770px;text-align: center; font-family: Arial;color:#5A5445;margin: auto;" align="center"><b>APRIL Assistance<br/> www.april.com </b></div>';
            $header = '<div align="center" style="margin-left:100px; width:770px; text-align: right;margin-bottom: 20px;"><img src="graphics/logo_april_pl.jpg"  width="148" height="120" /></div>';
            $margins = array('left' => 0, 'right' => 0, 'top' => 40, 'bottom' => 23);
            /*
             */
        } else if ($branch == 2 || $branch == 3) { //niemcy
            if ($jezyk == 'german') {
                //$template_name = 'DEfaxDE2.html';
                $template_name = 'faxDE_2017.html';
            } else {
                //$template_name = 'DEfaxEN.html';
                $template_name = 'faxEN_2017.html';
            }
        } else {
            if ($jezyk == 'french') {
                $template_name = 'faxFR_2017.html';
            } else if ($jezyk == 'german') {
                $template_name = 'faxDE_2017.html';
            } else if ($jezyk == 'polish') {
                $template_name = 'faxPL_2017.html';
            } else {
                $template_name = 'faxEN_2017.html';
            }
        }
        $page_template = new Template();
        $tmpl = $page_template->load_template(DIR_TEMPLATE . $template_name);
        if ($tmpl <> null) {
            $page_template->set('<!--NASZZNAK-->', getValue('case_number'));
            $page_template->set('<!--DATE-->', ($branch == 2 ? date("d.m.Y") : date("d/m/Y")));
            $page_template->set('<!--FROM_COMPANY-->', getValue('fromCo'));
            $page_template->set('<!--NUMERFAXU-->', getValue('fromFaxNo'));
            $page_template->set('<!--OPERATOR-->', getValue('editor'));
            $page_template->set('<!--NADAWCA-->', getValue('editor'));
            $page_template->set('<!--TONAME-->', stripslashes(getValue('contrahent_to_name')));
            $page_template->set('<!--COMPANY-->', stripslashes(getValue('contrahent_name')));
            $page_template->set('<!--TOFAXNO-->', getValue('faxto'));
            $page_template->set('<!--WASZZNAK-->', getValue('client_ref'));
            $page_template->set('<!--PAXNAME-->', getValue('paxname'));
            $fax_message = stripslashes(getValue('message'));
            $fax_message = str_replace('<!--ATTACHMENT-->', '<!--NewPage-->', $fax_message);

            $fax_message = str_replace('<!--NewPage-->', '</FONT></TD></TR></TABLE><!--NewPage--><TABLE align="center" cellpadding=0 cellspacing=0 width=700><TR><TD style="text-align: left; line-height: 150%"><FONT face="arial" size=3>', $fax_message);
            $page_template->set('<!--MESSAGE-->', $fax_message);
            $fax_out = $page_template->realize();

            $zm = array('�' => '&#260;', '�' => '&#379;', '�' => '&#346;', '�' => '&#377;', '�' => '&#262;', '�' => '&#211;', '�' => '&#321;', '�' => '&#323;', '�' => '&#280;',
                '�' => '&#261;', '�' => '&#380;', '�' => '&#347;', '�' => '&#378;', '�' => '&#263;', '�' => '&#243;', '�' => '&#322;', '�' => '&#324;', '�' => '&#281;',
                '�' => '&#246;', '�' => '&#252;'
            );

            $file = html2pdf_new($fax_out, strtr($STOPKA_F, $zm), strtr($header, $zm), $margins);

            if (file_exists($file) && filesize($file) > 0) { //dodawanie zalacznikow
                $file_upload = getValue('file_upload');
                $file_upload_org_name = getValue('file_upload_org_name');
                $file_upload_name = getValue('file_upload_name');
                $file_upload_type = getValue('file_upload_type');

                if (is_array($file_upload)) {
                    foreach ($file_upload as $poz) {
                        //$name = $poz;
                        $type = $file_upload_type[$poz];
                        $id = $file_upload_org_name[$poz];
                        $name = $file_upload_name[$poz];
                        $attachments[] = array('filename' => $name, 'type' => $type, 'content' => $id);
                    }
                }

                $dir_tmp = dirname(__FILE__) . "/tmp/";
                $lista_pdf = merge_fax_attachment_to_pdf($dir_tmp, $attachments);
                if ($lista_pdf === false) {
                    die("<br>Error merge PDF(1): <br>" . nl2br(print_r($attachments, 1)));
                }

                $file = PDFTools::mergePDF($dir_tmp, $file, $lista_pdf);
                if ($file === false) {
                    die("<br>Error merge PDF(2): " . $file . "<br>" . nl2br(print_r($lista_pdf, 1)));
                }
            }

            if (file_exists($file) && filesize($file) > 0) {
                if (isset($_POST['send'])) {//blokada wysylki

                    echo SEND_A_FAX;

                    $nr = poprawNumer(getValue('faxto'));

                    $contrahent_name = getValue('contrahent_name');
                    $contrahent_to_name = getValue('contrahent_to_name');
                    $paxName = getValue('paxname');
                    $template_id = getValue('template_id');

                    $faxObj = prepareFaxFromFile($nr, $file, $target);
                    $interactionObject = prepareSendFax($case_id, $faxObj, $contrahent_to_name, $contrahent_name . '/' . $nr, $paxName, $reclamation, $target, $template_id);
                    $interactionObject->send(0, $target);

                    if ($reclamation) CorisCase::set_case_reclamation($case_id);
                    if ($expense_id > 0) {
                        register_expense($expense_id, $interactionObject->getObjectId());
                    }

                    unlink($file);

                    echo '<br><b>' . FAX_SENT_TO_OUT_QUEUE . '</b><br>';
                    echo '<br><input type="button" value="' . INC_ZAMKOKNO . '" Onclick="window.close()">';
                } else {
                    sendPDF2Browser($file);
                    unlink($file);
                    exit ();
                }
            } else
                echo " ERROR file pdf " . $file;
        } else
            echo " ERROR template";


    } else if ($send_type == 'email') {
        include_once('include/file_utils.php');

        $email_to = strtolower(getValue('email_to'));
        $email_cc = strtolower(getValue('email_cc'));
        $email_temat = getValue('email_temat');
        $email_body = stripslashes(stripslashes(getValue('message')));

        if (strpos($email_to, ";"))
            $email_to = str_replace(";", ",", $email_to);
        if (strpos($email_cc, ";"))
            $email_cc = str_replace(";", ",", $email_cc);


        $attach = "";
        $poz = strpos($email_body, "<!--ATTACHMENT-->");
        $attach = "";
        if ($poz > 0) {
            $txt_email = substr($email_body, 0, $poz);
            $attach = substr($email_body, $poz + 17);
            $email_body = $txt_email . '</td></tr></tbody></table>';
        }
        if ($coris_case->getClient_id() == 17708) { // voyage prive

            $stopka = '<br><b>Ihr APRIL Assistance Team</b>';
            $stopka .= '<p><img src="graphics/logo_april_pl2.jpg"><br>';
            $stopka .= '<p>&nbsp;</p>';
            $stopka .= '<b>APRIL Assistance</b><br/>';
            $stopka .= "c/o Deutz Cubus, 6. Etage<br/>";
            $stopka .= "Erna-Scheffler-Stra�e 1a<br/>";
            $stopka .= '51103 K�ln<br/>';

            $stopka .= 'Tel.: +49 89 38 03 74 43<br/>';
            $stopka .= 'E-Mail: voyage-prive.de@april.com<br/>';
            $email_body .= '<br><br>' . $stopka;

        } else if (in_array($coris_case->getClient_id(), $INTERCO)) {

            $stopka = '<p><img src="graphics/logo_april_pl2.jpg"><br>';
            $stopka .= '<p>&nbsp;</p>';
            $stopka .= '<b>APRIL Assistance</b><br/>';
            $stopka .= "Erna-Scheffler-Str. 1a<br/>";
            $stopka .= 'DE-  51103 K�ln<br/>';
            $stopka .= 'assistance-germany@pl.april.com<br/>';
            $stopka .= 'Tel.: +49 89 380 37 444<br/>';
            $stopka .= 'Fax: +49 89 374 16 645<br/>';
            $email_body .= '<br><br>' . $stopka;

        } else if ($branch == 2) {
            if ($w_stopka == "") {
                $email_body .= '<br><br>' . Branch::getEmailFooter(2, $target);
            }
        } else if ($branch == 3) {

            if ($w_stopka == "") {
                //echo "<hr>jest1";
                $email_body .= '<br><br>' . Branch::getEmailFooter(3);
            }
        } else {
            if ($w_stopka == "") {
                $email_body .= '<br><br>' . Branch::getEmailFooter(1, $target);
            }
        }
        //'<!--DATE-->' => gmdate("d/m/Y"),
        $zmiany = array('<!--NASZZNAK-->' => $_POST['case_number'],
            '<!--DATE-->' => ($branch == 2 ? date("d.m.Y") : date("d/m/Y")),
            '<!--NUMERFAXU-->' => '+48 (22) 864 55 23',
            '<!--NADAWCA-->' => $_POST['editor'],
            '<!--TONAME-->' => stripslashes($_POST['contrahent_to_name']),
            '<!--COMPANY-->' => stripslashes($_POST['contrahent_name']),
            '<!--TOFAXNO-->' => $_POST['faxto'],
            '<!--WASZZNAK-->' => $_POST['client_ref'],
            '<!--PAXNAME-->' => $_POST['paxname'],
            '<!--NewPage-->' => '</FONT></TD></TR></TABLE><!--NewPage--><TABLE align="center" cellpadding=0 cellspacing=0 width=700><TR><TD style="text-align: left; line-height: 150%"><FONT face="arial" size=3>'
        );


        $attachments = array();

        if ($attach != "") {
            //$tmpl = load_template(DIR_TEMPLATE.'email_template.html');
            $zmiany_at = array('<!--MESSAGE-->' => stripslashes($attach));
            if ($tmpl <> null) {
                $email_attach = strtr($tmpl, $zmiany);
                $attach = strtr($email_attach, $zmiany_at);
                $file = html2pdf_new($attach);
                $attachments[] = array('content' => $file, 'type' => 'file', 'filename' => 'attach.pdf');
                $_POST['file_upload_type']['attach.pdf'] = 'upload';
                $_POST['file_upload_org_name']['attach.pdf'] = basename($file);
                $_POST['file_upload_name']['attach.pdf'] = 'attach.pdf';
            }

        }

        $template_name = 'email_template_april.html';
        $page_template = new Template();
        $tmpl = $page_template->load_template(DIR_TEMPLATE . $template_name);

        if ($tmpl <> null) {
            $page_template->set('<!--MESSAGE-->', stripslashes($email_body));
            $email_body = $page_template->realize();
        } else {
            echo " ERROR template";
            exit();
        }
        $file_upload = getValue('file_upload');
        $file_upload_org_name = getValue('file_upload_org_name');
        $file_upload_name = getValue('file_upload_name');
        $file_upload_type = getValue('file_upload_type');

        if (is_array($file_upload)) {
            foreach ($file_upload as $poz) {
                $type = $file_upload_type[$poz];
                $id = $file_upload_org_name[$poz];
                $name = $file_upload_name[$poz];
                $attachments[] = array('filename' => $name, 'type' => $type, 'content' => $id);
            }
        }

        $emailObject = prepareEmail($email_to, $email_cc, $email_temat, $email_body, $attachments, $target);
        if (isset($_POST['send'])) { //

            check_email($email_to);
            if ($email_cc != "")
                check_email($email_cc);
            $contrahent_name = getValue('contrahent_name');
            $contrahent_to_name = getValue('contrahent_to_name');
            $template_id = getValue('template_id');
            $bcc = '';
            $interactionObject = wysylka_email($case_id, $emailObject, $contrahent_to_name, $contrahent_name, $reclamation, $target, $template_id);

            if (in_array($template_id, array(66, 67))) {
                $bcc = 'barbara.warlikowska@pl.april.com';
                //echo "<hr>BCC: ".$bcc;
            }

            $res = $interactionObject->send(0, $target, $bcc);

            if ($reclamation) CorisCase::set_case_reclamation($case_id);
            if ($expense_id > 0) {
                register_expense($expense_id, $interactionObject->getObjectId());
            }

            if ($res) {
                echo INC_MAILWYSL . ':<br>';
            } else {
                echo 'Email send error:<br>';
            }
            echo '<br><input type="button" value="' . INC_ZAMKOKNO . '" Onclick="window.close()">';
        } else { // preview

            echo "<br>" . GEN_FAX_TO . ': ' . $emailObject->get_to();
            echo "<br>" . GEN_FAX_CC . ': ' . $emailObject->get_cc();
            echo "<br>" . GEN_FAX_TEM . ': ' . $emailObject->getName();

            echo "<br><br>" . GEN_FAX_ATT . ':';

            $att = $emailObject->getAttchments()->get_list();
            $ilosc = count($att);

            if ($ilosc > 0) {
                foreach ($att as $attachment) {
                    $file_name = $attachment->getName();
                    $link = $attachment->getNote();
                    echo '<br><a href="' . $link . '" target="_blank" title="View ' . $file_name . '">';
                    if (strlen($file_name) > 50)
                        echo substr($file_name, 0, 50) . "...";
                    else
                        echo $file_name;
                    echo '</a>';
                    echo "&nbsp;(";
                    $size = $attachment->getSize();
                    if ($size > 1048576)
                        echo round($size / 1048576, 1) . "&nbsp;MB";
                    else if ($size > 1024)
                        echo round($size / 1024, 1) . "&nbsp;KB";
                    else
                        echo $size . " B";
                    echo ")&nbsp;";

                    echo " " . $attachment->getContentType();
                }
            }
            echo "<br><br>";
            echo stripslashes($emailObject->getBody());
            exit;
        }
    }
} else {
    echo " ERROR request";
    exit;
}

function register_expense($expense_id, $interaction_id)
{

    $query = "UPDATE coris_assistance_cases_expenses  SET guarantee=1 WHERE expense_id='$expense_id' ";
    $mysql_result = mysql_query($query);


    $query = "INSERT INTO coris_assistance_cases_expenses_guarantee 
	SET ID_expense='$expense_id', 
	 ID_interaction ='$interaction_id',	 
	 date = now(),
	 ID_user = '" . Application::getCurrentUser() . "' 	
	";
    $mysql_result = mysql_query($query);
    if (!$mysql_result)
        echo "query ERROR: " . $query . "<br> " . mysql_error();
}

function convert_date($date, $branch = 1)
{
    /*	if ($branch==2 || $branch==3){
            $tmp = explode('-', $date);
            return $tmp[2].'.'.$tmp[1].'.'.$tmp[0];
        }else{ */
    return $date;
    //}
}

function save_taxi_in_cases($case_id)
{
    $query = "UPDATE coris_assistance_cases SET taxi = 1 WHERE case_id = '$case_id'";
    $mysql_result = mysql_query($query);
    if (!$mysql_result) {
        echo "query error: " . $query . "<BR>" . mysql_error();
    }
}

?>