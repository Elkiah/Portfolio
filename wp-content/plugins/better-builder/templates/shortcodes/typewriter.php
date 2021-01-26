<?php

wp_enqueue_script( 'typed' );

$content = html_entity_decode($content);

//$content = strip_tags($content, '<br /><br><br/>');

//remove any new lines already in there
$content = str_replace("\n", "", $content);

//remove all <p>
//$content = str_replace("<p>", "", $content);

//replace <br /> with \n
$content = str_replace(array("<br />", "<br>", "<br/>"), "\n", $content);

//replace </p> with \n\n
//$content = str_replace("</p>", "", $content);


$lines = explode( "\n", $content );

array_filter($lines);

?>
<?php if ( $blinking_cursor ): ?>
<style>
/* Typewriter blinking cursor style */
.ityped-cursor {
    font-size: 2.2rem;
    opacity: 1;
    -webkit-animation: blink 0.3s infinite;
    -moz-animation: blink 0.3s infinite;
    animation: blink 0.3s infinite;
    animation-direction: alternate;
}

@keyframes blink {
    100% {
        opacity: 0;
    }
}

@-webkit-keyframes blink {
    100% {
        opacity: 0;
    }
}

@-moz-keyframes blink {
    100% {
        opacity: 0;
    }
}
</style>
<?php else: ?>
<style>
.typed-cursor.typed-cursor--blink {
    animation: none !important;
    -webkit-animation: none !important;
}
</style>
<?php endif; ?>
<span <?php echo $id_attr; ?>  class="better-typewriter <?php echo $class; ?>" <?php BetterCore()->do_style( $styles );?>></span>
<?php if ( ! empty( $content ) ) : ?>
<script>
    (function($) {
        $(document).ready(function() {
			var typed = new Typed('#<?php echo $id; ?>', {
				strings: <?php echo json_encode( $lines ); ?>,
				stringsElement: null,
				typeSpeed: <?php echo empty( $type_speed ) ? 100 : $type_speed ?>,
				backSpeed: <?php echo empty( $back_speed ) ? 50 : $back_speed ?>,
				backDelay: <?php echo empty( $back_delay ) ? 500 : $back_delay ?>,
				loop: <?php echo empty( $loop ) ? 'false' : 'true'; ?>,
				showCursor: <?php echo empty( $show_cursor ) ? 'false' : 'true'; ?>,
				cursorChar: "<?php echo $cursor; ?>",
				contentType: 'html',
				//stringsElement: '#typed-strings'
			});
			/*
            ityped.init('#<?php //echo $id; ?>', {
                strings: <?php //echo json_encode( $lines ); ?>,
                //optional
                typeSpeed: <?php //echo empty( $type_speed ) ? 100 : $type_speed ?>, //default
                //optional
                backSpeed: <?php //echo empty( $back_speed ) ? 50 : $back_speed ?>, //default
                //optional
                startDelay: <?php //echo empty( $start_delay ) ? 500 : $start_delay ?>, //default
                //optional
                backDelay:  <?php //echo empty( $back_delay ) ? 500 : $back_delay ?>, //default
                //optional
                loop:       <?php //echo empty( $loop ) ? 'false' : 'true'; ?>, //default
                //optional
                showCursor: <?php //echo empty( $show_cursor ) ? 'false' : 'true'; ?>, //default
                //optional
                cursorChar: "<?php //echo $cursor; ?>", //default
                // optional callback called (if `loop` is false) once the
                // last string was typed
                onFinished: function(){},
			});
			*/
        });
    })(jQuery);
</script>
<?php endif; ?>