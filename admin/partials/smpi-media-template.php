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
.smpi-media-content {
    display: inline-block;
    width: 100%;
    min-height: 100%;
}
.smpi-media-content .media-item {
    box-sizing: border-box;
    display: flex;
    padding: 5px;
    border: 1px solid #eee;
    margin: 5px;
    float: left;
    position: relative;
}
span.media-import-button {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    text-align: center;
    width: 70px;
    margin-left: auto !important;
    margin-right: auto !important;
    margin-bottom: 20px !important;
    visibility: hidden;
}
.smpi-media-content .media-item:hover > span.media-import-button {
    visibility: visible;
}
.instagram-browser {
    padding: 10px;
    height: 100%;
    box-sizing: border-box;
    overflow: auto;
}
.media-frame.loading .smpi-media-content {
    background-image: url(<?php echo SMPI_PLUGIN_URL; ?>/admin/img/ajax-loading.gif);
    background-repeat: no-repeat;
    background-position: center;
}
</style>

<script type="text/html" id="tmpl-instagram">
    <div class="smpi-media-content"></div>
</script>

<script>
<?php require_once SMPI_PLUGIN_DIR . 'admin/js/media.js'; ?>
</script>