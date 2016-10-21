<?php

namespace AppBundle\Utils;

use Aws\S3\S3Client;
use Doctrine\Common\Persistence\ObjectManager;

class S3SignedUrlCacher
{

	public function __construct(S3Client $s3_client, ObjectManager $om){
		$this->s3_client = $s3_client;
		$this->om = $om;
	}

	public function getUrl($key){
		
        $cmd = $this->s3_client->getCommand('GetObject', [
            'Bucket' => 'photoalbums2',
            'Key'    => $key
        ]);

        $request = $this->s3_client->createPresignedRequest($cmd, '+24 hours');

        return (string) $request->getUri();

	}
}