<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

namespace Nos\Slideshow;

class Controller_Admin_enhancer extends \Nos\Controller_Admin_Enhancer
{

    public function action_popup()
    {
        $this->config['popup']['params']['slideshows'] = Model_Slideshow::find('all', array(
            'order_by' => array('slideshow_title' => 'asc'),
        ));

        \Config::load('noviusos_slideshow::slideshow', 'slideshow');
        $this->config['popup']['params']['sizes'] = \Config::get('slideshow.sizes');

        return parent::action_popup();
    }

    public function action_save(array $args = null)
    {
        \Config::load('noviusos_slideshow::slideshow', 'slideshow');
        $sizes = \Config::get('slideshow.sizes');

        $params = array();
        $params['src'] = Model_Image::find()->where('slidimg_slideshow_id', $_POST['slideshow_id'])->get_one()->medias->image->get_public_path_resized(100, 40);
        $params['title'] = Model_Slideshow::find($_POST['slideshow_id'])->slideshow_title;
        $params['size'] = !empty($_POST['size']) ? $_POST['size'] : current(array_keys($sizes));
        $body = array(
            'config'  => \Format::forge()->to_json($_POST),
            'preview' => \View::forge($this->config['preview']['view'], $params)->render(),
        );
        \Response::json($body);
    }

}