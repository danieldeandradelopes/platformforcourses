<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>
	
	<!--[if lt IE 9]>
	<script type="text/javascript" src="<?php echo THEME_URI; ?>js/html5.js"></script>
	<![endif]-->
	
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<div class="site-wrap">
		<div class="header-wrap">
			<header class="site-header">
				<div class="row">
					<div class="site-logo left">
						<a href="<?php echo SITE_URL; ?>" rel="home">
							<img src="<?php echo ThemexCore::getOption('site_logo', THEME_URI.'images/logo.png'); ?>" alt="<?php bloginfo('name'); ?>" />
						</a>
					</div>
					<!-- /logo -->
					<div class="header-options right clearfix">					
						<div class="login-options right">
						<?php if(is_user_logged_in()) { ?>
							<div class="button-wrap left">
								<a href="<?php echo wp_logout_url(SITE_URL); ?>" class="button dark">
									<span class="button-icon logout"></span><?php _e('Sign Out','academy'); ?>
								</a>							
							</div>
							<div class="button-wrap left">
								<a href="<?php echo ThemexUser::$data['user']['profile_url']; ?>" class="button">
									<span class="button-icon register"></span><?php _e('My Profile','academy'); ?>
								</a>						
							</div>							
							<?php } else { ?>						
							<div class="button-wrap left tooltip login-button">
								<a href="#" class="button dark"><span class="button-icon login"></span><?php _e('Sign In','academy'); ?></a>
								<div class="tooltip-wrap">
									<div class="tooltip-text">
										<form action="<?php echo AJAX_URL; ?>" class="ajax-form popup-form" method="POST">
											<div class="message"></div>
											<div class="field-wrap">
												<input type="text" name="user_login" value="<?php _e('Username','academy'); ?>" />
											</div>
											<div class="field-wrap">
												<input type="password" name="user_password" value="<?php _e('Password','academy'); ?>" />
											</div>
											<div class="button-wrap left nomargin">
												<a href="#" class="button submit-button"><?php _e('Sign In','academy'); ?></a>
											</div>											
											<?php if(ThemexFacebook::isActive()) { ?>
											<div class="button-wrap left">
												<a href="<?php echo ThemexFacebook::getURL(); ?>" title="<?php _e('Sign in with Facebook','academy'); ?>" class="button facebook-button">
													<span class="button-icon facebook"></span>
												</a>
											</div>
											<?php } ?>
											<div class="button-wrap switch-button left">
												<a href="#" class="button dark" title="<?php _e('Password Recovery','academy'); ?>">
													<span class="button-icon help"></span>
												</a>
											</div>
											<input type="hidden" name="user_action" value="login_user" />
											<input type="hidden" name="user_redirect" value="<?php echo themex_value($_POST, 'user_redirect'); ?>" />
											<input type="hidden" name="nonce" class="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
											<input type="hidden" name="action" class="action" value="<?php echo THEMEX_PREFIX; ?>update_user" />
										</form>
									</div>
								</div>
								<div class="tooltip-wrap password-form">
									<div class="tooltip-text">
										<form action="<?php echo AJAX_URL; ?>" class="ajax-form popup-form" method="POST">
											<div class="message"></div>
											<div class="field-wrap">
												<input type="text" name="user_email" value="<?php _e('Email','academy'); ?>" />
											</div>
											<div class="button-wrap left nomargin">
												<a href="#" class="button submit-button"><?php _e('Reset Password','academy'); ?></a>
											</div>
											<input type="hidden" name="user_action" value="reset_password" />
											<input type="hidden" name="nonce" class="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
											<input type="hidden" name="action" class="action" value="<?php echo THEMEX_PREFIX; ?>update_user" />
										</form>
									</div>
								</div>
							</div>
							<?php if(get_option('users_can_register')) { ?>
							<div class="button-wrap left">
								<a href="<?php echo ThemexCore::getURL('register'); ?>" class="button">
									<span class="button-icon register"></span><?php _e('Register','academy'); ?>
								</a>
							</div>
							<?php } ?>
						<?php } ?>
						</div>
						<!-- /login options -->										
						<div class="search-form right">
							<?php get_search_form(); ?>
						</div>
						<!-- /search form -->
						<?php if($code=ThemexCore::getOption('sharing')) { ?>
						<div class="button-wrap share-button tooltip right">
							<a href="#" class="button dark"><span class="button-icon plus nomargin"></span></a>
							<div class="tooltip-wrap">
								<div class="corner"></div>
								<div class="tooltip-text"><?php echo themex_html($code); ?></div>
							</div>
						</div>
						<!-- /share button -->
						<?php } ?>
					</div>
					<!-- /header options -->
					<div class="mobile-search-form">
						<?php get_search_form(); ?>
					</div>
					<!-- /mobile search form -->
					<nav class="header-navigation right">
						<?php wp_nav_menu( array( 'theme_location' => 'main_menu', 'container_class' => 'menu' ) ); ?>						
						<div class="select-menu">
							<span></span>
							<?php ThemexInterface::renderDropdownMenu('main_menu'); ?>							
						</div>
						<!--/ select menu-->
					</nav>
					<!-- /navigation -->						
				</div>			
			</header>
			<!-- /header -->
		</div>
		<div class="featured-content">
			<div class="substrate">
				<?php ThemexStyle::renderBackground(); ?>
			</div>
			<?php if(is_front_page() && is_page()) { ?>
			<?php get_template_part('module', 'slider'); ?>
			<?php } else { ?>
			<div class="row">
			<?php if(is_singular('course')) { ?>
				<?php get_template_part('module', 'course'); ?>
			<?php } else { ?>
				<div class="page-title">
					<h1 class="nomargin"><?php ThemexInterface::renderPageTitle(); ?></h1>
				</div>
				<!-- /page title -->				
			<?php } ?>
			</div>
		<?php } ?>		
		</div>
		<!-- /featured -->
		<div class="main-content">
			<div class="row">