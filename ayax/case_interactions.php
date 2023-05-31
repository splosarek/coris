<?php


include_once('../include/include_ayax.php');
$reclamation = getValue('reclamation');
$case_id = getValue('case_id');

$type = getValue('type');
$category = getValue('category');
$txt_search = getValue('txt_search');
$order = getValue('order');
$sort_direction = getValue('sort_direction');

$lang = $_SESSION['GUI_language'];

$result = '	
		
<input type="hidden" name="reclamatin" id="reclamation" value="' . $reclamation . '">
<input type="hidden" name="order" id="order" value="' . $order . '">
<input type="hidden" name="sort_direction" id="sort_direction" value="' . $sort_direction . '">';


$result .= '<table cellpadding="2" cellspacing="2" border="0" width="100%">';

/*$archive_status = CorisCase::check_archiveS($case_id);

if ($archive_status){
	$result .= '<tr><td align="center"><br><b>Sprawa przeniesiona do archiwum</b></td></tr>';
}else {*/
if ($reclamation == 0) {
    $result .= '<tr>
									<td width=72% align="center" valign="middle" style="background: #e0e0e0; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
						   <small>' . AS_CASD_WYBDOK . ': </small>&nbsp;<select name="new_document" style="font-family: Verdana; font-size: 8pt;" onchange=\'loadDocument(this.value); \'>
										<option value="">(' . AS_DOC_SELECT . '...)</option>
										<option value="DOC_new_document.php?case_id=' . $case_id . '&type=blank">' . AS_CASD_NOWDOK . '</option>
										<option value="DOC_new_notification.php?case_id=' . $case_id . '&doc=zgloszenie">' . AS_CASD_ZGL . '</option>
											 <!--   <option value="DOC_new_document.php?case_id=' . $case_id . '&doc=14&doclang=uk' . '">' . AS_CASD_PORSBRM1 . '</option>
												<option value="DOC_new_document.php?case_id=' . $case_id . '&doc=15&doclang=uk' . '">' . AS_CASD_PORSBRM2 . '</option>
												<option value="DOC_new_document.php?case_id=' . $case_id . '&doc=13&doclang=pl' . '">' . AS_CASD_HOLINTER . '</option>
												<option value="DOC_new_document.php?case_id=' . $case_id . '&doc=12&doclang=pl' . '">' . AS_CASD_HOLINNE . '</option>
												<option value="DOC_new_document.php?case_id=' . $case_id . '&doc=3&doclang=pl' . '">' . AS_CASD_WYNSAM . '</option>
												<option value="DOC_new_document.php?case_id=' . $case_id . '&doc=4&doclang=pl' . '">' . AS_CASD_REZNOCL . '</option>
												<option value="DOC_new_document.php?case_id=' . $case_id . '&doc=5&doclang=pl' . '">' . AS_CASD_AMBPL . '</option>
												-->
											   ' . getDocumentsList($case_id) . '
									  </select>
				<br><br>&nbsp;';

    $case = new CorisCase($case_id);
    //if ($case->getBranchId() == 1 ) {    //popr
    $result .= '	&nbsp;<input type="button" value="SMS" onClick="new_sms(' . $case_id . ');" style="width:50px"> ';
    if ($case->getClient_id() == '14189')
        $result .= '	&nbsp;<input type="button" value="SMS Potwierdzenie rejestracji" onClick="new_sms(\'' . $case_id . '&confirm=1\');" style="width:180px"> ';
    //}
    $result .= '&nbsp;<input type="button" value="' . AS_CASD_ROZMPRZ . '" onClick="new_conversation(' . $case_id . ',\'call_in\');" style="width:160px">
				&nbsp;<input type="button" value="' . AS_CASD_ROZMWYCH . '" onClick="new_conversation(' . $case_id . ',\'call_out\');" style="width:160px">
				<input type="button" value="' . AS_DOC_NEWNOTE . '" onClick="new_note(' . $case_id . ');">
									</td>
									<td width="33%" align="center" style="background: #e0e0e0; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
									<table border=0>
									<tr><td  align="right"><small>' . AS_CASD_FILTR . '</small></td>
										<td><select style="font-family: Verdana; font-size: 8pt;" name="filter_documents"  id="filter_documents" onChange="zastosuj_filter();" >
										<option value="0" ' . ($type == 0 ? 'selected' : '') . '>' . AS_CASD_WYB . '</option>
										<option value="3" ' . ($type == 3 ? 'selected' : '') . '>' . AS_CASD_ROZM . '</option>
										<option value="2" ' . ($type == 2 ? 'selected' : '') . '>' . AS_CASD_NOT . '</option>
										<option value="5" ' . ($type == 5 ? 'selected' : '') . '>' . FAX . '</option>
										<option value="4" ' . ($type == 4 ? 'selected' : '') . '>' . EMAIL . '</option>
										<option value="6" ' . ($type == 6 ? 'selected' : '') . '>' . SMS . '</option>
									  </select></td></tr>
									<tr><td align="right"><small>' . AS_DOC_CATEGORY . ':</small></td>
										<td><select style="font-family: Verdana; font-size: 8pt;" name="filter_category_id" id="filter_category_id" class="date-required" onChange="zastosuj_filter();">';
    $query2 = "SELECT * FROM coris_fax_in_category  WHERE ID_section=1 ";
    $mysql_result2 = mysql_query($query2) or die(mysql_error());
    $result .= '<option value=\'0\'> ' . AS_DOC_SELECT . ' </option>';
    while ($row2 = mysql_fetch_array($mysql_result2)) {
        $result .= '<option value=\'' . $row2['ID'] . '\'  ' . ($row2['ID'] == $category ? 'selected' : '') . '>' . (($lang == 'en' && $row2['name_eng'] != '') ? $row2['name_eng'] : $row2['name']) . '</option>';
    }

    $result .= ' </select></td></tr></table>
									</td>
								  </tr>';
}
//}
$result .= '<tr>';
$result .= dokumenty_lista_pozycji($txt_search);

