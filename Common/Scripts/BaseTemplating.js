$.views.converters({
    getValue: function (obj) {
        for (var key in obj) 
            return obj[key];
    }
});
$.views.helpers({
    getValue: function (obj) {
        for (var key in obj) 
            return obj[key];
    },
    firstUpper: function(str) {
        return str && str.length > 0 ? str.substr(0, 1).toUpperCase() + str.substr(1, str.length) : str;
    },
    abs: function(number) {
        return Math.abs(number);
    },
    log: function (value) {
        console.dir(value);
        return value;
    }
});