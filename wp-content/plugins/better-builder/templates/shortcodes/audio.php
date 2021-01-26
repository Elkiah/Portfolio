<?php

wp_enqueue_script( 'amplitude' );

$songs = array();
$tracks = array();

if ( ! empty( $track ) ) {
	$tracks[] = $track;
} else if ( ! empty( 	$album ) ) {
	if ( strpos( $album, ',' ) !== false ) {
		$album = explode( ',', $album );
	}
	$tracks = BetterCore()->custom_post_types->get_term_posts( 'better_audio', 'album', $album );
}

foreach ( $tracks as $key => $track ) {
	$albums = BetterCore()->custom_post_types->get_terms_list( 'album', $track );
	$genres = BetterCore()->custom_post_types->get_terms_list( 'genre', $track );
	$artists = BetterCore()->custom_post_types->get_terms_list( 'artist', $track );
	$file = tr_posts_field("file", $track);
	$duration = explode( ':', tr_posts_field("duration", $track) );
	$live = tr_posts_field('live_audio', $track);

	echo $live;

	if ( is_numeric( $file ) ) {
		$file = BetterCore()->get_file_src( $file );
	} else {
		$file = tr_posts_field('file_url', $track);
	}

	$cover_art = get_the_post_thumbnail_url( $track );

	$songs[] = array(
		'name' => get_the_title( $track ),
		'artist' => implode( ',', $artists ),
		'album' => implode( ',', $albums ),
		'url' => $file,
		'cover_art_url' => $cover_art,
		'genre' => implode( ',', $genres ),
		'minutes' => $duration[0],
		'seconds' => $duration[1],
		//'live' => $live ? 'true' : 'false'
	);
}

if ( empty( $player ) ) {
	$player = 'single';
}

?>

<div <?php echo $id_attr; ?> class="better-audio <?php echo $class; ?>" <?php BetterCore()->do_style( $styles );?>>

<?php if ( $player == 'single' ) : wp_enqueue_style( 'amplitude-single-song' ); ?>

	<div id="single-song-player">
      <img amplitude-song-info="cover_art_url" amplitude-main-song-info="true"/>
      <div class="bottom-container">
        <progress class="amplitude-song-played-progress" amplitude-main-song-played-progress="true" id="song-played-progress"></progress>

        <div class="time-container">
          <span class="current-time">
            <span class="amplitude-current-minutes" amplitude-main-current-minutes="true"></span>:<span class="amplitude-current-seconds" amplitude-main-current-seconds="true"></span>
          </span>
          <span class="duration">
            <span class="amplitude-duration-minutes" amplitude-main-duration-minutes="true"></span>:<span class="amplitude-duration-seconds" amplitude-main-duration-seconds="true"></span>
          </span>
        </div>

        <div class="control-container">
          <div class="amplitude-play-pause" amplitude-main-play-pause="true" id="play-pause"></div>
          <div class="meta-container">
            <span amplitude-song-info="name" amplitude-main-song-info="true" class="song-name"></span>
            <span amplitude-song-info="artist" amplitude-main-song-info="true"></span>
          </div>
        </div>
      </div>
    </div>

    <script>
    	window.onkeydown = function(e) {
          return !(e.keyCode == 32);
			};

			/*
			Handles a click on the song played progress bar.
			*/
			document.getElementById('song-played-progress').addEventListener('click', function( e ){
				var offset = this.getBoundingClientRect();
				var x = e.pageX - offset.left;

				Amplitude.setSongPlayedPercentage( ( parseFloat( x ) / parseFloat( this.offsetWidth) ) * 100 );
			});
    </script>

<?php elseif ( $player == 'multiple' ) : wp_enqueue_style( 'amplitude-multiple-songs' ); ?>

	<?php foreach ( $songs as $key => $song ) { ?>

		<div class="player">
	      <img src="<?php echo $song['cover_art_url']; ?>" class="album-art"/>
	      <div class="meta-container">
	        <div class="song-title"><?php echo $song['name']; ?></div>
	        <div class="song-artist"><?php echo $song['artist']; ?></div>

	        <div class="time-container">
	          <div class="current-time">
	            <span class="amplitude-current-minutes" amplitude-song-index="<?php echo $key; ?>"></span>:<span class="amplitude-current-seconds" amplitude-song-index="<?php echo $key; ?>"></span>
	          </div>

	          <div class="duration">
	            <span class="amplitude-duration-minutes" amplitude-song-index="<?php echo $key; ?>">3</span>:<span class="amplitude-duration-seconds" amplitude-song-index="<?php echo $key; ?>">30</span>
	          </div>
	        </div>
	        <progress class="amplitude-song-played-progress" amplitude-song-index="<?php echo $key; ?>" id="song-played-progress-<?php echo $key + 1; ?>"></progress>
	        <div class="control-container">
	          <div class="amplitude-prev">

	          </div>
	          <div class="amplitude-play-pause" amplitude-song-index="<?php echo $key; ?>">

	          </div>
	          <div class="amplitude-next">

	          </div>
	        </div>
	      </div>
	    </div>

	    <script>
	    	document.getElementById('song-played-progress-<?php echo $key + 1; ?>').addEventListener('click', function( e ){
	        if( Amplitude.getActiveIndex() == 0 ){
	          var offset = this.getBoundingClientRect();
	          var x = e.pageX - offset.left;

	          Amplitude.setSongPlayedPercentage( ( parseFloat( x ) / parseFloat( this.offsetWidth) ) * 100 );
	        }
	      });
    	</script>

	<?php } ?>

