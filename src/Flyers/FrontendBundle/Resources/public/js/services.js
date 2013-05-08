'use strict';

/* Services */

// Demonstrate how to register services
// In this case it is a simple value service.
angular.module('myApp.services', ['ngCookies', 'ngResource']).
  factory('Base64', function() {
    var keyStr = 'ABCDEFGHIJKLMNOP' +
        'QRSTUVWXYZabcdef' +
        'ghijklmnopqrstuv' +
        'wxyz0123456789+/' +
        '=';
    return {
        encode: function (input) {
            var output = "";
            var chr1, chr2, chr3 = "";
            var enc1, enc2, enc3, enc4 = "";
            var i = 0;
 
            do {
                chr1 = input.charCodeAt(i++);
                chr2 = input.charCodeAt(i++);
                chr3 = input.charCodeAt(i++);
 
                enc1 = chr1 >> 2;
                enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
                enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
                enc4 = chr3 & 63;
 
                if (isNaN(chr2)) {
                    enc3 = enc4 = 64;
                } else if (isNaN(chr3)) {
                    enc4 = 64;
                }
 
                output = output +
                    keyStr.charAt(enc1) +
                    keyStr.charAt(enc2) +
                    keyStr.charAt(enc3) +
                    keyStr.charAt(enc4);
                chr1 = chr2 = chr3 = "";
                enc1 = enc2 = enc3 = enc4 = "";
            } while (i < input.length);
 
            return output;
        },
 
        decode: function (input) {
            var output = "";
            var chr1, chr2, chr3 = "";
            var enc1, enc2, enc3, enc4 = "";
            var i = 0;
 
            // remove all characters that are not A-Z, a-z, 0-9, +, /, or =
            var base64test = /[^A-Za-z0-9\+\/\=]/g;
            if (base64test.exec(input)) {
                alert("There were invalid base64 characters in the input text.\n" +
                    "Valid base64 characters are A-Z, a-z, 0-9, '+', '/',and '='\n" +
                    "Expect errors in decoding.");
            }
            input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
 
            do {
                enc1 = keyStr.indexOf(input.charAt(i++));
                enc2 = keyStr.indexOf(input.charAt(i++));
                enc3 = keyStr.indexOf(input.charAt(i++));
                enc4 = keyStr.indexOf(input.charAt(i++));
 
                chr1 = (enc1 << 2) | (enc2 >> 4);
                chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
                chr3 = ((enc3 & 3) << 6) | enc4;
 
                output = output + String.fromCharCode(chr1);
 
                if (enc3 != 64) {
                    output = output + String.fromCharCode(chr2);
                }
                if (enc4 != 64) {
                    output = output + String.fromCharCode(chr3);
                }
 
                chr1 = chr2 = chr3 = "";
                enc1 = enc2 = enc3 = enc4 = "";
 
            } while (i < input.length);
 
            return output;
        }
    };
}).
factory('TokenHandler', [ '$http', '$cookieStore', 'Base64', function($http, $cookieStore, Base64) {
    var tokenHandler = {};
    var token = 'none';

    tokenHandler.set = function( newToken ) {
        token = newToken;
    };

    tokenHandler.get = function() {
        return token;
    };

    tokenHandler.getCredentials = function ( username, secret) {
        // Check if token is registered in cookies
        if ( (typeof $cookieStore.get('username') !== 'undefined') && 
             (typeof $cookieStore.get('digest') !== 'undefined') && 
             (typeof $cookieStore.get('b64nonce') !== 'undefined') && 
             (typeof $cookieStore.get('created') !== 'undefined') ) 
        {
            // Define variables from cookie cache
            var username = $cookieStore.get('username');
            var digest = $cookieStore.get('digest');
            var b64nonce = $cookieStore.get('b64nonce');
            var created = $cookieStore.get('created');
        }
        else
        {
            // Create token for backend communication
            var seed = Math.floor( Math.random() * 1000 )+'';
            // Encode seed in MD5
            var nonce = CryptoJS.MD5( seed ).toString(CryptoJS.enc.Hex);

            // Creation time of the token
            var created = formatDate(new Date());

            // Generating digest from secret, creation and seed
            var hash = CryptoJS.SHA1(nonce+created+secret);
            var digest = hash.toString(CryptoJS.enc.Base64);

            // Base64 Encode digest
            var b64nonce = Base64.encode(nonce);

            // Save token in cookies
            $cookieStore.put('username', username);
            $cookieStore.put('digest', digest);
            $cookieStore.put('nonce', b64nonce);
            $cookieStore.put('created', created);
        }

        // Return generated token
        return 'UsernameToken Username="'+username+'", PasswordDigest="'+digest+'", Nonce="'+b64nonce+'", Created="'+created+'"';
    };

    // Token Reinitializer
    tokenHandler.clearCredentials = function () {
        // Clear token from cache
        $cookieStore.remove('username');
        $cookieStore.remove('digest');
        $cookieStore.remove('nonce');
        $cookieStore.remove('created');

        // Clear token variable
        delete $http.defaults.headers.common['X-WSSE'];
    };

    // Token wrapper for resource actions
    tokenHandler.wrapActions = function( resource, actions ) {
        var wrapperResource = resource;

        for ( var i=0; i < actions.length; i++ ) {
            tokenWrapper( wrapperResource, actions[i] );
        }

        return wrapperResource;
    };

    // Token wrapper
    var tokenWrapper = function ( resource, action ) {
        resource['_'+action] = resource[action];
        resource[action] = function ( data, success, error ) {
            $http.defaults.headers.common['X-WSSE'] = tokenHandler.getCredentials('admin', 'adminpass');
            return resource['_'+action](
                data,
                success,
                error
            );
        };
    };

    // Date formater to UTC
    var formatDate = function (d) {
        // Padding for date creation
        var pad = function (num) {
            return ("0" + num).slice(-2);
        };

        return [d.getUTCFullYear(), 
                pad(d.getUTCMonth() + 1), 
                pad(d.getUTCDate())].join("-") + "T" + 
               [pad(d.getUTCHours()), 
                pad(d.getUTCMinutes()), 
                pad(d.getUTCSeconds())].join(":") + "Z";
    };

    return tokenHandler;
}]).
factory('Hello', ['$resource', 'TokenHandler', function($resource, tokenHandler) {
    var resource = $resource('/angular/app_dev.php/api/hello');
    resource = tokenHandler.wrapActions(resource, ['get']);
    return resource;
}]).
factory('Todos', ['$resource', 'TokenHandler', function($resource, tokenHandler){
    var resource = $resource('/angular/app_dev.php/api/todos', {}, { 
        query: {method:'GET', params:{}, isArray:true}
    });
    resource = tokenHandler.wrapActions(resource, ['get', 'query']);
    return resource;
}]).
factory('Todo', ['$resource', 'TokenHandler', function($resource, tokenHandler){
    var resource = $resource('/angular/app_dev.php/api/todo', {}, { 
        update: {method:'PUT'},
    });
    resource = tokenHandler.wrapActions(resource, ['get', 'update']);
    return resource;
}]).
  value('version', '0.1');
