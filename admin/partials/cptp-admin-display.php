<div class="wrap">
    <h1>Custom Product Text Preview Settings</h1>
    <div style="display: flex;">
        <form method="post" action="options.php" style="flex: 1;">
            <?php
            settings_fields('cptp_settings_group');
            do_settings_sections('cptp-settings');
            submit_button();
            ?>
        </form>
        <div style="flex: 1; padding-left: 20px;">
            <h2>Preview</h2>
            <canvas id="cptp-preview-canvas" width="500" height="500" style="border:1px solid #000;"></canvas>
        </div>
    </div>
</div>