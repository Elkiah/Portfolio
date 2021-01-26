<?php

require BETTERBUILDER_PLUGIN_FOLDER . '/assets/shortcodes/filler/filler-text.php';

$output = '';

if ( $paragraphs > 0 || $words > 0 || $bytes > 0 ) {
	$content = '';

	if ( $words > 0 ) $paragraphs = ceil( $words * .5 ); //pull off enough words to use later
	if ( $bytes > 0 ) $paragraphs = ceil( $bytes * .5 ); //pull off enough bytes to use later

	for ( $i=0; $i < $paragraphs; $i++ ) {
		$sentences = rand( 3, 12 );

		$content .= '<p>';

		for ( $j=0; $j < $sentences; $j++ ) {
			if ( $content === '<p>' && $start_with_lorem ) {
				$content .= $lipsum[ 0 ] . ' ';
			} else {
				$content .= $lipsum[ rand( 0, count( $lipsum ) - 1 ) ] . ' ';
			}
		}

		$content .= '</p>';
	}

	if ( $words > 0 ) {
		$word_list = explode( ' ', $content );

		for ( $i=0; $i < $words; $i++ ) {
			$output .= $word_list[ $i ] . ' ';
		}

		if ( substr_compare( $output, '</p>', -strlen( '</p>' ), strlen( '</p>' ) ) !== 0 ) {
			$output = trim( $output ) . '.</p>';
		}

		if ( $is_title ) {
			$output = str_replace( array( "<p>", "</p>", "." ), '', $output );
			$output = rtrim( $output, ',' );
			$output = ucwords( $output );
		}
	} else if ( $bytes > 0 ) {
			$output = str_replace( array( "<p>", "</p>", "." ), '', $content );
			$output = substr( $output, 0, $bytes );

			if ( $is_title ) {
				$output = str_replace( array( "<p>", "</p>", "." ), '', $output );
				$output = rtrim( $output, ',' );
				$output = ucwords( $output );
			} else {
				$output = '<p>' . $output . '</p>';
			}
		} else {
		$output = $content;
	}
} else if ( $list > 0 ) {
		$start_index = 0;

		$output .= '<ul>';

		if ( $start_with_lorem ) {
			$start_index = 0;
		} else {
			$start_index = rand( 0, count( $lipsum ) - 1 );
		}

		for ( $i=0; $i < $list; $i++ ) {
			$output .= '<li>' . $lipsum[ $start_index ] . '</li>';

			$start_index = rand( 0, count( $lipsum ) - 1 );
		}

		$output .= '</ul>';
	}

if ( $paragraph_separator == 'br' ) {
	$output = str_replace( array( "</p><p>" ), "<br /><br />", $output );
	$output = str_replace( array( "<p>" ), "", $output );
} else if ( $paragraph_separator == 'span' ) {
	$output = str_replace( array( "<p>" ), "<span>", $output );
	$output = str_replace( array( "</p>" ), "</span>", $output );				
}

echo $output;