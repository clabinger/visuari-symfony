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
$('div.gallery-wrapper').each(function(){
	
	var pic = $(this);
	
	var getItems = function() {
        var items = [], 
        	elements = [];
        pic.find('a').each(function() {
            var href   = $(this).attr('href'),
            	src    = $(this).find('img').attr('src'),
                size   = $(this).data('size').split('x'),
                caption= $(this).closest('figure').find('figcaption').text(),
                width  = size[0],
                height = size[1];

            var item = {
                src : href,
                msrc: src,
                w   : width,
                h   : height,
                title: caption
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
	
	pic.on('click', 'div.column', function(event) {
	    event.preventDefault();
	     
	    var index = $(this).index();
	    var options = {
	        index: index,
	        bgOpacity: 0.7,
	        // showHideOpacity: true,
	        getThumbBoundsFn: function(index) {
                // See Options ->  section of documentation for more info
                var thumbnail = elements[index].children('img')[0], // find thumbnail
                    pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
                    rect = thumbnail.getBoundingClientRect(); 

                return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
            },
            shareButtons: [
			    {id:'download', label:'Download image', url:'{{raw_image_url}}', download:true}
			],
	    }
	     
	    // Initialize PhotoSwipe
	    var lightBox = new PhotoSwipe(pswp, PhotoSwipeUI_Default, items, options);
	    lightBox.init();
	});


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
