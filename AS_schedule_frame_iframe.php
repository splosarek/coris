<?php include('include/include.php'); ?>
<html>
    <head>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-2">
    </head>
    <body>
    <?
if (isset($_GET['entry_id'])) {
    $query = "UPDATE coris_assistance_schedule SET active = 0 WHERE entry_id = $_GET[entry_id]";
    $result = mysql_query($query, $cn);
?>
        <script>parent.location = "AS_schedule_frame.php?offset=<?= $_GET['offset'] ?>&myMonth=<?= $_GET['myMonth'] ?>&myYear=<?= $_GET['myYear'] ?>";</script>
<?
} else if (isset($_GET['leave_id'])) {
    $query = "UPDATE coris_assistance_schedule_leaves SET active = 0 WHERE leave_id = $_GET[leave_id]";
    $result = mysql_query($query, $cn);
?>
        <script>parent.location = "AS_schedule_frame.php?offset=<?= $_GET['offset'] ?>&myMonth=<?= $_GET['myMonth'] ?>&myYear=<?= $_GET['myYear'] ?>";</script>
<?
}
?>
    </body>
</html>
