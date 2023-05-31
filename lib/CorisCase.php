<?php


class CorisCase
{

    var $case_id = 0;
    var $number;
    var $year;
    var $type_id;
    var $client_id;
    var $eventdate;
    var $paxname;
    var $paxsurname;
    var $country_id;
    var $date;
    var $client_ref;
    var $reclamation;
    var $branch_id;
    var $policyamount;
    var $policycurrency_id;
    var $paxmobile;
    var $fullNumber;
    var $archive;
    var $sex;
    var $paxaddress;
    var $paxpost;
    var $paxcity;
    var $paxcountry;


    function __construct($case_id)
    {
        if ($case_id > 0) {
            $this->case_id = $case_id;
            $this->loadCase($case_id);
        } else {
            throw new Exception('Wrong case_id:' . $case_id);
        }
    }

    function loadCase($case_id)
    {

        $storage = Application::getStorage();
        $query = "SELECT ac.case_id, ac.number, ac.year, ac.client_id, ac.type_id, ac.client_ref, ac.user_id, ac.date, ac.paxname, ac.paxsurname,ac.paxsex, ac.paxdob,ac.pax_email,ac.pax_pesel,
				 ac.policy,ac.policy_series, ac.cart_number, ac.event, ac.eventdate, ac.country_id, ac.city, ac.post, ac.watch, ac.ambulatory, ac.hospitalization, ac.transport, ac.decease, ac.archive, 
				 ac.costless, ac.unhandled, ac.reclamation, ac.status_client_notified, ac.status_policy_confirmed, ac.status_documentation, ac.status_decision, ac.status_assist_complete, ac.status_account_complete, ac.status_settled, ac.attention,ac.attention2, DATE(ac.archive_date) AS archive_date, acd.notificationdate, acd.informer, acd.validityfrom, acd.validityto, acd.policypurchasedate, acd.policypurchaselocation, acd.policyamount, acd.policycurrency_id, acd.circumstances, acd.comments,ac.marka_model,ac.nr_rej,ac.vin,acd.paxphone ,acd.paxmobile,ac.adress1,ac.adress2,acd.paxaddress, acd.paxpost, acd.paxcity, acd.paxcountry, acd.paxphone, acd.paxmobile ,
				 acd.validityfromDep,acd.validitytoDep,ac.telefon1 ,ac.telefon2,ac.status_briefcase_found,ac.liquidation,ac.claim_handler_date,ac.claim_handler_user_id,
				acd.ehic_no,acd.validityToEhic,acd.ehic_user_id,acd.ehic_date, ac.ID_cause, ac.status_send,ac.coris_branch_id,concat(number,'/',substring(year,3,2),'/',type_id,'/',client_id) As fullNumber,
				acd.paxaddress, acd.paxpost, acd.paxcity,  acd.paxcity,  acd.paxcountry 
				FROM coris_assistance_cases ac, coris_assistance_cases_details acd 
				WHERE ac.case_id = '$case_id' AND  ac.case_id = acd.case_id AND ac.active = 1 ";

        if ($_SESSION['new_user'] == 1) {
            $query .= " AND ac.`date` >= '2008-05-01 00:00:00' AND (ac.client_id=7592 OR ac.client_id=600 )";

        }

        $result = $storage->query($query);
        if ($row = $storage->fetch_row($result)) {
            $this->client_id = $row['client_id'];
            $this->number = $row['number'];
            $this->year = $row['year'];
            $this->paxname = $row['paxname'];
            $this->paxsurname = $row['paxsurname'];
            $this->date = $row['date'];
            $this->type_id = $row['type_id'];
            $this->client_ref = $row['client_ref'];
            $this->reclamation = $row['reclamation'];
            $this->branch_id = $row['coris_branch_id'];

            $this->policyamount = $row['policyamount'];
            $this->policycurrency_id = $row['policycurrency_id'];

            $this->paxmobile = $row['paxmobile'];
            $this->fullNumber = $row['fullNumber'];
            $this->archive = $row['archive'];
            $this->sex = $row['paxsex'];
            $this->eventdate = $row['eventdate'];

            $this->paxaddress = $row['paxaddress'];
            $this->paxpost = $row['paxpost'];
            $this->paxcity = $row['paxcity'];
            $this->paxcountry = $row['paxcountry'];


            //return $row;
        } else {
            die('Case error, case_id=' . $case_id);
        }

    }


    function check_archive()
    {
        return $this->archive;
    }

    static function check_archiveS($case_id)
    {
        $query = "SELECT archive FROM coris_assistance_cases  WHERE case_id = '$case_id' ";
        $mysql_result = mysql_query($query);
        if (!$mysql_result)
            throw new Exception("Error q: " . $query . "\n" . mysql_error());
        $row = mysql_fetch_array($mysql_result);

        return $row[0];

    }

    static function openCase($case_id, $category_id)
    {
        $case_id = intval($case_id);
        if ($case_id > 0) {
            $var = '';
            if ($category_id <> 10 && $category_id <> 11)
                $var .= ",status_assist_complete=0,status_assist_complete_date=null,status_assist_complete_user_id=0";

            $query = "UPDATE coris_assistance_cases SET  archive = 0, archive_date = null,archive_user_id=0 $var WHERE coris_assistance_cases.case_id = '$case_id' ";
            $mysql_result = mysql_query($query);
            if (!$mysql_result)
                throw new Exception("Error q: " . $query . "\n" . mysql_error());

        }
    }

