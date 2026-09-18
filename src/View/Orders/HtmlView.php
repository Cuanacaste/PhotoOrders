<?php
namespace YourVendor\Component\Photoorders\Site\View\Orders;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

class HtmlView extends BaseHtmlView
{
    public array $orders = [];

    public function display($tpl = null): void
    {
        $model = $this->getModel();
        $this->orders = $model->getOrders();

        parent::display($tpl);
    }
}