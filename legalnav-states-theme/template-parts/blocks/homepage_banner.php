<?php
	$user_state_id = $_SESSION['user_state_id'];
	$user_state_term = get_term_by('term_id', $user_state_id, 'states');
	$user_state_name = $user_state_term->name;
?>

<?php
	if(isset($user_state_id)) { ?>
		<section class="homepage-banner-block">
			<div class="container">
				<div class="homepage-banner-left-content">
					<div class="homepage-banner-heading"><h1> Get help with your legal questions in <?php echo $user_state_name ?></h1></div>
					<div class="homepage-banner-spot-container">
						<h5>Tell us about your problem:</h5>
						<p>Type your story here. Don't include personal information like your name and address.	<span>Example: I want to get a divorce and I don't know how to start everything.</span></p>
						<form action="/guided-assistant/" id="homepage-form">
						    <textarea type="text" form="homepage-form" aria-label="Type your story here" autocomplete="off" class="homepage-spot-search" name="gaSearchString" placeholder="Type your story here" required></textarea>
						    <input aria-label="search button" class="btn btn-primary" type="submit" value="Continue">
						</form>
						<p class="fine-print">We use the SPOT legal problem spotter to help find the best legal resources for you. <a href="/">Learn more.</a></p>
					</div>	
					<div class="homepage-content">
						<?php echo get_field('homepage_content', $user_state_term); ?>
					</div>
				</div>
				<div class="homepage-banner-image" style="background-image: url('<?php echo get_field('homepage_image', $user_state_term); ?>')">
					<div class="homepage-banner-arrow" style="background-image: url('https://www.legalnav.org/wp-content/uploads/2021/01/arrow_down.svg')"></div>
				</div>
				<div class="clear"></div>
			</div>
		</section>
<?php } else { ?>
	<section class="homepage-banner-block">
		<div class="container">
			<div class="homepage-banner-left-content">
				<div class="homepage-banner-heading"><h1> Get help with your legal questions in</h1></div>
				<div class="homepage-content">
					<p>Select state to see relevant information for that location.</p>
					<form method="post">
						<select name="location_select" class="location-select-home">
							<option selected disabled>Select A State</option>
							<?php
								$terms = get_terms('states');
								foreach($terms as $term) { ?>
									<option name=<?php echo $term->name; ?>><?php echo $term->name; ?></option>
								<?php }
							?>
						</select>

						<button class="btn btn-primary location-btn" type="submit" name="location_update">Apply</button>
					</form>
					<p>We do not store your location information. View our <a href="/privacy-policy">Privacy Notice</a>.</p>
				</div>
			</div>
			<div class="homepage-banner-image" style="background-image: url('https://www.legalnav.org/wp-content/uploads/2020/12/southwest.svg')">
				<div class="homepage-banner-arrow" style="background-image: url('https://www.legalnav.org/wp-content/uploads/2021/01/arrow_down.svg')"></div>
			</div>
			<div class="clear"></div>

		</div>
	</section>
<?php } ?>