$result .= '</td>             </tr>
			                </table>';

echo iconv('latin2', 'UTF-8', $result);


function getDocumentsList($case_id)
{
    $corisCase = new CorisCase($case_id);
    $branchId = $corisCase->getBranchId();

    $sql = "SELECT cdt.*, cdtl.lang AS lang 
            FROM coris_document_templates AS cdt
            LEFT JOIN coris_document_template_languages AS cdtl ON cdtl.ID=cdt.language_id
            WHERE status=1
            AND visible_list1=1 ";
    if ($branchId == 1)
        $sql .= " AND coris_branch_id = '$branchId' ";
    if ($branchId == 2 || $branchId == 3)
        $sql .= " AND coris_branch_id = '2' ";


    $INTERCO = array(53, 54, 55, 59, 70, 71, 6050, 11358, 13850, 2231);

    if (in_array($corisCase->getClient_id(), $INTERCO)) {
        $sql .= " OR cdt.ID IN (79,80) ";
    }

    $sql .= " ORDER BY name";
    $mysqlRes = mysql_query($sql) or die($sql . '<br>' . mysql_error());
    $re = '';
    while ($row = mysql_fetch_assoc($mysqlRes)) {
        $re .= '<option value="DOC_new_document.php?case_id=' . $case_id . '&doc=' . $row['ID'] . '&doclang=' . $row['lang'] . '">' . $row['name'] . ' (' . $row['lang'] . ')' . '</option>';

    }
    return $re;
}


