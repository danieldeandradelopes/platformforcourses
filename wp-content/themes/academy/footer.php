				<?php if(is_active_sidebar('footer')) { ?>
					<div class="clear"></div>
					<div class="footer-sidebar sidebar clearfix">
						<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer')); ?>
					</div>
				<?php } ?>
				</div>
			</div>
			<!-- /content -->
			<div class="footer-wrap">
				<footer class="site-footer">
					<div class="row">
						<div class="copyright left">
							<?php echo ThemexCore::getOption('copyright', 'Academy Theme &copy; '.date('Y')); ?>
						</div>
						<nav class="footer-navigation right">
							<?php wp_nav_menu( array( 'theme_location' => 'footer_menu' ) ); ?>
						</nav>
						<!-- /navigation -->				
					</div>			
				</footer>				
			</div>
			<!-- /footer -->			
		</div>
		<!-- /site wrap -->
	<?php wp_footer(); ?>
	</body>
</html>