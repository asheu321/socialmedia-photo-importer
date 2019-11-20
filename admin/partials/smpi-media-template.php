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
.smpi-media-content .media-item {
    box-sizing: border-box;
    display: flex;
    padding: 5px;
    border: 1px solid #eee;
    margin: 5px;
    float: left;
    cursor: pointer;
}
</style>

<script type="text/html" id="tmpl-instagram">
    <div class="smpi-media-content"></div>
</script>

<script>
<?php require_once SMPI_PLUGIN_DIR . 'admin/js/media.js'; ?>
</script>