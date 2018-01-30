<?php

/**
 * Convert all errors into ErrorExceptions
 */
set_error_handler(
	function($severity, $errstr, $errfile, $errline) {
		throw new ErrorException($errstr, 1, $severity, $errfile, $errline);
	},
	E_USER_ERROR
);
