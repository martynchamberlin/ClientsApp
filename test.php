<?php

$end = time() + 1;

while ( time() < $end ) {
	static $i = 1;
	echo $i++ . " ";
	if ($i == 500000 )
		break;
}

?>
