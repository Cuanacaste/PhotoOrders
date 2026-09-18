<?php
namespace YourVendor\Component\Photoorders\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;

class OrdersModel extends ListModel
{
    public function getOrders(): array
    {
        $db = Factory::getContainer()->get('DatabaseDriver');
        $query = $db->getQuery(true)
            ->select('o.*, a.title AS album_title')
            ->from($db->quoteName('#__photoorders_orders', 'o'))
            ->join('LEFT', $db->quoteName('#__photoorders_albums', 'a') . ' ON o.album_id = a.id')
            ->order('o.created_at DESC');

        $db->setQuery($query);
        return $db->loadObjectList() ?: [];
    }
}