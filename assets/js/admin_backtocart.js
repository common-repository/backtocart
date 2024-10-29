(function() {

    //console.log('admin');

})();

function b2c_integration( b2c_button ) {

    var b2c_button_consumer_key = document.getElementById('b2c_consumer_key_input').value;
    var b2c_button_consumer_secret = document.getElementById('b2c_consumer_secret_input').value;

    if(b2c_button_consumer_key.length > 0 && b2c_button_consumer_secret.length > 0) {
        var b2c_clean_button_consumer_key = b2c_button_consumer_key.replace(/<[^>]+>| /g,"").replace(/^\s+|\s+$/gm,'');
        var b2c_clean_button_consumer_secret = b2c_button_consumer_secret.replace(/<[^>]+>| /g,"").replace(/^\s+|\s+$/gm,'');

        if(b2c_clean_button_consumer_key.length > 0 && b2c_clean_button_consumer_secret.length > 0) {
            console.log(b2c_clean_button_consumer_key);
            console.log(b2c_clean_button_consumer_secret);
            b2c_init_apy_key( b2c_clean_button_consumer_key, b2c_clean_button_consumer_secret );
        }else{
            return false;
        }
    }else{
        return false;
    }
}

function b2c_init_apy_key( consumer_key, consumer_secret ) {

    var http = new XMLHttpRequest();
    var url = window.location.protocol + '//' + window.location.host + '/wp-content/plugins/backtocart/includes/class-backtocart-integration.php';
    var params = 'b2c_consumer_key=' + consumer_key + '&b2c_consumer_secret=' + consumer_secret;

    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    http.onreadystatechange = function() {
        location.reload();
        //console.log(http);
    };

    http.send(params);
}

function b2c_change_embed_code_status( tag ) {

    tag.addEventListener('change', function(){
        if(this.checked) {
            b2c_send_embed_code_status( this.checked );
            document.getElementById('b2c_code_status').innerHTML = '<span class="b2c_status_info_on">ON</span>';
            document.getElementById('b2c_switch_info').innerText = 'ON - The embeded code has been inserted into your website';
        } else {
            b2c_send_embed_code_status( this.checked );
            document.getElementById('b2c_code_status').innerHTML = '<span class="b2c_status_info_off">OFF</span>';
            document.getElementById('b2c_switch_info').innerText = 'OFF - The embeded code has not been inserted into your website';
        }
    });
}

function b2c_send_embed_code_status( embed_code_status ) {

    var http = new XMLHttpRequest();
    var url = window.location.protocol + '//' + window.location.host + '/wp-content/plugins/backtocart/includes/class-backtocart-status-embed-code.php';
    var params = 'b2c_embed_code_status=' + embed_code_status;

    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    http.onreadystatechange = function() {
        //console.log(http);
    };

    http.send(params);
}
