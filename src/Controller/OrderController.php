<?php
namespace YourVendor\Component\Photoorders\Site\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Uri\Uri;

class OrderController extends BaseController
{
    public function submit()
    {
        $app = Factory::getApplication();
        $input = $app->getInput();
        $db = Factory::getContainer()->get('DatabaseDriver');

        $albumId = $input->getInt('album_id', 0);
        $selectedPhotos = $input->get('photos', [], 'array');

        $orderData = [];

        foreach ($selectedPhotos as $relativePath => $requestedSize) {
            if (!empty($requestedSize)) {
                $orderData[] = [
                    'filename'      => basename($relativePath),
                    'relative_path' => $relativePath,
                    'size'          => $requestedSize
                ];
            }
        }

        if (!empty($orderData) && $albumId > 0) {
            $order = new \stdClass();
            $order->album_id    = $albumId;
            $order->client_name = 'Client Order';
            $order->order_data  = json_encode($orderData);
            $order->created_at  = Factory::getDate()->toSql();

            $db->insertObject('#__photoorders_orders', $order);

            $app->enqueueMessage('Your print order has been placed successfully!', 'message');
        } else {
            $app->enqueueMessage('No prints were selected.', 'warning');
        }

        $app->redirect(Uri::root() . 'index.php?option=com_photoorders&view=album&id=' . $albumId);
    }
}