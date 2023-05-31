<?php

require_once('include/include_ayax.php');
include('include/email_util.php');


$interaction_id = getValue('int');
$action = getValue('action');
$msg = '';
$editor = '';
$case_number = '';

$attach_list = '';
$contrahent_name = '';
$contrahent_fax = '';
$client_case_no = '';

$editor = Application:: getUserName(Application::getCurrentUser());

$extra_code = '';

if ($interaction_id > 0) {
    $interaction = new Interaction(0, $interaction_id);
    $doc_id = $interaction->getDocument()->getObjectID();
    $case_id = $interaction->getCaseID();
    $corisCase = new CorisCase($case_id);
    $branch = $corisCase->getBranchId();

    $reclamation = $interaction->getReclamation();
    $document = $interaction->getDocument();

    if ($document instanceof Email) {

        $body = $document->getBody();
        $msg = '';
        $naglowek = generateHeader($document);
        $body = strip_tags($body, '<a><br><p><table><tr><td><font><span><img>');
        $msg = "<br><br>\n\n-----Original Message-----\n\n<br><br><table style=\"background:#dddddd;\" ><tr><td  style=\"background:#dddddd;\" >" . nl2br($naglowek) . "<br>" . $body . '</td></tr></table>';


        if ($action == 'forward') {
            $email_temat = 'Fw: ' . $document->getName();

            $att = $document->getAttchments()->get_list();
            $licznik = 0;
            foreach ($att as $attachment) {
                $file_name = $attachment->getName();
                $objID = $attachment->getObjectID();

                $attach_list .= '<li class="file" id="attach-a' . $licznik . '" style="background-color: transparent;">
    							<input type="checkbox" class="file-check" name="file_upload[]" value="attach-a' . $licznik . '" checked>
    							<input type="hidden" name="file_upload_org_name[attach-a' . $licznik . ']" value="DOC_get_content.php?id=' . $objID . '&amp;source=document&amp;action=view">
    							<input type="hidden" name="file_upload_name[attach-a' . $licznik . ']" value="' . $file_name . '">
    							<input type="hidden" name="file_upload_type[attach-a' . $licznik . ']" value="document">
    							<a class="file-title" href="DOC_get_content.php?id=' . $objID . '&amp;source=document&amp;action=view" title="' . htmlspecialchars($file_name, ENT_QUOTES, 'ISO-8859-1') . '" target="_blank">' . $file_name . '</a>';

                $tmp = explode('.', strtolower($file_name));
                if ($tmp[count($tmp) - 1] == 'pdf')
                    $attach_list .= '<a href="#" title="edycja pliku pdf" style="margin-left: 10px" onClick="edit_pdf(\'attach-a' . $licznik . '\',\'document\',\'DOC_get_content.php?id=' . $objID . '&amp;source=document&amp;action=view\',\'' . $file_name . '\');"><img src="img/edit.gif" border="0"></a>';
                $attach_list .= '</li>';

                $licznik++;
            }


        } else {
            $email_to = $document->get_from_email();
            $email_temat = 'Re: ' . $document->getName();
            $msg = $naglowek . $msg;
        }
    } else if ($document instanceof Fax) {

        if ($action == 'forward') {

            $file_name = 'fax_' . time() . '.pdf';
            $objID = $doc_id;
            $licznik = 0;
            $attach_list .= '<li class="file" id="attach-fa' . $licznik . '" style="background-color: transparent;">
    							<input type="checkbox" class="file-check" name="file_upload[]" value="attach-fa' . $licznik . '" checked>
    							<input type="hidden" name="file_upload_name[attach-fa' . $licznik . ']" value="' . $file_name . '">
    							<input type="hidden" name="file_upload_org_name[attach-fa' . $licznik . ']" value="DOC_get_content.php?id=' . $objID . '&amp;source=document&amp;action=view">
    							<input type="hidden" name="file_upload_type[attach-fa' . $licznik . ']" value="document">
    							<a class="file-title" href="DOC_get_content.php?id=' . $objID . '&amp;source=document&amp;action=view" title="' . htmlspecialchars($file_name, ENT_QUOTES, 'ISO-8859-1') . '" target="_blank">' . $file_name . '</a>';
            $attach_list .= '<a href="#" title="edycja pliku pdf" style="margin-left: 10px" onClick="edit_pdf(\'attach-fa' . $licznik . '\',\'document\',\'DOC_get_content.php?id=' . $objID . '&amp;source=document&amp;action=view\',\'' . $file_name . '\');"><img src="img/edit.gif" border="0"></a>';
            $attach_list .= '<a href="#" title="usun zalacznik" style="margin-left: 10px" onClick="removeFromList(\'attach-fa' . $licznik . '\');"><img src="img/delete.gif" border="0"></a>';

            $attach_list .= '</li>';

        }
    } else {

    }

} else {
    $reclamation = getValue('reclamation') == 1 ? 1 : 0;
    $expense_id = getValue('expense_id');
    $send_type = getValue('send_type');
    $case_id = getValue('case_id');
    $corisCase = new CorisCase($case_id);
    $branch = $corisCase->getBranchId();


    $email_to = '';


    $email_cc = '';
    $email_temat = '';
    $email_contrahent_to_name = '';
    $contrahent_email = '';


    $contrahent_to_name = '';
    $contrahent_name = '';
    $contrahent_id = '';
    $faxto = '';
    $client_ref = '';
    $paxname = '';

}


$doclang = getValue('doclang') != '' ? getValue('doclang') : 'pl';


if (getValue('jezyk') != '' && $send_type == 'fax') {
    switch (getValue('jezyk')) {
        case 'polish':
            $doclang = 'pl';
            break;

        case 'english':
            $doclang = 'uk';
            break;

        case 'german':
            $doclang = 'de';
            break;

        case 'french':
            $doclang = 'fr';
            break;

        default:
            $doclang = 'uk';
            break;
    }
}
if (getValue('jezyk2') != '' && $send_type == 'email') {

    switch (getValue('jezyk2')) {
        case 'polish':
            $doclang = 'pl';
            break;
        case 'english':
            $doclang = 'uk';
            break;
        default:
            $doclang = 'uk';
            break;
    }


}
$template = getValue('doc');

