<?php
// Check if the product has custom text enabled
if (get_field('has_custom_text')) {
    ?>
    <!-- Preview Image Modal -->
    <div id="cptp-preview-modal" class="cptp-modal">
        <span class="cptp-close">&times;</span>
        <div class="cptp-modal-content">
            <canvas id="cptp-canvas" width="500" height="500"></canvas>
        </div>
    </div>
    <?php
}
?>