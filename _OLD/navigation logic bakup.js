/**
 * Ajax loader logic
 */
var currentTab = false;
var currentProjetId = false;
var $loader;
jQuery(document).ready(function($){

    return; 
    
    // get resquested URL if coming from direct link to subpage
    var ajaxUrl = pepiteWorldGetURLParameter('pepiteajaxload') || '/home/';
    var sub = pepiteWorldGetURLParameter('sub') || false;
    pepiteWorldLoadPost(ajaxUrl, sub);


    // Main navigation override
    $.ajaxSetup({cache:false});
    $("input[name=pepite-tabbed-nav]").change(function(e){
        pepiteWorldLoadPost( $(this).data('link').replace(location.origin, '') );
        return false;
    });
    $("body").on('dblclick', '.draggable', function(e){
        e.preventDefault(); 
        console.log('Had an IMG dblclick', e);
        pepiteWorldLoadProjet( $(this) );
        return false;
    });
    $("body").on('click', '#projet-nav a', function(e){
        e.preventDefault();
        console.log('Had an A click', e);
        pepiteWorldLoadProjet( $(this) );
        return false;
    });
    $("body").on('click', '#edition-nav a', function(e){
        e.preventDefault();
        console.log('Had an A click', e);
        pepiteWorldLoadEdition( $(this) );
        return false;
    });
    $("body").on('click', '.fontsampler-nav-item[data-link]', (e)=>{
        e.preventDefault();
        var url = $(e.target).data('link');
        console.log( url );
        pepiteWorldLoadPost( url );
    });


    function pepiteWorldLoadPost(ajaxUrl, sub) {
        // Update History
        if(ajaxUrl!=window.location) {
            // if( ajaxUrl.indexOf('fontsampler')>-1 ) {
            //     var fakeUrl = `/fonderie/fontsampler/${ajaxUrl.split('=')[1]}`;
            //     window.history.pushState({path: fakeUrl}, '', fakeUrl);
            // } 
            // else
                window.history.pushState({path: ajaxUrl}, '', ajaxUrl);
        }

        // if currentTab is false, loading Home
        if (! currentTab ) {
            currentTab = $("#post-home div.content");
        } 
        else {
            // Hide current content
            currentTab.fadeOut('fast', function(){
                $(this).html('');
                // $loader.fadeIn('fast', (e)=>{console.log("IN"); $(this).toggleClass('active')});
            });
        }
        
        var tree = ajaxUrl.split('/').filter(e=>e!='');
        if ( ajaxUrl.indexOf('fontsampler')>-1 )
            var postName = tree[0].split('?')[0];
        else if ( tree.length == 1 )
            var postName = ajaxUrl.replace(/\//g,'');
        else
            var postName = tree[0];
        
        // check if prop already checked
        if ( tree.length == 1 && $("#item-"+postName).prop('checked') != true ) $("#item-"+postName).prop('checked', 'checked');
        // Update content
        // Projet displayed in Direction-Atistique are loaded in sub content part
        // Search add_action('the_content', 'pepite_world_drag_images');
        var container = $("#post-"+postName+" div.content");
        container.addClass('active-ajax-container')
        .hide()
        .load(ajaxUrl, function(){
            
            // Initiliaze Drag
            if( tree.length == 1 && postName == 'direction-artistique' ) {
                initializeDrag(container);
            }

            // load Fonsampler on Fonderie
            if( postName == 'fonderie') {
                fontsamplerSetup();
            }

            $(this).delay(750).fadeIn('fast');
            $loader.fadeOut('fast', (e)=>{$(this).toggleClass('active')});
            currentTab = container;
        });
    }

    function pepiteWorldLoadProjet(el) {
        
        var url = el.data('projet').replace(location.origin, '');

        // leaving item, removing styles
        if (currentProjetId) synchroniseMenuStyles(currentProjetId);
        currentProjetId = el.data('id');
        console.log("Found a current project ID", currentProjetId);

        // Update History
        if(ajaxUrl!=window.location) {
            window.history.pushState({path: url}, '', url);
            console.log("History changed", window.history);
        }

        var container = $('#post-direction-artistique .entry-content')
        .hide((e)=>{
            console.log("Container is hidden", container);
        })
        .load(url, function(){
            // entering item, adding styles
            synchroniseMenuStyles(currentProjetId)
            $('.infobar').removeClass('no-nav')
            $loader.fadeOut('fast', (e)=>{$(this).toggleClass('active')});
            $(this).fadeIn('fast', (e)=>{
                try {
                   glide
                   .on('build.after', function () {
                        $('[data-slide-total]').attr("data-slide-total", $('.glide__slide').length-2 );
                    })
                   .on('run.after', function () {
                        $('[data-slide-current]').attr("data-slide-current", glide.index+1);
                    })
                    .mount()
                } catch(e) {
                    console.log(e);
                }            
            });
        });
    }

    function pepiteWorldLoadEdition(el) {
        
        var url = el.data('projet').replace(location.origin, '');

        // leaving item, removing styles
        if (currentProjetId) synchroniseMenuStyles(currentProjetId);
        currentProjetId = el.data('id');
        console.log("Found a current project ID", currentProjetId);

        // Update History
        if(ajaxUrl!=window.location) {
            window.history.pushState({path: url}, '', url);
            console.log("History changed", window.history);
        }

        var container = $('#post-editions .entry-content')
        .hide((e)=>{
            console.log("Container is hidden", container);
        })
        .load(url, function(){
            // entering item, adding styles
            synchroniseMenuStyles(currentProjetId)
            $('.infobar').removeClass('no-nav')
            $loader.fadeOut('fast', (e)=>{$(this).toggleClass('active')});
            $(this).fadeIn('fast', (e)=>{
                try {
                   glide
                   .on('build.after', function () {
                        $('[data-slide-total]').attr("data-slide-total", $('.glide__slide').length-2 );
                    })
                   .on('run.after', function () {
                        $('[data-slide-current]').attr("data-slide-current", glide.index+1);
                    })
                    .mount()
                } catch(e) {
                    console.log(e);
                }            
            });
        });
    }

    // function pepiteWorldLoadFontSampler(el) {
        
    //     var url = el.data('projet').replace(location.origin, '');

    //     // leaving item, removing styles
    //     if (currentProjetId) synchroniseMenuStyles(currentProjetId);
    //     currentProjetId = el.data('id');

    //     // Update History
    //     if(ajaxUrl!=window.location) {
    //         window.history.pushState({path: url}, '', url);
    //         console.log("History changed", window.history);
    //     }

    //     var container = $('#post-direction-artistique .entry-content')
    //     .hide()
    //     .load(url, function(){
    //         // entering item, adding styles
    //         synchroniseMenuStyles(currentProjetId)
    //         $('.infobar').removeClass('no-nav')
    //         $loader.fadeOut('fast', (e)=>{$(this).toggleClass('active')});
    //         $(this).fadeIn('fast', (e)=>{
    //             try {
    //                glide
    //                .on('build.after', function () {
    //                     $('[data-slide-total]').attr("data-slide-total", $('.glide__slide').length-2 );
    //                 })
    //                .on('run.after', function () {
    //                     $('[data-slide-current]').attr("data-slide-current", glide.index+1);
    //                 })
    //                 .mount()
    //             } catch(e) {
    //                 console.log(e);
    //             }            
    //         });
    //     });
    // }

    // TODO: fiw hostroy shorten by navigation
    // manage history manipulation by user
    window.addEventListener('popstate', function(event) {
        // The popstate event is fired each time when the current history entry changes.
        //console.log(window.history);
        var nextUrl = event.state.path.replace(location.origin, '');
        pepiteWorldLoadPost(nextUrl);

        //event.preventDefault();

        // if (r == true) {
        //     // Call Back button programmatically as per user confirmation.
        //     history.back();
        //     // Uncomment below line to redirect to the previous page instead.
        //     // window.location = document.referrer // Note: IE11 is not supporting this.
        // } else {
        //     // Stay on the current page.
        //     history.pushState(null, null, window.location.pathname);
        // }

        // history.pushState(null, null, window.location.pathname);

    }, false);

    function pepiteWorldGetURLParameter(sParam) {
        var sPageURL = window.location.search.substring(1);
        var sURLVariables = sPageURL.split('&');
        for (var i = 0; i < sURLVariables.length; i++)  {
            var sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == sParam) {
                return sParameterName[1];
            }
        }
    }

    function initializeDrag(container) {
        container.
        // css({
        //     'display': 'block',
        //     'width': '100%',
        //     'height': '100%',
        //     'overflow': 'hidden'
        // }).
        // show(). 
        find('article').css('height',  '95%');

        var w = window.innerWidth -  (window.innerWidth*60)/100;
        var h = window.innerHeight - (window.innerHeight*30)/100;
        var images = jQuery('.draggable').each(function(){
            $(this).css({
                left: Math.floor( Math.random() * w ),
                top:  Math.floor( Math.random() * h )
            });
        });
        images.draggable({
            stack: ".draggable",
            containment: "parent"
        });
    };

    function synchroniseMenuStyles(id) {
        $('li#item-'+id).toggleClass('is-current-item');
        $('.infobar [data-modal-open]').data('modal-open', "modal-"+id);

    }
    
    /** Hovers */
    $("body").on('mouseover', '.pepite-tab-container .content a[rel]', function(e){
        var rel = $(this).attr('rel');
        
        if (rel.indexOf("social-network") > -1) {
            $('#a-rel-'+rel).appendTo($(this)).fadeIn(200);
        }

        else if (rel.indexOf("heart") > -1) {
            $(this).find('span').fadeIn(200);
        }

        else if (rel.indexOf("mailto") > -1) {
            var email = $(this).text();
            console.log(email);
            $(this).click((e)=>{
                window.open("mailto:"+email);
            });
        }
    });
    $("body").on('mouseleave', '.pepite-tab-container .content *[id |="a-rel"]', function(e){
        $(this).fadeOut(200);
    });
    $("body").on('mouseleave', '.pepite-tab-container .content a[rel="heart"]', function(e){
        $(this).find('span').fadeOut(200);
    });

    /** fontsampler */
    $("body").on("fontsampler.event.afterinit", ".fontsampler-wrapper", (e)=>{
        // move buttons to nav bar
        var buy = $(e.currentTarget).find('[data-block=buy] span').appendTo('.content .infobar .support-us');
        var specimen = $(e.currentTarget).find('[data-block=specimen] span').appendTo('.content .infobar .specimen');
        console.log(buy); 
    });

});