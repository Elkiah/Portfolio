<?php

if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

// Setup Form
$form = tr_form()->useJson()->setGroup( 'better_builder' );
?>

<div class="typerocket-container">
    <?php
    echo $form->open();

    $max_width = function() use ($form) {
            $options = [
                'Yes' => 'yes',
                'No' => 'no'
            ];

            echo $form->text( 'Small Template')
                    ->setAttribute( 'value', 750 );
            echo $form->text( 'Large Template')
                    ->setAttribute( 'value', 1140 );
            echo $form->select( 'Post default')
                    ->setOptions( [
                        'Small' => 'small',
                        'Large' => 'nosidebar',
                        'Fullwidth' => 'fullwidth'
                    ] )
                    ->setSetting( 'default', 'small' );
            echo $form->select( 'Page default')
                    ->setOptions( [
                        'Small' => 'small',
                        'Large' => 'nosidebar',
                        'Fullwidth' => 'fullwidth'
                    ] )
                    ->setSetting( 'default', 'small' );
            echo $form->radio( 'Enable Editor Width')
                    ->setOptions($options)
                    ->setHelp( "Allows for changes to the editor width on per page/post basis with preset defaults." )
                    ->setSetting( 'default', 'yes' );
    };

    $apis = function() use ($form) {
        $help = "To use the GIPHY API, you'll need to obtain an API Key by <a href='https://developers.giphy.com/dashboard/?create=true'>creating an app</a>. You'll need a GIPHY account to create an app (don't worry, it's free!) Each application you create will have its own API Key. Don't worry if you're not sure what you want to use the GIPHY API for yet, all that's needed to create an app is a name and a basic description, which can be changed at any time.";
            echo $form->text( 'GIPHY API Key')
                    ->setHelp($help);
    };

    // Save
    $save = $form->submit( 'Save Changes' );

    tr_tabs()->setSidebar( $save )
    ->addTab( 'Editor Width', $max_width )
    ->addTab( 'APIs', $apis )
    ->render('box');
    echo $form->close();
    ?>
</div>