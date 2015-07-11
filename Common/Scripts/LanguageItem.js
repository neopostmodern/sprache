function LanguageItem(text, scope, position, type) {
    this.Text = text;
    this.Scope = scope;
    this.Position = position;
    this.Type = type;
}

LanguageItem.prototype.Stringify = function () {
    return JSON.stringify(this);
};

LanguageItem.prototype.Populate = function (data) {
    if (typeof(data) === "string") 
        data = JSON.parse(data);
    
    this.Text = data.Text;
    this.Scope = data.Scope;
    this.Position = data.Position;
    this.Type = data.Type;
    return this;
};