<?php elseif( $player == 'flat-black' ) : wp_enqueue_style( 'amplitude-flat-black' ); ?>

	<div id="flat-black-player-container">
        <div id="list-screen" class="slide-in-top">

          <div id="list-screen-header" class="hide-playlist">
            <img id="up-arrow" src="<?php echo BetterCore()->assets_url . 'shortcodes/audio/flat-black'; ?>/img/up.svg"/>
            Hide Playlist
          </div>

          <div id="list">

			<?php foreach ( $songs as $key => $song ) { ?>

            <div class="song amplitude-song-container amplitude-play-pause" amplitude-song-index="<?php echo $key; ?>">
              <span class="song-number-now-playing">
                <span class="number"><?php echo $key + 1; ?></span>
                <img class="now-playing" src="<?php echo BetterCore()->assets_url . 'shortcodes/audio/flat-black'; ?>/img/now-playing.svg"/>
              </span>

              <div class="song-meta-container">
              	<span class="song-name"><?php echo $song['name']; ?></span>
        		<span class="song-artist-album"><?php echo $song['artist']; ?></span>
              </div>

              <span class="song-duration">
                <span class="amplitude-duration-minutes" amplitude-song-index="<?php echo $key; ?>"><?php echo $song['minutes']; ?></span>:<span class="amplitude-duration-seconds" amplitude-song-index="<?php echo $key; ?>"><?php echo $song['seconds']; ?></span>
              <span>
            </div>

            <?php } ?>

          </div>

          <div id="list-screen-footer">
            <div id="list-screen-meta-container">
              <span amplitude-song-info="name" amplitude-main-song-info="true" class="song-name"></span>

              <div class="song-artist-album">
                <span amplitude-song-info="artist" amplitude-main-song-info="true"></span>
              </div>
            </div>
            <div class="list-controls">
              <div class="list-previous amplitude-prev"></div>
              <div class="list-play-pause amplitude-play-pause" amplitude-main-play-pause="true"></div>
              <div class="list-next amplitude-next"></div>
            </div>
          </div>
        </div>
        <div id="player-screen">
          <div class="player-header down-header">
            <img id="down" src="<?php echo BetterCore()->assets_url . 'shortcodes/audio/flat-black'; ?>/img/down.svg"/>
            Show Playlist
          </div>
          <div id="player-top">
            <img amplitude-song-info="cover_art_url" amplitude-main-song-info="true"/>
          </div>
          <div id="player-progress-bar-container">
            <progress id="song-played-progress" class="amplitude-song-played-progress" amplitude-main-song-played-progress="true"></progress>
            <progress id="song-buffered-progress" class="amplitude-buffered-progress" value="0"></progress>
          </div>
          <div id="player-middle">
            <div id="time-container">
              <span class="amplitude-current-time time-container" amplitude-main-current-time="true"></span>
              <span class="amplitude-duration-time time-container" amplitude-main-duration-time="true"></span>
            </div>
            <div id="meta-container">
              <span amplitude-song-info="name" amplitude-main-song-info="true" class="song-name"></span>

              <div class="song-artist-album">
                <span amplitude-song-info="artist" amplitude-main-song-info="true"></span>
              </div>
            </div>
          </div>
          <div id="player-bottom">
            <div id="control-container">

              <div id="shuffle-container">
                <div class="amplitude-shuffle amplitude-shuffle-off" id="shuffle"></div>
              </div>

              <div id="prev-container">
                <div class="amplitude-prev" id="previous"></div>
              </div>

              <div id="play-pause-container">
                <div class="amplitude-play-pause" amplitude-main-play-pause="true" id="play-pause"></div>
              </div>

              <div id="next-container">
                <div class="amplitude-next" id="next"></div>
              </div>

              <div id="repeat-container">
                <div class="amplitude-repeat" id="repeat"></div>
              </div>

            </div>

            <div id="volume-container">
              <img src="<?php echo BetterCore()->assets_url . 'shortcodes/audio/flat-black'; ?>/img/volume.svg"/><input type="range" class="amplitude-volume-slider" step=".1"/>
            </div>
          </div>
        </div>
    </div>

    <script>
      	(function($) {
	  		window.onkeydown = function(e) {
			    return !(e.keyCode == 32);
			};

			$(document).on('ready', function(){

			  /*
			    Handles a click on the down button to slide down the playlist.
			  */
			  $('.down-header').on('click', function(){
			    /*
			      Sets the list's height;
			    */
			    $('#list').css('height', ( parseInt( $('#flat-black-player-container').height() ) - 135 )+ 'px' );

			    /*
			      Slides down the playlist.
			    */
			    $('#list-screen').removeClass("slideup").addClass("slidedown");
			  });

			  /*
			    Handles a click on the up arrow to hide the list screen.
			  */
			  $('.hide-playlist').on('click', function(){
			    $('#list-screen').removeClass("slidedown").addClass("slideup");
			  });

			  /*
			    Handles a click on the song played progress bar.
			  */
			  document.getElementById('song-played-progress').addEventListener('click', function( e ){
			    var offset = this.getBoundingClientRect();
			    var x = e.pageX - offset.left;

			    Amplitude.setSongPlayedPercentage( ( parseFloat( x ) / parseFloat( this.offsetWidth) ) * 100 );
			  });

			  $('img[amplitude-song-info="cover_art_url"]').css('height', $('img[amplitude-song-info="cover_art_url"]').width() + 'px' );
			});

			$(window).on('resize', function(){
			  $('img[amplitude-song-info="cover_art_url"]').css('height', $('img[amplitude-song-info="cover_art_url"]').width() + 'px' );
			});
		})(jQuery);
    </script>