    static function set_case_reclamation($case_id)
    {
        $query = "UPDATE coris_assistance_cases
		SET  coris_assistance_cases.reclamation=1 WHERE coris_assistance_cases.case_id = '$case_id' ";
        $mysql_result = mysql_query($query);
        if (!$mysql_result)
            throw new Exception("Error q: " . $query . "\n" . mysql_error());

        //	$this->reclamation=1;
    }

    /**
     * @return the $number
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return the $year
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @return the $type_id
     */
    public function getType_id()
    {
        return $this->type_id;
    }

    /**
     * @return the $client_id
     */
    public function getClient_id()
    {
        return $this->client_id;
    }

    /**
     * @return the $eventdate
     */
    public function getEventdate()
    {
        return $this->eventdate;
    }

    /**
     * @return the $paxname
     */
    public function getPaxname()
    {
        return $this->paxname;
    }

    /**
     * @return the $paxsurname
     */
    public function getPaxsurname()
    {
        return $this->paxsurname;
    }

    /**
     * @return the $country_id
     */
    public function getCountry_id()
    {
        return $this->country_id;
    }

    /**
     * @return the $date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param $number the $number to set
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @param $year the $year to set
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @param $type_id the $type_id to set
     */
    public function setType_id($type_id)
    {
        $this->type_id = $type_id;
    }

    /**
     * @param $client_id the $client_id to set
     */
    public function setClient_id($client_id)
    {
        $this->client_id = $client_id;
    }

    /**
     * @param $eventdate the $eventdate to set
     */
    public function setEventdate($eventdate)
    {
        $this->eventdate = $eventdate;
    }

    /**
     * @param $paxname the $paxname to set
     */
    public function setPaxname($paxname)
    {
        $this->paxname = $paxname;
    }

    /**
     * @param $paxsurname the $paxsurname to set
     */
    public function setPaxsurname($paxsurname)
    {
        $this->paxsurname = $paxsurname;
    }

    /**
     * @param $country_id the $country_id to set
     */
    public function setCountry_id($country_id)
    {
        $this->country_id = $country_id;
    }

    /**
     * @param $date the $date to set
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return the $client_ref
     */
    public function getClient_ref()
    {
        return $this->client_ref;
    }

    /**
     * @param $client_ref the $client_ref to set
     */
    public function setClient_ref($client_ref)
    {
        $this->client_ref = $client_ref;
    }


    /**
     * @return the full case number
     */
    public function getCaseNumber()
    {
        return $this->number . '/' . substr($this->year, 2) . '/' . $this->type_id . '/' . $this->client_id;
    }


    static function search_case($nr)
    {
        $storage = Application::getStorage();

        $query_cases = "SELECT case_id  FROM coris_assistance_cases WHERE ";

        $case_number = explode("/", $nr);
        if (count($case_number) > 1)
            $query_cases .= " number = '$case_number[0]' AND year = '$case_number[1]' ";
        else
            $query_cases .= " number = '$case_number[0]' ";


        $mysql_result = $storage->query($query_cases);
        $result = array();
        while ($row = $storage->fetch_row($mysql_result)) {
            $result[] = $row[0];

        }
        if (count($result) == 0) $result[] = 'null';
        return implode(',', $result);
    }

    /**
     * @return the $reclamation
     */
    public function getReclamation()
    {
        return $this->reclamation;
    }


    public function getBranchId()
    {
        return $this->branch_id;
    }

    /**
     * @return the $policyamount
     */
    public function getPolicyamount()
    {
        return $this->policyamount;
    }

    /**
     * @return the $policycurrency_id
     */
    public function getPolicycurrency_id()
    {
        return $this->policycurrency_id;
    }

    /**
     * @return the $paxmobile
     */
    public function getPaxmobile()
    {
        return $this->paxmobile;
    }

    /**
     * @return the $fullNumber
     */
    public function getFullNumber()
    {
        return $this->fullNumber;
    }


    public function getPaxSex()
    {
        return $this->sex;
    }


    public function getPaxAddress()
    {
        return $this->paxaddress;
    }

    public function getPaxPost()
    {
        return $this->paxpost;
    }

    public function getPaxCity()
    {
        return $this->paxcity;
    }

    public function getPaxCountry()
    {
        return $this->paxcountry;
    }


    static function updateClientRef($case_id, $nr)
    {

        if ($case_id > 0) {
            $query = "UPDATE coris_assistance_cases SET client_ref='$nr'  WHERE case_id='$case_id' LIMIT 1";
            $mr = mysql_query($query);
            //  echo "$query <br>".mysql_error();
        }


    }

    static function updateDataWyjazdu($case_id, $date)
    {

        if ($case_id > 0) {
            $query = "UPDATE coris_assistance_cases_details SET validityFromDep='$date'  WHERE case_id='$case_id' LIMIT 1";
            $mr = mysql_query($query);
            //  echo "$query <br>".mysql_error();
        }


    }

    static function updateTypeGenre($case_id, $type, $genre)
    {

        if ($case_id > 0) {
            $query = "UPDATE coris_assistance_cases  SET 	type_id='$type',genre_id='$genre'  WHERE case_id='$case_id' LIMIT 1";
            $mr = mysql_query($query);
            //  echo "$query <br>".mysql_error();
        }


    }

    static function updatePaxEmail($case_id, $_email)
    {

        if ($case_id > 0) {
            $query = "UPDATE coris_assistance_cases  SET 		pax_email='$_email'  WHERE case_id='$case_id' LIMIT 1";
            $mr = mysql_query($query);
            //  echo "$query <br>".mysql_error();
        }


    }


}


?>