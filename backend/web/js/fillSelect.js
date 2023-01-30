function fillSelect(selector, values, clear) {
    var select = $(selector);
    if(clear) {
        select.html('');
    }
    values.forEach(function(item) {
        var o = new Option(item.value, item.key);
        select.append(o);
    });
    select.trigger('change');
}