if ($contrahent_id > 0) {

} else {
    $contrahent_id = getValue('contrahent_id') > 0 ? getValue('contrahent_id') : '0';
}

$ref_number = getValue('ref_number');

$amount = getValue('amount');
$currency_id = getValue('currency_id');

$l_de = '';
$l_uk = '';
$l_fr = '';
$l_pl = '';


if ($contrahent_id > 0) {
    $queryc = "SELECT * FROM coris_contrahents  WHERE contrahent_id = '$contrahent_id' ";
    $mysql_resultc = mysql_query($queryc);
    if (mysql_num_rows($mysql_resultc) > 0) {
        $rowc = mysql_fetch_array($mysql_resultc);

        if (strtoupper($rowc['country_id']) == 'PL' && $branch == 1) {
            $doclang = 'pl';
        } else if (strtoupper($rowc['country_id']) == 'DE') {
            $doclang = 'de';
        } else {
            $doclang = 'uk';
        }
        $contrahent_name = $contrahent_name != '' ? $contrahent_name : $rowc['short_name'];

        $contrahent_fax = $faxto != '' ? $faxto : $rowc['fax1'];
        $client_case_no = $ref_number;
        $contrahent_email = $rowc['email'];

        //echo "<hr>doclang : ".$doclang;
    }
} else {
    $contrahent_fax = $faxto;
    $client_case_no = $ref_number;

    if ($branch == 2) {
        $doclang = 'de';
    }

    if ($branch == 3) {
        $doclang = 'de';
    }

}
$zm = 'l_' . $doclang;
$$zm = 'checked';

$query = "SELECT cac.case_id, cac.number, cac.`year`, cac.type_id, cac.client_id, cac.client_ref, cac.paxname, cac.paxsurname,
		                cac.country_id, cac.eventdate,
                        cac.nr_rej, pax_pesel, cac.marka_model, cac.adress1, cac.adress2, cac.ID_cause,
                        cacd.paxphone, cacd.paxmobile, cac.paxdob, cac.policy_series, cac.policy, cac.event, cac.city,
                        cacd.validityfrom, cacd.validityto, cacd.pax_place_of_stay, cacd.comments,
                        cacd.paxaddress, 	cacd.paxpost, 	cacd.paxcity
                    FROM coris_assistance_cases cac
                    JOIN coris_assistance_cases_details cacd ON cacd.case_id=cac.case_id
                    WHERE cac.case_id = '$case_id' ";

$result = mysql_query($query);
$row = mysql_fetch_array($result);

$queryCause = "SELECT cacc1.name n1, cacc2.name n2
                    FROM coris_assistance_cases_cause_l2 cacc2
                    JOIN coris_assistance_cases_cause_l1 cacc1 ON cacc1.ID=cacc2.ID_causel1
                    WHERE cacc2.ID='" . $row['ID_cause'] . "'";
$resultCase = mysql_query($queryCause);
$rowCase = mysql_fetch_assoc($resultCase);

//	$corisCase = new CorisCase($case_id);
$case_no = $corisCase->getNumber() . '/' . substr($corisCase->getYear(), 2) . '/' . $corisCase->getType_id() . '/' . $corisCase->getClient_id();
$pax_name = $corisCase->getPaxname() . ' ' . $corisCase->getPaxsurname();

$editorObj = Application::getUser(Application::getCurrentUser());
$editor = $editorObj->getName() . ' ' . $editorObj->getSurname();
if ('' != $editorObj->getPosition($doclang)) {
    $editor .= '<br/>' . $editorObj->getPosition($doclang);
}

if ($client_case_no == '') {
    $client_case_no = $corisCase->getClient_ref();
}

$tow_name = '';
$tow_name_full = '';
$tow_address = '';
$tow_post = '';
$tow_city = '';
$tow_country_id = '';
$tow_country = '';
$tow_nip = '';

$lista_tow = array(103, 129, 134);
if (getValue('doc') == '81' && in_array($corisCase->getClient_id(), $lista_tow)) {
    $tow_name = "APRIL Polska Sp�ka z o. o.";
    $tow_name_full = "APRIL Polska Sp�ka z o. o.";
    $tow_address = "ul. Sienna 73";
    $tow_post = "00-833";
    $tow_city = "Warszawa";
    $tow_country_id = "pl";
    $tow_country = "Poland";
    $tow_nip = "1132626599";
} else {
    $tow_name = Application::getContrahnetParam($corisCase->getClient_id(), 'short_name');

    $tow_name_full = Application::getContrahnetParam($corisCase->getClient_id(), 'name');
    $tow_address = Application::getContrahnetParam($corisCase->getClient_id(), 'address');
    $tow_post = Application::getContrahnetParam($corisCase->getClient_id(), 'post');
    $tow_city = Application::getContrahnetParam($corisCase->getClient_id(), 'city');
    $tow_country_id = Application::getContrahnetParam($corisCase->getClient_id(), 'country_id');
    $tow_country = Application::getCountryName2($tow_country_id, 'en');

    $tow_nip = Application::getContrahnetParam($corisCase->getClient_id(), 'nip');
}


$pan_pani_de = $corisCase->getPaxSex() == 'K' ? 'Sehr geehrte Frau ' . $corisCase->getPaxsurname() : 'Sehr geehrter Herr ' . $corisCase->getPaxsurname();

if (getValue('type') == "blank") {

    if ($branch == 1) {
        $msg = "\n\n\n<br><br>Z powa�aniem / With Regards,";
        $msg .= "\n<br>" . $editor;
        $msg .= "\n<br>APRIL Polska";
    } else {
        $msg = "\n\n\n<br><br>Mit freundlichen Gr��en / With Regards,";
        $msg .= "\n<br>" . $editor;
    }

}

