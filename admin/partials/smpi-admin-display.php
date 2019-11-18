<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://aguspri.com
 * @since      1.0.0
 *
 * @package    Smpi
 * @subpackage Smpi/admin/partials
 */

require_once SMPI_LIB_DIR . 'InstagramMediaScraper/vendor/autoload.php';

require_once SMPI_LIB_DIR . 'InstagramMediaScraper/InstagramScraper.php';

use InstagramScraper\Exception\InstagramException;
use InstagramScraper\Exception\InstagramNotFoundException;


$instagram = new \InstagramScraper\Instagram();

try {
    $medias = $instagram->getMedias('Kholid Basalamah');

    foreach ( $medias['medias'] as $media ) {
        echo '<img style="max-width:150px;"  src="' . $media->getImageThumbnailUrl() . '">';
    }
} catch (InstagramScraper\Exception\InstagramNotFoundException $e) {
    echo $e->getMessage();
}

/*echo "HasNextPage: {$medias['hasNextPage']}" . PHP_EOL;
echo "MaxId: {$medias['maxId']}" . PHP_EOL;

if ($medias['hasNextPage'] === true) {
    $result = $instagram->getPaginateMedias('kevin', $medias['maxId']);
    foreach ( $result['medias'] as $result ) {
        echo '<img style="max-width:150px;"  src="' . $result->getImageThumbnailUrl() . '">';
    }
}*/
