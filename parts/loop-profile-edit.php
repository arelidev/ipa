<?php
/**
 * Template part for displaying page content in page.php
 */

$user = 'user_' . get_current_user_id();

$settings = [
	"post_id" => $user,
	'submit_value' => __("Update profile", 'acf'),
	'updated_message' => __("Profile updated", 'acf'),
	'html_updated_message' => '<div id="message" class="updated callout success"><p>%s</p></div>',
	'fields' => [
		'image',
		'bio',
		'credentials',
		'work_information',
		'social_profiles',
		'offices'
	],
];
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(''); ?> role="article" itemscope itemtype="http://schema.org/WebPage">

    <header class="article-header hero hero-standard">
        <div class="hero-inner grid-container">
            <h1 class="page-title"><b><?= __('Edit Profile', 'ipa'); ?></b></h1>
        </div>
    </header> <!-- end article header -->

    <section class="entry-content grid-container" itemprop="text">
        <div class="grid-x grid-padding-x grid-padding-y align-center">
            <div class="small-12 medium-12 large-12 cell">
				<?php acf_form( $settings ); ?>
            </div>
        </div>
    </section> <!-- end article section -->

</article> <!-- end article -->