if ($msg == '') {
    //mail('krzysiek@evernet.com.pl', 'Doc_new', $contrahent_id.' '.$template . ' '. $doclang);

    if ($template == 64) {

        if ($corisCase->getClient_id() == 11 || $corisCase->getClient_id() == 2201) {
            $template = 62;
        } else {
            $template = 63;
        }
    }

    $tmp = getTemplate($template, ($doclang == 'pl' ? 'pl' : 'uk'));

    $tmpl_title = $tmp[0];
    $tmpl = $tmp[1];
    $attachments = $tmp['attachments'];

    if (count($attachments) > 0) {

        $extra_code .= '<script> ';
        foreach ($attachments as $item) {
            $extra_code .= " addToList('form','" . $item['name'] . "','DOC_get_content.php?id=" . $item['file'] . "&source=form&action=view') \n";
        }
        $extra_code .= '</script>';

    }

    $msg = str_replace('<!--NASZZNAK-->', $case_no, $tmpl);
    $msg = str_replace('<!--NADAWCA-->', $editor, $msg);
    $msg = str_replace('<!--PAXNAME-->', $pax_name, $msg);
    $msg = str_replace('<!--TOWNAME-->', $tow_name, $msg);
    $msg = str_replace('<!--NR_REJ-->', $row['nr_rej'], $msg);

    $msg = str_replace('<!--MARKA_MODEL-->', $row['marka_model'], $msg);
    $msg = str_replace('<!--ADRES_POSTOJU-->', $row['adress1'], $msg);
    $msg = str_replace('<!--ADRES_DOCELOWY-->', $row['adress2'], $msg);
    $msg = str_replace('<!--PAXPHONE-->', $row['paxphone'], $msg);
    $msg = str_replace('<!--CAUSE-->', $rowCase['n1'] . '/' . $rowCase['n2'], $msg);

    $msg = str_replace('<!--DATA_URODZENIA-->', $row['paxdob'], $msg);
    $msg = str_replace('<!--TOWARZYSTWO-->', $row['client_id'], $msg);
    $msg = str_replace('<!--NR_POLISY-->', $row['policy_series'] . ' ' . $row['policy'], $msg);
    $msg = str_replace('<!--SERIA_POLISY-->', $row['policy_series'], $msg);
    $msg = str_replace('<!--WAZNOSC_POLISY_OD-->', $row['validityfrom'], $msg);
    $msg = str_replace('<!--WAZNOSC_POLISY_DO-->', $row['validityto'], $msg);
    $msg = str_replace('<!--PROBLEM_DIAGNOZA-->', $row['event'], $msg);
    $msg = str_replace('<!--MIEJSCOWOSC-->', $row['city'], $msg);
    $msg = str_replace('<!--DATA_BIEZACA-->', date("Y-m-d"), $msg);

    $msg = str_replace('<!--PAXGSM-->', $row['paxmobile'], $msg);
    $msg = str_replace('<!--PESEL-->', $row['pax_pesel'], $msg);
    $msg = str_replace('<!--KRAJ_ZDARZENIA-->', Application:: getCountryName($row['country_id'], $doclang), $msg);
    $msg = str_replace('<!--ADRES_POBYTU-->', $row['pax_place_of_stay'], $msg);
    $msg = str_replace('<!--STANOWISKO-->', $editorObj->getPosition($doclang), $msg);
    $msg = str_replace('<!--DATA_ZDARZENIA-->', $row['eventdate'], $msg);

    $msg = str_replace('<!--NR_SPRAWY_KLIENTA-->', $row['client_ref'], $msg);
    $msg = str_replace('<!--UWAGI-->', $row['comments'], $msg);


    $msg = str_replace('<!--UBEZPIECZYCIEL-->', $tow_name_full, $msg);
    $msg = str_replace('<!--UBEZPIECZYCIEL_ADRES-->', $tow_address . ', ' . $tow_post . ' ' . $tow_city, $msg);


    $msg = str_replace('<!--UBEZPIECZYCIEL_ULICA-->', $tow_address, $msg);
    $msg = str_replace('<!--UBEZPIECZYCIEL_KOD-->', $tow_post, $msg);
    $msg = str_replace('<!--UBEZPIECZYCIEL_MIASTO-->', $tow_city, $msg);
    $msg = str_replace('<!--UBEZPIECZYCIEL_KRAJ-->', $tow_country, $msg);

    $msg = str_replace('<!--PAN_PANI_NAZWISKO_DE-->', $pan_pani_de, $msg);


    $msg = str_replace('<!--UBEZPIECZYCIEL_NIP-->', $tow_nip, $msg);

    $msg = str_replace('<!--UBEZPIECZONY_ADRES-->', $row['paxaddress'] . ', ' . $row['paxpost'] . ' ' . $row['paxcity'], $msg);

    if ($amount != '') {
        $msg = str_replace('<!--AMOUNT-->', $amount, $msg);
        $msg = str_replace('<!--CURRENCY_ID-->', $currency_id, $msg);
    }

    $email_to = $tmp['default_template_email'];
    $email_cc = $tmp['default_template_email_cc'];

    $barcleycard_docs = array(70, 71, 72, 73, 74, 76, 78);
    if (in_array(getValue('doc'), $barcleycard_docs)) {
        $email_to = getPaxEmail($case_id);
    }

}

if ($branch == 3) {
    $firma_nazwa = 'April Polska Sp. z o. o.';
    $firma_fax = Branch::getFaxNumber(3);
} else if ($branch == 2) {
    $firma_nazwa = 'April Polska Sp. z o. o.';
    $firma_fax = Branch::getFaxNumber(2);
} else {
    $firma_nazwa = 'April Polska Sp. z o. o.';
    $firma_fax = Branch::getFaxNumber(1);
}

// zamiana checkboxow i radio na zaznaczalne
/*  if('' != trim($msg))
  {

      $doc = new DOMDocument('1.0', 'ISO-8859-2');
      if (!$doc->loadhtml('<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-2" />' .$msg) )
      {
          echo __LINE__ . ' something went wrong: Load DOMDocument error!';
      }else
      {
          $span = $doc->createElement("span");
          $span->setAttribute("contenteditable","false");
          $xpath = new DOMXpath($doc);
          foreach($xpath->query('//input') as $elementInput)
          {
              $spanClone = $span->cloneNode();
              $oldElement = $elementInput->cloneNode();
              $elementInput->parentNode->replaceChild($spanClone, $elementInput);
              $spanClone->appendChild($oldElement);
          }

      }
      // win:
      //$msg = (substr($doc->saveHTML(),204, -15));
      // serv:
      $msg = (substr($doc->saveHTML(),207, -16));
  }
*/

