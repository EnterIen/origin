This is header!
<br>
<?=$content?>

<?php
	foreach ($data as $key => $value) {
		echo $key . ':' . $value . PHP_EOL;
	}
?>

<!-- <?php
if (isset($result['data'])) {
	foreach ($result['data'] as $key => $value) {
		echo $key . ':' . $value . PHP_EOL;
	}
}
?>
 -->
<br>
This is footer!
