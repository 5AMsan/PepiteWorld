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

import Glide from '@glidejs/glide';

jQuery(function($) {

    // Vimeo 
    window.vimeoPlayers = [];

    // Glide carousel
    window.Glides = {};
    if ($('.glide').length) initGlide();

    // Fontsampler
    if ($('.fontsampler-wrapper').length) initFontsampler();

    // Handle double click on draggable
    $("body").on('dblclick', '.draggable', function(e) {
        e.stopPropagation();
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

        // reflow Glide on content (static) page
        if (targetContainer == "risographie" && window.Glides[targetContainer]) {
            window.Glides[targetContainer].forEach(function(glide) {
                // ise timeout to refresh after tab size changed
                setTimeout(() => { glide.update() }, 2000);
            })
        }

        // Hash URL only
        if (location.hash) {
            $(`#post-${targetContainer} > div.content`).animate({ scrollTop: $(location.hash).offset().top - 200 }, 1000);
            return true;
        }

        // Update  browser title
        if ($(e.target).text()) document.title = $(e.target).text();

        // Is some project to unload ?
        if (link != '/direction-artistique/' && $('#projet').data('loaded') != '/direction-artistique/') {
            unloadSubContainer('projet');
        } else if (link != '/editions/' && $('#edition').data('loaded') != '/editions/') {
            unloadSubContainer('edition');
        }

        // skip ajax load if already loaded
        if ($(`#${targetContainer}`).data('loaded') == link) {
            return checkbox.prop('checked', true);
        }

        // Manage page load
        $.ajax({ url: link })
            .success(response => {

                $(`#${targetContainer}`).data('loaded', link)
                    .html(response.content)
                    .fadeIn('fast', function(e) {
                        $(this).find('.content').scrollTop(0)
                    })
                    // Content is loaded, run label animation
                checkbox.prop('checked', true);

                switch (targetContainer) {
                    case 'projet':
                        var init = location.pathname == '/direction-artistique/' ? initDrag() : [window.initMicroModal(), initInfobar(), initGlide()];
                        break;
                    case 'font':
                        initFontsampler();
                        break;
                    case 'edition':
                        var init = location.pathname == '/edition/' ? true : [window.initMicroModal(), initInfobar(), initGlide(), initWpShopify()];
                        break;
                        // case 'risographie':
                        //     var init = [initGlide()];
                        //     break;
                    case 'home':
                        var init = [window.initMicroModal()];
                        break;
                }

            })
            .fail(function() {
                console.error(`failed to load content in ${$targetContainer}`);
            })

        return true;

    });

    // Allow tab update from history
    window.onpopstate = (e) => {
        // trigger click to update tabs state
        $(`label[data-link="${location.href}"] .tab-title a`).trigger('click');
    };

    async function unloadSubContainer(el) {
        initInfobar(true);
        var element = $(`#${el}`);
        element.html('');
        element.data('loaded', 'false');
        element.css('display', 'block');
    }

    function initWpShopify() {
        wp.hooks.doAction('wpshopify.render');
    }

    function initInfobar(reset) {
        if (reset)
            $('.infobar').addClass('no-nav');
        else
            $('.infobar').removeClass('no-nav');
    }

    function initVimeo($el, index) {
        var context = $el.parents(".content-wrapper").attr('id');
        var iframe = $el.get(0);
        var player = new Vimeo.Player(iframe, {
            autopause: true,
            autoplay: true,
            background: true,
            byline: false,
            controls: false,
            loop: true,
            portrait: false,
            title: false
        });
        if (!window.vimeoPlayers[context]) window.vimeoPlayers[context] = []
        window.vimeoPlayers[context][index] = player;

        /**
         * Buffer events can't be accessed from stopped players (aytoplay=0)
         * Try to init play from Glide / not working 
         * Try from player ready event
         */
        // player.on('bufferend', function() {
        player.ready()
            .then(function() {
                //player.setVolume(0);
                // is player on active slide ? Both test are working
                // if (player.element.parentNode.parentNode.classList.contains('glide__slide--active')) {
                if ($el.parents('.glide__slide--active').length > 0) {
                    player.getPaused().then(function(paused) {
                        if (paused) {
                            player.play();
                        }
                    }).catch(function(e) {
                        console.error(e);
                    });
                }
            });

        return player;
    }

    function initGlide() {
        // Disable Glide
        var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
        if (width < 813) return;

        // let glideCar;
        let sliders = document.querySelectorAll('.glide')
        for (var i = 0; i < sliders.length; i++) {
            try {
                const glideCar = new Glide(sliders[i], {
                    type: 'carousel',
                    gap: 0,
                    perView: 1,
                    focusAt: 'center'
                })
                glideCar.on('build.after', function() {
                        // Store Glides references
                        if (!window.Glides[$(glideCar.selector).parents('.content-wrapper').attr('id')]) {
                            window.Glides[$(glideCar.selector).parents('.content-wrapper').attr('id')] = [];
                        }
                        window.Glides[$(glideCar.selector).parents('.content-wrapper').attr('id')].push(glideCar);
                        let $context = $(glideCar.selector).parent();
                        $context.parent().find('[data-slide-total]').attr("data-slide-total", $context.find('.glide__slide:not(.glide__slide--clone)').length);
                    })
                    .on('run.after', function(e) {
                        $('[data-slide-current]').attr("data-slide-current", glideCar.index + 1)
                    })
                    .on('run.before', function(e) {
                        // pause all players while exiting
                        var context = $(glideCar.selector).parents(".content-wrapper").attr('id')
                        if (!window.vimeoPlayers[context]) return;
                        var player = window.vimeoPlayers[context][glideCar.index];
                        if (!player) return;
                        try {
                            player.pause();
                        } catch (e) {
                            //console.error(e);
                        }
                    })
                    .on('run.after', function(e) {
                        // pause current player while entering
                        var context = $(glideCar.selector).parents(".content-wrapper").attr('id')
                        if (!window.vimeoPlayers[context]) return;
                        var player = window.vimeoPlayers[$(glideCar.selector).parents(".content-wrapper").attr('id')][glideCar.index];
                        if (!player) return;
                        try {
                            player.play();
                        } catch (e) {
                            // not a player, whatever
                            console.error(e);
                        }
                    })
                    .on('mount.after', function() {
                        // initialize Vimeo objects
                        // $(glideCar.selector).find(`.glide__slides > li`).each(function(e) {
                        $(glideCar.selector).find(`.glide__slides > li:not(.glide__slide--clone)`).each(function(e) {
                            setTimeout(() => {
                                var iframe = $(this).find('iframe')
                                if (typeof Vimeo != 'undefined' && iframe.length) {
                                    initVimeo(iframe, e)
                                }
                            }, 750);
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
                if ($('.fontsampler-wrapper').length > 1) {
                    $('.fontsampler-wrapper.initialized').children().css('pointer-events', 'none');
                    $('.type-tester__content').attr('contenteditable', false);
                }
            } catch (e) {
                console.error('Fontsampler error: ', e);
            }
        }
    }

    function initDrag() {
        if (!$(".draggable") || $(".draggable").length == 0) return;
        try {
            // $(".draggable").draggable({ containment: "#post-projet .content", scroll: false, stack: 'img', distance: 0 })
            // window.randomizeDrag($);
            /* Delay Draggable init*/
            $('#post-projet .content').css('opacity', 0);
            setTimeout(function(){
                $(".draggable").draggable({ containment: "#post-projet .content", scroll: false, stack: 'img', distance: 0 })
                window.randomizeDrag($);
                $('#post-projet .content').animate({opacity:1}, 500);
            }, 5000);
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