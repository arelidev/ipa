<?php
$courses = $args['courses'] ?? [];

$presenters = new WP_Query( array(
	'post_type'      => 'ipa_arlo_presenters',
	'post_status'    => 'publish',
	'posts_per_page' => - 1,
	'order'          => 'ASC',
	'orderby'        => 'title'
) );
?>
    <form class="search-bar">
        <div class="styled-container courses-table-widget-filters">
            <div class="grid-x grid-padding-x grid-padding-y align-middle">
                <div class="cell small-12 medium-auto">
                    <label>
						<?= __( 'Course Type', 'ipa' ); ?>
                        <select class="course-filter-type">
                            <option value="all"><?= __( 'Select course', 'ipa' ); ?></option>
							<?php foreach ( $courses as $title => $id ) : ?>
                                <option value="course-<?= acf_slugify( $title ); ?>"><?= $title; ?></option>
							<?php endforeach; ?>
                        </select>
                    </label>
                </div>
                <fieldset class="cell small-12 medium-auto" data-filter-group>
                    <label>
						<?= __( 'Select State', 'ipa' ); ?>
                        <select class="course-filter-state course-select-filter">
                            <option value="">All</option>
                            <option value=".al">Alabama</option>
                            <option value=".al">Alaska</option>
                            <option value=".az">Arizona</option>
                            <option value=".ar">Arkansas</option>
                            <option value=".ca">California</option>
                            <option value=".co">Colorado</option>
                            <option value=".ct">Connecticut</option>
                            <option value=".de">Delaware</option>
                            <option value=".district-of-columbia">District Of Columbia</option>
                            <option value=".fl">Florida</option>
                            <option value=".ga">Georgia</option>
                            <option value=".hi">Hawaii</option>
                            <option value=".id">Idaho</option>
                            <option value=".il">Illinois</option>
                            <option value=".in">Indiana</option>
                            <option value=".ia">Iowa</option>
                            <option value=".ks">Kansas</option>
                            <option value=".ky">Kentucky</option>
                            <option value=".la">Louisiana</option>
                            <option value=".me">Maine</option>
                            <option value=".md">Maryland</option>
                            <option value=".ma">Massachusetts</option>
                            <option value=".mi">Michigan</option>
                            <option value=".mn">Minnesota</option>
                            <option value=".ms">Mississippi</option>
                            <option value=".md">Missouri</option>
                            <option value=".mt">Montana</option>
                            <option value=".ne">Nebraska</option>
                            <option value=".nv">Nevada</option>
                            <option value=".nh">New Hampshire</option>
                            <option value=".nj">New Jersey</option>
                            <option value=".nm">New Mexico</option>
                            <option value=".ny">New York</option>
                            <option value=".nc">North Carolina</option>
                            <option value=".nd">North Dakota</option>
                            <option value=".oh">Ohio</option>
                            <option value=".ok">Oklahoma</option>
                            <option value=".or">Oregon</option>
                            <option value=".pa">Pennsylvania</option>
                            <option value=".ri">Rhode Island</option>
                            <option value=".sc">South Carolina</option>
                            <option value=".sd">South Dakota</option>
                            <option value=".tn">Tennessee</option>
                            <option value=".tx">Texas</option>
                            <option value=".ut">Utah</option>
                            <option value=".vt">Vermont</option>
                            <option value=".va">Virginia</option>
                            <option value=".wa">Washington</option>
                            <option value=".wv">West Virginia</option>
                            <option value=".wi">Wisconsin</option>
                            <option value=".wy">Wyoming</option>
                        </select>
                    </label>
                </fieldset>
                <fieldset class="cell small-12 medium-auto" data-filter-group>
                    <label>
						<?= __( 'Select Region', 'ipa' ); ?>
                        <select class="course-filter-region course-select-filter">
                            <option value="">All</option>
                            <option value=".midatlantic">Mid-Atlantic</option>
                            <option value=".midwest">Mid-West</option>
                            <option value=".northeast">Northeast</option>
                            <option value=".northwest">Northwest</option>
                            <option value=".southwest">Southwest</option>
                            <option value=".southeast">Southeast</option>
                            <option value=".west">West</option>
	                        <option value=".rmt-texas">Rocky Mt./Texas</option>
                        </select>
                    </label>
                </fieldset>
                <fieldset class="cell small-12 medium-auto" data-filter-group>
                    <label>
						<?= __( 'Instructor', 'ipa' ); ?>
                        <select class="course-filter-instructor course-select-filter">
                            <option value="">All</option>
							<?php if ( $presenters->have_posts() ): ?>
								<?php while ( $presenters->have_posts() ): $presenters->the_post(); ?>
                                    <option value=".<?= acf_slugify( get_the_title() ) ?>"><?php the_title(); ?></option>
								<?php endwhile; ?>
							<?php endif; ?>
                        </select>
                    </label>
                </fieldset>
                <fieldset class="cell small-12 medium-auto">
                    <label>
						<?= __( 'Date Range', 'ipa' ); ?>
                        <input type="text" placeholder="Start Date - End Date" id="course-filter-date">
                    </label>
                </fieldset>
            </div>
        </div>
        <div class="courses-table-widget-sort">
            <div class="grid-x grid-padding-y align-middle">
                <div class="small-12 medium-auto cell">
                    <fieldset data-filter-group>
                        <button type="button" class="course-filter-location active" value="all">All</button>
                        <button type="button" class="course-filter-location" value=".in-person">In-Person</button>
                        <button type="button" class="course-filter-location" value=".on-demand">On-Demand</button>
                        <button type="button" class="course-filter-location" value=".virtual">Virtual</button>
                    </fieldset>
                </div>
                <div class="small-12 medium-auto cell">
                    <p class="text-left medium-text-right">
                        <a type="button" class="expand-courses"><?= __( 'Show All', 'ipa' ); ?></a>
                        /
                        <a type="button" class="collapse-courses"><?= __( 'Hide All', 'ipa' ); ?></a>
                    </p>
                </div>
            </div>
        </div>
    </form>
<?php
wp_reset_postdata();