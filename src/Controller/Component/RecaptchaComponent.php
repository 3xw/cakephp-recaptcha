<?php
namespace Trois\Recaptcha\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Event\Event;
use Exception;
use ReCaptcha\ReCaptcha;

class RecaptchaComponent extends Component
{

  protected $_defaultConfig = [
    'ScoreThreshold' => 0.5,
    'ExpectedHostname' => null,
    'ExpectedAction' => null,
    'ChallengeTimeout' => null
  ];

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

    $recaptcha = new ReCaptcha(Configure::read('Trois/Recaptcha.secret'));
    foreach($this->getConfig() as $key => $value) if($value) $recaptcha->{"set$key"}($value);

    $resp = $recaptcha->verify($gRecaptchaResponse, $this->getController()->getRequest()->clientIp());

    // handle erros
    if(!$resp->isSuccess())
    {
      $this->errors = $resp->getErrorCodes();
      return false;
    }

    return true;
  }
}
