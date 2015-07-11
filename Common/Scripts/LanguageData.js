function LanguageData(data, scope, rating) {    
    this.Data = data || [];
    this.Scope = scope;
    this.Rating = rating;
}

LanguageData.prototype.Populate = function (text) {
    var data = JSON.parse(text);
    this.Data = data.Data;
    this.Scope = data.Scope;
    this.Rating = data.Rating;
    return this;
};

LanguageData.prototype.ObservablePopulate = function (text) {
    var data = JSON.parse(text);
    $.observable(this).setProperty('Data', data.Data);
    $.observable(this).setProperty('Scope', data.Scope);
    $.observable(this).setProperty('Rating', data.Rating);
};

LanguageData.prototype.Stringify = function () {
    return JSON.stringify(this);
}

LanguageData.prototype.Send = function (callback, errorHandler) {
    $.ajax({
        url : App.GetUrlForJsonApi() + App.GetCallForSaveLanguageData(),
        method : 'post',
        data : { "Data" : this.Stringify() }
    }).success(function (data) {
        var result = JSON.parse(data);
        if (result.success) {
            callback();
        } else {
            errorHandler(result.error);
        }
    });
};