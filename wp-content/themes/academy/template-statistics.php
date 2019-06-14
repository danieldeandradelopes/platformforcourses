<div id="themex-statistics" class="themex-statistics wrap">
	<div id="icon-edit" class="icon32"><br></div><h2><?php _e('Statistics', 'academy'); ?></h2>
	<div id="poststuff">		
		<div id="post-body" class="columns-2">
			<div id="post-body-content">
				<table class="widefat spaced">
					<?php if(ThemexCourse::$data['statistics']['user']['ID']!=0 || ThemexCourse::$data['statistics']['course']['ID']!=0) { ?>
					<thead>
						<tr>
							<?php if(ThemexCourse::$data['statistics']['user']['ID']==0) { ?>
							<th><?php _e('Username', 'academy'); ?></th>
							<?php } ?>
							<?php if(ThemexCourse::$data['statistics']['course']['ID']==0) { ?>
							<th class="total_row"><?php _e('Course', 'academy'); ?></th>
							<?php } ?>
							<th class="total_row"><?php _e('Lesson', 'academy'); ?></th>
							<th class="total_row"><?php _e('Grade', 'academy'); ?></th>
						</tr>
					</thead>
					<tfoot>
						<?php foreach(ThemexCourse::$data['statistics']['lessons'] as $lesson) { ?>
						<tr>
							<?php if(ThemexCourse::$data['statistics']['user']['ID']==0) { ?>
							<td><?php echo $lesson['username']; ?></td>
							<?php } ?>
							<?php if(ThemexCourse::$data['statistics']['course']['ID']==0) { ?>
							<td class="total_row"><?php echo $lesson['course']; ?></td>
							<?php } ?>
							<td class="total_row"><?php echo $lesson['lesson']; ?></td>
							<td class="total_row"><?php echo $lesson['grade']; ?>%</td>
						</tr>
						<?php } ?>
						<tr>
							<?php if(ThemexCourse::$data['statistics']['user']['ID']==0) { ?>
							<th><?php _e('Username', 'academy'); ?></th>
							<?php } ?>
							<?php if(ThemexCourse::$data['statistics']['course']['ID']==0) { ?>
							<th class="total_row"><?php _e('Course', 'academy'); ?></th>
							<?php } ?>
							<th class="total_row"><?php _e('Lesson', 'academy'); ?></th>
							<th class="total_row"><?php _e('Grade', 'academy'); ?></th>
						</tr>
					</tfoot>
					<?php } else { ?>
					<thead>
						<tr>
							<th><?php _e('Username', 'academy'); ?></th>
							<th class="total_row"><?php _e('Active Courses', 'academy'); ?></th>
							<th class="total_row"><?php _e('Completed Courses', 'academy'); ?></th>
							<th class="total_row"><?php _e('Average Grade', 'academy'); ?></th>
						</tr>
					</thead>
					<tfoot>
						<?php foreach(ThemexCourse::$data['statistics']['users'] as $user) { ?>
						<tr>
							<td><?php echo $user['username']; ?></td>
							<td class="total_row"><?php echo $user['active']; ?></td>
							<td class="total_row"><?php echo $user['completed']; ?></td>
							<td class="total_row"><?php echo $user['grade']; ?>&#37;</td>
						</tr>
						<?php } ?>
						<tr>
							<th><?php _e('Username', 'academy'); ?></th>
							<th class="total_row"><?php _e('Active Courses', 'academy'); ?></th>
							<th class="total_row"><?php _e('Completed Courses', 'academy'); ?></th>
							<th class="total_row"><?php _e('Average Grade', 'academy'); ?></th>
						</tr>
					</tfoot>
					<?php } ?>					
				</table>
			</div>
			<div id="postbox-container-1" class="postbox-container">
				<div id="postimagediv" class="postbox">
					<h3 class="normal"><?php _e('Filter','academy'); ?></h3>
					<div class="inside noborder">
						<form action="<?php echo themex_url(); ?>" method="GET">
							<p>
							<?php
							wp_dropdown_users(array(
								'show_option_all'=>__('All Students', 'academy'),
								'selected' => ThemexCourse::$data['statistics']['user']['ID'],
								'class'=>'widefat themex-submit-select',
							)); 
							?>
							</p>
							<p>
							<?php
							themex_dropdown_posts(array(
								'post_type' => 'course',
								'show_option_all'=>__('All Courses', 'academy'),
								'selected' => ThemexCourse::$data['statistics']['course']['ID'],
								'name' => 'course',
								'class'=>'widefat themex-submit-select',								
							));
							?>
							</p>
							<input type="hidden" name="post_type" value="course" />
							<input type="hidden" name="page" value="statistics" />
						</form>
					</div>
				</div>
				<div id="postimagediv" class="postbox">
					<h3 class="normal"><?php _e('Courses','academy'); ?></h3>
					<div class="inside noborder">
						<div class="misc-pub-section">
							<strong class="alignleft"><?php _e('Total', 'academy'); ?></strong>
							<span class="alignright"><?php echo ThemexCourse::$data['statistics']['course']['total']; ?></span>
							<div class="clear"></div>
						</div>
						<div class="misc-pub-section">
							<strong class="alignleft"><?php _e('Completed', 'academy'); ?></strong>
							<span class="alignright"><?php echo ThemexCourse::$data['statistics']['course']['completed']; ?></span>
							<div class="clear"></div>
						</div>
						<div class="misc-pub-section">
							<strong class="alignleft"><?php _e('Per User', 'academy'); ?></strong>
							<span class="alignright"><?php echo ThemexCourse::$data['statistics']['course']['average']; ?></span>
							<div class="clear"></div>
						</div>
					</div>
				</div>
				<div id="postimagediv" class="postbox">
					<h3 class="normal"><?php _e('Students','academy'); ?></h3>
					<div class="inside noborder">
						<div class="misc-pub-section">
							<strong class="alignleft"><?php _e('Total', 'academy'); ?></strong>
							<span class="alignright"><?php echo ThemexCourse::$data['statistics']['user']['total']; ?></span>
							<div class="clear"></div>
						</div>
						<div class="misc-pub-section">
							<strong class="alignleft"><?php _e('Active', 'academy'); ?></strong>
							<span class="alignright"><?php echo ThemexCourse::$data['statistics']['user']['active']; ?></span>
							<div class="clear"></div>
						</div>
						<div class="misc-pub-section">
							<strong class="alignleft"><?php _e('Grade', 'academy'); ?></strong>
							<span class="alignright"><?php echo ThemexCourse::$data['statistics']['user']['grade']; ?>%</span>
							<div class="clear"></div>
						</div>
					</div>
				</div>
				<div id="postimagediv" class="postbox">
					<h3 class="normal"><?php _e('Options','academy'); ?></h3>
					<div class="inside noborder">
						<div class="misc-pub-section">
							<form action="<?php echo themex_url(); ?>" method="GET" target="_blank">
								<a class="button themex-refresh-button" href="#"><?php _e('Update', 'academy'); ?></a>&nbsp;&nbsp;
								<a class="button themex-submit-button" href="#"><?php _e('Export', 'academy'); ?></a>
								<input type="hidden" name="export" value="1" />
								<input type="hidden" name="user" value="<?php echo ThemexCourse::$data['statistics']['user']['ID']; ?>" />
								<input type="hidden" name="course" value="<?php echo ThemexCourse::$data['statistics']['course']['ID']; ?>" />
								<input type="hidden" name="post_type" value="course" />
								<input type="hidden" name="page" value="statistics" />
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>		
	</div>	
</div>