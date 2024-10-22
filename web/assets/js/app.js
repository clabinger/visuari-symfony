// Manage reordering of images in album
$('div.reorder-photos').children('div.row').addClass('reorder-photo');

$('a.reorder-photo').on('click', function(){
	var target = $(this).closest('div.reorder-photo');

	if($(this).hasClass('reorder-photo-up')){
		target.insertBefore(target.prev());
	}else{
		target.insertAfter(target.next());
	}

	$('input.reorder-position').each(function(index){
		index += 1; // Make 1-indexed instead of 0-indexed
		$(this).val(index);
	});

});


// Manage deleting photos from an album
$('a.delete-photo').on('click', function(){
    
    var target = $(this).closest('div.reorder-photo');

    var img = $('<img>').attr('src', target.find('img').attr('src') );
    
    $('#confirmDeleteModal').find('p.img-container').html(img);

    $('#confirmDeleteModal').foundation('open');

    $('#confirmDeleteModal').find('button.delete-cancel').on('click', function(){
        $('#confirmDeleteModal').foundation('close');
    });

    $('#confirmDeleteModal').find('button.delete-confirm').on('click', function(){
        target.remove(); 
        $('#confirmDeleteModal').foundation('close');
    });

});



// Set up thumbnails for PhotoSwipe
$('div.gallery-wrapper').each(function(gallery_index){
	
	var gallery = $(this);
	
    var extract_id = function(filename){

        // Take the full S3 url of the photo and return just the filename (without extension)

        q = filename.indexOf('?');        
        if(q){
            filename = filename.substring(0,q);
        }

        s = filename.lastIndexOf('/');
        if(s){
            filename = filename.substring(s+1);
        }

        p = filename.lastIndexOf('.');
        if(s){
            filename = filename.substring(0,p);
        }

        return filename;
    }

	var getItems = function() {
        var items = [], 
        	elements = [];
        gallery.find('a').each(function() {
            var src_medium   = $(this).attr('href'), // medium size
                src_thumb    = $(this).find('img').attr('src'), // thumb size
            	src_orig    = $(this).attr('fhref'), // full size
                size_medium   = $(this).data('size').split('x'),
                size_orig   = $(this).data('fsize').split('x'),
                caption= $(this).closest('figure').find('figcaption').text(),
                width_medium  = size_medium[0],
                height_medium = size_medium[1],
                width_orig = size_orig[0],
                height_orig = size_orig[1];

            var item = {
                src : src_medium, // PhotoSwipe-dictated key
                msrc: src_thumb, // PhotoSwipe-dictated key
                w   : width_medium, // PhotoSwipe-dictated key
                w_full   : width_orig, 
                h   : height_medium, // PhotoSwipe-dictated key
                h_full   : height_orig, 
                title: caption, // PhotoSwipe-dictated key
                pid: extract_id(src_orig) // PhotoSwipe-dictated key
            }

            if(size_orig[0]!==size_medium[0]){
                item.fsrc = src_orig; // full size, if different than medium size - PhotoSwipe-dictated key
            }

            items.push(item);
            elements.push($(this));
        });
        return [items, elements];
    }

    var items_elements = getItems();
    var items = items_elements[0];
    var elements = items_elements[1];
    
    var pswp = $('.pswp')[0];
	
    var showImage = function(index){

        var shareButtons = [
                {id:'copy_url', label:'Copy URL to clipboard', url:'{{raw_url}}', action:'copy'},
                {id:'download', label:'Download image ('+items[index].w+'x'+items[index].h+')', url:'{{raw_image_url}}', download:true},
        ];

        if(items[index]['fsrc']){
            shareButtons.push(
                {id:'download-full', label:'Download full-resolution image ('+items[index].w_full+'x'+items[index].h_full+')', url:'{{raw_full_image_url}}', download:true}
            );
        }


        var options = {
            index: index,
            bgOpacity: 0.7,
            galleryPIDs: true,
            // showHideOpacity: true,
            getThumbBoundsFn: function(index) {
                // See Options ->  section of documentation for more info
                var thumbnail = elements[index].children('img')[0], // find thumbnail
                    pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
                    rect = thumbnail.getBoundingClientRect(); 

                return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
            },
            shareButtons: shareButtons,
        }
        

        // Initialize PhotoSwipe
        var lightBox = new PhotoSwipe(pswp, PhotoSwipeUI_Default, items, options);
        lightBox.init();
    
    }

	gallery.on('click', 'figure a', function(event) {
	    event.preventDefault();
	     
	    var index = $(this).closest('div.column').index();

        showImage(index);
	       
	});

    // parse picture index and gallery index from URL (#&pid=1&gid=2)
    var photoswipeParseHash = function() {
        var hash = window.location.hash.substring(1),
        params = {};

        if(hash.length < 5) {
            return params;
        }

        var vars = hash.split('&');
        for (var i = 0; i < vars.length; i++) {
            if(!vars[i]) {
                continue;
            }
            var pair = vars[i].split('=');  
            if(pair.length < 2) {
                continue;
            }           
            params[pair[0]] = pair[1];
        }

        if(params.gid) {
            params.gid = parseInt(params.gid, 10);
        }

        return params;
    };

    // Parse URL and open gallery if it contains #&pid=3&gid=1
    var hashData = photoswipeParseHash();

    if(hashData.pid && hashData.gid && gallery_index+1===hashData.gid) { // There is a pid in the URL for this gallery

        if(
            (index = items.findIndex(function(item){ return item.pid===hashData.pid; })) !== -1 // Image was found with the provided pid
        ){
            showImage(index);
        }
    }

});



// Generic autofocusing
$('.autofocus').first().each(function(){

    // Make sure user hasn't already filled it out
    if($(this).val()===''){
        $(this).focus();
    }else{

        // Focus on secondary field, such as a password field
        $('.autofocus_2').first().focus();

    }
});


// Manage deleting permissions
var delete_permission_buttons = function(){
    $('a.delete-permission').off().on('click', function(){
        
        var target = $(this).closest('div.permission-row');

        $('#confirmDeleteModal').foundation('open');

        $('#confirmDeleteModal').find('button.delete-cancel').on('click', function(){
            $('#confirmDeleteModal').foundation('close');
        });

        $('#confirmDeleteModal').find('button.delete-confirm').on('click', function(){
            target.remove(); 
            $('#confirmDeleteModal').foundation('close');
        });

    });
};

delete_permission_buttons();

// Add new permission
$('#add_new_permission').click(function(e) {
    e.preventDefault();

    var permissionsList = $('#item_permissions_permissions');
    
    var permissionsCount = permissionsList.children().length;

    // grab the prototype template
    var newWidget = permissionsList.attr('data-prototype');
    // replace the "__name__" used in the id and name of the prototype
    // with a number that's unique to your emails
    // end name attribute looks like name="contact[emails][2]"
    newWidget = newWidget.replace(/__name__/g, permissionsCount);
    permissionsCount++;

    // add the new blank permission it to the list
    permissionsList.prepend(newWidget);

delete_permission_buttons();
    

});
