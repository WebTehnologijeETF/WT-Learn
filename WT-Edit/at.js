//samo da vidim prihvata li dobro parametre...
//izgleda da neće proći jer se cijeli HTML ne može proslijediti kao param :(
//možda u neki fajl spremit pa učitati...
var system = require('system');
if (system.args.length === 1) {
    console.log('Try to pass some args when invoking this script!');
} else {
    system.args.forEach(function (arg, i) {
        console.log(i + ': ' + arg);
    });
}
phantom.exit();
