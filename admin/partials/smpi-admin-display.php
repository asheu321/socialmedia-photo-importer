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
    $medias = $instagram->getPaginateMedias('nike');

    foreach ( $medias['medias'] as $media ) {
        echo '<img style="max-width:150px;"  src="' . $media->getImageThumbnailUrl() . '">';
    }
} catch (InstagramScraper\Exception\InstagramNotFoundException $e) {
    echo $e->getMessage();
}

echo "HasNextPage: {$medias['hasNextPage']}" . PHP_EOL;
echo "MaxId: {$medias['maxId']}" . PHP_EOL;

if ($medias['hasNextPage'] === true) {
    $result = $instagram->getPaginateMedias('nike', 'QVFCbjEtV1hYa0REU0VtS3g0QjlIck12T0I3LVY1TEE0QzAybVVpdnlzS1BPNm0xTmZyODBCazRTam5oeHRka2FpZDRsakJJNGhMNWk0bXJGS3Y4WjgxMw==');
    foreach ( $result['medias'] as $res ) {
        echo '<img style="max-width:150px;"  src="' . $res->getImageThumbnailUrl() . '">';
    }
echo "HasNextPage:" . $result['hasNextPage'];
echo "MaxId:" . $result['maxId'];
echo '<pre>';
print_r($result);
echo '</pre>';
}

