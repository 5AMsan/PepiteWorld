/**
 * Drag
 */
// jQuery(function($) {
//     $(".draggable").draggable();
// });

/**
 * Modal
 */
import MicroModal from 'micromodal';
window.initMicroModal = function() {
    MicroModal.init({
        onClose: modal => modal.previousElementSibling.style['pointer-events'] = 'inherit',
        openTrigger: 'data-modal-open',
        closeTrigger: 'data-modal-close',
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

// Font loader
window.loaderFonts = [
    'MocheRegular',
    'PipeLight',
    'BendicionRegular'
]
window.loaderLoadedFont = 1;
window.LoaderInterval;


// $(document).on('classChanged', '.loader-wrapper .loader', () => {
//     console.log('ok')
//     if (window.LoaderInterval) {
//         window.LoaderInterval = setInterval(() => {
//             window.loaderLoadedFont = window.loaderLoadedFont == window.loaderFonts.length ? 0 : window.loaderLoadedFont + 1
//             console.log(window.loaderFonts.length, window.loaderLoadedFont)
//             $('.loader-wrapper .loader').css('font-family', window.loaderFonts[window.loaderLoadedFont]);
//         }, 100);
//     } else {
//         clearInterval(window.LoaderInterval);
//     }
// })

jQuery(document).ready(function($) {

    // observe loader class mutation
    var $el = $(".loader-wrapper");
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === "class") {
                var attributeValue = $(mutation.target).prop(mutation.attributeName).split(' ');
                console.log("Class attribute changed to:", attributeValue);
                if (attributeValue.indexOf('active') !== -1) {
                    console.log("in");
                    window.LoaderInterval = setInterval(() => {
                        window.loaderLoadedFont = window.loaderLoadedFont == window.loaderFonts.length ? 0 : window.loaderLoadedFont + 1
                        console.log(window.loaderFonts.length, window.loaderLoadedFont)
                        $('.loader-wrapper .loader').css('font-family', window.loaderFonts[window.loaderLoadedFont]);
                    }, 100);
                } else {
                    clearInterval(window.LoaderInterval);
                }
            }
        });
    });
    observer.observe($el[0], {
        attributes: true
    });

    let padding = 32;
    let menuWidth = $(".draggable").width() / 2 + $('.pepite-tab-container nav.secondary').width() + parseInt($('#post-projet .content').css('padding-left').replace(/\D/g, ''), 10) + parseInt($('#post-projet .content').css('margin-left').replace(/\D/g, ''), 10);
    let constraint = [
        menuWidth + padding,
        padding,
        $('#post-projet .content').width() - padding,
        $('#post-projet .content').height() - padding
    ];
    $(".draggable").draggable({ containment: constraint, scroll: false });
    window.randomizeDrag($);

    $(document).on('click', '[data-modal-open]', function() {
        var modalId = $(this).data('modal-open');
        MicroModal.show(modalId);
        $('body .glide').css('pointer-events', 'none');
        $('body .entry-content').addClass('close-cursor')
            .on('click', function() {
                MicroModal.close(modalId);
                $('body .entry-content').toggleClass('close-cursor')
                $('body .glide').css('pointer-events', 'unset');
            });
    })
    $(document).on('click', '[data-modal-close]', function() {
        MicroModal.close($(this).data('modal-close'));
    })

});

/** CSS specials  */
// allow click on font title on `fonderie`
jQuery(document).ready(function($) {
    $('.fontsampler-wrapper.initialized').children().css('pointer-events', 'none');
});

/** Populate Home FX */
// jQuery(document).ready(function($) {
//     $('#home strong').each(i => {
//         // console.log($(this).text())
//         // if ( $(this).html().toLowerCase() == "studio") {
//         //     $(this).append('<div class="studio-fx"><div class="studio-fx__bg"></div></div>')
//         // }
//     })
// });