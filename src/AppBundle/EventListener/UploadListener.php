<?php

namespace AppBundle\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use League\Flysystem\Filesystem;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Translation\DataCollectorTranslator;


use Imagine\Imagick\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Image\ImageInterface;


use AppBundle\Entity\Photo;

class UploadListener
{
    /**
     * @var ObjectManager
     */
    private $om;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, ObjectManager $om, DataCollectorTranslator $translator, Filesystem $filesystem, Filesystem $filesystem_local, Imagine $imager, $local_photos_directory, $size_thumb, $size_medium, $extensions_allowed)
    {
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
        
        $file = $event->getFile();
        $filename = $file->getBasename();
        $extension = $file->getExtension();

        if(!in_array(strtoupper($extension), $this->extensions_allowed)){
            throw new UploadException($this->translator->trans('upload.error.file_format_not_supported'));
        }


        $filename_thumb = substr($filename, 0, strlen($filename)-strlen($extension)-1).'_s.'.$extension;
        $filename_medium = substr($filename, 0, strlen($filename)-strlen($extension)-1).'_m.'.$extension;
        
        // file_put_contents('/tmp/test.txt', implode("\n", get_class_methods($file)), FILE_APPEND);

        $request = $event->getRequest();
        $album = $this->om->getRepository('AppBundle:Album')
        ->find( $request->get('album') );

        if(false === $this->authorizationChecker->isGranted('edit', $album)){
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

        file_put_contents('/tmp/test.txt', $file_original_modified, FILE_APPEND);

        file_put_contents('/tmp/test.txt', "\n\n", FILE_APPEND);


        // End For Testing

        function resize($imager, $image_in, $image_out, $width, $height){

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

            return true;

        }
  
        // Create thumbnail and save locally
        resize(
            $this->imager,
            $this->local_photos_directory.'/'.$filename,
            $this->local_photos_directory.'/'.$filename_thumb,
            $this->size_thumb,
            $this->size_thumb
        );

        // Create medium size and save locally
        resize(
            $this->imager,
            $this->local_photos_directory.'/'.$filename,
            $this->local_photos_directory.'/'.$filename_medium,
            $this->size_medium,
            $this->size_medium
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




        $photo = new Photo();

        $photo->setSizeOriginal($filename);
        $photo->setSizeMedium($filename_medium);
        $photo->setSizeThumb($filename_thumb);

        $photo->setOriginalFilename($file_original_name);

        // Check if a valid unix timestamp, then create DateTime and put in database.
        if((string)(int)$file_original_modified = $file_original_modified){
            $file_original_modified = new \DateTime('@'.round($file_original_modified/1000));
            $photo->setOriginalModified($file_original_modified);
        }

        $this->om->persist($photo);
        $this->om->flush();

    }
}