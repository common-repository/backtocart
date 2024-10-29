<?php
/**
 * Admin View: Backtocart Template Preview
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<h1 class='b2c_logo_section'>
    <img class='b2c_image_logo' src='/wp-content/plugins/backtocart/assets/images/logo.png' alt='logo' width='250'>
</h1>

<div class='b2c_introduce b2c_section'>
    <div class='b2c_intro_info'>
    Backtocart is a personalized notification platform that will promote conversation rate optimization and boost the sales of your e-commerce store.
    <br> Get started with Backtocart by clicking the button on the right. Sign up and get your account up and running with Backtocart today!
    </div>
    <a class='b2c_button_sign_up' href='//app.backtocart.co' target='_blank'>Sign Up To Backtocart</a>
</div>


<div class='b2c_section'>
    <label class='b2c_switch'>
        <input type='checkbox' <?php if($GLOBALS['backtocart']->b2c_code_access==='true'){echo'checked';} ?> onclick='b2c_change_embed_code_status(this);'>
        <span class='b2c_slider b2c_round'></span>
    </label>
    <span id='b2c_code_status'><?php if($GLOBALS['backtocart']->b2c_code_access==='true'){echo'<span class="b2c_status_info_on">ON</span>';}else{echo'<span class="b2c_status_info_off">OFF</span>';} ?></span>
    <div class='b2c_ckeckbox_info'>
        <span id='b2c_switch_info'><?php if($GLOBALS['backtocart']->b2c_code_access==='true'){echo'ON - The embeded code has been inserted into your website';}else{echo'OFF - The embeded code has not been inserted into your website';} ?></span>
    </div>
</div>


<div class='b2c_section'>
    <div>Using the WooCommerce Integration feature, connect your shopping cart to Backtocart in order to have a complete ecommerce analytics, later on, enabling you to analyze and act upon your customers’ shopping behavior.</div>
    <hr>
    <table class="form-table">
        <tbody>
            <tr>
                <th>Consumer key</th>
                <td>
                    <input id='b2c_consumer_key_input' class='b2c_input' type='text' <?php if($GLOBALS['backtocart']->b2c_key_access==='true'){echo'disabled';} ?>>
                </td>
            </tr>
            <tr>
                <th>Consumer secret</th>
                <td>
                    <input id='b2c_consumer_secret_input' class='b2c_input' type='text'<?php if($GLOBALS['backtocart']->b2c_key_access==='true'){echo'disabled';} ?>>
                </td>
            </tr>
            <tr>
                <td>
                    <?php if($GLOBALS['backtocart']->b2c_key_access==='true'){echo'<div class="b2c_integration_done"><span>INTEGRATION DONE</span><img  style="vertical-align: middle;" src="/wp-content/plugins/backtocart/assets/images/check.png"></div>';}else{echo"<a class='b2c_button' onclick='b2c_integration(this);'>Submit API-KEY</a>";} ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>


<div class='b2c_section'>
    <h4>New Feature Updates</h4>
    In this update, juggle with your website’s Embed Code by turning it ON /OFF using a simple toggle. Also find some brand new features including the WooCommerce-Backtocart integration feature which will help you gain full insight into your shoppers’ cart abandonment rates, purchasing frequency and general shopping behavior. All of this is coming with the Cart Analyzer function in the future updates!
</div>