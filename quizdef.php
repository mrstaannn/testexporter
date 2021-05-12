<?php
require_once "dbconnector.php";
$dataBase->questionList_Identifier();
?>

<html>
<head>
</head>
<body>
<div>
    <?php $dataBase->questionList_Compactor(); ?>
</div>
</body>
</html>