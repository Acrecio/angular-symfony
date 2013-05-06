'use strict';

/* Services */
function pad(num) {
    return ("0" + num).slice(-2);
}

function formatDate(d) {
    return [d.getUTCFullYear(), 
            pad(d.getUTCMonth() + 1), 
            pad(d.getUTCDate())].join("-") + "T" + 
           [pad(d.getUTCHours()), 
            pad(d.getUTCMinutes()), 
            pad(d.getUTCSeconds())].join(":") + "Z";
}

// Demonstrate how to register services
// In this case it is a simple value service.
angular.module('myApp.services', ['ngCookies']).
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
  factory('Auth', ['$cookieStore', '$http', 'Base64', function($cookieStore, $http, Base64) {
    $http.defaults.headers.common['X-WSSE'] = 'UsernameToken Username="'+$cookieStore.get('username')+'", PasswordDigest="'+$cookieStore.get('digest')+'", Nonce="'+$cookieStore.get('nonce')+'", Created="'+$cookieStore.get('created')+'"';

    return {
      setCredentials: function(username, secret) {
        var seed = Math.floor( Math.random() * 1000 )+'';
        var nonce = CryptoJS.MD5( seed ).toString(CryptoJS.enc.Hex);

        var now = new Date();

        var created = formatDate(new Date());

        var hash = CryptoJS.SHA1(nonce+created+secret);
        var digest = hash.toString(CryptoJS.enc.Base64);

        var b64nonce = Base64.encode(nonce);

        $http.defaults.headers.common['X-WSSE'] = 'UsernameToken Username="'+username+'", PasswordDigest="'+digest+'", Nonce="'+b64nonce+'", Created="'+created+'"';

        $cookieStore.put('username', username);
        $cookieStore.put('digest', digest);
        $cookieStore.put('nonce', b64nonce);
        $cookieStore.put('created', created);

        $http.get('http://localhost/webservices/app_dev.php/api/hello').success(function(data){
          console.log(data);
        })
      },
      clearCredentials: function() {
        $cookieStore.remove('username');
        $cookieStore.remove('digest');
        $cookieStore.remove('nonce');
        $cookieStore.remove('created');

        delete $http.defaults.headers.common['X-WSSE'];

      }
    }
  }]).
  value('version', '0.1');
