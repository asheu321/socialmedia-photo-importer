<?php // Template for the inline uploader, used for example in the Media Library admin page - Add New. ?>
<style>
.instagram-browser {
    padding: 10px;
}
.smpi-media-header {
    margin-bottom: 10px;
}
#req-instagram {
    max-width: 475px;
    position: relative;
}
#req-instagram input[type="text"] {
    min-width: 400px;
}
span.smpi-small-loading {
    display: none;
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    background-color: rgba(255,255,255,0.9);
    height: 100%;
    background-image: url(<?php echo SMPI_PLUGIN_URL; ?>/admin/img/ajax-loader.gif);
    background-repeat: no-repeat;
    background-position: center;
}
</style>

<script type="text/html" id="tmpl-instagram">
    <div class="smpi-media-header">
        <form id="req-instagram">
            <input type="text" name="instagram-username" placeholder="Type instagram username here">
            <input type="submit" value="Search" class="button-primary">
            <span class="smpi-small-loading"></span>
        </form>
    </div>
    <div class="smpi-media-content"></div>
</script>

<script>
<?php require_once SMPI_PLUGIN_DIR . 'admin/js/media.js'; ?>
</script>