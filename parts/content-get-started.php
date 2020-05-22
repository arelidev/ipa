<div class="get-started">
    <div class="get-started-inner grid-x grid-padding-x grid-padding-y align-center-middle">
        <div class="small-12 medium-12 large-12 cell">
            <h3 class="text-center"><b><?= get_field( 'footer_cta_title' ); ?></b></h3>
            <p class="text-center margin-bottom-0">
				<?= get_field( 'footer_cta_text' ); ?>
            </p>
        </div>
        <div class="small-12 medium-12 large-shrink cell">
            <div class="grid-x grid-padding-x align-center-middle">
                <div class="shrink cell">
                    <h3 class="text-center large-text-left">
                        <b><?= get_field( 'footer_cta_select_label' ); ?></b>
                    </h3>
                </div>
                <div class="shrink cell">
                    <div class="select-box">

						<?php if ( have_rows( 'footer_cta_select_options' ) ): $i = 0; ?>
                            <div class="select-box__current" tabindex="1">
								<?php while ( have_rows( 'footer_cta_select_options' ) ) : the_row(); ?>
                                    <div class="select-box__value">
                                        <input class="select-box__input" type="radio" id="<?= $i; ?>" value="<?= $i; ?>"
                                               name="cta_link" <?= ( $i == 0 ) ? 'checked="checked"' : ''; ?>
                                               data-link="<?php the_sub_field( 'footer_cta_select_option_link' ); ?>"/>
                                        <p class="select-box__input-text">
											<?php the_sub_field( 'footer_cta_select_option_title' ); ?>
                                        </p>
                                    </div>
									<?php $i ++; ?>
								<?php endwhile; ?>
                                <i class="far fa-chevron-down fa-xs select-box__icon" aria-hidden="true"></i>
                            </div>
						<?php endif; ?>

						<?php if ( have_rows( 'footer_cta_select_options' ) ): $i = 0; ?>
                            <ul class="select-box__list">
								<?php while ( have_rows( 'footer_cta_select_options' ) ) : the_row(); ?>
                                    <li>
                                        <label class="select-box__option" for="<?= $i; ?>" aria-hidden="true">
											<?php the_sub_field( 'footer_cta_select_option_title' ); ?>
                                        </label>
                                    </li>
									<?php $i ++; endwhile; ?>
                            </ul>
						<?php endif; ?>

                    </div>
                </div>
                <div class="shrink cell">
                    <button type="submit" class="button large white" style="margin-bottom: 0;" id="footer_cta_submit"><?= __( 'Go!', 'ipa' ); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
