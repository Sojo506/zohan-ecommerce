<?php

use Cloudinary\Cloudinary;

class CloudinaryService
{
    private Cloudinary $cloudinary;

    public function __construct()
    {
        // Centraliza la configuración del SDK para que el controlador solo envíe el archivo temporal.
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
                'api_key' => $_ENV['CLOUDINARY_API_KEY'],
                'api_secret' => $_ENV['CLOUDINARY_API_SECRET']
            ],
            'url' => [
                'secure' => true
            ]
        ]);
    }

    public function upload($filePath)
    {
        $result = $this->cloudinary
            ->uploadApi()
            ->upload($filePath);

        // La app guarda únicamente la URL HTTPS final, no el payload completo de Cloudinary.
        return $result['secure_url'];
    }
}
