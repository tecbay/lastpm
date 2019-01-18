<?php


/**
 * @param $key
 * @param $value
 */
function putPermanentEnv( $key, $value ) {
	$path = app()->environmentFilePath();

	$escaped = preg_quote( '=' . env( $key ), '/' );

	file_put_contents( $path, preg_replace(
		"/^{$key}{$escaped}/m",
		"{$key}={$value}",
		file_get_contents( $path )
	) );
}

function diskUsed() {
	$allowed_space = auth()->user()->plan->allowed_space;
	$used_space    = auth()->user()->used_spaces;
	return round( ( $used_space / $allowed_space ) * 100, 1 );
}

function fileSizeConverter( $byte ) {

	if ( $byte < 1000 ) {
		return round( $byte / 1000, 2 ) . ' kb';
	}
	if ( $byte < ( 1000 * 1000 ) ) {
		return round( $byte / ( 1000 * 1000 ), 2 ) . ' mb';
	}
	if ( $byte < ( 1000 * 1000*1000 ) ) {
		return round( $byte / ( 1000 * 1000 * 1000 ), 2 ) . ' gb';
	}
}

function folderValidate($folderId){
	if (!(auth()->user()->folders->find( $folderId ))) return;
	return true;
}