<div id="<?php echo uniqid('player_'); ?>" class="jp-container jp-<?php echo $GLOBALS['file']['type']; ?>">
	<div class="jp-source">
		<?php foreach($GLOBALS['file']['url'] as $url) { ?>
		<a href="<?php echo $url; ?>"></a>
		<?php } ?>
	</div>
	<div class="jp-type-single">
		<div class="jp-jplayer-wrap">
			<div class="jp-jplayer"></div>
			<div class="jp-video-play">
				<a href="javascript:;" class="jp-video-play-icon" tabindex="1" title="<?php _e('Play', 'academy'); ?>"></a>
			</div>			
		</div>							
		<div class="jp-gui">								
			<div class="jp-interface">
				<div class="jp-controls">
					<a href="javascript:;" class="jp-play" tabindex="1" title="<?php _e('Play', 'academy'); ?>"></a>
					<a href="javascript:;" class="jp-pause" tabindex="1" title="<?php _e('Pause', 'academy'); ?>"></a>								
				</div>
				<div class="jp-timeline">
					<div class="jp-title"><?php echo $GLOBALS['file']['title']; ?></div>
					<div class="jp-progress">
						<div class="jp-seek-bar">
							<div class="jp-play-bar"></div>
						</div>
					</div>							
				</div>						
				<div class="jp-volume">
					<div class="jp-time-holder clearfix">
						<?php if($GLOBALS['file']['type']=='video') { ?>
						<a href="javascript:;" class="jp-full-screen jp-screen-option" tabindex="1" title="<?php _e('Full screen', 'academy'); ?>"></a>
						<a href="javascript:;" class="jp-restore-screen jp-screen-option" tabindex="1" title="<?php _e('Exit full screen', 'academy'); ?>"></a>
						<?php } ?>
						<div class="jp-time right">
							<div class="jp-current-time"></div>&nbsp;/&nbsp;<div class="jp-duration"></div>
						</div>						
					</div>
					<div class="clear"></div>
					<a href="javascript:;" class="jp-mute" tabindex="1" title="<?php _e('Mute', 'academy'); ?>"></a>
					<a href="javascript:;" class="jp-unmute" tabindex="1" title="<?php _e('Unmute', 'academy'); ?>"></a>
					<div class="jp-volume-bar">
						<div class="jp-volume-bar-value"></div>
					</div>					
				</div>																		
			</div>
		</div>
		<div class="jp-no-solution"></div>
	</div>
</div>