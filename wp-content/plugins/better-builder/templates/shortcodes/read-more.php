<?php
wp_enqueue_style( 'better.readmore' );

$height = is_numeric( $height ) ? $height . 'px' : $height;
?>
<style>
    <?php echo '#' . $id; ?> {
        max-height: <?php echo $height; ?>;
        color: <?php echo $text_color; ?>;
    }
    <?php if ( ! empty( $fading_color ) || ! empty( $btn_align ) ) { ?>
    <?php echo '#' . $id; ?> .read-more {
        <?php if ( ! empty( $btn_align ) ) { ?>
            text-align: <?php echo $btn_align; ?>;
        <?php } ?>
        <?php if ( ! empty( $fading_color ) ) { ?>
            background-image: linear-gradient(to bottom, transparent 0%, <?php echo $fading_color; ?> 45%, <?php echo $fading_color; ?> 100%);
        <?php } ?>
    }
    <?php } ?>
    <?php if ( ! empty( $btn_color ) ) { ?>
    <?php echo '#' . $id; ?> .read-more > .rm-btn {
        color: <?php echo $btn_color; ?>;
    }
    <?php } ?>
</style>

<div <?php echo $id_attr; ?> class="sidebar-box<?php echo ( ! empty( $class ) ? ' ' . $class : '' ); ?>" >
    <?php echo $content; ?>
    <p class="read-more"><a href="#" class="rm-btn"><?php echo ( ! empty( $read_more_text ) ) ? $read_more_text : esc_html__( 'Read more' ); ?></a></p>
</div>

<script>
    jQuery(document).ready(function($) {
        var $el, $ps, $up, totalHeight;

        $(".sidebar-box .rm-btn").click(function() {
            
            totalHeight = 0

            $el = $(this);
            $p  = $el.parent();
            $up = $p.parent();
            $ps = $up.find("p:not('.read-more')");

            // measure how tall inside should be by adding together heights of all inside paragraphs (except read-more paragraph)
            $ps.each(function() {
            totalHeight += $(this).outerHeight();
            });
                
            $up
            .css({
                // Set height to prevent instant jumpdown when max height is removed
                "height": $up.height(),
                "max-height": 9999
            })
            .animate({
                "height": totalHeight
            });

            // fade out read-more
            $p.fadeOut();

            // prevent jump-down
            return false;
            
        });
    });
</script>