?>
    <HTML>
    <HEAD>
        <META HTTP-EQUIV="content-type" CONTENT="TEXT/HTML; CHARSET=iso-8859-2">
        <TITLE><?= AS_FORMSF_TITLE ?></TITLE>


        <script type="text/javascript" src="Scripts/jquery-1.11.1.min.js"></script>
        <script type="text/javascript">
            //no conflict jquery
            //  jQuery.noConflict();
            var JQ = jQuery.noConflict(true);
            //jquery stuff

            /*
                (function($) {

                })(jQuery);
            */


            ;
        </script>

        <script language="javascript" src="Scripts/javascript.js"></script>
        <script type="text/javascript" language="javascript" src="Scripts/mootools-core.js"></script>
        <script type="text/javascript" language="javascript" src="Scripts/mootools-more.js"></script>


        <!--<script type="text/javascript" src="Scripts/File.upload.js"></script>
        <script type="text/javascript" src="Scripts/Fx.ProgressBar.js"></script>
        <script type="text/javascript" src="Scripts/Swiff.Uploader.js"></script>
        <script type="text/javascript" src="Scripts/FancyUpload4.Attach.js"></script>
        -->
        <script type="text/javascript" src="Scripts/doc_upload_script2.js"></script>


        <meta http-equiv="X-UA-Compatible" content="IE=8"/>


        <link rel="stylesheet" type="text/css" href="themes/default/css/Content.css"/>
        <link rel="stylesheet" type="text/css" href="themes/default/css/Core.css"/>
        <link rel="stylesheet" type="text/css" href="themes/default/css/Layout.css"/>
        <link rel="stylesheet" type="text/css" href="themes/default/css/Dock.css"/>
        <link rel="stylesheet" type="text/css" href="themes/default/css/Window.css"/>
        <link rel="stylesheet" type="text/css" href="themes/default/css/Tabs.css"/>

        <!--[if IE]>
        <script type="text/javascript" src="scripts/excanvas_r43.js"></script>
        <![endif]-->

        <script type="text/javascript" src="Scripts/mocha.js"></script>
        <script type="text/javascript" src="Scripts/mocha-init.js"></script>

        <style>

            .btn-success {
                color: #fff;
            }

            .btn {
                cursor: pointer;
                font-size: 14px;
                font-weight: 400;
                line-height: 1.42857;
                text-align: center;
                white-space: nowrap;
            }

            #fileupload {
                position: absolute;
                top: 0;
                right: 0;
                margin: 0;
                opacity: 0;
                -ms-filter: 'alpha(opacity=0)';
                font-size: 1px;
                direction: ltr;
                cursor: pointer;
                box-sizing: border-box;
            }
        </style>


        <script language="javascript">
            <!--
            function MM_openBrWindow(theURL, winName, features) { //v2.0
                window.open(theURL, winName, features);
            }

            function valbutton(thisform) {
                // place any other field validations that you require here
                var textA = document.form1.message.value;
                var toN = document.form1.contrahent_to_name.value;
                var toC = document.form1.contrahent_name.value;
                var toFN = document.form1.faxto.value;

                //var toCN = document.form1.toCaseNo.value
                // validate myradiobuttons
                myOption = -1;
                for (i = 0; i < thisform.jezyk.length; i++) {
                    if (thisform.jezyk[i].checked) {
                        myOption = i;
                    }
                }
                if (toN != '') {
                    if (toC != '') {
                        if (myOption == -1) {
                            alert("<?= AS_FORMSF_WYBWERSJJEZ ?>");
                            return false;
                        }
                    }
                }

                if (thisform.send_type[0].checked) {//fax
                    if ((toC == '') && (toN == '')) {
                        alert("<?= AS_FORMSF_PODNAZWFIRMDOKTPISZ ?>");
                        form1.contrahent_to_name.focus();
                        return false;
                    } else if (toFN == '') {
                        alert("<?= AS_FORMSF_WPISZNRFAKSU ?>");
                        form1.faxto.focus();
                        return false;
                    }
                } else { //email

                    if (document.form1.email_to.value == '') {
                        alert('<?= AS_FORMSF_BRODBMAIL ?>');
                        document.form1.email_to.focus();
                        return false;
                    } else if (document.form1.email_temat.value == '') {
                        alert('<?= AS_FORMSF_BRTEMMAIL ?>');
                        document.form1.email_temat.focus();
                        return false;
                    }
                }

                return true;

            }


            function zmien(type) {
                fax_layer = document.getElementById('form_fax');
                email_layer = document.getElementById('form_email');
                fax_lang_layer = document.getElementById('form_fax_lang');
                email_lang_layer = document.getElementById('form_email_lang');
                email_attach_add = document.getElementById('form_email_attach_add');

                //fax_attach_add = document.getElementById('form_fax_attach_add');

                if (type == 'email') {
                    fax_layer.style.display = 'none';
                    fax_lang_layer.style.display = 'none';
                    email_layer.style.display = 'block';
                    email_lang_layer.style.display = 'block';
                    email_attach_add.style.display = 'block';
                    //fax_attach_add.style.display = 'none';
                } else if (type == 'fax') {
                    email_layer.style.display = 'none';
                    email_lang_layer.style.display = 'none';
                    fax_layer.style.display = 'block';
                    fax_lang_layer.style.display = 'block';
                    email_attach_add.style.display = 'block';
                    //fax_attach_add.style.display = 'block';

                }

            }


            function dodaj_document() {
                popup('DOC_document_search.php?case_id=<?php echo $case_id;?>', '', 800, 600);
            }

            function dodaj_formularz() {
                popup('DOC_form_search.php?case_id=<?php echo $case_id;?>', '', 600, 500);
            }


            function search_contact(tryb, element) {
                //$('interface').opacity = 20;
                myWindow = new MUI.Modal({
                    'id': 'mywin1', width: 710, height: 400, top: 350,
                    type: 'modal',
                    loadMethod: 'xhr',
                    contentURL: 'ayax/doc_search_contact.php?case_id=<?php echo $case_id;?>&tryb=' + tryb + '&element=' + element,
                    title: 'Szukaj kontaktu',
                    icon: 'img/edit.gif'

                });
                myWindow.center();

            }


            function getMsgValue(divName) {
                /* $(divName).getElements('input').each(
                     function(el)
                     {
                         if(el.checked){el.setAttribute('checked',"checked");}
                         else{el.removeAttribute('checked');}
                     }
                 );
             */
                return $(divName).innerHTML;
            }


            window.addEvent('domready', function () {
                initloader();
            });
            //-->
        </script>
        <style type="text/css">

            @
            {
                font-family: verdana
            ;
                font-size: 12px
            ;
            }
            #interface td {
                font-family: verdana;
                font-size: 12px;
            }

            #interface img {
                border-top: lightgrey 1px solid;
                border-left: lightgrey 1px solid;
                border-right: lightgrey 1px solid;
                border-bottom: lightgrey 1px solid;
            }

            #interface .block {
                text-align: left;
                line-height: normal;
                height: 275px;
                width: 655px;
                background-color: white;
                font-family: Arial;
                font-size: 12px;
                padding: 10pt;
                border: solid #6699cc 1px;
                scrollbar-base-color: gainsboro;
                overflow: auto;
            }

            #interface BUTTON {
                cursor: hand;
                background: #ffffff;
                border-color: #99ccff;
                font-weight: bold;
            }

            #interface P {
                margin-top: 0px;
                margin-bottom: 0px;
            }

        </style>
    </HEAD>
    <BODY bgcolor="#dfdfdf">

    <FORM METHOD="POST" ACTION="DOC_new_document_send.php" name="form1" id="form1" target="_blank" enctype="multipart/form-data" onsubmit="return valbutton(form1);">
        <input type="hidden" name="template_id" value="<?php echo $template; ?>">
        <input type="hidden" name="expense_id" value="<?php echo $expense_id; ?>">
        <input type="hidden" name="case_id" value="<?php echo $case_id; ?>">
        <input type="hidden" name="doclang" value="<?php echo $doclang; ?>">
        <input type="hidden" name="doc" value="">


        <input type="hidden" name="case_id" value="<?php echo $case_id; ?>">

        <div id="interface">
            <BR>
            <BR>
            <TABLE border=0 bgcolor=#eeeeee ALIGN="CENTER" cellspacing=0 cellpadding=3 style="border: #6699cc 1px solid" SIZE=80%>
                <tr>
                    <td colspan="2" align="left"><span align="left" style="color:red;margin-right:200px;"><b><?= AS_TITLE_REKLAM1 ?></b><input style="background: #eeeeee ;" type="checkbox"
                                                                                                                                               name="reclamation"
                                                                                                                                               value="1" <?php echo $reclamation == 1 ? 'checked' : ''; ?>></span>
                        <b><?= AS_FORMSF_WYSLIJJAKO ?>:
                            <input tabindex=0 type="radio" name="send_type" value="fax" onclick="zmien('fax')"><?= FAX ?>
                            &nbsp;<input tabindex=0 onclick="zmien('email')" type="radio" name="send_type" value="email" checked><?= EMAIL ?></b>
                    </td>
                </tr>
                <TR>
                    <TD colspan="2">
                        <div class="block" style="display: none;width: 805px;height: 180px;background-color: #EEEEEE;" id="form_fax" name="form_fax">
                            <table width="100%" border="0">
                                <tr>
                                    <td width="50%">

                                        <table align="center" cellspacing=0 cellpadding=0>
                                            <tr>
                                                <td>
                                                    &nbsp;<?= AS_FORMSF_WYSLDO ?>:&nbsp;<BR>
                                                </td>
                                                <td>
                                                    <input tabindex=1 type="text" name="contrahent_to_name" size=25 maxlength="25" value="<?php echo stripslashes($contrahent_to_name); ?>"><BR>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    &nbsp;<?= AS_FORMSF_FIRM ?>:&nbsp;<BR>
                                                </td>
                                                <td>
                                                    <input tabindex=2 type="text" id="contrahent_name" name="contrahent_name" size=25 maxlength=25 value="<? if ($contrahent_name != '') {
                                                        echo '' . $contrahent_name;
                                                    } else {
                                                        echo '';
                                                    } ?>">&nbsp;
                                                    <input type="hidden" id="contrahent_id" name="contrahent_id" value="<? if ($contrahent_id != '') {
                                                        echo $contrahent_id;
                                                    } else {
                                                        echo '';
                                                    } ?>">
                                                    <input type="button" style="width: 20px" tabindex="-1" title="<?= AS_FORMSF_WYSZKL ?>"
                                                           onclick="MM_openBrWindow('GEN_contrahents_select_frameset.php?fax=1','','width=550,height=420')" value="&gt;">

                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    &nbsp;<?= AS_FORMSF_NRFAKS ?>:&nbsp;<BR>
                                                </td>
                                                <td>
                                                    <input tabindex=3 type="text" id="faxto" name="faxto" size=25 maxlength=25 value="<? if ($contrahent_fax != '') {
                                                        echo $contrahent_fax;
                                                    } else {
                                                        echo '';
                                                    } ?>"><BR>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    &nbsp;<?= AS_FORMSF_NRSPR ?>:&nbsp;<BR>
                                                </td>
                                                <td>
                                                    <input tabindex=4 type="text" name="client_ref" size=25 maxlength=25 value="<? if ($client_case_no != '') {
                                                        echo $client_case_no;
                                                    } else {
                                                        echo '';
                                                    } ?>"><BR>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    &nbsp;<?= AS_FORMSF_DOT ?>:&nbsp;<BR>
                                                </td>
                                                <td>
                                                    <input tabindex=4 type="text" name="paxname" value="<? echo $pax_name ?>" size=45 maxlength=70><BR>
                                                </td>
                                            </tr>
                                        </table>
                                        <BR>
                                    </TD>
                                    <TD>
                                        <BR>
                                        <table align="center" cellspacing=0 cellpadding=0>
                                            <tr>
                                                <td>
                                                    &nbsp;<?= AS_FORMSF_WYSLOD ?>:&nbsp;<BR>
                                                </td>
                                                <td>
                                                    <input tabindex=-1 type="text" name="editor" size=25 maxlength=25 value="<? echo $editor; ?>"><BR>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    &nbsp;<?= AS_FORMSF_FIRM ?>:&nbsp;<BR>
                                                </td>
                                                <td>


                                                    <input tabindex=-1 type="text" name="fromCo" size=25 maxlength=100 value="<?= $firma_nazwa ?> "><BR>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    &nbsp;<?= AS_FORMSF_NRFAKS ?>:&nbsp;<BR>
                                                </td>
                                                <td>
                                                    <input tabindex=-1 type="text" name="fromFaxNo" size=25 maxlength=25 value="<?= $firma_fax ?>"><BR>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    &nbsp;<?= AS_FORMSF_NRSPR ?>:&nbsp;<BR>
                                                </td>
                                                <td>
                                                    <input tabindex=-1 type="text" name="case_number" size=25 maxlength=25 value="<? echo $case_no; ?>"><BR>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    &nbsp;<BR>
                                                </td>
                                                <td>
                                                    <BR>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>


                            </table>
                        </div>


                        <div class="block" style="display: block;width: 805px;height: 200px;padding=0px;background-color: #EEEEEE;" id="form_email" name="form_email">
                            <table width="100%" cellspacing=0 cellpadding=0>
                                <tr>
                                    <td>

                                        <input type="submit" value="<?= AS_FORMSF_PODGLWYDR ?>" style="cursor: hand; color: white; background: #6699cc" onclick="message.value=getMsgValue('oDiv');">
                                        &nbsp;&nbsp;
                                        <input type="submit" name="send" value="<?= AS_FORMSF_WYSLDOK ?>" style="cursor: hand; color: white; background: #6699cc"
                                               onclick="message.value=getMsgValue('oDiv');">
                                    </td>
                                    <td align=right><input type="button" name="send" value="<?= CLOSE ?>" style="cursor: hand; color: white; background: #cc0000" onclick="window.close();">
                                    </td>
                                </tr>
                            </table>

                            <table width="100%" border="0">
                                <tr>
                                    <td width="50">
                                        <table width="700" height="23" align="center" cellpadding=0 cellspacing=0 border=0>
                                            <tr>
                                                <td nowrap> &nbsp;<?= AS_FORMSF_WYSLOD ?>:&nbsp;<BR></td>
                                                <td>
                                                    <input tabindex=1 type="text" name="editor2" size=25 maxlength=25 value="<? echo $editor; ?>">
                                                </td>
                                                <td width="10">&nbsp;</td>
                                                <td nowrap>&nbsp;<?= AS_FORMSF_NRSPRCOR ?>:</td>
                                                <td><input tabindex=2 type="text" name="case_number" size=22 maxlength=25 value="<? echo $case_no; ?>"></td>
                                                <td nowrap> &nbsp;&nbsp;<?= AS_FORMSF_NRSPRKL ?>:</td>

                                                <td><input tabindex=2 type="text" name="email_client_ref" size=20 maxlength=25 value="<? if ($client_case_no != '') {
                                                        echo $client_case_no;
                                                    } else {
                                                        echo '';
                                                    } ?>">
                                                </td>
                                            </tr>
                                        </table>
                                        <br>
                                    </TD>
                                </tr>
                                <tr>
                                    <td>
                                        <table width="622" align="center" cellpadding=0 cellspacing=2>

                                            <tr>
                                                <td> &nbsp;<?= AS_FORMSF_EMAILTO ?>:&nbsp;<BR>
                                                </td>
                                                <td colspan="4">
                                                    <input tabindex=3 name="email_to" type="text" id="email_to" size=80 maxlength=240
                                                           value="<?php echo ($contrahent_email <> '') ? $contrahent_email : $email_to; ?>">
                                                    <input type="button" style="width: 20px" tabindex="-1" title="Lista emaili" onclick="search_contact('list','email_to')" value="&gt;">
                                                    <input type="button" style="width: 20px" tabindex="-1" title="<?= AS_FORMSF_WYSZKL ?>" onclick="search_contact('search','email_to')" value="L"
                                                           style="background: #cccccc; color: #999999; font-family: webdings; font-size: 12pt; height: 18pt; line-height: 8pt; width: 23pt;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td> &nbsp;<?= AS_FORMSF_EMAILCC ?>:&nbsp;<BR>
                                                </td>
                                                <td colspan="4">
                                                    <input tabindex=4 name="email_cc" type="text" id="email_cc" size=80 maxlength=240 value="<?php echo $email_cc; ?>">
                                                    <input type="button" style="width: 20px" tabindex="-1" title="Lista emaili" onclick="search_contact('list','email_cc')" value="&gt;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?= AS_FORMSF_TEMAT ?>:</td>
                                                <td colspan="4"><input tabindex=4 name="email_temat" type="text" id="email_temat" size=80 maxlength=200 value="<?php echo $email_temat; ?>"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5">
                                                    <hr>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td> &nbsp;<?= AS_FORMSF_WYSLDO ?>:&nbsp;<BR>
                                                </td>
                                                <td>
                                                    <input tabindex=5 type="text" name="email_contrahent_to_name" size=25 maxlength=25 value="<? echo $email_contrahent_to_name ?>">
                                                </td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;<?= AS_FORMSF_DOT ?>:&nbsp;</td>
                                                <td><input tabindex=7 type="text" name="email_paxname" value="<? echo $pax_name ?>" size=40 maxlength=70></td>
                                            </tr>
                                        </table>
                                    </TD>
                                </tr>
                            </table>
                        </div>
                        <!-- koniec -->
                    </TD>
                </TR>
                <tr>
                    <td>
                        <?php

                        //	echo '<br>';
                        echo '<div class="block" style="display: block;width: 805px;height: 120px;background-color: #EEEEEE;" id="form_email_attach" name="form_email_attach">';
                        echo AS_FORMSF_ZAL;
                        echo '<ul id="demo-list">' . $attach_list . '</ul>';
                        echo '</div>';

                        echo '<div class="block"  style="display: block;width: 805px;height: 35px;background-color: #EEEEEE; padding=0px;" id="form_email_attach_add" name="form_email_attach_add">';
                        echo '

			';
                        echo '<table width=100% border=0 cellspacing=1 cellpadding=1><tr>';
                        //  echo '<td><a href="#" id="demo-attach">'.ATTACH_FILE .'</a></td>';
                        echo '<td>

