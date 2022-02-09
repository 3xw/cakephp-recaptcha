<?php
namespace Trois\Recaptcha\View\Helper;

use Cake\Core\Configure;
use Cake\View\View;
use Cake\View\Helper;

class RecaptchaHelper extends Helper
{
  public function initialize(array $config)
  {
    $siteKey =  $this->setConfig(Configure::read('Trois/Recaptcha'))
    ->setConfig($config)
    ->getConfig('sitekey');

    // add elements
    $this->getView()->Html->script(['https://www.google.com/recaptcha/api.js?render='.$siteKey],['block' => 'script']);
    $this->getView()->append('script', $this->getView()->element('Trois/Recaptcha.script', ['siteKey' => $siteKey]));
  }

  public function display()
  {
    echo $this->getView()->Form->unlockField('g-recaptcha-response');
    echo $this->getView()->Form->input('g-recaptcha-response',['class' => 'g-recaptcha-response','type' => 'hidden']);
  }
}
