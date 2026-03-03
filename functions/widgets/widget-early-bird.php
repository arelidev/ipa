<?php
/**
 * Generates an early bird widget displaying the unique offers for specified IPA Arlo events.
 *
 * @param array $atts {
 *     An array of attributes to filter and customize the widget content.
 *
 * @type string|bool $presenter Optional. Filter events by a specific presenter. Default false.
 * @type string|bool $template Optional. Filter events by a template (e.g., "Virtual", "On-demand"). Default false.
 * @type bool $filters Optional. Flag to enable or disable additional filters. Default true.
 * @type bool $single Optional. Flag to indicate if a single event should be displayed. Default false.
 * @type string $el_class Optional. Additional CSS class to customize the widget's container. Default ''.
 * }
 * @return string The generated HTML output of the widget.
 */
function ipa_early_bird_widget( $atts ) {
    $atts = shortcode_atts( array(
            'presenter' => false,
            'template'  => false,
            'filters'   => true,
            'single'    => false,
            'el_class'  => ''
    ), $atts );

    ob_start();

    $active = false;

    $args = array(
            'post_type'        => 'ipa_arlo_events',
            'post_status'      => 'publish',
            'posts_per_page'   => - 1,
            'suppress_filters' => false,
            'meta_key'         => 'startdatetime',
            'orderby'          => 'meta_value',
            'order'            => 'ASC',
    );

    if ( $atts['presenter'] ) :
        $args['meta_query'] = array(
                array(
                        'key'     => 'presenters_$_linked_presenter',
                        'value'   => '"' . $atts['presenter'] . '"',
                        'compare' => 'LIKE'
                )
        );

        $active = true;
    endif;

    if ( $atts['template'] ) :
        if ( $atts['template'] === "Virtual" || $atts["template"] === "On-demand" ) :
            $args['meta_query'] = array(
                    array(
                            'key'     => 'categories_$_name',
                            'value'   => $atts['template'],
                            'compare' => 'LIKE'
                    )
            );

            $atts["single"] = false;
        else :
            $args['meta_query'] = array(
                    array(
                            'key'     => 'templatecode',
                            'value'   => $atts['template'],
                            'compare' => '='
                    )
            );
        endif;

        $active = true;
    endif;

    $loop = new WP_Query( $args );

    $courses = [];
    if ( $loop->have_posts() ) :
        while ( $loop->have_posts() ) : $loop->the_post();
            $course               = get_field( 'name' );
            $courses[ $course ][] = get_the_ID();
        endwhile;
    endif;

    ksort( $courses );

    wp_reset_postdata();

    foreach ( $courses as $course => $course_ids ) :
        $offers = [];

        foreach ( $course_ids as $id ) :
            $advertisedOffers = get_field( 'advertisedoffers', $id );
            if ( ! is_array( $advertisedOffers ) ) :
                continue;
            endif;

            foreach ( $advertisedOffers as $offer ) :
                $offers[] = [
                    'amount'  => $offer['offeramount']['amounttaxexclusive'],
                    'post_id' => $id,
                ];
            endforeach;
        endforeach;

        $early_bird = $super_bird = false;
        $regular_price = false;
        $regular_price_post_id = false;

        if ( count( $offers ) > 0 ) :
            $seen = [];
            $regular_offer = false;

            foreach ( $offers as $offer_data ) :
                $amount = floatval( $offer_data['amount'] );
                if ( $amount <= 0 ) :
                    continue;
                endif;

                if ( isset( $seen[ $amount ] ) ) :
                    continue;
                endif;

                $seen[ $amount ] = true;
                $regular_offer = $offer_data;
                break;
            endforeach;

            if ( $regular_offer ) :
                $regular_price = floatval( $regular_offer['amount'] );
                $regular_price_post_id = intval( $regular_offer['post_id'] );
                $early_bird = $regular_price - 50;
                $super_bird = $regular_price - 100;
            endif;
        endif;

        if ( ! ( $regular_price || $early_bird || $super_bird ) ) :
            continue;
        endif;
        ?>
        <div class="early-bird-course">
            <a href="#price-information">
                <?php if ( $regular_price ) : ?>
                    <div class="regular-price-widget">
                        <div class="regular-price">
                            <span class="regular-price-label"><b><?php esc_html_e( 'Regular Price', 'ipa' ); ?></b></span>
                            <span class="regular-price-amount"><?php esc_html_e( '$' . number_format( $regular_price, 2 ) ); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ( $early_bird ) : ?>
                    <div class="early-bird-widget">
                        <div class="early-bird-offer">
                            <span class="early-bird-offer-label"><b><?php esc_html_e( 'Early Bird Offer', 'ipa' ); ?>: </b></span>
                            <span class="early-bird-offer-amount"><?php esc_html_e( '$' . number_format( $early_bird, 2 ) ); ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ( $super_bird ) : ?>
                    <div class="super-bird-widget">
                        <div class="super-bird-offer">
                            <span class="super-bird-offer-label"><b><?php esc_html_e( 'Super Bird Offer', 'ipa' ); ?>: </b></span>
                            <span class="super-bird-offer-amount"><?php esc_html_e( '$' . number_format( $super_bird, 2 ) ); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
            </a>
        </div>
        <?php
    endforeach;

    return ob_get_clean();
}

add_shortcode( 'ipa_early_bird', 'ipa_early_bird_widget' );
