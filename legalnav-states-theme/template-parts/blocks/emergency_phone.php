<?php
    // Retrieve state phone number
    if(isset($_SESSION['user_state_id'])) {
        $state_emergency_phone = get_field('emergency_phone_number', get_term_by('id', $_SESSION['user_state_id'], 'states'));
        if($state_emergency_phone) { ?>
            <section class="emergency-phone-block">
                <p class="emergency-phone-message">Are you safe? Call 
                    <a href="tel:<?php echo $state_emergency_phone; ?>" class="emergency-phone-number">
                        <?php echo $state_emergency_phone; ?>
                    </a> to get help.
                </p>
            </section>
        <?php }
    }
?>

