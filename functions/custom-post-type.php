<?php
/* joints Custom Post Type Example
This page walks you through creating
a custom post type and taxonomies. You
can edit this one or copy the following code
to create another one.

I put this in a separate file so as to
keep it organized. I find it easier to edit
and change things if they are concentrated
in their own file.

*/

// Arlo
require_once(get_template_directory().'/functions/post-types/arlo-events.php');
require_once(get_template_directory().'/functions/post-types/arlo-presenters.php');
require_once(get_template_directory().'/functions/post-types/arlo-venues.php');

// Clinics
require_once(get_template_directory().'/functions/post-types/clinics.php');

// Jobs
require_once(get_template_directory().'/functions/post-types/jobs.php');

// Slider
require_once(get_template_directory().'/functions/post-types/slider.php');

// Testimonials
require_once(get_template_directory().'/functions/post-types/testimonials.php');