function dokumenty_lista_pozycji($txt_search)
{
    global $reclamation, $lang;
    $case_id = getValue('case_id');


    $type = getValue('type');
    $category = getValue('category');
    $txt_search = getValue('txt_search');


    $direction = getValue('sort_direction') != '' ? getValue('sort_direction') : '';
    $order = getValue('order') != '' ? getValue('order') : "date";


    $param_array = array();
    $order_array = array();

    if ($_SESSION['new_user'] == 1) {
        $param_array['internal'] = '0';
    }

    if ($type > 0) {
        $param_array['ID_document_type'] = $type;
    }

    if ($category > 0) {
        $param_array['ID_category'] = $category;
    }

    if ($reclamation == 1) {
        $param_array['reclamation'] = 1;
    }

    if ($txt_search != '') {
        $param_array['search'] = $txt_search;
    }


    $order_array = array('order' => $order, 'direction' => $direction);


    $case_interactions = new CaseInteractions($case_id, $param_array, $order_array);
    $case_interactions->execute();

    $result = '';
    $result .= '<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td align="right" style="border-bottom: #6699cc 1px solid;" height="30">';
    if ($reclamation == 0) {
        $result .= '<font color="#6699cc">' . AS_CASD_WYSZ . '</font> 
				<input type="text" name="search_txt" id="search_txt" value="' . $txt_search . '" onChange="zastosuj_filter()" style="border-top: #6699cc 1px solid; border-bottom:  #6699cc 1px solid; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; background: #eeeeee">&nbsp;				
				';
    }
    $result .= '</td>
			</tr>
			<tr>
				<td valign="top">
			<div style="float:left;width:670;height:600px;overflow:auto">
					<table cellpadding="0" cellspacing="1" border="0" width="665">
						<tr height="6" align="center">
							<td width="20"><font color="#6699cc">' . TYPE . '</font></td>
							<td width="20"><font color="#6699cc">&nbsp;</font></td>
							<td width="110"><font color="#6699cc">CORIS</font></td>
							<td width="20"></td>
							<td width="220"><font color="#6699cc">' . AS_CASADD_KLIENT . '</font></td>
							<td width="205"><font color="#6699cc">' . AS_CASD_TEMAT . '</font></td>
							<td width="110"><font color="#6699cc">' . DATA . '</font></td>
						</tr>
						<tr height="6" style="cursor: hand" align="center">
							<td	bgcolor="' . (($order == "type") ? "#6699cc" : "#999999") . '" width="20" onmouseover="this.bgColor=\'#99ccff\';" onmouseout="this.bgColor=\'' . (($order == "type") ? "#6699cc" : "#999999") . '\';" onclick="clickOrder(\'type\')"></td>
							<td	bgcolor="' . (($order == "attach") ? "#6699cc" : "#999999") . '" width="20" onmouseover="this.bgColor=\'#99ccff\';" onmouseout="this.bgColor=\'' . (($order == "attach") ? "#6699cc" : "#999999") . '\';" ></td>
							<td bgcolor="' . (($order == "sender") ? "#6699cc" : "#999999") . '" width="100" onmouseover="this.bgColor=\'#99ccff\';" onmouseout="this.bgColor=\'' . (($order == "sender") ? "#6699cc" : "#999999") . '\';" onclick="clickOrder(\'sender\')"></td>
							<td bgcolor="' . (($order == "direction") ? "#6699cc" : "#999999") . '" width="20" onmouseover="this.bgColor=\'#99ccff\';" onmouseout="this.bgColor=\'' . (($order == "direction") ? "#6699cc" : "#999999") . '\';" onclick="clickOrder(\'direction\')"></td>
							<td bgcolor="' . (($order == "recipient") ? "#6699cc" : "#999999") . '" width="100" onmouseover="this.bgColor=\'#99ccff\';" onmouseout="this.bgColor=\'' . (($order == "recipient") ? "#6699cc" : "#999999") . '\';" onclick="clickOrder(\'recipient\')"></td>
							<td bgcolor="' . (($order == "subject") ? "#6699cc" : "#999999") . '" width="135" onmouseover="this.bgColor=\'#99ccff\';" onmouseout="this.bgColor=\'' . (($order == "subject") ? "#6699cc" : "#999999") . '\';" onclick="clickOrder(\'subject\')"></td>
							<td	bgcolor="' . (($order == "date") ? "#6699cc" : "#999999") . '" width="110" onmouseover="this.bgColor=\'#99ccff\';" onmouseout="this.bgColor=\'' . (($order == "date") ? "#6699cc" : "#999999") . '\';" onclick="clickOrder(\'date\')"></td>
						</tr>
						<tr height="5">
							<td colspan="5"></td>
						</td>';


    // while ($row = mysql_fetch_array($mysql_result)) {
    foreach ($case_interactions->getInteractions() as $pozycja) {
        $userObject = Application::getUser($pozycja->getUserId());

        $st1 = '';
        $st2 = '';
        $msg_new = '';
        if ($pozycja->getNew()) {
            $msg_new = AS_CASD_NOWDOK . '   ';
            $st1 = '<b>';
            $st2 = '</b>';
        }
        //<tr bgcolor="#e9e9e9" onmouseover="this.bgColor='#ced9e2';" onmouseout="this.bgColor='#e9e9e9';" style="cursor: hand" onclick="javascript:<?

        $is_call_or_note = in_array($pozycja->getType(), array(Interaction::$_TYPE_CALL, Interaction::$_TYPE_NOTE));

        $result .= '<tr id="row[' . $pozycja->getObjectID() . ']" class="interactions_row" ';
        if ($is_call_or_note) {
            if ($pozycja->getInternal()) {
                $result .= "bgcolor=\"#F5B757\"";
            } else {
                $result .= "bgcolor=\"lightyellow\"";
            }
        } else {
            $result .= "bgcolor=\"#e9e9e9\" OnMouseOver=\"this.bgColor='#ced9e2';\" onmouseout=\"this.bgColor='#e9e9e9'\"";
        }
        $result .= 'style="border-top: #ffffe0 1px solid; border-bottom: #ffffe0 1px solid; cursor: pointer;" onclick="open_case_interaction(' . $pozycja->getType() . ',' . $pozycja->getDirection() . ',' . $pozycja->getObjectId() . ',' . $pozycja->getCaseID() . ',\'case\',this)';


        $result .= '" height="24"><td align="center">';
        $result .= '<img src="img/' . $pozycja->getIco() . '">';
        $result .= '</td>';
        $result .= '<td align="center">';

        if ($pozycja->getType() == Interaction::$_TYPE_EMAIL) {
            if (count($pozycja->getDocument()->getAttchments()->get_list()) > 0) {
                $result .= '<img src="img/assets/attach.png">';
            } else {
                $result .= '&nbsp;';
            }

        } else {
            $result .= '&nbsp;';
        }

        $result .= '</td>';
        $result .= '<td align="center" title="';

        $result .= $userObject->getSurname() . ', ' . $userObject->getName();
        $result .= '"><font color="blue">';
        if ($pozycja->getType() == Interaction::$_TYPE_NOTE && $pozycja->getExternal() == 1) {
            $result .= $st1 . 'Notatka zewnêtrzna' . $st2;
        } else {
            $result .= $st1 . StrTrim($userObject->getSurname() . ', ' . $userObject->getName(), 20) . $st2;
        }
        $result .= '</font></td><td	width="20" align="center">';

        if ($pozycja->getDirection() == Interaction::$DIRECTION_IN) {
            $result .= '<img src="img/direct_in.png">';
        }

        if ($pozycja->getDirection() == Interaction::$DIRECTION_OUT) {
            $result .= '<img src="img/direct_out.png">';
        }

        $result .= '</td>
			<td	align="center" title="' . $pozycja->getInteractionName() . ' ' . $pozycja->getInteractionContact() . '"><font color="blue">';
        if ($pozycja->getType() == Interaction::$_TYPE_EMAIL) { //email
            $result .= $st1 . substr($pozycja->getInteractionContact(), 0, 30) . $st2;
        } else {
            $result .= ($pozycja->getInteractionContact() != "") ? $st1 . $pozycja->getInteractionContact() . "/" : "$st1";
            $result .= StrTrim($pozycja->getInteractionName(), 22) . $st2;
        }
        $result .= '</font></td>
		<td	 title="' . $pozycja->getInteractionSubject() . '">';//'.$row['note'] .'

        if ($pozycja->getDirection() == Interaction::$DIRECTION_IN && ($pozycja->getType() == Interaction::$_TYPE_EMAIL || $pozycja->getType() == Interaction::$_TYPE_FAX)) {
            $cat_name = $pozycja->getCategoryName($lang);
            $result .= $cat_name != '' ? $st1 . $cat_name . $st2 : $st1 . StrTrim($pozycja->getInteractionSubject(), 30) . $st2;
        } else {
            $result .= $st1 . StrTrim($pozycja->getInteractionSubject(), 30) . $st2;
        }
        $result .= '</td><td	align="right">';
        $result .= $st1;

        if (substr($pozycja->getDate(), 0, 10) == date("Y-m-d")) {
            $result .= "<font color=\"blue\">" . AS_CASD_DZIS . " " . substr($pozycja->getDate(), 11, 5) . "</font>";
        } else if (substr($pozycja->getDate(), 0, 10) == date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")))) {
            $result .= "<font color=\"darkblue\">" . AS_CASD_WCZ . " " . substr($pozycja->getDate(), 11, 5) . "</font>";
        } else {
            $result .= substr($pozycja->getDate(), 0, 16);
        }

        $result .= $st2;
        $result .= '</td></tr>';
        if ($is_call_or_note) {
            $result .= '<tr>
									<td colspan="7" bgcolor="' . ($pozycja->getInternal() ? '#F5B757' : 'lightyellow') . '" align="left"><small>' . nl2br($pozycja->getDocument()->getBody()) . '</td>
								</tr>';

        }
        if ($pozycja->getType() == Interaction::$_TYPE_EMAIL) { //email)
            $result .= '<tr>
					<td colspan="7" bgcolor="#d0d0d0" align="left"><small>' . substr(strip_tags($pozycja->getDocument()->getBody()), 0, 400) . '</td>
			</tr>';

        }
    }
    $result .= '                     
					</table>
			</div>		
			<div style="float:right;width:610px;height:600px;overflow:auto;border: 1px #000 solid;" id="case_document_view">
			</div>		
					
				</td>
			</tr>
		</table>';
    return $result;
}

function StrTrim($string, $length)
{
    return (strlen($string) < $length) ? $string : substr($string, 0, $length) . "...";
}

?>