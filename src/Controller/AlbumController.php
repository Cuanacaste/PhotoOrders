<?php
namespace YourVendor\Component\Photoorders\Site\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;

/**
 * Album Controller for Photoorders Component
 */
class AlbumController extends BaseController
{
    /**
     * Default display method
     *
     * @param   boolean  $cachable   If true, the view output will be cached
     * @param   array    $urlparams  An array of safe URL parameters and their variable types
     *
     * @return  BaseController|boolean  This object to support chaining or false on failure
     */
    public function display($cachable = false, $urlparams = array())
    {
        $app = Factory::getApplication();
        $id  = $app->input->getInt('id', 0);

        // Set the default view if not set
        $vName = $app->input->getCmd('view', 'album');
        $app->input->set('view', $vName);

        return parent::display($cachable, $urlparams);
    }
}