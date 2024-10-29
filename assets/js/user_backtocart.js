window.onload = function() {

    //console.log('user');

    //=============== Listen DOM ==================
    // create an observer instance
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            //console.log(mutation.type);
            var b2c_removeCartButtonChanged = document.querySelectorAll('div.woocommerce form table a.remove');
            if(b2c_removeCartButtonChanged.length !== 0 && b2c_removeCartButtonChanged !== undefined && b2c_removeCartButtonChanged !== null) {
                b2c_remove_item_from_cart( b2c_removeCartButtonChanged );
            }
        });
    });

    // configuration of the observer:
    var config = { attributes: true, childList: true, characterData: true };

    // pass in the target node, as well as the observer options
    observer.observe(document.body, config);

    // later, you can stop observing
    // observer.disconnect();
    //=============== END Listen DOM ==============

    var b2c_addCartButton = document.getElementsByClassName('add_to_cart_button');
    var b2c_addSimpleCartButton = document.getElementsByClassName('single_add_to_cart_button');
    var b2c_removeCartButton = document.querySelectorAll('div.woocommerce form table a.remove');

    //=============== Remove From Cart =============

    if(b2c_removeCartButton.length !== 0 && b2c_removeCartButton !== undefined && b2c_removeCartButton !== null) {
        b2c_remove_item_from_cart( b2c_removeCartButton );
    }

    //=============== Add To Cart =============

    if(b2c_addCartButton.length !== 0 && b2c_addCartButton !== undefined && b2c_addCartButton !== null) {

        for (var b2c_i = 0; b2c_i < b2c_addCartButton.length; b2c_i++) {

            var b2c_addSelector = '[' + b2c_addCartButton[b2c_i].attributes[3].name + '="' + b2c_addCartButton[b2c_i].attributes[3].value + '"]';

            document.querySelector( b2c_addSelector ).classList.add('b2c_cart');

            b2c_addCartButton[b2c_i].addEventListener('click', (function (b2c_i) {
                return function () {
                    b2c_init_cart_changes('b2c_cart');
                };
            })(b2c_i));
        }
    }

    //=============== Add To Cart From Single Page =============

    if(b2c_addSimpleCartButton.length !== 0 && b2c_addSimpleCartButton !== undefined && b2c_addSimpleCartButton !== null) {

        for (var b2c_k = 0; b2c_k < b2c_addSimpleCartButton.length; b2c_k++) {

            var b2c_addSimpleSelector = '[' + b2c_addSimpleCartButton[b2c_k].attributes[3].name + '="' + b2c_addSimpleCartButton[b2c_k].attributes[3].value + '"]';

            document.querySelector( b2c_addSimpleSelector ).classList.add('b2c_single_cart');

            b2c_addSimpleCartButton[b2c_k].addEventListener('click', (function (b2c_k) {
                return function () {
                    b2c_init_cart_changes('b2c_single_cart');
                };
            })(b2c_k));
        }
    }

    b2c_init_user_type();
};

    function b2c_remove_item_from_cart( b2c_removeCartButton ) {

        for (var b2c_j = 0; b2c_j < b2c_removeCartButton.length; b2c_j++) {

            // b2c_removeCartButton[j].classList.contains('b2c_remove_cart');

            var b2c_removeSelector = '[' + b2c_removeCartButton[b2c_j].attributes[3].name + '="' + b2c_removeCartButton[b2c_j].attributes[3].value + '"]';

            document.querySelector( b2c_removeSelector ).classList.add('b2c_remove_cart');

            b2c_removeCartButton[b2c_j].addEventListener('click', (function (b2c_j) {
                return function () {
                    b2c_init_cart_changes('b2c_remove_cart');
                };
            })(b2c_j));
        }
    }

    function b2c_init_cart_changes( change_type ) {

        var b2cGetStorage = window.localStorage;
        var getStorage = b2cGetStorage.getItem('b2cUserCart');
        var userInit = '';

        if(getStorage !== null && getStorage !== 'unknown'){
            userInit = getStorage.toString();
        }else{
            userInit = 'unknown';
        }

        var http = new XMLHttpRequest();
        var url = window.location.protocol + '//' + window.location.host + '/wp-content/plugins/backtocart/includes/class-backtocart-init-changes.php';
        var params = 'b2c_change_type=' + change_type + '&b2c_user_init=' + userInit;

        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        http.onreadystatechange = function() {
            //console.log(http);
        };
        http.send(params);
    }

    function b2c_init_user_type() {

        var http = new XMLHttpRequest();
        var url = window.location.protocol + '//' + window.location.host + '/wp-content/plugins/backtocart/includes/class-backtocart-user-type.php';
        var params = 'b2c_user_type=check';

        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        http.onreadystatechange = function() {
            if(http.readyState == XMLHttpRequest.DONE && http.status == 200) {
                if(http.responseText.split(/[\s>]+/).pop() !== 'unknown') {
                    var b2cSetStorage = window.localStorage;
                    b2cSetStorage.setItem('b2cUserCart',http.responseText.split(/[\s>]+/).pop());
                }
            }
        };
        http.send(params);
    }
