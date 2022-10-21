<?php
$registrationInfo = get_field('registrationinfo');
$registerUri = $registrationInfo['registeruri'];
$registermessage = $registrationInfo['registermessage'];
?>

<p class="text-right course-card-learn-more">
    <a href="<?= $registerUri; ?>" class="enroll-button button expanded"<?= (empty($registerUri)) ? "disabled" : ""; ?>>
		<b><?= strtoupper($registermessage); ?></b>
    </a>
</p>
