<?php
header("Content-Type: text/html;charset=utf-8");
?>
<html>
<head>
    <script type="text/javascript" src="<?=Yii::getAlias('@web').'/js/jsAddress.js'?>"></script>
</head>

<body>
<select id="cmbProvince" name="cmbProvince"></select>
<select id="cmbCity" name="cmbCity"></select>
<select id="cmbArea" name="cmbArea"></select>
<script type="text/javascript">
    addressInit('cmbProvince', 'cmbCity', 'cmbArea');
</script>
</body>
</html>

