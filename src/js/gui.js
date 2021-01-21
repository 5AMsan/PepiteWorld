/**
 * Modal
 */
import MicroModal from 'micromodal';
window.initMicroModal = function() {
    MicroModal.init({
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


jQuery(document).ready(function($) {

    // observe loader class mutation
    // var $el = $(".loader-wrapper");
    // var observer = new MutationObserver(function(mutations) {
    //     mutations.forEach(function(mutation) {
    //         if (mutation.attributeName === "class") {
    //             var attributeValue = $(mutation.target).prop(mutation.attributeName).split(' ');
    //             console.log("Class attribute changed to:", attributeValue);
    //             if (attributeValue.indexOf('active') !== -1) {
    //                 console.log("in");
    //                 window.LoaderInterval = setInterval(() => {
    //                     window.loaderLoadedFont = window.loaderLoadedFont == window.loaderFonts.length ? 0 : window.loaderLoadedFont + 1
    //                     console.log(window.loaderFonts.length, window.loaderLoadedFont)
    //                     $('.loader-wrapper .loader').css('font-family', window.loaderFonts[window.loaderLoadedFont]);
    //                 }, 100);
    //             } else {
    //                 clearInterval(window.LoaderInterval);
    //             }
    //         }
    //     });
    // });
    // observer.observe($el[0], {
    //     attributes: true
    // });

    let padding = 32;
    let menuWidth = $(".draggable").width() / 2 + $('.pepite-tab-container nav.secondary').width() + parseInt($('#post-projet .content').css('padding-left').replace(/\D/g, ''), 10) + parseInt($('#post-projet .content').css('margin-left').replace(/\D/g, ''), 10);
    let constraint = [
        // menuWidth + padding,
        padding,
        padding,
        $('#post-projet .content').width() - padding,
        $('#post-projet .content').height() - padding
    ];
    $(".draggable").draggable({ containment: constraint, scroll: false, stack: 'img', distance: 0 });
    window.randomizeDrag($);

    $(document).on('click', '[data-modal-close], [data-micromodal-close]', function() {
        // var modalId = $(this).data('modal-close') ? $(this).data('modal-close') : $(this).data('micromodal-close');
        var modalId = $(this).prev('.modal').attr('id');
        MicroModal.close(modalId);
    });

    // Risograpghie scroll watch
    $(window).scroll(function() {
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
    if ($('.fontsampler-wrapper').length > 1) $('.fontsampler-wrapper.initialized').children().css('pointer-events', 'none');

    // Activate custom button for fontsampler actions
    // if ($('.fontsampler-wrapper').length == 1) {
    //     $('#post-font').on('click', '.infobar .support-us', function(){
    //         var win = window.open($('.fontsampler-wrapper.initialized .fontsampler-ui-block[data-block=buy] a').attr('href'), '_blank');
    //         win.focus();
    //     })
    //     $('#post-font').on('click', '.infobar .download', function(){
    //         var win = window.open($('.fontsampler-wrapper.initialized .fontsampler-ui-block[data-block=specimen] a').attr('href'), '_blank');
    //         win.focus();
    //     })
    // } 



});