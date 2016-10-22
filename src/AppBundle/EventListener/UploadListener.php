<?php

namespace AppBundle\EventListener;



use Doctrine\Common\Persistence\ObjectManager;
use League\Flysystem\Filesystem;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;


use Imagine\Imagick\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Image\ImageInterface;


use AppBundle\Entity\Photo;
use AppBundle\Entity\Album_Photo;

class UploadListener
{
    /**
     * @var ObjectManager
     */
    private $om;

    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker, 
        ObjectManager $om, 
        Translator $translator, 
        Filesystem $filesystem, 
        Filesystem $filesystem_local, 
        Imagine $imager, 
        $local_photos_directory, 
        $size_thumb, 
        $size_medium, 
        $extensions_allowed
    ){
        $this->authorizationChecker = $authorizationChecker;
        $this->om = $om;
        $this->translator = $translator;
        $this->filesystem = $filesystem;
        $this->filesystem_local = $filesystem_local;
        $this->imager = $imager;
        $this->local_photos_directory = $local_photos_directory;
        $this->size_thumb = $size_thumb;
        $this->size_medium = $size_medium;
        $this->extensions_allowed = $extensions_allowed;
    }

    public function onUpload(PostPersistEvent $event)
    {
        
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300);


        $file = $event->getFile();
        $filename = $file->getBasename();
        $extension = $file->getExtension();

        $dimensions = Array(); // Used to store dimensions of original and resized photos until they are written to the database.

        if(!in_array(strtoupper($extension), $this->extensions_allowed)){
            $this->filesystem_local->delete($filename);
            throw new UploadException($this->translator->trans('upload.error.file_format_not_supported'));
        }


        $filename_thumb = substr($filename, 0, strlen($filename)-strlen($extension)-1).'_s.'.$extension;
        $filename_medium = substr($filename, 0, strlen($filename)-strlen($extension)-1).'_m.'.$extension;
        
        // file_put_contents('/tmp/test.txt', implode("\n", get_class_methods($file)), FILE_APPEND);

        $request = $event->getRequest();
        

        try{
            $album = $this->om->getRepository('AppBundle:Album')->find( $request->get('album') );
        }catch(\Doctrine\ORM\ORMException $e){
            $this->filesystem_local->delete($filename);
            throw new UploadException($this->translator->trans('upload.error.album_not_found'));
        }

        try{
            $album_max_position = $this->om->getRepository('AppBundle:Album_Photo')->getMaxPosition($album);
        }catch(\Doctrine\ORM\ORMException $e){
            $this->filesystem_local->delete($filename);
            throw new UploadException($this->translator->trans('upload.error.album_not_initialized'));
        }

        if(false === $this->authorizationChecker->isGranted('edit', $album)){
            $this->filesystem_local->delete($filename);
            throw new UploadException($this->translator->trans('album.edit_not_allowed_photos'));
        }


        $file_original_name = $request->get('file_name');
        $file_original_modified = $request->get('file_modified_date');


        // For Testing:
        // // file_put_contents('/tmp/test.txt', $event->getRequest(), FILE_APPEND);
        // // file_put_contents('/tmp/test.txt', $event->getResponse(), FILE_APPEND);
        // file_put_contents('/tmp/test.txt', $file->getPathname()."\n", FILE_APPEND);
        // file_put_contents('/tmp/test.txt', $file->getBasename()."\n", FILE_APPEND);
        // file_put_contents('/tmp/test.txt', print_r($file->getMetadata(), true)."\n", FILE_APPEND);
        // file_put_contents('/tmp/test.txt', "\n\nALBUM: ".$album, FILE_APPEND);
        // file_put_contents('/tmp/test.txt', "\n\n".$this->local_photos_directory, FILE_APPEND);
        // file_put_contents('/tmp/test.txt', implode("\n", get_class_methods($file)), FILE_APPEND);
        // file_put_contents('/tmp/test.txt', $file_original_modified, FILE_APPEND);
        // file_put_contents('/tmp/test.txt', "\n\n", FILE_APPEND);
        // End For Testing

        function resize($imager, $image_in, $image_out, $width, $height, &$input_width=null, &$input_height=null, &$output_width=null, &$output_height=null){

            $image = $imager->open($image_in);

            $orig_width = $image->getSize()->getWidth();
            $orig_height = $image->getSize()->getHeight();

            if($orig_width>$orig_height){
                $new_width = $width;
                $new_height = ($new_width / $orig_width) * $orig_height;
            }else{
                $new_height = $height;
                $new_width = ($new_height / $orig_height) * $orig_width;
            }

            if($new_width>$orig_width){
                $new_width = $orig_width;
                $new_height = $orig_height;
            }

            $image
                ->resize(new Box( $new_width, $new_height ), ImageInterface::FILTER_LANCZOS)
                ->save($image_out)
                ;

            $input_width = $orig_width;
            $input_height = $orig_height;
            $output_width = $new_width;
            $output_height = $new_height;

            return true;

        }
  
        // Create thumbnail and save locally
        resize(
            $this->imager,
            $this->local_photos_directory.'/'.$filename,
            $this->local_photos_directory.'/'.$filename_thumb,
            $this->size_thumb,
            $this->size_thumb,
            $dimensions['original']['width'],
            $dimensions['original']['height'],
            $dimensions['thumb']['width'],
            $dimensions['thumb']['height']
        );

        // Create medium size and save locally
        resize(
            $this->imager,
            $this->local_photos_directory.'/'.$filename,
            $this->local_photos_directory.'/'.$filename_medium,
            $this->size_medium,
            $this->size_medium,
            $dimensions['original']['width'],
            $dimensions['original']['height'],
            $dimensions['medium']['width'],
            $dimensions['medium']['height']
        );
        


        // Read local copies into memory
        $contents = $this->filesystem_local->read($filename);
        $contents_thumb = $this->filesystem_local->read($filename_thumb);
        $contents_medium = $this->filesystem_local->read($filename_medium);


        // Upload to remote filesystem
        $this->filesystem->put($filename, $contents);
        $this->filesystem->put($filename_thumb, $contents_thumb);
        $this->filesystem->put($filename_medium, $contents_medium);

        // Delete local copies
        $contents = $this->filesystem_local->delete($filename);
        $contents = $this->filesystem_local->delete($filename_thumb);
        $contents = $this->filesystem_local->delete($filename_medium);


        // Start create photo

        $photo = new Photo();

        $photo->setSizeOriginal($filename);
        $photo->setSizeMedium($filename_medium);
        $photo->setSizeThumb($filename_thumb);
        
        $photo->setOriginalWidth($dimensions['original']['width']);
        $photo->setOriginalHeight($dimensions['original']['height']);

        $photo->setMediumWidth($dimensions['medium']['width']);
        $photo->setMediumHeight($dimensions['medium']['height']);

        $photo->setThumbWidth($dimensions['thumb']['width']);
        $photo->setThumbHeight($dimensions['thumb']['height']);


        $photo->setOriginalFilename($file_original_name);

        // Check if a valid unix timestamp, then create DateTime and put in database.
        if((string)(int)$file_original_modified = $file_original_modified){
            $file_original_modified = new \DateTime('@'.round($file_original_modified/1000));
            $photo->setOriginalModified($file_original_modified);
        }

        $this->om->persist($photo);

        // End create photo



        // Start add photo to album

        $album_photo = new Album_Photo();

        $album_photo->setPhoto($photo);
        $album_photo->setAlbum($album);
        $album_photo->setPosition($album_max_position + 1);

        $this->om->persist($album_photo);

        // End add photo to album


        $this->om->flush();

    }
}