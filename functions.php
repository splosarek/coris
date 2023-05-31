<?php
	function getPaymentTypes() {
		$handle = mysql_connect("", "script", "script") or die (mysql_error());
		
		$query = "SELECT paymenttype_id, value FROM coris_finances_paymenttypes WHERE coris = 1 ORDER BY paymenttype_id";
		$paymenttypes = array();
		if ($result = mysql_query($query, $handle)) {
			while ($row = mysql_fetch_row($result)) {
				$paymenttypes[] = $row;
			}
		} else {
			die(mysql_error());
		}
		mysql_free_result($result);

		return $paymenttypes;
	}
	function getUsers() {
		$handle = mysql_connect("", "script", "script") or die (mysql_error());
		
		$query = "SELECT user_id, surname, name FROM coris_users ORDER BY surname";
		$users = array();
		if ($result = mysql_query($query, $handle)) {
			while ($row = mysql_fetch_row($result)) {
				$users[] = $row;
			}
		} else {
			die(mysql_error());
		}
		mysql_free_result($result);

		return $users;
	}
	function getCurrencies() {
		$handle = mysql_connect("", "script", "script") or die (mysql_error());
		
		$query = "SELECT currency_id, name FROM coris_finances_currencies WHERE active = 1 ORDER BY currency_id";
		$currencies = array();
		if ($result = mysql_query($query, $handle)) {
			while ($row = mysql_fetch_row($result)) {
				$currencies[] = $row;
			}
		} else {
			die(mysql_error());
		}
		mysql_free_result($result);
	
		return $currencies;
	}
	function getBookingTypes($type) {
		// type 1 - sprzeda¿
		// type 2 - kupno
		$handle = mysql_connect("", "script", "script") or die (mysql_error());
		
		$query = "SELECT bookingtype_id, value FROM coris_finances_bookings_bookingtypes WHERE active = 1";
		if ($type == 1) 
			$query .= " AND symbol LIKE '20'";
		else if ($type == 2)
			$query .= " AND symbol LIKE '10'";

		$bookingtypes = array();
		if ($result = mysql_query($query, $handle)) {
			while ($row = mysql_fetch_row($result)) {
				$bookingtypes[] = $row;
			}
		} else {
			die(mysql_error());
		}
		mysql_free_result($result);
		
		return $bookingtypes;
	}
	function getRate($type, $date, $currency_id) {
		// type 1 - œredni
		// type 2 - kupno
		// type 3 - sprzeda¿
		$handle = mysql_connect("", "script", "script") or die (mysql_error());
		
		$query = "SELECT fct.table_id, CONCAT_WS('/', fct.number, fct.type_id, 'NBP', fct.year) AS table_no, fct.publication_date, fctr.rate, fctrt.value AS ratetype_value FROM  coris_finances_currencies_tables fct, coris_finances_currencies_tables_rates fctr,  coris_finances_currencies_tables_ratetypes fctrt WHERE fct.table_id = fctr.table_id AND  fct.ratetype_id = fctrt.ratetype_id AND fct.active = 1 AND fct.ratetype_id = 1 AND fctr.symbol  LIKE 'EUR' AND fct.publication_date <= '2004-02-11' ORDER BY fct.publication_date DESC LIMIT 1";

		$ratearray = array();
		if ($result = mysql_query($query, $handle)) {
			if ($row = mysql_fetch_row($result)) {
				$ratearray[] = $row;
			}
		} else {
			die(mysql_error());
		}
		mysql_free_result($result);
		
		return $ratearray;
	}
?>