<?php
    /**
     * Template Name: Topics & Resources
     */

    get_header();
	$user_state_id = $_SESSION['user_state_id'];
?>

    <main class="site-main">


    <?php
        // This template loads a list of topics and their icons based on the users state
		if(!$user_state_id) { ?>
			<div class="container">
					<h5 class="error-text">Sorry, there are no topics available. Please select a different location.</h5>
			</div>
		<?php } else {
		
            // Get all topic terms
            $topics = get_terms('topics');
            // Filter only parent topics that are in the currently selected state
            $parent_topics = array();
			if(!empty($topics)){
				foreach($topics as $topic) {
					// If topic has hidden toggle, dont show it
					$isHidden = get_field('topic_hidden', $topic);
					$associated_states = get_field('associated_states', $topic);
					if(!$isHidden) {
						if(is_array($associated_states)){
							if(in_array($user_state_id, $associated_states)) {
								// If the topic is a parent topic add it to the list, else add its parent to the list
								if($topic->parent == 0) {
									$parent_topics[$topic->term_id] = $topic;
								} else {
									//$parent_topics[$topic->parent] = get_term($topic->parent);
								}
							}
						}
					}
				}
			}
            // Render parent topics and their icons
			?>
			<div class="container">
				<div class="row">


			<section class="topic-section col-md-8 col-sm-12 col-xs-12">
				<ul>
				<?php


            foreach($parent_topics as $topic) {

				$hierarchylist = array();
				if(!empty($topic)){
					if(!empty($topic->term_id)){
						$hierarchylist[] = $topic->term_id;
						$hierarchy = get_taxonomy_hierarchy( 'topics' , $topic->term_id );
						if(!empty($hierarchy)){
							foreach($hierarchy as $hierarchyVal){
								$hierarchylist[] = $hierarchyVal->term_id;
								if(!empty($hierarchyVal->children)){
									foreach($hierarchyVal->children as $hierarchychildrenVal){
										$hierarchylist[] = $hierarchychildrenVal->term_id;
									}
								}
							}
						}
					} else {
						$hierarchylist[] = $topics;
					}
				}
				$arr = array_unique($hierarchylist);
				$topics = implode(" ,",$arr);

				$args = array(
						'post_type' => array('ln_resource'),
						'posts_per_page' => -1,
						'post_status' => 'publish',
						'tax_query'=> array(
							array(
								'taxonomy' => 'topics',
								'field' => 'term_id',
								'terms' => $topics,
							),
							array(
								'taxonomy' => 'states',
								'field' => 'term_id',
								'terms' => $user_state_id,
							),
						),
					);
				$resources = new WP_Query($args);
				if($resources->have_posts()) {
					?>
					<li class="topic-card-container col-md-3 col-sm-4 col-xs-6">
						<a href="<?php echo get_term_link($topic->slug, 'topics') ?>" class="topic-card">
							<div class="topic-icon" style="background-image: url('<?php echo get_field('icon', $topic) ?>')"></div>
							<p class="topic-name"><?php echo $topic->name ?></p>
						</a>
					</li>
					<?php
				}
            }
			?>
			</ul>
		<?php } ?>
		</section>

		<?php
		$termData = get_term_by('id', $user_state_id, 'states');
		$sidebar_heading = get_field('sidebar_heading', $termData);
		$sidebar_text = get_field('sidebar_text', $termData);
		$sidebar_button_text = get_field('sidebar_button_text', $termData);
		$sidebar_link = get_field('sidebar_link', $termData);
		$sidebar_image = get_field('sidebar_image', $termData);


		?>

			<div class="col-md-4 col-sm-12 text-center pull-right sidebar-container">
				<div class="side-ga-callout">
                    <h5>Need help? Use our Guided Assistant to receive personalized recommendations.</h5>
                    <p>No account necessary. We never share any information you provide. Read our <a href="/privacy-policy">Privacy Notice</a>.</p>
                    <a href="/guided-assistant" class="btn btn-primary">Start the Guided Assistant</a>
                </div>
				<?php if (!empty($sidebar_heading)) { ?>
				<div class="sidebar-callout">

					<?php
					if(!empty($sidebar_heading)){
						?><h3><?php echo $sidebar_heading; ?></h3><?php
					}
					if(!empty($sidebar_image)){
						?><div class="topic-icon" style="background-image: url('<?php echo $sidebar_image; ?>')"></div><?php
					}
					if(!empty($sidebar_text)){
						?><h6><?php echo $sidebar_text; ?></h6><?php
					}
					if(!empty($sidebar_button_text)){
						?><a class="btn btn-primary" href="<?php echo $sidebar_link; ?>" target="_blank"><?php echo $sidebar_button_text; ?></a><?php
					}
					?>

				</div>
				<?php } ?>
			</div>

		</div>
		</div>
		<?php
    ?>
    </main>

<?php get_footer();?>
