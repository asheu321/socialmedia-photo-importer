<?php // Template for the inline uploader, used for example in the Media Library admin page - Add New. ?>
<style>
.instagram-browser {
    padding: 10px;
}
#req-instagram input[type="text"] {
    min-width: 400px;
}
</style>

<script type="text/html" id="tmpl-instagram">
    <div class="smpi-media-header">
        <form id="req-instagram">
            <input type="text" name="instagram-username" placeholder="Type instagram username here">
            <input type="submit" value="Search" class="button-primary">
        </form>
    </div>
    <div class="smpi-media-content">
        the reseult will be displayed here 
    </div>
</script>

<script>
<?php require_once SMPI_PLUGIN_DIR . 'admin/js/media.js'; ?>
</script>