<?php
echo ($row['reclamation']) ? "<font color=\"red\" title=\"".AS_CASES_REKL."\" style=\"cursor: default; font-size: 16pt;\">R</font>" : "";
echo ($row['costless']) ? "<font color=\"#bdc8d1\" title=\"".AS_CASES_BEZKOSZT."\" style=\"cursor: default; font-size: 18pt; text-decoration: line-through;\">$</font>" : ""; 
echo ($row['unhandled']) ? "<font color=\"#bdc8d1\" title=\"".AS_CASES_ZAMKBEZRYCZHON."\" style=\"cursor: default; font-size: 18pt;\" face=\"Webdings\">y</font>" : "";
echo ($row['archive']) ? "<font color=\"#bdc8d1\" face=\"webdings\" title=\"".AS_CASES_ARCH."\" style=\"cursor: default; font-size: 18pt;\">I</font>" : "";
echo ($row['watch']) ? "<font color=\"#bdc8d1\" face=\"webdings\" title=\"".AS_CASES_NOWEWIAD."\" style=\"cursor: default; font-size: 20pt;\">N</font>" : "";
echo ($row['transport']) ? "<font color=\"#bdc8d1\" face=\"wingdings\" title=\"".AS_CASES_TRANSP."\" style=\"cursor: default; font-size: 18pt;\">Q</font>" : "";
echo ($row['decease']) ? "<font color=\"#bdc8d1\" face=\"wingdings\" title=\"".AS_CASES_ZGON."\" style=\"cursor: default; font-size: 18pt;\">U</font>" : "";
echo ($row['ambulatory']) ? "&nbsp;<font color=\"#bdc8d1\" title=\"".AS_CASES_AMB."\" style=\"cursor: default; font-size: 16pt;\">A</font>" : "";
echo ($row['hospitalization']) ? "&nbsp;<font color=\"#bdc8d1\" title=\"".AS_CASES_HOSP."\" style=\"cursor: default; font-size: 16pt;\">H</font>" : "";
?>