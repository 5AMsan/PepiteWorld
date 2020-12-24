/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */

// ( function() {
//     const contentRoot = document.querySelectorAll( '#page > .pepite-tab-container' )[0];
//     const checks = contentRoot.querySelectorAll( 'input' );
//     const labels = contentRoot.querySelectorAll( 'input + label' );
//     labels.forEach(item => { 
//         item.addEventListener("click", (e) => {
//             linkCapture(e);
//         });
//     });
// } )();

// function hasSomeParentTheClass(element, classname) {
//     if (element.className.split(' ').indexOf(classname) >= 0) return true;
//     return element.parentNode && hasSomeParentTheClass(element.parentNode, classname);
// }

jQuery(function($) {

    // Vimeo 
    window.vimeoPlayers = [];


    // Glide carousel
    if ($('.glide').length) initGlide();

    // Fontsampler
    if ($('.fontsampler-wrapper').length) initFontsampler();

    // var loader = $('.loader-wrapper')

    // Handle double click on draggable
    $("body").on('dblclick', '.draggable', function(e) {
        return $(`#item-${$(this).data('id')} a`).trigger('click');;
    });

    // Capture click on labels, links are tracked from within labels 
    $('body').on('click', '#page > .pepite-tab-container a:not([target]):not([href^=mailto])', (e) => {

        // stop link click and label animation (checkbox won't be checked)
        e.preventDefault();

        try {
            var url = new URL($(e.target).data('link') || e.target.href);
        } catch (e) {
            return;
        }

        var link = url.pathname;
        var targetContainer = $(e.target).attr('rel');
        var checkbox = $(`#item-${targetContainer}`);

        // Update browser history
        history.pushState(null, null, url.href);

        // Hash
        if (location.hash) {
            $(`#post-${targetContainer} > div.content`).animate({ scrollTop: $(location.hash).offset().top - 200 }, 1000);
            return true;
        }

        // Is some project to unload ?
        if (link != '/direction-artistique/' && $('#projet').data('loaded') != '/direction-artistique/') {
            unloadSubContainer('projet');
        }
        if (link != '/editions/' && $('#edition').data('loaded') != '/editions/') {
            unloadSubContainer('edition');
        }

        // skip ajax load if already loaded
        if ($(`#${targetContainer}`).data('loaded') == link) {
            return checkbox.prop('checked', true);
        }

        // Manage page load
        //loader.addClass('active').trigger('classChanged');
        $.ajax({ url: link })
            .success(response => {
                $(`#${targetContainer}`).data('loaded', link)
                    .html(response.content)
                    .fadeIn('fast');
                //loader.removeClass('active').trigger('classChanged');
                // Content is loaded, run label animation
                checkbox.prop('checked', true);

                switch (targetContainer) {
                    case 'projet':
                        var init = location.pathname == '/direction-artistique/' ? initDrag() : [window.initMicroModal(), initGlide(), initInfobar()];
                        break;
                    case 'font':
                        initFontsampler();
                        break;
                    case 'edition':
                        var init = location.pathname == '/edition/' ? true : [window.initMicroModal(), initGlide(), initInfobar()];
                        break;
                    case 'home':
                        var init = [window.initMicroModal()];
                        break;
                }

            })
            .done(function() {
                //loader.removeClass('active').trigger('classChanged');
            });

        return true;

    });

    // Allow tab update from history
    window.onpopstate = (e) => {
        // trigger click to update tabs state
        $(`label[data-link="${location.href}"`).trigger('click');
    };

    function unloadSubContainer(el) {
        initInfobar(true);
        var element = $(`#${el}`);
        element.fadeOut('fast', function() {
            $(this).html('');
            $(this).data('loaded', 'false');
            setTimeout(() => {
                $(this).css('display', 'block');
            }, 1000);
        });
    }

    function initInfobar(reset) {
        if (reset)
            $('.infobar').addClass('no-nav');
        else
            $('.infobar').removeClass('no-nav');
    }

    function initVimeo($el, index) {

        var iframe = $el.get(0);
        var player = new Vimeo.Player(iframe, {
            autopause: true,
            autoplay: false,
            background: true,
            byline: false
        });

        window.vimeoPlayers[index] = player;
        player.on('bufferend', function() {
            // is player on active slide ?
            if (player.element.parentNode.parentNode.classList.contains('glide__slide--active')) {
                try {
                    player.play();
                } catch (e) {
                    console.error(e);
                }
            }
        });

        return player;
    }

    function initGlide(post) {
        // let glideCar;
        let sliders = document.querySelectorAll('.glide');
        for (var i = 0; i < sliders.length; i++) {
            try {
                const glideCar = new Glide(sliders[i], {
                    type: 'carousel',
                });
                glideCar.on('build.after', function() {
                        let context = $(glideCar.selector).parent();
                        context.parent().find('[data-slide-total]').attr("data-slide-total", context.find('.glide__slide:not(.glide__slide--clone)').length);
                    })
                    .on('run.after', function(e) {
                        $('[data-slide-current]').attr("data-slide-current", glideCar.index + 1);
                    })
                    .on('run.before', function(e) {
                        window.vimeoPlayers.forEach(player => {
                            try {
                                player.pause();
                            } catch (e) {
                                console.error(e);
                            }
                        });
                    })
                    .on('run.after', function(e) {
                        // try {
                        //     window.vimeoPlayers[glideCar.index].play();
                        // } catch (e) {}
                    })
                    .on('mount.after', function() {
                        // initialize Vimeo objects
                        $(`.glide__slides > li:not(.glide__slide--clone)`).each(function(e) {
                            iframe = $(this).find('iframe');
                            if (typeof Vimeo != 'undefined' && iframe.length) {
                                initVimeo(iframe, e);
                            }
                        });

                    })
                    .mount();
            } catch (e) {
                console.error('Glide error: ', e);
            }
        }
    }

    function initFontsampler() {
        if (typeof fontsamplerSetup === "function") {
            try {
                // offical Fontsampler function to init all font samplers
                fontsamplerSetup();
            } catch (e) {
                console.error('Fontsampler error: ', e);
            }
        }
    }

    function initDrag() {
        try {
            $(".draggable").draggable({ containment: "#post-projet .content", scroll: false });
            window.randomizeDrag($);
        } catch (e) {
            console.error('Drag error: ', e);
        }
    }

    // Back button
    $('body').on('click', '#post-projet button.back', function(e) {
        $('#post-projet .tab-title a').trigger('click')
    });
    $('body').on('click', '#post-font button.back', function(e) {
        $('#post-font .tab-title a').trigger('click')
    });
    $('body').on('click', '#post-edition button.back', function(e) {
        $('#post-edition .tab-title a').trigger('click')
    });
    $('#post-font').on('click', '.fontsampler-nav-item[data-link]', function(e) {
        document.location.href = $(this).children('a').attr('href');
    });
    $('#post-font').on('click', 'button.support-us', function(e) {
        $('#post-font .fontsampler-ui-block-buy a').trigger('click')
    });
    $('#post-font').on('click', 'button.specimen', function(e) {
        $('#post-font .fontsampler-ui-block-specimen a').trigger('click')
    });

});