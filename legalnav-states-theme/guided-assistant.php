<?php

/**
 * Template Name: Guided Assistant
 */

get_template_part("/template-parts/guided-assistant-functions");
require_once get_template_directory() . '/classes/ga-search.php';

get_header();

$user_state_id = $_SESSION['user_state_id'];
// Get state term
$user_state_term = get_term_by('term_id', $user_state_id, 'states');
// Get current state name
$user_state_name = $user_state_term->name;

$ga_search_string = $_REQUEST['gaSearchString']; 

?>

<main class="site-main">

    <?php if ($ga_search_string) {
        $ga_search = new GASearch($ga_search_string); ?>

        <div class="ga-posts">

            <h2 class="ga-searched-for">
                
                You searched for: <span><?php echo stripslashes($ga_search_string); ?></span>
            </h2>

            <?php 
                $ga_search->getResults();

                $spot_query_id = $ga_search->getSpotSearchID();
                $spot_topics_ids_str = implode(',',array_map(function($topic) {return $topic->id;}, $ga_search->getSpotTopics()));
            ?>
            <div class="spot-query-id" style="display: none;" data-query-id="<?php echo $spot_query_id; ?>"></div>
            <div class="spot-data" style="display: none;" data-spot-ids="<?php echo $spot_topics_ids_str; ?>"></div>
        </div>

    <?php } ?>

    <?php
        $topics_heading_text = ($ga_search->hasResults || !$ga_search_string) ? "Or Browse Available Guided Assistants" : "Browse Available Guided Assistants";
    ?>

    <?php if(isset($user_state_id) && $ga_search_string) { ?>
        <div class="container">
            <?php render_ga_topics($user_state_id, $topics_heading_text); ?>
        </div>
    <?php } ?>

    <div class="container text-center">
        <div class="ga-container">
            <div class="ga-container-left">
                <? $hasPrevSearch = $ga_search_string; ?>
                <h2><?php echo ($hasPrevSearch) ? "Or try another search" : "Tell us what's going on" ?></h2>
                <div class="ga-intro-text">
                    <?= ($hasPrevSearch) ? get_field('ga_intro_alternate_text', 'options') : get_field('ga_intro_text', 'options') ?>
                </div>
            </div>
            <div class="ga-container-right" style="background-image: url(<?= get_field('ga_intro_image', 'options'); ?>)"></div>
            <div class="clear"></div>
            <form aria-label="search box" id="ga-form" class="">
                <input type="text" form="ga-form" _ngcontent-c9="" aria-label="Search Box" autocomplete="off" class="form-control ng-untouched ng-pristine ng-valid" name="gaSearchString" placeholder="Keyword or phrase" required></textarea>
                <button aria-label="search button" class="btn btn-primary" id="continue-guided-assistant" type="submit"> Continue </button>
                <p class="fine-print">We use the SPOT legal problem spotter to help find the best legal resources for you. <a href="/">Learn more.</a></p>
                <input type="hidden" name="location" id="searchform-location" value="<?php echo $user_state_name; ?>">
            </form>
        </div>
    </div>

    <?php if(isset($user_state_id) && !$ga_search_string) { ?>
        <div class="container">
            <?php render_ga_topics($user_state_id, $topics_heading_text); ?>
        </div>
    <?php } ?>

    <?php if (isset($user_state_id)) { ?>
        <?php if($ga_search_string) { ?>
            <div class="related-topics"></div>
        <?php } ?>
    <?php }

    if (have_posts()) {
        // Load posts loop.
        while (have_posts()) {
            the_post();
            the_content();
        }
    } ?>
    
</main>

<?php get_footer(); ?>