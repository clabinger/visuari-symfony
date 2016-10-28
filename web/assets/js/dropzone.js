var dropzone_count = 0;

Dropzone.options.uploadDropzone = {
	maxFilesize: 30, // MB	
	acceptedFiles: 'image/*',	
	sending: function(file, xhr, formData){
		var album = $('form.dropzone input.uploader_album_id').val();
		formData.append("album", album);
		formData.append("file_name", file.name);
		formData.append("file_modified_date", file.lastModified);
		formData.append("upload_count", ++dropzone_count);
    },
};