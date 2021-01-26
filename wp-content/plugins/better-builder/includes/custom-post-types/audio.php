<?php
$audio = tr_post_type( __('Track', 'better' ), __('Audio Tracks', 'better' ), array( 'show_in_rest' => true ) );
$audio->setId('better_audio');
$audio->setIcon('headphones');
$audio->setArgument('supports', ['title', 'thumbnail'] );

tr_taxonomy( __('Album', 'better' ), __('Albums', 'better' ), array( 'show_in_rest' => true ) )->apply($audio);
tr_taxonomy( __('Artist', 'better' ) )->apply($audio);
tr_taxonomy( __('Genre', 'better' ) )->apply($audio);
// tr_taxonomy( __('Style', 'better' ) )->apply($audio);
// tr_taxonomy( __('Record Label', 'better' ) )->apply($audio);
// tr_taxonomy( __('Studio', 'better' ) )->apply($audio);
// tr_taxonomy( __('Remixer', 'better' ) )->apply($audio);
// tr_taxonomy( __('Producer', 'better' ) )->apply($audio);
// tr_taxonomy( __('Catalog Number', 'better' ) )->apply($audio);
// tr_taxonomy( __('Language', 'better' ) )->apply($audio);
 
tr_meta_box( __('Audio Details', 'better') )->apply($audio);

function add_meta_content_audio_details() {
	$form = tr_form();
    $file = $form->file( __('File', 'better') );
    //$file->setSetting('button', 'Audio File');
    echo $file;
    echo $form->text( __('File URL', 'better') );
    echo $form->text( __('Duration', 'better') );
    //echo $form->checkbox( __('Live Audio', 'better') );
    //$editor = $form->editor('post_content');
    //echo $editor->setLabel('About Person');
}

$audio->setTitleForm( function() {
    
} );

