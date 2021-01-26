<?php
	function toolkit_get_default_dashboard_widgets() {
        global $wp_meta_boxes;
        $screen = is_network_admin() ? 'dashboard-network' : 'dashboard';
        $current_screen = get_current_screen();
        if ( ! isset( $wp_meta_boxes[ $screen ] ) || ! is_array( $wp_meta_boxes[ $screen ] ) ) {
            require_once ABSPATH . '/wp-admin/includes/dashboard.php';
            set_current_screen( $screen );
            wp_dashboard_setup();
        }
        if ( isset( $wp_meta_boxes[ $screen ][0] ) ) {
            unset( $wp_meta_boxes[ $screen ][0] );
        }
        $widgets = [];
        if ( isset( $wp_meta_boxes[ $screen ] ) ) {
            $widgets = $wp_meta_boxes[ $screen ];
        }
        set_current_screen( $current_screen );
		return $widgets;
	}

	function toolkit_get_default_wordpress_widgets() {
		$widgets = [];
		if ( ! empty( $GLOBALS['wp_widget_factory'] ) ) {
			$widgets = $GLOBALS['wp_widget_factory']->widgets;
		}			
		return $widgets;
	}

	function get_disabled_dashboard_widgets() {
		$widgets = (array) get_option( 'toolkit_wp_widget_disable_dashboard', [] );

		if ( is_network_admin() ) {
			$widgets = (array) get_site_option( 'toolkit_wp_widget_disable_dashboard', [] );
		}

		return $widgets;
	}
	
	function render_elementor_widgets() {
		echo '<form id="elementor_widgets_ds" action="">';	
		$elementor_widget_blacklist_common = ['author-box','post-comments','post-navigation','post-info','accordion','alert','audio','button','counter','divider','google_maps','heading','html','icon','icon-box','icon-list','image','image-box','image-carousel','image-gallery','menu-anchor','progress','read-more','shortcode','sidebar','social-icons','spacer','star-rating','tabs','testimonial','text-editor','toggle','video'];

		$elementor_widget_blacklist_pro = ['portfolio','template','login','media-carousel','testimonial-carousel','reviews','facebook-button','facebook-comments','facebook-embed','facebook-page','theme-builder','posts','gallery','form','slides','nav-menu','animated-headline','price-list','price-table','flip-box',
		'call-to-action','carousel','countdown','share-buttons','theme-elements','blockquote','social','library','dynamic-tags',
		'sticky','wp-cli','link-actions','table-of-contents'];

		$elementor_widget_blacklist_woo =['Archive-Products','Wc-Categories','Archive-Products-Deprecated','Archive-Description','Woocommerce-Products','Products-Deprecated',
		'Woocommerce-Breadcrumb','Wc-Pages','Wc-Add-To-Cart','Elements','Single-Elements','Categories','Woocommerce-Menu-Cart','Product-Title','Product-Images','Product-Price','Woocommerce-Product-Add-To-Cart',
		'Product-Rating','Product-Stock','Product-Meta','Product-Short-Description','Product-Content','Product-Data-Tabs','Product-Additional-Information',
		'Product-Related','Product-Upsell'];

		$options = (array) get_option( 'toolkit_elementor_widgets_disable', [] );
		
		foreach ( $elementor_widget_blacklist_common as $id ) {
			$try_replace = str_replace('_',' ',$id);
			$try_replace = str_replace('-',' ',$try_replace);
			$widget_name = strtoupper($try_replace);
			printf(
				'<p><input type="checkbox" id="%1$s" name="%2$s" value="disabled" %3$s> <label for="%1$s">%4$s</label></p>',
				$id,
				'toolkit_elementor_widgets_disable' . '[' . $id . ']',
				checked( array_key_exists( $id, $options ), true, false ),
			sprintf(
				_x( '%1$s (%2$s)', 'elementor widget', 'wp-widget-disable' ),
				$widget_name,
				'<code>' . $id . '</code>'
				)
			);
		}
		if ( is_plugin_active( 'elementor-pro/elementor-pro.php' ) ) {
			foreach ( $elementor_widget_blacklist_pro as $id ) {
				$try_replace = str_replace('_',' ',$id);
				$try_replace = str_replace('-',' ',$try_replace);
				$widget_name = strtoupper($try_replace);
				printf(
						'<p><input type="checkbox" id="%1$s" name="%2$s" value="disabled" %3$s> <label for="%1$s">%4$s</label></p>',
				$id,
				'toolkit_elementor_widgets_disable' . '[' . $id . ']',
				checked( array_key_exists( $id, $options ), true, false ),
			sprintf(
				_x( '%1$s (%2$s)', 'elementor widget', 'wp-widget-disable' ),
				$widget_name,
				'<code>' . $id . '</code>'
					)
				);
			}
		}
		if ( class_exists( 'woocommerce' ) ) { 
			foreach ( $elementor_widget_blacklist_woo as $id ) {
				$try_replace = str_replace('_',' ',$id);
				$try_replace = str_replace('-',' ',$try_replace);
				$widget_name = strtoupper($try_replace);
				$widget_id = strtolower($id);
				printf(
						'<p><input type="checkbox" id="%1$s" name="%2$s" value="disabled" %3$s> <label for="%1$s">%4$s</label></p>',
				$widget_id,
				'toolkit_elementor_widgets_disable' . '[' . $widget_id . ']',
				checked( array_key_exists( $widget_id, $options ), true, false ),
			sprintf(
				_x( '%1$s (%2$s)', 'elementor widget', 'wp-widget-disable' ),
				$widget_name,
				'<code>' . $widget_id . '</code>'
					)
				);
			}
		}		
		?>
		<p>
		<button type="button" class="button-link" id="elementor_disable_select_all"><?php _e( 'Select all', 'wp-widget-disable' ); ?></button> |
		<button type="button" class="button-link" id="elementor_disable_deselect_all"><?php _e( 'Deselect all', 'wp-widget-disable' ); ?></button>
		</p>
		<div class="form-group">
			<input type="hidden" name="action" value="disable_elementor_widgets">
			<input type="hidden" name="_nonce" value="<?php echo wp_create_nonce('toolkit-elementor'); ?>"/>
			<button type="button" class="button toolkit-btn" id="disable-elementor-widgets"><?php _e('Disable Selected Widgets'); ?></button>
		</div>
		</form>		
	<?php	
	}
	
	function render_wordpress_widgets() {
		$toolkit_widgets = (array) toolkit_get_default_wordpress_widgets(); //get_option('toolkit_wordpress_widgets', [] );
		$widgets = wp_list_sort( $toolkit_widgets, [ 'name' => 'ASC' ], null, true );
		if ( ! $widgets ) {
			printf(
				'<p>%s</p>',
				__( 'Oops, we could not retrieve your wordpress widgets! This normally occurs when there is another plugin managing your widgets.', 'wp-widget-disable' )
				);
			return;
		}
		$options = (array) get_option( 'toolkit_wp_widget_disable_wordpress', [] );
		echo '<form id="wordpress_widgets_ds" action="">';
		foreach ( $widgets as $id => $widget_object ) {
			printf(
				'<p><input type="checkbox" id="%1$s" name="%2$s" value="disabled" %3$s> <label for="%1$s">%4$s</label></p>',
				esc_attr( $id ),
				esc_attr( 'toolkit_wp_widget_disable_wordpress' ) . '[' . esc_attr( $id ) . ']',
				checked( array_key_exists( $id, $options ), TRUE, false ),
			sprintf(
				_x( '%1$s (%2$s)', 'sidebar widget', 'wp-widget-disable' ),
				esc_html( $widget_object->name ),
				'<code>' . esc_html( $id ) . '</code>'
				)
			);
		}
		?>
		<p>
		<button type="button" class="button-link" id="wordpress_disable_select_all"><?php _e( 'Select all', 'wp-widget-disable' ); ?></button> |
		<button type="button" class="button-link" id="wordpress_disable_deselect_all"><?php _e( 'Deselect all', 'wp-widget-disable' ); ?></button>
		</p>
		<div class="form-group">
			<input type="hidden" name="action" value="disable_wordpress_widgets">
			<input type="hidden" name="_nonce" value="<?php echo wp_create_nonce('toolkit-elementor'); ?>"/>
			<button type="button" class="button toolkit-btn" id="disable-wordpress-widgets"><?php _e('Disable Selected Widgets'); ?></button>
		</div>
		</form>
	<?php	
	}
	
	function render_dashboard_widgets() {
		echo '<form id="dashboard_widgets_ds" action="">';
		$widgets = (array) toolkit_get_default_dashboard_widgets();
		$flat_widgets = [];
		
		foreach ( $widgets as $context => $priority ) {
			foreach ( $priority as $data ) {
				foreach ( $data as $id => $widget ) {
					if ( ! $widget ) {
						continue;
					}

					$widget['title']          = isset( $widget['title'] ) ? $widget['title'] : '';
					$widget['title_stripped'] = wp_strip_all_tags( $widget['title'] );
					$widget['context']        = $context;
					$flat_widgets[ $id ] = $widget;
				}
			}
		}
		$widgets = wp_list_sort( $flat_widgets, [ 'title_stripped' => 'ASC' ], null, true );
		if ( ! $widgets ) {
			printf(
				'<p>%s</p>',
				__( 'Oops, we could not retrieve your dashboard widgets! This normally occurs when there is another plugin managing your widgets.', 'wp-widget-disable' )
				);
			return;
		}
		$options    = get_disabled_dashboard_widgets();
		$wp_version = get_bloginfo( 'version' );
		?>
			<p>
				<input type="checkbox" id="dashboard_browser_nag" name="toolkit_wp_widget_disable_dashboard[dashboard_browser_nag]" value="normal"
				<?php checked( 'dashboard_browser_nag', ( array_key_exists( 'dashboard_browser_nag', $options ) ? 'dashboard_browser_nag' : false ) ); ?>>
				<label for="dashboard_browser_nag">
				<?php
				/* translators: %s: dashboard_browser_nag */
				printf( __( 'WP Admin Nag Messages (%s)', 'wp-widget-disable' ), '<code>dashboard_browser_nag</code>' );
				?>
				</label>
			</p>
		<?php
		if ( version_compare( $wp_version, '5.1.0', '>=' ) ) :
		?>
		<p>
			<input type="checkbox" id="dashboard_php_nag" name="toolkit_wp_widget_disable_dashboard[dashboard_php_nag]" value="normal"
			<?php checked( 'dashboard_php_nag', ( array_key_exists( 'dashboard_php_nag', $options ) ? 'dashboard_php_nag' : false ) ); ?>>
			<label for="dashboard_php_nag">
			<?php
				/* translators: %s: dashboard_php_nag */
			printf( __( 'PHP Update Messages (%s)', 'wp-widget-disable' ), '<code>dashboard_php_nag</code>' );
			?>
			</label>
		</p>
		<?php
		endif;

		foreach ( $widgets as $id => $widget ) {
			if ( empty( $widget['title'] ) ) {
				printf(
					'<p><input type="checkbox" id="%1$s" name="%2$s" value="%3$s" %4$s> <label for="%1$s">%5$s</label></p>',
					esc_attr( $id ),
					esc_attr( 'toolkit_wp_widget_disable_dashboard' ) . '[' . esc_attr( $id ) . ']',
					esc_attr( $widget['context'] ),
					checked( array_key_exists( $id, $options ), true, false ),
					'<code>' . esc_html( $id ) . '</code>'
				);

				continue;
			}

			printf(
				'<p><input type="checkbox" id="%1$s" name="%2$s" value="%3$s" %4$s> <label for="%1$s">%5$s</label></p>',
				esc_attr( $id ),
				esc_attr( 'toolkit_wp_widget_disable_dashboard' ) . '[' . esc_attr( $id ) . ']',
				esc_attr( $widget['context'] ),
				checked( array_key_exists( $id, $options ), true, false ),
				sprintf(
					/* translators: 1: widget name, 2: widget ID */
					_x( '%1$s (%2$s)', 'dashboard widget', 'wp-widget-disable' ),
					wp_kses( $widget['title'], [ 'span' => [ 'class' => true ] ] ),
					'<code>' . esc_html( $id ) . '</code>'
				)
			);
		}
		?>
		<p>
			<button type="button" class="button-link" id="dashboard_disable_select_all"><?php _e( 'Select all', 'wp-widget-disable' ); ?></button> |
			<button type="button" class="button-link" id="dashboard_disable_deselect_all"><?php _e( 'Deselect all', 'wp-widget-disable' ); ?></button>
		</p>
		<div class="form-group">
			<input type="hidden" name="action" value="dashboard_widgets_toolkit_disable">
			<input type="hidden" name="_nonce" value="<?php echo wp_create_nonce('toolkit-elementor'); ?>"/>
			<button type="button" class="button toolkit-btn" id="disable-dashboard-widgets"><?php _e('Disable Selected Widgets'); ?></button>
		</div>
	</form>
	<?php
	}
	
	?>
	<div class="row" style="display: block;">
		<div class="col-md-12">
			<div class="widgets-tab-warpper">
				<input class="widgets-tab-radio" id="one" name="group" type="radio" checked>
				<input class="widgets-tab-radio" id="two" name="group" type="radio">
				<input class="widgets-tab-radio" id="three" name="group" type="radio">
				<div class="widget-tabs">
					<label class="widget-tab" id="elementor-tab" for="one">Elementor Widgets</label>
					<label class="widget-tab" id="wordpress-tab" for="two">WP Widgets</label>
					<label class="widget-tab" id="dashboard-tab" for="three">WP Dashboard Widgets</label>
				</div>
				<div class="widgets-tab-panels">				
					<div class="widget-panel" id="elementor-panel">					
						<div class="widget-panel-title">Dequeue/Hide Elementor Widgets</div>
						<?php
						echo render_elementor_widgets(); 
						?>
					</div>					
				</div>
				<div class="widgets-tab-panels">
					<div class="widget-panel" id="wordpress-panel">
						<div class="widget-panel-title">Dequeue/Hide WordPress Widgets</div>
						<?php
						echo render_wordpress_widgets();
						?>
					</div>					
				</div>
				<div class="widgets-tab-panels">
					<div class="widget-panel" id="dashboard-panel">
						<div class="widget-panel-title">Dequeue/Hide Admin Dashboard Widgets</div>					
						<?php
						echo render_dashboard_widgets();
						?>
					</div>	
				</div>		
			</div>
		</div>
	</div>
<br/>
<script type="text/javascript">
		jQuery( document ).ready( function( $ ) {
			$( '#dashboard_disable_select_all, #dashboard_disable_deselect_all' ).click( function() {
				var isChecked = 'dashboard_disable_select_all' === $( this ).attr( 'id' );
				$( this ).parents( '#dashboard-panel' ).find( 'input' ).each( function() {
					$( this ).get( 0 ).checked = isChecked;
				} );
			} );
			$( '#wordpress_disable_select_all, #wordpress_disable_deselect_all' ).click( function() {
				var isChecked = 'wordpress_disable_select_all' === $( this ).attr( 'id' );
				$( this ).parents( '#wordpress-panel' ).find( 'input' ).each( function() {
					$( this ).get( 0 ).checked = isChecked;
				} );
			} );
			$( '#elementor_disable_select_all, #elementor_disable_deselect_all' ).click( function() {
				var isChecked = 'elementor_disable_select_all' === $( this ).attr( 'id' );
				$( this ).parents( '#elementor-panel' ).find( 'input' ).each( function() {
					$( this ).get( 0 ).checked = isChecked;
				} );
			} );			
		} );
</script>
