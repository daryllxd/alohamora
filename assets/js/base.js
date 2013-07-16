$.fn.center = function () {
    this.css("position","absolute");
    this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + 
        $(window).scrollTop()) + "px");
    //    this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + 
    //        $(window).scrollLeft()) + "px");    
    return this;
}
$.validator.addMethod(
    "regex",
    function(value, element, regexp) {
        var re = new RegExp(regexp);
        console.log(re.test(value))
        return this.optional(element) || re.test(value);
    },
    "Please check your input."
    );
        
$.validator.addMethod(
    'in_array',
    
    function(value, element, array) {
        return  (array.indexOf(value) > -1);
    },
    "Please check your input."
    );
        
$.fn.shake = function(intShakes, intDistance, intDuration) {
    this.each(function() {
        $(this).css("position","relative"); 
        for (var x=1; x<=intShakes; x++) {
            $(this).animate({
                left:(intDistance*-1)
            }, (((intDuration/intShakes)/4)))
            .animate({
                left:intDistance
            }, ((intDuration/intShakes)/2))
            .animate({
                left:0
            }, (((intDuration/intShakes)/4)));
        }
    });
    return this;
};

