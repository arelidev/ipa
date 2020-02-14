<div class="testimonial-card">
    <div class="testimonial-card-inner">
        <ul class="menu horizontal testimonial-card-rating">
            <li><i class="fas fa-star"></i></li>
            <li><i class="fas fa-star"></i></li>
            <li><i class="fas fa-star"></i></li>
            <li><i class="fas fa-star"></i></li>
            <li><i class="fas fa-star"></i></li>
        </ul>

        <div class="testimonial-card-text" data-equalizer-watch="testimonials-content">
            <?php the_content(); ?>
        </div>

        <div class="grid-x align-middle">
            <div class="shrink cell">
				<?php if ( has_post_thumbnail( get_the_ID() ) ) : ?>
					<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'testimonial-card-profile-image' ) ); ?>
				<?php endif; ?>
            </div>
            <div class="auto cell">
                <p class="testimonial-card-name"><?php the_title(); ?></p>
				<?php if ( ! empty( $by_line = get_field( 'testimonial_by_line' ) ) ) : ?>
                    <p class="testimonial-card-company"><?= $by_line; ?></p>
				<?php endif; ?>
            </div>
        </div>
    </div>
</div>
