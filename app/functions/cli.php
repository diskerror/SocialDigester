<?php

/**
 * Write to CLI standard output.
 * Usage: cout('my output' . PHP_EOL);
 */
function cout($s)
{
	fwrite(STDOUT, $s);
}

/**
 * Write to CLI error output.
 * Usage: cerr('something weird happened' . PHP_EOL);
 */
function cerr($s)
{
	fwrite(STDERR, $s);
}
