<?php
	$user_state_id = $_SESSION['user_state_id'];
	$user_state_term = get_term_by('term_id', $user_state_id, 'states');
	$state_feat_topics = get_field('homepage_feat_topics', $user_state_term);

	if($user_state_id && $state_feat_topics) { ?>
		<section class="topics-resource-block">
			<div class="container">
				<h2 class="topics-resource-heading"> Or Pick a Topic </h2>
				<div class="row">
					<?php foreach ($state_feat_topics as $topic) { ?>
						<a href="<?php echo get_term_link($topic->slug, 'topics') ?>" class="topic-card col-md-3 col-sm-4 col-xs-6">
							<div class="topic-icon" style="background-image: url('<?php echo get_field('icon', $topic) ?>')"></div>
							<p class="topic-name"><?php echo $topic->name ?></p>
						</a>
					<?php } ?>
				</div>
				<a class="topics-resource-link btn btn-primary" href="/topics-resources">See More Topics</a>
			</div>
		</section>
	<?php } else { ?>
		<section class="topics-resource-block">
			<div class="container">
			<h2 class="topics-resource-heading"> More Information, Videos, and Links to Resources by Topic </h2>
				<p class="topics-resource-message">Sorry, We did not find anything that matches your location. Please try again with selecting location on larger area</p>
				<a class="topics-resource-link btn btn-primary" href="/topics-resources">See More Topics</a>
			</div>
		</section>
	<?php }
?>




