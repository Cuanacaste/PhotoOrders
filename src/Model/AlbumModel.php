<?php
namespace YourVendor\Component\Photoorders\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\CMS\Uri\Uri;

class AlbumModel extends ItemModel
{
    public function getAlbum(int $id)
    {
        $db = Factory::getContainer()->get('DatabaseDriver');
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__photoorders_albums'))
            ->where($db->quoteName('id') . ' = ' . (int) $id);

        $db->setQuery($query);
        return $db->loadObject();
    }

    public function getPhotos(string $mediaFolder): array
    {
        $photos = [];
        
        // Clean folder reference (e.g., converts 'local-images:/client_a' to 'client_a')
        $cleanPath = str_replace('local-images:', '', $mediaFolder);
        $cleanPath = trim($cleanPath, '/');

        $fullPath = JPATH_ROOT . '/images/' . $cleanPath;

        if (is_dir($fullPath)) {
            $files = scandir($fullPath);
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];

            foreach ($files as $file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($ext, $allowedExts)) {
                    $relativePath = $cleanPath . '/' . $file;
                    $photos[] = [
                        'name'          => $file,
                        'relative_path' => $relativePath,
                        'url'           => Uri::root() . 'images/' . ltrim($relativePath, '/')
                    ];
                }
            }
        }

        return $photos;
    }
}