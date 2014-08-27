(function ($, window, count) {
    
    var cache = {},
    methods = {
        init: function (opts) {
            var T = this;
            if (!T.length) {
                // There is no object, return
                return T;
            }
            if (T.length > 1) {
                // The selector matched more than one object, apply this function each individually
                T.each(function() {
                    $(this).serverView(opts);
                });
                return T;
            }
            T.c = ++count;
            T.attr({'data-serverviewid': T.c});
            var s = $.extend({
                maxFiles: 100,
                path: null,
                position: false,
                randomize: false,
                startAt: 0
            }, opts);
            
            if (!s.path) {
                // The user hasn't specified a path to the server script
                $.error('Please specify a path to the script that fishes the images');
            }
            
            $.ajax({
                url: s.path,
                type: 'post',
                dataType: 'json',
                data: {limit: s.maxFiles, startat: s.startAt, randomize: s.randomize}
            }).done(function (e) {
                var paths = e.data,
                len = paths.length;
                for (var i = 0; i < len; i++) {
                    var img = new Image();
                    img.svindex = i;
                    img.onload = function () {
                        var vi = this.svindex;
                        T.append('<div class="imgcontainer" id="imgcontainer_' + T.c + '_' + vi + '">' + this.outerHTML + '</div>');
                        if (s.position) {
                            // The user wants us to position the images in the square
                            $('#imgcontainer_' + T.c + '_' + vi).fillView({width: 200, height: 200});
                        }
                    };
                    img.src = paths[i];
                }
            }).fail(function (e) {
                // There was an unexpected error
                $.error('An unexpected error occurred: ' + e.responseText);
            });
            
            return T;
        }
    };

    /**
     * Get this object from the cache
     * @param {object(jQuery)} elem The object to test
     * @returns {object(jQuery)} Either the jQuery object from the cache, or elem if a cache entry does not exist
     */
    function getThis(elem) {
        var id = elem.data('serverviewid');
        return id ? cache[id] : elem;
    }

    
    $.fn.serverView = function(methodOrOpts) {
        var T = getThis(this);
        if (methods[methodOrOpts]) {
            // The first option passed is a method, therefore call this method
            return methods[methodOrOpts].apply(T, Array.prototype.slice.call(arguments, 1));
        } else if (Object.prototype.toString.call(methodOrOpts) === '[object Object]' || !methodOrOpts) {
            // The default action is to call the init function
            return methods.init.apply(T, arguments);
        } else {
            // The user has passed us something dodgy, throw an error
            $.error(['The method ', methodOrOpts, ' does not exist'].join(''));
        }
    };
    
})(jQuery, this, 0);