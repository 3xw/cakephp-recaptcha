<?php
namespace Trois\Recaptcha\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Event\Event;
use Exception;
use ReCaptcha\ReCaptcha;

class RecaptchaComponent extends Component
{

  public function getErrors()
  {
    return $this->errors;
  }

  public function startup(Event $event)
  {
    $secret = Configure::read('Trois/Recaptcha.secret');
    if (empty($secret)) throw new Exception(__d('Trois/Recaptcha', "You must set the secret Recaptcha key in config/recaptcha.php file"));
    if (!isset($this->getController()->helpers['Trois/Recaptcha.Recaptcha'])) $this->getController()->helpers[] = 'Trois/Recaptcha.Recaptcha';
  }

  /**
  * Verify Response
  *
  * @return bool
  */
  public function verify()
  {
    if(!$gRecaptchaResponse = $this->getController()->getRequest()->getData('g-recaptcha-response'))
    {
      $this->errors = [new Exception('Empty g-recaptcha-response')];
      return false;
    }

    $resp = (new ReCaptcha(Configure::read('Trois/Recaptcha.secret')))
    //->setExpectedHostname('recaptcha-demo.appspot.com')
    //->setExpectedAction('homepage')
    ->setScoreThreshold(0.5)
    ->verify($gRecaptchaResponse/*, $remoteIp*/);

    // handle erros
    if(!$resp->isSuccess())
    {
      $this->errors = $resp->getErrorCodes();
      return false;
    }

    return true;
  }
}
