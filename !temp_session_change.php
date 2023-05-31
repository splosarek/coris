<?php
session_start();
if(!isset($_SESSION['coris_branch']))
{
    exit('Brak:  <b>$_SESSION[\'coris_branch\']</b>');
}

if(isset($_GET['cb']))
{
    $_SESSION['coris_branch'] = trim(intval($_GET['cb']));
    //echo 'zmieniono na: ' . $_SESSION['coris_branch'] . "<br/>\n";
}
echo 'wartosc oddzia³u: <b>' . $_SESSION['coris_branch'] . "</b>\n";
echo "<br/>\n";
echo "<br/>\n";

    
    
?>
<form>
    <span>oddzia³: </span>
    <input type="submit" name="cb" value="1 polski">
    <input type="submit" name="cb" value="2 niemiecki">
    <input type="submit" name="refresh" value="refresh">
</form>


<?php
if(!isset($_SESSION['GUI_language']))
{
    exit('Brak:  <b>$_SESSION[\'GUI_language\']</b>');
}

if(isset($_GET['lang']))
{
    $_SESSION['GUI_language'] = trim(($_GET['lang'])) ;
    //echo 'zmieniono na: ' . $_SESSION['GUI_language'] . "<br/>\n";
}
echo 'wartosc GUI_language: <b>' . $_SESSION['GUI_language'] . "</b>\n";
echo "<br/>\n";
echo "<br/>\n";

    
    
?>
<form>
    <span>languag: </span>
    <input type="submit" name="lang" value="en">
    <input type="submit" name="lang" value="pl">
    <input type="submit" name="refresh" value="refresh">
</form>