<div class="wrap">
    <h1>Custom Product Text Preview Settings</h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('cptp_settings_group');
        do_settings_sections('cptp-settings');
        submit_button();
        ?>
    </form>
</div>