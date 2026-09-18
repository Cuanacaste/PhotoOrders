<?php
namespace YourVendor\Component\Photoorders\Site\View\Album;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

class HtmlView extends BaseHtmlView
{
    public $album;
    public array $photos = [];
    public bool $isAuthenticated = false;

    public function display($tpl = null): void
    {
        $app = Factory::getApplication();
        $input = $app->getInput();
        $session = Factory::getSession();

        $albumId = $input->getInt('id', 1);
        $model = $this->getModel();

        $this->album = $model->getAlbum($albumId);

        if ($this->album) {
            // Check password submission or session status
            $submittedPassword = $input->post->getString('album_password', '');
            $sessionKey = 'photoorders_auth_' . $this->album->id;

            if ($submittedPassword !== '' && $submittedPassword === $this->album->password) {
                $session->set($sessionKey, true);
            }

            $this->isAuthenticated = (bool) $session->get($sessionKey, false);

            if ($this->isAuthenticated) {
                $this->photos = $model->getPhotos($this->album->media_folder);
            }
        }

        parent::display($tpl);
    }
}