<span class="btn btn-success fileinput-button">
<i class="glyphicon glyphicon-plus"></i>
<input type=button name="file_attach_buton2" value="' . ATTACH_FILE . '" onclick="dodaj_zalacznik()" style="cursor: hand; height: 17pt; width: 90pt; color: white; background: #4477aa">
<input id="fileupload" type="file" multiple="" name="files[]" onChange="start_upload(this)">
</span>


			</td>';
                        //			echo '<td>Dodaj za��cznik: </td>';
                        //echo '<td><input type=file name="file_attach" style="cursor: hand;"> <input type=button name="file_attach_buton" value="Dodaj za��cznik" onclick="dodaj_zalacznik()" style="cursor: hand; height: 17pt; width: 90pt; color: white; background: #6699cc"></td>';
                        echo '<td><input type=button name="file_attach_buton2" value="' . AS_FORMSF_DODDOK . '" onclick="dodaj_document()" style="cursor: hand; height: 17pt; width: 90pt; color: white; background: #4477aa"></td>'; //
                        echo '<td><input type=button name="file_attach_buton2" value="' . AS_FORMSF_DODFORM . '" onclick="dodaj_formularz()" style="cursor: hand; height: 17pt; width: 90pt; color: white; background: #4477aa"></td>';
                        echo '</tr></table>';
                        echo '</div>';
                        ?>
                    </TD>
                </TR>
                <TR>
                    <TD colspan=2>
                        <font size=2 face="arial narrow">
                            <CENTER>
                                <HR ALIGN="CENTER" WIDTH=75%>
                                <?= AS_FORMSF_WOKNPONWPISZTRESC ?><BR>
                                <HR ALIGN="CENTER" WIDTH=50%>
                            </CENTER>
                            <font size=2 face="verdana">
                                <CENTER>

                                </CENTER>
                                <?
                                $case_id = getValue('case_id');
                                ?>
                    </TD>
                </TR>
                <tr>
                    <td colspan=2>
                        <table align="left" border="0">
                            <tr>
                                <td rowspan=2 style="background-color: #eeeeee; font-size: 0px">
                                    <img src="graphics/UI_bold_1.gif" alt="<?= AS_FORMSF_POGR ?>"
                                         onmouseover='this.style.borderTopColor="darkgray"; this.style.borderLeftColor="darkgray"; this.style.borderBottomColor="black"; this.style.borderRightColor="black"; style.cursor="hand";'
                                         onmouseout='this.style.borderColor="lightgrey"' onclick='document.execCommand("Bold",false,null);'>
                                </td>
                                <td rowspan=2 style="background-color: #eeeeee; font-size: 0px">
                                    <img src="graphics/UI_italic_1.gif" alt="<?= AS_FORMSF_KURS ?>"
                                         onmouseover='this.style.borderTopColor="darkgray"; this.style.borderLeftColor="darkgray"; this.style.borderBottomColor="black"; this.style.borderRightColor="black"; style.cursor="hand";'
                                         onmouseout='this.style.borderColor="lightgrey"' onclick='document.execCommand("Italic",false,null);'>
                                </td>
                                <td rowspan=2 style="background-color: #eeeeee; font-size: 0px">
                                    <img src="graphics/UI_underline_1.gif" alt="<?= AS_FORMSF_PODKR ?>"
                                         onmouseover='this.style.borderTopColor="darkgray"; this.style.borderLeftColor="darkgray"; this.style.borderBottomColor="black"; this.style.borderRightColor="black"; style.cursor="hand";'
                                         onmouseout='this.style.borderColor="lightgrey"' onclick='document.execCommand("Underline",false,null);'>
                                </td>
                                <td rowspan=2 style="background-color: #eeeeee; font-size: 0px">&nbsp;&nbsp;&nbsp;&nbsp;
                                    <img src="graphics/UI_leftalign_1.gif" alt="<?= AS_FORMSF_WYRDOLEW ?>"
                                         onmouseover='this.style.borderTopColor="darkgray"; this.style.borderLeftColor="darkgray"; this.style.borderBottomColor="black"; this.style.borderRightColor="black"; style.cursor="hand";'
                                         onmouseout='this.style.borderColor="lightgrey"' onclick='document.execCommand("JustifyLeft",false,null);'>
                                </td>
                                <td rowspan=2 style="background-color: #eeeeee; font-size: 0px">
                                    <img src="graphics/UI_centeralign_1.gif" alt="<?= AS_FORMSF_WYSR ?>"
                                         onmouseover='this.style.borderTopColor="darkgray"; this.style.borderLeftColor="darkgray"; this.style.borderBottomColor="black"; this.style.borderRightColor="black"; style.cursor="hand";'
                                         onmouseout='this.style.borderColor="lightgrey"' onclick='document.execCommand("JustifyCenter",false,null);'>
                                </td>
                                <td rowspan=2 style="background-color: #eeeeee; font-size: 0px">
                                    <img src="graphics/UI_rightalign_1.gif" alt="<?= AS_FORMSF_WYRDOPRA ?>"
                                         onmouseover='this.style.borderTopColor="darkgray"; this.style.borderLeftColor="darkgray"; this.style.borderBottomColor="black"; this.style.borderRightColor="black"; style.cursor="hand";'
                                         onmouseout='this.style.borderColor="lightgrey"' onclick='document.execCommand("JustifyRight",false,null);'>
                                </td>
                                <td width=460 style="font-size: 10px; font-family: verdana" align="right">
                                    <?= AS_FORMSF_WYBWERSJEZNAGL ?>:&nbsp;&nbsp;&nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td width=460 align="right">

                                    <div style="display: none; border: 0px;margin: 0px; padding: 0px;  width: 250px;height: 25px;background-color: #EEEEEE;" id="form_fax_lang" name="form_fax_lang"
                                         align="right">
                                        <?php if ($branch == 2 || $branch == 3) { ?>
                                            <input tabindex=-1 type="radio" value="german" name="jezyk" style="background: #eeeeee" <?php echo $l_de; ?>><img src="graphics/de1.gif" height=12 width=21
                                                                                                                                                              alt="<?= AS_FORMSF_NIEM ?>">&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input tabindex=-1 type="radio" value="english" name="jezyk" style="background: #eeeeee" <?php echo $l_uk; ?>><img src="graphics/uk1.gif" height=12 width=21
                                                                                                                                                               alt="<?= AS_FORMSF_ANG ?>">&nbsp;&nbsp;&nbsp;&nbsp;


                                        <?php } else { ?>
                                            <input tabindex=-1 type="radio" value="polish" name="jezyk" style="background: #eeeeee" <?php echo $l_pl; ?>><img src="graphics/pl1.gif" height=12 width=21
                                                                                                                                                              alt="<?= AS_FORMSF_POL ?>">&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input tabindex=-1 type="radio" value="english" name="jezyk" style="background: #eeeeee" <?php echo $l_uk; ?>><img src="graphics/uk1.gif" height=12 width=21
                                                                                                                                                               alt="<?= AS_FORMSF_ANG ?>">&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input tabindex=-1 type="radio" value="german" name="jezyk" style="background: #eeeeee" <?php echo $l_de; ?>><img src="graphics/de1.gif" height=12 width=21
                                                                                                                                                              alt="<?= AS_FORMSF_NIEM ?>">&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input tabindex=-1 type="radio" value="french" name="jezyk" style="background: #eeeeee" <?php echo $l_fr; ?>><img src="graphics/fr1.gif" height=12 width=21
                                                                                                                                                              alt="<?= AS_FORMSF_FR ?>">&nbsp;&nbsp;
                                        <?php } ?>
                                    </div>
                                    <div style="display: block; border: 0px;margin: 0px; padding: 0px;  width: 250px;height: 25px;background-color: #EEEEEE;" id="form_email_lang"
                                         name="form_email_lang" align="right">
                                        <?php if ($branch == 2 || $branch == 3) { ?>
                                            <input tabindex=-1 onclick="zmien_temat('uk');" type="radio" value="german" name="jezyk2" style="background: #eeeeee" <?php echo $l_de; ?> ><img
                                                    src="graphics/de1.gif" height=12 width=21 alt="<?= AS_FORMSF_NIEM ?>">&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input tabindex=-1 onclick="zmien_temat('uk');" type="radio" value="english" name="jezyk2" style="background: #eeeeee" <?php echo $l_uk; ?> ><img
                                                    src="graphics/uk1.gif" height=12 width=21 alt="<?= AS_FORMSF_ANG ?>">&nbsp;&nbsp;&nbsp;&nbsp;
                                        <?php } else { ?>
                                            <input tabindex=-1 onclick="zmien_temat('pl');" type="radio" value="polish" name="jezyk2" style="background: #eeeeee" <?php echo $l_pl; ?> checked><img
                                                    src="graphics/pl1.gif" height=12 width=21 alt="<?= AS_FORMSF_POL ?>">&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input tabindex=-1 onclick="zmien_temat('uk');" type="radio" value="english" name="jezyk2" style="background: #eeeeee" <?php echo $l_uk; ?> ><img
                                                    src="graphics/uk1.gif" height=12 width=21 alt="<?= AS_FORMSF_ANG ?>">&nbsp;&nbsp;&nbsp;&nbsp;
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan=2>
                        <table width=800>
                            <tr>
                                <td width=800>
                                    <DIV tabindex=9 id="oDiv" class="block" name="nDiv" CONTENTEDITABLE=true designMode="on" ALIGN=left style="width:800px;height=450px"><?php echo $msg; ?></DIV>
                                    <BR>
                                    <input type="hidden" id="textArea" name="textArea">
                                    <input type="hidden" id="message" name="message">
                                    <center>
                                        <input tabindex=11 type="submit" value="<?= AS_FORMSF_PODGLWYDR ?>" style="cursor: hand; height: 15pt; width: 90pt; color: white; background: #6699cc"
                                               onclick="message.value=getMsgValue('oDiv');">
                                        &nbsp;&nbsp;
                                        <input tabindex=12 type="submit" name="send" value="<?= AS_FORMSF_WYSLDOK ?>" style="cursor: hand; height: 15pt; width: 90pt; color: white; background: #6699cc"
                                               onclick="message.value=getMsgValue('oDiv');">

                                    </center>
                                    <BR>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </TABLE>
            <script>

                function zmien_temat(lang) {
                    ff = document.getElementById('email_temat');

                    if (lang == 'pl')
                        ff.value = '<?php echo addslashes($pax_name) . " / " . AS_FORMSF_NASZNR . ": $case_no / " . AS_FORMSF_PANNR . ": $client_case_no"; ?>';
                    else
                        ff.value = '<?php echo addslashes($pax_name) . " / Our ref.: $case_no / Your ref.: $client_case_no"; ?>';
                }

                <?php

                if (trim($email_temat) == '')
                    if ($doclang == 'pl') {
                        echo 'zmien_temat(\'pl\');' . "\n";
                    } else {
                        echo 'zmien_temat(\'en\');' . "\n";
                    }
                if ($send_type == 'email') {
                    echo 'zmien(\'email\');';
                    echo "\ndocument.form1.send_type[1].click()";
                } else if ($send_type == 'fax') {
                    echo 'zmien(\'fax\');';
                    echo "\ndocument.form1.send_type[0].click()";
                }

                ?>
            </script>
        </div>
    </FORM>
    <?php echo $extra_code; ?>
    </BODY>
    </HTML>
<?php

function generateHeader(Email $email)
{

    $naglowek = '
------------------- Original message -------------------
Subject: ' . $email->getName() . '
From:    ' . $email->get_from() . ' &lt;' . $email->get_from_email() . '&gt;
Date:  ' . $email->get_date() . '
To:    ' . $email->get_to() . '
CC:   ' . $email->get_cc() . '
---------------------------------------------------------------------------------------------

';
    return $naglowek;
}

function getPaxEmail($case_id)
{
    $query = "SELECT pax_email FROM coris_assistance_cases WHERE case_id = '$case_id'";
    $mr = mysql_query($query);
    $row = mysql_fetch_array($mr);
    return $row[0];

}

?>