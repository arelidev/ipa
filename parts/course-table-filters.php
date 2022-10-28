<?php
$courses = $args['courses'] ?? [];

$presenters = new WP_Query(array(
	'post_type' => 'ipa_arlo_presenters',
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'order' => 'ASC',
	'orderby' => 'title'
));
?>
    <div class="search-bar styled-container courses-table-widget-filters">
        <div class="grid-container">
            <div class="grid-x grid-padding-x grid-padding-y align-middle">
                <div class="cell auto show-for-medium">
                    <b>Filter by:</b>
                </div>
                <div class="cell small-12 medium-auto">
                    <label>
                        <span class="hide-for-medium"><?= __('Course Type', 'ipa'); ?></span>
                        <select class="course-filter-type">
                            <option value="all">Course Type</option>
							<?php foreach ($courses as $title => $id) : ?>
                                <option value="course-<?= acf_slugify($title); ?>"><?= $title; ?></option>
							<?php endforeach; ?>
                        </select>
                    </label>
                </div>
                <div class="cell small-12 medium-auto">
                    <label>
                        <span class="hide-for-medium"><?= __('Select Faculty', 'ipa'); ?></span>
                        <select class="course-filter-select">
                            <option value="all">State</option>
                            <option value="all">All</option>
                            <option value=".AL">Alabama</option>
                            <option value=".AK">Alaska</option>
                            <option value=".AZ">Arizona</option>
                            <option value=".AR">Arkansas</option>
                            <option value=".CA">California</option>
                            <option value=".CO">Colorado</option>
                            <option value=".CT">Connecticut</option>
                            <option value=".DE">Delaware</option>
                            <option value=".DC">District Of Columbia</option>
                            <option value=".FL">Florida</option>
                            <option value=".GA">Georgia</option>
                            <option value=".HI">Hawaii</option>
                            <option value=".ID">Idaho</option>
                            <option value=".IL">Illinois</option>
                            <option value=".IN">Indiana</option>
                            <option value=".IA">Iowa</option>
                            <option value=".KS">Kansas</option>
                            <option value=".KY">Kentucky</option>
                            <option value=".LA">Louisiana</option>
                            <option value=".ME">Maine</option>
                            <option value=".MD">Maryland</option>
                            <option value=".MA">Massachusetts</option>
                            <option value=".MI">Michigan</option>
                            <option value=".MN">Minnesota</option>
                            <option value=".MS">Mississippi</option>
                            <option value=".MO">Missouri</option>
                            <option value=".MT">Montana</option>
                            <option value=".NE">Nebraska</option>
                            <option value=".NV">Nevada</option>
                            <option value=".NH">New Hampshire</option>
                            <option value=".NJ">New Jersey</option>
                            <option value=".NM">New Mexico</option>
                            <option value=".NY">New York</option>
                            <option value=".NC">North Carolina</option>
                            <option value=".ND">North Dakota</option>
                            <option value=".OH">Ohio</option>
                            <option value=".OK">Oklahoma</option>
                            <option value=".OR">Oregon</option>
                            <option value=".PA">Pennsylvania</option>
                            <option value=".RI">Rhode Island</option>
                            <option value=".SC">South Carolina</option>
                            <option value=".SD">South Dakota</option>
                            <option value=".TN">Tennessee</option>
                            <option value=".TX">Texas</option>
                            <option value=".UT">Utah</option>
                            <option value=".VT">Vermont</option>
                            <option value=".VA">Virginia</option>
                            <option value=".WA">Washington</option>
                            <option value=".WV">West Virginia</option>
                            <option value=".WI">Wisconsin</option>
                            <option value=".WY">Wyoming</option>
                        </select>
                    </label>
                </div>
                <div class="cell small-12 medium-auto">
                    <label>
                        <span class="hide-for-medium"><?= __('Select Region', 'ipa'); ?></span>
                        <select class="course-filter-region">
                            <option value="all">Region</option>
                            <option value="all">All</option>
                            <option value="midatlantic">Mid-Atlantic</option>
                            <option value="midwest">Mid-West</option>
                            <option value="northeast">Northeast</option>
                            <option value="northwest">Northwest</option>
                            <option value="southwest">Southwest</option>
                            <option value="southeast">Southeast</option>
                            <option value="-west">West</option>
                        </select>
                    </label>
                </div>
                <div class="cell small-12 medium-auto">
                    <label>
                        <span class="hide-for-medium"><?= __('Search by instructor', 'ipa'); ?></span>
                        <select class="clinics-filter-certification" id="course-filter-instructor">
                            <option value="all">Primary Instructor</option>
							<?php if ($presenters->have_posts()): ?>
								<?php while ($presenters->have_posts()): $presenters->the_post(); ?>
                                    <option value="<?= acf_slugify(get_the_title()) ?>"><?php the_title(); ?></option>
								<?php endwhile; ?>
							<?php endif; ?>
                        </select>
                    </label>
                </div>
                <div class="cell small-12 medium-auto">
                    <label>
                        <span class="hide-for-medium"><?= __('Start Date', 'ipa'); ?></span>
                        <input type="text" placeholder="Start Date" id="course-filter-date">
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="courses-table-widget-sort">
        <p class="text-left medium-text-right">
            <a type="button" class="expand"><?= __('Show All', 'ipa'); ?></a>
            /
            <a type="button" class="collapse"><?= __('Hide All', 'ipa'); ?></a>
        </p>
    </div>
<?php
wp_reset_postdata();