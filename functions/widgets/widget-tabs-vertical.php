<?php
/**
 * IPA Vertical Tabs Shortcode Widget
 *
 * @param $atts
 * @param null $content
 *
 * @return false|string
 */
function ipa_vertical_tabs_widget( $atts, $content = null ) {
	$atts = shortcode_atts( array(
		'el_class' => ''
	), $atts );

	ob_start();
	?>
    <div class="grid-x grid-margin-x ipa-vertical-tabs <?= $atts['el_class']; ?>">
        <div class="cell small-12 medium-12 large-5">
            <ul class="vertical tabs" data-tabs id="example-tabs">
                <li class="tabs-title is-active">
                    <a href="#panel1v" aria-selected="true">FMT is systematic and scientifically rooted </a>
                </li>
                <li class="tabs-title">
                    <a href="#panel2v">FMT is a full-body approach!</a>
                </li>
                <li class="tabs-title">
                    <a href="#panel3v">FMT goes beyond traditional mobilization and mechanical treatment</a>
                </li>
                <li class="tabs-title">
                    <a href="#panel4v">FMT integrates specific neuromuscular assessment and function</a>
                </li>
                <li class="tabs-title">
                    <a href="#panel5v">FMT Facilitates CoreFirst® Strategies and Automatic Core Engagement</a>
                </li>
            </ul>
        </div>
        <div class="cell small-12 medium-12 large-7">
            <div class="tabs-content" data-tabs-content="example-tabs">
                <div class="tabs-panel is-active" id="panel1v">
                    <ol class="styled-ol">
                        <li>Balances evidence and science with the art of individual application.</li>
                        <li>Correlates anatomy and kinesiology to movement of the parts and the whole.</li>
                        <li>Breaks free from normative values to discover each individual’s most optimal state of
                            function.
                        </li>
                        <li>romotes the development of pattern recognition, leading to becoming a master clinician</li>
                    </ol>
                </div>
                <div class="tabs-panel" id="panel2v">
                    <ol class="styled-ol">
                        <li>Trains the therapist to think three dimensionally from a interdependent systems perspective,
                            not just a interrelated tissue perspective
                        </li>
                        <li>Trains the therapist to see regional inter-dependency within a three-dimensional systems
                            perspective
                        </li>
                        <li>Focuses on how the mechanical capacity of an individual body part interacts with the body
                            both above and below.
                        </li>
                    </ol>
                </div>
                <div class="tabs-panel" id="panel3v">
                    <ol class="styled-ol">
                        <li>Assessment of joint mechanical capacity is not dictated by the angles of the articular
                            surfaces or arthrokinematics; it is end-feel directed and performed three dimensionally to
                            assess the surrounding connective tissue which typically limits joint mobility.
                        </li>
                        <li>Traditional mobilization focuses on attaining neutrality of a body part in a resting
                            position. FMT focuses on attaining the full excursion mobility of the body part through
                            complex 3D movements
                        </li>
                        <li>Utilizes body positions, myofascial planes and the skin system to create a “total body”
                            locking of a target mechanical restriction.
                        </li>
                        <li> FMT utilizes weight bearing positions to reveal and mobilize mechanical hypomobilities not
                            always evident in non-weight bearing positions.
                        </li>
                        <li> Each mobilization is followed by specific neuromuscular function and motor control training
                            in all new ranges of motion.
                        </li>
                        <li>Patient active involvement with Functional Mobilization of all structures (joints, soft
                            tissues, viscera, and neurovascular tissues) promotes changes in the homunculus,
                            facilitating a more effective release.
                        </li>
                    </ol>
                </div>
                <div class="tabs-panel" id="panel4v">
                    <ol class="styled-ol">
                        <li>Utilizes the concept of fiber specificity in which a manual therapist can assess and
                            facilitate local (tonic) versus global (phasic) muscle contractions.
                        </li>
                        <li>Seamlessly incorporates neuromuscular reeducation in the new ROM after mobilization to
                            enhance function.
                        </li>
                        <li>Focuses on the direct correlation between mechanical capacity and the efficiency of
                            neuromuscular function and motor control.
                        </li>
                        <li>Ensures the full integration of each restored tissue mobility with visual and palpation
                            assessment of full body functional activities and postures.
                        </li>
                    </ol>
                </div>
                <div class="tabs-panel" id="panel5v">
                    <ol class="styled-ol">
                        <li>FMT motor control training is built on the principles of proper synergistic activation of
                            local and global muscles (CoreFirst Strategies)
                        </li>
                        <li>Provides a structured system of training Motor Control</li>
                        <li>Utilizes Five CoreFirst® Principles of posture and movement to allow for an Automatic Core
                            Engagement (ACE)
                        </li>
                        <li>FMT provides a structured system of motor control training to assess the mechanical capacity
                            and neuromuscular function of all ADL and determine the presence or absence of CoreFirst
                            postural and movement strategies.
                        </li>
                        <li>Assimilated Central Nervous System - effectively recognizing sensory input and activating
                            effective motor output for any given automatic or volitional movement
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
	<?php
	return ob_get_clean();
}

add_shortcode( 'ipa_vertical_tabs', 'ipa_vertical_tabs_widget' );

/**
 * IPA Vertical Tabs Item
 *
 * @param $atts
 * @param $content
 */
function ipa_vertical_tabs_item_widget( $atts, $content ) {
	$atts = shortcode_atts( array(
		'el_class'  => '',
		'is_active' => false, // add class is-active
		'title'     => '',
	), $atts );

	$slug = clean( strtolower( $atts['title'] ) );
	?>
    <li class="accordion-item ipa-accordion-item <?= $atts['el_class']; ?>" data-accordion-item>
        <!-- Accordion tab title -->
        <a href="#<?= $slug; ?>" class="accordion-title ipa-accordion-title"><b><?= $atts['title']; ?></b></a>

        <!-- Accordion tab content: it would start in the open state due to using the `is-active` state class. -->
        <div class="accordion-content ipa-accordion-content" data-tab-content id="<?= $slug; ?>">
			<?= do_shortcode( $content ); ?>
        </div>
    </li>
	<?php
}

// add_shortcode( 'ipa_vertical_tabs_item', 'ipa_vertical_tabs_item_widget' );

// Integrate with Visual Composer
function ipa_vertical_tabs_integrateWithVC() {
	try {
		vc_map( array(
			"name"                    => __( "Vertical Tabs", "ipa" ),
			"base"                    => "ipa_vertical_tabs",
			"class"                   => "",
			"category"                => __( "Custom", "ipa" ),
			"params"                  => array(),
			"show_settings_on_create" => false
		) );
	} catch ( Exception $e ) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

add_action( 'vc_before_init', 'ipa_vertical_tabs_integrateWithVC' );