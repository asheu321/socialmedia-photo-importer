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
    $account = $instagram->getAccount('priyanto.agus393');
    // Available fields
    echo "Account info:<br/>";
    echo "Id: {$account->getId()}<br/>";
    echo "Username: {$account->getUsername()}<br/>";
    echo "Full name: {$account->getFullName()}<br/>";
    echo "Biography: {$account->getBiography()}<br/>";
    echo "Profile picture url: {$account->getProfilePicUrl()}<br/>";
    echo "External link: {$account->getExternalUrl()}<br/>";
    echo "Number of published posts: {$account->getMediaCount()}<br/>";
    echo "Number of followers: {$account->getFollowsCount()}<br/>";
    echo "Number of follows: {$account->getFollowedByCount()}<br/>";
    echo "Is private: {$account->isPrivate()}<br/>";
    echo "Is verified: {$account->isVerified()}<br/>";
    echo "Media: {count($account->getMedias())}<br/>";
    echo '<pre>';
    print_r($account);
    echo '</pre>';
    
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
