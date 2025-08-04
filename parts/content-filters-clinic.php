    <div class="search-bar styled-container content-filters-clinic">
    <form class="grid-x grid-padding-x grid-padding-y align-middle">
        <div class="cell auto">
            <b><?= __( 'Filter by:', 'ipa' ); ?></b>
        </div>
        <fieldset class="cell shrink">
            <a class="button small clear-button" style="margin-bottom: 0px" role="button" tabindex="0">
				<?= __( 'Clear', 'ipa' ); ?>
            </a>
        </fieldset>
        <fieldset class="cell auto">
            <label for="clinics-filter-select">
                <span class="show-for-sr"><?= __( 'Filter by clinic type', 'ipa' ); ?></span>
                <select id="clinics-filter-select" aria-label="<?= __( 'Filter by clinic type', 'ipa' ); ?>">
                    <option value=""><?= __( 'All Clinics', 'ipa' ); ?></option>
                    <option value="faculty"><?= __( 'All Faculty', 'ipa' ); ?></option>
                    <option value="primary-faculty"><?= __( 'Primary Faculty', 'ipa' ); ?></option>
                    <option value="associate-faculty"><?= __( 'Associate Faculty', 'ipa' ); ?></option>
                    <option value="fmt-fellow"><?= __( 'FMT Fellows', 'ipa' ); ?></option>
                    <option value="cfmt"><?= __( 'CFMT', 'ipa' ); ?></option>
                    <option value="cafmt"><?= __( 'CAFMT', 'ipa' ); ?></option>
                    <option value="clinic"><?= __( 'IPA Clinics', 'ipa' ); ?></option>
                </select>
            </label>
        </fieldset>
        <fieldset class="cell auto">
            <label for="clinics-filter-state">
                <span class="show-for-sr"><?= __( 'Filter by state', 'ipa' ); ?></span>
                <select class="clinics-filter-state" id="clinics-filter-state" aria-label="<?= __( 'Filter by state', 'ipa' ); ?>">
                    <option value="all"><?= __( 'State', 'ipa' ); ?></option>
                    <option value="all"><?= __( 'All', 'ipa' ); ?></option>
                    <option value="AL">Alabama</option>
                    <option value="AK">Alaska</option>
                    <option value="AZ">Arizona</option>
                    <option value="AR">Arkansas</option>
                    <option value="CA">California</option>
                    <option value="CO">Colorado</option>
                    <option value="CT">Connecticut</option>
                    <option value="DE">Delaware</option>
                    <option value="DC">District Of Columbia</option>
                    <option value="FL">Florida</option>
                    <option value="GA">Georgia</option>
                    <option value="HI">Hawaii</option>
                    <option value="ID">Idaho</option>
                    <option value="IL">Illinois</option>
                    <option value="IN">Indiana</option>
                    <option value="IA">Iowa</option>
                    <option value="KS">Kansas</option>
                    <option value="KY">Kentucky</option>
                    <option value="LA">Louisiana</option>
                    <option value="ME">Maine</option>
                    <option value="MD">Maryland</option>
                    <option value="MA">Massachusetts</option>
                    <option value="MI">Michigan</option>
                    <option value="MN">Minnesota</option>
                    <option value="MS">Mississippi</option>
                    <option value="MO">Missouri</option>
                    <option value="MT">Montana</option>
                    <option value="NE">Nebraska</option>
                    <option value="NV">Nevada</option>
                    <option value="NH">New Hampshire</option>
                    <option value="NJ">New Jersey</option>
                    <option value="NM">New Mexico</option>
                    <option value="NY">New York</option>
                    <option value="NC">North Carolina</option>
                    <option value="ND">North Dakota</option>
                    <option value="OH">Ohio</option>
                    <option value="OK">Oklahoma</option>
                    <option value="OR">Oregon</option>
                    <option value="PA">Pennsylvania</option>
                    <option value="RI">Rhode Island</option>
                    <option value="SC">South Carolina</option>
                    <option value="SD">South Dakota</option>
                    <option value="TN">Tennessee</option>
                    <option value="TX">Texas</option>
                    <option value="UT">Utah</option>
                    <option value="VT">Vermont</option>
                    <option value="VA">Virginia</option>
                    <option value="WA">Washington</option>
                    <option value="WV">West Virginia</option>
                    <option value="WI">Wisconsin</option>
                    <option value="WY">Wyoming</option>
                </select>
            </label>
        </fieldset>
        <fieldset class="cell auto">
            <label for="clinics-filter-instructor">
                <span class="show-for-sr"><?= __( 'Filter by name', 'ipa' ); ?></span>
                <input type="text" class="clinics-filter-name" id="clinics-filter-instructor" placeholder="<?php esc_attr_e( 'Name', 'ipa' ); ?>" aria-label="<?= __( 'Filter by name', 'ipa' ); ?>">
            </label>
        </fieldset>
        <div class="cell shrink">
            <label><?= __( 'or', 'ipa' ); ?></label>
        </div>
        <fieldset class="cell auto">
            <label for="clinics-filter-zip">
                <span class="show-for-sr"><?= __( 'Filter by zip code', 'ipa' ); ?></span>
                <input type="text" class="clinics-filter-zipcode" placeholder="<?php esc_attr_e( 'Zip Code', 'ipa' ); ?>" id="clinics-filter-zip" aria-label="<?= __( 'Filter by zip code', 'ipa' ); ?>">
            </label>
        </fieldset>
    </form>
</div>