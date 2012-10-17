<?php
/**
 * Contacts Controller
 *
 * PHP version 5
 *
 * @category Controller
 * @package  Croogo
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class ContactsController extends AppController {
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Contacts';

    /**
     * Components
     *
     * @var array
     * @access public
     */
    public $components = array(
        'Akismet',
        'Email',
        'Recaptcha',
    );

    /**
     * Models used by the Controller
     *
     * @var array
     * @access public
     */
    public $uses = array('Contact');

    public function admin_index() {
        $this->set('title_for_layout', __('Contacts', true));

        $this->Contact->recursive = 0;
        $this->paginate['Contact']['order'] = 'Contact.title ASC';
        $this->set('contacts', $this->paginate());
    }

    public function admin_add() {
        $this->set('title_for_layout', __('Add Contact', true));

        if (!empty($this->data)) {
            $this->Contact->create();
            if ($this->Contact->save($this->data)) {
                $this->Session->setFlash(__('The Contact has been saved', true), 'default', array('class' => 'success'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The Contact could not be saved. Please, try again.', true), 'default', array('class' => 'error'));
            }
        }
    }

    public function admin_edit($id = null) {
        $this->set('title_for_layout', __('Edit Contact', true));

        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid Contact', true), 'default', array('class' => 'error'));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->Contact->save($this->data)) {
                $this->Session->setFlash(__('The Contact has been saved', true), 'default', array('class' => 'success'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The Contact could not be saved. Please, try again.', true), 'default', array('class' => 'error'));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Contact->read(null, $id);
        }
    }

    public function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for Contact', true), 'default', array('class' => 'error'));
            $this->redirect(array('action' => 'index'));
        }
        if (!isset($this->params['named']['token']) || ($this->params['named']['token'] != $this->params['_Token']['key'])) {
            $blackHoleCallback = $this->Security->blackHoleCallback;
            $this->$blackHoleCallback();
        }
        if ($this->Contact->delete($id)) {
            $this->Session->setFlash(__('Contact deleted', true), 'default', array('class' => 'success'));
            $this->redirect(array('action' => 'index'));
        }
    }

    public function view($alias = null) {
        if (!$alias) {
            $this->redirect('/');
        }

        $contact = $this->Contact->find('first', array(
            'conditions' => array(
                'Contact.alias' => $alias,
                'Contact.status' => 1,
            ),
            'cache' => array(
                'name' => 'contact_' . $alias,
                'config' => 'contacts_view',
            ),
                ));
        if (!isset($contact['Contact']['id'])) {
            $this->redirect('/');
        }
        $this->set('contact', $contact);

        $continue = true;
        if (!$contact['Contact']['message_status']) {
            $continue = false;
        }
        if (!empty($this->data) && $continue === true) {
            $this->data['Message']['contact_id'] = $contact['Contact']['id'];
            $this->data['Message']['title'] = htmlspecialchars($this->data['Message']['title']);
            $this->data['Message']['name'] = htmlspecialchars($this->data['Message']['name']);
            $this->data['Message']['body'] = htmlspecialchars($this->data['Message']['body']);
            $continue = $this->__validation($continue, $contact);
            $continue = $this->__spam_protection($continue, $contact);
            $continue = $this->__captcha($continue, $contact);
            $continue = $this->__send_email($continue, $contact);

            if ($continue === true) {
                //$this->Session->setFlash(__('Your message has been received.', true));
                //unset($this->data['Message']);

                echo $this->flash(__('Your message has been received...', true), '/');
            }
        }

        $meta_for_description = $this->description('description', 'For general inquiries - contact WeatherPH via E-mail, Tel#:+6327932653  & Fax#:+6328125893');
        $og_image = array('property'=>'og:image','content'=>'http://alpha.weather.com.ph/theme/weatherph/img/logo.png');
        $og_title = array('property'=>'og:title','content'=>'Weather Philippines Foundation');
        $og_description=array('property'=>'og:description','content'=>"For general inquiries - contact WeatherPH via E-mail, Tel#:+6327932653 & Fax#:+6328125893");
        $this->set('title_for_layout', $contact['Contact']['title']);
        $this->set(compact('continue', 'meta_for_description','og_image','og_title','og_description'));
    }

    private function __validation($continue, $contact) {
        if ($this->Contact->Message->set($this->data) &&
                $this->Contact->Message->validates() &&
                $continue === true) {
            if ($contact['Contact']['message_archive'] &&
                    !$this->Contact->Message->save($this->data['Message'])) {
                $continue = false;
            }
        } else {
            $continue = false;
        }

        return $continue;
    }

    private function __spam_protection($continue, $contact) {
        if (!empty($this->data) &&
                $contact['Contact']['message_spam_protection'] &&
                $continue === true) {
            $this->Akismet->setCommentAuthor($this->data['Message']['name']);
            $this->Akismet->setCommentAuthorEmail($this->data['Message']['email']);
            $this->Akismet->setCommentContent($this->data['Message']['body']);
            if ($this->Akismet->isCommentSpam()) {
                $continue = false;
                $this->Session->setFlash(__('Sorry, the message appears to be spam.', true), 'default', array('class' => 'error'));
            }
        }

        return $continue;
    }

    private function __captcha($continue, $contact) {
        if (!empty($this->data) &&
                $contact['Contact']['message_captcha'] &&
                $continue === true &&
                !$this->Recaptcha->valid($this->params['form'])) {
            $continue = false;
            $this->Session->setFlash(__('Invalid captcha entry', true), 'default', array('class' => 'error'));
        }

        return $continue;
    }

    private function __send_email($continue, $contact) {
        if ($contact['Contact']['message_notify'] && $continue === true) {

            /* SMTP Options */
            $this->Email->smtpOptions = array(
                'port' => '25',
                'timeout' => '30',
                'host' => 'smtp.meteomedia.ch',
                'username' => 'mvornhusen@meteomedia.ch',
            );
            
            $this->Email->bcc = array('sgauran@meteomedia.com.ph');

            /* Set delivery method */
            $this->Email->delivery = 'smtp';

            /* Do not pass any args to send() */
            $this->Email->send();

            /* Check for SMTP errors. */
            $this->set('smtp_errors', $this->Email->smtpError);


            $this->Email->to = 'mvornhusen@meteomedia.ch';
            #$this->Email->from = $this->data['Message']['name'] . ' <' . $this->data['Message']['email'] . '>';
            $this->Email->from = 'admin@weather.com.ph';
            $this->Email->subject = '[' . Configure::read('Site.title') . '] ' . $contact['Contact']['title'];
            $this->Email->template = 'contact';

            $this->set('contact', $contact);
            $this->set('message', $this->data);
            if ($this->Email->send()) {
                $continue = true;
            } else {
                $continue = false;
            }
        }

        return $continue;
    }

}
