function toggleSelection(e){
    var el = '#' + e.data;
    console.log(arguments);
    if (this.checked) {
        $(el).attr('disabled', 'disabled');
    } else {
        $(el).removeAttr('disabled');
    }
}