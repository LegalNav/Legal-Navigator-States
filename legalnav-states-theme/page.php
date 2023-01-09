<?php
/**
 * Default Template
 */

	get_header();
	$user_state_id = $_SESSION['user_state_id'];
?>

<main class="site-main">
<?php
    if(isset($user_state_id) || !is_front_page() ) {
		if ( have_posts() ) {
			// Load posts loop.
			while ( have_posts() ) {
				the_post(); ?>
				<?php if(!is_front_page()) { ?>
					<div class="container">
				<?php } ?>
				<div class="content-wrapper">
					<?php if(!empty(get_field('icon'))) { ?>
						<div class="container">
							<div class="content-heading-wrapper">
								<?php echo @file_get_contents(get_field('icon', get_the_ID() )); ?>
								<h1>
									<?php the_title(); ?>
								</h1>
							</div>
						</div>
					<?php } ?>
					<?php the_content(); ?>
				</div>
				<?php if(!is_front_page()) { ?>
					</div>
				<?php } ?>
				<?php
				// If the sidebar GA CTA is selected for page, render it
				if(get_field('show_sidebar_guided_assistant_callout')) { ?>
					<aside class="sidebar">
						<div class="side-ga-callout">
							<h4>Need help? Use our Guided Assistant to receive personalized recommendations.</h4>
							<p>No account necessary. We never share any information you provide. Read our <a href="/privacy-policy">Privacy Notice</a>.</p>
							<a href="/guided-assistant" class="btn btn-primary">Start the Guided Assistant</a>
						</div>
					</aside>
				<?php }
			}
		} else {
			// If no content, include the "No posts found" template
			echo "Sorry, no posts found.";
		}
	} else {
		// Below is what renders when no state is selected
		?>
			<div class="content-wrapper">
				<?php the_content(); ?>
			</div>


		<?php
	}
?>

</main>

<?php get_footer();?>