<?php elseif( $player == 'blue-playlist' ) :
	wp_enqueue_style( 'amplitude-blue-playlist' );
	wp_enqueue_style( 'foundation' );
	wp_enqueue_script( 'foundation' );
?>

	<div class="grid-x" id="blue-playlist-container">
		<div class="large-8 medium-10 small-11 large-centered medium-centered small-centered cell" id="amplitude-player">
			<div class="grid-x">
				<div class="large-6 medium-6 small-12 columns" id="amplitude-left">
					<img amplitude-song-info="cover_art_url" amplitude-main-song-info="true"/>
					<div id="player-left-bottom">
						<div id="time-container">
							<span class="current-time">
								<span class="amplitude-current-minutes" amplitude-main-current-minutes="true"></span>:<span class="amplitude-current-seconds" amplitude-main-current-seconds="true"></span>
							</span>

							<div id="progress-container">
					          	<input type="range" class="amplitude-song-slider" amplitude-main-song-slider="true"/>
					          	<progress id="song-played-progress" class="amplitude-song-played-progress" amplitude-main-song-played-progress="true"></progress>
					          	<progress id="song-buffered-progress" class="amplitude-buffered-progress" value="0"></progress>
					        </div>

							<span class="duration">
								<span class="amplitude-duration-minutes" amplitude-main-duration-minutes="true"></span>:<span class="amplitude-duration-seconds" amplitude-main-duration-seconds="true"></span>
							</span>
						</div>

						<div id="control-container">
							<div id="repeat-container">
					          <div class="amplitude-repeat" id="repeat"></div>
					          <div class="amplitude-shuffle amplitude-shuffle-off" id="shuffle"></div>
					        </div>

					        <div id="central-control-container">
					          <div id="central-controls">
					            <div class="amplitude-prev" id="previous"></div>
					            <div class="amplitude-play-pause" amplitude-main-play-pause="true" id="play-pause"></div>
					            <div class="amplitude-next" id="next"></div>
					          </div>
					        </div>

					        <div id="volume-container">
					          <div class="volume-controls">
					            <div class="amplitude-mute amplitude-not-muted"></div>
					            <input type="range" class="amplitude-volume-slider"/>
					            <div class="ms-range-fix"></div>
					          </div>
					          <div class="amplitude-shuffle amplitude-shuffle-off" id="shuffle-right"></div>
					        </div>
						</div>

						<div id="meta-container">
							<span amplitude-song-info="name" amplitude-main-song-info="true" class="song-name"></span>

							<div class="song-artist-album">
								<span amplitude-song-info="artist" amplitude-main-song-info="true"></span>
								<span amplitude-song-info="album" amplitude-main-song-info="true"></span>
							</div>
						</div>
					</div>
				</div>
				<div class="large-6 medium-6 small-12 cell" id="amplitude-right">

				<?php foreach ( $songs as $key => $song ) { ?>

					<div class="song amplitude-song-container amplitude-play-pause" amplitude-song-index="<?php echo $key; ?>">
						<div class="song-now-playing-icon-container">
							<div class="play-button-container">

							</div>
							<img class="now-playing" src="<?php echo BetterCore()->assets_url . 'shortcodes/audio/blue-playlist'; ?>/img/now-playing.svg"/>
						</div>
						<div class="song-meta-data">
							<span class="song-title"><?php echo $song['name']; ?></span>
        					<span class="song-artist"><?php echo $song['artist']; ?></span>
						</div>
						<!-- <a href="https://switchstancerecordings.bandcamp.com/track/risin-high-feat-raashan-ahmad" class="bandcamp-link" target="_blank">
							<img class="bandcamp-grey" src="<?php echo BetterCore()->assets_url . 'shortcodes/audio/blue-playlist'; ?>/img/bandcamp-grey.svg"/>
							<img class="bandcamp-white" src="<?php echo BetterCore()->assets_url . 'shortcodes/audio/blue-playlist'; ?>/img/bandcamp-white.svg"/>
						</a> -->
						<span class="song-duration">
							<span class="amplitude-duration-minutes" amplitude-song-index="<?php echo $key; ?>"><?php echo $song['minutes']; ?></span>:<span class="amplitude-duration-seconds" amplitude-song-index="<?php echo $key; ?>"><?php echo $song['seconds']; ?></span>
						</span>
					</div>

				<?php } ?>

				</div>
			</div>
		</div>
	</div>

	<script>
		/*
			Adjusts the height of the left and right side of the players to be the same.
		*/
		function adjustPlayerHeights(){
			if( Foundation.MediaQuery.atLeast('medium') ) {
				jQuery('#amplitude-right').css('max-height', jQuery('#amplitude-left').height()+'px');
			}else{
				jQuery('#amplitude-right').css('max-height', 'initial' );
			}
		}

		(function($) {
			/*
				Initializes the player
			*/
			$(window).load(function() {
				/*
					Initializes foundation for responsive design.
				*/
				$(document).foundation();

				/*
					When the window resizes, ensure the left and right side of the player
					are equal.
				*/
				$(window).on('resize', function(){
					adjustPlayerHeights();
				});

				/*
					When the bandcamp link is pressed, stop all propagation so AmplitudeJS doesn't
					play the song.
				*/
				$('.bandcamp-link').on('click', function( e ){

					e.stopPropagation();
				});

				/*
					Ensure that on mouseover, CSS styles don't get messed up for active songs.
				*/
				jQuery('.song').on('mouseover', function(){
					jQuery(this).css('background-color', '#00A0FF');
					jQuery(this).find('.song-meta-data .song-title').css('color', '#FFFFFF');
					jQuery(this).find('.song-meta-data .song-artist').css('color', '#FFFFFF');

					if( !jQuery(this).hasClass('amplitude-active-song-container') ){
						jQuery(this).find('.play-button-container').css('display', 'block');
					}

					jQuery(this).find('img.bandcamp-grey').css('display', 'none');
					jQuery(this).find('img.bandcamp-white').css('display', 'block');
					jQuery(this).find('.song-duration').css('color', '#FFFFFF');
				});

				/*
					Ensure that on mouseout, CSS styles don't get messed up for active songs.
				*/
				jQuery('.song').on('mouseout', function(){
					jQuery(this).css('background-color', '#FFFFFF');
					jQuery(this).find('.song-meta-data .song-title').css('color', '#272726');
					jQuery(this).find('.song-meta-data .song-artist').css('color', '#607D8B');
					jQuery(this).find('.play-button-container').css('display', 'none');
					jQuery(this).find('img.bandcamp-grey').css('display', 'block');
					jQuery(this).find('img.bandcamp-white').css('display', 'none');
					jQuery(this).find('.song-duration').css('color', '#607D8B');
				});

				/*
					Show and hide the play button container on the song when the song is clicked.
				*/
				jQuery('.song').on('click', function(){
					jQuery(this).find('.play-button-container').css('display', 'none');
				});

				/*
					Equalizes the player heights for left and right side of the player
				*/
				setTimeout(adjustPlayerHeights, 200);
			});


		})(jQuery);
	</script>

<?php endif; ?>

</div>
<script>
	(function($) {
		$(document).ready(function() {
			Amplitude.init({
				"songs": <?php echo json_encode( $songs ); ?>,
				"bindings": {
		          37: 'prev',
		          39: 'next',
		          32: 'play_pause'
		        },
		        "autoplay": <?php echo !empty( $autoplay ) ? 'true' : 'false'; ?>
			});
		});
	})(jQuery);
</script>
