/**
 * Modal
 */
import MicroModal from 'micromodal';
window.initMicroModal = function() {
    var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
    MicroModal.init({
        onShow: modal => {
            if (modal.classList.contains('mentions-legales') ||
                modal.classList.contains('politique-de-confidentialite') ||
                width < 767) {
                document.body.appendChild(modal);
                document.body.appendChild(document.getElementById('modal-overlay'));
            }
        },
        onClose: modal => modal.previousElementSibling.style['pointer-events'] = 'inherit',
        openTrigger: 'data-modal-open',
        //closeTrigger: 'data-modal-close',
        openClass: 'is-open',
        disableScroll: false,
        disableFocus: false,
        awaitOpenAnimation: false,
        awaitCloseAnimation: false,
        debugMode: false
    });
}
window.initMicroModal();

window.randomizeDrag = function($) {
    var $element = $('#post-projet .content').css('position', 'relative'),
        docWidth = $element.width(),
        docHeight = $element.height(),
        docPosition = $element.offset(),
        docX = Math.floor(docPosition.left), // + parseInt($element.css('paddingLeft'), 10)),
        docY = Math.floor(docPosition.top); // + parseInt($element.css('paddingTop'), 10));

    $(".draggable").each(function() {
        var divWidth = $(this).width(),
            divHeight = $(this).height(),
            maxX = docWidth - divWidth,
            maxY = docHeight - divHeight,
            minX = Math.floor($('#projet-nav').width() + divWidth / 2);
        $(this).css({
            position: 'absolute',
            left: Math.max(minX, Math.floor(Math.random() * maxX)),
            top: Math.floor(Math.random() * maxY)
        });
    })
}

window.fadeProjetIn = function($) {
    /* Delay Draggable and Infobar init*/
    $('#post-projet .content').css('opacity', 0);
    setTimeout(function(){
        $(".draggable").draggable({ containment: "#post-projet .content", scroll: false, stack: 'img', distance: 0 })
        window.randomizeDrag($);
        $('#post-projet .content').animate({opacity:1}, 500);
    }, 500);
}

jQuery(document).ready(function($) {

    let padding = 32;
    let menuWidth = $(".draggable").width() / 2 + $('.pepite-tab-container nav.secondary').width() + parseInt($('#post-projet .content').css('padding-left').replace(/\D/g, ''), 10) + parseInt($('#post-projet .content').css('margin-left').replace(/\D/g, ''), 10);
    let constraint = [
        // menuWidth + padding,
        padding,
        padding,
        $('#post-projet .content').width() - padding,
        $('#post-projet .content').height() - padding
    ];
    
    /* Delay Draggable and Infobar init*/
    fadeProjetIn($);
    // $('#post-projet .content').css('opacity', 0);
    // setTimeout(function(){
    //     $(".draggable").draggable({ containment: "#post-projet .content", scroll: false, stack: 'img', distance: 0 })
    //     window.randomizeDrag($);
    //     $('#post-projet .content').animate({opacity:1}, 500);
    // }, 500);

    $(document).on('click', '[data-modal-close], [data-micromodal-close]', function() {
        // var modalId = $(this).data('modal-close') ? $(this).data('modal-close') : $(this).data('micromodal-close');
        var modalId = $(this).prev('.modal').attr('id');
        MicroModal.close(modalId);
    });

    // Risograpghie scroll watch
    $(window).on("scroll", function() {
        var winTop = $(this).scrollTop();
        var $targets = $('#risographie .content h2[id]');
        $.each($targets, function(item) {
            if ($(item).position().top <= winTop)
                var name = $(this).attr('id');
            $(`.infobar a.secondary[href=#${name}]`).css('font-family', 'pipemedium')
        });
    });

    /** FontSampler  */
    // allow click on font title on `fonderie`
    if ($('.fontsampler-wrapper').length > 1) {
        $('.fontsampler-wrapper.initialized').children().css('pointer-events', 'none');
        $('.type-tester__content').attr('contenteditable', false);
    }

    // Projet NAV close button
    $('body').on('click', '#projet-nav', function(e) {
        e.preventDefault();
        if (e.target.tagName.toLowerCase() != "nav") {
            return
        }
        var $menu = $(this).toggleClass("closed");
        $menu.prev('.infobar').toggleClass('open');
        if ($menu.data('open')) {
            $menu.data('open', false)
                .find('ul')
                .hide("slide", { direction: "left" });
            // $menu.prev('.infobar').removeClass('open')
        } else {
            $menu.data('open', true)
                .find('ul')
                .show("slide", { direction: "right" });
            // $menu.prev('.infobar').addClass('open')
        }
    });

    // Default to EUR
    wp.hooks.addFilter('misc.pricing.defaultCurrencyCode', 'wpshopify', function(defaultCode) {
        return 'EUR';
    });

    // Animation Paragraph Noms
    $('#c-noms__text').appendTo($('#post-home .content'));
    $('.c-noms__title').on("mouseenter", function() {
        animateParagraph(true);
        $('#c-noms__text').show()
    }).on("mouseleave", function() {
        $('#c-noms__text').hide()
        animateParagraph(false);
    });
});