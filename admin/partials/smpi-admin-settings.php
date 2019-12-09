<div class="wrap">
    <h2>Social Media Photo Importer</h2>
    <form id="save-instagram-username">
        <div class="smpi-loading"></div>
        <h3>Instagram</h3>
        
        <div class="inp-group hide-on-validated">
            <span class="label">Instagram Username</span>
            <input type="text" name="instagram-username">
        </div>
        <div class="data-result"></div>
        <div class="inp-group hide-on-validated">
            <input type="submit" class="button-primary" value="Update">
        </div>
    </form>
</div>
<style>
<?php if ( get_option( 'smpi_instagram_account' ) != '' ) { echo '.hide-on-validated {display:none}'; } ?>
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
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
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
.data-result .profile {
    padding: 10px;
    border: 1px solid #eee;
}
.data-result .profile .photo {
    width: 150px;
    height: 150px;
    margin: 0 auto 15px;
}
.data-result .profile .photo img {
    max-width: 100%;
}
.data-result .profile .profile-data {
    text-align: center;
}
.data-result .profile .profile-data label {
    display: block;
    font-weight: bold;
    margin-top: 15px;
    font-size: 16px;
}
.data-result .profile .delete {
    text-align: center;
    padding-top: 10px;
    text-decoration: underline;
    color: red;
}
.data-result .profile .delete span {
    cursor: pointer;
}
</style>