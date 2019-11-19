<div class="wrap">
    <h2>Social Media Photo Importer</h2>
    <form id="save-instagram-username">
        <div class="smpi-loading"></div>
        <h3>Instagram</h3>
        <div class="inp-group">
            <span class="label">Instagram Username</span>
            <input type="text" name="instagram-username"><span class="button-validate" data-id="instagram">Validate</span>
        </div>
        <div class="inp-group">
            <input type="submit" class="button-primary" value="Update">
        </div>
    </form>
</div>
<style>
form#save-instagram-username {
    position: relative;
    display: inline-block;
    border: 1px solid #ddd;
    margin-top: 10px;
    padding: 10px;
    border-radius: 3px;
    background-color: #fff;
}
.inp-group {
    margin-bottom: 10px;
}
.inp-group span.label {
    width: 200px;
    display: inline-block;
    font-weight: bold;
}
span.button-validate {
    text-decoration: underline;
    margin-left: 10px;
    color: blue;
    cursor: pointer;
}
.inp-group input[type="submit"] {
    margin-top: 35px;
}
.smpi-loading {
    display: none;
    position: absolute;
    background-color: rgba(255,255,255,0.8);
    width: 100%;
    height: 100%;
    left: 0;
    top: 0;
    background-image: url(/wp-content/plugins/socialmedia-photo-importer/admin/img/ajax-loader.gif);
    background-repeat: no-repeat;
    background-position: center;
}

</style>